



<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works'); ?>


<?php $__env->startPush('styles'); ?>
    
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/dashboard_admin.css')); ?>">
    <style>
        /* CSS tambahan untuk memastikan teks di dalam card menyesuaikan saat mode gelap aktif */
        html[data-bs-theme="dark"] .card h6 {
            color: #ffffff !important;
        }
    </style>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
<div class="mb-3">
    <h3>Dashboard</h3>
</div>
    <div class="page-content">
        <section class="row">
            <div class="col-20 col-lg-20">
                <div class="row">
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-6 col-lg-3 col-md-6">
                        
                        <div class="card shadow-sm">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon <?php echo e($item['color']); ?> mb-2">
                                            <i class="bi <?php echo e($item['icon']); ?>"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="font-bold"><?php echo e($item['label']); ?></h6>
                                        <h6 class="font-extrabold mb-0"><?php echo e($item['count']); ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Activity History</h4>
                            </div>
                            <div class="card-body">
                               <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Activity</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $activity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($item->user->name); ?></td>
                                                <td><?php echo e($item->activity); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($item->activity_time)->format('d M Y, H:i')); ?> WIB</td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </section>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/extensions/apexcharts/apexcharts.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/static/js/pages/dashboard.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/dashboard.blade.php ENDPATH**/ ?>