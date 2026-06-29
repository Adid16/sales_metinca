<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInternal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderInternalController extends Controller
{
    //
    private function authorizeAccess()
    {
        $user = Auth::user();
        if(!($user->isAdmin() || $user->isStaff())){
            abort(403, 'Unauthorized.');
        }
        return $user;
    }
    
    //GET  Purchase-orders-internal
    public function index(Request $request)
    {
        $this->authorizeAccess();
        $filters = $request->only(['search', 'start_date', 'end_date']);

        $query = PurchaseOrderInternal::with(['purchaseOrder.customer', 'purchaseOrder.quotation'])
            ->latest();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('item', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('po_no', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('purchaseOrder.customer', fn($q2) =>
                        $q2->where('name', 'like', '%' . $filters['search'] . '%'))
                  ->orWhereHas('purchaseOrder.quotation', fn($q2) =>
                        $q2->where('quotation_no', 'like', '%' . $filters['search'] . '%'));
            });
        }

        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        $items = $query->paginate(10);
        return view('purchase-orders-internal.index', compact('items', 'filters'));
    }

    // GET /purchase-orders-internal/{purchaseOrder}/create
    public function create(PurchaseOrder $purchaseOrder)
    {
        $this->authorizeAccess();
        
        // Tarik data customer, quotation, beserta seluruh item barang di dalamnya
        $purchaseOrder->load(['customer', 'quotation.items', 'internals']);
        
        // Kirim kedua nama variabel agar tidak ada error 'undefined variable' di Blade
        return view('purchase-orders-internal.create', [
            'purchaseOrder' => $purchaseOrder,
            'po'            => $purchaseOrder
        ]);
    }
 
    // POST /purchase-orders-internal/{purchaseOrder}
    public function store(Request $request, PurchaseOrder $purchaseOrder)
    {
        $this->authorizeAccess();
 
        $request->validate([
            'item.*'          => 'required|string|max:255',
            'material.*'      => 'nullable|string|max:255',
            'spesifikasi.*'   => 'nullable|string|max:500',
            'article.*'       => 'nullable|string|max:50',
            'qty.*'           => 'required|integer|min:1',
            'unit_price.*'    => 'required|numeric|min:0',
            'delivery_date.*' => 'nullable|date',
            'supplier.*'      => 'nullable|string|max:255',
            'po_no.*'         => 'nullable|string|max:100',
            'pic_buyer.*'     => 'nullable|string|max:255',
            'company_buyer.*' => 'nullable|string|max:255',
            'notes.*'         => 'nullable|string|max:500',
        ]);
 
        $purchaseOrder->internals()->delete();
 
        foreach ($request->item as $index => $itemName) {
            if (empty($itemName)) continue;
            $qty       = $request->qty[$index] ?? 1;
            $unitPrice = $request->unit_price[$index] ?? 0;
            $purchaseOrder->internals()->create([
                'item'          => $itemName,
                'material'      => $request->material[$index]      ?? null,
                'spesifikasi'   => $request->spesifikasi[$index]   ?? null,
                'satuan'        => $request->satuan[$index]        ?? null,
                'qty'           => $qty,
                'unit_price'    => $unitPrice,
                'subtotal'      => $qty * $unitPrice,
                'delivery_date' => $request->delivery_date[$index] ?? null,
                'supplier'      => $request->supplier[$index]      ?? null,
                'po_no'         => $request->po_no[$index]         ?? null,
                'pic_buyer'     => $request->pic_buyer[$index]     ?? null,
                'company_buyer' => $request->company_buyer[$index] ?? null,
                'notes'         => $request->notes[$index]         ?? null,
            ]);
        }
 
        \App\Models\HistoryActivity::create([
            'user_id'       => Auth::id(),
            'activity'      => 'Input PO Internal untuk PO ' . $purchaseOrder->po_no,
            'activity_time' => now()->format('Y-m-d H:i:s'),
        ]);

        // ===================================================================
        // KODE TAMBAHAN: JIKA PO AMANDEMEN, UBAH STATUS JADI ANTREAN (SENT)
        // ===================================================================
        if ($purchaseOrder->status == 'amandement') {
            $purchaseOrder->update([
                'status' => 'sent' // Mengubah status menjadi Antrean agar tombol kontrak muncul di halaman PO External
            ]);
        }
        // ===================================================================
 
        return redirect()
            ->route('purchase-orders-internal.show', $purchaseOrder->id)
            ->with('success', 'PO Internal berhasil disimpan.');
    }
 
    // GET /purchase-orders-internal/{purchaseOrder}
    public function show(PurchaseOrder $purchaseOrder)
    {
        $user = Auth::user();
        if (!($user->isAdmin() || $user->isStaff())) {
            abort(403);
        }

        $purchaseOrder->load(['customer', 'quotation', 'internals']);
        return view('purchase-orders-internal.show', compact('purchaseOrder'));
    } 

    public function showItem(PurchaseOrderInternal $internal)
    {
        $user = Auth::user();
        if (!($user->isAdmin() || $user->isStaff())) {
            abort(403);
        }
        $internal->load('purchaseOrder.quotation', 'purchaseOrder.customer');
        return view('purchase-orders-internal.show-item', compact('internal'));
    }

    // GET /purchase-orders-internal/{purchaseOrder}/edit
    public function edit(PurchaseOrder $purchaseOrder)
    {
        $this->authorizeAccess();
        $purchaseOrder->load(['customer', 'quotation', 'internals']);
        return view('purchase-orders-internal.create', compact('purchaseOrder'));
    }
 
    // PUT /purchase-orders-internal/{purchaseOrder}
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        return $this->store($request, $purchaseOrder);
    }
 
    // DELETE /purchase-orders-internal/item/{internal}
    public function destroyItem(PurchaseOrderInternal $internal)
    {
        $this->authorizeAccess();
        $internal->delete();
        return response()->json(['success' => true]);
    }
}