<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Quotation;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInternal;
use App\Models\ContractRequirement;
use App\Models\HistoryActivity;
use App\Models\Article;
use App\Models\User;
use App\Notifications\GenericSystemNotification;
use App\Exports\PurchaseOrderExport;
use App\Services\SystemSettingService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseOrderController extends Controller
{
    /**
     * Export purchase orders to Excel
     */
    public function export(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'status']);
        $filename = 'purchase-orders-' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new PurchaseOrderExport($filters), $filename);
    }

    /**
     * Display a listing of the resource.
     * Eager-load relasi internals.contract untuk kebutuhan tampilan per-item customer
     */
    public function index(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'status', 'type']);

        $query = PurchaseOrder::with(['customer', 'quotation.request.assignment.sales', 'quotation.items', 'internals.contract', 'internals.contracts', 'contracts']);

        $user = Auth::user();
        if ($user->role == 'customer') {
            $query->where('customer_id', $user->id);
        } elseif ($user->role === 'staff' && $user->divisi === 'sales') {
            // Staff Sales HANYA BISA MELIHAT PO yang di-PIC oleh dirinya sendiri
            $query->whereHas('quotation.request.assignment', function ($q) use ($user) {
                $q->where('sales_id', $user->id);
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

        if (!empty($filters['type'])) {
            if ($filters['type'] === 'new') {
                $query->whereNotIn('purchase_orders.status', ['amandement', 'amandement_pending'])
                    ->whereDoesntHave('internals', function ($q) {
                        $q->whereIn('purchase_order_internals.status', ['amandement', 'amandement_pending']);
                    })
                    ->whereDoesntHave('contracts', function ($q) {
                        $q->whereIn('contracts.status', ['amandement', 'amandement_pending']);
                    })
                    ->whereDoesntHave('internalContracts', function ($q) {
                        $q->whereIn('contracts.status', ['amandement', 'amandement_pending']);
                    });
            } elseif ($filters['type'] === 'amandement') {
                $query->where(function ($q) {
                    $q->whereIn('purchase_orders.status', ['amandement', 'amandement_pending'])
                        ->orWhereHas('internals', function ($qSub) {
                            $qSub->whereIn('purchase_order_internals.status', ['amandement', 'amandement_pending']);
                        })
                        ->orWhereHas('contracts', function ($qSub) {
                            $qSub->whereIn('contracts.status', ['amandement', 'amandement_pending']);
                        })
                        ->orWhereHas('internalContracts', function ($qSub) {
                            $qSub->whereIn('contracts.status', ['amandement', 'amandement_pending']);
                        });
                });
            }
        }

        $pos = $query->orderByRaw("
            CASE 
                WHEN status = 'amandement_pending' THEN 1
                WHEN status = 'pending' THEN 2
                WHEN status = 'draft' THEN 3
                WHEN status = 'sent' THEN 4
                WHEN status = 'amandement' THEN 5
                WHEN status = 'production' THEN 6
                WHEN status = 'completed' OR status = 'finished' THEN 7
                ELSE 8
            END ASC, created_at DESC
        ")->paginate(10)->appends($filters);

        // Auto-heal data status master PO jika sebelumnya tersimpan 'production' padahal belum semua item masuk produksi
        foreach ($pos as $p) {
            if (strtolower($p->status) === 'production' && $p->effective_status !== 'production') {
                $p->update(['status' => $p->effective_status]);
            }
        }

        return view('purchase-orders.index', compact('pos', 'filters'));
    }

    public function schedule(Request $request)
    {
        $query = PurchaseOrder::query();
        if ($request->filled('status')) {
            $query->where('status', '=', $request->filled('status'));
        }
        $pos = $query->where('status', '=', 'production')
            ->orderBy('delivery_request')
            ->get();
        return view('purchase-orders.schedule', compact('pos'));
    }

    public function arrangeSchedule(Request $request)
    {
        PurchaseOrder::where('status', 'production')
            ->whereBetween('delivery_request', [
                Carbon::now(),
                Carbon::now()->addDays(21)
            ])
            ->update([
                'status_order' => 'urgent'
            ]);

        return back()->with('success', 'Berhasil mengurutkan kembali PO');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $query = Quotation::query();
        if (Auth::user()->role == 'customer') {
            $query->where('customer_id', '=', Auth::user()->id);
        }
        $quotations = $query->whereIn('status', ['sent', 'accepted'])->get();

        $selectedQuotation = Quotation::with('customer')->find($request->quotation_id);
        if ($request->has('quotation_id')) {
            $selectedQuotation = Quotation::find($request->quotation_id);
        }
        return view('purchase-orders.create', compact('quotations', 'selectedQuotation'));
    }

    /**
     * Halaman Pengajuan Amandemen (Mendukung Konteks Item Spesifik via hidden internal_id)
     */
    public function createAmandement(Request $request, $id)
    {
        $lastPo = PurchaseOrder::findOrFail($id);
        $user = Auth::user();

        $internalId = $request->query('internal_id');
        $selectedItem = null;
        $contract = null;

        if ($internalId) {
            $selectedItem = PurchaseOrderInternal::find($internalId);
            $contract = Contract::where('purchase_order_internal_id', $internalId)
                ->orderByDesc('amandement_no')
                ->first();
        } else {
            $contract = Contract::where('order_no', $lastPo->po_no)->first();
        }

        // Ambil status PO & Kontrak (lowercase agar aman)
        $poStatus       = strtolower($lastPo->status ?? '');
        $contractStatus = $contract ? strtolower($contract->status ?? '') : null;
        $itemStatus     = $selectedItem ? strtolower($selectedItem->status ?? '') : null;

        // KUNCI PRODUKSI: Jika status item / kontrak sudah masuk produksi / done, amandemen DILARANG!
        if ($selectedItem || $contract) {
            if (($contractStatus && in_array($contractStatus, ['production', 'done'])) 
                || ($itemStatus && in_array($itemStatus, ['production', 'done']))) {
                return redirect()->route('purchase-orders.index')->with('error', 'Item pesanan ini sudah masuk tahap produksi (In Production / Selesai) dan tidak dapat diajukan amandemen lagi.');
            }
        } else {
            if (in_array($poStatus, ['production', 'done'])) {
                return redirect()->route('purchase-orders.index')->with('error', 'Pesanan ini sudah masuk tahap produksi (In Production / Selesai) dan tidak dapat diajukan amandemen lagi.');
            }
        }

        // KUNCI BATAS AMANDEMEN: Maksimal 2x amandemen
        $maxAmendmentLimit = SystemSettingService::maxAmendmentLimit();
        $currentAmendmentNo = (int) ($contract ? ($contract->amandement_no ?? 0) : 0);

        if ($currentAmendmentNo >= $maxAmendmentLimit) {
            return redirect()->route('purchase-orders.index')->with('error', 
                'Item ini telah mencapai batas maksimal amandemen (' . $currentAmendmentNo . '/' . $maxAmendmentLimit . ' kali). Tidak dapat mengajukan amandemen lagi.'
            );
        }

        // Daftar status PO / Kontrak yang diizinkan untuk amandemen customer
        $allowedStatuses = ['sent', 'review', 'contract', 'created', 'po', 'approved'];

        $isAllowed = in_array($poStatus, $allowedStatuses) || ($contractStatus && in_array($contractStatus, $allowedStatuses));

        if (! ($user->isAdmin() || ($user->isCustomer() && $isAllowed)) ) {
            abort(403, 'Unauthorized action.');
        }

        // OPSI A: Customer hanya bisa mengajukan amandemen jika Sales telah memproses PO Internal / Kontrak awal
        if ($user->isCustomer() && (!$selectedItem || !$contract)) {
            return redirect()->back()->with('error', 'Amandemen baru dapat diajukan setelah Staff Sales meninjau PO dan memproses Lembar Kontrak awal.');
        }
        
        $originalQuantity = $contract 
            ? ContractRequirement::where('contract_id', $contract->id)
                ->where('requirement', 'LIKE', '%Quantity%')
                ->value('requirement_value') 
            : ($selectedItem->qty ?? 0);

        $nextAmendmentNo = $currentAmendmentNo + 1;

        $itemPoNo = $selectedItem 
            ? ($selectedItem->po_no ?? ($lastPo->po_no . '-' . $selectedItem->id)) 
            : $lastPo->po_no;

        return view('purchase-orders.create-amandement', compact(
            'lastPo', 
            'selectedItem', 
            'contract', 
            'originalQuantity', 
            'nextAmendmentNo',
            'itemPoNo'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'quotation_id'     => 'required|exists:quotations,id',
                'po_no'            => 'required|unique:purchase_orders,po_no',
                'attachments'      => 'required|mimes:pdf|max:10240',
                'delivery_request' => 'required|date'
            ]);

            $qt = Quotation::find($validated['quotation_id']);
            if ($qt->purchaseOrder) {
                $validatedR = $request->validate([
                    'amandement_no' => 'required'
                ]);
                $qt->purchaseOrder->update(['status' => 'amandement']);
                $validated['amandement_no'] = $validatedR['amandement_no'];
            }

            if ($request->hasFile('attachments')) {
                $file = $request->file('attachments');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('uploads', $filename, 'public');
            } else {
                $filename = null;
            }

            $validated['attachment'] = $filename;
            $validated['customer_id'] = Auth::user()->id;
            $newPO = PurchaseOrder::create($validated);
            $newPO->quotation->update([
                'status'  => 'po',
                'po_date' => now()
            ]);

            HistoryActivity::create([
                'user_id'       => Auth::user()->id,
                'activity'      => 'Membuat PO ' . $newPO->po_no,
                'activity_time' => now()->format('Y-m-d H:i:s')
            ]);

            // 1. Notifikasi ke Sales PIC
            $salesPic = $newPO->sales_pic;
            if ($salesPic && Auth::id() !== $salesPic->id) {
                $salesPic->notify(new GenericSystemNotification([
                    'message'  => 'Customer telah menyetujui Quotation #' . ($newPO->quotation->quotation_no ?? '-') . ' dan menerbitkan PO #' . $newPO->po_no . '.',
                    'url'      => route('purchase-orders.show', $newPO->id),
                    'order_no' => $newPO->po_no,
                    'category' => 'po',
                ]));
            }

            // 2. Notifikasi ke Admin
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                if (Auth::id() === $admin->id) continue;
                $admin->notify(new GenericSystemNotification([
                    'message'  => 'Customer telah menerbitkan PO #' . $newPO->po_no . ' untuk Quotation #' . ($newPO->quotation->quotation_no ?? '-') . '.',
                    'url'      => route('purchase-orders.show', $newPO->id),
                    'order_no' => $newPO->po_no,
                    'category' => 'po',
                ]));
            }

            return redirect()->route('purchase-orders.index')->with('success', 'Berhasil mengajukan PO');
        } catch (Exception $e) {
            Log::error('Error : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat PO.');
        }
    }

    /**
     * Display the specified resource (MODAL DETAIL POP-UP).
     * Mendukung pemanggilan via Ajax/Fetch dengan opsional query internal_id
     */
    public function show(Request $request, $id)
    {
        $po = PurchaseOrder::with([
            'quotation.request.assignment.sales', 
            'quotation.items.article',
            'customer', 
            'internals.contract.article',
            'internals.contract.requirements',
            'internals.contracts.article',
            'internals.contracts.requirements'
        ])->findOrFail($id);

        $user = Auth::user();
        if ($user->role === 'customer' && $po->customer_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        if ($user->role === 'staff' && $user->divisi === 'sales') {
            $salesPic = $po->sales_pic;
            if ($salesPic && $salesPic->id !== $user->id) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['error' => 'Akses ditolak. PO ini ditangani oleh Sales PIC lain.'], 403);
                }
                return redirect()->route('purchase-orders.index')
                    ->with('error', 'Akses ditolak. PO ini ditangani oleh Sales PIC lain (' . ($salesPic->name ?? 'Sales PIC') . ').');
            }
        }

        $internalId = $request->query('internal_id');
        $quotationItemId = $request->query('quotation_item_id');
        $itemIndex = $request->query('item_index');
        $selectedItem = null;

        if ($internalId) {
            $selectedItem = $po->internals->where('id', $internalId)->first();
        } elseif ($quotationItemId && $po->quotation) {
            $selectedItem = $po->quotation->items->where('id', $quotationItemId)->first();
        } elseif ($itemIndex !== null && is_numeric($itemIndex)) {
            $itemsList = $po->internals->count() > 0 ? $po->internals : ($po->quotation?->items ?? collect());
            $selectedItem = $itemsList->values()->get((int)$itemIndex);
        }

        return view('purchase-orders.show-partial', compact('po', 'selectedItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchase_order)
    {
        $user = Auth::user();
        $salesPic = $purchase_order->sales_pic;
        $isPicOrAdmin = $user->isAdmin() || ($user->isManager() && $user->divisi === 'sales') || ($user->isStaff() && $user->divisi === 'sales' && (!$salesPic || $salesPic->id === $user->id));
        if (!$isPicOrAdmin) {
            return redirect()->route('purchase-orders.index')
                ->with('error', 'Akses ditolak. Hanya Sales PIC penanggung jawab (' . ($salesPic->name ?? 'Sales PIC') . ') yang berhak mengedit PO ini.');
        }

        return view('purchase-orders.edit-partial', compact('purchase_order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $user = Auth::user();
        $salesPic = $purchaseOrder->sales_pic;
        $isPicOrAdmin = $user->isAdmin() || ($user->isManager() && $user->divisi === 'sales') || ($user->isStaff() && $user->divisi === 'sales' && (!$salesPic || $salesPic->id === $user->id));
        if (!$isPicOrAdmin) {
            return redirect()->route('purchase-orders.index')
                ->with('error', 'Akses ditolak. Hanya Sales PIC penanggung jawab (' . ($salesPic->name ?? 'Sales PIC') . ') yang berhak mengupdate PO ini.');
        }

        $validated = $request->validate([
            'status' => 'required|in:production'
        ]);

        $purchaseOrder->update($validated);

        return back()->with('success', 'Berhasil update status PO');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Membuka Form Pembuatan Kontrak Berdasarkan PO dan Item Spesifik
     */
    public function createContract(Request $request, $idPo)
    {
        $po = is_numeric($idPo) ? PurchaseOrder::find($idPo) : PurchaseOrder::where('po_no', $idPo)->first();
        $user = Auth::user();
        $salesPic = $po?->sales_pic;
        $isPicOrAdmin = $user->isAdmin() || ($user->isManager() && $user->divisi === 'sales') || ($user->isStaff() && $user->divisi === 'sales' && (!$salesPic || $salesPic->id === $user->id));
        if (!$isPicOrAdmin) {
            return redirect()->route('purchase-orders.index')
                ->with('error', 'Akses ditolak. Hanya Sales PIC penanggung jawab (' . ($salesPic->name ?? 'Sales PIC') . ') yang berhak membuat Contract Review Sheet untuk PO ini.');
        }

        return app(\App\Http\Controllers\ContractController::class)->create($request, $idPo);
    }

    /**
     * Pelacakan Progress Order Publik 
     */
    public function trackPublic(Request $request)
    {
        $po_no = $request->input('po_no');
        $purchase_order = null;

        if ($po_no) {
            $purchase_order = PurchaseOrder::where('po_no', $po_no)
                ->with(['customer', 'quotation'])
                ->first();
        }

        return view('customer_home.track', compact('purchase_order', 'po_no'));
    }

    /**
    1. Customer mengajukan Amandemen Spesifik Per-Item (Status Kontrak Item = amandement_pending)
     */
    public function storeAmandement(Request $request, $id)
    {
        $request->validate([
            'alasan_amandemen' => 'required|string|max:1000',
            'attachments'      => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240',
        ], [
            'alasan_amandemen.required' => 'Alasan / pesan perubahan dokumen wajib diisi!',
            'attachments.required'      => 'Dokumen pendukung amandemen wajib diunggah!',
            'attachments.file'          => 'Berkas lampiran amandemen tidak valid.',
            'attachments.mimes'         => 'Format dokumen harus berupa PDF, JPG, PNG, DOC/DOCX, atau XLS/XLSX.',
            'attachments.max'           => 'Ukuran dokumen maksimal adalah 10MB.',
        ]);

        $po = PurchaseOrder::findOrFail($id);
        $internalId = $request->input('purchase_order_internal_id');

        // Cari Kontrak Spesifik berdasarkan purchase_order_internal_id
        if ($internalId) {
            $kontrakAwal = Contract::where('purchase_order_internal_id', $internalId)->orderByDesc('amandement_no')->first();
            $selectedItem = PurchaseOrderInternal::find($internalId);
        } else {
            $kontrakAwal = Contract::where('order_no', $po->po_no)->orderByDesc('amandement_no')->first();
            $selectedItem = null;
        }

        $poStatus       = strtolower($po->status ?? '');
        $contractStatus = $kontrakAwal ? strtolower($kontrakAwal->status ?? '') : null;
        $itemStatus     = $selectedItem ? strtolower($selectedItem->status ?? '') : null;

        // KUNCI PRODUKSI:
        if ($selectedItem || $kontrakAwal) {
            if (($contractStatus && in_array($contractStatus, ['production', 'done'])) 
                || ($itemStatus && in_array($itemStatus, ['production', 'done']))) {
                return redirect()->route('purchase-orders.index')->with('error', 'Item pesanan ini sudah masuk tahap produksi (In Production / Selesai) dan tidak dapat diajukan amandemen lagi.');
            }
        } else {
            if (in_array($poStatus, ['production', 'done'])) {
                return redirect()->route('purchase-orders.index')->with('error', 'Pesanan ini sudah masuk tahap produksi (In Production / Selesai) dan tidak dapat diajukan amandemen lagi.');
            }
        }

        // ====================================================================
        // MODUL 7: Cek batas maksimal amandemen per item (Maks 2x)
        // ====================================================================
        $maxAmendmentLimit = SystemSettingService::maxAmendmentLimit();
        $currentAmendmentCount = (int) ($kontrakAwal ? ($kontrakAwal->amandement_no ?? 0) : 0);

        if ($currentAmendmentCount >= $maxAmendmentLimit) {
            return redirect()->route('purchase-orders.index')->with('error', 
                'Kuota amandemen telah habis (' . $currentAmendmentCount . '/' . $maxAmendmentLimit . ' kali). '
                . 'Tidak dapat mengajukan amandemen baru untuk item ini.');
        }

        DB::transaction(function () use ($request, $po, $internalId, &$kontrakAwal) {
            if (!$kontrakAwal) {
                $kontrakAwal = Contract::create([
                    'customer_id'                => $po->customer_id,
                    'quotation_id'               => $po->quotation_id,
                    'purchase_order_internal_id' => $internalId,
                    'order_no'                   => $po->po_no,
                    'contract_no'                => 'CTR-' . $po->po_no . ($internalId ? '-' . $internalId : ''),
                    'status'                     => 'amandement_pending',
                    'alasan_amandemen'           => $request->alasan_amandemen,
                    'amandement_no'              => 1,
                    // Reset review untuk seluruh 4 divisi manager
                    'sales_approver'             => null,
                    'sales_approved_at'          => null,
                    'manager_sales_signature'    => null,
                    'sales_reject_reason'        => null,
                    'sales_rejected_at'          => null,
                    'quality_approver'           => null,
                    'quality_approved_at'        => null,
                    'manager_quality_signature'  => null,
                    'quality_reject_reason'      => null,
                    'quality_rejected_at'        => null,
                    'ppc_approver'               => null,
                    'ppc_approved_at'            => null,
                    'manager_ppc_signature'      => null,
                    'ppc_reject_reason'          => null,
                    'ppc_rejected_at'            => null,
                    'dev_engineering_approver'   => null,
                    'dev_engineering_approved_at'=> null,
                    'manager_de_signature'       => null,
                    'dev_engineering_reject_reason' => null,
                    'dev_engineering_rejected_at'=> null,
                ]);
            } else {
                $kontrakAwal->update([
                    'alasan_amandemen'           => $request->alasan_amandemen,
                    'amandement_no'              => ($kontrakAwal->amandement_no ?? 0) + 1,
                    'status'                     => 'amandement_pending',
                    // Reset review untuk seluruh 4 divisi manager
                    'sales_approver'             => null,
                    'sales_approved_at'          => null,
                    'manager_sales_signature'    => null,
                    'sales_reject_reason'        => null,
                    'sales_rejected_at'          => null,
                    'quality_approver'           => null,
                    'quality_approved_at'        => null,
                    'manager_quality_signature'  => null,
                    'quality_reject_reason'      => null,
                    'quality_rejected_at'        => null,
                    'ppc_approver'               => null,
                    'ppc_approved_at'            => null,
                    'manager_ppc_signature'      => null,
                    'ppc_reject_reason'          => null,
                    'ppc_rejected_at'            => null,
                    'dev_engineering_approver'   => null,
                    'dev_engineering_approved_at'=> null,
                    'manager_de_signature'       => null,
                    'dev_engineering_reject_reason' => null,
                    'dev_engineering_rejected_at'=> null,
                ]);
            }

            // Handle Upload File Lampiran Baru jika ada
            if ($request->hasFile('attachments')) {
                $file = $request->file('attachments');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('uploads', $filename, 'public');

                $kontrakAwal->update([
                    'po_pdf' => 'uploads/' . $filename
                ]);

                $po->attachment = !empty($po->attachment) ? $po->attachment . ',' . $filename : $filename;
                $po->save();
            }
        });

        $amendmentNo = (int) ($kontrakAwal ? ($kontrakAwal->amandement_no ?? 1) : 1);

        // 1. Notifikasi ke Sales PIC
        $salesPic = $kontrakAwal?->sales_pic ?: $po->sales_pic;
        if ($salesPic && Auth::id() !== $salesPic->id) {
            $salesPic->notify(new GenericSystemNotification([
                'message'  => 'Customer mengajukan Amandemen ke-' . $amendmentNo . ' untuk PO #' . $po->po_no . '.',
                'url'      => route('purchase-orders.approval-amandement'),
                'order_no' => $po->po_no,
                'category' => 'amandement',
            ]));
        }

        // 2. Notifikasi ke Manager Sales (Eskalasi Amandemen)
        $managerSales = User::where('role', 'manager')->where('divisi', 'sales')->get();
        foreach ($managerSales as $mgr) {
            if (Auth::id() === $mgr->id) continue;
            $mgr->notify(new GenericSystemNotification([
                'message'  => 'Pengajuan amandemen PO #' . $po->po_no . ' memerlukan persetujuan Manager Sales.',
                'url'      => route('purchase-orders.approval-amandement'),
                'order_no' => $po->po_no,
                'category' => 'amandement',
            ]));
        }

        // 3. Notifikasi ke Admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            if (Auth::id() === $admin->id) continue;
            $admin->notify(new GenericSystemNotification([
                'message'  => 'Customer mengajukan Amandemen ke-' . $amendmentNo . ' untuk PO #' . $po->po_no . '.',
                'url'      => route('purchase-orders.approval-amandement'),
                'order_no' => $po->po_no,
                'category' => 'amandement',
            ]));
        }

        return redirect()->route('purchase-orders.index')->with('success', 'Pengajuan amandemen item berhasil dikirim dan menunggu persetujuan.');
    }

    /**
     * 2. Halaman Review Amandemen (Untuk Staff / Manager)
     */
    public function indexAmandement()
    {
        $user = Auth::user();

        if (!($user->isStaff() || $user->isManager() || $user->isAdmin())) {
            abort(403, 'Akses ditolak. Hanya Staff atau Manager yang dapat mengakses halaman ini.');
        }

        // Ambil kontrak item yang sedang diajukan amandemennya (status = amandement_pending)
        $pendingQuery = Contract::with([
            'customer', 
            'quotation.request.assignment.sales', 
            'purchaseOrderInternal.purchaseOrder.quotation.request.assignment.sales', 
            'purchaseOrder.quotation.request.assignment.sales',
            'requirements'
        ])->where('status', 'amandement_pending');

        // Riwayat amandemen yang sudah diproses (Disetujui / Ditolak)
        $historyQuery = Contract::with([
            'customer', 
            'quotation.request.assignment.sales', 
            'purchaseOrderInternal.purchaseOrder.quotation.request.assignment.sales', 
            'purchaseOrder.quotation.request.assignment.sales',
            'requirements'
        ])->where('amandement_no', '>', 0)
          ->where('status', '!=', 'amandement_pending');

        if ($user->role === 'staff' && $user->divisi === 'sales') {
            // Staff Sales HANYA BISA MELIHAT & MEMPROSES Amandemen PO yang di-PIC oleh dirinya sendiri
            $picFilter = function ($q) use ($user) {
                $q->whereHas('quotation.request.assignment', function ($sub) use ($user) {
                    $sub->where('sales_id', $user->id);
                })->orWhereHas('purchaseOrderInternal.purchaseOrder.quotation.request.assignment', function ($sub) use ($user) {
                    $sub->where('sales_id', $user->id);
                })->orWhereHas('purchaseOrder.quotation.request.assignment', function ($sub) use ($user) {
                    $sub->where('sales_id', $user->id);
                });
            };

            $pendingQuery->where($picFilter);
            $historyQuery->where($picFilter);
        }

        $pendingContracts = $pendingQuery->latest('updated_at')->get();
        $historyContracts = $historyQuery->latest('updated_at')->limit(20)->get();

        return view('purchase-orders.approval-amandement', compact('pendingContracts', 'historyContracts'));
    }

    /**
     * 3. APPROVE: Amandemen Item disetujui
     */
    public function approveAmandement(Request $request, $id)
    {
        $user = Auth::user();

        if (!($user->isStaff() || $user->isManager() || $user->isAdmin())) {
            abort(403, 'Akses ditolak.');
        }

        $po = PurchaseOrder::findOrFail($id);
        $contractId = $request->input('contract_id');
        $internalId = $request->input('purchase_order_internal_id');

        if ($contractId) {
            $contract = Contract::find($contractId);
        } elseif ($internalId) {
            $contract = Contract::where('purchase_order_internal_id', $internalId)->where('status', 'amandement_pending')->latest('id')->first()
                ?? Contract::where('purchase_order_internal_id', $internalId)->latest('id')->first();
        } else {
            $contract = Contract::where('order_no', $po->po_no)->where('status', 'amandement_pending')->latest('id')->first()
                ?? Contract::where('order_no', $po->po_no)->latest('id')->first();
        }

        // VALIDASI SALES PIC: Hanya Sales PIC pemegang tiket atau Atasan (Manager Sales / Admin) yang berhak
        $salesPic = $contract ? $contract->sales_pic : $po->sales_pic;
        $isPicOrAdmin = $user->isAdmin() || ($user->isManager() && $user->divisi === 'sales') || ($user->isStaff() && $user->divisi === 'sales' && (!$salesPic || $salesPic->id === $user->id));
        if (!$isPicOrAdmin) {
            return redirect()->back()
                ->with('error', 'Akses ditolak. Hanya Sales PIC penanggung jawab (' . ($salesPic->name ?? 'Sales PIC') . ') atau Manager/Admin yang berhak menyetujui amandemen PO ini.');
        }

        DB::transaction(function () use ($request, $po, $contract, $user) {
            if ($contract) {
                $contract->update([
                    'status'                         => 'amandement',
                    'alasan_penolakan'               => null,
                    'catatan_sales'                  => $request->input('catatan', 'Amandemen item disetujui.'),
                    // Pastikan seluruh review manager di-reset untuk siklus review amandemen
                    'sales_approver'                 => null,
                    'sales_approved_at'              => null,
                    'manager_sales_signature'        => null,
                    'sales_reject_reason'            => null,
                    'sales_rejected_at'              => null,
                    'quality_approver'               => null,
                    'quality_approved_at'            => null,
                    'manager_quality_signature'      => null,
                    'quality_reject_reason'          => null,
                    'quality_rejected_at'            => null,
                    'ppc_approver'                   => null,
                    'ppc_approved_at'                => null,
                    'manager_ppc_signature'          => null,
                    'ppc_reject_reason'              => null,
                    'ppc_rejected_at'                => null,
                    'dev_engineering_approver'   => null,
                    'dev_engineering_approved_at'=> null,
                    'manager_de_signature'       => null,
                    'dev_engineering_reject_reason' => null,
                    'dev_engineering_rejected_at'=> null,
                ]);

                // ====================================================================
                // MODUL 7: Sinkronisasi kuantitas, harga, dan subtotal baru ke PO Internal
                // ====================================================================
                if ($contract->purchase_order_internal_id) {
                    $internalItem = PurchaseOrderInternal::find($contract->purchase_order_internal_id);
                    if ($internalItem) {
                        $newQty = $request->input('new_qty', $internalItem->qty);
                        $newPrice = $request->input('new_unit_price', $internalItem->unit_price);
                        $internalItem->update([
                            'qty'       => $newQty,
                            'unit_price'=> $newPrice,
                            'subtotal'  => $newQty * $newPrice,
                            'status'    => 'amandement',
                        ]);
                    }
                }
            }

            // Sync PO master status jika tidak ada lagi amandement_pending
            $hasPending = Contract::where('order_no', $po->po_no)->where('status', 'amandement_pending')->exists();
            if (!$hasPending && $po->status === 'amandement_pending') {
                $po->update(['status' => 'amandement']);
            }

            HistoryActivity::create([
                'user_id'       => $user->id,
                'activity'      => 'Menyetujui Amandemen PO No: ' . ($contract->order_no ?? $po->po_no) . ($contract ? ' (Kontrak: ' . $contract->contract_no . ')' : ''),
                'activity_time' => now()
            ]);
        });

        // Notifikasi ke Customer bahwa pengajuan amandemen telah disetujui
        if ($po->customer) {
            $po->customer->notify(new GenericSystemNotification([
                'message'  => 'Pengajuan Amandemen untuk PO #' . $po->po_no . ' telah disetujui.',
                'url'      => route('purchase-orders.index'),
                'order_no' => $po->po_no,
                'category' => 'approved',
            ]));
        }

        return redirect()->back()->with('success', 'Amandemen item berhasil disetujui. Status item dan data PO Internal telah diperbarui.');
    }

    /**
     * 4. REJECT: Amandemen Item ditolak
     */
    public function rejectAmandement(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan amandemen wajib diisi!'
        ]);

        $user = Auth::user();

        if (!($user->isStaff() || $user->isManager() || $user->isAdmin())) {
            abort(403, 'Akses ditolak.');
        }

        $po = PurchaseOrder::findOrFail($id);
        $contractId = $request->input('contract_id');
        $internalId = $request->input('purchase_order_internal_id');

        if ($contractId) {
            $contract = Contract::find($contractId);
        } elseif ($internalId) {
            $contract = Contract::where('purchase_order_internal_id', $internalId)->where('status', 'amandement_pending')->latest('id')->first()
                ?? Contract::where('purchase_order_internal_id', $internalId)->latest('id')->first();
        } else {
            $contract = Contract::where('order_no', $po->po_no)->where('status', 'amandement_pending')->latest('id')->first()
                ?? Contract::where('order_no', $po->po_no)->latest('id')->first();
        }

        // VALIDASI SALES PIC: Hanya Sales PIC pemegang tiket atau Atasan (Manager Sales / Admin) yang berhak
        $salesPic = $contract ? $contract->sales_pic : $po->sales_pic;
        $isPicOrAdmin = $user->isAdmin() || ($user->isManager() && $user->divisi === 'sales') || ($user->isStaff() && $user->divisi === 'sales' && (!$salesPic || $salesPic->id === $user->id));
        if (!$isPicOrAdmin) {
            return redirect()->back()
                ->with('error', 'Akses ditolak. Hanya Sales PIC penanggung jawab (' . ($salesPic->name ?? 'Sales PIC') . ') atau Manager/Admin yang berhak menolak amandemen PO ini.');
        }

        DB::transaction(function () use ($request, $po, &$contract, $user, $internalId) {
            if ($contract) {
                $contract->update([
                    'status'           => 'rejected',
                    'alasan_penolakan' => $request->alasan_penolakan
                ]);
                if ($contract->purchase_order_internal_id) {
                    Contract::where('purchase_order_internal_id', $contract->purchase_order_internal_id)->update([
                        'status'           => 'rejected',
                        'alasan_penolakan' => $request->alasan_penolakan
                    ]);
                }
            } else {
                $contract = Contract::create([
                    'customer_id'                => $po->customer_id,
                    'quotation_id'               => $po->quotation_id,
                    'purchase_order_internal_id' => $internalId,
                    'order_no'                   => $po->po_no,
                    'contract_no'                => 'CTR-' . $po->po_no,
                    'status'                     => 'rejected',
                    'alasan_penolakan'           => $request->alasan_penolakan,
                ]);
            }

            // Sync PO master status jika tidak ada lagi amandement_pending
            $hasPending = Contract::where('order_no', $po->po_no)->where('status', 'amandement_pending')->exists();
            if (!$hasPending && $po->status === 'amandement_pending') {
                $po->update(['status' => 'sent']);
            }

            HistoryActivity::create([
                'user_id'       => $user->id,
                'activity'      => 'Menolak Amandemen PO No: ' . ($contract->order_no ?? $po->po_no) . ' (Alasan: ' . $request->alasan_penolakan . ')',
                'activity_time' => now()
            ]);
        });

        if ($po->customer) {
            try {
                $po->customer->notify(new \App\Notifications\AmendmentRejectedNotification($po, $request->alasan_penolakan));
            } catch (\Exception $e) {
                // notification log
            }
        }

        return redirect()->back()->with('success', 'Amandemen item berhasil ditolak. Notifikasi penolakan telah dikirimkan ke Customer.');
    }

    /**
     * 5. Customer Mengeklik "OK" untuk Konfirmasi Penolakan / Reset Status ke Review
     */
    public function acknowledgeAmandement(Request $request, $id)
    {
        $po = PurchaseOrder::findOrFail($id);
        $internalId = $request->input('purchase_order_internal_id');
        $catatanCustomer = $request->input('catatan_customer');

        $activityNote = 'Customer mengonfirmasi penolakan amandemen PO No: ' . $po->po_no . '.';
        if (!empty($catatanCustomer)) {
            $activityNote .= ' (Tanggapan Customer: ' . $catatanCustomer . ')';
        }

        if ($internalId) {
            $contract = Contract::where('purchase_order_internal_id', $internalId)->first();
            if ($contract) {
                $contract->update([
                    'status'           => 'review',
                    'catatan_sales'    => !empty($catatanCustomer) ? 'Tanggapan Customer: ' . $catatanCustomer : $contract->catatan_sales,
                    'alasan_penolakan' => null,
                ]);
            }
        } else {
            Contract::where('order_no', $po->po_no)->update([
                'status'           => 'review',
                'alasan_penolakan' => null,
            ]);
        }

        HistoryActivity::create([
            'user_id'       => Auth::id(),
            'activity'      => $activityNote,
            'activity_time' => now()
        ]);

        return redirect()->back()->with('success', 'Konfirmasi berhasil. Tombol Ajukan Amandemen kini telah aktif kembali.');
    }
}