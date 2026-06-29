@extends('layouts.app')
@section('title') Setup Account @endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header py-3" style="background-color: #0d6efd; color: white;">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-person-plus-fill me-2"></i>SETUP ACCOUNT
        </h5>
        <small class="opacity-75">Lengkapi data perusahaan Anda untuk mulai menggunakan layanan kami.</small>
    </div>
    <div class="card-body px-4 py-4">

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('account.store') }}" method="POST">
            @csrf

            {{-- Info Akun (readonly) --}}
            <div class="border rounded p-3 mb-4" style="border-left: 4px solid #0d6efd !important;">
                <h6 class="fw-bold text-uppercase mb-3" style="letter-spacing:1px; font-size:0.78rem; color:#0d6efd;">
                    <i class="bi bi-person me-1"></i>Informasi Akun
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama</label>
                        <input type="text" class="form-control form-control-sm bg-light"
                            value="{{ Auth::user()->name }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control form-control-sm bg-light"
                            value="{{ Auth::user()->email }}" readonly>
                    </div>
                </div>
            </div>

            {{-- Info Perusahaan --}}
            <div class="border rounded p-3 mb-4" style="border-left: 4px solid #fd7e14 !important;">
                <h6 class="fw-bold text-uppercase mb-3" style="letter-spacing:1px; font-size:0.78rem; color:#fd7e14;">
                    <i class="bi bi-building me-1"></i>Informasi Perusahaan
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Perusahaan</label>
                        <input type="text" name="company" class="form-control form-control-sm @error('company') is-invalid @enderror"
                            value="{{ old('company') }}" placeholder="PT. Nama Perusahaan">
                        @error('company') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jabatan</label>
                        <input type="text" name="position" class="form-control form-control-sm @error('position') is-invalid @enderror"
                            value="{{ old('position') }}" placeholder="Manager, Direktur, dll.">
                        @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control form-control-sm @error('phone') is-invalid @enderror"
                            value="{{ old('phone') }}" placeholder="08xx-xxxx-xxxx">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Fax</label>
                        <input type="text" name="fax" class="form-control form-control-sm"
                            value="{{ old('fax') }}" placeholder="021-xxxx-xxxx">
                    </div>
                </div>
            </div>

            {{-- Alamat --}}
            <div class="border rounded p-3 mb-4" style="border-left: 4px solid #198754 !important;">
                <h6 class="fw-bold text-uppercase mb-3" style="letter-spacing:1px; font-size:0.78rem; color:#198754;">
                    <i class="bi bi-geo-alt me-1"></i>Alamat
                </h6>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Street Address</label>
                        <input type="text" name="address" class="form-control form-control-sm @error('address') is-invalid @enderror"
                            value="{{ old('address') }}" placeholder="Jl. Nama Jalan No. XX">
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kota</label>
                        <input type="text" name="city" class="form-control form-control-sm"
                            value="{{ old('city') }}" placeholder="Jakarta">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">ZIP / Kode Pos</label>
                        <input type="text" name="zip" class="form-control form-control-sm"
                            value="{{ old('zip') }}" placeholder="12345">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm fw-semibold">
                    <i class="bi bi-save me-1"></i>Simpan Account
                </button>
                <a href="{{ route('account.show') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-x me-1"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection