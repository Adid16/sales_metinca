

<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works'); ?>
 
<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app-dark.css')); ?>">
<?php $__env->stopPush(); ?>
 
<?php $__env->startSection('content'); ?>
 
<div class="card shadow-sm mb-3">
    <div class="card-header d-flex bg-primary justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-white">
            <i class="bi bi-file-earmark-ruled-fill me-2"></i>
            Detail PO Internal — <?php echo e($purchaseOrder->po_no); ?>

        </h5>
    </div>
 
    
    <div class="card-body py-3 px-4">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="border rounded p-3" style="border-left:4px solid #0d6efd !important;">
                    <small class="text-muted d-block">PO No (External)</small>
                    <span class="fw-bold fs-6"><?php echo e($purchaseOrder->po_no ?? '-'); ?></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3" style="border-left:4px solid #fd7e14 !important;">
                    <small class="text-muted d-block">Quotation No</small>
                    <span class="fw-semibold"><?php echo e($purchaseOrder->quotation->quotation_no ?? '-'); ?></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3" style="border-left:4px solid #198754 !important;">
                    <small class="text-muted d-block">Customer</small>
                    <span><?php echo e($purchaseOrder->customer->name ?? '-'); ?></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3" style="border-left:4px solid #dc3545 !important;">
                    <small class="text-muted d-block">Delivery Request</small>
                    <span class="fw-bold text-danger">
                        <?php echo e($purchaseOrder->delivery_request
                            ? \Carbon\Carbon::parse($purchaseOrder->delivery_request)->format('d M Y')
                            : '-'); ?>

                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
 
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-1"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
 

<div class="card shadow-sm">
    <div class="card-header py-2 d-flex justify-content-between align-items-center"
        style="solid #198754; background:#f8f9fa;">
        <h6 class="mb-0 fw-bold text-uppercase" style="font-size:0.78rem; letter-spacing:1px;">
            <i class="bi bi-list-ul me-1"></i>Daftar Item PO Internal
            
        </h6>
        
    </div>
    <div class="card-body p-0">

<div class="accordion" id="itemAccordion">
    <?php $__empty_1 = true; $__currentLoopData = $purchaseOrder->internals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="accordion-item mb-2 border rounded" style="border-left:4px solid #0d6efd !important;">
        <h2 class="accordion-header" id="heading-<?php echo e($i); ?>">
            <button class="accordion-button <?php echo e($i > 0 ? 'collapsed' : ''); ?> fw-semibold" 
                type="button" data-bs-toggle="collapse"
                data-bs-target="#collapse-<?php echo e($i); ?>" 
                aria-expanded="<?php echo e($i == 0 ? 'true' : 'false'); ?>"
                aria-controls="collapse-<?php echo e($i); ?>">
                <span class="badge bg-primary me-2">#<?php echo e($i + 1); ?></span>
                <?php echo e($item->item); ?>

                <span class="ms-3 text-muted small fw-normal"><?php echo e($item->po_no ?? ''); ?></span>
                <span class="ms-auto me-3 fw-bold text-dark">
                    Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?>

                </span>
            </button>
        </h2>
        <div id="collapse-<?php echo e($i); ?>" 
            class="accordion-collapse collapse <?php echo e($i == 0 ? 'show' : ''); ?>"
            aria-labelledby="heading-<?php echo e($i); ?>"
            data-bs-parent="#itemAccordion">
            <div class="accordion-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">No PO</small>
                            <span class="fw-semibold"><?php echo e($item->po_no ?? '-'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Nama Item</small>
                            <span class="fw-semibold"><?php echo e($item->item); ?></span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Material</small>
                            <span><?php echo e($item->material ?? '-'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Spesifikasi</small>
                            <span><?php echo e($item->spesifikasi ?? '-'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Satuan</small>
                            <span><?php echo e($item->satuan ?? '-'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Qty</small>
                            <span class="fw-bold"><?php echo e($item->qty); ?></span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Unit Price</small>
                            <span>Rp <?php echo e(number_format($item->unit_price, 0, ',', '.')); ?></span>
                        </div>
                        <div class="d-flex justify-content-between pb-1">
                            <small class="text-muted">Subtotal</small>
                            <span class="fw-bold text-primary">Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Delivery Date</small>
                            <span><?php echo e($item->delivery_date?->format('d M Y') ?? '-'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Supplier/Vendor</small>
                            <span><?php echo e($item->supplier ?? '-'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">PIC Buyer</small>
                            <span><?php echo e($item->pic_buyer ?? '-'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <small class="text-muted">Perusahaan Buyer</small>
                            <span><?php echo e($item->company_buyer ?? '-'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between pb-1">
                            <small class="text-muted">Notes</small>
                            <span class="fst-italic"><?php echo e($item->notes ?? '-'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="text-center text-muted fst-italic py-3">Belum ada item.</div>
    <?php endif; ?>
</div>


<?php if($purchaseOrder->internals->count() > 0): ?>
<div class="d-flex justify-content-between align-items-center border rounded p-3 mt-3" 
    style="background:#f0f4ff;">
    <span class="fw-bold">TOTAL (<?php echo e($purchaseOrder->internals->count()); ?> item, 
        <?php echo e($purchaseOrder->internals->sum('qty')); ?> qty)</span>
    <span class="fw-bold fs-5 text-primary">
        Rp <?php echo e(number_format($purchaseOrder->internals->sum('subtotal'), 0, ',', '.')); ?>

    </span>
</div>
<?php endif; ?>        <div class="d-flex justify-content-end px-3 mb-3 mt-2 gap-1">
            <?php if(auth()->user()->isAdmin() || (auth()->user()->isStaff() && auth()->user()->divisi == 'sales')): ?>
            <a href="<?php echo e(route('purchase-orders-internal.edit', $purchaseOrder->id)); ?>"
                class="btn btn-sm btn-warning fw-semibold">
                Edit
            </a>
            <?php endif; ?>
            <a href="<?php echo e(route('purchase-orders-internal.index')); ?>" class="btn btn-sm btn-danger">
                Back
            </a>
        </div>
    </div>
</div>
 
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/purchase-orders-internal/show.blade.php ENDPATH**/ ?>