
<?php $__env->startSection('title'); ?>PT. Metinca Prima Industrial Works 
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow-sm">
    <div class="card-header bg-warning py-3">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-person-circle me-2"></i>Edit My Account
        </h5>
    </div>
    <div class="card-body px-4 py-4">

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-1"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('account.update')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            
            <div class = "page-content">
                <section class = "row">
                    <div class = "col-12">
                        <div class = "d-flex justify-content-between align-items-center">
                            <h5 class="mb-02 fw-bold"> 
                                <i class="bi bi-person me-1"></i>Detail Account
                            </h5>  
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-success btn-sm fw-semibold">
                                    Save
                                </button>
                            </div>                          
                        </div>
                        <div class="border rounded p-3 mt-2" style="border-left: 4px solid #c0392b !important;">
                            <div class = "row g-2">
                                <div class = "col-md-4 pe-4">
                                    <div class = "form-group mb-0 d-flex align-items-center text-black">
                                        <label class="col-sm-3 col-form-label">Name</label>
                                        <input class = "form-control form-control-sm bg-light" value="<?php echo e($user->name); ?>" readonly>
                                    </div>
                                </div>
                                <div class = "col-md-4 ps-4">
                                    <div class = "form-group mb-0 d-flex align-items-center text-black">
                                        <label class="col-sm-3 col-form-label">Email</label>
                                        <input class = "form-control form-control-sm bg-light" value="<?php echo e($user->email); ?>" readonly>
                                    </div>
                                </div>
                                <div class = "col-md-4 ps-4">
                                    <div class = "form-group mb-0 d-flex align-items-center text-black">
                                        <label class="col-sm-3 col-form-label">Position</label>
                                        <input class = "form-control form-control-sm" 
                                            name="position" value="<?php echo e(old('position', $account->position)); ?>" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class = "d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 mt-4 fw-bold"> 
                                <i class="bi bi-building me-1"></i>Company Detail
                            </h5>  
                        </div>
                        <div class="border rounded p-3 mt-2" style="border-left: 4px solid #e67e22 !important;">
                            <div class = "row g-2">
                                <div class = "col-md-6 pe-3">
                                <div class = "form-group mb-0 d-flex align-items-center text-black">
                                    <label class="col-sm-3 col-form-label">Company</label>
                                    <input class = "form-control form-control-sm" 
                                        name="company" value="<?php echo e($account->company ?? '-'); ?>">
                                </div>
                            </div>
                            <div class = "col-md-5 ps-4">
                                <div class = "form-group mb-0 d-flex align-items-center text-black">
                                    <label class="col-sm-3 col-form-label">Phone</label>
                                    <input class = "form-control form-control-sm" 
                                        name="phone" value="<?php echo e($account->phone ?? '-'); ?>">
                                </div>
                            </div>
                            <div class = "col-md-6 pe-3">
                                <div class = "form-group mb-0 d-flex align-items-center text-black">
                                    <label class="col-sm-3 col-form-label">City</label>
                                    <input class = "form-control form-control-sm" 
                                        name="city" value="<?php echo e(old('city', $account->city)); ?>">
                                </div>
                            </div>
                            <div class = "col-md-5 ps-4">
                                <div class = "form-group mb-0 d-flex align-items-center text-black">
                                    <label class="col-sm-3 col-form-label">Fax</label>
                                    <input class = "form-control form-control-sm" 
                                        name="fax" value="<?php echo e(old('fax', $account->fax)); ?>">
                                </div>
                            </div>
                            <div class = "col-md-6 pe-3">
                                <div class = "form-group mb-0 d-flex align-items-center text-black">
                                    <label class="col-sm-3 col-form-label">Address</label>
                                    <textarea class = "form-control form-control-sm" 
                                        name="address" rows="3" ><?php echo e(old('address', $account->address)); ?>

                                    </textarea>
                                </div>
                            </div>
                            <div class = "col-md-5 ps-4">
                                <div class = "form-group mb-0 d-flex align-items-center text-black">
                                    <label class="col-sm-3 col-form-label">ZIP</label>
                                    <input class = "form-control form-control-sm" 
                                        name="zip" value="<?php echo e(old('zip', $account->zip)); ?>">
                                </div>
                            </div> 
                            </div>
                        </div>

                    </div>
                </section>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/account/edit.blade.php ENDPATH**/ ?>