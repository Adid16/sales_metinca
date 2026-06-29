<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderItemController extends Controller
{
    public function index(PurchaseOrder $purchaseOrder)
    {
        $user = Auth::user();
        if ($user->isCustomer()) {
            abort(403);
        }

        $purchaseOrder->load(['quotation', 'customer', 'items']);
        $canEdit = $user->isAdmin() || ($user->isStaff() && $user->divisi === 'sales');

        return view('purchase-orders.items.index', compact('purchaseOrder', 'canEdit'));
    }

    public function store(Request $request, PurchaseOrder $purchaseOrder)
    {
        $user = Auth::user();
        if (!($user->isAdmin() || ($user->isStaff() && $user->divisi === 'sales'))) {
            abort(403);
        }

        $request->validate([
            'item.*'          => 'required|string|max:255',
            'material.*'      => 'nullable|string|max:255',
            'article.*'        => 'nullable|string|max:50',
            'qty.*'           => 'required|integer|min:1',
            'unit_price.*'    => 'required|numeric|min:0',
            'delivery_date.*' => 'nullable|date',
            'notes.*'         => 'nullable|string|max:500',
        ]);

        $purchaseOrder->items()->delete();

        foreach ($request->item as $index => $itemName) {
            if (empty($itemName)) continue;
            $qty       = $request->qty[$index] ?? 1;
            $unitPrice = $request->unit_price[$index] ?? 0;
            $purchaseOrder->items()->create([
                'item'          => $itemName,
                'material'      => $request->material[$index]      ?? null,
                'article'       => $request->article[$index]        ?? null,
                'qty'           => $qty,
                'unit_price'    => $unitPrice,
                'subtotal'      => $qty * $unitPrice,
                'delivery_date' => $request->delivery_date[$index] ?? null,
                'notes'         => $request->notes[$index]         ?? null,
            ]);
        }

        return redirect()
            ->route('purchase-orders.items.index', $purchaseOrder->id)
            ->with('success', 'Item berhasil disimpan.');
    }

    public function updateNotes(Request $request, PurchaseOrderItem $item)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);
        $item->update(['notes' => $request->notes]);
        return response()->json(['success' => true]);
    }

    public function destroy(PurchaseOrderItem $item)
    {
        $user = Auth::user();
        if (!($user->isAdmin() || ($user->isStaff() && $user->divisi === 'sales'))) {
            abort(403);
        }
        $item->delete();
        return response()->json(['success' => true]);
    }
}