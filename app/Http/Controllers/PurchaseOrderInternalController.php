<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInternal;
use App\Models\Contract;
use App\Models\HistoryActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderInternalController extends Controller
{
    private function authorizeAccess()
    {
        $user = Auth::user();
        if (!($user->isAdmin() || $user->isStaff() || $user->isManager())) {
            abort(403, 'Unauthorized.');
        }
        return $user;
    }

    private function checkPicAuthorization(PurchaseOrder $purchaseOrder): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        // Admin & Manager Sales memiliki hak supervisi manajerial
        if ($user->isAdmin() || ($user->isManager() && $user->divisi === 'sales')) {
            return true;
        }

        // Staff Sales harus sesuai dengan Sales PIC
        if ($user->isStaff() && $user->divisi === 'sales') {
            $salesPic = $purchaseOrder->sales_pic;
            if ($salesPic && $salesPic->id !== $user->id) {
                return false;
            }
            return true;
        }

        return false;
    }
    
    // GET /purchase-orders-internal
    public function index(Request $request)
    {
        $this->authorizeAccess();
        $user = Auth::user();
        $filters = $request->only(['search', 'start_date', 'end_date']);

        $query = PurchaseOrder::has('internals')->with([
            'customer', 
            'quotation.request.assignment.sales',
            'contract',
            'internals.contract'
        ]);

        // Staff Sales HANYA MELIHAT PO Internal yang di-PIC oleh dirinya sendiri
        if ($user->isStaff() && $user->divisi === 'sales') {
            $query->whereHas('quotation.request.assignment', function ($q) use ($user) {
                $q->where('sales_id', $user->id);
            });
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('po_no', 'like', "%{$search}%")
                ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                ->orWhereHas('quotation', fn($q2) => $q2->where('quotation_no', 'like', "%{$search}%"))
                ->orWhereHas('internals', fn($q2) => $q2->where('item', 'like', "%{$search}%")->orWhere('po_no', 'like', "%{$search}%"));
            });
        }

        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        $query->orderBy('created_at', 'desc');

        $pos = $query->paginate(10);
        return view('purchase-orders-internal.index', compact('pos', 'filters'));
    }

    // GET /purchase-orders-internal/{purchaseOrder}/create
    public function create(Request $request, PurchaseOrder $purchaseOrder)
    {
        $this->authorizeAccess();

        if (!$this->checkPicAuthorization($purchaseOrder)) {
            return redirect()->route('purchase-orders.index')
                ->with('error', 'Akses ditolak. Hanya Sales PIC penanggung jawab (' . ($purchaseOrder->sales_pic->name ?? 'Sales PIC') . ') yang berhak membuat PO Internal untuk pesanan ini.');
        }
        
        $internalId      = $request->query('internal_id');
        $quotationItemId = $request->query('quotation_item_id');

        $purchaseOrder->load(['customer.account', 'quotation.customer.account', 'quotation.items.article', 'internals']);

        // Pastikan relasi article di quotation->items selalu ter-resolve jika quotation ada
        if ($purchaseOrder->quotation && $purchaseOrder->quotation->items) {
            foreach ($purchaseOrder->quotation->items as $qItemRow) {
                if (!$qItemRow->article) {
                    $foundArticle = \App\Models\Article::where('id', $qItemRow->article_id)
                        ->orWhere('part_name', $qItemRow->item)
                        ->orWhere('article_no', $qItemRow->item)
                        ->orWhere('internal_part_no', $qItemRow->item)
                        ->first();
                    if ($foundArticle) {
                        $qItemRow->setRelation('article', $foundArticle);
                    }
                }
            }
        }

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

        // Ambil profil nama perusahaan buyer
        $customerCompany = $purchaseOrder->customer?->company 
            ?? $purchaseOrder->customer?->account?->company 
            ?? $purchaseOrder->company 
            ?? $purchaseOrder->quotation?->company 
            ?? '';

        return view('purchase-orders-internal.create', [
            'purchaseOrder'   => $purchaseOrder,
            'po'              => $purchaseOrder,
            'selectedItem'    => $selectedItem,
            'contract'        => $contract,
            'itemPoNo'        => $itemPoNo,
            'internalId'      => $internalId,
            'quotationItemId' => $quotationItemId,
            'qItem'           => $qItem,
            'customerCompany' => $customerCompany,
        ]);
    }
 
    // POST /purchase-orders-internal/{purchaseOrder}
    public function store(Request $request, PurchaseOrder $purchaseOrder)
    {
        $this->authorizeAccess();

        if (!$this->checkPicAuthorization($purchaseOrder)) {
            return redirect()->route('purchase-orders.index')
                ->with('error', 'Akses ditolak. Hanya Sales PIC penanggung jawab (' . ($purchaseOrder->sales_pic->name ?? 'Sales PIC') . ') yang berhak menginput PO Internal untuk pesanan ini.');
        }

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

        // ====================================================================
        // MODUL 6: Wrap proses simpan multi-item dalam DB::transaction
        // ====================================================================
        DB::transaction(function () use ($request, $purchaseOrder) {
            foreach ($request->item as $index => $itemName) {
                if (empty($itemName)) continue;
                $qty       = $request->qty[$index] ?? 1;
                $unitPrice = $request->unit_price[$index] ?? 0;

                // Generate default No PO Sub-Item format konsisten: {PO_NO}-{index}
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
                    'status'        => 'created',
                ];

                if ($existingId && $purchaseOrder->internals()->where('id', $existingId)->exists()) {
                    $purchaseOrder->internals()->where('id', $existingId)->update($itemData);
                    
                    // Update status kontrak item dari amandement ke created
                    $itemContract = Contract::where('purchase_order_internal_id', $existingId)->first();
                    if ($itemContract && strtolower($itemContract->status) === 'amandement') {
                        $itemContract->update(['status' => 'created']);
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
                $purchaseOrder->update(['status' => 'sent']);
            }
        });

        return redirect()
            ->route('purchase-orders-internal.show', $purchaseOrder->id)
            ->with('success', 'PO Internal berhasil disimpan.');
    }
 
    // GET /purchase-orders-internal/{purchaseOrder}
    public function show(PurchaseOrder $purchaseOrder)
    {
        $this->authorizeAccess();

        if (!$this->checkPicAuthorization($purchaseOrder)) {
            return redirect()->route('purchase-orders-internal.index')
                ->with('error', 'Akses ditolak. PO Internal ini ditangani oleh Sales PIC lain (' . ($purchaseOrder->sales_pic->name ?? 'Sales PIC') . ').');
        }

        $purchaseOrder->load(['customer', 'quotation', 'internals']);
        return view('purchase-orders-internal.show', compact('purchaseOrder'));
    } 

    public function showItem(PurchaseOrderInternal $internal)
    {
        $this->authorizeAccess();

        if ($internal->purchaseOrder && !$this->checkPicAuthorization($internal->purchaseOrder)) {
            return redirect()->route('purchase-orders-internal.index')
                ->with('error', 'Akses ditolak. Item PO Internal ini ditangani oleh Sales PIC lain (' . ($internal->purchaseOrder->sales_pic->name ?? 'Sales PIC') . ').');
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

        if ($internal->purchaseOrder && !$this->checkPicAuthorization($internal->purchaseOrder)) {
            return response()->json(['success' => false, 'error' => 'Akses ditolak. Hanya Sales PIC penanggung jawab yang berhak menghapus item ini.'], 403);
        }

        $internal->delete();
        return response()->json(['success' => true]);
    }
}