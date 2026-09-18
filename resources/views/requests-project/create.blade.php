{{-- Extend layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'Create Request Project - PT. Metinca Prima Industrial Works')

{{-- Untuk menggunakan css --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css') }}">
    <style>
        .form-card {
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease;
        }

        .form-section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #25396f;
            margin-top: 1.25rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #eef2f6;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .required-field::after {
            content: ' *';
            color: #dc3545;
            font-weight: bold;
        }

        .form-help-text {
            font-size: 0.825rem;
            color: #64748b;
            margin-top: 0.35rem;
        }

        .info-step-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.85rem 1rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .info-step-number {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background-color: #435ebe;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .doc-guideline-box {
            background-color: #f0f7ff;
            border: 1px dashed #435ebe;
            border-radius: 0.5rem;
            padding: 0.85rem 1rem;
            margin-bottom: 1rem;
            color: #1e40af;
            font-size: 0.875rem;
        }

        /* Dark Mode Overrides */
        html[data-bs-theme="dark"] .form-card {
            background-color: #1e1e2d !important;
            border-color: #2d3047 !important;
        }

        html[data-bs-theme="dark"] .form-section-title {
            color: #f1f1f9 !important;
            border-bottom-color: #2d3047 !important;
        }

        html[data-bs-theme="dark"] .info-step-card {
            background-color: #181824 !important;
            border-color: #2d3047 !important;
            color: #cbd5e1 !important;
        }

        html[data-bs-theme="dark"] .doc-guideline-box {
            background-color: rgba(67, 94, 190, 0.15) !important;
            border-color: #435ebe !important;
            color: #93b0ff !important;
        }

        html[data-bs-theme="dark"] .form-help-text {
            color: #94a3b8 !important;
        }

        html[data-bs-theme="dark"] .form-control:read-only,
        html[data-bs-theme="dark"] .form-control[readonly] {
            background-color: #181824 !important;
            color: #94a3b8 !important;
            border-color: #2d3047 !important;
        }
    </style>
@endpush

{{-- Isi content --}}
@section('content')
    <div class="page-heading mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h3 class="mb-1 text-primary fw-bold">New Request Project</h3>
                <p class="text-subtitle text-muted mb-0">Ajukan permintaan penawaran harga atau proyek baru manufaktur pengecoran logam & permesinan presisi.</p>
            </div>
            <a href="{{ route('requests-project.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            {{-- Main Form Column --}}
            <div class="col-lg-8">
                <div class="card form-card mb-4">
                    <div class="card-header bg-primary text-white py-3 px-4 rounded-top-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-plus fs-5"></i>
                            <h5 class="card-title text-white mb-0">Formulir Pengajuan Proyek & Penawaran</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                                <i class="bi bi-check-circle fs-5"></i>
                                <div>{{ session('success') }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Display validation errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h6 class="alert-heading d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Terdapat Kesalahan Input:
                                </h6>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('requests-project.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation">
                            @csrf

                            {{-- SEKSI 1: INFORMASI PEMOHON --}}
                            <h6 class="form-section-title">
                                <i class="bi bi-building-check text-primary"></i> 1. Informasi Pemohon (Requester)
                            </h6>
                            
                            <div class="row g-3">
                                {{-- Company Name --}}
                                <div class="col-md-6">
                                    <label for="company" class="form-label fw-semibold">Nama Perusahaan (PT / CV)</label>
                                    <input type="text" class="form-control @error('company') is-invalid @enderror"
                                        id="company" name="company"
                                        value="{{ old('company') ?? (auth()->user()->account->company ?? auth()->user()->company ?? '') }}"
                                        placeholder="Nama Perusahaan"
                                        {{ auth()->user()->isCustomer() ? 'readonly' : '' }}>
                                    @error('company')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- PIC Name --}}
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold required-field">Nama PIC Pemohon</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') ?? auth()->user()->name }}"
                                        placeholder="Nama Lengkap PIC" required
                                        {{ auth()->user()->isCustomer() ? 'readonly' : '' }}>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold required-field">Alamat Email Resmi</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') ?? auth()->user()->email }}"
                                        placeholder="email@perusahaan.com" required
                                        {{ auth()->user()->isCustomer() ? 'readonly' : '' }}>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold">Nomor Telepon / WhatsApp</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" 
                                        value="{{ old('phone') ?? (auth()->user()->account->phone ?? '') }}"
                                        placeholder="+62 xxx xxxx xxxx"
                                        {{ auth()->user()->isCustomer() ? 'readonly' : '' }}>
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Hidden: customer_id --}}
                            @if(auth()->user()->isCustomer())
                                <input type="hidden" name="customer_id" value="{{ auth()->id() }}">
                            @endif

                            {{-- SEKSI 2: PERIHAL & DOKUMEN TEKNIS --}}
                            <h6 class="form-section-title mt-4">
                                <i class="bi bi-file-earmark-text text-primary"></i> 2. Perihal & Lampiran Dokumen Teknis
                            </h6>

                            {{-- Subject --}}
                            <div class="mb-3">
                                <label for="subject" class="form-label fw-semibold required-field">Perihal Permintaan (Subject)</label>
                                <select class="form-select @error('subject') is-invalid @enderror" id="subject" name="subject" required>
                                    <option value="">-- Pilih Jenis Permintaan --</option>
                                    <option value="quotation" {{ old('subject') == 'quotation' || old('subject') == '' ? 'selected' : '' }}>
                                        Permintaan Penawaran Harga (Quotation)
                                    </option>
                                    <option value="other project" {{ old('subject') == 'other project' ? 'selected' : '' }}>
                                        Inquiry Proyek Baru (New Project Inquiry)
                                    </option>
                                    <option value="meeting" {{ old('subject') == 'meeting' ? 'selected' : '' }}>
                                        Konsultasi Teknis & Pembuatan Sampel (Sample / Trial Production)
                                    </option>
                                </select>
                                @error('subject')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Technical Attachment (Wajib) --}}
                            <div class="mb-3">
                                <label for="attachment" class="form-label fw-semibold required-field">
                                    Lampiran Dokumen Teknis / Drawing 2D & 3D (PDF)
                                </label>
                                
                                <div class="doc-guideline-box">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="bi bi-info-circle-fill fs-6"></i>
                                        <strong>Ketentuan Spesifikasi Teknis:</strong>
                                    </div>
                                    <div>Seluruh spesifikasi komponen (nama part, kuantiti, jenis logam/material, dimensi, toleransi, dan standar mutu) akan <strong>dianalisis langsung dari berkas dokumen atau gambar teknik (drawing)</strong> yang Anda lampirkan di bawah ini.</div>
                                </div>

                                <input type="file" required multiple data-max-files="3"
                                    class="form-control filepond multiple-files-filepond with-validation-filepond @error('attachment') is-invalid @enderror"
                                    id="attachment" name="attachment[]" accept=".pdf">
                                @error('attachment')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-help-text">Format yang didukung: <strong>PDF</strong> (Maksimal 3 berkas, @5MB per berkas).</small>
                            </div>

                            {{-- SEKSI 3: PESAN & CATATAN PENGANTAR --}}
                            <h6 class="form-section-title mt-4">
                                <i class="bi bi-chat-left-text text-primary"></i> 3. Catatan Tambahan (Opsional)
                            </h6>

                            {{-- Message --}}
                            <div class="mb-4">
                                <label for="message" class="form-label fw-semibold">Pesan / Catatan Khusus Pengantar</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="3"
                                    placeholder="Tuliskan catatan tambahan jika ada (misal: target tanggal kebutuhan mendesak, estimasi kebutuhan kuantiti per bulan, atau instruksi khusus)">{{ old('message') }}</textarea>
                                @error('message')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-help-text">Beri catatan ringkas mengenai ekspektasi jadwal atau pengiriman jika diperlukan.</small>
                            </div>

                            {{-- Form Actions --}}
                            <div class="d-flex justify-content-end align-items-center gap-2 pt-3 border-top">
                                <a href="{{ route('requests-project.index') }}" class="btn btn-light-secondary px-4">
                                    <i class="bi bi-x-lg me-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-4 fw-semibold d-inline-flex align-items-center gap-2">
                                    <i class="bi bi-send-fill"></i> Kirim Permintaan Proyek
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Sidebar Information Column --}}
            <div class="col-lg-4">
                <div class="card form-card mb-4">
                    <div class="card-header bg-light py-3 px-4 border-bottom">
                        <h6 class="card-title mb-0 fw-bold d-flex align-items-center gap-2 text-primary">
                            <i class="bi bi-diagram-3-fill"></i> Alur Proses Permintaan
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="info-step-card">
                            <div class="info-step-number">1</div>
                            <div>
                                <div class="fw-bold text-dark mb-1">Pengajuan & Upload Drawing</div>
                                <div class="small text-muted">Pelanggan mengirimkan tiket inquiry beserta lampiran dokumen gambar teknik (drawing).</div>
                            </div>
                        </div>

                        <div class="info-step-card">
                            <div class="info-step-number">2</div>
                            <div>
                                <div class="fw-bold text-dark mb-1">Klaim PIC & Review Teknis</div>
                                <div class="small text-muted">Staf Sales PIC dan tim Engineering PT. Metinca Prima meninjau spesifikasi dan menghitung estimasi biaya modal.</div>
                            </div>
                        </div>

                        <div class="info-step-card">
                            <div class="info-step-number">3</div>
                            <div>
                                <div class="fw-bold text-dark mb-1">Penerbitan Quotation Resmi</div>
                                <div class="small text-muted">Dokumen penawaran harga resmi terbit dan siap untuk disepakati atau dinegosiasikan.</div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-light-primary rounded-3">
                            <div class="d-flex align-items-center gap-2 mb-2 text-primary fw-bold">
                                <i class="bi bi-shield-check fs-5"></i> Kerahasiaan Dokumen
                            </div>
                            <p class="small text-muted mb-0">
                                Seluruh berkas drawing CAD dan dokumen spesifikasi teknis yang Anda lampirkan dijamin kerahasiaannya dan hanya digunakan untuk keperluan kalkulasi produksi PT. Metinca Prima Industrial Works.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

{{-- Untuk menggunakan javascript --}}
@push('scripts')
    <script src="{{ asset('assets/extensions/filepond/filepond.js') }}"></script>
    <script src="{{ asset('assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>
    <script>
        // Inisialisasi FilePond dengan storeAsFile: true (upload langsung bersama form submit)
        FilePond.create(document.querySelector(".multiple-files-filepond"), {
            credits: null,
            allowImagePreview: false,
            allowMultiple: true,
            maxFiles: 3,
            allowFileEncode: false,
            required: true,
            storeAsFile: true,
            labelIdle: 'Tarik & Letakkan file PDF atau <span class="filepond--label-action">Cari Dokumen</span>',
        });
    </script>
@endpush
