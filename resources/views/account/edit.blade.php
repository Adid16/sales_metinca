@extends('layouts.app')
@section('title', 'Edit My Account - PT. Metinca Prima Industrial Works')

@section('content')
<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-header bg-primary py-3 px-4 rounded-top-3 text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-white d-flex align-items-center gap-2">
            <i class="bi bi-person-gear fs-5"></i> Edit Akun & Pengaturan Profil
        </h5>
        <a href="{{ route('account.show') }}" class="btn btn-sm btn-light-secondary text-white border-0">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
    
    <div class="card-body px-4 py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <h6 class="alert-heading fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Terdapat Kesalahan:</h6>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('account.update') }}" method="POST" id="formUpdateAccount">
            @csrf
            @method('PUT')

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0 text-primary d-flex align-items-center">
                        <i class="bi bi-person-lines-fill me-2"></i> 1. Informasi Akun & PIC
                    </h6>
                    <small class="text-muted">Data identitas utama pengguna</small>
                </div>
                <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
            </div>

            <div class="border rounded p-3 mb-4" style="border-left: 4px solid #435ebe !important;">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">NAMA LENGKAP</label>
                        <input class="form-control form-control-sm bg-light" value="{{ $user->name }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">ALAMAT EMAIL</label>
                        <input class="form-control form-control-sm bg-light" value="{{ $user->email }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">JABATAN / POSISI</label>
                        <input class="form-control form-control-sm" name="position" value="{{ old('position', $account->position) }}" placeholder="Contoh: Purchasing Manager / Staff">
                    </div>
                </div>
            </div>

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
                        <input class="form-control form-control-sm" name="company" value="{{ old('company', $account->company ?? $user->company) }}" placeholder="PT. Nama Perusahaan">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">NOMOR TELEPON / WA</label>
                        <input class="form-control form-control-sm" name="phone" value="{{ old('phone', $account->phone) }}" placeholder="+62 xxx xxxx xxxx">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">KOTA</label>
                        <input class="form-control form-control-sm" name="city" value="{{ old('city', $account->city) }}" placeholder="Contoh: Jakarta / Bekasi">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">NOMOR FAX</label>
                        <input class="form-control form-control-sm" name="fax" value="{{ old('fax', $account->fax) }}" placeholder="Nomor Fax (opsional)">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small fw-bold text-muted">ALAMAT LENGKAP PABRIK / KANTOR</label>
                        <textarea class="form-control form-control-sm" name="address" rows="3" placeholder="Alamat lengkap...">{{ old('address', $account->address) }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">KODE POS (ZIP)</label>
                        <input class="form-control form-control-sm" name="zip" value="{{ old('zip', $account->zip) }}" placeholder="Kode Pos">
                    </div>
                </div>
            </div>

            {{-- SEKSI 3: KEAMANAN & UBAH PASSWORD --}}
            <div class="mb-3">
                <h6 class="fw-bold mb-1 text-primary d-flex align-items-center">
                    <i class="bi bi-shield-lock me-2"></i> 3. Keamanan & Ubah Password
                </h6>
                <div class="text-muted small">
                    <i class="bi bi-info-circle text-primary me-1"></i>Kosongkan kolom password di bawah ini jika Anda tidak ingin mengubah kata sandi.
                </div>
            </div>

            <div class="border rounded p-3 mb-4" style="border-left: 4px solid #10b981 !important;">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">PASSWORD SAAT INI</label>
                        <div class="input-group input-group-sm">
                            <input type="password" name="current_password" id="currentPasswordInput" class="form-control form-control-sm @error('current_password') is-invalid @enderror" placeholder="Masukkan password saat ini">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('currentPasswordInput', this)" title="Lihat/Sembunyikan Password">
                                <i class="bi bi-eye" id="curPassIcon"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">PASSWORD BARU</label>
                        <div class="input-group input-group-sm">
                            <input type="password" name="new_password" id="newPasswordInput" class="form-control form-control-sm @error('new_password') is-invalid @enderror" placeholder="Min. 6 karakter">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('newPasswordInput', this)" title="Lihat/Sembunyikan Password">
                                <i class="bi bi-eye" id="newPassIcon"></i>
                            </button>
                        </div>
                        @error('new_password')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">KONFIRMASI PASSWORD BARU</label>
                        <div class="input-group input-group-sm">
                            <input type="password" name="new_password_confirmation" id="newPasswordConfirmInput" class="form-control form-control-sm" placeholder="Ulangi password baru">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('newPasswordConfirmInput', this)" title="Lihat/Sembunyikan Password">
                                <i class="bi bi-eye" id="newConfirmIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                <a href="{{ route('account.show') }}" class="btn btn-secondary px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-4 fw-semibold">
                    <i class="bi bi-check-lg me-1"></i> Simpan Semua Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePass(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}

document.getElementById('formUpdateAccount').addEventListener('submit', function(e) {
    const newPass = document.getElementById('newPasswordInput').value;
    const newConfirm = document.getElementById('newPasswordConfirmInput').value;
    const curPass = document.getElementById('currentPasswordInput').value;

    if (newPass) {
        if (!curPass) {
            e.preventDefault();
            alert('Silakan masukkan Password Saat Ini untuk mengonfirmasi perubahan kata sandi!');
            return;
        }
        if (newPass.length < 6) {
            e.preventDefault();
            alert('Password Baru minimal 6 karakter!');
            return;
        }
        if (newPass !== newConfirm) {
            e.preventDefault();
            alert('Konfirmasi Password Baru tidak cocok!');
            return;
        }
    }
});
</script>
@endpush