{{-- Include layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'PT. Metinca Prima Industrial Works')

{{-- Untuk menggunakan css --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/toastify-js/src/toastify.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
@endpush

{{-- Isi content --}}
@section('content')
<section id="multiple-column-form">
    <div class="mb-3">
        <h3 class="card-title">Create Amandement PO!</h3>
    </div>
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form action="{{ route('purchase-orders.store-amandement', $lastPo->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf 
                            
                            {{-- Hidden Input untuk mengunci Item Internal Spesifik --}}
                            @if(isset($selectedItem))
                                <input type="hidden" name="purchase_order_internal_id" value="{{ $selectedItem->id }}">
                            @endif

                            {{-- BOX INFORMASI ITEM YANG SEDANG DIAMANDEMEN (PERMANENT CARD CONTAINER) --}}
                            @if(isset($selectedItem))
                                <div class="card border border-primary shadow-sm mb-4 p-3 rounded bg-light-primary" style="background-color: #e7f3ff !important;">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-box-seam-fill fs-5 text-primary me-2"></i>
                                        <h6 class="text-primary mb-0 fw-bold">Target Amandemen Item:</h6>
                                    </div>
                                    <ul class="mb-0 small text-dark" style="list-style-type: none; padding-left: 0;">
                                        <li class="mb-1"><i class="bi bi-dash me-1 text-primary"></i><strong>No. PO External Item:</strong> <span class="badge bg-dark">{{ $itemPoNo }}</span></li>
                                        <li class="mb-1"><i class="bi bi-dash me-1 text-primary"></i><strong>Nama Barang / Item:</strong> <span class="fw-bold text-dark">{{ $selectedItem->item }}</span></li>
                                        <li class="mb-1"><i class="bi bi-dash me-1 text-primary"></i><strong>Part No:</strong> <code>{{ $selectedItem->contract->part_no ?? $selectedItem->part_no ?? '-' }}</code></li>
                                        <li class="mb-1"><i class="bi bi-dash me-1 text-primary"></i><strong>Jumlah Qty Saat Ini:</strong> <span class="fw-semibold text-primary">{{ number_format($selectedItem->qty) }} pcs</span></li>
                                        @if(isset($contract))
                                            <li><i class="bi bi-dash me-1 text-primary"></i><strong>No. Tinjauan Kontrak Terikat:</strong> <span class="badge bg-primary">{{ $contract->contract_no }}</span></li>
                                        @endif
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                {{-- 1. KANTONG QUOTATION (OTOMATIS & READONLY) --}}
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="form-group">
                                        <label for="quotation_no">Quotation Number</label>
                                        <input type="text" name="quotation_no" id="quotation_no" value="{{ $lastPo->quotation->quotation_no }}" readonly class="form-control disabled bg-light">
                                        <input type="hidden" name="quotation_id" value="{{ $lastPo->quotation_id }}">
                                    </div>
                                    @error('quotation_id')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- 2. NOMOR PO UTAMA (DIKUNCI / TIDAK BERUBAH) --}}
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="form-group">
                                        <label for="po_no">PO Number (Item External)</label>
                                        <input type="text" id="po_no" name="po_no" value="{{ $itemPoNo }}" readonly class="form-control disabled bg-light">
                                    </div>
                                </div>

                                {{-- 3. NOMOR AMANDEMEN YANG AKAN DATANG (MODUL 7: LIMIT CHECK) --}}
                                @php
                                    $maxLimit = \App\Services\SystemSettingService::maxAmendmentLimit();
                                    $currentNo = (int) $nextAmendmentNo;
                                    $quotaExceeded = $currentNo > $maxLimit;
                                @endphp
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="form-group">
                                        <label for="next_amandement_no">Amandement No (Sisa Kuota: {{ max(0, $maxLimit - $currentNo + 1) }}/{{ $maxLimit }})</label>
                                        <input type="text" id="next_amandement_no" value="Revisi #{{ $nextAmendmentNo }} (Batas SOP: Max {{ $maxLimit }}x)" readonly class="form-control disabled {{ $quotaExceeded ? 'bg-danger text-white' : 'bg-light' }}">
                                    </div>
                                    @if($quotaExceeded)
                                        <small class="text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i> Batas amandemen telah tercapai. Pengajuan ini akan ditolak oleh sistem.</small>
                                    @endif
                                </div>

                                {{-- 4. TEXTAREA ALASAN AMANDEMEN --}}
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="alasan_amandemen" class="form-label font-weight-bold">Alasan / Pesan Perubahan Dokumen <span class="text-danger">*</span></label>
                                        <textarea name="alasan_amandemen" id="alasan_amandemen" rows="3" class="form-control" placeholder="Tuliskan alasan detail amandemen di sini... (Misal: Perubahan kuantitas atau spesifikasi teknis item dari customer)" required></textarea>
                                    </div>
                                    @error('alasan_amandemen')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- 5. UPLOAD LAMPIRAN BERKAS BARU (WAJIB) --}}
                                <div class="col-12 mb-3">
                                    <label class="form-label font-weight-bold">Upload Dokumen Pendukung Amandemen <span class="text-danger">* (Wajib Diunggah)</span></label>
                                    <div class="card border">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <input type="file" name="attachments" class="multiple-files-filepond" required>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Format berkas yang didukung: PDF, JPG, PNG, DOC/DOCX, XLS/XLSX (Maks. 10MB).</small>
                                    @error('attachments')
                                        <div class="text-danger small mt-1 font-weight-bold">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- TOMBOL SUBMIT & RESET --}}
                                <div class="col-12 d-flex justify-content-end mt-3">
                                    <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary me-1 mb-1">Kembali</a>
                                    <button type="submit" class="btn btn-warning me-1 mb-1 font-weight-bold text-white" {{ $quotaExceeded ? 'disabled' : '' }}>
                                        <i class="bi bi-send-fill me-1"></i> Kirim Amandemen
                                    </button>
                                    <button type="reset" class="btn btn-light-secondary me-1 mb-1" {{ $quotaExceeded ? 'disabled' : '' }}>Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-image-filter/filepond-plugin-image-filter.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond/filepond.js') }}"></script>
<script src="{{ asset('assets/extensions/toastify-js/src/toastify.js') }}"></script>
<script src="{{ asset('assets/static/js/pages/filepond.js') }}"></script>
@endsection