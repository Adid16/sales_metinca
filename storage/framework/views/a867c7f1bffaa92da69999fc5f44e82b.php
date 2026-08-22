<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo e(asset('assets/extensions/simple-datatables/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/table-datatable.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app-dark.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/statustabel.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card detail-card shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-send-plus-fill me-2"></i>Contract Review Sheet
            </h5>
        </div>

        <div class="page-content">
            <section class="row">
                <div class="col-12">

                    
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show m-3">
                            <i class="bi bi-check-circle me-1"></i><?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card-body py-2">
                        <form class="row g-2 align-items-center mt-0" method="GET" action="<?php echo e(route('contracts.index')); ?>">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-1">
                                    <label class="form-label small mb-0 text-nowrap">From : </label>
                                    <input type="date" name="start_date" class="form-control form-control-sm" value="<?php echo e($filters['start_date'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-1">
                                    <label class="form-label small mb-0 text-nowrap">To : </label>
                                    <input type="date" name="end_date" class="form-control form-control-sm" value="<?php echo e($filters['end_date'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-auto">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">Status</option>
                                    <option value="created" <?php echo e((isset($filters['status']) && $filters['status']=='created') ? 'selected' : ''); ?>>Created</option>
                                    <option value="revision" <?php echo e((isset($filters['status']) && $filters['status']=='revision') ? 'selected' : ''); ?>>Revision</option>
                                    <option value="done" <?php echo e((isset($filters['status']) && $filters['status']=='done') ? 'selected' : ''); ?>>Done</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="dept" class="form-select form-select-sm">
                                    <option value="">Departements</option>
                                    <option value="sales" <?php echo e((isset($filters['dept']) && $filters['dept']=='sales') ? 'selected' : ''); ?>>Sales</option>
                                    <option value="quality" <?php echo e((isset($filters['dept']) && $filters['dept']=='quality') ? 'selected' : ''); ?>>Quality</option>
                                    <option value="ppc" <?php echo e((isset($filters['dept']) && $filters['dept']=='ppc') ? 'selected' : ''); ?>>PPC</option>
                                    <option value="design engineering" <?php echo e((isset($filters['dept']) && $filters['dept']=='design engineering') ? 'selected' : ''); ?>>Development Engineering</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                <button type="submit" formaction="<?php echo e(route('contracts.export')); ?>" class="btn btn-sm btn-success">Export</button>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle table-bordered" id="table1">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="40">No</th>
                                        <th class="text-center">Req Id</th>
                                        <th class="text-center">Quotation No</th>
                                        <th class="text-center">PO No</th>
                                        <th class="text-center">Tinjauan Kontrak</th>
                                        <th class="text-center">Part No</th>
                                        <th class="text-center">Part Name</th>
                                        <th class="text-center">Manager Sales</th>
                                        <th class="text-center">Manager Quality</th>
                                        <th class="text-center">Manager PPC</th>
                                        <th class="text-center">Manager DE</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center" width="120">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                            <td class="text-center">
                                                <span class="badge badge-sm bg-primary"><?php echo e($contract->quotation->request_id ?? $contract->id); ?></span>
                                            </td>
                                            <td><?php echo e($contract->quotation->quotation_no ?? '-'); ?></td>
                                            <td class="fw-semibold"><?php echo e($contract->order_no); ?></td>
                                            <td class="fw-semibold text-secondary"><?php echo e($contract->contract_no); ?></td>

                                            
                                            <td><?php echo e($contract->part_no ?? $contract->internalItem->part_no ?? '-'); ?></td>
                                            <td class="fw-bold"><?php echo e($contract->part_name ?? $contract->internalItem->item ?? '-'); ?></td>

                                            <td class="text-center"><?php echo $contract->sales_approver ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-exclamation-circle-fill text-warning"></i>'; ?></td>
                                            <td class="text-center"><?php echo $contract->quality_approver ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-exclamation-circle-fill text-warning"></i>'; ?></td>
                                            <td class="text-center"><?php echo $contract->ppc_approver ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-exclamation-circle-fill text-warning"></i>'; ?></td>
                                            <td class="text-center"><?php echo $contract->dev_engineering_approver ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-exclamation-circle-fill text-warning"></i>'; ?></td>
                                            
                                            <td class="text-center">
                                                <?php if($contract->status == 'amended'): ?>
                                                    <span class="badge bg-light-danger text-danger fw-bold">Amended</span>
                                                <?php else: ?>
                                                    <span class="badge bg-light-primary text-primary"><?php echo e(ucfirst($contract->status) ?? '-'); ?></span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?php echo e(route('contracts.show', $contract->id)); ?>"
                                                        class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>

                                                    <?php if($contract->status == 'amended'): ?>
                                                        <span class="badge bg-secondary ms-1 d-flex align-items-center" data-bs-toggle="tooltip" title="Kontrak terunci (Amended)">
                                                            <i class="bi bi-lock-fill"></i>
                                                        </span>
                                                    <?php else: ?>
                                                        <?php
                                                            $all4Approved = $contract->sales_approver 
                                                                         && $contract->quality_approver 
                                                                         && $contract->ppc_approver 
                                                                         && $contract->dev_engineering_approver;
                                                        ?>
                                                        <?php if(auth()->user()->isAdmin() || (auth()->user()->isStaff() && auth()->user()->divisi == 'sales')): ?>
                                                            <?php if($all4Approved && !in_array($contract->status, ['production', 'done'])): ?>
                                                                <form action="<?php echo e(route('contracts.finalize', $contract->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('4 Manager telah menyetujui. Memfinalisasi kontrak ini ke tahap In Production (Dalam Produksi)?')">
                                                                    <?php echo csrf_field(); ?>
                                                                    <button type="submit" class="btn btn-sm btn-success text-white fw-bold" data-bs-toggle="tooltip" title="Finalisasi Kontrak ke Tahap In Production (Dalam Produksi)">
                                                                        <i class="bi bi-gear-fill"></i> Finalisasi
                                                                    </button>
                                                                </form>
                                                            <?php endif; ?>
                                                            <a href="<?php echo e(route('contracts.edit', $contract->id)); ?>"
                                                                class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Edit Kontrak">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            <form action="<?php echo e(route('contracts.destroy', $contract->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Contract Review Sheet ini?')">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Hapus Kontrak">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                    <?php endif; ?>

                                                    <?php if( (auth()->user()->role == 'staff' && strtolower(auth()->user()->divisi) == 'sales') || ($contract->sales_approver && $contract->ppc_approver && $contract->quality_approver && $contract->dev_engineering_approver) ): ?>
                                                        <a target="_blank" href="<?php echo e(route('contract.pdf', $contract->id)); ?>" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Download PDF">
                                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="13" class="text-center text-muted fst-italic py-4">
                                                Belum ada data Contract Review Sheet.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        
                        <div class="mt-3">
                            <?php echo e($contracts->links('pagination::bootstrap-5')); ?>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/extensions/simple-datatables/umd/simple-datatables.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/static/js/pages/simple-datatables.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/contracts/index.blade.php ENDPATH**/ ?>