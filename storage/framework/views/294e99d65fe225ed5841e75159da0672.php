<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works'); ?>


<?php $__env->startPush('styles'); ?>
    <link rel="shortcut icon" href="<?php echo e(asset('assets/compiled/svg/favicon.svg')); ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app-dark.css')); ?>">
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
<div class="card detail-card">
    <div class="card-header bg-info text-black py-3">
        <h5 class="card-title mb-0">
            <i class="bi bi-send-plus-fill"></i> Detail Request Project 
        </h5>
    </div>
    
                
                
            

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

    <section class="section">
        
            <div class="row">
                <div class="col-lg-12">
                    
                    
                        
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <h5 class="card-title mb-0">Sender Request</h5>
                                <div class="d-flex gap-1 flex-wrap align-items-end justify-content-end ms-auto">
                                
                                    
                                    
                                
                                
                                   <?php if(auth()->user()->isAdmin() || (auth()->user()->isManager() && auth()->user()->divisi === 'sales') || (auth()->user()->isStaff() && !auth()->user()->isCustomer())): ?>
    
    <?php if(!$requestProject->assignment): ?>
        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Project harus diambil terlebih dahulu dengan mengklik tombol (+) di halaman list!">
            <button class="btn btn-secondary btn-sm" disabled>
                <i class="bi bi-lock-fill"></i> Create Quotation (Belum Di-assign)
            </button>
        </span>
    <?php elseif($requestProject->quotation): ?>
        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Penawaran harga (Quotation #<?php echo e($requestProject->quotation->quotation_no); ?>) sudah pernah dibuat untuk request ini.">
            <button class="btn btn-secondary btn-sm me-1" disabled>
                <i class="bi bi-check-circle-fill text-success me-1"></i> Quotation Sudah Dibuat
            </button>
        </span>
    <?php else: ?>
        <a href="<?php echo e(route('quotations.create', ['request_id' => $requestProject->id])); ?>" class="btn btn-primary btn-sm me-1">
            <i class="bi bi-pencil"></i> Create Quotation
        </a>
    <?php endif; ?>
<?php endif; ?>
                                        
                                        <a href="<?php echo e(route('requests-project.index')); ?>" class="btn btn-sm btn-end btn-secondary">
                                            Back
                                        </a>
                                    

                                

                                <?php if(auth()->user()->isCustomer()): ?>
                                    <button type="button" class="btn btn-danger btn-sm " data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                                <div class="row mb-2">
                                    <div class="col-md-3">Name</div>
                                    <div class="col-md-4"><?php echo e($requestProject->name); ?></div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">Company</div>
                                    <div class="col-md-4">
                                        <?php echo e($requestProject->company); ?>

                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">Email:</div>
                                    <div class="col-md-6">
                                        <a href="mailto:<?php echo e($requestProject->email); ?>">
                                            <i class="bi bi-envelope"></i> <?php echo e($requestProject->email); ?>

                                        </a>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">Phone:</div>
                                    <div class="col-md-6">
                                        <a href="tel:<?php echo e($requestProject->phone); ?>">
                                            <i class="bi bi-telephone"></i> <?php echo e($requestProject->phone); ?>

                                        </a>
                                    </div>
                                </div>
                                
                                <hr class="border-dark opacity-50 mt-0">

                            <h5 class="card-title mb-0">Request Information</h5>
                                 <div class="row mb-2">
                                    <div class="col-md-3">Request ID</div>
                                    <div class="col-md-4"><span class="badge bg-primary">#<?php echo e($requestProject->id); ?></span></div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">Subject</div>
                                    <div class="col-md-4"><strong><?php echo e($requestProject->subject); ?></strong></div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">Message</div>
                                    <div class="col-md-4"><strong><?php echo e($requestProject->message); ?></strong></div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">Attachments</div>
                                        <div class="col-md-9">
                                            <?php if($requestProject->attachments && count($requestProject->attachments) > 0): ?>
                                            <ul class="list-group">
                                            <?php $__currentLoopData = $requestProject->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <a href="<?php echo e(asset('storage/' . $attachment->file_path)); ?>" target="_blank">
                                                        <i class="bi bi-file-earmark-pdf"></i>
                                                        <?php echo e($attachment->document_name ?? ''); ?>

                                                    </a>
                                                    <a href="<?php echo e(asset('storage/' . $attachment->file_path)); ?>" download
                                                        class="btn btn-sm btn-secondary inline-text">
                                                        <i class="bi bi-file-earmark-arrow-down"></i>
                                                    </a>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                        <?php else: ?>
                                            <p>No attachments available.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">Created Date</div>
                                    <div class="col-md-7"><?php echo e($requestProject->created_at->format('d F Y H:i')); ?></div>
                                </div>

                            <div class="row mb-2">
                                <div class="col-md-3">Last Updated</div>
                                <div class="col-md-7"><?php echo e($requestProject->updated_at->format('d F Y H:i')); ?></div>
                            </div>
                                <hr class="border-dark opacity-50 mt-0">

                            <h5 class="card-title mb-0">Receiver Request</h5>
                                 <div class="row mb-2">
                                    <div class="col-md-3">Sales Person</div>
                                    <div class="col-md-4"><?php echo e($requestProject->assignment->sales->name ?? 'N/A'); ?></div>
                                </div>

                                <div class="row mb-2">
                                    
                                </div>
                                
                               
                        </div>
                        
                    

                    
                    

                    

                    
                    

                    
                    
                </div>

                
               
                         

                    
                </div>
            </div>
        
    </section>

    <div class="modal fade" id="modalnewcustomer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="background:lightblue;">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">New user</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('users.customer.request', $requestProject->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="customername">Name</label>
                            <input type="text" name="name" value="<?php echo e(old('name', $requestProject->name)); ?>" class="form-control form-control-sm" id="customername"
                                placeholder="Name">
                        </div>

                        <div class="form-group">
                            <label for="companyname">Company</label>
                            <input type="text" name="company" value="<?php echo e(old('company', $requestProject->company)); ?>" class="form-control form-control-sm" id="customername"
                                placeholder="Company">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" value="<?php echo e(old('email', $requestProject->email)); ?>" class="form-control form-control-sm" id="email"
                                placeholder="Email">
                        </div>

                        

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control form-control-sm" id="password"
                                placeholder="Password">
                        </div>
                        
                        <div class="form-group">
                            <label for="role">Role</label>
                            
                            <fieldset class="form-group">
                                <select class="form-select form-select-sm" id="basicSelect" name="role">
                                    <option selected>Select Role</option>
                                    <option value="customer">Customer</option>
                                </select>
                            </fieldset>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success btn-sm">Save</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Email Modal -->
    <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="emailModalLabel">Send Email</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="#" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="emailRecipient" class="form-label">Recipient Email</label>
                            <input type="email" class="form-control" id="emailRecipient"
                                value="<?php echo e($requestProject->email); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="emailSubject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="emailSubject" name="subject"
                                placeholder="Enter email subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="emailMessage" class="form-label">Message</label>
                            <textarea class="form-control" id="emailMessage" name="message" rows="5" placeholder="Enter your message"
                                required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Send Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this request project? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="#" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/static/js/pages/simple-datatables.js')); ?>"></script>
    <script>
        // Add any custom scripts here
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips if needed
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/requests-project/show.blade.php ENDPATH**/ ?>