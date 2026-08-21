<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInternal;
use App\Models\Contract;
use App\Models\HistoryActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderInternalController extends Controller
{
    private function authorizeAccess()
    {
        $user = Auth::user();
        if (!($user->isAdmin() || $user->isStaff())) {
            abort(403, 'Unauthorized.');
        }
        return $user;
    }
    
    // GET /purchase-orders-internal
    public function index(Request $request)
    {
        $this->authorizeAccess();
        $filters = $request->only(['search', 'start_date', 'end_date']);

        $query = PurchaseOrderInternal::with([
            'purchaseOrder.customer', 
            'purchaseOrder.quotation',
            'purchaseOrder.contract'
        ])->latest();

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
    public function create(Request $request, PurchaseOrder $purchaseOrder)
    {
        $this->authorizeAccess();
        
        $internalId      = $request->query('internal_id');
        $quotationItemId = $request->query('quotation_item_id');

        $purchaseOrder->load(['customer', 'quotation.items', 'internals']);

        $selectedItem = null;
        $contract     = null;
        $qItem        = null;

        if ($internalId) {
            $selectedItem = PurchaseOrderInternal::where('purchase_order_id', $purchaseOrder->id)
                ->where('id', $internalId)
                ->first();

            $contract = Contract::where('purchase_order_internal_id', $internalId)
                ->orderByDesc('amandement_no')
                ->first();
        } elseif ($quotationItemId && $purchaseOrder->quotation) {
            $qItem = $purchaseOrder->quotation->items->where('id', $quotationItemId)->first();
        }

        // Proteksi: Jika item / PO sedang dalam status amandement_pending, alihkan dan minta Sales menyelesaikan review amandemen
        if (($contract && $contract->status === 'amandement_pending') || ($purchaseOrder->status === 'amandement_pending' && !$selectedItem)) {
            return redirect()->route('purchase-orders.index')->with('error', 'PO / Item ini sedang dalam proses pengajuan amandemen oleh Customer. Harap setujui atau tolak pengajuan amandemen terlebih dahulu pada menu PO Amandement.');
        }

        $qItemIndex = 1;
        if ($qItem && $purchaseOrder->quotation && $purchaseOrder->quotation->items) {
            $foundIndex = $purchaseOrder->quotation->items->values()->search(function($item) use ($qItem) {
                return $item->id == $qItem->id;
            });
            $qItemIndex = ($foundIndex !== false) ? ($foundIndex + 1) : 1;
        }

        // Format PO No spesifik item (Contoh: PO-2026-07-002-1)
        $itemPoNo = $selectedItem 
            ? ($selectedItem->po_no ?? ($purchaseOrder->po_no . '-' . $selectedItem->id)) 
            : ($qItem ? ($purchaseOrder->po_no . '-' . $qItemIndex) : $purchaseOrder->po_no);

        return view('purchase-orders-internal.create', [
            'purchaseOrder'   => $purchaseOrder,
            'po'              => $purchaseOrder,
            'selectedItem'    => $selectedItem,
            'contract'        => $contract,
            'itemPoNo'        => $itemPoNo,
            'internalId'      => $internalId,
            'quotationItemId' => $quotationItemId,
            'qItem'           => $qItem
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



        foreach ($request->item as $index => $itemName) {
            if (empty($itemName)) continue;
            $qty       = $request->qty[$index] ?? 1;
            $unitPrice = $request->unit_price[$index] ?? 0;

            // Generate default No PO Sub-Item jika tidak diisi manual
            $defaultItemPoNo = $purchaseOrder->po_no . '-' . ($index + 1);
            $itemPoNo        = !empty($request->po_no[$index]) ? $request->po_no[$index] : $defaultItemPoNo;
            $existingId      = !empty($request->internal_id[$index]) ? $request->internal_id[$index] : null;

            $itemData = [
                'item'          => $itemName,
                'material'      => $request->material[$index]      ?? null,
                'spesifikasi'   => $request->spesifikasi[$index]   ?? null,
                'article'       => $request->article[$index]       ?? null,
                'qty'           => $qty,
                'unit_price'    => $unitPrice,
                'subtotal'      => $qty * $unitPrice,
                'delivery_date' => $request->delivery_date[$index] ?? null,
                'supplier'      => $request->supplier[$index]      ?? null,
                'po_no'         => $itemPoNo,
                'pic_buyer'     => $request->pic_buyer[$index]     ?? null,
                'company_buyer' => $request->company_buyer[$index] ?? null,
                'notes'         => $request->notes[$index]         ?? null,
            ];

            if ($existingId && $purchaseOrder->internals()->where('id', $existingId)->exists()) {
                $purchaseOrder->internals()->where('id', $existingId)->update($itemData);
                
                // Update status kontrak item dari amandement ke created agar tombol pada PO External menjadi 'Sudah Diproses'
                $itemContract = \App\Models\Contract::where('purchase_order_internal_id', $existingId)->first();
                if ($itemContract && strtolower($itemContract->status) === 'amandement') {
                    $itemContract->update([
                        'status' => 'created'
                    ]);
                }
            } else {
                $purchaseOrder->internals()->create($itemData);
            }
        }
 
        HistoryActivity::create([
            'user_id'       => Auth::id(),
            'activity'      => 'Input PO Internal untuk PO ' . $purchaseOrder->po_no,
            'activity_time' => now()->format('Y-m-d H:i:s'),
        ]);

        if ($purchaseOrder->status == 'amandement') {
            $purchaseOrder->update([
                'status' => 'sent'
            ]);
        }
 
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
    public function edit(Request $request, PurchaseOrder $purchaseOrder)
    {
        return $this->create($request, $purchaseOrder);
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