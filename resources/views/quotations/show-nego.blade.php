{{-- Extend layout utama --}}
@extends('layouts.app')
 
@section('title', 'PT. Metinca Prima Industrial Works')
 
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <style>
        .negotiate-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .negotiate-card .card-header {
            border-radius: 12px 12px 0 0;
            padding: 10px 18px;
            font-weight: 600;
            font-size: 12px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            background-color: #f0f4f8;
            color: #555;
            border-bottom: 1px solid #e0e6ed;
        }
        .negotiate-card .card-body { padding: 18px; }
        .info-row { display: flex; margin-bottom: 8px; font-size: 14px; }
        .info-label { width: 130px; color: #888; flex-shrink: 0; }
        .info-sep { margin-right: 8px; color: #ccc; }
        .info-value { font-weight: 500; color: #333; }
        .page-header-card {
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
            border-radius: 12px;
            padding: 18px 24px;
            color: white;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .page-header-card .company-name { font-size: 18px; font-weight: 700; }
        .page-header-card .company-tagline { font-size: 12px; opacity: .85; margin-top: 2px; }
        .item-table th {
            background-color: #f5f7fa;
            font-size: 12px;
            font-weight: 600;
            color: #666;
            border-bottom: 2px solid #e0e6ed;
        }
        .item-table td { vertical-align: middle; font-size: 13px; }
        .orig-price { font-size: 11px; color: #bbb; text-decoration: line-through; display: block; }
        .input-rp { position: relative; }
        .input-rp .prefix { position: absolute; left: 8px; top: 50%; transform: translateY(-50%); font-size: 12px; color: #aaa; pointer-events: none; }
        .input-rp input { padding-left: 32px; }
        .total-row { background-color: #f5f7fa; font-weight: 600; }
        .neg-total { color: #e65100; font-weight: 600; }
        .orig-total-strike { color: #bbb; text-decoration: line-through; font-size: 12px; }
        .summary-item { display: flex; justify-content: space-between; font-size: 13px; padding: 5px 0; }
        .summary-item .lbl { color: #888; }
        .action-grid { display: grid; gap: 8px; }
        .history-item { border-left: 3px solid #e0e6ed; padding-left: 12px; margin-bottom: 12px; }
        .history-item.from-customer { border-left-color: #7c4dff; }
        .history-item.from-pt { border-left-color: #00bcd4; }
        .history-meta { font-size: 11px; color: #bbb; margin-bottom: 3px; }
        .history-content { font-size: 13px; color: #555; }
    </style>
@endpush
 
@section('content')
 
{{-- Header --}}
<div class="page-header-card">
    <div>
        <div class="company-name">PT. Metinca Prima Industrial Works</div>
        <div class="company-tagline">Manufacturing & Industrial Solutions</div>
    </div>
    
</div>
 
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
 
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
 
<form action="{{ route('negotiate.store', $quotation->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
 
    <div class="row">
 
        {{-- KIRI --}}
        <div class="col-lg-8">
 
            {{-- Quotation Info --}}
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-file-earmark-text me-1"></i> Quotation Information</div>
                <div class="card-body">
                    <div class="row">
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
 
{{-- Pricelist Negotiation --}}
<div class="negotiate-card card">
    <div class="card-header"><i class="bi bi-list-ul me-1"></i> Pricelist Item Negotiation</div>
    <div class="card-body p-0">
        <table class="table item-table mb-0">
            <thead>
                <tr>
                    <th class="ps-3" style="width:44px">No</th>
                    <th>Item</th>
                    <th class="text-center" style="width:60px">Qty</th>
                    <th class="text-end" style="width:130px">Original Price</th>
                    <th class="text-end" style="width:170px">Negotiated Price</th>
                    <th class="text-end pe-3" style="width:120px">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotation->items as $index => $item)
                @php
                    $lastPrice = null;
                    if(isset($lastNegotiation) && $lastNegotiation && $lastNegotiation->negotiated_items) {
                        $lastItem  = collect($lastNegotiation->negotiated_items)->firstWhere('id', $item->id);
                        $lastPrice = $lastItem['negotiated_price'] ?? null;
                    }
                    $displayPrice = old("items.{$index}.negotiated_price", $lastPrice ?? $item->price);
                    $isAccepted   = $quotation->status === 'accepted';
                @endphp
                <tr>
                    <td class="ps-3 text-center">{{ $loop->iteration }}</td>
                    <td>
                        {{ $item->item }}
                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                        {{-- Hidden input agar nilai tetap terkirim saat disabled --}}
                        @if($isAccepted)
                            <input type="hidden" name="items[{{ $index }}][negotiated_price]" value="{{ $displayPrice }}">
                        @endif
                    </td>
                    <td class="text-center">{{ $item->qty }}</td>
                    <td class="text-end">
                        <span class="orig-price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        <div class="input-rp">
                            <span class="prefix">Rp</span>
                            <input type="number"
                                class="form-control form-control-sm negotiated-price"
                                {{ $isAccepted ? '' : 'name=items['.$index.'][negotiated_price]' }}
                                value="{{ $displayPrice }}"
                                data-qty="{{ $item->qty }}"
                                data-original="{{ $item->price }}"
                                min="0"
                                {{ $isAccepted ? 'disabled' : 'required' }}
                                style="{{ $isAccepted ? 'background:#f5f5f5;cursor:not-allowed;opacity:0.7;' : '' }}">
                        </div>
                    </td>
                    <td class="text-end pe-3">
                        <span class="subtotal-cell fw-semibold">
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
                            Original: Rp {{ number_format($quotation->items->sum(fn($i) => $i->price * $i->qty), 0, ',', '.') }}
                        </div>
                        <div style="font-size:13px">Negotiated total:</div>
                    </td>
                    <td colspan="2" class="text-end pe-3 py-3">
                        <div class="orig-total-strike" id="origTotalDisplay">
                            Rp {{ number_format($quotation->items->sum(fn($i) => $i->price * $i->qty), 0, ',', '.') }}
                        </div>
                        <div class="neg-total" id="negTotalDisplay">
                            Rp {{ number_format($quotation->items->sum(fn($i) => $i->price * $i->qty), 0, ',', '.') }}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div> 
            {{-- Negotiation Message --}}
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-chat-left-text me-1"></i> Negotiation Message</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">
                            Reason / Message <span class="text-danger">*</span>
                        </label>
                        <textarea name="negotiation_message"
                            class="form-control @error('negotiation_message') is-invalid @enderror"
                            rows="4"
                            placeholder="Tuliskan alasan negosiasi atau pesan tambahan untuk pihak PT..."
                            {{ $quotation->status === 'accepted' ? 'disabled' : 'required' }}>{{ old('negotiation_message', $quotation->status === 'accepted' && $lastNegotiation ? $lastNegotiation->message : '') }}</textarea>
                        @error('negotiation_message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" style="font-size:12px">
                            Sampaikan alasan negosiasi harga dengan jelas agar dapat dipertimbangkan.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Target Delivery Date</label>
                        <input type="date" name="target_delivery_date"
                            class="form-control"
                            value="{{ old('target_delivery_date', $quotation->status === 'accepted' && $lastNegotiation && $lastNegotiation->target_delivery_date ? $lastNegotiation->target_delivery_date->format('Y-m-d') : '') }}"
                            min="{{ now()->format('Y-m-d') }}"
                            {{ $quotation->status === 'accepted' ? 'disabled' : '' }}>
                        <div class="form-text" style="font-size:12px">Opsional — jika ada permintaan khusus terkait jadwal pengiriman.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Payment Terms</label>
                        <select name="payment_terms" class="form-select" {{ $quotation->status === 'accepted' ? 'disabled' : '' }}>
                            <option value="">-- Pilih Payment Terms --</option>
                            @php
                                $lastPayment = $quotation->status === 'accepted' && $lastNegotiation ? $lastNegotiation->payment_terms : old('payment_terms');
                            @endphp
                            <option value="cash"        {{ $lastPayment == 'cash'        ? 'selected' : '' }}>Cash</option>
                            <option value="net_30"      {{ $lastPayment == 'net_30'      ? 'selected' : '' }}>Net 30</option>
                            <option value="net_60"      {{ $lastPayment == 'net_60'      ? 'selected' : '' }}>Net 60</option>
                            <option value="dp_50"       {{ $lastPayment == 'dp_50'       ? 'selected' : '' }}>DP 50%</option>
                            <option value="installment" {{ $lastPayment == 'installment' ? 'selected' : '' }}>Installment</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold" style="font-size:13px">Supporting Document (Opsional)</label>
                        @if($quotation->status === 'accepted' && $lastNegotiation && $lastNegotiation->support_document)
                            {{-- Tampilkan link dokumen terakhir jika sudah accepted --}}
                            <div class="form-control d-flex align-items-center gap-2" style="background:#f5f5f5">
                                <i class="bi bi-paperclip"></i>
                                <a href="{{ asset('storage/' . $lastNegotiation->support_document) }}" target="_blank" style="font-size:13px">
                                    {{ basename($lastNegotiation->support_document) }}
                                </a>
                            </div>
                        @else
                            <input type="file" name="support_document" class="form-control"
                                accept=".pdf,.doc,.docx,.jpg,.png"
                                {{ $quotation->status === 'accepted' ? 'disabled' : '' }}>
                        @endif
                        <div class="form-text" style="font-size:12px">Upload dokumen pendukung jika ada (penawaran kompetitor, referensi harga, dll).</div>
                    </div>
                </div>
            </div>
 
        </div>
 
        {{-- KANAN --}}
        <div class="col-lg-4">
 
            {{-- PIC Info --}}
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-person-badge me-1"></i> PIC Information</div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">PIC</span>
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
                        <span class="info-value">{{ $quotation->customer->company ?? '-' }}</span>
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
                <div class="card-header"><i class="bi bi-calculator me-1"></i> Negotiation Summary</div>
                <div class="card-body">
                    <div class="summary-item">
                        <span class="lbl">Original total</span>
                        <span class="fw-semibold" id="summaryOriginal">
                            Rp {{ number_format($quotation->items->sum(fn($i) => $i->price * $i->qty), 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="summary-item">
                        <span class="lbl">Negotiated total</span>
                        <span class="fw-bold" style="color:#e65100" id="summaryNegotiated">
                            Rp {{ number_format($quotation->items->sum(fn($i) => $i->price * $i->qty), 0, ',', '.') }}
                        </span>
                    </div>
                    <hr class="my-2">
                    <div class="summary-item">
                        <span class="lbl">Difference</span>
                        <span class="fw-bold" id="summaryDiff" style="color:#e65100">Rp 0</span>
                    </div>
                    <div class="summary-item">
                        <span class="lbl">Discount %</span>
                        <span id="summaryPct" style="color:#43a047;font-size:13px">0%</span>
                    </div>
                </div>
            </div>
 
            {{-- History --}}
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-clock-history me-1"></i> Negotiation History</div>
                <div class="card-body">
                    @forelse($negotiations as $neg)
                        <div class="history-item {{ $neg->from_customer ? 'from-customer' : 'from-pt' }}">
                            <div class="history-meta">
                                <strong>{{ $neg->from_customer ? ($quotation->customer->name ?? 'Customer') : 'PT. Metinca' }}</strong>
                                &middot; {{ $neg->created_at->format('d M Y H:i') }}
                            </div>
                            <div class="history-content">{{ $neg->message }}</div>
                            @if($neg->negotiated_total)
                                <div class="mt-1">
                                    <span class="badge bg-light text-dark border" style="font-size:11px">
                                        Proposed: Rp {{ number_format($neg->negotiated_total, 0, ',', '.') }}
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

                        @if($quotation->status === 'accepted')
                            <button type="button" class="btn btn-warning" disabled>
                                <i class="bi bi-arrow-left-right"></i> Submit Negotiation
                            </button>
                            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                            <button type="button" class="btn btn-success" disabled>
                                <i class="bi bi-check-circle"></i> Accept & Finalize
                            </button>
                            @endif
                            <a href="{{ route('quotations.show', $quotation->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <div class="text-center mt-1">
                                <span class="badge bg-success px-3 py-2" style="font-size:12px">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    Difinalisasi pada {{ $quotation->accepted_date 
                                        ? \Carbon\Carbon::parse($quotation->accepted_date)->format('d M Y H:i') 
                                        : '-' }}
                                </span>
                            </div>
                        @else
                            <button type="submit" name="action" value="negotiate" class="btn btn-warning">
                                <i class="bi bi-arrow-left-right"></i> Submit Negotiation
                            </button>
                            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                            <button type="submit" name="action" value="accept" class="btn btn-success"
                                onclick="return confirm('Terima harga yang dinegosiasikan? Tindakan ini tidak dapat dibatalkan.')">
                                <i class="bi bi-check-circle"></i> Accept & Finalize
                            </button>
                            @endif
                            <a href="{{ route('quotations.show', $quotation->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        @endif

                    </div>
                    <div class="text-center mt-2" style="font-size:11px;color:#aaa">
                        Negotiations will be recorded and sent to the relevant parties.
                    </div>
                </div>
            </div>
 
        </div>
    </div>
</form>
 
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
            const neg      = parseFloat(input.value) || 0;
            const subtotal = neg * qty;
 
            negTotal  += subtotal;
            origTotal += original * qty;
 
            const row         = input.closest('tr');
            const subtotalCell = row.querySelector('.subtotal-cell');
            if (subtotalCell) {
                subtotalCell.textContent = formatRp(subtotal);
                subtotalCell.style.color = neg < original ? '#e65100' : '#333';
            }
        });
 
        const diff = origTotal - negTotal;
        const pct  = origTotal > 0 ? ((diff / origTotal) * 100).toFixed(1) : 0;
 
        document.getElementById('negTotalDisplay').textContent  = formatRp(negTotal);
        document.getElementById('summaryNegotiated').textContent = formatRp(negTotal);
        document.getElementById('summaryDiff').textContent      = diff > 0 ? '-' + formatRp(diff) : formatRp(0);
        document.getElementById('summaryPct').textContent       = diff > 0 ? '-' + pct + '%' : '0%';
        document.getElementById('summaryPct').style.color       = diff > 0 ? '#e65100' : '#43a047';
    }
 
    document.querySelectorAll('.negotiated-price').forEach(function(input) {
        input.addEventListener('input', recalculate);
    });
 
    recalculate();
</script>
@endpush