<?php

namespace App\Http\Controllers;

use App\Notifications\ContractApprovedNotification;
use App\Notifications\ContractNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;
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

        $contracts = $query->with(['customer','quotation'])->latest()->paginate(10)->appends($filters);
        return view('contracts.index',compact('contracts','filters'));
    }

    public function create()
    {
        return view('contracts.create');
    }

    public function store(Request $request)
    {
        // ===============================
        // VALIDATION (Ditambahkan aturan untuk po_pdf)
        // ===============================
        $request->validate([
            'customer_id'   => 'required|exists:users,id',
            'quotation_id'  => 'required|exists:quotations,id',
            'order_no'      => 'required|string|max:255',
            'amandment_no'  => 'nullable|string|max:255',
            'part_no'       => 'nullable|string|max:255',
            'part_name'     => 'nullable|string|max:255',
            'others_comment'=> 'nullable|string',
            'po_pdf'        => 'nullable|file|mimes:pdf|max:2048', // Batasi maksimal 2MB PDF

            'requirements'                      => 'nullable|array',
            'requirements.*.*.requirement_from' => 'required|string|in:sales,quality,ppc,design engineering',
            'requirements.*.*.requirement'      => 'required|string|max:255',
            'requirements.*.*.requirement_value'=> 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {

            $contract = Contract::where('order_no', $request->order_no)->first();

            // Siapkan data kontrak dasar
            $dataContract = [
                'customer_id'   => $request->customer_id,
                'quotation_id'  => $request->quotation_id,
                'amandement_no' => $request->amendment_no ?? $request->amandment_no ?? 0, 
                'part_no'       => $request->part_no,
                'part_name'     => $request->part_name,
                'others_comment'=> $request->others_comment,
                'article_id'    => $request->article_id,
            ];

            // LOGIKA UPLOAD FILE PO PDF BARU
            if ($request->hasFile('po_pdf')) {
                $filePath = $request->file('po_pdf')->store('contracts/po', 'public');
                $dataContract['po_pdf'] = $filePath;
            }

            if ($contract) {
                // JIKA KONTRAK SUDAH ADA: Update data & status amandement
                $dataContract['status'] = 'amandement';
                $contract->update($dataContract);

                // Bersihkan data requirement departemen lama agar tidak menumpuk duplikat
                $contract->requirements()->delete();

            } else {
                // JIKA BELUM ADA KONTRAK SAMA SEKALI: Buat baru
                $dataContract['order_no'] = $request->order_no;
                $dataContract['status'] = 'Created';
                $contract = Contract::create($dataContract);
            }

            // Pastikan status Purchase Order eksternal ikut ter-update
            if ($contract->quotation && $contract->quotation->purchaseOrder) {
                $contract->quotation->purchaseOrder->update([
                    'status' => 'review'
                ]);
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

            \App\Models\HistoryActivity::create([
                'user_id' => Auth::user()->id,
                'activity' => 'Memperbarui kontrak amandemen untuk PO: ' . $request->order_no,
                'activity_time' => now()->format('Y-m-d H:i:s')
            ]);

            $manager = User::where('role','=','manager')->get();
            foreach($manager as $usr)
            {
                $usr->notify(new ContractNotification($contract));
            }
        });

        return redirect()
            ->route('contracts.index')
            ->with('success', 'Data amandemen dan file PO PDF berhasil disimpan langsung ke kontrak.');
    }

    public function show(Contract $contract)
    {
        $contract->load(['customer','quotation','requirements','article']);
        $requirements = $contract->requirements;
        $grouped = $requirements->groupBy('requirement_from');

        return view('contracts.show', compact('contract', 'grouped'));
    }

    public function edit(Contract $contract)
    {
        $contract->load(['requirements']);
        return view('contracts.edit',compact('contract'));
    }

    
    public function update(Request $request, Contract $contract)
    {
        $request->validate([
            'customer_id'   => 'required|exists:users,id',
            'quotation_id'  => 'required|exists:quotations,id',
            'order_no'      => 'required|string|max:255',
            'amandment_no'  => 'nullable|string|max:255',
            'part_no'       => 'nullable|string|max:255',
            'part_name'     => 'nullable|string|max:255',
            'others_comment'=> 'nullable|string',
            'po_pdf'        => 'nullable|file|mimes:pdf|max:2048', // Validasi file PDF baru
        ]);

        try {
            DB::transaction(function () use ($request, $contract) {

                $dataUpdate = [
                    'customer_id'   => $request->customer_id,
                    'quotation_id'  => $request->quotation_id,
                    'order_no'      => $request->order_no,
                    'amandement_no' => $request->amendment_no ?? $request->amandment_no ?? $contract->amandement_no,
                    'part_no'       => $request->part_no,
                    'part_name'     => $request->part_name,
                    'others_comment'=> $request->others_comment,
                    'article_id'    => $request->article_id ?? null,
                    'status'        => 'Created', 
                    
                    // RESET SEMUA APPROVAL MANAGER AGAR MEREKA REVIEW ULANG AMANDEMENNYA
                    'sales_approver' => null,
                    'ppc_approver' => null,
                    'quality_approver' => null,
                    'dev_engineering_approver' => null,
                    'sales_approved_at' => null,
                    'ppc_approved_at' => null,
                    'quality_approved_at' => null,
                    'dev_engineering_approved_at' => null,
                ];

                // KONDISI JIKA ADA FILE PDF BARU YANG DIUPLOAD SAAT EDIT
                if ($request->hasFile('po_pdf')) {
                    // Hapus file lama jika ada untuk menghemat ruang disk
                    if ($contract->po_pdf && \Illuminate\Support\Facades\Storage::disk('public')->exists($contract->po_pdf)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($contract->po_pdf);
                    }
                    // Simpan file baru
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

                if ($contract->quotation && $contract->quotation->purchaseOrder) {
                    $contract->quotation->purchaseOrder->update([
                        'status' => 'review'
                    ]);
                }

                \App\Models\HistoryActivity::create([
                    'user_id' => Auth::user()->id,
                    'activity' => 'Update spesifikasi kontrak amandemen ' . ($contract->order_no ?? ''),
                    'activity_time' => now()->format('Y-m-d H:i:s')
                ]);
            });

            return redirect()
                ->route('contracts.show', $contract->id)
                ->with('success', 'Contract Amandemen & File PO berhasil diupdate & diteruskan ke Manager untuk di-Review ulang.');

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
        
        if(Auth::user()->divisi == 'ppc')
        {
            if($contract->ppc_approver != null){
                return redirect()->back()->with('error','Anda sudah melakukan approve');
            }
            $contract->ppc_approver = Auth::user()->id;
            $contract->ppc_approved_at = now();
            $approver = 'PPC';
        }elseif(Auth::user()->divisi == 'sales')
        {
            if($contract->sales_approver != null){
                return redirect()->back()->with('error','Anda sudah melakukan approve');
            }
            $contract->sales_approver = Auth::user()->id;
            $contract->sales_approved_at = now();
            $approver = 'Sales';
        }elseif(Auth::user()->divisi == 'design engineering')
        {
            if($contract->dev_engineering_approver != null){
                return redirect()->back()->with('error','Anda sudah melakukan approve');
            }
            $contract->dev_engineering_approver = Auth::user()->id;
            $contract->dev_engineering_approved_at = now();
            $approver = 'Design Engineering';
        }elseif(Auth::user()->divisi == 'quality')
        {
            if($contract->quality_approver != null){
                return redirect()->back()->with('error','Anda sudah melakukan approve');
            }
            $contract->quality_approver = Auth::user()->id;
            $contract->quality_approved_at = now();
            $approver = 'Quality';
        }
        $contract->save();

        $sales = User::where('role','=','staff')->get();
        foreach($sales as $pic)
        {
            $pic->notify(new ContractApprovedNotification($contract, $approver));
        }

        $contract->isDone();

        // ===================================================================
        // KODE AUTOMATION: OTOMATIS BALIKIN STATUS PO KE REVIEW
        // ===================================================================
        $po = \App\Models\PurchaseOrder::where('po_no', $contract->order_no)->first();
        if ($po && $po->status == 'amandement') {
            $po->update([
                'status' => 'review' 
            ]);
        }
        // ===================================================================

        \App\Models\HistoryActivity::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Approve kontrak ' . ($contract->contract_no ?? ''),
            'activity_time' => now()->format('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success','Berhasil approve');
    }

    public function rejectManager(Request $request, $contractId)
    {
        $contract = Contract::findOrFail($contractId);
        if(Auth::user()->divisi == 'ppc')
        {
            $contract->others_comment .= '; PPC-' . Carbon::parse(now())->format('d M Y'). ' : ' . $request->comment;
        }elseif(Auth::user()->divisi == 'sales')
        {
            $contract->others_comment .= '; Sales-' . now()->format('d M Y'). ' : ' . $request->comment;
        }elseif(Auth::user()->divisi == 'design engineering')
        {
            $contract->others_comment .= '; DE-' . now()->format('d M Y'). ' : ' . $request->comment;
        }elseif(Auth::user()->divisi == 'quality')
        {
            $contract->others_comment .= '; PPC-'. now()->format('d M Y'). ' : ' . $request->comment;
        }

        $contract->status = 'revision';
        $contract->save();

        return redirect()->back()->with('success','Komentar berhasil ditambahkan.');
    }

    public function generatePdf($contractId)
    {
        $contract = Contract::with(['requirements'])->find($contractId);
        $requirements = $contract->requirements;

        // 1. Cek apakah user yang login adalah Admin atau dari Divisi Sales
        $isAuthorized = Auth::user()->role == 'admin' || strtolower(Auth::user()->divisi) == 'sales';
        
        // 2. Jika BUKAN Sales/Admin, samarkan nilai 'Price' sebelum dicetak ke PDF
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

    /**
     * Generate PDF with custom contract data
     */
    public function generatePdfWithContract(Request $request, $articleId)
    {
        $article = Article::with(['detailArticles' => function($query) {
            $query->orderBy('dept_code')->orderBy('created_at');
        }, 'detailArticles.checkBy'])->findOrFail($articleId);

        $contract = (object)[
            'contract_number' => $request->input('contract_number', '3701068'),
            'customer' => $request->input('customer', 'S6 / SPECK PUMPEN WALTER SPECK GMBH'),
            'order_number' => $request->input('order_number', '6183500'),
            'amendment_number' => $request->input('amendment_number', ''),
            'location' => $request->input('location', 'PT.Metinca (Jakarta)'),
            'data_record' => $request->input('data_record', '2025-01-20'),
            'others_comment' => $request->input('others_comment', ''),
        ];

        $pdf = Pdf::loadView('contracts.pdf', [
            'article' => $article,
            'contract' => $contract,
        ]);

        $pdf->setPaper('A4', 'portrait');
        $filename = "Contract-Review-{$contract->contract_number}-{$article->article_number}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Preview PDF in browser
     */
    public function previewPdf($articleId)
    {
        $article = Article::with(['detailArticles' => function($query) {
            $query->orderBy('dept_code')->orderBy('created_at');
        }, 'detailArticles.checkBy'])->findOrFail($articleId);

        $contract = (object)[
            'contract_number' => '3701068',
            'customer' => 'S6 / SPECK PUMPEN WALTER SPECK GMBH',
            'order_number' => '6183500',
            'amendment_number' => '',
            'location' => 'PT.Metinca (Jakarta)',
            'data_record' => '2025-01-20',
            'others_comment' => '',
        ];

        return view('contracts.pdf', compact('article', 'contract'));
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

    // Mengubah status menjadi 'production' karena sudah terdaftar di ENUM migration
    $contract->status = 'production'; 
    $contract->save();

    $po = \App\Models\PurchaseOrder::where('po_no', $contract->order_no)->first();
    if ($po) {
        $po->update([
            'status' => 'production' 
        ]);
    }

    \App\Models\HistoryActivity::create([
        'user_id' => Auth::user()->id,
        'activity' => 'Memfinalisasi kontrak ke tahap produksi untuk PO: ' . $contract->order_no,
        'activity_time' => now()->format('Y-m-d H:i:s')
    ]);

    return redirect()->route('contracts.index')->with('success', 'Kontrak berhasil difinalisasi! Status pesanan kini berada Dalam Produksi.');
}

}