<?php $__env->startSection('title', 'Edit My Account - PT. Metinca Prima Industrial Works'); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-header bg-primary py-3 px-4 rounded-top-3 text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-white d-flex align-items-center gap-2">
            <i class="bi bi-person-gear fs-5"></i> Edit Akun & Pengaturan Profil
        </h5>
        <a href="<?php echo e(route('account.show')); ?>" class="btn btn-sm btn-light-secondary text-white border-0">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
    
    <div class="card-body px-4 py-4">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-1"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <h6 class="alert-heading fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Terdapat Kesalahan:</h6>
                <ul class="mb-0 ps-3">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('account.update')); ?>" method="POST" id="formUpdateAccount">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

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
                        <input class="form-control form-control-sm bg-light" value="<?php echo e($user->name); ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">ALAMAT EMAIL</label>
                        <input class="form-control form-control-sm bg-light" value="<?php echo e($user->email); ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">JABATAN / POSISI</label>
                        <input class="form-control form-control-sm" name="position" value="<?php echo e(old('position', $account->position)); ?>" placeholder="Contoh: Purchasing Manager / Staff">
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
                        <input class="form-control form-control-sm" name="company" value="<?php echo e(old('company', $account->company ?? $user->company)); ?>" placeholder="PT. Nama Perusahaan">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">NOMOR TELEPON / WA</label>
                        <input class="form-control form-control-sm" name="phone" value="<?php echo e(old('phone', $account->phone)); ?>" placeholder="+62 xxx xxxx xxxx">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">KOTA</label>
                        <input class="form-control form-control-sm" name="city" value="<?php echo e(old('city', $account->city)); ?>" placeholder="Contoh: Jakarta / Bekasi">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">NOMOR FAX</label>
                        <input class="form-control form-control-sm" name="fax" value="<?php echo e(old('fax', $account->fax)); ?>" placeholder="Nomor Fax (opsional)">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small fw-bold text-muted">ALAMAT LENGKAP PABRIK / KANTOR</label>
                        <textarea class="form-control form-control-sm" name="address" rows="3" placeholder="Alamat lengkap..."><?php echo e(old('address', $account->address)); ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">KODE POS (ZIP)</label>
                        <input class="form-control form-control-sm" name="zip" value="<?php echo e(old('zip', $account->zip)); ?>" placeholder="Kode Pos">
                    </div>
                </div>
            </div>

            
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
                            <input type="password" name="current_password" id="currentPasswordInput" class="form-control form-control-sm <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Masukkan password saat ini">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('currentPasswordInput', this)" title="Lihat/Sembunyikan Password">
                                <i class="bi bi-eye" id="curPassIcon"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <small class="text-danger d-block mt-1"><?php echo e($message); ?></small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">PASSWORD BARU</label>
                        <div class="input-group input-group-sm">
                            <input type="password" name="new_password" id="newPasswordInput" class="form-control form-control-sm <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Min. 6 karakter">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('newPasswordInput', this)" title="Lihat/Sembunyikan Password">
                                <i class="bi bi-eye" id="newPassIcon"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <small class="text-danger d-block mt-1"><?php echo e($message); ?></small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                <a href="<?php echo e(route('account.show')); ?>" class="btn btn-secondary px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-4 fw-semibold">
                    <i class="bi bi-check-lg me-1"></i> Simpan Semua Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/account/edit.blade.php ENDPATH**/ ?>