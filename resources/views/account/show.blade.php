@extends('layouts.app')
@section('title', 'My Account - PT. Metinca Prima Industrial Works')

@section('content')
<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-header bg-primary d-flex justify-content-between align-items-center py-3 px-4 rounded-top-3 text-white">
        <h5 class="mb-0 fw-bold text-white d-flex align-items-center gap-2">
            <i class="bi bi-person-circle fs-5"></i> My Account & Profil Pengguna
        </h5>
        <a href="{{ route('account.edit') }}" class="btn btn-warning btn-sm fw-semibold shadow-sm">
            <i class="bi bi-pencil-square me-1"></i> Edit Profil & Ubah Password
        </a>
    </div>

    <div class="card-body px-4 py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(!$account || (!$account->company && !$account->phone && !$account->address))
            <div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-exclamation-triangle-fill fs-5 text-warning"></i>
                <div>
                    Data profil perusahaan Anda belum lengkap.
                    <a href="{{ route('account.edit') }}" class="fw-bold text-dark text-decoration-underline ms-1">Lengkapi sekarang →</a>
                </div>
            </div>
        @endif

        {{-- Seksi 1: Informasi Akun --}}
        <div class="mb-3">
            <h6 class="fw-bold mb-0 text-primary d-flex align-items-center">
                <i class="bi bi-person-lines-fill me-2"></i> 1. Informasi Akun & PIC
            </h6>
            <small class="text-muted">Data identitas utama pengguna</small>
        </div>

        <div class="border rounded p-3 mb-4" style="border-left: 4px solid #435ebe !important;">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">NAMA LENGKAP</label>
                    <input class="form-control form-control-sm bg-light" value="{{ Auth::user()->name }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">ALAMAT EMAIL</label>
                    <input class="form-control form-control-sm bg-light" value="{{ Auth::user()->email }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">JABATAN / POSISI</label>
                    <input class="form-control form-control-sm bg-light" value="{{ $account->position ?? '-' }}" readonly>
                </div>
            </div>
        </div>

        {{-- Seksi 2: Informasi Perusahaan --}}
        <div class="mb-3">
            <h6 class="fw-bold mb-0 text-primary d-flex align-items-center">
                <i class="bi bi-building me-2"></i> 2. Informasi Perusahaan
            </h6>
            <small class="text-muted">Alamat kontak dan detail entitas perusahaan</small>
        </div>

        <div class="border rounded p-3 mb-4" style="border-left: 4px solid #e67e22 !important;">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">NAMA PERUSAHAAN (PT / CV)</label>
                    <input class="form-control form-control-sm bg-light" value="{{ $account->company ?? (Auth::user()->company ?? '-') }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">NOMOR TELEPON / WA</label>
                    <input class="form-control form-control-sm bg-light" value="{{ $account->phone ?? '-' }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">KOTA</label>
                    <input class="form-control form-control-sm bg-light" value="{{ $account->city ?? '-' }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">NOMOR FAX</label>
                    <input class="form-control form-control-sm bg-light" value="{{ $account->fax ?? '-' }}" readonly>
                </div>
                <div class="col-md-8">
                    <label class="form-label small fw-bold text-muted">ALAMAT LENGKAP PABRIK / KANTOR</label>
                    <textarea class="form-control form-control-sm bg-light" rows="2" readonly>{{ $account->address ?? '-' }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">KODE POS (ZIP)</label>
                    <input class="form-control form-control-sm bg-light" value="{{ $account->zip ?? '-' }}" readonly>
                </div>
            </div>
        </div>

        {{-- Seksi 3: Keamanan Akun & Password --}}
        <div class="mb-3">
            <h6 class="fw-bold mb-0 text-primary d-flex align-items-center">
                <i class="bi bi-shield-lock me-2"></i> 3. Keamanan Akun & Password
            </h6>
            <small class="text-muted">Status kredensial dan kata sandi akun</small>
        </div>

        <div class="border rounded p-3 mb-4" style="border-left: 4px solid #10b981 !important;">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-box-square bg-success-subtle text-success rounded-3 p-2" style="width: 42px; height: 42px;">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">Password Terenkripsi & Aktif</div>
                        <small class="text-muted">Password Anda terlindungi dengan standar enkripsi Bcrypt. Anda dapat memperbarui password kapan saja.</small>
                    </div>
                </div>
                <a href="{{ route('account.edit') }}" class="btn btn-outline-success btn-sm fw-semibold">
                    <i class="bi bi-key-fill me-1"></i> Ubah Password
                </a>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center pt-3 border-top text-muted small">
            <div>
                <i class="bi bi-clock me-1"></i>
                Terakhir diupdate: {{ $account?->updated_at ? \Carbon\Carbon::parse($account->updated_at)->format('d F Y, H:i') : '-' }}
            </div>
            <a href="{{ route('account.edit') }}" class="btn btn-primary btn-sm px-3 fw-semibold">
                <i class="bi bi-pencil-square me-1"></i> Edit Data Akun
            </a>
        </div>
    </div>
</div>
@endsection