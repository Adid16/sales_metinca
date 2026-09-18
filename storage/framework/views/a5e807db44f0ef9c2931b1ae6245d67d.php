<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('articles.index')); ?>">Articles</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card detail-card">
    <div class="card-header bg-warning text-black py-3">
        <h5 class="card-title mb-0">
            <i class="bi bi-box-seam-fill"></i> Update Detail Product
        </h5>
    </div>

    
    <form action="<?php echo e(route('articles.update', $article->id)); ?>" method="POST" id="articleForm" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card-body">
                    
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="part_number" class="col-sm-4 col-form-label">Internal Part No</label>
                                
                                <input type="text" class="form-control form-control-sm <?php $__errorArgs = ['part_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="part_number" name="part_number"
                                    value="<?php echo e(old('part_number', $article->internal_part_no)); ?>" required>
                                <?php $__errorArgs = ['part_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-md-5 gap-1 d-flex justify-content-end align-items-center">
                            <button type="submit" class="btn btn-warning btn-sm text-black font-weight-bold">
                                <i class="fas fa-save"></i> Update Data
                            </button>
                            <a href="<?php echo e(route('articles.show', $article->id)); ?>" class="btn btn-danger btn-sm">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="article_no" class="col-sm-4 col-form-label">Article</label>
                                <input type="text" class="form-control form-control-sm <?php $__errorArgs = ['article_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="article_no" name="article_no"
                                    value="<?php echo e(old('article_no', $article->article_no)); ?>" required>
                                <?php $__errorArgs = ['article_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="part_name" class="col-sm-4 col-form-label">Part Name</label>
                                <input type="text" class="form-control form-control-sm <?php $__errorArgs = ['part_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="part_name" name="part_name" value="<?php echo e(old('part_name', $article->part_name)); ?>">
                                <?php $__errorArgs = ['part_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="index_no" class="col-sm-4 col-form-label">Index</label>
                                <input type="text" class="form-control form-control-sm" id="index_no" name="index_no" value="<?php echo e(old('index_no', $article->index_no)); ?>">
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="berat" class="col-sm-4 col-form-label">Berat (Kg)</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="berat" name="berat" value="<?php echo e(old('berat', $article->berat)); ?>">
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="customer_id" class="col-sm-4 col-form-label">Customer</label>
                                <select class="form-select form-select-sm <?php $__errorArgs = ['customer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="customer_id" name="customer_id">
                                    <option value="">-- Select Customer --</option>
                                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($customer->id); ?>" <?php echo e(old('customer_id', $article->customer_id) == $customer->id ? 'selected' : ''); ?>>
                                            <?php echo e($customer->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="die_no" class="col-sm-4 col-form-label">Die No</label>
                                <input type="text" class="form-control form-control-sm" id="die_no" name="die_no" value="<?php echo e(old('die_no', $article->die_no)); ?>">
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="material" class="col-sm-4 col-form-label">Material</label>
                                <input type="text" class="form-control form-control-sm" id="material" name="material" value="<?php echo e(old('material', $article->material)); ?>">
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="lokasi_pengerjaan" class="col-sm-4 col-form-label">Lokasi Pengerjaan</label>
                                <input type="number" class="form-control form-control-sm" id="lokasi_pengerjaan" name="lokasi_pengerjaan" value="<?php echo e(old('lokasi_pengerjaan', $article->lokasi_pengerjaan)); ?>">
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="drawing_no" class="col-sm-4 col-form-label">Drawing No</label>
                                <input type="text" class="form-control form-control-sm" id="drawing_no" name="drawing_no" value="<?php echo e(old('drawing_no', $article->drawing_no)); ?>">
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="drawing_rev" class="col-sm-4 col-form-label">Drawing Rev</label>
                                <input type="text" class="form-control form-control-sm" id="drawing_rev" name="drawing_rev" value="<?php echo e(old('drawing_rev', $article->drawing_rev)); ?>">
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="effective_date" class="col-sm-4 col-form-label">Effective Date</label>
                                <input type="date" class="form-control form-control-sm" id="effective_date" name="effective_date" value="<?php echo e(old('effective_date', is_string($article->effective_date) ? $article->effective_date : ($article->effective_date?->format('Y-m-d') ?? ''))); ?>">
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="remark" class="col-sm-4 col-form-label">Remark</label>
                                <textarea class="form-control form-control-sm" id="remark" name="remark" rows="1"><?php echo e(old('remark', $article->remark)); ?></textarea>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-1 d-flex align-items-center">
                                <label for="casting_price" class="form-label col-sm-4 col-form-label">Price Casting <span class="text-danger">*</span></label>
                                <input type="number" id="casting_price" name="casting_price" class="form-control form-control-sm font-weight-bold" value="<?php echo e(old('casting_price', $article->casting_price ?? 0)); ?>" required>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-1 d-flex align-items-center">
                                <label for="machining_price" class="form-label col-sm-4 col-form-label">Price Machining <span class="text-danger">*</span></label>
                                <input type="number" id="machining_price" name="machining_price" class="form-control form-control-sm font-weight-bold" value="<?php echo e(old('machining_price', $article->machining_price ?? 0)); ?>" required>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-2 d-flex align-items-center">
                                <label for="total_price" class="form-label col-sm-4 col-form-label font-weight-bold text-success">Total Price (Auto)</label>
                                <input type="number" id="total_price" name="price" class="form-control form-control-sm bg-light font-weight-bold text-success border-success" value="<?php echo e(old('price', $article->price ?? 0)); ?>" readonly>
                            </div>  
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="pdf_attachment" class="col-sm-4 col-form-label">Update Drawing PDF</label>
                                <input type="file" class="form-control form-control-sm" id="pdf_attachment" name="pdf_attachment" accept=".pdf">
                                <?php if($article->pdf_attachment): ?>
                                    <div class="ms-2 text-nowrap">
                                        <a href="<?php echo e(asset('storage/uploads/pricelist/' . $article->pdf_attachment)); ?>" target="_blank" class="badge bg-danger text-decoration-none"><i class="bi bi-file-pdf"></i> View Current PDF</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const castingInput = document.getElementById('casting_price');
            const machiningInput = document.getElementById('machining_price');
            const totalInput = document.getElementById('total_price');

            // Kalkulasi otomatis kolom total harga
            function calculateTotal() {
                const casting = parseFloat(castingInput.value) || 0;
                const machining = parseFloat(machiningInput.value) || 0;
                totalInput.value = casting + machining;
            }

            castingInput.addEventListener('input', calculateTotal);
            machiningInput.addEventListener('input', calculateTotal);
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/articles/edit.blade.php ENDPATH**/ ?>