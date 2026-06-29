<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Quotation;
use App\Models\PurchaseOrder;
use Exception;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PurchaseOrderExport;
use Illuminate\Http\Request;
use App\Models\ContractRequirement;

class PurchaseOrderController extends Controller
{
    /**
     * Export purchase orders to Excel
     */
    public function export(Request $request)
    {
        $filters = $request->only(['start_date','end_date','status']);
        $filename = 'purchase-orders-' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new PurchaseOrderExport($filters), $filename);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['start_date','end_date','status']);

        $query = PurchaseOrder::with([
        'quotation.request.assignment.sales' 
        ]);
        
        $query = PurchaseOrder::query();
        if (Auth::user()->role == 'customer') {
            $query->where('customer_id', '=', Auth::user()->id);
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
            // else {
            //     $query->whereIn('status', ['sent', 'amandement', 'review', 'contract']);
            // }
        $pos = $query->latest()->get();
        return view('purchase-orders.index', compact('pos','filters'));
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

        return back()->with('success','Berhasil mengurutkan kembali po');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $query = Quotation::query();
        if (Auth::user()->role == 'customer') {
            $query->where('customer_id', '=', Auth::user()->id);
        }
        $quotations = $query->whereIn('status', ['sent', 'accepted'])->get();

        $selectedQuotation = Quotation::with('customer')->find($request->quotation_id);
        if ($request->has('quotation_id')){
            $selectedQuotation = Quotation::find($request->quotation_id);
        }
        return view('purchase-orders.create', compact('quotations', 'selectedQuotation'));
    }

    public function createAmandement($id)
{
    $lastPo = PurchaseOrder::find($id);
    $user = Auth::user();

    if (! ($user->isAdmin() || ($user->isCustomer() && in_array($lastPo->status, ['sent', 'review', 'contract']))) ) {
        abort(403, 'Unauthorized action.');
    }

    // 1. Ambil data kontrak awal
    $contract = \App\Models\Contract::where('order_no', $lastPo->po_no)->first();
    
    // 2. Gunakan operator LIKE agar lebih aman dari spasi/perbedaan huruf besar-kecil bray
    $originalQuantity = $contract 
        ? \App\Models\ContractRequirement::where('contract_id', $contract->id)
            ->where('requirement', 'LIKE', '%Quantity%')
            ->value('requirement_value') 
        : 0; // Otomatis 0 jika lembar kontrak memang belum di-generate oleh Sales

    // 3. Hitung nomor amandemen berikutnya
    $lastAmendment = \App\Models\Contract::where('order_no', $lastPo->po_no)
                             ->orderByDesc('amandement_no')
                             ->first();
                             
    $nextAmendmentNo = $lastAmendment ? ($lastAmendment->amandement_no) : 1;

    return view('purchase-orders.create-amandement', compact('lastPo', 'originalQuantity', 'nextAmendmentNo'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            //code...
            $validated = $request->validate([
                'quotation_id'      => 'required|exists:quotations,id',
                'po_no'             => 'required|unique:purchase_orders,po_no',
                'attachments'       => 'required|mimes:pdf|max:10240',
                'delivery_request'  => 'required|date'
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

                //membuat nama file unik
                $filename = time() . '_' . $file->getClientOriginalName();

                //simpan file ke folder:storage/app/public/uploads
                $path = $file->storeAs('uploads', $filename, 'public');
            } else {
                $filename = null;
            }
            $validated['attachment'] = $filename;
            $validated['customer_id'] = Auth::user()->id;
            $newPO = PurchaseOrder::create($validated);
            $newPO->quotation->update([
                'status' => 'po',
                'po_date' => now()
            ]);
            \App\Models\HistoryActivity::create([
                'user_id' => Auth::user()->id,
                'activity' => 'Membuat PO',
                'activity_time' => now()->format('Y-m-d H:i:s')
            ]);

            return redirect()->route('purchase-orders.index')->with('success', 'Berhasil mengajukan PO');
        } catch (Exception $e) {
            //throw $th;
            Log::error('Error : ' . $e->getMessage());
            return redirect()->back()->with('error');
        }
    }

    /**
     * Display the specified resource.
     */
   public function show($id)
{
    $po = \App\Models\PurchaseOrder::with(['quotation.request.assignment.sales', 'customer'])->findOrFail($id);
    
    // Satukan semua role (Customer & Staff) ke file partial karena sama-sama memakai pop-up modal
    return view('purchase-orders.show-partial', compact('po'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchase_order)
    {
        // Authorize: admin can edit; staff in sales can edit when PO status is 'contract'
        $user = Auth::user();
        if (! ($user->isAdmin() || ($user->isStaff() && $user->divisi === 'sales' && in_array($purchase_order->status,['contract','review','production','ship']))) ) {
            abort(403, 'Unauthorized action.');
        }

        return view('purchase-orders.edit-partial', compact('purchase_order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        // Authorization: admin can update; staff in sales can update
        $user = Auth::user();
        if (! ($user->isAdmin() || ($user->isStaff() && $user->divisi === 'sales')) ) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:production,ship'
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

    public function createContract($idPo)
    {
        $po = PurchaseOrder::with(['quotation', 'customer'])->find($idPo);

            $user = Auth::user();
            if (!($user->isAdmin() || ($user->isStaff() && $user->divisi === 'sales' && $po->status === 'sent'))) {
                abort(403, 'Unauthorized action.');
            }

            $articles = \App\Models\Article::select('id', 'internal_part_no', 'part_name')->get();

            return view('contracts.create', compact('po', 'articles'));    }

            /**
     * Modul Alur Baru: Pelacakan Progress Order Publik 
     */
    public function trackPublic(\Illuminate\Http\Request $request)
    {
        $po_no = $request->input('po_no');
        $purchase_order = null;

        if ($po_no) {
            // Mencari data PO beserta relasi customer dan quotation-nya
            $purchase_order = \App\Models\PurchaseOrder::where('po_no', $po_no)
                ->with(['customer', 'quotation'])
                ->first();
        }

        return view('customer_home.track', compact('purchase_order', 'po_no'));
    }

    public function storeAmandement(Request $request, $id)
    {
        // 1. Cari PO yang akan diamandemen
        $po = PurchaseOrder::findOrFail($id);
        
        // Cek apakah PO ini sudah pernah di-review oleh Sales
        $kontrakAwal = Contract::where('order_no', $po->po_no)->first();
        if (!$kontrakAwal) {
            return redirect()->back()->with('error', 'Lembar Tinjauan Kontrak untuk PO ini belum dibuat oleh pihak Sales. Silakan hubungi Sales.');
        }

        // 2. Ubah status kontrak lama untuk menandakan butuh ditinjau ulang
       // 2. Ubah status kontrak lama untuk menandakan butuh ditinjau ulang
        $kontrakAwal->update([
            'status' => 'amandement', // <-- Ubah menjadi huruf kecil semua dan akhiran 't'
            'alasan_amandemen' => $request->alasan_amandemen,
            'amandement_no' => $kontrakAwal->amandement_no + 1,
        ]);

        // ===================================================================
        // 3. PROSES PENGATURAN MULTIPLE LAMPIRAN PADA PURCHASE ORDER
        // ===================================================================
        if ($request->hasFile('attachments')) {
            $file = $request->file('attachments');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('uploads', $filename, 'public');

            // Jika sudah ada lampiran sebelumnya, gabungkan dengan koma
            if (!empty($po->attachment)) {
                $po->attachment = $po->attachment . ',' . $filename;
            } else {
                $po->attachment = $filename;
            }
        }
        // ===================================================================

        // 4. Perbarui status PO induk menjadi amandement
        $po->status = 'amandement';
        $po->save();

        return redirect()->route('purchase-orders.index')->with('success', 'Pengajuan Amandemen PO berhasil dikirim. File amandemen telah ditambahkan!');
    }

}


