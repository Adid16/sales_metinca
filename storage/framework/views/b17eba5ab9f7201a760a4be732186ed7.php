
<?php $__env->startSection('title'); ?> PT. Metinca Prima Industrial Works <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow-sm">
    <div class="card-header bg-primary d-flex justify-content-between align-items-center py-3"
        >
        <h5 class="mb-0 fw-bold text-white">
            <i class="bi bi-person-circle me-2"></i>My Account
        </h5>
    </div>

    <div class="card-body px-4 py-4">

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-1"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(!$account || (!$account->company && !$account->phone && !$account->address)): ?>
            <div class="alert alert-warning d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>
                    Data account Anda belum lengkap.
                    <a href="<?php echo e(route('account.create')); ?>" class="fw-semibold">Lengkapi sekarang →</a>
                </div>
            </div>
        <?php endif; ?>

            
           <div class = "page-content">
                <section class = "row">
                    <div class = "col-12">
                        <div class = "d-flex justify-content-between align-items-center">
                            <h5 class="mb-2 fw-bold"> 
                                <i class="bi bi-person me-1"></i>Detail Account
                            </h5>  
                            <div class="d-flex gap-1">
                                <a href="<?php echo e(route('account.edit')); ?>" class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                            </div>                          
                        </div>
                        <div class="border rounded p-3 mt-2" style="border-left: 4px solid #c0392b !important;">
                            <div class = "row g-2">
                                <div class = "col-md-4 pe-4">
                                    <div class = "form-group mb-0 d-flex align-items-center text-black">
                                        <label class="col-sm-3 col-form-label">Name</label>
                                        <input class = "form-control form-control-sm" value="<?php echo e(Auth::user()->name); ?>" readonly>
                                    </div>
                                </div>
                                <div class = "col-md-4 ps-4">
                                    <div class = "form-group mb-0 d-flex align-items-center text-black">
                                        <label class="col-sm-3 col-form-label">Email</label>
                                        <input class = "form-control form-control-sm" value="<?php echo e(Auth::user()->email); ?>" readonly>
                                    </div>
                                </div>
                                <div class = "col-md-4 ps-4">
                                    <div class = "form-group mb-0 d-flex align-items-center text-black">
                                        <label class="col-sm-3 col-form-label">Position</label>
                                        <input class = "form-control form-control-sm" value="<?php echo e($account->position ?? '-'); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            
           <div class = "page-content">
                <section class = "row">
                    <div class = "col-12">
                        <div class = "d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 mt-4 fw-bold"> 
                                <i class="bi bi-building me-1"></i>Company Detail
                            </h5>  
                        </div>
                            <div class="border rounded p-3 mt-2" style="border-left: 4px solid #e67e22 !important;">
                                <div class = "row g-1">
                                    <div class = "col-md-6 pe-3">
                                        <div class = "form-group mb-0 d-flex align-items-center text-black">
                                            <label class="col-sm-3 col-form-label">Company</label>
                                            <input class = "form-control form-control-sm" value="<?php echo e($account->company ?? '-'); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class = "col-md-5 ps-4">
                                        <div class = "form-group mb-0 d-flex align-items-center text-black">
                                            <label class="col-sm-3 col-form-label">Phone</label>
                                            <input class = "form-control form-control-sm" value="<?php echo e($account->phone ?? '-'); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class = "col-md-6 pe-3">
                                        <div class = "form-group mb-0 d-flex align-items-center text-black">
                                            <label class="col-sm-3 col-form-label">City</label>
                                            <input class = "form-control form-control-sm" value="<?php echo e($account->city ?? '-'); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class = "col-md-5 ps-4">
                                        <div class = "form-group mb-0 d-flex align-items-center text-black">
                                            <label class="col-sm-3 col-form-label">Fax</label>
                                            <input class = "form-control form-control-sm" value="<?php echo e($account->fax ?? '-'); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class = "col-md-6 pe-3">
                                        <div class = "form-group mb-0 d-flex align-items-center text-black">
                                            <label class="col-sm-3 col-form-label">Address</label>
                                            <textarea class = "form-control form-control-sm" rows="3" readonly><?php echo e($account->address ?? '-'); ?></textarea>
                                        </div>
                                    </div>
                                    <div class = "col-md-5 ps-4">
                                        <div class = "form-group mb-0 d-flex align-items-center text-black">
                                            <label class="col-sm-3 col-form-label">ZIP</label>
                                            <input class = "form-control form-control-sm" value="<?php echo e($account->zip ?? '-'); ?>" readonly>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                    </div>
                </section>
            </div>
            <div class="mt-4 pt-3 border-top text-muted small">
                <i class="bi bi-clock me-1"></i>
                Terakhir diupdate: <?php echo e($account?->updated_at ? \Carbon\Carbon::parse($account->updated_at)->format('d F Y, H:i') : '-'); ?>

            </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/account/show.blade.php ENDPATH**/ ?>