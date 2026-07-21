@extends('layouts.app')
 
@section('title')
    PT. Metinca Prima Industrial Works
@endsection
 
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
        .summary-item { display: flex; justify-content: space-between; font-size: 13px; padding: 5px 0; }
        .summary-item .lbl { color: #888; }
        .history-item { border-left: 3px solid #e0e6ed; padding-left: 12px; margin-bottom: 12px; }
        .history-item.from-customer { border-left-color: #7c4dff; }
        .history-item.from-pt { border-left-color: #00bcd4; }
        .history-meta { font-size: 11px; color: #bbb; margin-bottom: 3px; }
        .history-content { font-size: 13px; color: #555; }
    </style>
@endpush
 
@section('content')
@php
    // JAMINAN BIAR TIDAK UNDEFINED: Ambil data negosiasi paling atas/terakhir dari koleksi history
    $lastNegotiation = $negotiations->first();
@endphp
 
<div class="card shadow-sm">
 
    {{-- Header --}}
    <div class="card-header d-flex justify-content-between bg-info align-items-center py-3">
        <h5 class="mb-0 text-black fw-bold">
            <i class="bi bi-file-earmark-text-fill me-2"></i>Quotation Detail
        </h5>
    </div>
 
    <div class="card-body px-4 py-4">
 
        {{-- Banner Status Accepted --}}
        @if(in_array($quotation->status, ['accepted', 'po', 'rejected', 'ship']))
            @php
                $closedNego = $negotiations->whereIn('action', ['closed', 'accept'])->first();
            @endphp
            <div class="mb-3 d-flex align-items-center gap-3" style="background: linear-gradient(135deg, #d4edda, #c3e6cb); border: 1.5px solid #28a745; border-radius: 10px; padding: 16px;">
                <div style="font-size: 32px; line-height:1;">
                    <i class="bi bi-patch-check-fill text-success"></i>
                </div>
                <div style="flex:1;">
                    <div class="fw-bold text-success" style="font-size:15px;">
                        Negosiasi Telah Disetujui oleh PT. Metinca Prima
                    </div>
                    <div class="text-muted" style="font-size:12px;">
                        Harga yang telah disepakati berlaku sebagai harga final. Negosiasi tidak dapat dilanjutkan.
                    </div>
                    
                    @if($quotation->accepted_date)
                        <div class="mt-1" style="font-size:11px; color:#555;">
                            <i class="bi bi-clock me-1"></i> Disetujui pada:
                            <strong>{{ \Carbon\Carbon::parse($quotation->accepted_date)->format('d F Y, H:i') }} WIB</strong>
                        </div>
                    @endif
 
                    @if($closedNego && $closedNego->negotiated_total)
                        <div class="mt-1" style="font-size:11px; color:#555;">
                            <i class="bi bi-tag me-1"></i> Total harga final:
                            <strong class="text-success">Rp {{ number_format($closedNego->negotiated_total, 0, ',', '.') }}</strong>
                        </div>
                    @endif
                </div>
                
                <div class="ms-auto d-flex flex-column gap-1 text-center">
                    @if(auth()->user()->isCustomer())
                        <a href="{{ route('purchase-orders.create', ['quotation_id' => $quotation->id]) }}" class="btn btn-success btn-sm fw-semibold">
                            <i class="bi bi-bag-check me-1"></i> Buat PO Sekarang
                        </a>
                    @endif
                </div>
            </div>
        @endif
 
        {{-- Company Header Strip & Action Buttons --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h6 class="mb-0 fw-bold text-uppercase" style="color: black; letter-spacing: 1px;">
                    PT. Metinca Prima Industrial Works
                </h6>
                <small class="text-muted">Manufacturing & Industrial Solutions</small>
            </div>
            
            <div class="d-flex gap-1 align-items-center">
                {{-- Actions Menu untuk Customer --}}
                @if(auth()->user()->isCustomer())
                    <a href="{{ route('negotiate.show', ['quotation' => $quotation->id]) }}" class="btn btn-sm btn-warning">
                        @if($quotation->status === 'accepted')
                            <i class="bi bi-lock-fill me-1"></i>View Negotiate
                        @else
                            <i class="bi bi-chat-left-text me-1"></i>Negotiate
                        @endif
                        @if($negotiations->count() > 0)
                            <span class="badge bg-danger ms-1">{{ $negotiations->count() }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('purchase-orders.create', ['quotation_id' => $quotation->id]) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-bag-check me-1"></i>Create PO
                    </a>
                @endif
 
                {{-- Actions Menu untuk Internal Staff / Management --}}
                @if(auth()->user()->isStaff() || auth()->user()->isManager() || auth()->user()->isAdmin())
                    @if($quotation->status == 'created')
                        <a href="{{ route('quotations.edit', $quotation->id) }}" class="btn btn-warning btn-sm text-dark fw-bold">
                            <i class="bi bi-pencil-square"></i> Edit Quotation
                        </a>
                    @else
                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Quotation locked, data tidak dapat diubah.">
                            <button class="btn btn-secondary btn-sm" disabled>
                                <i class="bi bi-lock-fill"></i> Quotation Locked
                            </button>
                        </span>
                    @endif
                     
                    <a href="{{ route('negotiate.show-nego', $quotation->id) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-eye me-1"></i>View Negotiate
                        @if($negotiations->count() > 0)
                            <span class="badge bg-danger ms-1">{{ $negotiations->count() }}</span>
                        @endif
                    </a>
                @endif
 
                <a href="{{ route('quotations.export-pdf', $quotation->id) }}" class="btn btn-sm btn-danger">
                    <i class="bi bi-file-earmark-pdf"></i>
                </a>
                <a href="{{ route('quotations.index') }}" class="btn btn-sm btn-light fw-semibold">
                    Back
                </a>
            </div>
        </div>
 
        {{-- Metadata Row --}}
        <div class="row g-4 mb-4">
            <div class="col-md-7">
                <div class="card border h-100">
                    <div class="card-header py-2 px-3" style="background: #e9ecef;">
                        <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.78rem;">
                            <i class="bi bi-file-text me-1"></i>Quotation Information
                        </h6>
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="d-flex mb-1"><span class="text-muted col-4">Quotation No</span><span class="text-muted col-1">:</span><span class="fw-semibold col-7">{{ $quotation->quotation_no }}</span></div>
                        <div class="d-flex mb-1"><span class="text-muted col-4">Request ID</span><span class="text-muted col-1">:</span><span class="fw-semibold col-7">{{ $quotation->request_id ?? '-' }}</span></div>
                        <div class="d-flex mb-1"><span class="text-muted col-4">Created Date</span><span class="text-muted col-1">:</span><span class="fw-semibold col-7">{{ \Carbon\Carbon::parse($quotation->created_at)->format('d F Y') }}</span></div>
                        <div class="d-flex mb-1">
                            <span class="text-muted col-4">Expired Date</span><span class="text-muted col-1">:</span>
                            <div class="fw-semibold col-7">
                                {{ \Carbon\Carbon::parse($quotation->date_expired)->format('d F Y') }}
                                <span class="badge bg-danger small ms-1">{{ \Carbon\Carbon::parse($quotation->date_expired)->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 
            <div class="col-md-5">
                <div class="card border h-100">
                    <div class="card-header py-2 px-3" style="background: #e9ecef;">
                        <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.78rem;">
                            <i class="bi bi-person-badge me-1"></i>PIC Information
                        </h6>
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="d-flex mb-1"><span class="text-muted col-3">PIC</span><span class="text-muted col-1">:</span><span class="col-8">{{ $quotation->customer->name ?? '-' }}</span></div>
                        <div class="d-flex mb-1"><span class="text-muted col-3">Email</span><span class="text-muted col-1">:</span><span class="col-8">{{ $quotation->customer->email ?? '-' }}</span></div>
                        <div class="d-flex mb-1"><span class="text-muted col-3">Company</span><span class="text-muted col-1">:</span><span class="col-8">{{ $customerAccount->company ?? '-' }}</span></div>
                        <div class="d-flex mb-1"><span class="text-muted col-3">Phone</span><span class="text-muted col-1">:</span><span class="col-8">{{ $customerAccount->phone ?? '-' }}</span></div>
                    </div>
                </div>
            </div>
        </div>
 
        {{-- Item Quotation Table --}}
        <div class="card border mb-4">
            <div class="card-header px-3 py-2" style="background: #e9ecef;">
                <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.78rem;">
                    <i class="bi bi-list-ul me-1"></i>Pricelist Item Quotation
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th width="5%" class="text-center py-2">No</th>
                                <th class="text-center py-2">Item</th>
                                <th width="10%" class="text-center py-2">Qty</th>
                                <th width="20%" class="text-center py-2">Unit Price</th>
                                <th width="20%" class="text-center py-2">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $lastNego = $lastNegotiation ?? null;
                                
                                $lastItemsRaw = [];
                                if ($lastNego && $lastNego->negotiated_items) {
                                    $lastItemsRaw = is_array($lastNego->negotiated_items) 
                                        ? $lastNego->negotiated_items 
                                        : json_decode($lastNego->negotiated_items, true);
                                }
                                $lastItems = collect($lastItemsRaw);
                                
                                $origTotal = $quotation->items->sum(function ($i) use ($lastItems) {
                                    $itemData = $lastItems->firstWhere('id', $i->id);
                                    return ($itemData['original_price'] ?? $i->price) * $i->qty;
                                });
                                $negoTotal = $lastNego?->negotiated_total ?? $origTotal;
                            @endphp
 
                            @forelse ($quotation->items as $index => $item)
                                @php
                                    $lastItemData = $lastItems->firstWhere('id', $item->id);
                                    $displayPrice = $lastItemData['negotiated_price'] ?? $item->price;
                                    $displaySub   = $displayPrice * $item->qty;
                                    $originalPrice = $lastItemData['original_price'] ?? $item->price;
                                    $originalSub   = $originalPrice * $item->qty;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $item->item }}</td>
                                    <td class="text-center">{{ $item->qty }}</td>
                                    <td class="text-end">
                                        @if($lastItemData && $originalPrice != $displayPrice)
                                            <small class="text-muted text-decoration-line-through d-block">
                                                Rp {{ number_format($originalPrice, 0, ',', '.') }}
                                            </small>
                                        @endif
                                        Rp {{ number_format($displayPrice, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end">
                                        @if($lastItemData && $originalSub != $displaySub)
                                            <small class="text-muted text-decoration-line-through d-block">
                                                Rp {{ number_format($originalSub, 0, ',', '.') }}
                                            </small>
                                        @endif
                                        Rp {{ number_format($displaySub, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted fst-italic py-3">Tidak ada item</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot style="background: #f0f4ff;">
                            <tr>
                                <th colspan="4" class="text-end py-2">{{ $lastNego ? 'NEGOTIATED TOTAL' : 'TOTAL' }}</th>
                                <th class="text-end py-2 fw-bold {{ $lastNego ? 'text-danger' : '' }}">
                                    Rp {{ number_format($negoTotal, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
 
        {{-- Hasil Negosiasi Terakhir Card Strip --}}
        @if($lastNegotiation)
            <div class="card border mb-4">
                <div class="card-header px-3 py-2 d-flex justify-content-between align-items-center" style="background: #fff3cd;">
                    <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.78rem;">
                        <i class="bi bi-arrow-left-right me-1"></i>Hasil Negosiasi Terakhir
                    </h6>
                    <small class="text-muted">
                        {{ $lastNegotiation->from_customer ? ($quotation->customer->name ?? 'Customer') : 'PT. Metinca' }} &middot; {{ $lastNegotiation->created_at->format('d M Y, H:i') }}
                    </small>
                </div>
                <div class="card-body py-3 px-3">
                    <div class="row g-3">
                        <div class="col-md-12 text-dark">
                            <div class="d-flex gap-2">
                                <span class="text-muted" style="min-width:130px; font-size:13px">Pesan Negosiasi</span>:
                                <span style="font-size:13px">{{ $lastNegotiation->message ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <span class="text-muted" style="min-width:130px; font-size:13px">Payment Terms</span>:
                                <span class="fw-semibold text-dark" style="font-size:13px">
                                    @php
                                        $paymentLabels = ['cash' => 'Cash', 'net_30' => 'Net 30', 'net_60' => 'Net 60', 'dp_50' => 'DP 50%', 'installment' => 'Installment'];
                                    @endphp
                                    {{ $paymentLabels[$lastNegotiation->payment_terms] ?? ($lastNegotiation->payment_terms ?? '-') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <span class="text-muted" style="min-width:130px; font-size:13px">Target Delivery</span>:
                                <span class="fw-semibold text-dark" style="font-size:13px">
                                    {{ $lastNegotiation->target_delivery_date ? \Carbon\Carbon::parse($lastNegotiation->target_delivery_date)->format('d F Y') : '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <span class="text-muted" style="min-width:130px; font-size:13px">Negotiated Total</span>:
                                <span class="fw-bold text-danger" style="font-size:13px">Rp {{ number_format($lastNegotiation->negotiated_total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @if($lastNegotiation->support_document)
                            <div class="col-md-12">
                                <div class="d-flex gap-2">
                                    <span class="text-muted" style="min-width:130px; font-size:13px">Dokumen Pendukung</span>:
                                    <a href="{{ asset('storage/' . $lastNegotiation->support_document) }}" target="_blank" style="font-size:13px">
                                        <i class="bi bi-paperclip me-1"></i>{{ basename($lastNegotiation->support_document) }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
 
        {{-- Notes & Attachments Master Row --}}
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border h-100">
                    <div class="card-header py-2 px-3" style="background: #e9ecef;">
                        <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.78rem;">
                            <i class="bi bi-sticky me-1"></i>Message
                        </h6>
                    </div>
                    <div class="card-body py-2 px-3">
                        <p class="mb-0 text-muted fst-italic">{{ $quotation->notes ?? 'Tidak ada catatan.' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border h-100">
                    <div class="card-header py-2 px-3" style="background: #e9ecef;">
                        <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.78rem;">
                            <i class="bi bi-paperclip me-1"></i>Request Attachments
                        </h6>
                    </div>
                    <div class="d-flex gap-1 align-content-start py-2 px-3">
                        @forelse ($quotation->request->attachments as $reqs)
                            <a href="/storage/{{ $reqs->file_path }}" target="_blank">
                                <i class="bi bi-file-earmark-pdf me-1"></i>{{ $reqs->document_name }}
                            </a>
                        @empty
                            <span class="text-muted fst-italic">Tidak ada lampiran.</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
 
        {{-- Footer Strip --}}
        <div class="mt-4 pt-3 border-top text-muted small d-flex justify-content-between">
            <span><i class="bi bi-clock me-1"></i>Created: {{ \Carbon\Carbon::parse($quotation->created_at)->format('d F Y, H:i') }} WIB</span>
            <span>{{ $quotation->quotation_no }}</span>
        </div>
 
    </div>
</div>
@endsection