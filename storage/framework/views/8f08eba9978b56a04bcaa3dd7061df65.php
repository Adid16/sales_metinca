 

<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works'); ?>
 

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app-dark.css')); ?>">
    <style>
        .timeline { position: relative; padding: 20px 0; }
        .timeline::before {
            content: '';
            position: absolute;
            top: 0; bottom: 0;
            left: 20px;
            width: 2px;
            background: #e0e6ed;
        }
        .timeline-item { position: relative; margin-bottom: 24px; padding-left: 50px; }
        .timeline-badge {
            position: absolute;
            left: 10px; top: 0;
            width: 22px; height: 22px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px;
        }
        .timeline-badge.customer { background: #7c4dff; color: #fff; }
        .timeline-badge.pt { background: #00bcd4; color: #fff; }
        .badge-status-waiting { background: #ff9800; color: #fff; font-size: 11px; padding: 3px 8px; border-radius: 12px; }
        .badge-status-accepted { background: #4caf50; color: #fff; font-size: 11px; padding: 3px 8px; border-radius: 12px; }
        .badge-status-rejected { background: #f44336; color: #fff; font-size: 11px; padding: 3px 8px; border-radius: 12px; }
        .badge-status-closed { background: #9e9e9e; color: #fff; font-size: 11px; padding: 3px 8px; border-radius: 12px; }
        .badge-status-negotiate { background: #2196f3; color: #fff; font-size: 11px; padding: 3px 8px; border-radius: 12px; }
        .item-table th {
            background: #f8f9fa;
            font-size: 12px;
            font-weight: 600;
            color: #555;
            border-bottom: 2px solid #e0e6ed;
        }
        .item-table td { vertical-align: middle; font-size: 13px; }
        .orig-price { font-size: 11px; color: #bbb; text-decoration: line-through; display: block; }
        .summary-item { display: flex; justify-content: space-between; font-size: 13px; padding: 5px 0; }
        .summary-item .lbl { color: #888; }
        .history-item { border-left: 3px solid #e0e6ed; padding-left: 12px; margin-bottom: 12px; }
        .history-item.from-customer { border-left-color: #7c4dff; }
        .history-item.from-pt { border-left-color: #00bcd4; }
        .history-meta { font-size: 11px; color: #bbb; margin-bottom: 3px; }
        .history-content { font-size: 13px; color: #555; }

        /* Dark Mode Overrides */
        html[data-bs-theme="dark"] .item-table th {
            background: #222438 !important;
            color: #e2e8f0 !important;
            border-bottom: 2px solid #3d425c !important;
        }
        html[data-bs-theme="dark"] .timeline::before {
            background: #363954 !important;
        }
        html[data-bs-theme="dark"] .history-item {
            border-left-color: #363954 !important;
        }
        html[data-bs-theme="dark"] .history-content {
            color: #cbd5e1 !important;
        }
    </style>
<?php $__env->stopPush(); ?>
 
<?php $__env->startSection('content'); ?>
<?php
    $lastNegotiation = $negotiations->first();
    $isInternalSales = auth()->check() && (auth()->user()->isAdmin() || auth()->user()->divisi === 'sales');
    $isManagerSales  = auth()->check() && (auth()->user()->isAdmin() || (auth()->user()->isManager() && auth()->user()->divisi === 'sales'));
?>
 
<div class="card shadow-sm">
 
    
    <div class="card-header d-flex justify-content-between bg-info align-items-center py-3">
        <h5 class="mb-0 text-black fw-bold">
            <i class="bi bi-file-earmark-text-fill me-2"></i>Quotation Detail
        </h5>
    </div>
 
    <div class="card-body px-4 py-4">
 
        
        <?php
            $currentNegoCount = \App\Models\Negotiate::where('quotation_id', $quotation->id)->where('action', 'negotiate')->count();
            $effectiveLimit = \App\Services\SystemSettingService::effectiveNegotiationLimit($quotation);
            $quotaExceeded = $currentNegoCount >= $effectiveLimit;
            $maxRounds = floor($effectiveLimit / 2);
        ?>

        <div class="alert alert-permanent <?php echo e($quotaExceeded ? 'alert-danger' : 'alert-info'); ?> d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 shadow-sm" style="border-left: 4px solid <?php echo e($quotaExceeded ? '#dc3545' : '#0dcaf0'); ?>;">
            <div>
                <div class="d-flex align-items-center flex-wrap gap-1">
                    <i class="bi <?php echo e($quotaExceeded ? 'bi-exclamation-octagon-fill text-danger' : 'bi-info-circle-fill text-info'); ?> me-2 fs-5"></i>
                    <span class="fw-bold" style="font-size: 0.92rem;">
                        Batas Negosiasi Harga: Counter Negosiasi Ke-<strong><?php echo e($currentNegoCount); ?></strong> dari Maksimal <strong><?php echo e($effectiveLimit); ?>x</strong>
                        <span class="text-muted fw-normal">(<?php echo e($maxRounds); ?>x Saling Balas)</span>
                    </span>
                    <?php if($quotation->negotiation_override_quota > 0): ?>
                        <span class="badge bg-warning text-dark ms-2"><i class="bi bi-shield-check me-1"></i>Termasuk Override +<?php echo e($quotation->negotiation_override_quota); ?>x</span>
                    <?php endif; ?>
                </div>
                <small class="d-block text-muted mt-1 ms-4 ps-1">
                    <?php if($quotaExceeded): ?>
                        <span class="text-danger fw-semibold"><i class="bi bi-lock-fill me-1"></i>Kuota negosiasi telah habis (<?php echo e($currentNegoCount); ?>/<?php echo e($effectiveLimit); ?>x). Silakan sepakati harga penawaran terakhir atau hubungi Manager Sales.</span>
                    <?php else: ?>
                        <span>Sisa kuota: <strong><?php echo e($effectiveLimit - $currentNegoCount); ?>x</strong> kesempatan pengajuan penawaran. (1x Saling Balas = 1 Customer + 1 Sales).</span>
                    <?php endif; ?>
                </small>
            </div>
            
            
            <?php if($isManagerSales): ?>
                <button type="button" class="btn btn-sm btn-warning text-dark fw-bold ms-auto text-nowrap shadow-sm" data-bs-toggle="modal" data-bs-target="#overrideModalShow">
                    <i class="bi bi-plus-circle-fill me-1"></i> Manager Override (+Kuota)
                </button>
            <?php endif; ?>
        </div>

        
        <?php if($isManagerSales): ?>
        <div class="modal fade" id="overrideModalShow" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title fw-bold"><i class="bi bi-shield-lock-fill me-2"></i>Manager Override Kuota Negosiasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?php echo e(route('quotations.override-nego-limit', $quotation->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <p class="small text-muted mb-2">Gunakan fitur ini untuk menambah kuota negosiasi harga bagi customer pada Quotation <strong>#<?php echo e($quotation->quotation_no); ?></strong>.</p>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jumlah Kuota Tambahan:</label>
                                <select name="additional_quota" class="form-select form-select-sm" required>
                                    <option value="1">+1 Kali Negosiasi Tambahan</option>
                                    <option value="2">+2 Kali Negosiasi Tambahan (1 Putaran Saling Balas)</option>
                                    <option value="4">+4 Kali Negosiasi Tambahan (2 Putaran Saling Balas)</option>
                                    <option value="6">+6 Kali Negosiasi Tambahan (3 Putaran Saling Balas)</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-warning fw-bold text-dark"><i class="bi bi-check-lg me-1"></i> Tambahkan Kuota</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>

        
        <?php if(in_array($quotation->status, ['accepted', 'po', 'rejected'])): ?>
            <?php
                $closedNego = $negotiations->whereIn('action', ['closed', 'accept'])->first();
            ?>
            <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-3 shadow-sm" style="background: linear-gradient(135deg, #d4edda, #c3e6cb); border: 1.5px solid #28a745; border-radius: 10px; padding: 16px;">
                <div class="d-flex align-items-center gap-3">
                    <div style="font-size: 32px; line-height:1;">
                        <i class="bi bi-patch-check-fill text-success"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-success" style="font-size:15px;">
                            Negosiasi Telah Disetujui oleh PT. Metinca Prima
                        </div>
                        <div class="text-muted" style="font-size:12px;">
                            Harga yang telah disepakati berlaku sebagai harga final. Negosiasi tidak dapat dilanjutkan.
                        </div>
                        
                        <?php if($quotation->accepted_date): ?>
                            <div class="mt-1" style="font-size:11px; color:#555;">
                                <i class="bi bi-clock me-1"></i> Disetujui pada:
                                <strong><?php echo e(\Carbon\Carbon::parse($quotation->accepted_date)->format('d F Y, H:i')); ?> WIB</strong>
                            </div>
                        <?php endif; ?>
     
                        <?php if($closedNego && $closedNego->negotiated_total): ?>
                            <div class="mt-1" style="font-size:11px; color:#555;">
                                <i class="bi bi-tag me-1"></i> Total harga final:
                                <strong class="text-success">Rp <?php echo e(number_format($closedNego->negotiated_total, 0, ',', '.')); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if(auth()->check() && auth()->user()->isCustomer()): ?>
                    <div>
                        <a href="<?php echo e(route('purchase-orders.create', ['quotation_id' => $quotation->id])); ?>" class="btn btn-success btn-sm fw-semibold shadow-sm">
                            <i class="bi bi-bag-check me-1"></i> Buat PO Sekarang
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
 
        
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h6 class="mb-0 fw-bold text-uppercase" style="color: black; letter-spacing: 1px;">
                    PT. Metinca Prima Industrial Works
                </h6>
                <small class="text-muted">Manufacturing & Industrial Solutions</small>
            </div>
            
            <div class="d-flex gap-2 align-items-center flex-wrap">
                
                <?php if(auth()->check() && auth()->user()->isCustomer()): ?>
                    <a href="<?php echo e(route('negotiate.show', ['quotation' => $quotation->id])); ?>" class="btn btn-sm btn-warning">
                        <?php if($quotation->status === 'accepted'): ?>
                            <i class="bi bi-lock-fill me-1"></i>View Negotiate
                        <?php else: ?>
                            <i class="bi bi-chat-left-text me-1"></i>Negotiate
                        <?php endif; ?>
                        <?php if($negotiations->count() > 0): ?>
                            <span class="badge bg-danger ms-1"><?php echo e($negotiations->count()); ?></span>
                        <?php endif; ?>
                    </a>
                    
                    <a href="<?php echo e(route('purchase-orders.create', ['quotation_id' => $quotation->id])); ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-bag-check me-1"></i>Create PO
                    </a>
                <?php endif; ?>
 
                
                <?php if($isInternalSales): ?>
                    <?php if($quotation->status == 'created'): ?>
                        <a href="<?php echo e(route('quotations.edit', $quotation->id)); ?>" class="btn btn-warning btn-sm text-dark fw-bold">
                            <i class="bi bi-pencil-square"></i> Edit Quotation
                        </a>
                    <?php else: ?>
                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Quotation locked, data tidak dapat diubah.">
                            <button class="btn btn-secondary btn-sm" disabled>
                                <i class="bi bi-lock-fill"></i> Quotation Locked
                            </button>
                        </span>
                    <?php endif; ?>
                     
                    <a href="<?php echo e(route('negotiate.show-nego', $quotation->id)); ?>" class="btn btn-sm btn-warning">
                        <i class="bi bi-eye me-1"></i>View Negotiate
                        <?php if($negotiations->count() > 0): ?>
                            <span class="badge bg-danger ms-1"><?php echo e($negotiations->count()); ?></span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
 
                <a href="<?php echo e(route('quotations.export-pdf', $quotation->id)); ?>" class="btn btn-sm btn-danger" title="Export PDF">
                    <i class="bi bi-file-earmark-pdf"></i>
                </a>
                <a href="<?php echo e(route('quotations.index')); ?>" class="btn btn-sm btn-light fw-semibold">
                    Back
                </a>
            </div>
        </div>
 
        
        <div class="row g-4 mb-4">
            <div class="col-md-7">
                <div class="card border h-100">
                    <div class="card-header py-2 px-3" style="background: #e9ecef;">
                        <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.78rem;">
                            <i class="bi bi-file-text me-1"></i>Quotation Information
                        </h6>
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="d-flex mb-1"><span class="text-muted col-4">Quotation No</span><span class="text-muted col-1">:</span><span class="fw-semibold col-7"><?php echo e($quotation->quotation_no); ?></span></div>
                        <div class="d-flex mb-1"><span class="text-muted col-4">Request ID</span><span class="text-muted col-1">:</span><span class="fw-semibold col-7"><?php echo e($quotation->request_id ?? '-'); ?></span></div>
                        <div class="d-flex mb-1"><span class="text-muted col-4">Created Date</span><span class="text-muted col-1">:</span><span class="fw-semibold col-7"><?php echo e(\Carbon\Carbon::parse($quotation->created_at)->format('d F Y')); ?></span></div>
                        <div class="d-flex mb-1">
                            <span class="text-muted col-4">Expired Date</span><span class="text-muted col-1">:</span>
                            <div class="fw-semibold col-7">
                                <?php if($quotation->date_expired): ?>
                                    <?php echo e(\Carbon\Carbon::parse($quotation->date_expired)->format('d F Y')); ?>

                                    <span class="badge bg-danger small ms-1"><?php echo e(\Carbon\Carbon::parse($quotation->date_expired)->diffForHumans()); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 
            <div class="col-md-5">
                <div class="card border h-100">
                    <div class="card-header py-2 px-3" style="background: #e9ecef;">
                        <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.78rem;">
                            <i class="bi bi-person-badge me-1"></i>PIC Information
                        </h6>
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="d-flex mb-1"><span class="text-muted col-3">PIC</span><span class="text-muted col-1">:</span><span class="col-8"><?php echo e($quotation->customer->name ?? '-'); ?></span></div>
                        <div class="d-flex mb-1"><span class="text-muted col-3">Email</span><span class="text-muted col-1">:</span><span class="col-8"><?php echo e($quotation->customer->email ?? '-'); ?></span></div>
                        <div class="d-flex mb-1"><span class="text-muted col-3">Company</span><span class="text-muted col-1">:</span><span class="col-8"><?php echo e($customerAccount->company ?? $quotation->customer->company ?? '-'); ?></span></div>
                        <div class="d-flex mb-1"><span class="text-muted col-3">Phone</span><span class="text-muted col-1">:</span><span class="col-8"><?php echo e($customerAccount->phone ?? '-'); ?></span></div>
                    </div>
                </div>
            </div>
        </div>
 
        
        <div class="card border mb-4">
            <div class="card-header px-3 py-2 d-flex justify-content-between align-items-center" style="background: #e9ecef;">
                <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.78rem;">
                    <i class="bi bi-list-ul me-1"></i>Pricelist Item Quotation
                </h6>
                <?php if($isInternalSales): ?>
                    <span class="badge bg-white text-dark border">
                        <i class="bi bi-shield-check text-primary me-1"></i>Master Price List & Floor Price System
                    </span>
                <?php endif; ?>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 align-middle">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th width="4%" class="text-center py-2">No</th>
                                <th class="py-2">Item & Spesifikasi Article</th>
                                <th width="8%" class="text-center py-2">Qty</th>
                                <th width="18%" class="text-center py-2">Price List (Awal)</th>
                                <?php if($isInternalSales): ?>
                                    <th width="15%" class="text-center py-2">Batas Bawah (Floor)</th>
                                <?php endif; ?>
                                <th width="18%" class="text-center py-2">Harga Final / Nego</th>
                                <th width="16%" class="text-center py-2">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $lastNego = $lastNegotiation ?? null;
                                
                                $lastItemsRaw = [];
                                if ($lastNego && $lastNego->negotiated_items) {
                                    $lastItemsRaw = is_array($lastNego->negotiated_items) 
                                        ? $lastNego->negotiated_items 
                                        : json_decode($lastNego->negotiated_items, true);
                                }
                                $lastItems = collect($lastItemsRaw);
                                
                                $origTotal = $quotation->items->sum(function ($i) use ($lastItems) {
                                    $itemData = $lastItems->firstWhere('id', $i->id);
                                    return ($itemData['original_price'] ?? ($i->original_price ?: $i->price)) * $i->qty;
                                });
                                $negoTotal = $lastNego?->negotiated_total ?? $quotation->items->sum(fn($i) => $i->price * $i->qty);
                            ?>
 
                            <?php $__empty_1 = true; $__currentLoopData = $quotation->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $lastItemData  = $lastItems->firstWhere('id', $item->id);
                                    $originalPrice = (float) ($lastItemData['original_price'] ?? ($item->original_price ?: $item->price));
                                    $displayPrice  = (float) ($lastItemData['negotiated_price'] ?? $item->price);
                                    $displaySub    = $displayPrice * $item->qty;
                                    
                                    $floorInfo   = $floorPrices[$item->id] ?? ['floor_price' => 0];
                                    $floorPrice  = (float) ($item->floor_price ?: ($floorInfo['floor_price'] ?? 0));
                                    $isBelow     = ($floorPrice > 0 && $displayPrice < $floorPrice);
                                ?>
                                <tr>
                                    <td class="text-center fw-bold"><?php echo e($index + 1); ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo e($item->item); ?></div>
                                        <?php if($item->article): ?>
                                            <small class="text-muted">
                                                Art. No: <span class="fw-semibold"><?php echo e($item->article->article_no); ?></span>
                                                <?php if($item->article->material): ?> &bull; Mat: <?php echo e($item->article->material); ?> <?php endif; ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?php echo e($item->qty); ?></td>
                                    <td class="text-end">
                                        Rp <?php echo e(number_format($originalPrice, 0, ',', '.')); ?>

                                    </td>
                                    <?php if($isInternalSales): ?>
                                        <td class="text-end">
                                            <?php if($floorPrice > 0): ?>
                                                <span class="text-muted fw-semibold">Rp <?php echo e(number_format($floorPrice, 0, ',', '.')); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                    <td class="text-end">
                                        <?php if($lastItemData && $originalPrice != $displayPrice): ?>
                                            <small class="text-muted text-decoration-line-through d-block">
                                                Rp <?php echo e(number_format($originalPrice, 0, ',', '.')); ?>

                                            </small>
                                        <?php endif; ?>
                                        <span class="fw-bold <?php echo e($isBelow ? 'text-danger' : 'text-dark'); ?>">
                                            Rp <?php echo e(number_format($displayPrice, 0, ',', '.')); ?>

                                        </span>
                                        <?php if($isInternalSales && $isBelow): ?>
                                            <div class="badge bg-light-danger text-danger border border-danger mt-1" style="font-size: 0.7rem;">
                                                <i class="bi bi-exclamation-triangle me-1"></i>Di bawah batas bawah
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end fw-bold">
                                        Rp <?php echo e(number_format($displaySub, 0, ',', '.')); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="<?php echo e($isInternalSales ? 7 : 6); ?>" class="text-center text-muted fst-italic py-3">Tidak ada item</td></tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot style="background: #f0f4ff;">
                            <tr>
                                <th colspan="<?php echo e($isInternalSales ? 6 : 5); ?>" class="text-end py-2"><?php echo e($lastNego ? 'NEGOTIATED GRAND TOTAL' : 'GRAND TOTAL'); ?></th>
                                <th class="text-end py-2 fw-bold text-primary fs-6">
                                    Rp <?php echo e(number_format($negoTotal, 0, ',', '.')); ?>

                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
 
        
        <?php if($lastNegotiation): ?>
            <div class="card border mb-4">
                <div class="card-header px-3 py-2 d-flex justify-content-between align-items-center" style="background: #fff3cd;">
                    <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.78rem;">
                        <i class="bi bi-arrow-left-right me-1"></i>Hasil Negosiasi Terakhir
                    </h6>
                    <small class="text-muted">
                        <?php echo e($lastNegotiation->from_customer ? ($quotation->customer->name ?? 'Customer') : 'PT. Metinca Prima'); ?> &middot; <?php echo e($lastNegotiation->created_at->format('d M Y, H:i')); ?>

                    </small>
                </div>
                <div class="card-body py-3 px-3">
                    <div class="row g-3">
                        <div class="col-md-12 text-dark">
                            <div class="d-flex gap-2">
                                <span class="text-muted" style="min-width:130px; font-size:13px">Pesan Negosiasi</span>:
                                <span style="font-size:13px"><?php echo e($lastNegotiation->message ?? '-'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <span class="text-muted" style="min-width:130px; font-size:13px">Payment Terms</span>:
                                <span class="fw-semibold text-dark" style="font-size:13px">
                                    <?php
                                        $paymentLabels = ['cash' => 'Cash', 'net_30' => 'Net 30', 'net_60' => 'Net 60', 'dp_50' => 'DP 50%', 'installment' => 'Installment'];
                                    ?>
                                    <?php echo e($paymentLabels[$lastNegotiation->payment_terms] ?? ($lastNegotiation->payment_terms ?? '-')); ?>

                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <span class="text-muted" style="min-width:130px; font-size:13px">Target Delivery</span>:
                                <span class="fw-semibold text-dark" style="font-size:13px">
                                    <?php echo e($lastNegotiation->target_delivery_date ? \Carbon\Carbon::parse($lastNegotiation->target_delivery_date)->format('d F Y') : '-'); ?>

                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <span class="text-muted" style="min-width:130px; font-size:13px">Negotiated Total</span>:
                                <span class="fw-bold text-danger" style="font-size:13px">Rp <?php echo e(number_format($lastNegotiation->negotiated_total, 0, ',', '.')); ?></span>
                            </div>
                        </div>
                        <?php if($lastNegotiation->support_document): ?>
                            <div class="col-md-12">
                                <div class="d-flex gap-2">
                                    <span class="text-muted" style="min-width:130px; font-size:13px">Dokumen Pendukung</span>:
                                    <a href="<?php echo e(asset('storage/' . $lastNegotiation->support_document)); ?>" target="_blank" style="font-size:13px">
                                        <i class="bi bi-paperclip me-1"></i><?php echo e(basename($lastNegotiation->support_document)); ?>

                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
 
        
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border h-100">
                    <div class="card-header py-2 px-3" style="background: #e9ecef;">
                        <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.78rem;">
                            <i class="bi bi-sticky me-1"></i>Message / Notes
                        </h6>
                    </div>
                    <div class="card-body py-2 px-3">
                        <p class="mb-0 text-muted fst-italic"><?php echo e($quotation->notes ?? 'Tidak ada catatan.'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border h-100">
                    <div class="card-header py-2 px-3" style="background: #e9ecef;">
                        <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.78rem;">
                            <i class="bi bi-paperclip me-1"></i>Request Attachments
                        </h6>
                    </div>
                    <div class="d-flex gap-1 align-content-start py-2 px-3">
                        <?php if($quotation->request && $quotation->request->attachments): ?>
                            <?php $__empty_1 = true; $__currentLoopData = $quotation->request->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reqs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <a href="/storage/<?php echo e($reqs->file_path); ?>" target="_blank">
                                    <i class="bi bi-file-earmark-pdf me-1"></i><?php echo e($reqs->document_name); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <span class="text-muted fst-italic">Tidak ada lampiran.</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted fst-italic">Tidak ada lampiran.</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
 
        
        <div class="mt-4 pt-3 border-top text-muted small d-flex justify-content-between">
            <span><i class="bi bi-clock me-1"></i>Created: <?php echo e(\Carbon\Carbon::parse($quotation->created_at)->format('d F Y, H:i')); ?> WIB</span>
            <span><?php echo e($quotation->quotation_no); ?></span>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/quotations/show.blade.php ENDPATH**/ ?>