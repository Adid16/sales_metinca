<?php

namespace App\Http\Controllers;

use App\Notifications\ContractApprovedNotification;
use App\Notifications\ContractNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\User;
use App\Models\Article;
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
        $filters = $request->only(['start_date','end_date','status','dept']);

        $query = Contract::query();
        if(Auth::user()->role == 'customer')
        {
            $query->where('customer_id','=',Auth::user()->id);
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

        $contracts = $query->with(['customer','quotation','internalItem'])->latest()->paginate(10)->appends($filters);
        return view('contracts.index', compact('contracts','filters'));
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
        } else {
            // JIKA BELUM PERNAH ADA KONTRAK (KONTRAK BARU / ORIGINAL)
            $latestContract  = null; 
            $amandementNo    = 0;
            $alasanAmandemen = '-';
        }

        return view('contracts.create', compact('po', 'selectedItem', 'latestContract', 'amandementNo', 'alasanAmandemen'));
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
        $contract->load(['customer','quotation','requirements','article','internalItem']);
        $requirements = $contract->requirements;
        $grouped = $requirements->groupBy('requirement_from');

        return view('contracts.show', compact('contract', 'grouped'));
    }

    public function edit(Contract $contract)
    {
        $contract->load(['requirements','internalItem']);
        return view('contracts.edit', compact('contract'));
    }

    public function update(Request $request, Contract $contract)
    {
        $request->validate([
            'customer_id'    => 'required|exists:users,id',
            'quotation_id'   => 'required|exists:quotations,id',
            'order_no'       => 'required|string|max:255',
            'amandment_no'   => 'nullable|string|max:255',
            'alasan_amandemen' => 'nullable|string',
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
                    
                    // RESET SEMUA APPROVAL MANAGER AGAR MEREKA REVIEW ULANG
                    'sales_approver'              => null,
                    'ppc_approver'                => null,
                    'quality_approver'            => null,
                    'dev_engineering_approver'    => null,
                    'sales_approved_at'           => null,
                    'ppc_approved_at'             => null,
                    'quality_approved_at'         => null,
                    'dev_engineering_approved_at' => null,
                ];

                if ($request->hasFile('po_pdf')) {
                    if ($contract->po_pdf && \Illuminate\Support\Facades\Storage::disk('public')->exists($contract->po_pdf)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($contract->po_pdf);
                    }
                    $dataUpdate['po_pdf'] = $request->file('po_pdf')->store('contracts/po', 'public');
                }

                $contract->update($dataUpdate);

                foreach ($request->requirements ?? [] as $id => $item) {
                    if (empty($item['requirement'])) continue;

                    if (is_numeric($id)) {
                        $req = $contract->requirements()->find($id);
                        if ($req) {
                            $req->update([
                                'requirement'       => $item['requirement'],
                                'requirement_value' => $item['value'] ?? null,
                            ]);
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
                    'activity'      => 'Update spesifikasi kontrak amandemen ' . ($contract->order_no ?? ''),
                    'activity_time' => now()->format('Y-m-d H:i:s')
                ]);
            });

            return redirect()
                ->route('contracts.show', $contract->id)
                ->with('success', 'Contract Amandemen & File PO berhasil diupdate.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function approveManager($contractId)
    {
        $contract = Contract::findOrFail($contractId);
        
        if(Auth::user()->divisi == 'ppc') {
            if($contract->ppc_approver != null){
                return redirect()->back()->with('error','Anda sudah melakukan approve');
            }
            $contract->ppc_approver = Auth::user()->id;
            $contract->ppc_approved_at = now();
            $approver = 'PPC';
        } elseif(Auth::user()->divisi == 'sales') {
            if($contract->sales_approver != null){
                return redirect()->back()->with('error','Anda sudah melakukan approve');
            }
            $contract->sales_approver = Auth::user()->id;
            $contract->sales_approved_at = now();
            $approver = 'Sales';
        } elseif(Auth::user()->divisi == 'design engineering') {
            if($contract->dev_engineering_approver != null){
                return redirect()->back()->with('error','Anda sudah melakukan approve');
            }
            $contract->dev_engineering_approver = Auth::user()->id;
            $contract->dev_engineering_approved_at = now();
            $approver = 'Design Engineering';
        } elseif(Auth::user()->divisi == 'quality') {
            if($contract->quality_approver != null){
                return redirect()->back()->with('error','Anda sudah melakukan approve');
            }
            $contract->quality_approver = Auth::user()->id;
            $contract->quality_approved_at = now();
            $approver = 'Quality';
        }
        $contract->save();

        $sales = User::where('role','=','staff')->get();
        foreach($sales as $pic) {
            $pic->notify(new ContractApprovedNotification($contract, $approver));
        }

        // CEK APAKAH SUDAH APPROVED OLEH SEMUA 4 MANAGER
        $allApproved = $contract->sales_approver 
                    && $contract->ppc_approver 
                    && $contract->quality_approver 
                    && $contract->dev_engineering_approver;

        if ($allApproved) {
            if ($contract->amandement_no > 0) {
                // JIKA KONTRAK HASIL AMANDEMEN: Kembalikan status ke 'created' agar dapat diproses ulang di PO External
                $contract->update([
                    'status' => 'created'
                ]);

                if ($contract->purchase_order_internal_id) {
                    PurchaseOrderInternal::where('id', $contract->purchase_order_internal_id)
                        ->update(['status' => 'created']);
                }
            } else {
                // JIKA KONTRAK PERTAMA (ASLI): Status menjadi 'contract' (Sah)
                $contract->update([
                    'status' => 'contract'
                ]);

                if ($contract->purchase_order_internal_id) {
                    PurchaseOrderInternal::where('id', $contract->purchase_order_internal_id)
                        ->update(['status' => 'contract']);
                }
            }
        }

        HistoryActivity::create([
            'user_id'       => Auth::user()->id,
            'activity'      => 'Approve kontrak ' . ($contract->contract_no ?? ''),
            'activity_time' => now()->format('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success','Berhasil approve');
    }

    public function rejectManager(Request $request, $contractId)
    {
        $contract = Contract::findOrFail($contractId);
        if(Auth::user()->divisi == 'ppc') {
            $contract->others_comment .= '; PPC-' . Carbon::parse(now())->format('d M Y'). ' : ' . $request->comment;
        } elseif(Auth::user()->divisi == 'sales') {
            $contract->others_comment .= '; Sales-' . now()->format('d M Y'). ' : ' . $request->comment;
        } elseif(Auth::user()->divisi == 'design engineering') {
            $contract->others_comment .= '; DE-' . now()->format('d M Y'). ' : ' . $request->comment;
        } elseif(Auth::user()->divisi == 'quality') {
            $contract->others_comment .= '; Quality-'. now()->format('d M Y'). ' : ' . $request->comment;
        }

        $contract->status = 'revision';
        $contract->save();

        return redirect()->back()->with('success','Komentar berhasil ditambahkan.');
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

        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream("Contract-Review-{$contract->contract_no}.pdf");
    }

    public function finalize($id)
    {
        $contract = Contract::findOrFail($id);

        if (Auth::user()->role !== 'admin' && !(Auth::user()->role === 'staff' && Auth::user()->divisi === 'sales')) { 
            return redirect()->back()->with('error', 'Akses ditolak! Hanya Staff Sales yang dapat memfinalisasi kontrak.');
        }

        if (!$contract->sales_approver || !$contract->ppc_approver || !$contract->quality_approver || !$contract->dev_engineering_approver) { 
            return redirect()->back()->with('error', 'Gagal memfinalisasi. Kontrak belum disetujui sepenuhnya oleh 4 Divisi.');
        }

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

        HistoryActivity::create([
            'user_id'       => Auth::user()->id,
            'activity'      => 'Memfinalisasi kontrak ke status In Production (Dalam Produksi) untuk PO: ' . $contract->order_no,
            'activity_time' => now()->format('Y-m-d H:i:s')
        ]);

        return redirect()->route('contracts.index')->with('success', 'Kontrak berhasil difinalisasi! Status kini In Production (Dalam Produksi).');
    }

    // DELETE /contracts/{contract}
    public function destroy($id)
    {
        $contract = Contract::findOrFail($id);
        
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