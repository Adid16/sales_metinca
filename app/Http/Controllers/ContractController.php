<?php

namespace App\Http\Controllers;

use App\Notifications\ContractApprovedNotification;
use App\Notifications\ContractNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\Quotation;
use App\Models\User;
use App\Models\Article;
use App\Models\Negotiate;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInternal;
use App\Models\HistoryActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContractExport;

class ContractController extends Controller
{
    /**
     * Export contracts to Excel
     */
    public function export(Request $request)
    {
        $filters = $request->only(['start_date','end_date','status','dept']);
        $filename = 'contracts-' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new ContractExport($filters), $filename);
    }

    public function contract()
    {
        return view('contracts.create');
    }

    public function index(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'status', 'dept', 'search']);

        $query = Contract::query();
        if (Auth::user()->role == 'customer') {
            $query->where('customer_id', '=', Auth::user()->id);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('contract_no', 'like', "%{$search}%")
                  ->orWhere('order_no', 'like', "%{$search}%")
                  ->orWhere('part_no', 'like', "%{$search}%")
                  ->orWhere('part_name', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('quotation', fn($q2) => $q2->where('quotation_no', 'like', "%{$search}%"));
            });
        }

        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['dept'])) {
            $dept = $filters['dept'];
            $query->whereHas('requirements', function ($q) use ($dept) {
                $q->where('requirement_from', $dept);
            });
        }

        $allContracts = $query->with([
            'customer',
            'quotation',
            'internalItem.purchaseOrder',
            'article',
            'requirements'
        ])->latest()->get();

        // Kelompokkan kontrak berdasarkan Purchase Order (PO Utama)
        $grouped = $allContracts->groupBy(function ($contract) {
            if ($contract->internalItem && $contract->internalItem->purchaseOrder) {
                return $contract->internalItem->purchaseOrder->po_no ?? $contract->order_no;
            }
            $cleanPoNo = preg_replace('/-\d+$/', '', $contract->order_no);
            return !empty($cleanPoNo) ? $cleanPoNo : ($contract->quotation->quotation_no ?? 'PO-' . $contract->id);
        });

        // Urutkan grup PO: data yang butuh diproses (ada penolakan manager / masih review / created) di atas, yang sudah selesai (done/production) di bawah
        $grouped = $grouped->sortBy(function ($contractsInGroup) {
            // Prioritas 1: Ada catatan penolakan dari Manager (perlu segera direvisi Sales)
            $hasRejection = $contractsInGroup->contains(function ($c) {
                return !empty($c->sales_reject_reason) || !empty($c->quality_reject_reason) 
                    || !empty($c->ppc_reject_reason) || !empty($c->dev_engineering_reject_reason);
            });
            if ($hasRejection) return 1;

            // Prioritas 2: Status masih dalam proses review / approval manager
            $hasPendingReview = $contractsInGroup->contains(function ($c) {
                return in_array(strtolower($c->status), ['review', 'created', 'amandement', 'amandement_pending', 'waiting_approval', 'draft']);
            });
            if ($hasPendingReview) return 2;

            // Prioritas 3: Semua kontrak dalam PO sudah 100% disetujui / in production
            $allDone = $contractsInGroup->every(function ($c) {
                return in_array(strtolower($c->status), ['production', 'approved', 'done']);
            });
            if ($allDone) return 3;

            return 4;
        });

        // Pagination untuk kumpulan grup PO
        $page = $request->get('page', 1);
        $perPage = 10;
        $contracts = new \Illuminate\Pagination\LengthAwarePaginator(
            $grouped->forPage($page, $perPage),
            $grouped->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('contracts.index', compact('contracts', 'filters'));
    }

    /**
     * Create new contract with specific item context
     */
    public function create(Request $request, $idPO = null)
    {
        // Tangkap ID PO dan ID Item Internal
        $poId = $idPO ?? $request->query('idPO') ?? $request->query('id');
        $internalId = $request->query('internal_id');

        $po = null;
        $selectedItem = null;

        if ($poId) {
            $po = PurchaseOrder::with(['customer', 'quotation', 'internals'])->find($poId);
        }

        if ($internalId) {
            $selectedItem = PurchaseOrderInternal::find($internalId);
            if ($selectedItem && !$po) {
                $po = $selectedItem->purchaseOrder;
            }
        } elseif ($po && $po->internals->count() > 0) {
            $selectedItem = $po->internals->first();
        }

        // =========================================================
        // LOGIKA PEMISAHAN: KONTRAK BARU VS AMANDEMEN (MURNI PER-ITEM)
        // =========================================================
        $latestContract = null;
        $amandementNo = 0;
        $alasanAmandemen = '-';

        // CARI KONTRAK TERDAHULU KHUSUS UNTUK ITEM INI ATAU ORDER_NO PO
        if ($selectedItem) {
            $latestContract = Contract::with('requirements')
                ->where('purchase_order_internal_id', $selectedItem->id)
                ->orderByDesc('amandement_no')
                ->first();
        }

        if (!$latestContract && $po) {
            $latestContract = Contract::with('requirements')
                ->where('order_no', $po->po_no)
                ->orderByDesc('amandement_no')
                ->first();
        }

        // JIKA KONTRAK SUDAH ADA / ADA PENGAJUAN AMANDEMEN
        if ($latestContract) {
            $rawAmandementNo = (int) ($latestContract->amandement_no ?? 0);

            // Ambil alasan amandemen dari kontrak atau po
            $alasanAmandemen = !empty($latestContract->alasan_amandemen) && $latestContract->alasan_amandemen !== '-'
                ? $latestContract->alasan_amandemen 
                : ($po->reason ?? '-');

            // Jika status PO/Kontrak sedang amandemen atau alasan amandemen ada tapi no amandemen masih 0
            if ($rawAmandementNo == 0 && ($po->status === 'amandement_pending' || $po->status === 'amandement' || $alasanAmandemen !== '-')) {
                $amandementNo = 1;
            } else {
                $amandementNo = $rawAmandementNo;
            }
        }

        // Auto resolve article data jika tersedia dari PO Internal atau Quotation Item
        $articleObj = null;
        if ($selectedItem) {
            if (!empty($selectedItem->article)) {
                $articleObj = Article::where('article_no', $selectedItem->article)
                    ->orWhere('internal_part_no', $selectedItem->article)
                    ->first();
            }
            if (!$articleObj && !empty($selectedItem->item)) {
                $articleObj = Article::where('part_name', $selectedItem->item)
                    ->orWhere('article_no', $selectedItem->item)
                    ->orWhere('internal_part_no', $selectedItem->item)
                    ->first();
            }
        }

        // Cek otorisasi Sales PIC
        $user = Auth::user();
        $targetPo = $po ?? ($selectedItem ? $selectedItem->purchaseOrder : null);
        $salesPic = $targetPo?->sales_pic;
        $isPicOrAdmin = $user->isAdmin() || ($user->isManager() && $user->divisi === 'sales') || ($user->isStaff() && $user->divisi === 'sales' && (!$salesPic || $salesPic->id === $user->id));
        if (!$isPicOrAdmin) {
            return redirect()->route('contracts.index')
                ->with('error', 'Akses ditolak. Hanya Sales PIC penanggung jawab (' . ($salesPic->name ?? 'Sales PIC') . ') yang berhak membuat Contract Review Sheet untuk pesanan ini.');
        }

        return view('contracts.create', compact('po', 'selectedItem', 'latestContract', 'amandementNo', 'alasanAmandemen', 'articleObj'));
    }

    public function store(Request $request)
    {
        // ===============================
        // VALIDATION
        // ===============================
        $request->validate([
            'customer_id'                => 'required|exists:users,id',
            'quotation_id'               => 'required|exists:quotations,id',
            'order_no'                   => 'required|string|max:255',
            'purchase_order_internal_id' => 'nullable|exists:purchase_order_internals,id',
            'amandment_no'               => 'nullable|string|max:255',
            'alasan_amandemen'           => 'nullable|string',
            'part_no'                    => 'nullable|string|max:255',
            'part_name'                  => 'nullable|string|max:255',
            'others_comment'             => 'nullable|string',
            'po_pdf'                     => 'nullable|file|mimes:pdf|max:2048',

            'requirements'                      => 'nullable|array',
            'requirements.*.*.requirement_from' => 'required|string|in:sales,quality,ppc,design engineering',
            'requirements.*.*.requirement'      => 'required|string|max:255',
            'requirements.*.*.requirement_value'=> 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $quotation = Quotation::find($request->quotation_id);
        $salesPic = $quotation?->request?->assignment?->sales;
        $isPicOrAdmin = $user->isAdmin() || ($user->isManager() && $user->divisi === 'sales') || ($user->isStaff() && $user->divisi === 'sales' && (!$salesPic || $salesPic->id === $user->id));
        if (!$isPicOrAdmin) {
            return redirect()->route('contracts.index')
                ->with('error', 'Akses ditolak. Hanya Sales PIC penanggung jawab (' . ($salesPic->name ?? 'Sales PIC') . ') yang berhak menyimpan Contract Review Sheet untuk pesanan ini.');
        }

        DB::transaction(function () use ($request) {

            // =========================================================
            // 1. CARI KONTRAK EKSISTING DEDIKASI PER-ITEM
            // =========================================================
            $contract = null;

            if (!empty($request->purchase_order_internal_id)) {
                $contract = Contract::where('purchase_order_internal_id', $request->purchase_order_internal_id)->first();
            } else {
                $contract = Contract::where('order_no', $request->order_no)
                    ->whereNull('purchase_order_internal_id')
                    ->first();
            }

            // =========================================================
            // 2. CEK STATUS KONTRAK (AMANDEMEN VS REVIEW)
            // =========================================================
            $amandmentNo = (int) ($request->amandment_no ?? $request->amendment_no ?? 0);
            $statusTarget = 'review';

            // Siapkan data kontrak
            $dataContract = [
                'customer_id'                => $request->customer_id,
                'quotation_id'               => $request->quotation_id,
                'purchase_order_internal_id' => $request->purchase_order_internal_id, 
                'order_no'                   => $request->order_no,
                'amandement_no'              => $amandmentNo, 
                'alasan_amandemen'           => $request->alasan_amandemen,
                'part_no'                    => $request->part_no,
                'part_name'                  => $request->part_name,
                'others_comment'             => $request->others_comment,
                'article_id'                 => $request->article_id,
                'status'                     => $statusTarget,
            ];

            if ($request->hasFile('po_pdf')) {
                $filePath = $request->file('po_pdf')->store('contracts/po', 'public');
                $dataContract['po_pdf'] = $filePath;
            } elseif ($request->filled('po_attachment_default')) {
                $dataContract['po_pdf'] = $request->input('po_attachment_default');
            }

            // =========================================================
            // 3. SIMPAN ATAU UPDATE DATA KONTRAK
            // =========================================================
            if ($contract) {
                // Reset approval manager jika statusnya amandemen
                if ($amandmentNo > 0) {
                    $dataContract['sales_approver']              = null;
                    $dataContract['ppc_approver']                = null;
                    $dataContract['quality_approver']            = null;
                    $dataContract['dev_engineering_approver']    = null;
                    $dataContract['sales_approved_at']           = null;
                    $dataContract['ppc_approved_at']             = null;
                    $dataContract['quality_approved_at']         = null;
                    $dataContract['dev_engineering_approved_at'] = null;
                }

                $contract->update($dataContract);
                $contract->requirements()->delete();
            } else {
                $contract = Contract::create($dataContract);
            }

            // Update status item internal jika ada
            if ($request->purchase_order_internal_id) {
                PurchaseOrderInternal::where('id', $request->purchase_order_internal_id)
                    ->update(['status' => $statusTarget]);
            }

            // SIMPAN DATA REQUIREMENTS DEPARTEMEN TERBARU
            foreach ($request->requirements ?? [] as $department => $items) {
                foreach ($items as $item) {
                    if (empty($item['requirement'])) {
                        continue;
                    }

                    $contract->requirements()->create([
                        'requirement_from'  => $item['requirement_from'],
                        'requirement'       => $item['requirement'],
                        'requirement_value' => $item['requirement_value'] ?? null,
                    ]);
                }
            }

            HistoryActivity::create([
                'user_id'       => Auth::user()->id,
                'activity'      => 'Memperbarui kontrak untuk PO: ' . $request->order_no . ' Item: ' . ($request->part_name ?? '-'),
                'activity_time' => now()->format('Y-m-d H:i:s')
            ]);

            $manager = User::where('role','=','manager')->get();
            foreach($manager as $usr) {
                $usr->notify(new ContractNotification($contract));
            }
        });

        return redirect()
            ->route('contracts.index')
            ->with('success', 'Data kontrak per-item berhasil disimpan.');
    }

    public function show(Contract $contract)
    {
        $contract->load([
            'customer.account',
            'quotation.request.assignment.sales',
            'quotation.request.attachments',
            'quotation.items.article',
            'quotation.negotiates.user',
            'quotation.negotiates.manager',
            'requirements',
            'article',
            'internalItem.purchaseOrder.internals.contract',
            'internalItem.purchaseOrder.customer',
            'purchaseOrder.internals.contract',
            'purchaseOrder.customer',
            'salesApprover',
            'qualityApprover',
            'ppcApprover',
            'devEngineeringApprover'
        ]);

        $requirements = $contract->requirements;
        $grouped = $requirements->groupBy('requirement_from');

        // ====================================================================
        // AUDIT TRAIL DATA LENGKAP: DARI REQUEST SAMPAI KONTRAK
        // ====================================================================
        $customerId = $contract->customer_id;

        // 1. Total frekuensi riwayat transaksi customer
        $customerTotalPO = PurchaseOrder::where('customer_id', $customerId)->count();
        $customerTotalQuotation = Quotation::where('customer_id', $customerId)->count();
        $customerTotalContracts = Contract::where('customer_id', $customerId)->count();

        // 2. Base PO & Purchase Order Model
        $basePoNo = preg_replace('/-\d+$/', '', $contract->order_no);
        $purchaseOrder = $contract->purchaseOrder 
            ?? ($contract->internalItem ? $contract->internalItem->purchaseOrder : null) 
            ?? PurchaseOrder::where('po_no', $basePoNo)->first();

        // 3. Quotation, Request Project, & Sales PIC
        $quotation = $contract->quotation 
            ?? ($purchaseOrder ? $purchaseOrder->quotation : null) 
            ?? Quotation::find($contract->quotation_id);

        $requestProject = $quotation ? $quotation->request : null;
        $salesPic = $contract->sales_pic 
            ?? ($requestProject && $requestProject->assignment ? $requestProject->assignment->sales : null)
            ?? ($purchaseOrder ? $purchaseOrder->sales_pic : null);

        // 4. Riwayat Seluruh Negosiasi Harga (Multi-Round)
        $negotiations = collect();
        if ($quotation) {
            $negotiations = Negotiate::where('quotation_id', $quotation->id)
                ->with(['user', 'manager'])
                ->orderBy('created_at', 'asc')
                ->get();
        }

        // 5. Rincian Item PO Internal
        $internalItems = $purchaseOrder 
            ? $purchaseOrder->internals()->with('contract')->get() 
            : ($contract->internalItem ? collect([$contract->internalItem]) : collect());

        // 6. Rekap Kontrak Terkait untuk PO Ini
        $relatedContracts = Contract::where(function($q) use ($basePoNo, $contract) {
                $q->where('order_no', 'like', $basePoNo . '%')
                  ->orWhere('id', $contract->id);
            })
            ->with(['internalItem', 'salesApprover', 'qualityApprover', 'ppcApprover', 'devEngineeringApprover', 'requirements'])
            ->orderBy('id', 'asc')
            ->get();

        // 7. Riwayat Lengkap Amandemen
        $amendmentHistory = Contract::where('order_no', 'like', $basePoNo . '%')
            ->where('amandement_no', '>', 0)
            ->with(['internalItem', 'salesApprover', 'qualityApprover', 'ppcApprover', 'devEngineeringApprover'])
            ->orderBy('amandement_no', 'asc')
            ->get();

        // 8. Seluruh Log Aktivitas Sistem Terkait Order Ini
        $orderActivities = HistoryActivity::where(function($q) use ($contract, $basePoNo, $quotation) {
            $q->where('activity', 'LIKE', '%' . $contract->order_no . '%')
              ->orWhere('activity', 'LIKE', '%' . $basePoNo . '%')
              ->orWhere('activity', 'LIKE', '%' . $contract->contract_no . '%');
            if ($quotation) {
                $q->orWhere('activity', 'LIKE', '%' . $quotation->quotation_no . '%');
            }
        })->with('user')->orderBy('activity_time', 'asc')->get();

        // Extract catatan amandemen dari riwayat aktivitas (termasuk yang ditolak/disetujui)
        $amendmentEvents = $orderActivities->filter(function($act) {
            return stripos($act->activity, 'amandemen') !== false;
        });

        return view('contracts.show', compact(
            'contract', 'grouped',
            'customerTotalPO', 'customerTotalQuotation', 'customerTotalContracts',
            'purchaseOrder', 'quotation', 'requestProject', 'salesPic',
            'negotiations', 'internalItems', 'relatedContracts', 'amendmentHistory', 'amendmentEvents', 'orderActivities'
        ));
    }

    public function edit(Contract $contract)
    {
        $user = Auth::user();
        $salesPic = $contract->sales_pic;
        $isPicOrAdmin = $user->isAdmin() || ($user->isManager() && $user->divisi === 'sales') || ($user->isStaff() && $user->divisi === 'sales' && (!$salesPic || $salesPic->id === $user->id));
        if (!$isPicOrAdmin) {
            return redirect()->route('contracts.index')
                ->with('error', 'Akses ditolak. Hanya Sales PIC penanggung jawab (' . ($salesPic->name ?? 'Sales PIC') . ') yang berhak mengedit kontrak ini.');
        }

        $contract->load(['requirements','internalItem']);
        return view('contracts.edit', compact('contract'));
    }

    public function update(Request $request, $id)
    {
        $contract = Contract::findOrFail($id);
        $user = Auth::user();
        $salesPic = $contract->sales_pic;
        $isPicOrAdmin = $user->isAdmin() || ($user->isManager() && $user->divisi === 'sales') || ($user->isStaff() && $user->divisi === 'sales' && (!$salesPic || $salesPic->id === $user->id));
        if (!$isPicOrAdmin) {
            return redirect()->route('contracts.index')
                ->with('error', 'Akses ditolak. Hanya Sales PIC penanggung jawab (' . ($salesPic->name ?? 'Sales PIC') . ') yang berhak mengupdate kontrak ini.');
        }

        $request->validate([
            'customer_id'    => 'required|exists:users,id',
            'quotation_id'   => 'nullable|exists:quotations,id',
            'order_no'       => 'required|string',
            'part_no'        => 'nullable|string|max:255',
            'part_name'      => 'nullable|string|max:255',
            'others_comment' => 'nullable|string',
            'po_pdf'         => 'nullable|file|mimes:pdf|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request, $contract) {

                $dataUpdate = [
                    'customer_id'      => $request->customer_id,
                    'quotation_id'     => $request->quotation_id,
                    'order_no'         => $request->order_no,
                    'amandement_no'    => $request->amandment_no ?? $request->amendment_no ?? $contract->amandement_no,
                    'alasan_amandemen' => $request->alasan_amandemen ?? $contract->alasan_amandemen,
                    'part_no'          => $request->part_no,
                    'part_name'        => $request->part_name,
                    'others_comment'   => $request->others_comment,
                    'article_id'       => $request->article_id ?? null,
                    'status'           => 'review',
                ];

                // Reset status reject pada divisi yang sebelumnya me-reject agar kembali menjadi "Menunggu Persetujuan Manager [Divisi]"
                if ($contract->sales_reject_reason) {
                    $dataUpdate['sales_reject_reason'] = null;
                    $dataUpdate['sales_rejected_at'] = null;
                }
                if ($contract->quality_reject_reason) {
                    $dataUpdate['quality_reject_reason'] = null;
                    $dataUpdate['quality_rejected_at'] = null;
                }
                if ($contract->ppc_reject_reason) {
                    $dataUpdate['ppc_reject_reason'] = null;
                    $dataUpdate['ppc_rejected_at'] = null;
                }
                if ($contract->dev_engineering_reject_reason) {
                    $dataUpdate['dev_engineering_reject_reason'] = null;
                    $dataUpdate['dev_engineering_rejected_at'] = null;
                }
                $dataUpdate['rejected_by_dept'] = null;

                if ($request->hasFile('po_pdf')) {
                    if ($contract->po_pdf && \Illuminate\Support\Facades\Storage::disk('public')->exists($contract->po_pdf)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($contract->po_pdf);
                    }
                    $dataUpdate['po_pdf'] = $request->file('po_pdf')->store('contracts/po', 'public');
                }

                $contract->update($dataUpdate);

                // Update requirements: perbarui yang belum diapprove
                foreach ($request->requirements ?? [] as $id => $item) {
                    if (empty($item['requirement'])) continue;

                    if (is_numeric($id)) {
                        $req = $contract->requirements()->find($id);
                        if ($req) {
                            $dept = strtolower(trim($req->requirement_from ?? ''));
                            $isApproved = match($dept) {
                                'sales'              => !empty($contract->sales_approver),
                                'quality'            => !empty($contract->quality_approver),
                                'ppc', 'ppic'        => !empty($contract->ppc_approver),
                                'design engineering', 'de' => !empty($contract->dev_engineering_approver),
                                default              => false
                            };

                            if (!$isApproved) {
                                $req->update([
                                    'requirement'       => $item['requirement'],
                                    'requirement_value' => $item['value'] ?? null,
                                ]);
                            }
                        }
                    } else {
                        $contract->requirements()->create([
                            'requirement_from'  => $item['from'] ?? null,
                            'requirement'       => $item['requirement'],
                            'requirement_value' => $item['value'] ?? null,
                        ]);
                    }
                }

                HistoryActivity::create([
                    'user_id'       => Auth::user()->id,
                    'activity'      => 'Update dan pengajuan ulang spesifikasi kontrak ' . ($contract->contract_no ?? $contract->order_no),
                    'activity_time' => now()->format('Y-m-d H:i:s')
                ]);
            });

            return redirect()
                ->route('contracts.show', $contract->id)
                ->with('success', 'Revisi spesifikasi kontrak berhasil disimpan dan diajukan ulang untuk persetujuan Manager.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function approveManager(Request $request, $contractId)
    {
        $contract = Contract::findOrFail($contractId);
        $user = Auth::user();
        $divisi = strtolower(trim($user->divisi ?? ''));

        // ====================================================================
        // Non-sequential / Parallel Approval: Bebas siapa saja approve duluan
        // Sales, Quality, PPC / PPIC, Design Engineering
        // ====================================================================
        $approvalMapping = [
            'sales'              => ['field' => 'sales_approver',           'at' => 'sales_approved_at',           'sig' => 'manager_sales_signature',   'label' => 'Sales'],
            'quality'            => ['field' => 'quality_approver',         'at' => 'quality_approved_at',         'sig' => 'manager_quality_signature', 'label' => 'Quality'],
            'ppc'                => ['field' => 'ppc_approver',             'at' => 'ppc_approved_at',             'sig' => 'manager_ppc_signature',     'label' => 'PPC'],
            'ppic'               => ['field' => 'ppc_approver',             'at' => 'ppc_approved_at',             'sig' => 'manager_ppc_signature',     'label' => 'PPC'],
            'design engineering' => ['field' => 'dev_engineering_approver', 'at' => 'dev_engineering_approved_at', 'sig' => 'manager_de_signature',      'label' => 'Design Engineering'],
            'de'                 => ['field' => 'dev_engineering_approver', 'at' => 'dev_engineering_approved_at', 'sig' => 'manager_de_signature',      'label' => 'Design Engineering'],
        ];

        if (!isset($approvalMapping[$divisi])) {
            return redirect()->back()->with('error', 'Divisi Anda (' . ($user->divisi ?? 'Unknown') . ') tidak memiliki hak approval pada kontrak ini.');
        }

        $config = $approvalMapping[$divisi];

        // Cek apakah sudah approve
        if ($contract->{$config['field']} != null) {
            return redirect()->back()->with('error', 'Divisi Anda (' . $config['label'] . ') sudah melakukan approve sebelumnya.');
        }

        $rejectField = match($divisi) {
            'sales'              => 'sales_reject_reason',
            'quality'            => 'quality_reject_reason',
            'ppc', 'ppic'        => 'ppc_reject_reason',
            'design engineering', 'de' => 'dev_engineering_reject_reason',
            default              => null
        };

        // Cek apakah sedang dalam status ditolak dan belum direvisi oleh Sales
        if ($rejectField && !empty($contract->{$rejectField})) {
            return redirect()->back()->with('error', 'Divisi Anda (' . $config['label'] . ') telah menolak kontrak ini. Menunggu tim Sales melakukan revisi data terlebih dahulu sebelum dapat di-approve.');
        }

        // Simpan tanda tangan digital jika dikirim dari signature pad
        if ($request->filled('signature')) {
            $contract->{$config['sig']} = $request->input('signature');
        }

        // Eksekusi approval langsung tanpa prerequisite urutan!
        $contract->{$config['field']} = $user->id;
        $contract->{$config['at']} = now();

        // Reset catatan reject divisi ini jika ada
        if ($divisi == 'sales') {
            $contract->sales_reject_reason = null;
            $contract->sales_rejected_at = null;
        } elseif ($divisi == 'quality') {
            $contract->quality_reject_reason = null;
            $contract->quality_rejected_at = null;
        } elseif (in_array($divisi, ['ppc', 'ppic'])) {
            $contract->ppc_reject_reason = null;
            $contract->ppc_rejected_at = null;
        } elseif (in_array($divisi, ['design engineering', 'de'])) {
            $contract->dev_engineering_reject_reason = null;
            $contract->dev_engineering_rejected_at = null;
        }

        $contract->save();

        $sales = User::where('role','=','staff')->get();
        foreach($sales as $pic) {
            $pic->notify(new ContractApprovedNotification($contract, $config['label']));
        }

        // CEK APAKAH SUDAH APPROVED OLEH SEMUA 4 MANAGER
        $allApproved = $contract->sales_approver 
                    && $contract->ppc_approver 
                    && $contract->quality_approver 
                    && $contract->dev_engineering_approver;

        if ($allApproved) {
            $contract->update(['status' => 'approved']);
        }

        HistoryActivity::create([
            'user_id'       => $user->id,
            'activity'      => 'Approve kontrak ' . ($contract->contract_no ?? '') . ' oleh Divisi ' . $config['label'],
            'activity_time' => now()->format('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Berhasil approve sebagai Divisi ' . $config['label'] . '.');
    }

    public function rejectManager(Request $request, $contractId)
    {
        $request->validate([
            'comment' => 'required|string|min:3',
        ], [
            'comment.required' => 'Alasan penolakan / permintaan revisi wajib diisi!',
            'comment.min'      => 'Alasan penolakan minimal 3 karakter.'
        ]);

        $contract = Contract::findOrFail($contractId);
        $user = Auth::user();
        $divisi = strtolower(trim($user->divisi ?? ''));
        $comment = trim($request->input('comment'));

        $deptLabel = match($divisi) {
            'sales'              => 'Sales',
            'quality'            => 'Quality',
            'ppc', 'ppic'        => 'PPC',
            'design engineering', 'de' => 'Design Engineering',
            default              => 'Manager'
        };

        $rejectField = match($divisi) {
            'sales'              => 'sales_reject_reason',
            'quality'            => 'quality_reject_reason',
            'ppc', 'ppic'        => 'ppc_reject_reason',
            'design engineering', 'de' => 'dev_engineering_reject_reason',
            default              => null
        };

        if ($rejectField && !empty($contract->{$rejectField})) {
            return redirect()->back()->with('error', 'Divisi Anda (' . $deptLabel . ') sudah menolak kontrak ini sebelumnya. Menunggu tim Sales melakukan revisi data.');
        }

        // Reset approval & Catat penolakan pada divisi yang bersangkutan
        if ($divisi == 'sales') {
            $contract->sales_approver = null;
            $contract->sales_approved_at = null;
            $contract->manager_sales_signature = null;
            $contract->sales_reject_reason = $comment;
            $contract->sales_rejected_at = now();
        } elseif ($divisi == 'quality') {
            $contract->quality_approver = null;
            $contract->quality_approved_at = null;
            $contract->manager_quality_signature = null;
            $contract->quality_reject_reason = $comment;
            $contract->quality_rejected_at = now();
        } elseif (in_array($divisi, ['ppc', 'ppic'])) {
            $contract->ppc_approver = null;
            $contract->ppc_approved_at = null;
            $contract->manager_ppc_signature = null;
            $contract->ppc_reject_reason = $comment;
            $contract->ppc_rejected_at = now();
        } elseif (in_array($divisi, ['design engineering', 'de'])) {
            $contract->dev_engineering_approver = null;
            $contract->dev_engineering_approved_at = null;
            $contract->manager_de_signature = null;
            $contract->dev_engineering_reject_reason = $comment;
            $contract->dev_engineering_rejected_at = now();
        } else {
            $contract->sales_reject_reason = $comment;
            $contract->sales_rejected_at = now();
        }

        $contract->rejected_by_dept = $divisi;
        $contract->alasan_penolakan = $comment;
        $contract->status = 'revision';

        // Catat ke catatan riwayat
        $historyComment = strtoupper($deptLabel) . '-' . now()->format('d M Y H:i') . ' (REJECT): ' . $comment;
        $contract->others_comment = !empty($contract->others_comment)
            ? $contract->others_comment . "\n" . $historyComment
            : $historyComment;

        $contract->save();

        HistoryActivity::create([
            'user_id'       => $user->id,
            'activity'      => 'Menolak kontrak ' . ($contract->contract_no ?? $contract->order_no) . ' (Divisi ' . $deptLabel . '): ' . $comment,
            'activity_time' => now()->format('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Penolakan kontrak oleh Manager ' . $deptLabel . ' berhasil dicatat beserta alasan revisi.');
    }

    public function generatePdf($contractId)
    {
        $contract = Contract::with(['requirements'])->find($contractId);
        $requirements = $contract->requirements;

        $isAuthorized = Auth::user()->role == 'admin' || strtolower(Auth::user()->divisi) == 'sales';
        
        if (!$isAuthorized) {
            foreach ($requirements as $req) {
                if (strtolower($req->requirement) == 'price') {
                    $req->requirement_value = '*** RAHASIA PERUSAHAAN ***';
                }
            }
        }

        $pdf = Pdf::loadView('contracts.pdf', [
            'article' => $requirements,
            'contract' => $contract,
        ]);

        $pdf->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream("Contract-Review-{$contract->contract_no}.pdf");
    }

    public function finalize($id)
    {
        $contract = Contract::with([
            'quotation.request.assignment.sales', 
            'purchaseOrder.quotation.request.assignment.sales', 
            'internalItem.purchaseOrder.quotation.request.assignment.sales'
        ])->findOrFail($id);

        $user = Auth::user();

        // 1. Otorisasi role: Admin, Staff Sales, atau Manager Sales
        if ($user->role !== 'admin' && !($user->role === 'staff' && $user->divisi === 'sales') && !($user->role === 'manager' && $user->divisi === 'sales')) { 
            return redirect()->back()->with('error', 'Akses ditolak! Hanya Staff Sales PIC atau Manajemen yang dapat memfinalisasi kontrak.');
        }

        // 2. Otorisasi Sales PIC: Jika yang login adalah Staff Sales, harus sesuai dengan Sales PIC Request/Quotation
        $salesPic = $contract->sales_pic;
        $salesPicId = $salesPic?->id;

        if ($user->role === 'staff' && $user->divisi === 'sales') {
            if ($salesPicId && $salesPicId !== $user->id) {
                return redirect()->back()->with('error', 'Akses ditolak. Hanya Staff Sales PIC pemegang tiket pesanan ini (' . ($salesPic->name ?? 'Sales PIC') . ') yang berhak memfinalisasi ke tahap produksi.');
            }
        }

        // 3. Validasi 4 Approver (Sales, Quality, PPC, Design Engineering)
        if (!$contract->sales_approver || !$contract->ppc_approver || !$contract->quality_approver || !$contract->dev_engineering_approver) { 
            return redirect()->back()->with('error', 'Gagal memfinalisasi. Kontrak belum disetujui sepenuhnya oleh 4 Divisi (Sales, Quality, PPC, Design Engineering).');
        }

        // 4. Update status ke In Production
        $contract->status = 'production'; 
        $contract->save();

        if ($contract->purchase_order_internal_id) {
            PurchaseOrderInternal::where('id', $contract->purchase_order_internal_id)
                ->update(['status' => 'production']);
        }

        $po = PurchaseOrder::where('po_no', $contract->order_no)->first();
        if ($po) {
            $po->update([
                'status' => 'production' 
            ]);
        }

        $picName = $salesPic ? $salesPic->name : $user->name;

        HistoryActivity::create([
            'user_id'       => $user->id,
            'activity'      => 'Sales PIC (' . $picName . ') memfinalisasi Contract Review Sheet #' . ($contract->contract_no ?? $contract->order_no) . ' ke tahap In Production',
            'activity_time' => now()->format('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Kontrak berhasil difinalisasi oleh Sales PIC (' . $picName . ')! Status pesanan kini resmi In Production (Dalam Produksi).');
    }

    // DELETE /contracts/{contract}
    public function destroy($id)
    {
        $contract = Contract::findOrFail($id);
        $user = Auth::user();
        $salesPic = $contract->sales_pic;
        $isPicOrAdmin = $user->isAdmin() || ($user->isManager() && $user->divisi === 'sales') || ($user->isStaff() && $user->divisi === 'sales' && (!$salesPic || $salesPic->id === $user->id));
        if (!$isPicOrAdmin) {
            return redirect()->route('contracts.index')
                ->with('error', 'Akses ditolak. Hanya Sales PIC penanggung jawab (' . ($salesPic->name ?? 'Sales PIC') . ') yang berhak menghapus kontrak ini.');
        }
        
        $contractNo = $contract->contract_no;

        DB::transaction(function () use ($contract) {
            ContractRequirement::where('contract_id', $contract->id)->delete();
            $contract->delete();
        });

        HistoryActivity::create([
            'user_id'  => Auth::id(),
            'activity' => 'Menghapus Contract Review Sheet #' . $id . ' (' . $contractNo . ')',
        ]);

        return redirect()->route('contracts.index')->with('success', 'Contract Review Sheet (' . $contractNo . ') berhasil dihapus!');
    }
}