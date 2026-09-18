<?php $__env->startSection('title', 'Pusat Notifikasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div class="page-title mb-3">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3 class="fw-bold mb-1"><i class="bi bi-bell-fill text-primary me-2"></i>Pusat Notifikasi</h3>
                <p class="text-subtitle text-muted mb-0">Daftar semua pemberitahuan, aktivitas proyek, dan status persetujuan.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-md-end mb-3 mb-md-0">
                <?php if($unreadCount > 0): ?>
                    <form action="<?php echo e(route('notifikasi.markAllRead')); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-primary btn-sm px-3 shadow-sm">
                            <i class="bi bi-check2-all me-1"></i> Tandai Semua Dibaca (<?php echo e($unreadCount); ?>)
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-transparent border-bottom py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-primary rounded-pill px-3 filter-notif-btn active" data-filter="all">
                    Semua <span class="badge bg-white text-primary rounded-pill ms-1"><?php echo e($notifications->count()); ?></span>
                </button>
                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 filter-notif-btn" data-filter="unread">
                    Belum Dibaca <span class="badge bg-danger text-white rounded-pill ms-1"><?php echo e($unreadCount); ?></span>
                </button>
                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 filter-notif-btn" data-filter="read">
                    Sudah Dibaca <span class="badge bg-secondary text-white rounded-pill ms-1"><?php echo e($notifications->count() - $unreadCount); ?></span>
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <?php if($notifications->isEmpty()): ?>
                <div class="text-center py-5 px-3">
                    <div class="mb-3">
                        <i class="bi bi-bell-slash text-muted" style="font-size: 3.5rem; opacity: 0.4;"></i>
                    </div>
                    <h5 class="fw-bold text-secondary mb-1">Belum Ada Notifikasi</h5>
                    <p class="text-muted small mb-0">Pemberitahuan terkait order, penawaran, dan approval akan muncul di sini.</p>
                </div>
            <?php else: ?>
                <div class="list-group list-group-flush" id="notificationFullList">
                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $msg = $n->data['message'] ?? 'Pemberitahuan baru';
                            $isUnread = is_null($n->read_at);
                            $url = route('notifikasi.read', $n->id);
                            $timeAgo = $n->created_at ? $n->created_at->diffForHumans() : '';
                            $exactTime = $n->created_at ? $n->created_at->format('d M Y, H:i') : '';
                            $docNo = $n->data['order_no'] ?? $n->data['quotation_no'] ?? $n->data['po_no'] ?? null;
                            
                            $lowerMsg = strtolower($msg);
                            $icon = 'bi-bell-fill';
                            $iconClass = 'notif-icon-primary';
                            $categoryBadge = 'Info';
                            $categoryBadgeClass = 'bg-secondary';
                            
                            if (str_contains($lowerMsg, 'approve') || str_contains($lowerMsg, 'disetujui') || str_contains($lowerMsg, 'berhasil')) {
                                $icon = 'bi-check-circle-fill';
                                $iconClass = 'notif-icon-success';
                                $categoryBadge = 'Approved';
                                $categoryBadgeClass = 'bg-success';
                            } elseif (str_contains($lowerMsg, 'tolak') || str_contains($lowerMsg, 'reject') || str_contains($lowerMsg, 'batal') || str_contains($lowerMsg, 'gagal')) {
                                $icon = 'bi-x-circle-fill';
                                $iconClass = 'notif-icon-danger';
                                $categoryBadge = 'Ditolak';
                                $categoryBadgeClass = 'bg-danger';
                            } elseif (str_contains($lowerMsg, 'request') || str_contains($lowerMsg, 'permintaan') || str_contains($lowerMsg, 'ditugaskan')) {
                                $icon = 'bi-inbox-fill';
                                $iconClass = 'notif-icon-purple';
                                $categoryBadge = 'Request';
                                $categoryBadgeClass = 'bg-primary';
                            } elseif (str_contains($lowerMsg, 'quotation') || str_contains($lowerMsg, 'penawaran')) {
                                $icon = 'bi-file-earmark-text-fill';
                                $iconClass = 'notif-icon-primary';
                                $categoryBadge = 'Quotation';
                                $categoryBadgeClass = 'bg-primary';
                            } elseif (str_contains($lowerMsg, 'contract') || str_contains($lowerMsg, 'kontrak')) {
                                $icon = 'bi-file-earmark-check-fill';
                                $iconClass = 'notif-icon-info';
                                $categoryBadge = 'Contract';
                                $categoryBadgeClass = 'bg-info';
                            } elseif (str_contains($lowerMsg, 'po') || str_contains($lowerMsg, 'purchase order') || str_contains($lowerMsg, 'amandemen')) {
                                $icon = 'bi-bag-check-fill';
                                $iconClass = 'notif-icon-warning';
                                $categoryBadge = 'PO';
                                $categoryBadgeClass = 'bg-warning text-dark';
                            }
                        ?>
                        <div class="list-group-item list-group-item-action p-3 px-4 notif-row <?php echo e($isUnread ? 'is-unread bg-light-subtle' : ''); ?>"
                             data-status="<?php echo e($isUnread ? 'unread' : 'read'); ?>"
                             style="<?php echo e($isUnread ? 'border-left: 4px solid #435ebe;' : ''); ?>">
                            <div class="d-flex align-items-center gap-3">
                                <div class="notification-icon-box <?php echo e($iconClass); ?> rounded-3" style="width: 44px; height: 44px; min-width: 44px; font-size: 1.25rem;">
                                    <i class="bi <?php echo e($icon); ?>"></i>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge <?php echo e($categoryBadgeClass); ?> rounded-pill" style="font-size: 0.7rem;">
                                                <?php echo e($categoryBadge); ?>

                                            </span>
                                            <?php if($docNo): ?>
                                                <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.72rem;">
                                                    <?php echo e($docNo); ?>

                                                </span>
                                            <?php endif; ?>
                                            <?php if($isUnread): ?>
                                                <span class="badge bg-primary rounded-pill" style="font-size: 0.65rem;">Baru</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-muted small" title="<?php echo e($exactTime); ?>">
                                            <i class="bi bi-clock me-1"></i><?php echo e($timeAgo); ?> (<?php echo e($exactTime); ?>)
                                        </div>
                                    </div>
                                    <h6 class="mb-2 <?php echo e($isUnread ? 'fw-bold text-dark' : 'text-body-secondary'); ?>" style="font-size: 0.95rem;">
                                        <?php echo e($msg); ?>

                                    </h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="<?php echo e($url); ?>" class="btn btn-sm btn-outline-primary px-3 rounded-pill">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> Buka Dokumen Terkait
                                        </a>
                                        <?php if(!$isUnread): ?>
                                            <span class="text-muted small ms-2"><i class="bi bi-check2 text-success me-1"></i>Sudah dibaca</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div id="noFilteredNotifs" class="text-center py-5 d-none">
                    <i class="bi bi-inbox text-muted fs-1 opacity-50 d-block mb-2"></i>
                    <p class="text-muted mb-0">Tidak ada notifikasi pada kategori ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.querySelectorAll('.filter-notif-btn').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.filter-notif-btn').forEach(btn => {
            btn.classList.remove('btn-primary', 'active');
            btn.classList.add('btn-outline-secondary');
        });
        this.classList.remove('btn-outline-secondary');
        this.classList.add('btn-primary', 'active');

        const filter = this.getAttribute('data-filter');
        const rows = document.querySelectorAll('.notif-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            if (filter === 'all' || status === filter) {
                row.classList.remove('d-none');
                visibleCount++;
            } else {
                row.classList.add('d-none');
            }
        });

        const noData = document.getElementById('noFilteredNotifs');
        if (noData) {
            if (visibleCount === 0) {
                noData.classList.remove('d-none');
            } else {
                noData.classList.add('d-none');
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/notifikasi.blade.php ENDPATH**/ ?>