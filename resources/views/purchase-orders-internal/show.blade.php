{{-- resources/views/purchase-orders-internal/show.blade.php --}}
@extends('layouts.app')
@section('title', 'PT. Metinca Prima Industrial Works')
 
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
@endpush
 
@section('content')
 
<div class="card shadow-sm mb-3">
    <div class="card-header d-flex bg-primary justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-white">
            <i class="bi bi-file-earmark-ruled-fill me-2"></i>
            Detail PO Internal — {{ $purchaseOrder->po_no }}
        </h5>
    </div>
 
    {{-- Info PO --}}
    <div class="card-body py-3 px-4">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="border rounded p-3" style="border-left:4px solid #0d6efd !important;">
                    <small class="text-muted d-block">PO No (External)</small>
                    <span class="fw-bold fs-6">{{ $purchaseOrder->po_no ?? '-' }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3" style="border-left:4px solid #fd7e14 !important;">
                    <small class="text-muted d-block">Quotation No</small>
                    <span class="fw-semibold">{{ $purchaseOrder->quotation->quotation_no ?? '-' }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3" style="border-left:4px solid #198754 !important;">
                    <small class="text-muted d-block">Customer</small>
                    <span>{{ $purchaseOrder->customer->name ?? '-' }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3" style="border-left:4px solid #dc3545 !important;">
                    <small class="text-muted d-block">Delivery Request</small>
                    <span class="fw-bold text-danger">
                        {{ $purchaseOrder->delivery_request
                            ? \Carbon\Carbon::parse($purchaseOrder->delivery_request)->format('d M Y')
                            : '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
 
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
 
{{-- Tabel Item --}}
<div class="card shadow-sm">
    <div class="card-header py-2 d-flex justify-content-between align-items-center"
        style="solid #198754; background:#f8f9fa;">
        <h6 class="mb-0 fw-bold text-uppercase" style="font-size:0.78rem; letter-spacing:1px;">
            <i class="bi bi-list-ul me-1"></i>Daftar Item PO Internal
            {{-- <span class="badge bg-primary ms-2">{{ $purchaseOrder->internals->count() }} item</span> --}}
        </h6>
        {{-- <span class="fw-bold text-dark small">
            Total: Rp {{ number_format($purchaseOrder->internals->sum('subtotal'), 0, ',', '.') }}
        </span> --}}
    </div>
    <div class="card-body p-0">
{{-- Daftar Item per Card Accordion --}}
<div class="accordion" id="itemAccordion">
    @forelse($purchaseOrder->internals as $i => $item)
    <div class="accordion-item mb-2 border rounded" style="border-left:4px solid #0d6efd !important;">
        <h2 class="accordion-header" id="heading-{{ $i }}">
            <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }} fw-semibold" 
                type="button" data-bs-toggle="collapse"
                data-bs-target="#collapse-{{ $i }}" 
                aria-expanded="{{ $i == 0 ? 'true' : 'false' }}"
                aria-controls="collapse-{{ $i }}">
                <span class="badge bg-primary me-2">#{{ $i + 1 }}</span>
                {{ $item->item }}
                <span class="ms-3 text-muted small fw-normal">{{ $item->po_no ?? '' }}</span>
                <span class="ms-auto me-3 fw-bold text-dark">
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </span>
            </button>
        </h2>
        <div id="collapse-{{ $i }}" 
            class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
            aria-labelledby="heading-{{ $i }}"
            data-bs-parent="#itemAccordion">
            <div class="accordion-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">No PO</small>
                            <span class="fw-semibold">{{ $item->po_no ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Nama Item</small>
                            <span class="fw-semibold">{{ $item->item }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Material</small>
                            <span>{{ $item->material ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Spesifikasi</small>
                            <span>{{ $item->spesifikasi ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Satuan</small>
                            <span>{{ $item->satuan ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Qty</small>
                            <span class="fw-bold">{{ $item->qty }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Unit Price</small>
                            <span>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between pb-1">
                            <small class="text-muted">Subtotal</small>
                            <span class="fw-bold text-primary">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Delivery Date</small>
                            <span>{{ $item->delivery_date?->format('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Supplier/Vendor</small>
                            <span>{{ $item->supplier ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">PIC Buyer</small>
                            <span>{{ $item->pic_buyer ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Perusahaan Buyer</small>
                            <span>{{ $item->company_buyer ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between pb-1">
                            <small class="text-muted">Notes</small>
                            <span class="fst-italic">{{ $item->notes ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center text-muted fst-italic py-3">Belum ada item.</div>
    @endforelse
</div>

{{-- Total --}}
@if($purchaseOrder->internals->count() > 0)
<div class="d-flex justify-content-between align-items-center border rounded p-3 mt-3" 
    style="background:#f0f4ff;">
    <span class="fw-bold">TOTAL ({{ $purchaseOrder->internals->count() }} item, 
        {{ $purchaseOrder->internals->sum('qty') }} qty)</span>
    <span class="fw-bold fs-5 text-primary">
        Rp {{ number_format($purchaseOrder->internals->sum('subtotal'), 0, ',', '.') }}
    </span>
</div>
@endif        <div class="d-flex justify-content-end px-3 mb-3 mt-2 gap-1">
            @if(auth()->user()->isAdmin() || (auth()->user()->isStaff() && auth()->user()->divisi == 'sales'))
            <a href="{{ route('purchase-orders-internal.edit', $purchaseOrder->id) }}"
                class="btn btn-sm btn-warning fw-semibold">
                Edit
            </a>
            @endif
            <a href="{{ route('purchase-orders-internal.index') }}" class="btn btn-sm btn-danger">
                Back
            </a>
        </div>
    </div>
</div>
 
@endsection