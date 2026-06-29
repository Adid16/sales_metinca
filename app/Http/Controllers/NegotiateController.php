<?php
 
namespace App\Http\Controllers;
 
use App\Models\Negotiate;
use App\Models\Quotation;
use App\Models\Account;
use App\Models\HistoryActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
 
class NegotiateController extends Controller
{
    /**
     * Halaman negotiate untuk customer
     */
    public function show(Quotation $quotation)
    {
        $quotation->load([
            'customer',
            'items',
            'request.attachments',
            'negotiates' => function ($q) {
                $q->with('user')->orderBy('created_at', 'desc');
            }
        ]);
 
        $customerAccount = Account::where('user_id', $quotation->customer_id)->first();
        $negotiations    = $quotation->negotiates;
        $lastNegotiation = $negotiations->first();
        // $lastNegotiation = $negotiations->where('from_customer', true)->first();
 
        return view('quotations.negotiate', compact(
            'quotation',
            'customerAccount',
            'negotiations',
            'lastNegotiation'
        ));
    }
 
    /**
     * Halaman view negotiate untuk staff / manager / admin
     */
    public function viewNego(Quotation $quotation)
    {
        $quotation->load([
            'customer',
            'items',
            'request.attachments',
            'negotiates' => function ($q) {
                $q->with('user')->orderBy('created_at', 'desc');
            }
        ]);
 
        $customerAccount = Account::where('user_id', $quotation->customer_id)->first();
        $negotiations    = $quotation->negotiates;
        $lastNegotiation = $negotiations->where('from_customer', true)->first();
 
        return view('quotations.show-nego', compact(
            'quotation',
            'customerAccount',
            'negotiations',
            'lastNegotiation'
        ));
    }
 
    /**
     * Simpan data negosiasi
     */
    public function store(Request $request, Quotation $quotation)
    {
        $request->validate([
            'negotiation_message'      => 'required|string|max:2000',
            'items'                    => 'required|array',
            'items.*.id'               => 'required|exists:quotation_items,id',
            'items.*.negotiated_price' => 'required|numeric|min:0',
            'target_delivery_date'     => 'nullable|date|after_or_equal:today',
            'payment_terms'            => 'nullable|string',
            'support_document'         => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
            'action'                   => 'required|in:negotiate,accept',
        ]);
 
        try {
            $quotation->load('items');
 
            // Hitung total negosiasi dan siapkan snapshot item
            $negotiatedItems = [];
            $negotiatedTotal = 0;
 
            foreach ($request->items as $itemData) {
                $item = $quotation->items->firstWhere('id', $itemData['id']);
                if ($item) {
                    $subtotal         = $itemData['negotiated_price'] * $item->qty;
                    $negotiatedTotal += $subtotal;
                    $negotiatedItems[] = [
                        'id'               => $item->id,
                        'item'             => $item->item,
                        'qty'              => $item->qty,
                        'original_price'   => $item->price,
                        'negotiated_price' => $itemData['negotiated_price'],
                        'subtotal'         => $subtotal,
                    ];
                }
            }
 
            // =============================================
            // ACCEPT & FINALIZE
            // Tidak buat record baru, cukup:
            // 1. Update action record terakhir → accept
            // 2. Update status quotation → accepted
            // 3. Update harga item sesuai harga nego
            // =============================================
            if ($request->action === 'accept') {
 
                // Update record negotiate terakhir → action = accept
                $lastNegotiate = Negotiate::where('quotation_id', $quotation->id)
                    ->latest()
                    ->first();
 
                if ($lastNegotiate) {
                    $lastNegotiate->update([
                        'action'           => 'accept',
                        'negotiated_total' => $negotiatedTotal,
                        'negotiated_items' => $negotiatedItems,
                    ]);
                }
 
                // Update status quotation → accepted
                $quotation->update([
                    'status'        => 'accepted',
                    'accepted_date' => now(),
                ]);
 
                // Update harga item sesuai harga nego yang disubmit
                foreach ($negotiatedItems as $negItem) {
                    $quotation->items()
                        ->where('id', $negItem['id'])
                        ->update(['price' => $negItem['negotiated_price']]);
                }
 
                $activityMsg = 'Menerima & finalisasi negosiasi quotation ' . $quotation->quotation_no;
                $msg         = 'Negosiasi diterima. Quotation telah difinalisasi dengan harga yang disepakati.';
 
            // =============================================
            // SUBMIT NEGOTIATE
            // Buat record baru, update status → negotiating
            // =============================================
            } else {
 
                // Upload dokumen pendukung jika ada
                $supportDocPath = null;
                if ($request->hasFile('support_document')) {
                    $file           = $request->file('support_document');
                    $filename       = time() . '_' . $file->getClientOriginalName();
                    $supportDocPath = $file->storeAs('negotiates', $filename, 'public');
                }
 
                // Buat record negotiate baru
                Negotiate::create([
                    'quotation_id'         => $quotation->id,
                    'user_id'              => Auth::id(),
                    'from_customer'        => Auth::user()->isCustomer(),
                    'message'              => $request->negotiation_message,
                    'negotiated_total'     => $negotiatedTotal,
                    'payment_terms'        => $request->payment_terms,
                    'target_delivery_date' => $request->target_delivery_date,
                    'support_document'     => $supportDocPath,
                    'action'               => 'negotiate',
                    'negotiated_items'     => $negotiatedItems,
                ]);
 
                // Update status quotation → negotiating
                $quotation->update(['status' => 'negotiating']);
 
                $activityMsg = Auth::user()->isCustomer()
                    ? 'Customer mengajukan negosiasi quotation ' . $quotation->quotation_no
                    : 'Staff membalas negosiasi quotation ' . $quotation->quotation_no;
 
                $msg = Auth::user()->isCustomer()
                    ? 'Negosiasi berhasil diajukan.'
                    : 'Balasan negosiasi berhasil dikirim.';
            }
 
            // Catat activity
            HistoryActivity::create([
                'user_id'       => Auth::id(),
                'activity'      => $activityMsg,
                'activity_time' => now()->format('Y-m-d H:i:s'),
            ]);
 
            return redirect()->route('quotations.show', $quotation->id)->with('success', $msg);
 
        } catch (\Exception $e) {
            Log::error('Negotiate error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}