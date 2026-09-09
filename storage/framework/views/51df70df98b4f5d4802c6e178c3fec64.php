<?php $__env->startSection('title', 'Notifications'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-heading">
        <h3>Notifications</h3>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">All Notifications</h5>
                    <div>
                        <?php if($unreadCount > 0): ?>
                            <form action="<?php echo e(route('notifikasi.markAllRead')); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-sm btn-secondary">Mark all read (<?php echo e($unreadCount); ?>)</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($notifications->isEmpty()): ?>
                    <p class="text-center text-muted">No notifications</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start <?php echo e(is_null($n->read_at) ? 'list-group-item-warning' : ''); ?>">
                                <div>
                                    <a href="<?php echo e(route('notifikasi.read', $n->id)); ?>" class="text-decoration-none">
                                        <div class="fw-bold"><?php echo e($n->data['message']); ?></div>
                                        <div class="text-muted small"><?php echo e($n->created_at->diffForHumans()); ?></div>
                                    </a>
                                </div>
                                <div>
                                    <?php if(is_null($n->read_at)): ?>
                                        <form action="<?php echo e(route('notifikasi.read', $n->id)); ?>" method="GET">
                                            <button class="btn btn-sm btn-primary">Open & Mark read</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="badge bg-success">Read</span>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/notifikasi.blade.php ENDPATH**/ ?>