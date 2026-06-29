{{-- resources/views/purchase-orders-internal/create.blade.php --}}
@extends('layouts.app')
@section('title', 'PT. Metinca Prima Industrial Works')
 
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    {{-- CSS SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush
 
@section('content')
 
{{-- Info Header PO --}}
<div class="card shadow-sm mb-3">
    <div class="card-header d-flex justify-content-between align-items-center py-3 
        {{ $purchaseOrder->internals->count() > 0 ? 'bg-warning' : 'bg-primary' }}">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-file-earmark-plus-fill me-2"
                style="color: {{ $purchaseOrder->internals->count() > 0 ? '#000' : '#fff' }} !important;"></i>
            <span style="color: {{ $purchaseOrder->internals->count() > 0 ? '#000' : '#fff' }} !important;">
                {{ $purchaseOrder->internals->count() > 0 ? 'Edit' : 'Input' }} PO Internal
            </span>
        </h5>
    </div>
    <div class="card-body py-3 px-4">
        <div class="row g-2">
            <div class="col-md-3">
                <small class="text-muted d-block">PO No (External)</small>
                <span class="fw-bold">{{ $purchaseOrder->po_no ?? '-' }}</span>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">Quotation No</small>
                <span class="fw-semibold">{{ $purchaseOrder->quotation->quotation_no ?? '-' }}</span>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">Customer</small>
                <span>{{ $purchaseOrder->customer->name ?? '-' }}</span>
            </div>
            <div class="col-md-3">
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
 
{{-- PDF Attachment --}}
<div class="card shadow-sm mb-3">
    <div class="card-header py-2" style="background:#e9ecef;">
        <h6 class="mb-0 fw-bold text-uppercase" style="font-size:0.78rem; letter-spacing:1px; color:#6c757d;">
            <i class="bi bi-paperclip me-1"></i>Dokumen PO dari Customer (Terbaru)
        </h6>
    </div>
    <div class="card-body p-2">
        @php
            $attachments = [];
            $rawAttr = $purchaseOrder->attachment;
            
            // Ekstrak semua nama file
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

            // AMBIL HANYA FILE TERAKHIR (TERBARU)
            $latestFile = count($attachments) > 0 ? end($attachments) : null;
        @endphp

        @if($latestFile)
            @php 
                // Bersihkan karakter spasi dan tanda kutip
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

{{-- ================= KOTAK PENCARIAN ARTIKEL BARIS #1 GLOBAL ================= --}}
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
                <small class="text-muted mt-1 d-block">* Menarik data otomatis ke kolom "Item #1" di form bawah. Anda juga bisa langsung mengetik kode artikel di dalam kolom baris item mana pun lalu tekan Enter.</small>
            </div>
            
            {{-- Tombol Send to QC (Awalnya disembunyikan menggunakan d-none) --}}
            <div class="col-md-4 text-center mt-3 mt-md-0 d-none" id="qc_action_area">
                <p class="text-danger small fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill"></i> Data Tidak Ditemukan!</p>
                <button type="button" class="btn btn-sm btn-danger" id="btn_send_qc" data-bs-toggle="tooltip" title="Kirim notifikasi ke QC untuk melengkapi master data">
                    <i class="bi bi-send-fill"></i> Send to QC
                </button>
            </div>
        </div>
    </div>
</div>
{{-- ================= END PENCARIAN ================= --}}

{{-- Form Input Item --}}
<div class="card shadow-sm">
    <div class="card-header py-2" style="background:#e9ecef;">
        <h6 class="mb-0 fw-bold text-uppercase" style="font-size:0.78rem; letter-spacing:1px; color:#0d6efd;">
            <i class="bi bi-list-check me-1"></i>Detail Item PO Internal
            <small class="text-muted fw-normal text-lowercase ms-2">— input berdasarkan dokumen di atas</small>
        </h6>
    </div>
    <div class="card-body mt-2">
 
        @php
            $isEdit = $purchaseOrder->internals->count() > 0;
            $action = $isEdit
                ? route('purchase-orders-internal.update', $purchaseOrder->id)
                : route('purchase-orders-internal.store', $purchaseOrder->id);
        @endphp
 
        <form action="{{ $action }}" method="POST">
            @csrf
            @if($isEdit) @method('PUT') @endif
 
            <div id="item-body">
                {{-- MODE EDIT: JIKA DATA PO INTERNAL SUDAH PERNAH DI-INPUT SEBELUMNYA --}}
                @forelse($purchaseOrder->internals as $index => $item)
                <div class="border rounded p-3 mb-3 item-row">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <span class="fw-bold text-secondary">Item #<span class="row-no">{{ $index + 1 }}</span></span>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove"><i class="bi bi-trash"></i> Hapus</button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">CUSTOMER ORDER NO</label>
                            <input type="text" name="po_no[]" class="form-control form-control-sm" value="{{ $item->po_no }}" placeholder="No PO">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small text-dark"><i class="bi bi-keyboard text-info"></i> Article (Tekan Enter)</label>
                            <input type="text" name="article[]" class="form-control form-control-sm form-article text-uppercase fw-semibold" value="{{ $item->article ?? '' }}" placeholder="Ketik Kode Artikel lalu tekan Enter">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Nama Item <span class="text-danger">*</span></label>
                            <input type="text" name="item[]" class="form-control form-control-sm form-item" value="{{ $item->item }}" placeholder="Nama item" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Material</label>
                            <input type="text" name="material[]" class="form-control form-control-sm form-material" value="{{ $item->material }}" placeholder="Material">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Spesifikasi (Drawing/Berat)</label>
                            <input type="text" name="spesifikasi[]" class="form-control form-control-sm form-spesifikasi" value="{{ $item->spesifikasi }}" placeholder="Spesifikasi teknis">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Qty <span class="text-danger">*</span></label>
                            <input type="number" name="qty[]" class="form-control form-control-sm qty-input" value="{{ $item->qty }}" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Unit Price <span class="text-danger">*</span></label>
                            <input type="number" name="unit_price[]" class="form-control form-control-sm price-input" value="{{ $item->unit_price }}" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Subtotal</label>
                            <input type="text" class="form-control form-control-sm subtotal-cell bg-light fw-semibold"
                                value="Rp {{ number_format($item->subtotal, 0, ',', '.') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Delivery Date</label>
                            <input type="date" name="delivery_date[]" class="form-control form-control-sm" value="{{ $item->delivery_date?->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Supplier/Vendor</label>
                            <input type="text" name="supplier[]" class="form-control form-control-sm" value="{{ $item->supplier }}" placeholder="Supplier/Vendor">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">PIC Buyer</label>
                            <input type="text" name="pic_buyer[]" class="form-control form-control-sm" value="{{ $item->pic_buyer }}" placeholder="Nama PIC">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Perusahaan Buyer</label>
                            <input type="text" name="company_buyer[]" class="form-control form-control-sm" value="{{ $item->company_buyer }}" placeholder="Nama perusahaan">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label mb-1 fw-semibold small">Notes</label>
                            <input type="text" name="notes[]" class="form-control form-control-sm" value="{{ $item->notes }}" placeholder="Catatan tambahan">
                        </div>
                    </div>
                </div>
                
                {{-- MODE INPUT BARU: JIKA BELUM ADA DATA, OTOMATIS AMBIL SEMUA ITEM DARI QUOTATION --}}
                @empty
                    @foreach($purchaseOrder->quotation->items as $qIndex => $qItem)
                    <div class="border rounded p-3 mb-3 item-row" style="border-left: 4px solid #0d6efd !important;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-primary">Item #<span class="row-no">{{ $qIndex + 1 }}</span></span>
                            <button type="button" class="btn btn-sm btn-danger btn-remove"><i class="bi bi-trash me-1"></i>Hapus</button>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">No PO</label>
                                <input type="text" name="po_no[]" class="form-control form-control-sm" value="{{ $purchaseOrder->po_no ?? '' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-dark"><i class="bi bi-keyboard text-info"></i> Article (Tekan Enter)</label>
                                <input type="text" name="article[]" class="form-control form-control-sm form-article text-uppercase fw-semibold" placeholder="Ketik Kode Artikel lalu tekan Enter">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Nama Item <span class="text-danger">*</span></label>
                                <input type="text" name="item[]" class="form-control form-control-sm form-item" value="{{ $qItem->item }}" placeholder="Nama item" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Material</label>
                                <input type="text" name="material[]" class="form-control form-control-sm form-material" placeholder="Material">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Spesifikasi (Drawing/Berat)</label>
                                <input type="text" name="spesifikasi[]" class="form-control form-control-sm form-spesifikasi" placeholder="Spesifikasi teknis">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Qty <span class="text-danger">*</span></label>
                                <input type="number" name="qty[]" class="form-control form-control-sm qty-input" value="{{ $qItem->qty }}" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Unit Price <span class="text-danger">*</span></label>
                                <input type="number" name="unit_price[]" class="form-control form-control-sm price-input" value="{{ (int)$qItem->price }}" min="0" step="0.01" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Subtotal</label>
                                <input type="text" class="form-control form-control-sm subtotal-cell bg-light fw-semibold" 
                                    value="Rp {{ number_format($qItem->qty * $qItem->price, 0, ',', '.') }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Delivery Date</label>
                                <input type="date" name="delivery_date[]" class="form-control form-control-sm" value="{{ $purchaseOrder->delivery_request ? \Carbon\Carbon::parse($purchaseOrder->delivery_request)->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Supplier/Vendor</label>
                                <input type="text" name="supplier[]" class="form-control form-control-sm" placeholder="Supplier/Vendor">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">PIC Buyer</label>
                                <input type="text" name="pic_buyer[]" class="form-control form-control-sm" value="{{ $purchaseOrder->customer->name ?? '' }}" placeholder="Nama PIC">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Perusahaan Buyer</label>
                                <input type="text" name="company_buyer[]" class="form-control form-control-sm" value="{{ $purchaseOrder->company ?? ($purchaseOrder->quotation->company ?? '') }}" placeholder="Nama perusahaan">
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
            <div class="border rounded p-3 mb-3 d-flex justify-content-between align-items-center" style="background:#f0f4ff;">
                <span class="fw-bold">TOTAL MULTI-ITEMS</span>
                <span class="fw-bold fs-5" id="grand-total">Rp 0</span>
            </div>
 
            <div class="d-flex justify-content-end mt-3 gap-1">
                <button type="button" class="btn btn-sm btn-success" id="btn-add-row">
                    <i class="bi bi-plus-lg me-1"></i>Add item
                </button>
                <button type="submit" class="btn btn-primary btn-sm fw-semibold">
                    Save
                </button>
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-sm btn-light">
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

        // --- 1. FITUR UTAMA: INTERCEPT TEKAN ENTER DI FIELD ARTICLE BARIS MANA PUN ---
        document.getElementById('item-body').addEventListener('keydown', function(e) {
            if (e.target.classList.contains('form-article') && e.key === 'Enter') {
                e.preventDefault(); // Kunci form agar tidak men-submit otomatis

                const inputField = e.target;
                const articleCode = inputField.value.trim();
                if (articleCode === '') return;

                const currentRow = inputField.closest('.item-row');

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
                            // Isikan data ke kolom yang berada di baris itu saja
                            currentRow.querySelector('.form-item').value = res.data.part_name ?? '';
                            currentRow.querySelector('.form-material').value = res.data.material ?? '';
                            
                            let specText = [];
                            if (res.data.drawing_no) specText.push(`Drawing: ${res.data.drawing_no}`);
                            if (res.data.berat) specText.push(`Berat: ${res.data.berat} Kg`);
                            currentRow.querySelector('.form-spesifikasi').value = specText.join(' | ');

                            if (res.data.total_price || res.data.price) {
                                currentRow.querySelector('.price-input').value = res.data.total_price || res.data.price;
                            }

                            // Jalankan ulang kalkulator subtotal baris tersebut
                            const qty = parseFloat(currentRow.querySelector('.qty-input').value) || 0;
                            const price = parseFloat(currentRow.querySelector('.price-input').value) || 0;
                            currentRow.querySelector('.subtotal-cell').value = 'Rp ' + (qty * price).toLocaleString('id-ID');
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
        });

        // --- 2. FITUR LAMA: TOP GLOBAL SEARCH BAR (TARGET SPESIFIK ITEM #1) ---
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
                        
                        const firstRow = document.querySelector('#item-body .item-row');
                        if (firstRow) {
                            firstRow.querySelector('.form-article').value = article;
                            firstRow.querySelector('.form-item').value = res.data.part_name ?? '';
                            firstRow.querySelector('.form-material').value = res.data.material ?? '';
                            
                            let specText = [];
                            if (res.data.drawing_no) specText.push(`Drawing: ${res.data.drawing_no}`);
                            if (res.data.berat) specText.push(`Berat: ${res.data.berat} Kg`);
                            firstRow.querySelector('.form-spesifikasi').value = specText.join(' | ');
                            
                            if (res.data.total_price || res.data.price) {
                                firstRow.querySelector('.price-input').value = res.data.total_price || res.data.price;
                            }
                            
                            const qty = parseFloat(firstRow.querySelector('.qty-input').value) || 0;
                            const price = parseFloat(firstRow.querySelector('.price-input').value) || 0;
                            firstRow.querySelector('.subtotal-cell').value = 'Rp ' + (qty * price).toLocaleString('id-ID');
                            updateTotal();
                        }

                        Swal.fire({ icon: 'success', title: 'Data Ditemukan', text: 'Data berhasil ditarik ke Item #1', timer: 1500, showConfirmButton: false });
                    }
                })
                .catch(error => {
                    qcArea.classList.remove('d-none');
                    const firstRow = document.querySelector('#item-body .item-row');
                    if (firstRow) firstRow.querySelector('.form-article').value = article;

                    Swal.fire({ icon: 'warning', title: 'Barang Baru!', text: 'Nomor Artikel tidak ditemukan. Silakan klik "Send to QC".', confirmButtonText: 'Mengerti' });
                });
        }

        btnSearch.addEventListener('click', performSearch);
        searchInput.addEventListener('keypress', function(e) {
            if(e.key === 'Enter') { e.preventDefault(); performSearch(); }
        });

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

        updateTotal();
    });

    // --- 3. FITUR MULTI-ROW DYNAMIC APPEND BLOCK ---
    function newRow(no) {
        return `
        <div class="border rounded p-3 mb-3 item-row" style="border-left: 4px solid #0d6efd !important;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold text-primary">Item #<span class="row-no">${no}</span></span>
                <button type="button" class="btn btn-sm btn-danger btn-remove">
                    <i class="bi bi-trash me-1"></i>Hapus
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-6"><label class="form-label small text-muted mb-1">No PO</label>
                    <input type="text" name="po_no[]" class="form-control form-control-sm" value="{{ $purchaseOrder->po_no ?? '' }}" readonly></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1 text-dark"><i class="bi bi-keyboard text-info"></i> Article (Tekan Enter)</label>
                    <input type="text" name="article[]" class="form-control form-control-sm form-article text-uppercase fw-semibold" placeholder="Ketik Kode Artikel lalu tekan Enter"></div>            
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Nama Item <span class="text-danger">*</span></label>
                    <input type="text" name="item[]" class="form-control form-control-sm form-item" placeholder="Nama item" required></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Material</label>
                    <input type="text" name="material[]" class="form-control form-control-sm form-material" placeholder="Material"></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Spesifikasi (Drawing/Berat)</label>
                    <input type="text" name="spesifikasi[]" class="form-control form-control-sm form-spesifikasi" placeholder="Spesifikasi teknis"></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Qty <span class="text-danger">*</span></label>
                    <input type="number" name="qty[]" class="form-control form-control-sm qty-input" value="1" min="1" required></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Unit Price <span class="text-danger">*</span></label>
                    <input type="number" name="unit_price[]" class="form-control form-control-sm price-input" value="0" min="0" step="0.01" required></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Subtotal</label>
                    <input type="text" class="form-control form-control-sm subtotal-cell bg-light fw-semibold" value="Rp 0" readonly></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Delivery Date</label>
                    <input type="date" name="delivery_date[]" class="form-control form-control-sm" value="{{ $purchaseOrder->delivery_request ? \Carbon\Carbon::parse($purchaseOrder->delivery_request)->format('Y-m-d') : '' }}"></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Supplier/Vendor</label>
                    <input type="text" name="supplier[]" class="form-control form-control-sm" placeholder="Supplier/Vendor"></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">PIC Buyer</label>
                    <input type="text" name="pic_buyer[]" class="form-control form-control-sm" value="{{ $purchaseOrder->customer->name ?? '' }}" placeholder="Nama PIC"></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Perusahaan Buyer</label>
                    <input type="text" name="company_buyer[]" class="form-control form-control-sm" value="{{ $purchaseOrder->company ?? ($purchaseOrder->quotation->company ?? '') }}" placeholder="Nama perusahaan"></div>
                <div class="col-md-12"><label class="form-label small text-muted mb-1">Notes</label>
                    <input type="text" name="notes[]" class="form-control form-control-sm" placeholder="Catatan tambahan"></div>
            </div>
        </div>`;
    }

    document.getElementById('btn-add-row').addEventListener('click', function () {
        const count = document.querySelectorAll('#item-body .item-row').length + 1;
        document.getElementById('item-body').insertAdjacentHTML('beforeend', newRow(count));
        updateTotal();
    });

    document.getElementById('item-body').addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove')) {
            if (document.querySelectorAll('#item-body .item-row').length > 1) {
                e.target.closest('.item-row').remove();
                updateRowNumbers();
                updateTotal();
            }
        }
    });

    document.getElementById('item-body').addEventListener('input', function (e) {
        if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
            const row   = e.target.closest('.item-row');
            const qty   = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            row.querySelector('.subtotal-cell').value = 'Rp ' + (qty * price).toLocaleString('id-ID');
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
        document.querySelectorAll('.subtotal-cell').forEach(cell => {
            total += parseInt(cell.value.replace(/[^0-9]/g, '')) || 0;
        });
        document.getElementById('grand-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>
@endpush