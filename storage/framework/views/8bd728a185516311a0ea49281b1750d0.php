<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card detail-card">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-box-seam-fill"></i> Pricelist Products
            </h5>
        </div>

        <div class="card-body">
            <form method="GET" action="<?php echo e(route('articles.index')); ?>" class="mb-3">
                <div class="row g-2 align-items-end mt-2">
                    <div class="col-md-2">
                        <input type="text" name="part_number" value="<?php echo e(request('part_number')); ?>" class="form-control form-control-sm" placeholder="Part Number">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="article_no" value="<?php echo e(request('article_no')); ?>" class="form-control form-control-sm" placeholder="Article">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="part_name" value="<?php echo e(request('part_name')); ?>" class="form-control form-control-sm" placeholder="Part Name">
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                        <a href="<?php echo e(route('articles.index')); ?>" class="btn btn-danger btn-sm" style="margin-left:5px;">Reset</a>
                        <a href="<?php echo e(route('articles.create')); ?>" class="btn btn-primary btn-sm ms-auto"><i class="fas fa-plus"></i> Add Pricelist</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark text-nowrap">
                        <tr>
                            <th><center>No</center></th>
                            <th><center>Part Number</center></th>
                            <th><center>Article</center></th>
                            <th><center>Part Name</center></th>
                            <th><center>Index</center></th>
                            <th><center>Berat (Kg)</center></th>
                            <th><center>Material</center></th>
                            
                            <th><center>Price</center></th>
                            <th><center>Drawing PDF</center></th>
                            <th><center>Created At</center></th>
                            <th><center>Actions</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><center><?php echo e($articles->firstItem() + $loop->index); ?></center></td>
                                <td><center><strong><?php echo e($article->internal_part_no); ?></strong></center></td>
                                <td><?php echo e($article->article_no); ?></td>
                                <td><?php echo e($article->part_name ?? '-'); ?></td>
                                <td><center><?php echo e($article->index_no ?? '-'); ?></center></td>
                                <td><center><?php echo e($article->berat ? number_format($article->berat, 2) . ' Kg' : '-'); ?></center></td>
                                <td><?php echo e($article->material ?? '-'); ?></td>
                                
                                
                                <td><center><strong>Rp <?php echo e(number_format($article->price, 0, ',', '.')); ?></strong></center></td>

                                <td><center>
                                    <?php if($article->pdf_attachment): ?>
                                        <a href="<?php echo e(asset('storage/uploads/pricelist/' . $article->pdf_attachment)); ?>" target="_blank" class="btn btn-sm btn-outline-danger py-0 px-2 text-nowrap">
                                            <i class="bi bi-file-earmark-pdf-fill"></i> View PDF
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">No File</span>
                                    <?php endif; ?>
                                </center></td>
                                
                                <td class="text-nowrap"><center><?php echo e(\Carbon\Carbon::parse($article->created_at)->format('d/m/Y')); ?></center></td>
                                <td><center>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('articles.show', $article->id)); ?>" class="btn btn-sm btn-info" title="View">
                                            <i class="bi bi-eye text-white"></i>
                                        </a>

                                        <a href="<?php echo e(route('articles.edit', $article->id)); ?>" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil-square text-white"></i>
                                        </a>
                                        
                                        <form action="<?php echo e(route('articles.destroy', $article->id)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this article?')" title="Delete">
                                                <i class="bi bi-trash text-white"></i>
                                            </button>
                                        </form>
                                    </div>
                                </center></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">No articles found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
            
        <?php if($articles->hasPages()): ?>
            <div class="d-flex justify-content-center card-footer bg-transparent border-0">
                <?php echo e($articles->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .btn-group .btn {
            margin-right: 5px;
        }
        .btn-group .btn:last-child {
            margin-right: 0;
        }
    </style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/articles/index.blade.php ENDPATH**/ ?>