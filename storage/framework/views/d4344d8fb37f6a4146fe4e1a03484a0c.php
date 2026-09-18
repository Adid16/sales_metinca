



<?php $__env->startSection('title', 'Dashboard - PT. Metinca Prima Industrial Works'); ?>


<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/dashboard_admin.css')); ?>">
    <style>
        .dashboard-metric-card {
            transition: all 0.25s ease-in-out;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.06);
            background: #ffffff;
            cursor: pointer;
        }
        .dashboard-metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
            border-color: rgba(67, 94, 190, 0.4) !important;
        }
        .dashboard-metric-card .chevron-icon {
            transition: transform 0.2s ease;
            opacity: 0.4;
        }
        .dashboard-metric-card:hover .chevron-icon {
            transform: translateX(4px);
            opacity: 1;
            color: #435ebe;
        }
        html[data-bs-theme="dark"] .dashboard-metric-card {
            background: #1e1e2d !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        html[data-bs-theme="dark"] .dashboard-metric-card:hover {
            border-color: rgba(67, 94, 190, 0.8) !important;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3) !important;
        }
        html[data-bs-theme="dark"] .dashboard-metric-card h4,
        html[data-bs-theme="dark"] .dashboard-metric-card h6 {
            color: #f1f1f1 !important;
        }
    </style>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
<div class="page-heading mb-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3 class="mb-1 text-dark fw-bold">Dashboard Operasional</h3>
            <p class="text-subtitle text-muted mb-0">Ringkasan transaksi dan status alur kerja PT. Metinca Prima Industrial Works.</p>
        </div>
        <div>
            <span class="badge bg-light text-dark border px-3 py-2">
                <i class="bi bi-clock-history me-1 text-primary"></i> <?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?>

            </span>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            
            <div class="row g-3 mb-4">
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12 col-sm-6 col-xl-4">
                    <a href="<?php echo e($item['url'] ?? '#'); ?>" class="text-decoration-none text-reset">
                        <div class="card dashboard-metric-card shadow-sm h-100 mb-0">
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="stats-icon <?php echo e($item['color'] ?? 'blue'); ?> m-0">
                                            <i class="bi <?php echo e($item['icon'] ?? 'bi-grid'); ?>"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted font-semibold mb-0" style="font-size: 0.9rem;"><?php echo e($item['label']); ?></h6>
                                            <h3 class="font-extrabold mb-0 mt-1 text-dark"><?php echo e(number_format($item['count'])); ?></h3>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right chevron-icon fs-5"></i>
                                </div>
                                <?php if(!empty($item['subtext'])): ?>
                                    <div class="pt-2 border-top">
                                        <small class="text-muted d-flex align-items-center" style="font-size: 0.78rem;">
                                            <i class="bi bi-arrow-right-circle me-1 text-primary"></i> <?php echo e($item['subtext']); ?>

                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex align-items-center">
                                <div class="icon-box-circle bg-primary-subtle text-primary rounded-circle me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-clock-history fs-5"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0 text-dark fw-bold">Riwayat Aktivitas Terkini (Audit Trail)</h5>
                                    <small class="text-muted">Pemantauan rekam jejak aksi dan transaksi sistem secara real-time</small>
                                </div>
                            </div>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 small fw-semibold d-inline-flex align-items-center">
                                <i class="bi bi-list-check me-1"></i>15 Aktivitas Terbaru
                            </span>
                        </div>

                        
                        <div class="px-4 py-2 bg-light border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center flex-wrap gap-3 small">
                                <span class="text-secondary fw-bold text-uppercase d-flex align-items-center" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                                    <i class="bi bi-palette-fill me-1.5 text-primary"></i>Keterangan Garis Samping (Sifat Aksi):
                                </span>
                                <div class="d-flex align-items-center gap-1.5">
                                    <span class="d-inline-block rounded-pill bg-primary" style="width: 14px; height: 6px;"></span>
                                    <span class="text-secondary" style="font-size: 0.8rem;"><strong class="text-dark">Biru:</strong> Input / Pembaruan Data</span>
                                </div>
                                <div class="d-flex align-items-center gap-1.5">
                                    <span class="d-inline-block rounded-pill bg-success" style="width: 14px; height: 6px;"></span>
                                    <span class="text-secondary" style="font-size: 0.8rem;"><strong class="text-dark">Hijau:</strong> Disetujui / Masuk Produksi</span>
                                </div>
                                <div class="d-flex align-items-center gap-1.5">
                                    <span class="d-inline-block rounded-pill bg-danger" style="width: 14px; height: 6px;"></span>
                                    <span class="text-secondary" style="font-size: 0.8rem;"><strong class="text-dark">Merah:</strong> Ditolak / Revisi / Hapus</span>
                                </div>
                                <div class="d-flex align-items-center gap-1.5">
                                    <span class="d-inline-block rounded-pill bg-warning" style="width: 14px; height: 6px;"></span>
                                    <span class="text-secondary" style="font-size: 0.8rem;"><strong class="text-dark">Kuning:</strong> Proses Negosiasi</span>
                                </div>
                                <div class="d-flex align-items-center gap-1.5">
                                    <span class="d-inline-block rounded-pill bg-info" style="width: 14px; height: 6px;"></span>
                                    <span class="text-secondary" style="font-size: 0.8rem;"><strong class="text-dark">Cyan:</strong> Pengiriman Notifikasi</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr class="text-secondary small fw-bold">
                                            <th width="22%" class="ps-4 py-3">Pengguna & Peran</th>
                                            <th width="20%" class="py-3">Modul & Aksi</th>
                                            <th width="40%" class="py-3">Rincian Aktivitas</th>
                                            <th width="18%" class="text-end pe-4 py-3">Waktu Eksekusi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $activity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr class="<?php echo e($item->action_info['border']); ?>">
                                                <td class="ps-4 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar bg-light border text-primary rounded-circle p-1 me-2 d-flex align-items-center justify-content-center fw-bold" style="width: 34px; height: 34px; font-size: 13px;">
                                                            <?php echo e(strtoupper(substr($item->user->name ?? 'S', 0, 2))); ?>

                                                        </div>
                                                        <div>
                                                            <div class="text-dark fw-bold small"><?php echo e($item->user->name ?? 'Sistem'); ?></div>
                                                            <div class="mt-0.5"><?php echo $item->user_badge_html; ?></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="d-flex flex-wrap gap-1 align-items-center">
                                                        <span class="badge <?php echo e($item->module_info['class']); ?> small">
                                                            <i class="<?php echo e($item->module_info['icon']); ?> me-1"></i><?php echo e($item->module_info['name']); ?>

                                                        </span>
                                                        <span class="badge <?php echo e($item->action_info['badge_class']); ?> small">
                                                            <i class="<?php echo e($item->action_info['icon']); ?> me-1"></i><?php echo e($item->action_info['label']); ?>

                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="text-dark small leading-relaxed">
                                                        <?php echo $item->formatted_html; ?>

                                                    </div>
                                                </td>
                                                <td class="text-end pe-4 py-3">
                                                    <div class="text-dark fw-semibold small">
                                                        <i class="bi bi-clock me-1 text-muted"></i><?php echo e(\Carbon\Carbon::parse($item->activity_time)->translatedFormat('d M Y, H:i')); ?> WIB
                                                    </div>
                                                    <span class="badge bg-light text-secondary border extra-small mt-1">
                                                        <?php echo e($item->time_ago); ?>

                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="4" class="text-center py-5 text-muted">
                                                    <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                                                    Belum ada rekam riwayat aktivitas sistem.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/dashboard.blade.php ENDPATH**/ ?>