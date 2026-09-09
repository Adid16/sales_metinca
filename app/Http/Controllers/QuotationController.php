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
use App\Models\Article;
use App\Services\SystemSettingService;
use App\Notifications\QuotationSendNotification;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'status']);

        $query = Quotation::with(['customer', 'items', 'request.assignment.sales', 'approvedByManager']);

        $user = Auth::user();
        if ($user->isCustomer()) {
            $query->where('customer_id', '=', $user->id)
                ->where('date_expired', '>=', now())
                ->whereDoesntHave('purchaseOrder')
                ->whereIn('status', ['sent', 'accepted', 'negotiating']);
        } elseif ($user->isStaff() && $user->divisi === 'sales') {
            // Staff Sales HANYA BISA MELIHAT Quotation yang di-PIC oleh dirinya sendiri
            $query->whereHas('request.assignment', function ($q) use ($user) {
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

        $quotations = $query->orderByRaw("
            CASE 
                WHEN status IN ('negotiating', 'negotiation') THEN 1
                WHEN status IN ('created', 'draft') THEN 2
                WHEN status = 'sent' THEN 3
                WHEN status = 'accepted' THEN 4
                WHEN status = 'po' THEN 5
                WHEN status IN ('rejected', 'reject') THEN 6
                WHEN status = 'expired' THEN 7
                WHEN status IN ('cancel', 'canceled') THEN 8
                ELSE 9
            END ASC, updated_at DESC
        ")->get();

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

            // Lock akses Quotation ke PIC Sales pemegang tiket
            if ($requestProject && $requestProject->assignment) {
                $user = Auth::user();
                if ($user->role === 'staff' && $user->divisi === 'sales') {
                    if ($requestProject->assignment->sales_id !== $user->id) {
                        return redirect()->route('requests-project.index')
                            ->with('error', 'Akses ditolak. Hanya Sales PIC pemegang tiket (' . ($requestProject->assignment->sales->name ?? '-') . ') yang berhak membuat Quotation untuk request ini.');
                    }
                }
            }

            if ($requestProject && $requestProject->customer_id) {
                $customerAccount = Account::where('user_id', $requestProject->customer_id)->first();
            }
        }

        $customers = User::with(['requestProjects.attachments'])
            ->whereHas('requestProjects')
            ->where('role', '=', 'customer')
            ->get();

        $articles = Article::orderBy('part_name', 'asc')->get();

        return view('quotations.create', compact('customers', 'requestProject', 'quotationNo', 'customerAccount', 'articles'));
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

            $quotation = Quotation::create([
                'customer_id'             => $request->customer_id ?? $customerId,
                'request_id'              => $request->request_id,
                'quotation_no'            => $request->quotation_no,
                'date_expired'            => $request->date_expired,
                'notes'                   => $request->notes,
                'attachments'             => json_encode($filename),
                'target_delivery_date'    => $request->target_delivery_date,
                'payment_terms'           => $request->payment_terms,
                'status'                  => 'created',
                'is_below_floor_price'    => false,
                'manager_approval_status' => 'none',
            ]);

            if ($request->has('item') && is_array($request->item)) {
                foreach ($request->item as $index => $itemName) {
                    if (!empty($itemName)) {
                        $cleanName = trim($itemName);
                        $articleId = $request->article_id[$index] ?? null;

                        $article = null;
                        if ($articleId) {
                            $article = Article::find($articleId);
                        }
                        if (!$article) {
                            $article = Article::where('part_name', $cleanName)
                                ->orWhere('article_no', $cleanName)
                                ->orWhere('internal_part_no', $cleanName)
                                ->first();
                        }

                        $priceList = $article ? $article->effective_price_list : (float) ($request->price[$index] ?? 0);
                        $floorPrice = $article ? $article->effective_floor_price : 0;

                        $quotation->items()->create([
                            'article_id'           => $article?->id,
                            'item'                 => $article ? $article->part_name : $itemName,
                            'qty'                  => $request->qty[$index] ?? 1,
                            'original_price'       => $priceList,
                            'price'                => $priceList,
                            'negotiated_price'     => null,
                            'floor_price'          => $floorPrice,
                            'is_below_floor_price' => false,
                        ]);
                    }
                }
            }

            HistoryActivity::create([
                'user_id'       => Auth::id(),
                'activity'      => 'Membuat quotation ' . $quotation->quotation_no,
                'activity_time' => now()->format('Y-m-d H:i:s')
            ]);

            return redirect()->route('quotations.index')->with('success', 'Quotation berhasil dibuat.');
        } catch (Exception $e) {
            Log::error('error create quotation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function send(Request $request, $id)
    {
        try {
            $quotation = Quotation::with('request.assignment')->findOrFail($id);
            $user = Auth::user();

            if ($user->isStaff() && $user->divisi === 'sales') {
                $salesId = $quotation->request?->assignment?->sales_id;
                if ($salesId && $salesId !== $user->id) {
                    return redirect()->route('quotations.index')
                        ->with('error', 'Akses ditolak. Hanya Sales PIC penanggung jawab yang berhak mengirim Quotation ini.');
                }
            }

            $quotation->update([
                'status'    => 'sent',
                'sent_date' => now()
            ]);

            $customer = User::where('id', $quotation->customer_id)->first();
            if ($customer) {
                $customer->notify(new QuotationSendNotification($quotation));
            }

            HistoryActivity::create([
                'user_id'       => Auth::id(),
                'activity'      => 'Mengirim quotation ' . $quotation->quotation_no . ' ke customer',
                'activity_time' => now()->format('Y-m-d H:i:s')
            ]);

            return redirect()->route('quotations.index')->with('success', 'Quotation berhasil dikirim ke customer.');
        } catch (Exception $e) {
            Log::error('error send quotation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show(Quotation $quotation)
    {
        $user = Auth::user();

        if ($user->role === 'customer' && $quotation->customer_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        if ($user->isStaff() && $user->divisi === 'sales') {
            $salesId = $quotation->request?->assignment?->sales_id;
            if ($salesId && $salesId !== $user->id) {
                return redirect()->route('quotations.index')
                    ->with('error', 'Akses ditolak. Quotation ini ditangani oleh Sales PIC lain.');
            }
        }

        $quotation->load([
            'customer', 
            'items.article', 
            'request.attachments', 
            'negotiates' => fn($q) => $q->with(['user', 'manager'])->orderBy('id', 'desc'),
            'approvedByManager'
        ]);

        $customerAccount = Account::where('user_id', $quotation->customer_id)->first();
        $negotiations = $quotation->negotiates;
        $lastNegotiation = $negotiations->first();

        // Hitung floor prices per item untuk referensi tampilan
        $floorPrices = [];
        foreach ($quotation->items as $item) {
            $floorPrices[$item->id] = NegotiateController::resolveItemFloorPrice($item->item);
        }

        return view('quotations.show', compact('quotation', 'customerAccount', 'negotiations', 'lastNegotiation', 'floorPrices'));
    }

    public function edit(Quotation $quotation)
    {
        $user = Auth::user();
        if ($quotation->status !== 'created') {
            return redirect()->route('quotations.show', $quotation->id)
                ->with('error', 'Quotation tidak dapat diedit karena status sudah ' . $quotation->status . '.');
        }

        if ($user->role === 'staff' && $user->divisi === 'sales') {
            $salesId = $quotation->request?->assignment?->sales_id;
            if ($salesId && $salesId !== $user->id) {
                return redirect()->route('quotations.index')
                    ->with('error', 'Akses ditolak. Hanya Sales PIC penanggung jawab yang berhak mengedit Quotation ini.');
            }
        }

        $articles = Article::orderBy('part_name', 'asc')->get();

        return view('quotations.edit', compact('quotation', 'articles'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        $user = Auth::user();
        if ($user->role === 'staff' && $user->divisi === 'sales') {
            $salesId = $quotation->request?->assignment?->sales_id;
            if ($salesId && $salesId !== $user->id) {
                return redirect()->route('quotations.index')
                    ->with('error', 'Akses ditolak. Hanya Sales PIC penanggung jawab yang berhak mengupdate Quotation ini.');
            }
        }

        $data = $request->except(['_token', '_method', 'attachments', 'item_ids', 'item', 'qty', 'price', 'article_id']);
        
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
                    $cleanName = trim($itemName);
                    $articleId = $request->article_id[$index] ?? null;

                    $article = null;
                    if ($articleId) {
                        $article = Article::find($articleId);
                    }
                    if (!$article) {
                        $article = Article::where('part_name', $cleanName)
                            ->orWhere('article_no', $cleanName)
                            ->orWhere('internal_part_no', $cleanName)
                            ->first();
                    }

                    $priceList = $article ? $article->effective_price_list : (float) ($request->price[$index] ?? 0);
                    $floorPrice = $article ? $article->effective_floor_price : 0;

                    $itemData = [
                        'article_id'           => $article?->id,
                        'item'                 => $article ? $article->part_name : $itemName,
                        'qty'                  => $request->qty[$index] ?? 1,
                        'original_price'       => $priceList,
                        'price'                => $priceList,
                        'floor_price'          => $floorPrice,
                        'is_below_floor_price' => false,
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
            'activity'      => 'Mengupdate quotation ' . $quotation->quotation_no,
            'activity_time' => now()->format('Y-m-d H:i:s')
        ]);

        $quotation->update($data);

        return redirect()->route('quotations.index')->with('success', 'Quotation berhasil diupdate.');
    }

    public function destroy(Quotation $quotation)
    {
        try {
            $user = Auth::user();
            if ($user->role === 'staff' && $user->divisi === 'sales') {
                $salesId = $quotation->request?->assignment?->sales_id;
                if ($salesId && $salesId !== $user->id) {
                    return redirect()->route('quotations.index')
                        ->with('error', 'Akses ditolak. Hanya Sales PIC penanggung jawab yang berhak menghapus Quotation ini.');
                }
            }

            $quotationNo = $quotation->quotation_no;
            $quotation->items()->delete();
            $quotation->negotiates()->delete();
            $quotation->delete();

            HistoryActivity::create([
                'user_id'       => Auth::id(),
                'activity'      => 'Menghapus quotation ' . $quotationNo,
                'activity_time' => now()->format('Y-m-d H:i:s')
            ]);

            return redirect()->route('quotations.index')->with('success', 'Quotation berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('error delete quotation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Persetujuan Harga Khusus (DEPRECATED - Harga di bawah batas bawah dilarang)
     */
    public function approveSpecialPrice(Request $request, Quotation $quotation)
    {
        return redirect()->back()->with('info', 'Persetujuan harga khusus tidak lagi berlaku karena harga penawaran di bawah harga dasar modal tidak diizinkan.');
    }

    /**
     * Penolakan Harga Khusus (DEPRECATED - Harga di bawah batas bawah dilarang)
     */
    public function rejectSpecialPrice(Request $request, Quotation $quotation)
    {
        return redirect()->back()->with('info', 'Penolakan harga khusus tidak lagi berlaku karena harga penawaran di bawah harga dasar modal tidak diizinkan.');
    }

    public function exportPdf(Quotation $quotation)
    {
        $user = Auth::user();
        if ($user->role === 'customer' && $quotation->customer_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }
        if ($user->role === 'staff' && $user->divisi === 'sales') {
            $salesId = $quotation->request?->assignment?->sales_id;
            if ($salesId && $salesId !== $user->id) {
                abort(403, 'Akses ditolak. Quotation ini ditangani oleh Sales PIC lain.');
            }
        }

        $quotation->load(['customer', 'items.article', 'approvedByManager']);
        $pdf = Pdf::loadView('quotations.quotation-pdf', compact('quotation'))->setPaper('a4', 'portrait');
        return $pdf->stream('Quotation-' . $quotation->quotation_no . '.pdf');
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

    /**
     * Manager Override — Tambah kuota negosiasi per-quotation.
     * Hanya bisa diakses oleh Manager Sales atau Admin.
     */
    public function overrideNegotiationLimit(Request $request, $id)
    {
        $quotation = Quotation::findOrFail($id);
        Gate::authorize('overrideQuota', $quotation);

        $request->validate([
            'additional_quota' => 'required|integer|min:1|max:10',
        ]);

        $result = SystemSettingService::overrideNegotiationLimit($id, $request->additional_quota);

        if (!$result) {
            return redirect()->back()->with('error', 'Quotation tidak ditemukan.');
        }

        HistoryActivity::create([
            'user_id'       => Auth::id(),
            'activity'      => 'Manager Override: Menambah kuota negosiasi (' . $request->additional_quota . 'x) untuk Quotation #' . $quotation->quotation_no,
            'activity_time'  => now()->format('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Kuota negosiasi berhasil ditambahkan (' . $request->additional_quota . 'x tambahan).');
    }
}