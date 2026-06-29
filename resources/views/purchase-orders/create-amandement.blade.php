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
                                        <label for="po_no">PO Number (Fixed)</label>
                                        <input type="text" id="po_no" name="po_no" value="{{ $lastPo->po_no }}" readonly class="form-control disabled bg-light">
                                    </div>
                                </div>

                                {{-- 3. NOMOR AMANDEMEN YANG AKAN DATANG --}}
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="form-group">
                                        <label for="next_amandement_no">Amandement No (Fixed)</label>
                                        <input type="text" id="next_amandement_no" value="{{ $nextAmendmentNo }}" readonly class="form-control disabled bg-light">
                                    </div>
                                </div>

                                {{-- 4. TEXTAREA ALASAN AMANDEMEN --}}
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="alasan_amandemen" class="form-label font-weight-bold">Alasan / Pesan Perubahan Dokumen <span class="text-danger">*</span></label>
                                        <textarea name="alasan_amandemen" id="alasan_amandemen" rows="3" class="form-control" placeholder="Tuliskan alasan detail amandemen di sini... (Misal: Lampiran revisi teknis dari customer)" required></textarea>
                                    </div>
                                    @error('alasan_amandemen')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- 5. UPLOAD LAMPIRAN BERKAS BARU --}}
                                <div class="col-12 mb-3">
                                    <label class="form-label font-weight-bold">Upload Dokumen Pendukung Amandemen <span class="text-danger">*</span></label>
                                    <div class="card border">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <input type="file" name="attachments" class="multiple-files-filepond" required>
                                            </div>
                                        </div>
                                    </div>
                                    @error('attachments')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- TOMBOL SUBMIT & RESET --}}
                                <div class="col-12 d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-warning me-1 mb-1 font-weight-bold text-white">Kirim Amandemen</button>
                                    <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
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