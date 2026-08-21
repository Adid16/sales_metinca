<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Quotation;
use App\Models\RequestProject;
use App\Models\User;
use App\Models\Account;
use App\Models\Negotiate;
use App\Models\HistoryActivity;
use App\Notifications\QuotationSendNotification;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'status']);

        $query = Quotation::with(['customer', 'items', 'request.assignment.sales']);

        if (Auth::user()->isCustomer()) {
            $query->where('customer_id', '=', Auth::id())
                ->where('date_expired', '>=', now())
                ->whereDoesntHave('purchaseOrder')
                ->whereIn('status', ['sent', 'accepted', 'negotiating']);
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

        $quotations = $query->latest()->get();

        return view('quotations.index', compact('quotations', 'filters'));
    }
    
    public function create(Request $request)
    {
        $quotationNo = $this->generateQuotationNo();
        
        $requestProject = null;
        $customerAccount = null;

        if ($request->has('request_id')) {
            $existingQuotation = Quotation::where('request_id', $request->request_id)->first();
            if ($existingQuotation) {
                return redirect()->route('requests-project.show', $request->request_id)
                    ->with('error', 'Penawaran harga (Quotation #' . $existingQuotation->quotation_no . ') untuk Request ini sudah pernah dibuat.');
            }

            $requestProject = RequestProject::with(['attachments', 'customer', 'assignment.sales'])
                ->find($request->request_id);

            if ($requestProject && $requestProject->customer_id) {
                $customerAccount = Account::where('user_id', $requestProject->customer_id)->first();
            }
        }

        $customers = User::with(['requestProjects.attachments'])
            ->whereHas('requestProjects')
            ->where('role', '=', 'customer')
            ->get();

        return view('quotations.create', compact('customers', 'requestProject', 'quotationNo', 'customerAccount'));
    }

    public function store(Request $request)
    {
        try {
            if ($request->hasFile('attachments')) {
                $file = $request->file('attachments');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads', $filename, 'public');
            } else {
                $filename = null;
            }

            $requestProject = RequestProject::find($request->request_id);
            $customerId = $requestProject?->customer_id;

            if (!$customerId && $requestProject?->email) {
                $customer = User::where('email', $requestProject->email)->first();
                $customerId = $customer?->id;
            }

            // BAGIAN PERUBAHAN: Menyimpan data delivery date dan payment terms dari form creation penawaran mase
            $quotation = Quotation::create([
                'customer_id'          => $request->customer_id,
                'request_id'           => $request->request_id,
                'quotation_no'         => $request->quotation_no,
                'date_expired'         => $request->date_expired,
                'notes'                => $request->notes,
                'attachments'          => json_encode($filename),
                'target_delivery_date' => $request->target_delivery_date,
                'payment_terms'        => $request->payment_terms,
            ]);

            if ($request->has('item') && is_array($request->item)) {
                foreach ($request->item as $index => $itemName) {
                    if (!empty($itemName)) {
                        $quotation->items()->create([
                            'item'  => $itemName,
                            'qty'   => $request->qty[$index] ?? 0,
                            'price' => $request->price[$index] ?? 0,
                        ]);
                    }
                }
            }

            HistoryActivity::create([
                'user_id'       => Auth::id(),
                'activity'      => 'Membuat quotation',
                'activity_time' => now()->format('Y-m-d H:i:s')
            ]);

            return redirect()->route('quotations.index')->with('success', 'Data berhasil disimpan');
        } catch (Exception $e) {
            Log::error('error : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function send(Request $request, $id)
    {
        try {
            $quotation = Quotation::findOrFail($id);
            $quotation->update([
                'status'    => 'sent',
                'sent_date' => now()
            ]);

            $quotation->customer->notify(new QuotationSendNotification($quotation));
            
            HistoryActivity::create([
                'user_id'       => Auth::id(),
                'activity'      => 'Mengirim quotation',
                'activity_time' => now()->format('Y-m-d H:i:s')
            ]);

            return redirect()->back()->with('success', 'Quotation sent');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function show(Quotation $quotation)
    {
        $quotation->load([
            'customer',
            'items',
            'purchaseOrder.internals.contract',
            'request.attachments',
            'negotiates' => function($q) {
                $q->with('user')->orderBy('created_at', 'desc');
            }
        ]);

        $customerAccount = Account::where('user_id', $quotation->customer_id)->first();
        $negotiations = $quotation->negotiates;
        $po = $quotation->purchaseOrder ?? \App\Models\PurchaseOrder::with(['internals.contract'])->where('quotation_id', $quotation->id)->first();

        return view('quotations.show', compact('quotation', 'customerAccount', 'negotiations', 'po'));
    }

    public function edit(Quotation $quotation)
    {
        return view('quotations.edit', compact('quotation'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        $data = $request->except(['_token', '_method', 'attachments', 'item_ids', 'item', 'qty', 'price']);
        
        if ($request->hasFile('attachments')) {
            $file = $request->file('attachments');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/quotation'), $filename);
            $data['attachments'] = $filename;
        }

        if ($request->has('item') && is_array($request->item)) {
            $existingItemIds = $quotation->items->pluck('id')->toArray();
            $keptItemIds = [];

            foreach ($request->item as $index => $itemName) {
                if (!empty($itemName)) {
                    $itemId = $request->item_ids[$index] ?? null;
                    
                    $itemData = [
                        'item'  => $itemName,
                        'qty'   => $request->qty[$index] ?? 0,
                        'price' => $request->price[$index] ?? 0, 
                    ];

                    if (!empty($itemId)) {
                        $quotation->items()->where('id', $itemId)->update($itemData);
                        $keptItemIds[] = $itemId;
                    } else {
                        $newItem = $quotation->items()->create($itemData);
                        $keptItemIds[] = $newItem->id;
                    }
                }
            }

            $itemsToDelete = array_diff($existingItemIds, $keptItemIds);
            if (!empty($itemsToDelete)) {
                $quotation->items()->whereIn('id', $itemsToDelete)->delete();
            }
        }

        HistoryActivity::create([
            'user_id'       => Auth::id(),
            'activity'      => 'Mengupdate quotation',
            'activity_time' => now()->format('Y-m-d H:i:s')
        ]);

        $quotation->update($data);

        return redirect()->route('quotations.index')->with('success', 'Quotation updated successfully');
    }

    public function export(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'status']);
        $filename = 'quotations-' . now()->format('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\QuotationExport($filters), $filename);
    }

    public function exportPdf(Quotation $quotation)
    {
        $quotation->load(['customer', 'items', 'request.attachments']);
        $customerAccount = Account::where('user_id', $quotation->customer_id)->first();
        $pdf = Pdf::loadView('quotations.quotation-pdf', compact('quotation', 'customerAccount'))->setPaper('a4', 'portrait');

        return $pdf->download('quotation-' . $quotation->quotation_no . '.pdf');
    }

    /* 
    |--------------------------------------------------------------------------
    | FUNGSI BARU: ALUR PROSES NEGOSIASI MULTI-ITEM
    |--------------------------------------------------------------------------
    */
    public function negotiate($id)
    {
        $quotation = Quotation::with('items')->findOrFail($id);
        
        $lastNegotiation = Negotiate::where('quotation_id', $id)
            ->where('action', 'negotiate')
            ->latest()
            ->first();

        $negotiations = Negotiate::where('quotation_id', $id)->latest()->get();
        $customerAccount = Account::where('user_id', $quotation->customer_id)->first();

        return view('quotations.negotiate', compact('quotation', 'lastNegotiation', 'negotiations', 'customerAccount'));
    }

    public function storeNegotiate(Request $request, $id)
    {
        $quotation = Quotation::with('items')->findOrFail($id);
        
        $request->validate([
            'negotiation_message'  => 'required|string',
            'target_delivery_date' => 'nullable|date',
            'payment_terms'        => 'nullable|string',
            'support_document'     => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        if ($request->action === 'negotiate') {
            $documentPath = null;
            if ($request->hasFile('support_document')) {
                $documentPath = $request->file('support_document')->store('negotiation_docs', 'public');
            }

            $negotiatedItems = [];
            $negotiatedTotal = 0;

            foreach ($request->items as $itemData) {
                $quotationItem = $quotation->items->find($itemData['id']);
                if ($quotationItem) {
                    $subtotal = $itemData['negotiated_price'] * $quotationItem->qty;
                    $negotiatedTotal += $subtotal;

                    $negotiatedItems[] = [
                        'id'               => $quotationItem->id,
                        'qty'              => $quotationItem->qty,
                        'item'             => $quotationItem->item,
                        'original_price'   => $quotationItem->price,
                        'negotiated_price' => $itemData['negotiated_price'],
                        'subtotal'         => $subtotal
                    ];
                }
            }

            Negotiate::create([
                'quotation_id'         => $quotation->id,
                'user_id'              => Auth::id(),
                'from_customer'        => Auth::user()->role === 'customer' ? 1 : 0,
                'message'              => $request->negotiation_message,
                'negotiated_total'     => $negotiatedTotal,
                'payment_terms'        => $request->payment_terms,
                'target_delivery_date' => $request->target_delivery_date,
                'support_document'     => $documentPath,
                'action'               => 'negotiate',
                'negotiated_items'     => $negotiatedItems,
            ]);

            $quotation->update(['status' => 'negotiating']);

            HistoryActivity::create([
                'user_id'       => Auth::id(),
                'activity'      => Auth::user()->role === 'customer' 
                    ? 'Customer mengajukan negosiasi quotation ' . $quotation->quotation_no 
                    : 'Staff membalas negosiasi quotation ' . $quotation->quotation_no,
                'activity_time' => now()->format('Y-m-d H:i:s')
            ]);

            return redirect()->route('quotations.index')->with('success', 'Tawaran negosiasi baru berhasil dikirim.');
        }

        if ($request->action === 'accept') {
            $lastNego = Negotiate::where('quotation_id', $quotation->id)
                ->where('action', 'negotiate')
                ->latest()
                ->first();

            if (!$lastNego) {
                return redirect()->back()->with('error', 'Belum ada data negosiasi harga yang bisa disepakati.');
            }

            foreach ($lastNego->negotiated_items as $negoItem) {
                $quotation->items()->where('id', $negoItem['id'])->update([
                    'price' => $negoItem['negotiated_price']
                ]);
            }

            $quotation->update([
                'status'               => 'accepted',
                'accepted_date'        => now(),
                'payment_terms'        => $lastNego->payment_terms,
                'target_delivery_date' => $lastNego->target_delivery_date
            ]);

            Negotiate::create([
                'quotation_id'         => $quotation->id,
                'user_id'              => Auth::id(),
                'from_customer'        => Auth::user()->role === 'customer' ? 1 : 0,
                'message'              => $request->negotiation_message,
                'negotiated_total'     => $lastNego->negotiated_total,
                'payment_terms'        => $lastNego->payment_terms,
                'target_delivery_date' => $lastNego->target_delivery_date,
                'action'               => 'closed',
                'negotiated_items'     => $lastNego->negotiated_items
            ]);

            HistoryActivity::create([
                'user_id'       => Auth::id(),
                'activity'      => 'Menutup & menyepakati negosiasi quotation ' . $quotation->quotation_no,
                'activity_time' => now()->format('Y-m-d H:i:s')
            ]);

            return redirect()->route('quotations.index')->with('success', 'Negosiasi berhasil disepakati.');
        }
    }

    private function generateQuotationNo(): string
    {
        $prefix = 'QT';
        $year = now()->format('Y');
        $month = now()->format('m');

        $last = Quotation::where('quotation_no', 'like', "{$prefix}-{$year}-{$month}-%")
            ->orderBy('quotation_no', 'desc')
            ->first();

        if ($last) {
            $lastNo = (int) substr($last->quotation_no, -4);
            $newNo  = str_pad($lastNo + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNo = '0001';
        }

        return "{$prefix}-{$year}-{$month}-{$newNo}";
    }
}