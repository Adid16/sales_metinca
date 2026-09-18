<div class="modal-header border-0 pb-0 pt-3 px-4">
    <div class="d-flex align-items-center">
        <i class="bi bi-pencil-square text-primary me-2 fs-5"></i>
        <h5 class="modal-title fw-bold fs-6 mb-0" id="staticBackdropLabel">Update User Customer</h5>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body px-4 pt-3 pb-4">
    <form action="{{ route('users.update', $user->id) }}" method="POST" id="formEditCustomer_{{ $user->id }}">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label class="form-label text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #8e9bb0;">Name</label>
            <input type="text" name="name" class="form-control" id="customername"
                placeholder="Name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #8e9bb0;">Company</label>
            <input type="text" name="company" value="{{ old('company', $user->company) }}" class="form-control" id="companyname"
                placeholder="Company">
        </div>

        <div class="mb-3">
            <label class="form-label text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #8e9bb0;">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" id="email"
                placeholder="email@metinca.com" required>
        </div>

        <!-- PASSWORD -->
        <div class="mb-3">
            <label class="form-label text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #8e9bb0;">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="custEditPassword_{{ $user->id }}" class="form-control"
                    placeholder="Masukkan password baru (min. 6 karakter)">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('custEditPassword_{{ $user->id }}', this)" title="Lihat/Sembunyikan Password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">
                <i class="bi bi-info-circle me-1"></i>Kosongkan jika tidak ingin mengubah password (isi hanya untuk reset).
            </small>
        </div>

        <!-- KONFIRMASI PASSWORD -->
        <div class="mb-3">
            <label class="form-label text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #8e9bb0;">Konfirmasi Password</label>
            <div class="input-group">
                <input type="password" name="password_confirmation" id="custEditPasswordConfirm_{{ $user->id }}" class="form-control"
                    placeholder="Ulangi password baru">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('custEditPasswordConfirm_{{ $user->id }}', this)" title="Lihat/Sembunyikan Password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #8e9bb0;">Role</label>
            <select class="form-select" name="role" readonly>
                <option selected value="customer">Customer</option>
            </select>
        </div>

        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success px-4 fw-semibold">Save</button>
        </div>
    </form>
</div>

<script>
function toggleCustPass(inputId, iconId) {
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

document.getElementById('formEditCustomer_{{ $user->id }}').addEventListener('submit', function(e) {
    const pass = document.getElementById('custEditPassword_{{ $user->id }}').value;
    const confirm = document.getElementById('custEditPasswordConfirm_{{ $user->id }}').value;
    if (pass) {
        if (pass.length < 6) {
            e.preventDefault();
            alert('Password minimal 6 karakter!');
            return;
        }
        if (pass !== confirm) {
            e.preventDefault();
            alert('Konfirmasi password tidak cocok!');
            return;
        }
    }
});
</script>
