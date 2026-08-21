<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Quotation;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInternal;
use App\Models\ContractRequirement;
use App\Models\HistoryActivity;
use App\Models\Article;
use App\Exports\PurchaseOrderExport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $query = PurchaseOrder::with(['customer', 'quotation', 'internals', 'contracts']);

        if (Auth::user()->role == 'customer') {
            $query->where('customer_id', Auth::user()->id);
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
                $query->whereNotIn('status', ['amandement', 'amandement_pending'])
                    ->whereDoesntHave('contracts', function ($q) {
                        $q->where('amandement_no', '>', 0);
                    });
            } elseif ($filters['type'] === 'amandement') {
                $query->where(function ($q) {
                    $q->whereIn('status', ['amandement', 'amandement_pending'])
                        ->orWhereHas('contracts', function ($qSub) {
                            $qSub->where('amandement_no', '>', 0);
                        });
                });
            }
        }

        $pos = $query->latest()->paginate(10)->appends($filters);

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
        $contractStatus = $contract ? strtolower($contract->status) : null;

        // Daftar status PO / Kontrak yang diizinkan untuk amandemen customer
        $allowedStatuses = ['sent', 'review', 'contract', 'created', 'po', 'approved', 'production'];

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

        $lastAmendment = Contract::where('order_no', $lastPo->po_no)
                                 ->orderByDesc('amandement_no')
                                 ->first();
                                 
        $nextAmendmentNo = $lastAmendment ? ($lastAmendment->amandement_no) : 1;

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
                'activity'      => 'Membuat PO',
                'activity_time' => now()->format('Y-m-d H:i:s')
            ]);

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
            'customer', 
            'internals.contract'
        ])->findOrFail($id);

        $internalId = $request->query('internal_id');
        $selectedItem = null;

        if ($internalId) {
            $selectedItem = $po->internals->where('id', $internalId)->first();
        }

        return view('purchase-orders.show-partial', compact('po', 'selectedItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchase_order)
    {
        $user = Auth::user();
        if (! ($user->isAdmin() || ($user->isStaff() && $user->divisi === 'sales' && in_array($purchase_order->status, ['contract', 'review', 'production', 'ship']))) ) {
            abort(403, 'Unauthorized action.');
        }

        return view('purchase-orders.edit-partial', compact('purchase_order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
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

    /**
     * Membuka Form Pembuatan Kontrak Berdasarkan PO dan Item Spesifik
     */
    public function createContract(Request $request, $idPo)
    {
        if (!auth()->user()->isAdmin() && strtolower(auth()->user()->divisi) !== 'sales') {
            abort(403, 'UNAUTHORIZED ACTION.');
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
        $po = PurchaseOrder::findOrFail($id);
        $internalId = $request->input('purchase_order_internal_id');

        // Cari Kontrak Spesifik berdasarkan purchase_order_internal_id
        if ($internalId) {
            $kontrakAwal = Contract::where('purchase_order_internal_id', $internalId)->first();
        } else {
            $kontrakAwal = Contract::where('order_no', $po->po_no)->first();
        }

        if (!$kontrakAwal) {
            // Jika kontrak belum dibuat oleh Sales, buatkan record kontrak awal berstatus amandement_pending
            $kontrakAwal = Contract::create([
                'customer_id'                => $po->customer_id,
                'quotation_id'               => $po->quotation_id,
                'purchase_order_internal_id' => $internalId,
                'order_no'                   => $po->po_no,
                'contract_no'                => 'CTR-' . $po->po_no . ($internalId ? '-' . $internalId : ''),
                'status'                     => 'amandement_pending',
                'alasan_amandemen'           => $request->alasan_amandemen,
                'amandement_no'              => 1,
            ]);
        } else {
            // Catat pengajuan amandemen spesifik HANYA pada kontrak item ini
            $kontrakAwal->update([
                'alasan_amandemen' => $request->alasan_amandemen,
                'amandement_no'    => ($kontrakAwal->amandement_no ?? 0) + 1,
                'status'           => 'amandement_pending', // Status amandemen pending khusus item ini
            ]);
        }

        // Handle Upload File Lampiran Baru jika ada
        if ($request->hasFile('attachments')) {
            $file = $request->file('attachments');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('uploads', $filename, 'public');

            $po->attachment = !empty($po->attachment) ? $po->attachment . ',' . $filename : $filename;
            $po->save();
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

        // Ambil SEMUA kontrak item yang sedang diajukan amandemennya (status = amandement_pending)
        $pendingContracts = Contract::with(['customer', 'quotation', 'purchaseOrderInternal.purchaseOrder.customer'])
            ->where('status', 'amandement_pending')
            ->latest('updated_at')
            ->get();

        return view('purchase-orders.approval-amandement', compact('pendingContracts'));
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

        if ($contract) {
            $contract->update([
                'status'           => 'amandement',
                'alasan_penolakan' => null,
                'catatan_sales'    => $request->input('catatan', 'Amandemen item disetujui.')
            ]);
        }

        HistoryActivity::create([
            'user_id'       => $user->id,
            'activity'      => 'Menyetujui Amandemen PO No: ' . ($contract->order_no ?? $po->po_no) . ($contract ? ' (Kontrak: ' . $contract->contract_no . ')' : ''),
            'activity_time' => now()
        ]);

        return redirect()->back()->with('success', 'Amandemen item berhasil disetujui. Status item diperbarui.');
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

        if ($po->customer) {
            try {
                $po->customer->notify(new \App\Notifications\AmendmentRejectedNotification($po, $request->alasan_penolakan));
            } catch (\Exception $e) {
                // notification log
            }
        }

        HistoryActivity::create([
            'user_id'       => $user->id,
            'activity'      => 'Menolak Amandemen PO No: ' . ($contract->order_no ?? $po->po_no) . ' (Alasan: ' . $request->alasan_penolakan . ')',
            'activity_time' => now()
        ]);

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