{{-- resources/views/purchase-orders-internal/create.blade.php --}}
@extends('layouts.app')
@section('title', 'PT. Metinca Prima Industrial Works')
 
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush
 
@section('content')
 
{{-- Info Header PO --}}
<div class="card shadow-sm mb-3">
    <div class="card-header d-flex justify-content-between align-items-center py-3 
        {{ $purchaseOrder->internals->count() > 0 ? 'bg-warning text-dark' : 'bg-primary text-white' }}">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-file-earmark-plus-fill me-2"></i>
            {{ $purchaseOrder->internals->count() > 0 ? 'Edit' : 'Input' }} PO Internal
        </h5>
    </div>
    <div class="card-body py-3 px-4">
        <div class="row g-2">
            <div class="col-md-3">
                <small class="text-muted d-block">PO No (External)</small>
                <span class="fw-bold">{{ $purchaseOrder->po_no ?? '-' }}</span>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">PO No (Internal Item)</small>
                <span class="fw-bold text-primary">{{ $itemPoNo ?? $purchaseOrder->po_no ?? '-' }}</span>
            </div>
            <div class="col-md-2">
                <small class="text-muted d-block">Quotation No</small>
                <span class="fw-semibold">{{ $purchaseOrder->quotation->quotation_no ?? '-' }}</span>
            </div>
            <div class="col-md-2">
                <small class="text-muted d-block">Customer</small>
                <span>{{ $purchaseOrder->customer->name ?? '-' }}</span>
            </div>
            <div class="col-md-2">
                <small class="text-muted d-block">Delivery Request</small>
                <span class="text-danger fw-semibold">
                    {{ $purchaseOrder->delivery_request
                        ? \Carbon\Carbon::parse($purchaseOrder->delivery_request)->format('d M Y')
                        : '-' }}
                </span>
            </div>
        </div>
    </div>
</div>
 
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- BOX CATATAN AMANDEMEN ITEM (JIKA ADA) --}}
@if(isset($contract) && ($contract->alasan_amandemen || $contract->catatan_sales))
    <div class="card border-warning shadow-sm mb-3 alert-permanent bg-warning-subtle">
        <div class="card-body p-3 d-flex align-items-start">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning"></i>
            <div class="w-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-pencil-square me-1"></i> Catatan Amandemen Item (Revisi #{{ $contract->amandement_no }})
                    </h6>
                    <span class="badge bg-warning text-dark">Amandemen Active</span>
                </div>
                
                @if($contract->alasan_amandemen)
                    <p class="mb-1 small">
                        <strong>Alasan Amandemen Customer:</strong> {{ $contract->alasan_amandemen }}
                    </p>
                @endif
                
                @if($contract->catatan_sales)
                    <p class="mb-0 small">
                        <strong>Catatan Tim Sales:</strong> {{ $contract->catatan_sales }}
                    </p>
                @endif
            </div>
        </div>
    </div>
@endif
 
{{-- PDF Attachment --}}
<div class="card shadow-sm mb-3">
    <div class="card-header py-2 bg-light border-bottom">
        <h6 class="mb-0 fw-bold text-uppercase" style="font-size:0.78rem; letter-spacing:1px;">
            <i class="bi bi-paperclip me-1 text-primary"></i>Dokumen PO dari Customer (Terbaru)
        </h6>
    </div>
    <div class="card-body p-2">
        @php
            $attachments = [];
            $rawAttr = $purchaseOrder->attachment;
            
            if (is_array($rawAttr)) {
                $attachments = $rawAttr;
            } elseif (is_string($rawAttr)) {
                $decoded = json_decode($rawAttr, true);
                if (is_array($decoded)) {
                    $attachments = $decoded;
                } elseif (str_contains($rawAttr, ',')) {
                    $attachments = explode(',', $rawAttr);
                } elseif (!empty($rawAttr)) {
                    $attachments = [$rawAttr];
                }
            }

            $latestFile = count($attachments) > 0 ? end($attachments) : null;
        @endphp

        @if($latestFile)
            @php 
                $cleanFile = trim(trim($latestFile), '"\''); 
            @endphp
            
            @if(!empty($cleanFile))
                <div class="mb-2">
                    <a href="{{ asset('storage/uploads/' . $cleanFile) }}"
                        target="_blank" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Buka File Terbaru: {{ basename($cleanFile) }}
                    </a>
                </div>
                <iframe src="{{ asset('storage/uploads/' . $cleanFile) }}"
                    width="100%" height="400px"
                    style="border:1px solid #ccc;" class="rounded"></iframe>
            @endif
        @else
            <div class="p-2 border rounded bg-light text-center">
                <small class="text-muted"><i class="bi bi-exclamation-circle me-1"></i> File attachment tidak ditemukan.</small>
            </div>
        @endif
    </div>
</div>

{{-- KOTAK PENCARIAN ARTIKEL --}}
<div class="card shadow-sm mb-3" style="border-left: 4px solid #17a2b8;">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-md-8">
                <label class="form-label fw-bold text-info mb-1">Cek Ketersediaan Barang (Database Pricelist / QC)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" id="search_article" class="form-control" placeholder="Ketik Nomor Artikel (Contoh: ART-001) lalu tekan Enter..." autocomplete="off">
                    <button class="btn btn-info text-white" type="button" id="btn_search">Cari Data</button>
                </div>
                <small class="text-muted mt-1 d-block">* Data artikel & harga negosiasi sudah otomatis ditarik dari Quotation. Anda juga dapat mengetik kode artikel di baris mana pun lalu tekan Enter.</small>
            </div>
            
            <div class="col-md-4 text-center mt-3 mt-md-0 d-none" id="qc_action_area">
                <p class="text-danger small fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill"></i> Data Tidak Ditemukan!</p>
                <button type="button" class="btn btn-sm btn-danger" id="btn_send_qc" data-bs-toggle="tooltip" title="Kirim notifikasi ke QC untuk melengkapi master data">
                    <i class="bi bi-send-fill"></i> Send to QC
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Form Input Item --}}
<div class="card shadow-sm">
    <div class="card-header py-2 bg-light border-bottom">
        <h6 class="mb-0 fw-bold text-uppercase" style="font-size:0.78rem; letter-spacing:1px; color:#0d6efd;">
            <i class="bi bi-list-check me-1"></i>Detail Item PO Internal
            <small class="text-muted fw-normal text-lowercase ms-2">— terisi otomatis dari Quotation & Harga Negosiasi</small>
        </h6>
    </div>
    <div class="card-body mt-2">
 
        @php
            $isEdit = isset($selectedItem) || ($purchaseOrder->internals->count() > 0 && !$quotationItemId);
            $action = $isEdit
                ? route('purchase-orders-internal.update', $purchaseOrder->id)
                : route('purchase-orders-internal.store', $purchaseOrder->id);
            $defaultSupplier = 'PT. Metinca Prima Industrial Works';
        @endphp
 
        <form action="{{ $action }}" method="POST" id="po-internal-form">
            @csrf
            @if($isEdit) @method('PUT') @endif
            
            {{-- HIDDEN INPUT UNTUK SPESIFIK ITEM SCOPING --}}
            <input type="hidden" name="purchase_order_id" value="{{ $purchaseOrder->id }}">
            @if(isset($selectedItem))
                <input type="hidden" name="purchase_order_internal_id" value="{{ $selectedItem->id }}">
            @endif
 
            <div id="item-body">
                @php
                    if ($selectedItem) {
                        $itemsToEdit = collect([$selectedItem]);
                    } elseif ($quotationItemId) {
                        $itemsToEdit = collect();
                    } else {
                        $itemsToEdit = $purchaseOrder->internals;
                    }
                @endphp
                {{-- MODE EDIT: JIKA DATA PO INTERNAL SUDAH PERNAH DI-INPUT SEBELUMNYA --}}
                @forelse($itemsToEdit as $index => $item)
                <div class="border rounded p-3 mb-3 item-row">
                    <input type="hidden" name="internal_id[]" value="{{ $item->id }}">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <span class="fw-bold text-secondary">Item #<span class="row-no">{{ $index + 1 }}</span></span>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove"><i class="bi bi-trash"></i> Hapus</button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">CUSTOMER ORDER NO</label>
                            <input type="text" name="po_no[]" class="form-control form-control-sm bg-light fw-semibold" value="{{ $item->po_no ?? $itemPoNo ?? $purchaseOrder->po_no }}" placeholder="No PO">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small"><i class="bi bi-upc-scan text-info"></i> Article (Tekan Enter)</label>
                            <input type="text" name="article[]" class="form-control form-control-sm form-article text-uppercase fw-semibold" value="{{ $item->article ?? '' }}" placeholder="Ketik Kode Artikel lalu tekan Enter">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Nama Item <span class="text-danger">*</span></label>
                            <input type="text" name="item[]" class="form-control form-control-sm form-item fw-semibold" value="{{ $item->item }}" placeholder="Nama item" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Material</label>
                            <input type="text" name="material[]" class="form-control form-control-sm form-material" value="{{ $item->material }}" placeholder="Material">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Spesifikasi (Drawing/Berat/Remark)</label>
                            <input type="text" name="spesifikasi[]" class="form-control form-control-sm form-spesifikasi" value="{{ $item->spesifikasi }}" placeholder="Drawing / Berat / Remark">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Qty <span class="text-danger">*</span></label>
                            <input type="number" name="qty[]" class="form-control form-control-sm qty-input" value="{{ $item->qty }}" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small"><i class="bi bi-lock-fill text-secondary me-1"></i> Unit Price (Harga Kesepakatan) <span class="badge bg-secondary-subtle text-secondary border ms-1" style="font-size: 0.7rem;">Terkunci</span> <span class="text-danger">*</span></label>
                            <input type="number" name="unit_price[]" class="form-control form-control-sm price-input fw-bold text-success border-success" value="{{ (float)$item->unit_price }}" min="0" step="0.01" readonly required>
                            <small class="text-muted d-block mt-1" style="font-size: 0.72rem;"><i class="bi bi-shield-lock me-1"></i>Harga terkunci otomatis dari kesepakatan Quotation / Negosiasi</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Subtotal</label>
                            <input type="text" class="form-control form-control-sm subtotal-cell fw-bold text-primary"
                                value="Rp {{ number_format($item->subtotal ?? ($item->qty * $item->unit_price), 0, ',', '.') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Delivery Date</label>
                            <input type="date" name="delivery_date[]" class="form-control form-control-sm" value="{{ $item->delivery_date?->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Supplier/Vendor</label>
                            <input type="text" name="supplier[]" class="form-control form-control-sm" value="{{ $item->supplier ?? $defaultSupplier }}" placeholder="Supplier/Vendor">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">PIC Buyer</label>
                            <input type="text" name="pic_buyer[]" class="form-control form-control-sm" value="{{ $item->pic_buyer ?? ($purchaseOrder->customer->name ?? '') }}" placeholder="Nama PIC">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Perusahaan Buyer</label>
                            <input type="text" name="company_buyer[]" class="form-control form-control-sm" value="{{ $item->company_buyer ?? $customerCompany }}" placeholder="Nama perusahaan">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label mb-1 fw-semibold small">Notes</label>
                            <input type="text" name="notes[]" class="form-control form-control-sm" value="{{ $item->notes }}" placeholder="Catatan tambahan">
                        </div>
                    </div>
                </div>
                
                {{-- MODE INPUT BARU DARI QUOTATION --}}
                @empty
                    @php
                        $qItemsToInput = $qItem ? collect([$qItem]) : ($purchaseOrder->quotation?->items ?? collect());
                    @endphp
                    @foreach($qItemsToInput as $qIndex => $qItemRow)
                    @php
                        $artCode = $qItemRow->article?->article_no ?? $qItemRow->article?->internal_part_no ?? '';
                        $artMaterial = $qItemRow->article?->material ?? '';
                        $specs = [];
                        if ($qItemRow->article) {
                            if (!empty($qItemRow->article->drawing_no)) {
                                $dwg = 'Drawing: ' . $qItemRow->article->drawing_no . (!empty($qItemRow->article->drawing_rev) ? ' Rev: ' . $qItemRow->article->drawing_rev : '');
                                $specs[] = $dwg;
                            }
                            if (!empty($qItemRow->article->berat)) {
                                $specs[] = 'Berat: ' . $qItemRow->article->berat . ' Kg';
                            }
                            if (!empty($qItemRow->article->remark)) {
                                $specs[] = 'Remark: ' . $qItemRow->article->remark;
                            }
                        }
                        $artSpec = implode(' | ', $specs);
                        $finalPrice = (float)($qItemRow->price ?? $qItemRow->negotiated_price ?? $qItemRow->original_price ?? 0);
                        $rowSubtotal = $qItemRow->qty * $finalPrice;
                        $itemNumber = isset($qItemIndex) && $qItem ? $qItemIndex : ($qIndex + 1);
                        $rowPoNo = $itemPoNo ?? ($purchaseOrder->po_no . '-' . $itemNumber);
                    @endphp
                    <div class="border rounded p-3 mb-3 item-row" style="border-left: 4px solid #0d6efd !important;">
                        <input type="hidden" name="internal_id[]" value="">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="fw-bold text-primary">Item #<span class="row-no">{{ $itemNumber }}</span></span>
                                <span class="badge bg-light-success text-success border border-success ms-2" style="font-size: 0.72rem;">
                                    <i class="bi bi-link-45deg me-1"></i>Otomatis dari Quotation #{{ $purchaseOrder->quotation->quotation_no ?? '-' }}
                                </span>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger btn-remove"><i class="bi bi-trash me-1"></i>Hapus</button>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">No PO (Item)</label>
                                <input type="text" name="po_no[]" class="form-control form-control-sm bg-light fw-bold text-primary" value="{{ $rowPoNo }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small"><i class="bi bi-upc-scan text-info"></i> Article (Tekan Enter)</label>
                                <input type="text" name="article[]" class="form-control form-control-sm form-article text-uppercase fw-semibold" value="{{ $artCode }}" placeholder="Kode Artikel">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Nama Item <span class="text-danger">*</span></label>
                                <input type="text" name="item[]" class="form-control form-control-sm form-item fw-semibold" value="{{ $qItemRow->item }}" placeholder="Nama item" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Material</label>
                                <input type="text" name="material[]" class="form-control form-control-sm form-material" value="{{ $artMaterial }}" placeholder="Material">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Spesifikasi (Drawing/Berat/Remark)</label>
                                <input type="text" name="spesifikasi[]" class="form-control form-control-sm form-spesifikasi" value="{{ $artSpec }}" placeholder="Drawing / Berat / Remark">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Qty <span class="text-danger">*</span></label>
                                <input type="number" name="qty[]" class="form-control form-control-sm qty-input fw-semibold" value="{{ $qItemRow->qty }}" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small"><i class="bi bi-lock-fill text-secondary me-1"></i> Unit Price (Harga Kesepakatan) <span class="badge bg-secondary-subtle text-secondary border ms-1" style="font-size: 0.7rem;">Terkunci</span> <span class="text-danger">*</span></label>
                                <input type="number" name="unit_price[]" class="form-control form-control-sm price-input fw-bold text-success border-success" value="{{ $finalPrice }}" min="0" step="0.01" readonly required>
                                <small class="text-muted d-block mt-1" style="font-size: 0.72rem;"><i class="bi bi-shield-lock me-1"></i>Harga terkunci otomatis dari kesepakatan Quotation / Negosiasi</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Subtotal</label>
                                <input type="text" class="form-control form-control-sm subtotal-cell fw-bold text-primary" 
                                    value="Rp {{ number_format($rowSubtotal, 0, ',', '.') }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Delivery Date</label>
                                <input type="date" name="delivery_date[]" class="form-control form-control-sm" value="{{ $purchaseOrder->delivery_request ? \Carbon\Carbon::parse($purchaseOrder->delivery_request)->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Supplier/Vendor</label>
                                <input type="text" name="supplier[]" class="form-control form-control-sm" value="{{ $defaultSupplier }}" placeholder="Supplier/Vendor">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">PIC Buyer</label>
                                <input type="text" name="pic_buyer[]" class="form-control form-control-sm" value="{{ $purchaseOrder->customer->name ?? '' }}" placeholder="Nama PIC">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Perusahaan Buyer</label>
                                <input type="text" name="company_buyer[]" class="form-control form-control-sm" value="{{ $customerCompany }}" placeholder="Nama perusahaan">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label mb-1 fw-semibold small text-muted">Notes</label>
                                <input type="text" name="notes[]" class="form-control form-control-sm" placeholder="Catatan tambahan">
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endforelse
            </div>

            {{-- Grand Total --}}
            <div class="border rounded p-3 mb-3 d-flex justify-content-between align-items-center grand-total-box">
                <span class="fw-bold fs-6"><i class="bi bi-calculator me-2 text-primary"></i>TOTAL MULTI-ITEMS</span>
                <span class="fw-bold fs-4 text-primary" id="grand-total">Rp 0</span>
            </div>
 
            <div class="d-flex justify-content-end mt-3 gap-2">
                {{-- Tombol Add Item di-hidden agar item PO Internal konsisten terkunci sesuai Quotation / PO --}}
                <button type="button" class="btn btn-sm btn-success px-3 d-none" id="btn-add-row">
                    <i class="bi bi-plus-lg me-1"></i>Add item
                </button>
                <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold">
                    <i class="bi bi-save me-1"></i>Save PO Internal
                </button>
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-sm btn-outline-secondary px-3">
                    Back
                </a>
            </div>
        </form>
    </div>
</div>
 
@endsection
 
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search_article');
        const btnSearch   = document.getElementById('btn_search');
        const qcArea      = document.getElementById('qc_action_area');
        const btnSendQC   = document.getElementById('btn_send_qc');
        const mainForm    = document.getElementById('po-internal-form');

        // =========================================================================
        // 1. CEGAH FORM TERSIMPAN / SUBMIT OTOMATIS SAAT TEKAN TOMBOL ENTER
        // =========================================================================
        if (mainForm) {
            mainForm.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
                    e.preventDefault(); // Matikan auto-submit form pada tombol Enter

                    // Jika Enter ditekan pada input kode artikel, jalankan lookup data artikel
                    if (e.target.classList.contains('form-article')) {
                        loadArticleForRow(e.target);
                    }
                    return false;
                }
            });
        }

        // =========================================================================
        // 2. FUNGSI UNIFIED LOAD DATA ARTIKEL KE BARIS FORM
        // =========================================================================
        function loadArticleForRow(inputField) {
            const articleCode = inputField.value.trim();
            if (!articleCode) return;

            const currentRow = inputField.closest('.item-row');
            if (!currentRow) return;

            Swal.fire({ 
                title: 'Memuat Data...', 
                allowOutsideClick: false, 
                didOpen: () => { Swal.showLoading(); }
            });

            fetch(`/articles/requirements/${encodeURIComponent(articleCode)}`)
                .then(response => {
                    if (!response.ok) throw new Error('Not found');
                    return response.json();
                })
                .then(res => {
                    if (res.success && res.data) {
                        const d = res.data;
                        if (d.part_name) currentRow.querySelector('.form-item').value = d.part_name;
                        if (d.material) currentRow.querySelector('.form-material').value = d.material;
                        
                        let specList = [];
                        if (d.drawing_no) {
                            let dwg = `Drawing: ${d.drawing_no}`;
                            if (d.drawing_rev) dwg += ` Rev: ${d.drawing_rev}`;
                            specList.push(dwg);
                        }
                        if (d.berat) {
                            specList.push(`Berat: ${d.berat} Kg`);
                        }
                        if (d.remark) {
                            specList.push(`Remark: ${d.remark}`);
                        }
                        currentRow.querySelector('.form-spesifikasi').value = specList.join(' | ');

                        // JAGA HARGA HASIL NEGOSIASI: Hanya isi harga katalog jika input harga masih kosong atau 0
                        const currentPrice = parseFloat(currentRow.querySelector('.price-input').value) || 0;
                        if (currentPrice <= 0 && (d.total_price || d.price)) {
                            currentRow.querySelector('.price-input').value = d.total_price || d.price;
                        }

                        updateTotal();
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Data spesifikasi artikel dimuat.', timer: 1200, showConfirmButton: false });
                    }
                })
                .catch(() => {
                    Swal.fire({ 
                        icon: 'warning', 
                        title: 'Artikel Baru!', 
                        text: 'Kode artikel tidak ditemukan di database. Silakan isi data teknis secara manual.',
                        confirmButtonText: 'Siap' 
                    });
                });
        }

        // =========================================================================
        // 3. PENCARIAN ATAS DARI KOTAK CEK KETERSEDIAAN BARANG
        // =========================================================================
        function performSearch() {
            const article = searchInput.value.trim();
            if(article === '') return;

            Swal.fire({ title: 'Mencari...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});

            fetch(`/articles/requirements/${encodeURIComponent(article)}`)
                .then(response => {
                    if (!response.ok) throw new Error('Not found');
                    return response.json();
                })
                .then(res => {
                    if(res.success && res.data) {
                        qcArea.classList.add('d-none');
                        const d = res.data;
                        const firstRow = document.querySelector('#item-body .item-row');
                        if (firstRow) {
                            firstRow.querySelector('.form-article').value = article;
                            if (d.part_name) firstRow.querySelector('.form-item').value = d.part_name;
                            if (d.material) firstRow.querySelector('.form-material').value = d.material;
                            
                            let specList = [];
                            if (d.drawing_no) {
                                let dwg = `Drawing: ${d.drawing_no}`;
                                if (d.drawing_rev) dwg += ` Rev: ${d.drawing_rev}`;
                                specList.push(dwg);
                            }
                            if (d.berat) {
                                specList.push(`Berat: ${d.berat} Kg`);
                            }
                            if (d.remark) {
                                specList.push(`Remark: ${d.remark}`);
                            }
                            firstRow.querySelector('.form-spesifikasi').value = specList.join(' | ');
                            
                            // JAGA HARGA HASIL NEGOSIASI
                            const currentPrice = parseFloat(firstRow.querySelector('.price-input').value) || 0;
                            if (currentPrice <= 0 && (d.total_price || d.price)) {
                                firstRow.querySelector('.price-input').value = d.total_price || d.price;
                            }
                            
                            updateTotal();
                        }

                        Swal.fire({ icon: 'success', title: 'Data Ditemukan', text: 'Data spesifikasi ditarik ke Item #1', timer: 1500, showConfirmButton: false });
                    }
                })
                .catch(error => {
                    qcArea.classList.remove('d-none');
                    const firstRow = document.querySelector('#item-body .item-row');
                    if (firstRow) firstRow.querySelector('.form-article').value = article;

                    Swal.fire({ icon: 'warning', title: 'Barang Baru!', text: 'Nomor Artikel tidak ditemukan. Silakan klik "Send to QC".', confirmButtonText: 'Mengerti' });
                });
        }

        if (btnSearch) btnSearch.addEventListener('click', performSearch);
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if(e.key === 'Enter') { e.preventDefault(); performSearch(); }
            });
        }

        if (btnSendQC) {
            btnSendQC.addEventListener('click', function() {
                const article = searchInput.value.trim();
                Swal.fire({
                    title: 'Kirim Permintaan QC?',
                    text: `Meminta QC untuk membuat spesifikasi barang: ${article}`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Terkirim!', 'Permintaan telah dikirim ke QC.', 'success');
                    }
                });
            });
        }

        // =========================================================================
        // 4. HITUNG GRAND TOTAL SECARA REAL-TIME & ON LOAD
        // =========================================================================
        updateTotal();
    });

    function newRow(no) {
        return `
        <div class="border rounded p-3 mb-3 item-row" style="border-left: 4px solid #0d6efd !important;">
            <input type="hidden" name="internal_id[]" value="">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold text-primary">Item #<span class="row-no">${no}</span></span>
                <button type="button" class="btn btn-sm btn-danger btn-remove">
                    <i class="bi bi-trash me-1"></i>Hapus
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-6"><label class="form-label small text-muted mb-1">No PO</label>
                    <input type="text" name="po_no[]" class="form-control form-control-sm bg-light fw-semibold" value="{{ $itemPoNo ?? $purchaseOrder->po_no ?? '' }}" readonly></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1 text-dark"><i class="bi bi-upc-scan text-info"></i> Article (Tekan Enter)</label>
                    <input type="text" name="article[]" class="form-control form-control-sm form-article text-uppercase fw-semibold" placeholder="Ketik Kode Artikel lalu tekan Enter"></div>            
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Nama Item <span class="text-danger">*</span></label>
                    <input type="text" name="item[]" class="form-control form-control-sm form-item fw-semibold" placeholder="Nama item" required></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Material</label>
                    <input type="text" name="material[]" class="form-control form-control-sm form-material" placeholder="Material"></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Spesifikasi (Drawing/Berat/Remark)</label>
                    <input type="text" name="spesifikasi[]" class="form-control form-control-sm form-spesifikasi" placeholder="Drawing / Berat / Remark"></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Qty <span class="text-danger">*</span></label>
                    <input type="number" name="qty[]" class="form-control form-control-sm qty-input fw-semibold" value="1" min="1" required></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1"><i class="bi bi-lock-fill text-secondary me-1"></i>Unit Price (Harga Kesepakatan) <span class="badge bg-secondary-subtle text-secondary border ms-1" style="font-size: 0.7rem;">Terkunci</span> <span class="text-danger">*</span></label>
                    <input type="number" name="unit_price[]" class="form-control form-control-sm price-input bg-light fw-bold text-success border-success" value="0" min="0" step="0.01" readonly required>
                    <small class="text-muted d-block mt-1" style="font-size: 0.72rem;"><i class="bi bi-shield-lock me-1"></i>Harga terkunci otomatis</small></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Subtotal</label>
                    <input type="text" class="form-control form-control-sm subtotal-cell bg-light fw-bold text-dark" value="Rp 0" readonly></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Delivery Date</label>
                    <input type="date" name="delivery_date[]" class="form-control form-control-sm" value="{{ $purchaseOrder->delivery_request ? \Carbon\Carbon::parse($purchaseOrder->delivery_request)->format('Y-m-d') : '' }}"></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Supplier/Vendor</label>
                    <input type="text" name="supplier[]" class="form-control form-control-sm" value="PT. Metinca Prima Industrial Works" placeholder="Supplier/Vendor"></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">PIC Buyer</label>
                    <input type="text" name="pic_buyer[]" class="form-control form-control-sm" value="{{ $purchaseOrder->customer->name ?? '' }}" placeholder="Nama PIC"></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Perusahaan Buyer</label>
                    <input type="text" name="company_buyer[]" class="form-control form-control-sm" value="{{ $purchaseOrder->company ?? ($purchaseOrder->quotation->company ?? ($purchaseOrder->customer->company ?? '')) }}" placeholder="Nama perusahaan"></div>
                <div class="col-md-12"><label class="form-label small text-muted mb-1">Notes</label>
                    <input type="text" name="notes[]" class="form-control form-control-sm" placeholder="Catatan tambahan"></div>
            </div>
        </div>`;
    }

    document.getElementById('btn-add-row')?.addEventListener('click', function () {
        const count = document.querySelectorAll('#item-body .item-row').length + 1;
        document.getElementById('item-body').insertAdjacentHTML('beforeend', newRow(count));
        updateTotal();
    });

    document.getElementById('item-body')?.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove')) {
            if (document.querySelectorAll('#item-body .item-row').length > 1) {
                e.target.closest('.item-row').remove();
                updateRowNumbers();
                updateTotal();
            } else {
                Swal.fire({ icon: 'info', title: 'Perhatian', text: 'Minimal harus ada 1 baris item PO.', timer: 1500, showConfirmButton: false });
            }
        }
    });

    // Realtime perhitungan subtotal per row dan grand total saat qty atau price diketik / diubah
    document.getElementById('item-body')?.addEventListener('input', function (e) {
        if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
            updateTotal();
        }
    });

    document.getElementById('item-body')?.addEventListener('change', function (e) {
        if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
            updateTotal();
        }
    });

    function updateRowNumbers() {
        document.querySelectorAll('#item-body .item-row').forEach((row, i) => {
            const no = row.querySelector('.row-no');
            if (no) no.textContent = i + 1;
        });
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('#item-body .item-row').forEach(row => {
            const qtyInput = row.querySelector('.qty-input');
            const priceInput = row.querySelector('.price-input');
            const subtotalCell = row.querySelector('.subtotal-cell');

            const qty = parseFloat(qtyInput?.value) || 0;
            const price = parseFloat(priceInput?.value) || 0;
            const subtotal = qty * price;

            if (subtotalCell) {
                subtotalCell.value = 'Rp ' + Math.round(subtotal).toLocaleString('id-ID');
            }
            total += subtotal;
        });

        const grandTotalEl = document.getElementById('grand-total');
        if (grandTotalEl) {
            grandTotalEl.textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');
        }
    }
</script>
@endpush