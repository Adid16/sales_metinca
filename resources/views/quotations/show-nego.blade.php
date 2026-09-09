{{-- Extend layout utama --}}
@extends('layouts.app')
 
@section('title', 'PT. Metinca Prima Industrial Works - Negosiasi Tim Sales')
 
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <style>
        .negotiate-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
            margin-bottom: 20px;
            background: #fff;
        }
        .negotiate-card .card-header {
            border-radius: 11px 11px 0 0;
            padding: 12px 18px;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            background-color: #f8fafc;
            color: #495057;
            border-bottom: 1px solid #edf2f7;
        }
        .negotiate-card .card-body { padding: 18px; }
        .info-row { display: flex; align-items: baseline; margin-bottom: 8px; font-size: 13.5px; }
        .info-label { width: 125px; color: #6c757d; flex-shrink: 0; font-size: 13px; }
        .info-sep { margin-right: 8px; color: #adb5bd; }
        .info-value { font-weight: 600; color: #212529; }
        .page-header-card {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            border-radius: 12px;
            padding: 18px 24px;
            color: white;
            margin-bottom: 20px;
        }
        .page-header-card .company-name { font-size: 18px; font-weight: 700; }
        .page-header-card .company-tagline { font-size: 12px; opacity: .88; margin-top: 2px; }
        .item-table th {
            background-color: #f8fafc;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #495057;
            border-bottom: 2px solid #e2e8f0;
            padding: 10px 12px;
        }
        .item-table td { vertical-align: middle; font-size: 13.5px; padding: 12px; }
        .orig-price { font-size: 11.5px; color: #94a3b8; text-decoration: line-through; display: block; }
        .total-row { background-color: #f8fafc; font-weight: 600; }
        .neg-total { color: #d97706; font-weight: 700; }
        .orig-total-strike { color: #94a3b8; text-decoration: line-through; font-size: 12px; }
        .summary-item { display: flex; justify-content: space-between; font-size: 13px; padding: 6px 0; }
        .summary-item .lbl { color: #6c757d; }
        .history-item { 
            border-left: 3px solid #cbd5e1; 
            padding-left: 14px; 
            margin-bottom: 16px; 
            position: relative;
        }
        .history-item.from-customer { border-left-color: #7c4dff; }
        .history-item.from-pt { border-left-color: #0d6efd; }
        .history-meta { font-size: 11.5px; color: #64748b; margin-bottom: 4px; }
        .history-content { 
            font-size: 13px; 
            color: #334155; 
            background: #f8fafc; 
            padding: 8px 12px; 
            border-radius: 8px; 
            border: 1px solid #f1f5f9;
        }

        /* Dark Mode Scoped Overrides */
        html[data-bs-theme="dark"] .negotiate-card {
            background: #1e1e2d !important;
            border-color: #2d3047 !important;
        }
        html[data-bs-theme="dark"] .negotiate-card .card-header {
            background-color: #24263a !important;
            color: #e2e8f0 !important;
            border-bottom-color: #2d3047 !important;
        }
        html[data-bs-theme="dark"] .info-label {
            color: #94a3b8 !important;
        }
        html[data-bs-theme="dark"] .info-value {
            color: #f1f1f9 !important;
        }
        html[data-bs-theme="dark"] .item-table th {
            background-color: #222438 !important;
            color: #e2e8f0 !important;
            border-bottom: 2px solid #3d425c !important;
        }
        html[data-bs-theme="dark"] .total-row {
            background-color: #24263a !important;
        }
        html[data-bs-theme="dark"] .history-content {
            background: #171827 !important;
            color: #cbd5e1 !important;
            border-color: #2d3047 !important;
        }
    </style>
@endpush
 
@section('content')
 
@php
    $isManagerOrAdmin = auth()->user()->isAdmin() || (auth()->user()->isManager() && auth()->user()->divisi === 'sales');
@endphp

{{-- Header Card --}}
<div class="page-header-card shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <div class="company-name"><i class="bi bi-chat-left-quote-fill me-2"></i>Ruang Negosiasi Tim Sales</div>
        <div class="company-tagline">Quotation #{{ $quotation->quotation_no }} &bull; {{ $quotation->customer->name ?? '-' }} ({{ $quotation->company ?? ($quotation->customer->company ?? '-') }})</div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        @if($isManagerOrAdmin)
            <a href="{{ route('negotiations.index') }}" class="btn btn-light btn-sm fw-semibold shadow-sm">
                <i class="bi bi-list-ul me-1"></i> Daftar Negosiasi
            </a>
        @endif
        <a href="{{ route('quotations.show', $quotation->id) }}" class="btn btn-outline-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Detail Quotation
        </a>
    </div>
</div>
 
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
 
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ====================================================================
     NEGOTIATION COUNTER & OVERRIDE BANNER
     ==================================================================== --}}
@php
    $currentNegoCount = \App\Models\Negotiate::where('quotation_id', $quotation->id)->where('action', 'negotiate')->count();
    $effectiveLimit = \App\Services\SystemSettingService::effectiveNegotiationLimit($quotation);
    $quotaExceeded = $currentNegoCount >= $effectiveLimit;
    $maxRounds = floor($effectiveLimit / 2);

    $isLocked = in_array($quotation->status, ['accepted', 'po']);
    $hasCustomerInitiated = $negotiations->where('from_customer', true)->count() > 0;
    $lastNegoItem = $negotiations->where('action', 'negotiate')->first() ?? $negotiations->first();
    $isWaitingCustomer = ($lastNegoItem && !$lastNegoItem->from_customer && !$isLocked) || (!$hasCustomerInitiated && !$isLocked);
    $canSalesClose = $lastNegoItem && $lastNegoItem->from_customer && !$isLocked;
    $isFormDisabled = $isLocked || $quotaExceeded || $isWaitingCustomer;

    $hasLastNegoBelowFloor = false;
    if ($lastNegoItem && $lastNegoItem->negotiated_items) {
        $lastItemsArr = is_array($lastNegoItem->negotiated_items) ? $lastNegoItem->negotiated_items : json_decode($lastNegoItem->negotiated_items, true);
        if (is_array($lastItemsArr)) {
            foreach ($lastItemsArr as $it) {
                $fInfo = $floorPrices[$it['id']] ?? \App\Http\Controllers\NegotiateController::resolveItemFloorPrice($it['item'] ?? '');
                $fP = $fInfo['floor_price'] ?? 0;
                if ($fP > 0 && ($it['negotiated_price'] ?? 0) < $fP) {
                    $hasLastNegoBelowFloor = true;
                    break;
                }
            }
        }
    }
@endphp

<div class="alert alert-permanent {{ $quotaExceeded ? 'alert-danger' : 'alert-info' }} d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 shadow-sm" style="border-left: 4px solid {{ $quotaExceeded ? '#dc3545' : '#0dcaf0' }};">
    <div>
        <div class="d-flex align-items-center flex-wrap gap-1">
            <i class="bi {{ $quotaExceeded ? 'bi-exclamation-octagon-fill text-danger' : 'bi-info-circle-fill text-info' }} me-2 fs-5"></i>
            <span class="fw-bold" style="font-size: 0.92rem;">
                Batas Negosiasi Harga: Counter Negosiasi Ke-<strong>{{ $currentNegoCount }}</strong> dari Maksimal <strong>{{ $effectiveLimit }}x</strong>
                <span class="text-muted fw-normal">({{ $maxRounds }}x Saling Balas)</span>
            </span>
            @if($quotation->negotiation_override_quota > 0)
                <span class="badge bg-warning text-dark ms-2"><i class="bi bi-shield-check me-1"></i>Termasuk Override +{{ $quotation->negotiation_override_quota }}x</span>
            @endif
        </div>
        <small class="d-block text-muted mt-1 ms-4 ps-1">
            @if(!$hasCustomerInitiated)
                <span class="text-primary fw-semibold"><i class="bi bi-info-circle-fill me-1"></i>Menunggu Customer mengajukan negosiasi harga pertama kali. Negosiasi harus diawali oleh pihak Customer.</span>
            @elseif($quotaExceeded)
                <span class="text-danger fw-semibold"><i class="bi bi-lock-fill me-1"></i>Kuota negosiasi telah habis ({{ $currentNegoCount }}/{{ $effectiveLimit }}x). Silakan sepakati harga penawaran terakhir atau hubungi Manager Sales.</span>
            @elseif($isWaitingCustomer)
                <span class="text-primary fw-semibold"><i class="bi bi-hourglass-split me-1"></i>Giliran Customer menanggapi. Tim Sales telah mengirimkan penawaran terakhir.</span>
            @else
                <span>Sisa kuota: <strong>{{ $effectiveLimit - $currentNegoCount }}x</strong> kesempatan pengajuan penawaran. (1x Saling Balas = 1 Customer + 1 Sales).</span>
            @endif
        </small>
    </div>
    
    {{-- TOMBOL MANAGER OVERRIDE (KHUSUS MANAGER SALES & ADMIN) --}}
    @if($isManagerOrAdmin)
        <button type="button" class="btn btn-sm btn-warning text-dark fw-bold ms-auto text-nowrap shadow-sm" data-bs-toggle="modal" data-bs-target="#overrideModalShowNego">
            <i class="bi bi-plus-circle-fill me-1"></i> Manager Override (+Kuota)
        </button>
    @endif
</div>

{{-- MODAL MANAGER OVERRIDE --}}
@if($isManagerOrAdmin)
<div class="modal fade" id="overrideModalShowNego" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold"><i class="bi bi-shield-lock-fill me-2"></i>Manager Override Kuota Negosiasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('quotations.override-nego-limit', $quotation->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="small text-muted mb-2">Gunakan fitur ini untuk menambah kuota negosiasi harga bagi customer pada Quotation <strong>#{{ $quotation->quotation_no }}</strong>.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Kuota Tambahan:</label>
                        <select name="additional_quota" class="form-select form-select-sm" required>
                            <option value="1">+1 Kali Negosiasi Tambahan</option>
                            <option value="2">+2 Kali Negosiasi Tambahan (1 Putaran Saling Balas)</option>
                            <option value="4">+4 Kali Negosiasi Tambahan (2 Putaran Saling Balas)</option>
                            <option value="6">+6 Kali Negosiasi Tambahan (3 Putaran Saling Balas)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-warning fw-bold text-dark"><i class="bi bi-check-lg me-1"></i> Tambahkan Kuota</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
 
{{-- Banner Status Accepted --}}
@if($quotation->status === 'accepted')
    @php
        $closedNego = $quotation->negotiates->whereIn('action', ['closed', 'accept'])->first();
    @endphp
    <div class="mb-3 d-flex align-items-center gap-3 shadow-sm" style="background: linear-gradient(135deg, #d4edda, #c3e6cb); border: 1.5px solid #28a745; border-radius: 10px; padding: 16px;">
        <div style="font-size: 32px; line-height:1;">
            <i class="bi bi-patch-check-fill text-success"></i>
        </div>
        <div>
            <div class="fw-bold text-success" style="font-size:15px;">Negosiasi Telah Disetujui</div>
            <div class="text-muted" style="font-size:12px;">Harga yang disepakati telah difinalisasi. Negosiasi tidak dapat dilanjutkan.</div>
            
            @if($quotation->accepted_date)
                <div class="mt-1" style="font-size:11px; color:#555;">
                    <i class="bi bi-clock me-1"></i> Ditutup pada: 
                    <strong>{{ \Carbon\Carbon::parse($quotation->accepted_date)->format('d F Y') }} </strong>
                </div>
            @endif
 
            @if($closedNego && $closedNego->negotiated_total)
                <div class="mt-1" style="font-size:11px; color:#555;">
                    <i class="bi bi-tag me-1"></i> Total harga final: 
                    <strong class="text-success">Rp {{ number_format($closedNego->negotiated_total, 0, ',', '.') }}</strong>
                </div>
            @endif
        </div>
    </div>
@endif

@php
    $origTotal = $quotation->items->sum(fn($i) => $i->price * $i->qty);
    $initialNegoTotal = 0;
    $displayPrices = [];

    foreach($quotation->items as $index => $item) {
        $lastPrice = null;
        if(isset($lastNegotiation) && $lastNegotiation && $lastNegotiation->negotiated_items) {
            $itemsArray = is_array($lastNegotiation->negotiated_items) 
                ? $lastNegotiation->negotiated_items 
                : json_decode($lastNegotiation->negotiated_items, true);
            
            if(is_array($itemsArray)) {
                $lastItem = collect($itemsArray)->firstWhere('id', $item->id);
                $lastPrice = $lastItem['negotiated_price'] ?? null;
            }
        }
        $priceFinal = old("items.{$index}.negotiated_price", $lastPrice ?? $item->price);
        $displayPrices[$item->id] = $priceFinal;
        $initialNegoTotal += ($priceFinal * $item->qty);
    }

    $diff = $origTotal - $initialNegoTotal;
    $pct  = $origTotal > 0 ? number_format(($diff / $origTotal) * 100, 1) : 0;
@endphp
 
<form action="{{ route('negotiate.store', $quotation->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
 
    <div class="row g-3">
        {{-- KOLOM KIRI --}}
        <div class="col-lg-8">
 
            {{-- Quotation Info --}}
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-file-earmark-text me-1 text-primary"></i> Quotation Information</div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Quotation No</span>
                                <span class="info-sep">:</span>
                                <span class="info-value">{{ $quotation->quotation_no }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Request ID</span>
                                <span class="info-sep">:</span>
                                <span class="info-value">#{{ $quotation->request_id }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Created Date</span>
                                <span class="info-sep">:</span>
                                <span class="info-value">{{ $quotation->created_at->format('d F Y') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Expired Date</span>
                                <span class="info-sep">:</span>
                                <span class="info-value">{{ \Carbon\Carbon::parse($quotation->date_expired)->format('d F Y') }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Status</span>
                                <span class="info-sep">:</span>
                                <span class="info-value">
                                    <span class="badge bg-warning text-dark">{{ ucfirst($quotation->status) }}</span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Customer</span>
                                <span class="info-sep">:</span>
                                <span class="info-value">{{ $quotation->customer->name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 
            {{-- Pricelist Negotiation Table --}}
            <div class="negotiate-card card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-1">
                    <span><i class="bi bi-list-ul me-1 text-primary"></i> Item Negosiasi & Harga Dasar Modal (Bahan + Proses)</span>
                    <span class="badge bg-light-primary text-primary border" style="font-size: 0.72rem;">
                        <i class="bi bi-shield-check me-1"></i>Pricelist Protection
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table item-table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-3 text-center" style="width: 5%;">#</th>
                                    <th style="width: 32%;">Item & Harga Dasar</th>
                                    <th class="text-center" style="width: 8%;">Qty</th>
                                    <th class="text-end" style="width: 17%;">Harga Awal</th>
                                    <th class="text-end" style="width: 22%;">Harga Tawaran (Rp)</th>
                                    <th class="text-end pe-3" style="width: 16%;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quotation->items as $index => $item)
                                    @php
                                        $lastPrice = null;
                                        if(isset($lastNegotiation) && $lastNegotiation && $lastNegotiation->negotiated_items) {
                                            $itemsArray = is_array($lastNegotiation->negotiated_items) 
                                                ? $lastNegotiation->negotiated_items 
                                                : json_decode($lastNegotiation->negotiated_items, true);
                                            
                                            if(is_array($itemsArray)) {
                                                $lastItem  = collect($itemsArray)->firstWhere('id', $item->id);
                                                $lastPrice = $lastItem['negotiated_price'] ?? null;
                                            }
                                        }
                                        $displayPrice = old("items.{$index}.negotiated_price", $lastPrice ?? $item->price);
                                        $floorData = $floorPrices[$item->id] ?? ['floor_price' => 0, 'master_price' => 0, 'article' => null];
                                        $floorPrice = $floorData['floor_price'];
                                    @endphp
                                    
                                    <tr>
                                        <td class="ps-3 text-center text-muted fw-bold">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $item->item }}</div>
                                            @if($floorPrice > 0)
                                                <div class="mt-1">
                                                    <span class="badge bg-light-primary text-primary border border-primary py-1 px-2" style="font-size: 0.72rem; font-weight: 500;">
                                                        <i class="bi bi-shield-check me-1"></i>Harga Dasar: Rp {{ number_format($floorPrice, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            @else
                                                <small class="text-muted fst-italic" style="font-size: 0.72rem;">(Item custom / tanpa master)</small>
                                            @endif
                                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                            @if($isFormDisabled)
                                                <input type="hidden" name="items[{{ $index }}][negotiated_price]" value="{{ $displayPrice }}">
                                            @endif
                                        </td>
                                        <td class="text-center fw-bold">{{ $item->qty }}</td>
                                        <td class="text-end">
                                            <span class="orig-price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-light text-muted fw-semibold" style="font-size:0.75rem;">Rp</span>
                                                <input type="number"
                                                    step="0.01"
                                                    class="form-control form-control-sm text-end fw-semibold negotiated-price"
                                                    {{ $isFormDisabled ? '' : 'name=items['.$index.'][negotiated_price]' }}
                                                    value="{{ $displayPrice }}"
                                                    data-qty="{{ $item->qty }}"
                                                    data-original="{{ $item->price }}"
                                                    data-floor="{{ $floorPrice }}"
                                                    min="{{ $floorPrice > 0 ? $floorPrice : 0 }}" {{ $isFormDisabled ? 'disabled' : 'required' }}
                                                    style="{{ $isFormDisabled ? 'background:#f5f5f5;cursor:not-allowed;opacity:0.7;' : '' }}">
                                            </div>
                                            <div class="floor-warning-badge mt-1" style="display:none;">
                                                <span class="badge bg-danger text-white py-1 px-2 text-wrap text-start" style="font-size:0.70rem; line-height:1.3;">
                                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>Di bawah harga dasar modal
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-end pe-3">
                                            <span class="subtotal-cell fw-semibold text-dark">
                                                Rp {{ number_format($displayPrice * $item->qty, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="4" class="text-end pe-3 py-3">
                                        <div class="orig-total-strike">
                                            Original: Rp {{ number_format($origTotal, 0, ',', '.') }}
                                        </div>
                                        <div class="fw-bold text-muted" style="font-size:13px">Total Negosiasi:</div>
                                    </td>
                                    <td colspan="2" class="text-end pe-3 py-3">
                                        <div class="orig-total-strike" id="origTotalDisplay">
                                            Rp {{ number_format($origTotal, 0, ',', '.') }}
                                        </div>
                                        <div class="neg-total fs-6" id="negTotalDisplay">
                                            Rp {{ number_format($initialNegoTotal, 0, ',', '.') }}
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
 
            {{-- Negotiation Message --}}
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-chat-left-text me-1 text-primary"></i> Pesan Negosiasi Tim Sales</div>
                <div class="card-body">
                    @php
                        $lastDeliveryDate = old('target_delivery_date', $lastNegotiation?->target_delivery_date 
                            ? \Carbon\Carbon::parse($lastNegotiation->target_delivery_date)->format('Y-m-d') 
                            : ($quotation->target_delivery_date ? \Carbon\Carbon::parse($quotation->target_delivery_date)->format('Y-m-d') : ''));
                        $lastPaymentTerms = old('payment_terms', $lastNegotiation?->payment_terms ?? $quotation->payment_terms);
                    @endphp
 
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Pesan Balasan / Keterangan Penawaran <span class="text-danger">*</span></label>
                        <textarea name="negotiation_message" class="form-control form-control-sm @error('negotiation_message') is-invalid @enderror"
                            rows="3" placeholder="Tuliskan catatan tanggapan negosiasi harga kepada Customer..." {{ $isFormDisabled ? 'disabled' : 'required' }}>{{ old('negotiation_message') }}</textarea>
                        @error('negotiation_message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
 
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold" style="font-size:13px">Target Delivery Date</label>
                            <input type="date" name="target_delivery_date"
                                class="form-control form-control-sm @error('target_delivery_date') is-invalid @enderror"
                                value="{{ $lastDeliveryDate }}" {{ $isFormDisabled ? 'disabled' : '' }}>
                            @error('target_delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold" style="font-size:13px">Payment Terms</label>
                            <input type="text" name="payment_terms"
                                class="form-control form-control-sm @error('payment_terms') is-invalid @enderror"
                                value="{{ $lastPaymentTerms }}"
                                placeholder="Contoh: Net 30, Cash, DP 50%" {{ $isFormDisabled ? 'disabled' : '' }}>
                            @error('payment_terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
 
                    <div class="mb-1">
                        <label class="form-label fw-semibold" style="font-size:13px">
                            Support Document <span class="text-muted fw-normal">(PDF / Dokumen Pendukung, opsional)</span>
                        </label>
                        <input type="file" name="support_document"
                            class="form-control form-control-sm @error('support_document') is-invalid @enderror"
                            accept=".pdf,.doc,.docx,.jpg,.png" {{ $isFormDisabled ? 'disabled' : '' }}>
                        @error('support_document')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
 
        </div>
 
        {{-- KOLOM KANAN --}}
        <div class="col-lg-4">
            {{-- PIC Info --}}
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-person-badge me-1 text-primary"></i> Customer PIC Info</div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">PIC Name</span>
                        <span class="info-sep">:</span>
                        <span class="info-value">{{ $quotation->customer->name ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-sep">:</span>
                        <span class="info-value" style="font-size:13px">{{ $quotation->customer->email ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Company</span>
                        <span class="info-sep">:</span>
                        <span class="info-value">{{ $quotation->company ?? ($quotation->customer->company ?? '-') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone</span>
                        <span class="info-sep">:</span>
                        <span class="info-value">{{ $customerAccount->phone ?? '-' }}</span>
                    </div>
                </div>
            </div>
 
            {{-- Summary --}}
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-calculator me-1 text-primary"></i> Negotiation Summary</div>
                <div class="card-body">
                    <div class="summary-item">
                        <span class="lbl">Original total</span>
                        <span class="fw-semibold" id="summaryOriginal">Rp {{ number_format($origTotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="lbl">Negotiated total</span>
                        <span class="fw-bold" style="color:#d97706" id="summaryNegotiated">Rp {{ number_format($initialNegoTotal, 0, ',', '.') }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="summary-item">
                        <span class="lbl">Difference</span>
                        <span class="fw-bold" id="summaryDiff" style="color:#d97706">
                            {{ $diff > 0 ? '-' : '' }}Rp {{ number_format(abs($diff), 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="summary-item">
                        <span class="lbl">Discount %</span>
                        <span id="summaryPct" style="color:#d97706;font-size:13px;font-weight:600;">
                            {{ $diff > 0 ? '-' : '' }}{{ $pct }}%
                        </span>
                    </div>
                </div>
            </div>
 
            {{-- History --}}
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-clock-history me-1 text-primary"></i> Riwayat Diskusi Negosiasi</div>
                <div class="card-body">
                    @forelse($negotiations as $neg)
                        <div class="history-item {{ $neg->from_customer ? 'from-customer' : 'from-pt' }}">
                            <div class="history-meta">
                                <strong>{{ $neg->from_customer ? ($quotation->customer->name ?? 'Customer') : 'Tim Sales (PT. Metinca)' }}</strong>
                                &middot; {{ $neg->created_at->format('d M Y H:i') }}
                            </div>
                            <div class="history-content">{{ $neg->message }}</div>
                            @if($neg->negotiated_total)
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    <span class="badge bg-light text-dark border" style="font-size:11px">
                                        Penawaran: Rp {{ number_format($neg->negotiated_total, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-muted py-3" style="font-size:13px">
                            <i class="bi bi-chat-square-dots" style="font-size:22px;opacity:.3;display:block;margin-bottom:6px"></i>
                            Belum ada riwayat negosiasi
                        </div>
                    @endforelse
                </div>
            </div>
 
            {{-- Actions --}}
            <div class="negotiate-card card">
                <div class="card-body">
                    <div class="action-grid">
 
                        @if($isLocked)
                            <button type="button" class="btn btn-warning w-100" disabled>
                                <i class="bi bi-arrow-left-right"></i> Submit Negotiation
                            </button>
                            <button type="button" class="btn btn-success w-100" disabled>
                                <i class="bi bi-lock-fill"></i> Close Negotiate
                            </button>
                            <a href="{{ route('quotations.show', $quotation->id) }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-left"></i> Kembali ke Detail
                            </a>
                        @elseif($quotaExceeded)
                            <button type="button" class="btn btn-secondary w-100" disabled title="Kuota negosiasi telah habis">
                                <i class="bi bi-lock-fill me-1"></i> Kuota Negosiasi Habis ({{ $currentNegoCount }}/{{ $effectiveLimit }}x)
                            </button>
 
                            @if($canSalesClose)
                                @if($hasLastNegoBelowFloor)
                                    <div class="p-2 border border-warning rounded bg-light mt-1">
                                        <p class="text-danger mb-1 small fw-bold">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Tawaran Customer Di Bawah Harga Dasar Modal
                                        </p>
                                        <p class="small text-muted mb-0" style="font-size: 0.78rem;">
                                            Customer mengajukan harga di bawah modal bahan & proses. Anda tidak dapat menyetujui tawaran ini. Silakan <strong>Kirim Balasan Tawaran Sales</strong> dengan harga minimal sama dengan atau di atas harga dasar modal.
                                        </p>
                                    </div>
                                @else
                                    <div class="p-2 border rounded bg-light mt-1">
                                        <p class="text-success mb-2 small fw-semibold">
                                            <i class="bi bi-info-circle me-1"></i> Customer telah mengajukan tawaran harga. Anda masih dapat menyetujui dan menutup negosiasi ini.
                                        </p>
                                        <button type="button" class="btn btn-success w-100 fw-bold shadow-sm" onclick="submitCloseNegotiate()">
                                            <i class="bi bi-check-circle-fill me-1"></i> Close & Setujui Harga Customer
                                        </button>
                                    </div>
                                @endif
                            @endif

                            <div class="mt-1">
                                <button type="submit" name="action" value="accept" class="btn btn-outline-primary w-100" onclick="return confirm('Yakin menerima quotation ini?\nHarga quotation akan langsung difinalisasi.')">
                                    <i class="bi bi-check-circle me-1"></i> Accept Quotation (Harga Awal)
                                </button>
                            </div>

                            <a href="{{ route('quotations.show', $quotation->id) }}" class="btn btn-outline-secondary w-100 mt-1">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail
                            </a>
                        @elseif(!$hasCustomerInitiated)
                            {{-- CUSTOMER BELUM MENGAJUKAN NEGOSIASI SAMA SEKALI --}}
                            <div class="alert alert-info py-2 px-3 mb-2 small text-center shadow-sm">
                                <i class="bi bi-hourglass-split me-1 text-primary"></i><strong>Menunggu Inisiasi Customer</strong><br>
                                Sesuai SOP, negosiasi harga harus <strong>diawali oleh Customer</strong>. Tim Sales dapat membalas tawaran setelah Customer mengajukan negosiasi pertama kali.
                            </div>
                            <button type="button" class="btn btn-secondary w-100 fw-semibold" disabled title="Menunggu inisiasi negosiasi dari Customer">
                                <i class="bi bi-lock-fill me-1"></i> Menunggu Customer Memulai Negosiasi
                            </button>

                            <a href="{{ route('quotations.show', $quotation->id) }}" class="btn btn-outline-secondary w-100 mt-2">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Quotation
                            </a>
                        @elseif($isWaitingCustomer)
                            {{-- TIM SALES SUDAH MENGIRIM TAWARAN -> MENUNGGU RESPON CUSTOMER --}}
                            <div class="alert alert-info py-2 px-3 mb-2 small text-center shadow-sm">
                                <i class="bi bi-hourglass-split me-1 text-primary"></i><strong>Menunggu Respon Customer</strong><br>
                                Tim Sales telah mengirimkan penawaran harga terakhir. Sesuai giliran negosiasi bolak-balik, silakan tunggu Customer menanggapi atau membalas tawaran.
                            </div>
                            <button type="button" class="btn btn-secondary w-100 fw-semibold mb-2" disabled title="Menunggu respon/balasan dari Customer">
                                <i class="bi bi-hourglass-split me-1"></i> Menunggu Respon Customer
                            </button>

                            <a href="{{ route('quotations.show', $quotation->id) }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail
                            </a>
                        @else
                            {{-- GILIRAN SALES MEMBALAS ATAU MENYETUJUI --}}
                            <button type="submit" name="action" value="negotiate" class="btn btn-warning w-100 fw-bold text-dark shadow-sm mb-2">
                                <i class="bi bi-send-fill me-1"></i> Kirim Balasan Tawaran Sales
                            </button>
   
                            @if($canSalesClose)
                                @if($hasLastNegoBelowFloor)
                                    <div class="p-2 border border-warning rounded bg-light mb-2">
                                        <p class="text-danger mb-1 small fw-bold">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Tawaran Customer Di Bawah Harga Dasar Modal
                                        </p>
                                        <p class="small text-muted mb-0" style="font-size: 0.78rem;">
                                            Customer mengajukan harga di bawah modal bahan & proses (HPP). Anda tidak dapat menyetujui tawaran ini. Silakan ubah harga pada form di samping (minimal $\ge$ harga dasar modal), lalu klik <strong>Kirim Balasan Tawaran Sales</strong> di atas.
                                        </p>
                                    </div>
                                @else
                                    <div class="p-2 border rounded bg-light mb-2">
                                        <p class="text-success mb-2 small fw-semibold">
                                            <i class="bi bi-info-circle me-1"></i> Customer telah mengajukan tawaran harga valid. Anda dapat menyetujui dan menutup negosiasi ini.
                                        </p>
                                        <button type="button" class="btn btn-success w-100 fw-bold shadow-sm" onclick="submitCloseNegotiate()">
                                            <i class="bi bi-check-circle-fill me-1"></i> Close & Setujui Harga Customer
                                        </button>
                                    </div>
                                @endif
                            @endif

                            <a href="{{ route('quotations.show', $quotation->id) }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail
                            </a>
                        @endif
                    </div>
 
                    <div class="text-center mt-2" style="font-size:11px;color:#aaa">
                        @if(!$isLocked)
                            {{ $negotiations->count() }} putaran negosiasi berjalan
                        @else
                            Negosiasi selesai &middot; {{ $negotiations->count() }} putaran
                        @endif
                    </div>
                </div>
            </div>
 
        </div>
    </div>
</form>
 
{{-- Form Hidden untuk Aksi Close --}}
@if($negotiations->count() > 0 && !in_array($quotation->status, ['accepted', 'po']))
    <form id="closeNegotiateForm" method="POST" action="{{ route('negotiate.close', $quotation->id) }}" style="display:none;">
        @csrf
        @method('PATCH')
    </form>
@endif
 
@endsection
 
@push('scripts')
<script>
    function formatRp(n) {
        return 'Rp ' + Math.round(n).toLocaleString('id-ID');
    }
 
    function recalculate() {
        let negTotal = 0;
        let origTotal = 0;
 
        document.querySelectorAll('.negotiated-price').forEach(function(input) {
            const qty      = parseFloat(input.dataset.qty) || 0;
            const original = parseFloat(input.dataset.original) || 0;
            const floor    = parseFloat(input.dataset.floor) || 0;
            const neg      = parseFloat(input.value) || 0;
            const subtotal = neg * qty;
 
            negTotal  += subtotal;
            origTotal += original * qty;
 
            const row         = input.closest('tr');
            const subtotalCell = row.querySelector('.subtotal-cell');
            if (subtotalCell) {
                subtotalCell.textContent = formatRp(subtotal);
                subtotalCell.style.color = neg < original ? '#d97706' : '#212529';
            }

            const warnBadge = row.querySelector('.floor-warning-badge');
            if (warnBadge) {
                if (floor > 0 && neg < floor) {
                    warnBadge.style.display = 'block';
                    input.classList.add('is-invalid');
                } else {
                    warnBadge.style.display = 'none';
                    input.classList.remove('is-invalid');
                }
            }
        });
 
        const diff = origTotal - negTotal;
        const pct  = origTotal > 0 ? ((diff / origTotal) * 100).toFixed(1) : 0;
 
        document.getElementById('negTotalDisplay').textContent  = formatRp(negTotal);
        document.getElementById('summaryNegotiated').textContent = formatRp(negTotal);
        document.getElementById('summaryDiff').textContent      = diff > 0 ? '-' + formatRp(diff) : formatRp(0);
        document.getElementById('summaryPct').textContent       = diff > 0 ? '-' + pct + '%' : '0%';
        document.getElementById('summaryPct').style.color       = diff > 0 ? '#d97706' : '#198754';
    }
 
    document.querySelectorAll('.negotiated-price').forEach(function(input) {
        input.addEventListener('input', recalculate);
    });

    // Validasi saat form balasan sales dikirim
    const negoForm = document.querySelector('form[action*="negotiate"]');
    if (negoForm) {
        negoForm.addEventListener('submit', function(e) {
            const submitter = e.submitter;
            if (submitter && submitter.value === 'negotiate') {
                let invalid = false;
                let errorMsg = '';
                document.querySelectorAll('.negotiated-price').forEach(function(input) {
                    const floor = parseFloat(input.dataset.floor) || 0;
                    const val = parseFloat(input.value) || 0;
                    if (floor > 0 && val < floor) {
                        invalid = true;
                        const row = input.closest('tr');
                        const itemName = row ? row.querySelector('.fw-semibold')?.textContent?.trim() : 'Item';
                        errorMsg += '- ' + itemName + ': Rp ' + val.toLocaleString('id-ID') + ' < Batas Dasar Rp ' + floor.toLocaleString('id-ID') + '\n';
                    }
                });

                if (invalid) {
                    e.preventDefault();
                    alert('Balasan penawaran tidak dapat dikirim!\nHarga tidak boleh berada di bawah harga dasar modal (bahan + proses):\n\n' + errorMsg);
                    return false;
                }
            }
        });
    }
 
    recalculate();
</script>
 
<script>
    function submitCloseNegotiate() {
        if (confirm('Yakin menyepakati dan menutup negosiasi ini?\nHarga penawaran terakhir akan difinalisasi menjadi harga quotation resmi.')) {
            document.getElementById('closeNegotiateForm').submit();
        }
    }
</script>
@endpush