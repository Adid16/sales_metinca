<?php

namespace App\Http\Controllers;

use App\Models\Negotiate;
use App\Models\Quotation;
use App\Models\Account;
use App\Models\Article;
use App\Models\HistoryActivity;
use App\Services\SystemSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NegotiateController extends Controller
{
    /**
     * Cek apakah user adalah Tim Sales Internal (Staff Sales, Manager Sales, atau Admin)
     */
    private function isSalesInternal(): bool
    {
        $user = Auth::user();
        if (!$user) return false;
        return $user->isAdmin() || ($user->divisi === 'sales');
    }

    /**
     * Cek apakah user adalah Manager Sales atau Admin (yang berwenang memberi Approval)
     */
    private function isManagerSales(): bool
    {
        $user = Auth::user();
        if (!$user) return false;
        return $user->isAdmin() || ($user->isManager() && $user->divisi === 'sales');
    }

    /**
     * Helper untuk mendapatkan batas bawah harga dari master Article
     */
    public static function resolveItemFloorPrice($itemName): array
    {
        if (empty($itemName)) {
            return ['floor_price' => 0, 'master_price' => 0, 'article' => null];
        }

        $cleanName = trim($itemName);
        $article = Article::where('part_name', $cleanName)
            ->orWhere('article_no', $cleanName)
            ->orWhere('internal_part_no', $cleanName)
            ->first();

        // Jika belum ketemu, coba pencarian sebagian nama part
        if (!$article && strlen($cleanName) >= 4) {
            $article = Article::where('part_name', 'LIKE', '%' . $cleanName . '%')
                ->orWhere('article_no', 'LIKE', '%' . $cleanName . '%')
                ->first();
        }

        if ($article) {
            $masterPrice = $article->effective_price_list;
            $floorPrice = $article->effective_floor_price;

            return [
                'floor_price'  => round($floorPrice, 2),
                'master_price' => (float) $masterPrice,
                'article'      => $article,
            ];
        }

        return ['floor_price' => 0, 'master_price' => 0, 'article' => null];
    }

    /**
     * Halaman Dashboard / List Negosiasi & Persetujuan (Khusus Manager Sales & Admin)
     */
    public function index(Request $request)
    {
        if (!$this->isManagerSales()) {
            return redirect()->route('quotations.index')
                ->with('info', 'Halaman dashboard negosiasi harga khusus untuk Manager Sales. Anda dapat mengelola negosiasi langsung melalui menu Quotation.');
        }

        $tab = $request->get('tab', 'all');

        $query = Quotation::with([
            'customer',
            'items',
            'approvedByManager',
            'negotiates' => function ($q) {
                $q->with(['user', 'manager'])->orderBy('id', 'desc');
            }
        ])->whereHas('negotiates');

        if ($tab === 'active') {
            $query->where('status', 'negotiating');
        } elseif ($tab === 'accepted') {
            $query->whereIn('status', ['accepted', 'po']);
        }

        $quotations = $query->orderByRaw("
            CASE 
                WHEN status = 'negotiating' THEN 1
                WHEN status = 'accepted' THEN 2
                WHEN status = 'po' THEN 3
                ELSE 4
            END ASC, updated_at DESC
        ")->paginate(15);

        $activeCount = Quotation::where('status', 'negotiating')
            ->whereHas('negotiates')
            ->count();

        $acceptedCount = Quotation::whereIn('status', ['accepted', 'po'])
            ->whereHas('negotiates')
            ->count();

        $allCount = Quotation::whereHas('negotiates')->count();

        return view('negotiations.index', compact(
            'quotations',
            'tab',
            'activeCount',
            'acceptedCount',
            'allCount'
        ));
    }

    /**
     * Halaman negotiate untuk customer
     */
    public function show(Quotation $quotation)
    {
        $user = Auth::user();
        if ($user->isCustomer() && $quotation->customer_id !== $user->id) {
            abort(403, 'Anda tidak berhak mengakses quotation ini.');
        }

        $quotation->load([
            'customer',
            'items.article',
            'request.attachments',
            'negotiates' => function ($q) {
                $q->with(['user', 'manager'])->orderBy('id', 'desc');
            }
        ]);

        $customerAccount = Account::where('user_id', $quotation->customer_id)->first();
        $negotiations    = $quotation->negotiates;
        $lastNegotiation = $negotiations->first();

        // Hitung floor prices per item untuk referensi tampilan
        $floorPrices = [];
        foreach ($quotation->items as $item) {
            $floorPrices[$item->id] = self::resolveItemFloorPrice($item->item);
        }

        return view('quotations.negotiate', compact(
            'quotation',
            'customerAccount',
            'negotiations',
            'lastNegotiation',
            'floorPrices'
        ));
    }

    /**
     * Halaman view negotiate untuk internal sales (Staff Sales, Manager Sales & Admin)
     */
    public function viewNego(Quotation $quotation)
    {
        $user = Auth::user();
        if (!$this->isSalesInternal()) {
            return redirect()->route('quotations.show', $quotation->id)
                ->with('error', 'Akses ditolak. Fitur negosiasi hanya dapat diakses oleh Divisi Sales.');
        }

        if ($user->role === 'staff' && $user->divisi === 'sales') {
            $salesId = $quotation->request?->assignment?->sales_id;
            if ($salesId && $salesId !== $user->id) {
                return redirect()->route('quotations.index')
                    ->with('error', 'Akses ditolak. Negosiasi Quotation ini ditangani oleh Sales PIC lain.');
            }
        }

        $quotation->load([
            'customer',
            'items.article',
            'request.attachments',
            'approvedByManager',
            'negotiates' => function ($q) {
                $q->with(['user', 'manager'])->orderBy('id', 'desc');
            }
        ]);

        $customerAccount = Account::where('user_id', $quotation->customer_id)->first();
        $negotiations    = $quotation->negotiates;
        $lastNegotiation = $negotiations->first();

        // Hitung floor prices per item untuk referensi tampilan
        $floorPrices = [];
        foreach ($quotation->items as $item) {
            $floorPrices[$item->id] = self::resolveItemFloorPrice($item->item);
        }

        return view('quotations.show-nego', compact(
            'quotation',
            'customerAccount',
            'negotiations',
            'lastNegotiation',
            'floorPrices'
        ));
    }

    /**
     * Simpan data negosiasi (Diajukan oleh Customer atau Dibalas oleh Tim Sales)
     */
    public function store(Request $request, Quotation $quotation)
    {
        $user = Auth::user();

        // Validasi akses: Customer atau Tim Sales Internal
        if (!$user->isCustomer() && !$this->isSalesInternal()) {
            return redirect()->back()->with('error', 'Akses ditolak. Hanya Divisi Sales yang berwenang mengelola & membalas negosiasi harga.');
        }

        if ($user->role === 'staff' && $user->divisi === 'sales') {
            $salesId = $quotation->request?->assignment?->sales_id;
            if ($salesId && $salesId !== $user->id) {
                return redirect()->route('quotations.index')
                    ->with('error', 'Akses ditolak. Negosiasi Quotation ini ditangani oleh Sales PIC lain.');
            }
        }

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

            // ====================================================================
            // Pengecekan batas counter negosiasi (Maksimal 6x / 3x saling balas)
            // ====================================================================
            $currentNegoCount = Negotiate::where('quotation_id', $quotation->id)
                ->where('action', 'negotiate')
                ->count();
            $effectiveLimit = SystemSettingService::effectiveNegotiationLimit($quotation);

            if ($request->action === 'negotiate' && $currentNegoCount >= $effectiveLimit) {
                return redirect()->back()->with('error', 
                    'Kuota negosiasi telah habis (' . $currentNegoCount . '/' . $effectiveLimit . 'x). '
                    . 'Silakan sepakati harga penawaran terakhir atau hubungi Manager Sales untuk menambah kuota.');
            }

            // ====================================================================
            // Validasi Inisiasi & Giliran Saling Balas (Turn-based Negotiation Rule)
            // ====================================================================
            $lastNego = Negotiate::where('quotation_id', $quotation->id)
                ->where('action', 'negotiate')
                ->latest('id')
                ->first();

            // ATURAN BISNIS: Negosiasi harga HARUS diawali dan diajukan pertama kali oleh Customer
            if (!$lastNego) {
                if (!$user->isCustomer()) {
                    return redirect()->back()->withInput()->with('error', 
                        'Negosiasi harga harus diawali dan diajukan pertama kali oleh pihak Customer. Tim Sales hanya dapat memberikan balasan penawaran (counter-offer) setelah Customer mengajukan negosiasi.'
                    );
                }
            } else {
                if ($request->action === 'negotiate') {
                    if ($user->isCustomer() && $lastNego->from_customer) {
                        return redirect()->back()->with('error', 'Anda sudah mengirimkan pengajuan harga sebelumnya. Harap menunggu tanggapan/balasan penawaran dari Tim Sales terlebih dahulu.');
                    }

                    if (!$user->isCustomer() && !$lastNego->from_customer) {
                        return redirect()->back()->with('error', 'Tim Sales sudah mengirimkan penawaran harga sebelumnya. Harap menunggu tanggapan/balasan dari Customer terlebih dahulu.');
                    }
                }
            }

            // Hitung total negosiasi dan siapkan snapshot item beserta validasi batas bawah
            $negotiatedItems = [];
            $negotiatedTotal = 0;
            $floorPriceSnapshots = [];
            $hasAnyBelowFloor = false;

            foreach ($request->items as $itemData) {
                $item = $quotation->items->firstWhere('id', $itemData['id']);
                if ($item) {
                    $itemPrice = (float) $itemData['negotiated_price'];
                    $floorInfo = self::resolveItemFloorPrice($item->item);
                    $floorPrice = $floorInfo['floor_price'];
                    $isBelow = ($floorPrice > 0 && $itemPrice < $floorPrice);

                    if ($isBelow) {
                        $hasAnyBelowFloor = true;
                    }

                    // HARD BLOCK KHUSUS TIM SALES: Sales tidak boleh mengirimkan tawaran di bawah harga dasar modal
                    if (!$user->isCustomer() && $isBelow) {
                        return redirect()->back()->withInput()->with('error', 
                            'Harga penawaran dari Tim Sales untuk item "' . $item->item . '" (Rp ' . number_format($itemPrice, 0, ',', '.') . ') '
                            . 'tidak boleh lebih rendah dari harga dasar modal bahan & proses (Rp ' . number_format($floorPrice, 0, ',', '.') . '). '
                            . 'Silakan berikan harga penawaran balik (counter offer) di atas atau sama dengan harga dasar modal.'
                        );
                    }

                    $floorPriceSnapshots[] = [
                        'item_id'        => $item->id,
                        'item_name'      => $item->item,
                        'master_price'   => $floorInfo['master_price'],
                        'floor_price'    => $floorPrice,
                        'offered_price'  => $itemPrice,
                        'is_below_floor' => $isBelow,
                    ];

                    $subtotal         = $itemPrice * $item->qty;
                    $negotiatedTotal += $subtotal;
                    $negotiatedItems[] = [
                        'id'               => $item->id,
                        'item'             => $item->item,
                        'qty'              => $item->qty,
                        'original_price'   => (float) ($item->original_price ?: $item->price),
                        'negotiated_price' => $itemPrice,
                        'floor_price'      => $floorPrice,
                        'is_below_floor'   => $isBelow,
                        'subtotal'         => $subtotal,
                    ];

                    // Update detail kolom item pada database
                    $item->update([
                        'negotiated_price'     => $itemPrice,
                        'floor_price'          => $floorPrice,
                        'is_below_floor_price' => $isBelow,
                    ]);
                }
            }

            // =============================================
            // ACTION: ACCEPT & FINALIZE
            // =============================================
            if ($request->action === 'accept') {

                if ($hasAnyBelowFloor) {
                    if (!$user->isCustomer()) {
                        return redirect()->back()->with('error', 'Tidak dapat menyepakati negosiasi karena terdapat item dengan harga di bawah harga dasar modal bahan & proses. Silakan ajukan penawaran balik (counter offer) kepada Customer.');
                    } else {
                        return redirect()->back()->with('error', 'Penawaran ini belum dapat disetujui secara langsung. Harap ajukan negosiasi harga.');
                    }
                }

                $lastNegotiate = Negotiate::where('quotation_id', $quotation->id)
                    ->latest('id')
                    ->first();

                if ($lastNegotiate) {
                    $lastNegotiate->update([
                        'action'           => 'accept',
                        'negotiated_total' => $negotiatedTotal,
                        'negotiated_items' => $negotiatedItems,
                    ]);
                }

                $quotation->update([
                    'status'        => 'accepted',
                    'accepted_date' => now(),
                    'is_below_floor_price'    => false,
                    'manager_approval_status' => 'none',
                ]);

                foreach ($negotiatedItems as $negItem) {
                    $quotation->items()
                        ->where('id', $negItem['id'])
                        ->update([
                            'price'                => $negItem['negotiated_price'],
                            'negotiated_price'     => $negItem['negotiated_price'],
                            'is_below_floor_price' => false,
                        ]);
                }

                $activityMsg = 'Menerima & finalisasi negosiasi quotation ' . $quotation->quotation_no;
                $msg        = 'Negosiasi diterima. Quotation telah difinalisasi dengan harga yang disepakati.';

            // =============================================
            // ACTION: SUBMIT NEGOTIATE
            // =============================================
            } else {
                $supportDocPath = null;
                if ($request->hasFile('support_document')) {
                    $file           = $request->file('support_document');
                    $filename       = time() . '_' . $file->getClientOriginalName();
                    $supportDocPath = $file->storeAs('negotiates', $filename, 'public');
                }

                Negotiate::create([
                    'quotation_id'              => $quotation->id,
                    'user_id'                   => $user->id,
                    'from_customer'             => $user->isCustomer(),
                    'message'                   => $request->negotiation_message,
                    'negotiated_total'          => $negotiatedTotal,
                    'payment_terms'             => $request->payment_terms,
                    'target_delivery_date'      => $request->target_delivery_date,
                    'support_document'          => $supportDocPath,
                    'action'                    => 'negotiate',
                    'negotiated_items'          => $negotiatedItems,
                    'requires_manager_approval' => false,
                    'manager_approval_status'   => null,
                    'manager_approval_note'     => null,
                    'manager_approved_by'       => null,
                    'manager_approved_at'       => null,
                    'floor_price_snapshot'      => $floorPriceSnapshots,
                ]);

                $quotation->update([
                    'status'                  => 'negotiating',
                    'is_below_floor_price'    => $hasAnyBelowFloor,
                    'manager_approval_status' => 'none',
                ]);

                $roleTitle = $user->isCustomer() ? 'Customer' : ($user->isManager() ? 'Manager Sales' : 'Staff Sales');
                $activityMsg = $roleTitle . ' mengajukan/membalas negosiasi quotation ' . $quotation->quotation_no;

                $msg = $user->isCustomer()
                    ? 'Pengajuan negosiasi harga berhasil dikirim ke Tim Sales.'
                    : 'Balasan negosiasi berhasil dikirim ke Customer.';
            }

            HistoryActivity::create([
                'user_id'       => $user->id,
                'activity'      => $activityMsg,
                'activity_time' => now()->format('Y-m-d H:i:s'),
            ]);

            $redirectRoute = $user->isCustomer()
                ? route('quotations.show', $quotation->id)
                : route('negotiate.show-nego', $quotation->id);

            return redirect($redirectRoute)->with('success', $msg);

        } catch (\Exception $e) {
            Log::error('Negotiate error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Persetujuan (Approve) Harga di Bawah Batas Bawah (DEPRECATED - Harga di bawah batas bawah dilarang)
     */
    public function approvePrice(Request $request, Negotiate $negotiate)
    {
        return redirect()->back()->with('info', 'Persetujuan harga khusus tidak lagi berlaku karena harga penawaran di bawah harga dasar modal tidak diizinkan.');
    }

    /**
     * Penolakan (Reject) Harga di Bawah Batas Bawah (DEPRECATED - Harga di bawah batas bawah dilarang)
     */
    public function rejectPrice(Request $request, Negotiate $negotiate)
    {
        return redirect()->back()->with('info', 'Penolakan harga khusus tidak lagi berlaku karena harga penawaran di bawah harga dasar modal tidak diizinkan.');
    }

    /**
     * Aksi penyepakatan harga akhir / penutupan diskusi transaksi (Close Negotiate)
     */
    public function closeNegotiate(Request $request, Quotation $quotation)
    {
        try {
            $user = Auth::user();
            $isCustomer = $user->isCustomer();

            // Validasi otorisasi: Customer atau Tim Sales Internal
            if (!$isCustomer && !$this->isSalesInternal()) {
                return redirect()->back()->with('error', 'Akses ditolak. Penutupan negosiasi hanya dapat dilakukan oleh Customer atau Tim Sales.');
            }

            $lastNego = Negotiate::where('quotation_id', $quotation->id)
                ->where('action', 'negotiate')
                ->latest('id')
                ->first();

            if (!$lastNego) {
                return redirect()->back()->with('error', 'Belum ada data negosiasi harga yang bisa disepakati.');
            }

            // Customer hanya bisa menyetujui jika penawaran terakhir berasal dari Sales (from_customer == false)
            if ($isCustomer && $lastNego->from_customer) {
                return redirect()->back()->with('error', 'Anda tidak dapat menyetujui tawaran harga Anda sendiri. Harap tunggu tanggapan dari Tim Sales.');
            }

            // Sales hanya bisa menyetujui jika penawaran terakhir berasal dari Customer (from_customer == true)
            if (!$isCustomer && !$lastNego->from_customer) {
                return redirect()->back()->with('error', 'Anda tidak dapat menyetujui penawaran harga Anda sendiri. Harap tunggu tanggapan dari Customer.');
            }

            // Bongkar item snapshot dari JSON database
            $itemsArray = is_array($lastNego->negotiated_items) 
                ? $lastNego->negotiated_items 
                : json_decode($lastNego->negotiated_items, true);

            // Validasi: Tidak boleh menyepakati jika ada item di bawah harga dasar modal
            if (is_array($itemsArray)) {
                foreach ($itemsArray as $negoItem) {
                    $floorInfo = self::resolveItemFloorPrice($negoItem['item'] ?? '');
                    $floorPrice = $floorInfo['floor_price'] ?? 0;
                    $negPrice = (float) ($negoItem['negotiated_price'] ?? 0);

                    if ($floorPrice > 0 && $negPrice < $floorPrice) {
                        return redirect()->back()->with('error', 
                            'Tidak dapat menyepakati penawaran ini karena item "' . ($negoItem['item'] ?? '') . '" (Rp ' . number_format($negPrice, 0, ',', '.') . ') '
                            . 'berada di bawah harga dasar modal bahan & proses (Rp ' . number_format($floorPrice, 0, ',', '.') . '). '
                            . 'Silakan berikan penawaran balik (counter offer) kepada Customer.'
                        );
                    }
                }
            }

            if (is_array($itemsArray)) {
                foreach ($itemsArray as $negoItem) {
                    $quotation->items()->where('id', $negoItem['id'])->update([
                        'price'                => $negoItem['negotiated_price'],
                        'negotiated_price'     => $negoItem['negotiated_price'],
                        'is_below_floor_price' => false,
                    ]);
                }
            }

            // Update master quotation
            $quotation->update([
                'status'                  => 'accepted',
                'accepted_date'           => now(),
                'payment_terms'           => $lastNego->payment_terms,
                'target_delivery_date'    => $lastNego->target_delivery_date,
                'is_below_floor_price'    => false,
                'manager_approval_status' => 'none',
            ]);

            $closedByMessage = $isCustomer 
                ? 'Negosiasi resmi disepakati dan ditutup oleh Customer.' 
                : 'Negosiasi resmi disepakati dan ditutup oleh Tim Sales.';

            // Kunci alur negosiasi ke status 'closed'
            Negotiate::create([
                'quotation_id'              => $quotation->id,
                'user_id'                   => $user->id,
                'from_customer'             => $isCustomer,
                'message'                   => $closedByMessage,
                'negotiated_total'          => $lastNego->negotiated_total,
                'payment_terms'             => $lastNego->payment_terms,
                'target_delivery_date'      => $lastNego->target_delivery_date,
                'action'                    => 'closed',
                'negotiated_items'          => $lastNego->negotiated_items,
                'requires_manager_approval' => false,
                'manager_approval_status'   => null,
                'manager_approved_by'       => null,
                'manager_approved_at'       => null,
                'manager_approval_note'     => null,
            ]);

            HistoryActivity::create([
                'user_id'       => $user->id,
                'activity'      => ($isCustomer ? 'Customer' : ($user->isManager() ? 'Manager Sales' : 'Staff Sales')) . ' menutup & menyepakati negosiasi quotation ' . $quotation->quotation_no,
                'activity_time' => now()->format('Y-m-d H:i:s')
            ]);

            return redirect()->route('quotations.show', $quotation->id)->with('success', 'Negosiasi berhasil disepakati dan ditutup dengan harga akhir.');
        } catch (\Exception $e) {
            Log::error('Close negotiate error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}