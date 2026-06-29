@extends('layouts.app')
@section('title', 'PT. Metinca Prima Industrial Works')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
@endpush

@section('content')

<div class="card shadow-sm mb-3">
    <div class="card-header d-flex bg-info justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-dark">
            <i class="bi bi-file-earmark-richtext-fill me-2"></i>
            Detail Item PO Internal
        </h5>
    </div>

    {{-- Info PO Header --}}
    <div class="card-body py-3 px-4">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="border rounded p-3" style="border-left:4px solid #0d6efd !important;">
                    <small class="text-muted d-block">PO No (External)</small>
                    <span class="fw-bold">{{ $internal->purchaseOrder->po_no ?? '-' }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3" style="border-left:4px solid #fd7e14 !important;">
                    <small class="text-muted d-block">Quotation No</small>
                    <span class="fw-semibold">{{ $internal->purchaseOrder->quotation->quotation_no ?? '-' }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3" style="border-left:4px solid #198754 !important;">
                    <small class="text-muted d-block">Customer</small>
                    <span>{{ $internal->purchaseOrder->customer->name ?? '-' }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3" style="border-left:4px solid #dc3545 !important;">
                    <small class="text-muted d-block">Delivery Request</small>
                    <span class="fw-bold text-danger">
                        {{ $internal->purchaseOrder->delivery_request
                            ? \Carbon\Carbon::parse($internal->purchaseOrder->delivery_request)->format('d M Y')
                            : '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Detail Item --}}
<div class="card shadow-sm">
    <div class="card-header py-2" style="solid #0d6efd; background:#f8f9fa;">
        <h6 class="mb-0 fw-bold text-uppercase" style="font-size:0.78rem; letter-spacing:1px; color:#0d6efd;">
            <i class="bi bi-box-seam me-1"></i>Detail Item
        </h6>
    </div>
    <div class="card-body px-4 py-3">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <small class="text-muted">No PO</small>
                    <span class="fw-semibold">{{ $internal->po_no ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <small class="text-muted">Nama Item</small>
                    <span class="fw-semibold">{{ $internal->item }}</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <small class="text-muted">Material</small>
                    <span>{{ $internal->material ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <small class="text-muted">Spesifikasi</small>
                    <span>{{ $internal->spesifikasi ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <small class="text-muted">Article</small>
                    <span>{{ $internal->article ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <small class="text-muted">Qty</small>
                    <span class="fw-bold">{{ $internal->qty }}</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <small class="text-muted">Unit Price</small>
                    <span>Rp {{ number_format($internal->unit_price, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between pb-2">
                    <small class="text-muted">Subtotal</small>
                    <span class="fw-bold text-primary fs-6">
                        Rp {{ number_format($internal->subtotal, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <small class="text-muted">Delivery Date</small>
                    <span>{{ $internal->delivery_date?->format('d M Y') ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <small class="text-muted">Supplier/Vendor</small>
                    <span>{{ $internal->supplier ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <small class="text-muted">PIC Buyer</small>
                    <span>{{ $internal->pic_buyer ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <small class="text-muted">Perusahaan Buyer</small>
                    <span>{{ $internal->company_buyer ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <small class="text-muted">Tanggal Input</small>
                    <span>{{ $internal->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="d-flex justify-content-between pb-2">
                    <small class="text-muted">Notes</small>
                    <span class="fst-italic">{{ $internal->notes ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end px-3 mb-3 mt-2 gap-1">
<a href="{{ route('purchase-orders-internal.edit', $internal->purchaseOrder->id) }}" 
    class="btn btn-sm btn-warning fw-semibold">
    Edit
</a>        <a href="{{ route('purchase-orders-internal.index') }}" class="btn btn-sm btn-danger justify-content-end">
           Back
        </a>
    </div>
</div>
@endsection