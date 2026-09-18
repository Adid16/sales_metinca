<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works'); ?>


<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/extensions/simple-datatables/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/table-datatable.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/statustabel.css')); ?>">
<?php $__env->stopPush(); ?>



<?php $__env->startSection('content'); ?>
<div class="card detail-card">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-send-plus-fill"></i> Request Projects
            </h5>
        </div>

        <section class="section">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
            <div class="card-body py-0">
                <form class="mb-3" method="GET" action="<?php echo e(route('requests-project.index')); ?>">
                    <div class="row g-2 align-items-end mt-0">
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="d-flex align-items-center gap-1">
                                <label class="form-label small mb-0 text-nowrap">From : </label>
                                <input type="date" name="start_date" class="form-control form-control-sm" value="<?php echo e($filters['start_date'] ?? ''); ?>" placeholder="From">
                            </div>
                        </div>
                        
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="d-flex align-items-center gap-1">
                                <label class="form-label small mb-0 text-nowrap">To : </label>
                                <input type="date" name="end_date" class="form-control form-control-sm" value="<?php echo e($filters['end_date'] ?? ''); ?>" placeholder="To">
                            </div>
                        </div>
                        
                        <?php if(auth()->user()->isAdmin() || auth()->user()->isManager() || (auth()->user()->role == 'staff' && auth()->user()->divisi == 'sales') || auth()->user()->isCustomer()): ?>
                        <div class="col-12 col-sm-6 col-lg-2">
                            <div class="d-flex align-items-center gap-1">
                                <select name="sales_id" class="form-select form-select-sm">
                                    <option value="">All Sales</option>
                                    <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($s->id); ?>" <?php echo e((isset($filters['sales_id']) && $filters['sales_id']==$s->id) ? 'selected' : ''); ?>><?php echo e($s->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>
            
                        <div class="col-12 col-sm-6 col-lg-4 d-flex flex-wrap gap-1">
                            <?php if(auth()->user()->isAdmin() || auth()->user()->isManager() || (auth()->user()->role == 'staff' && auth()->user()->divisi == 'sales') || auth()->user()->isCustomer()): ?>
                                <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
                                <a href="<?php echo e(route('requests-project.index')); ?>" class="btn btn-sm btn-danger">Clear</a>
                                <button type="submit" formaction="<?php echo e(route('requests-project.export')); ?>" class="btn btn-success btn-sm btn-end text-end">Export</button>
                            <?php endif; ?>
                            <?php if(auth()->user()->isCustomer() || auth()->user()->isAdmin()): ?>
                                <a href="<?php echo e(route('requests-project.create')); ?>" class="btn btn-primary btn-sm"> New </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover text-nowrap" id="table1">
                    <thead>
                        <tr>
                            <th><center>No</center></th>
                            <th><center>Req Id</center></th>
                            <th><center>Name</center></th>
                            <th><center>Company</center></th>
                            <th><center>Email</center></th>
                            <th><center>Subject</center></th>
                            <th><center>Request Date</center></th>
                            <th><center>Sales PIC</center></th>
                            <th><center>Action</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><center><?php echo e($loop->iteration); ?></center></td>
                                <td><center><span class="badge badge-sm bg-primary"><?php echo e($project->id); ?></span></center></td>
                                <td><center><?php echo e($project->name ?: ($project->customer->name ?? '-')); ?></center></td>
                                <td><center><?php echo e($project->company ?: ($project->customer->account->company ?? ($project->customer->company ?? '-'))); ?></center></td>
                                <td><center><?php echo e($project->email); ?></center></td>
                                <td><center><?php echo e($project->subject); ?></center></td>
                                <td><center><?php echo e($project->created_at->format('d F Y ')); ?></center></td>
                                <td><center>
                                    
                                    <?php if($project->assignment): ?>
                                        <span class="badge bg-success" title="Sudah diklaim oleh <?php echo e($project->assignment->sales->name ?? 'Sales'); ?>">
                                            <i class="bi bi-person-check-fill me-1"></i><?php echo e($project->assignment->sales->name ?? 'Assigned'); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">
                                            <i class="bi bi-exclamation-circle-fill me-1"></i>Unassigned
                                        </span>
                                    <?php endif; ?>
                                </center></td>
                                <td><center>
                                    <a href="<?php echo e(route('requests-project.show', $project->id)); ?>" class="btn btn-sm btn-info" title="Lihat Detail Request">         
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <?php if(auth()->user()->isAdmin()): ?>
                                        <button type="button" class="btn btn-sm btn-primary"
                                            onclick="openAssignModal('<?php echo e(route('requests-project.assign', $project->id)); ?>', '<?php echo e($project->id); ?>', '<?php echo e($project->assignment->sales_id ?? ''); ?>', '<?php echo e(addslashes($project->subject ?? '')); ?>')"
                                            title="<?php echo e($project->assignment ? 'Ganti Sales PIC' : 'Tugaskan Sales PIC'); ?>">
                                            <i class="bi <?php echo e($project->assignment ? 'bi-person-gear' : 'bi-person-plus-fill'); ?>"></i>
                                        </button>
                                    <?php elseif(auth()->user()->role == 'staff' && auth()->user()->divisi == 'sales' && !$project->assignment): ?>
                                        <form action="<?php echo e(route('requests-project.assign', $project->id)); ?>" method="POST" class="d-inline">                                        
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-primary"
                                                onclick="return confirm('Ambil request project ini?')"
                                                title="Ambil / Klaim Request Project">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?> 
                                </center></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center">No data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        
    </section>

    <?php if(auth()->user()->isAdmin()): ?>
        <!-- Modal Penugasan Sales PIC (Super-Admin) -->
        <div class="modal fade" id="modalAssignSales" tabindex="-1" aria-labelledby="modalAssignSalesLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fs-6" id="modalAssignSalesLabel">
                            <i class="bi bi-person-check-fill me-1"></i> Penugasan Sales PIC
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formAssignSalesModal" method="POST" action="">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Request Project</label>
                                <input type="text" id="assignModalProjectInfo" class="form-control bg-light" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Pilih Sales PIC</label>
                                <select name="sales_id" id="assignModalSalesSelect" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih Karyawan Sales --</option>
                                    <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?> (<?php echo e($s->email); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary btn-sm px-3 fw-semibold">
                                <i class="bi bi-check-lg me-1"></i> Simpan Penugasan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php $__env->startPush('scripts'); ?>
        <script src="<?php echo e(asset('assets/extensions/simple-datatables/umd/simple-datatables.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/static/js/pages/simple-datatables.js')); ?>"></script>
        <script>
            function openAssignModal(actionUrl, projectId, currentSalesId, subject) {
                const form = document.getElementById('formAssignSalesModal');
                const info = document.getElementById('assignModalProjectInfo');
                const select = document.getElementById('assignModalSalesSelect');
                
                if (form) form.action = actionUrl;
                if (info) info.value = '#' + projectId + ' - ' + subject;
                if (select) {
                    if (currentSalesId) {
                        select.value = currentSalesId;
                    } else {
                        select.selectedIndex = 0;
                    }
                }
                
                const modalEl = document.getElementById('modalAssignSales');
                if (modalEl) {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
            }
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/requests-project/index.blade.php ENDPATH**/ ?>