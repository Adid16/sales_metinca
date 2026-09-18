<div class="modal-header border-0 pb-0 pt-3 px-4">
    <div class="d-flex align-items-center">
        <i class="bi bi-pencil-square text-primary me-2 fs-5"></i>
        <h5 class="modal-title fw-bold fs-6 mb-0" id="staticBackdropLabel">Edit Pengguna</h5>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body px-4 pt-3 pb-4">
    <form action="{{ route('users.update', $user->id) }}" method="POST" id="formEditUser_{{ $user->id }}">
        @csrf
        @method('PUT')

        <!-- NAMA LENGKAP -->
        <div class="mb-3">
            <label class="form-label text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #8e9bb0;">
                Nama Lengkap
            </label>
            <input type="text" name="name" class="form-control" placeholder="Masukkan nama" value="{{ old('name', $user->name) }}" required>
        </div>

        <!-- EMAIL -->
        <div class="mb-3">
            <label class="form-label text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #8e9bb0;">
                Email
            </label>
            <input type="email" name="email" class="form-control" placeholder="email@metinca.com" value="{{ old('email', $user->email) }}" required>
        </div>

        <!-- ROLE -->
        <div class="mb-3">
            <label class="form-label text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #8e9bb0;">
                Role
            </label>
            <select name="role" id="editRoleSelect_{{ $user->id }}" class="form-select" onchange="handleEditRoleChange('{{ $user->id }}')" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="manager" {{ old('role', $user->role) === 'manager' ? 'selected' : '' }}>Manager</option>
                <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff</option>
            </select>
        </div>

        <!-- DEPARTEMEN -->
        <div class="mb-3" id="editDivisiGroup_{{ $user->id }}">
            <label class="form-label text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #8e9bb0;">
                Departemen
            </label>
            <select name="divisi" id="editDivisiSelect_{{ $user->id }}" class="form-select">
                <option value="">-- Pilih Departemen --</option>
                <option value="sales" {{ old('divisi', $user->divisi) === 'sales' ? 'selected' : '' }}>Sales</option>
                <option value="ppc" {{ old('divisi', $user->divisi) === 'ppc' ? 'selected' : '' }}>PPC</option>
                <option value="quality" {{ old('divisi', $user->divisi) === 'quality' ? 'selected' : '' }}>Quality</option>
                <option value="design engineering" {{ old('divisi', $user->divisi) === 'design engineering' ? 'selected' : '' }}>Design Engineering</option>
            </select>
            <small class="text-muted d-none" id="editDivisiStaffNote_{{ $user->id }}" style="font-size: 0.78rem;">
                <i class="bi bi-info-circle me-1"></i>Role Staff dikhususkan untuk divisi Sales.
            </small>
        </div>

        <!-- PLANT / CABANG -->
        <div class="mb-3">
            <label class="form-label text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #8e9bb0;">
                Plant / Cabang
            </label>
            <select name="plant" class="form-select" required>
                <option value="">-- Pilih Plant / Cabang --</option>
                <option value="Jakarta" {{ old('plant', $user->plant ?? 'Jakarta') === 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                <option value="Salatiga" {{ old('plant', $user->plant) === 'Salatiga' ? 'selected' : '' }}>Salatiga</option>
                <option value="Bekasi" {{ old('plant', $user->plant) === 'Bekasi' ? 'selected' : '' }}>Bekasi</option>
            </select>
        </div>

        <!-- PASSWORD -->
        <div class="mb-3">
            <label class="form-label text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #8e9bb0;">
                Password
            </label>
            <div class="input-group">
                <input type="password" name="password" id="editPasswordInput_{{ $user->id }}" class="form-control" placeholder="Min. 6 karakter (kosongkan jika tidak diubah)">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('editPasswordInput_{{ $user->id }}', this)" title="Lihat/Sembunyikan Password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">
                <i class="bi bi-info-circle me-1"></i>Kosongkan jika tidak ingin mengubah password.
            </small>
        </div>

        <!-- KONFIRMASI PASSWORD -->
        <div class="mb-4">
            <label class="form-label text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #8e9bb0;">
                Konfirmasi Password
            </label>
            <div class="input-group">
                <input type="password" name="password_confirmation" id="editPasswordConfirmInput_{{ $user->id }}" class="form-control" placeholder="Ulangi password baru">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('editPasswordConfirmInput_{{ $user->id }}', this)" title="Lihat/Sembunyikan Password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary px-4 fw-semibold">Simpan</button>
        </div>
    </form>
</div>

<script>
function handleEditRoleChange(userId) {
    const roleSelect = document.getElementById('editRoleSelect_' + userId);
    if (!roleSelect) return;
    const role = roleSelect.value;
    const divisiGroup = document.getElementById('editDivisiGroup_' + userId);
    const divisiSelect = document.getElementById('editDivisiSelect_' + userId);
    const staffNote = document.getElementById('editDivisiStaffNote_' + userId);

    if (role === 'admin') {
        if (divisiGroup) divisiGroup.classList.add('d-none');
        if (divisiSelect) {
            divisiSelect.value = '';
            divisiSelect.removeAttribute('required');
        }
        if (staffNote) staffNote.classList.add('d-none');
    } else if (role === 'staff') {
        if (divisiGroup) divisiGroup.classList.remove('d-none');
        if (divisiSelect) {
            divisiSelect.value = 'sales';
            divisiSelect.setAttribute('required', 'required');
            Array.from(divisiSelect.options).forEach(opt => {
                if (opt.value && opt.value !== 'sales') {
                    opt.disabled = true;
                } else {
                    opt.disabled = false;
                }
            });
        }
        if (staffNote) staffNote.classList.remove('d-none');
    } else if (role === 'manager') {
        if (divisiGroup) divisiGroup.classList.remove('d-none');
        if (divisiSelect) {
            divisiSelect.setAttribute('required', 'required');
            Array.from(divisiSelect.options).forEach(opt => {
                opt.disabled = false;
            });
            if (divisiSelect.value === '') {
                divisiSelect.value = 'sales';
            }
        }
        if (staffNote) staffNote.classList.add('d-none');
    } else {
        if (divisiGroup) divisiGroup.classList.remove('d-none');
        if (divisiSelect) {
            divisiSelect.removeAttribute('required');
            Array.from(divisiSelect.options).forEach(opt => {
                opt.disabled = false;
            });
        }
        if (staffNote) staffNote.classList.add('d-none');
    }
}

// Initial trigger on load
handleEditRoleChange('{{ $user->id }}');

function toggleEditPassword(userId) {
    const input = document.getElementById('editPasswordInput_' + userId);
    const icon = document.getElementById('editEyeIcon_' + userId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}

function toggleEditConfirmPassword(userId) {
    const input = document.getElementById('editPasswordConfirmInput_' + userId);
    const icon = document.getElementById('editConfirmEyeIcon_' + userId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
