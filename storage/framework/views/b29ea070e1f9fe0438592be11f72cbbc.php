<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works'); ?>


<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/extensions/filepond/filepond.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/extensions/toastify-js/src/toastify.css')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app-dark.css')); ?>">
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
<section id="multiple-column-form">
    <div class="mb-3">
        <h3 class="card-title">Create Amandement PO!</h3>
    </div>
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form action="<?php echo e(route('purchase-orders.store-amandement', $lastPo->id)); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?> 
                            
                            
                            <?php if(isset($selectedItem)): ?>
                                <input type="hidden" name="purchase_order_internal_id" value="<?php echo e($selectedItem->id); ?>">
                            <?php endif; ?>

                            
                            <?php if(isset($selectedItem)): ?>
                                <div class="card border border-primary shadow-sm mb-4 p-3 rounded bg-light-primary">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-box-seam-fill fs-5 text-primary me-2"></i>
                                        <h6 class="text-primary mb-0 fw-bold">Target Amandemen Item:</h6>
                                    </div>
                                    <ul class="mb-0 small" style="list-style-type: none; padding-left: 0;">
                                        <li class="mb-1"><i class="bi bi-dash me-1 text-primary"></i><strong>No. PO External Item:</strong> <span class="badge bg-dark"><?php echo e($itemPoNo); ?></span></li>
                                        <li class="mb-1"><i class="bi bi-dash me-1 text-primary"></i><strong>Nama Barang / Item:</strong> <span class="fw-bold"><?php echo e($selectedItem->item); ?></span></li>
                                        <li class="mb-1"><i class="bi bi-dash me-1 text-primary"></i><strong>Part No:</strong> <code><?php echo e($selectedItem->contract->part_no ?? $selectedItem->part_no ?? '-'); ?></code></li>
                                        <li class="mb-1"><i class="bi bi-dash me-1 text-primary"></i><strong>Jumlah Qty Saat Ini:</strong> <span class="fw-semibold text-primary"><?php echo e(number_format($selectedItem->qty)); ?> pcs</span></li>
                                        <?php if(isset($contract)): ?>
                                            <li><i class="bi bi-dash me-1 text-primary"></i><strong>No. Tinjauan Kontrak Terikat:</strong> <span class="badge bg-primary"><?php echo e($contract->contract_no); ?></span></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <div class="row">
                                
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="form-group">
                                        <label for="quotation_no">Quotation Number</label>
                                        <input type="text" name="quotation_no" id="quotation_no" value="<?php echo e($lastPo->quotation->quotation_no); ?>" readonly class="form-control disabled bg-light">
                                        <input type="hidden" name="quotation_id" value="<?php echo e($lastPo->quotation_id); ?>">
                                    </div>
                                    <?php $__errorArgs = ['quotation_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger small"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="form-group">
                                        <label for="po_no">PO Number (Item External)</label>
                                        <input type="text" id="po_no" name="po_no" value="<?php echo e($itemPoNo); ?>" readonly class="form-control disabled bg-light">
                                    </div>
                                </div>

                                
                                <?php
                                    $maxLimit = \App\Services\SystemSettingService::maxAmendmentLimit();
                                    $currentNo = (int) $nextAmendmentNo;
                                    $quotaExceeded = $currentNo > $maxLimit;
                                ?>
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="form-group">
                                        <label for="next_amandement_no">Amandement No (Sisa Kuota: <?php echo e(max(0, $maxLimit - $currentNo + 1)); ?>/<?php echo e($maxLimit); ?>)</label>
                                        <input type="text" id="next_amandement_no" value="Revisi #<?php echo e($nextAmendmentNo); ?> (Batas SOP: Max <?php echo e($maxLimit); ?>x)" readonly class="form-control disabled <?php echo e($quotaExceeded ? 'bg-danger text-white' : 'bg-light'); ?>">
                                    </div>
                                    <?php if($quotaExceeded): ?>
                                        <small class="text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i> Batas amandemen telah tercapai. Pengajuan ini akan ditolak oleh sistem.</small>
                                    <?php endif; ?>
                                </div>

                                
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="alasan_amandemen" class="form-label font-weight-bold">Alasan / Pesan Perubahan Dokumen <span class="text-danger">*</span></label>
                                        <textarea name="alasan_amandemen" id="alasan_amandemen" rows="3" class="form-control" placeholder="Tuliskan alasan detail amandemen di sini... (Misal: Perubahan kuantitas atau spesifikasi teknis item dari customer)" required></textarea>
                                    </div>
                                    <?php $__errorArgs = ['alasan_amandemen'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger small"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                
                                <div class="col-12 mb-3">
                                    <label class="form-label font-weight-bold">Upload Dokumen Pendukung Amandemen <span class="text-danger">* (Wajib Diunggah)</span></label>
                                    <div class="card border">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <input type="file" name="attachments" class="multiple-files-filepond" required>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Format berkas yang didukung: PDF, JPG, PNG, DOC/DOCX, XLS/XLSX (Maks. 10MB).</small>
                                    <?php $__errorArgs = ['attachments'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger small mt-1 font-weight-bold"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                
                                <div class="col-12 d-flex justify-content-end mt-3">
                                    <a href="<?php echo e(route('purchase-orders.index')); ?>" class="btn btn-secondary me-1 mb-1">Kembali</a>
                                    <button type="submit" class="btn btn-warning me-1 mb-1 font-weight-bold text-white" <?php echo e($quotaExceeded ? 'disabled' : ''); ?>>
                                        <i class="bi bi-send-fill me-1"></i> Kirim Amandemen
                                    </button>
                                    <button type="reset" class="btn btn-light-secondary me-1 mb-1" <?php echo e($quotaExceeded ? 'disabled' : ''); ?>>Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/purchase-orders/create-amandement.blade.php ENDPATH**/ ?>