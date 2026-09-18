<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works'); ?>


<?php $__env->startPush('styles'); ?>
    
    
    <link rel="stylesheet" href="<?php echo e(asset('assets/extensions/filepond/filepond.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/extensions/toastify-js/src/toastify.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app-dark.css')); ?>">
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
<div class="card detail-card">
        <div class="card-header py-3 bg-primary text-white">
            <h5 class="card-title mb-0">
                <i class="bi bi-file-earmark-richtext-fill"></i> Create Purchase Order
            </h5>
        </div>
<section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                    <div class="card-content">
                        <div class="card-body">
                            <form action ="<?php echo e(route('purchase-orders.store')); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?> <div class="row">
                                    <input type="hidden" name="quotation_id" id="quotation_id" value="<?php echo e($selectedQuotation->id ?? ''); ?>" readonly>
                                     
                                    <div class="row">
                                        <div class = "col-md-6 d-flex align-items-between">
                                            <div class="col-md-3">Quotation</div>
                                                <div class="col-md-6 mb-2">
                                                    <input type="text" class="form-control form-control-sm bg-light fw-semibold"
                                                        value="<?php echo e($selectedQuotation->quotation_no); ?>" readonly>
                                                </div>
                                            </div>
    
                                            <?php $__errorArgs = ['quotation_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <?php echo e($message); ?>

                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    
                                            <div class = "col-md-6 d-flex align-items-between">
                                                <div class="col-md-4">PO</div>
                                                    <div class="col-md-6 mb-2">
                                                        <input type="text" id="po_no" class="form-control form-control-sm" name="po_no" placeholder="PO Number" required>
                                                    </div>
                                            </div>
                                    </div>
                                
                                    <div class="row">
                                        <div class = "col-md-6 d-flex align-items-between">
                                            <div class="col-md-3">PIC</div>
                                                <div class="col-md-6 mb-2">
                                                    <input type="text" id="pic" class="form-control form-control-sm bg-light fw-semibold" name="pic" value="<?php echo e($selectedQuotation->customer->name ?? ''); ?>" readonly>
                                                </div>
                                        </div>
                                    
                                        <div class="col-md-6 d-flex align-items-between">
                                            <div class="col-md-4">Delivery Request</div>
                                                <div class="col-md-6 mb-2">
                                                    <?php
                                                        // Ambil data negosiasi paling terakhir langsung dari relasi data quotation yang aktif
                                                        $lastNego = isset($selectedQuotation) ? $selectedQuotation->negotiates()->latest()->first() : null;

                                                        // Tentukan tanggal prefill; utamakan dari nego terakhir, jika kosong gunakan data quotation asli
                                                        $prefillDelivery = old('delivery_request',
                                                            $lastNego?->target_delivery_date
                                                                ? \Carbon\Carbon::parse($lastNego->target_delivery_date)->format('Y-m-d')
                                                                : ($selectedQuotation?->target_delivery_date
                                                                    ? \Carbon\Carbon::parse($selectedQuotation->target_delivery_date)->format('Y-m-d')
                                                                    : '')
                                                        );
                                                    ?>
                                                    <input type="date" name="delivery_request" id="deliveryRequest" class="form-control form-control-sm flatpickr-no-config" value="<?php echo e($prefillDelivery); ?>">
                                                </div>
                                        </div> 
                                    </div>    
 
                                    <div class="row">
                                        <div class = "col-md-6 d-flex align-items-between">
                                            <div class="col-md-3">Email</div>
                                                <div class="col-md-6 mb-2">
                                                    <input type="text" id="email" class="form-control form-control-sm bg-light fw-semibold" name="email" value="<?php echo e($selectedQuotation->customer->email ?? ''); ?>" readonly>
                                                </div>
                                        </div>
                                    </div>
                                         
                                        <?php $__errorArgs = ['po_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <?php echo e($message); ?>

                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                             
                                            <div class = "col-md-12">
                                                <input type="file" name="attachments" class="multiple-files-filepond mt-3" multiple>
                                                <small class="mt-0">Upload relevant files (Max: 5MB)</small>
                                            </div>
                                         
                                       <?php $__errorArgs = ['attachments'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <?php echo e($message); ?>

                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                     
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-sm btn-primary me-1 mb-1">Submit</button>
                                        <button type="reset" class="btn btn-sm btn-light-secondary me-1 mb-1">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </section>
<script src="<?php echo e(asset('assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/extensions/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/extensions/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/extensions/filepond-plugin-image-filter/filepond-plugin-image-filter.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/extensions/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/extensions/filepond/filepond.js')); ?>"></script>
<script src="<?php echo e(asset('assets/extensions/toastify-js/src/toastify.js')); ?>"></script>
<script src="<?php echo e(asset('assets/static/js/pages/filepond.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/purchase-orders/create.blade.php ENDPATH**/ ?>