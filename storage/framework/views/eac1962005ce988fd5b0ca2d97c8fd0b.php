 
<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works - Negosiasi Harga'); ?>
 
<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app-dark.css')); ?>">
    <style>
        .negotiate-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
            margin-bottom: 20px;
            background: #fff;
        }
        .negotiate-card .card-header {
            border-radius: 11px 11px 0 0;
            padding: 12px 18px;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            background-color: #f8fafc;
            color: #495057;
            border-bottom: 1px solid #edf2f7;
        }
        .negotiate-card .card-body { padding: 18px; }
        .info-row { display: flex; align-items: baseline; margin-bottom: 8px; font-size: 13.5px; }
        .info-label { width: 125px; color: #6c757d; flex-shrink: 0; font-size: 13px; }
        .info-sep { margin-right: 8px; color: #adb5bd; }
        .info-value { font-weight: 600; color: #212529; }
        .page-header-card {
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
            border-radius: 12px;
            padding: 18px 24px;
            color: white;
            margin-bottom: 20px;
        }
        .page-header-card .company-name { font-size: 18px; font-weight: 700; }
        .page-header-card .company-tagline { font-size: 12px; opacity: .88; margin-top: 2px; }
        .item-table th {
            background-color: #f8fafc;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #495057;
            border-bottom: 2px solid #e2e8f0;
            padding: 10px 12px;
        }
        .item-table td { vertical-align: middle; font-size: 13.5px; padding: 12px; }
        .orig-price { font-size: 11.5px; color: #94a3b8; text-decoration: line-through; display: block; }
        .total-row { background-color: #f8fafc; font-weight: 600; }
        .neg-total { color: #d97706; font-weight: 700; }
        .orig-total-strike { color: #94a3b8; text-decoration: line-through; font-size: 12px; }
        .summary-item { display: flex; justify-content: space-between; font-size: 13px; padding: 6px 0; }
        .summary-item .lbl { color: #6c757d; }
        .history-item { 
            border-left: 3px solid #cbd5e1; 
            padding-left: 14px; 
            margin-bottom: 16px; 
            position: relative;
        }
        .history-item.from-customer { border-left-color: #7c4dff; }
        .history-item.from-pt { border-left-color: #00bcd4; }
        .history-meta { font-size: 11.5px; color: #64748b; margin-bottom: 4px; }
        .history-content { 
            font-size: 13px; 
            color: #334155; 
            background: #f8fafc; 
            padding: 8px 12px; 
            border-radius: 8px; 
            border: 1px solid #f1f5f9;
        }

        /* Dark Mode Scoped Overrides */
        html[data-bs-theme="dark"] .negotiate-card {
            background: #1e1e2d !important;
            border-color: #2d3047 !important;
        }
        html[data-bs-theme="dark"] .negotiate-card .card-header {
            background-color: #24263a !important;
            color: #e2e8f0 !important;
            border-bottom-color: #2d3047 !important;
        }
        html[data-bs-theme="dark"] .info-label {
            color: #94a3b8 !important;
        }
        html[data-bs-theme="dark"] .info-value {
            color: #f1f1f9 !important;
        }
        html[data-bs-theme="dark"] .item-table th {
            background-color: #222438 !important;
            color: #e2e8f0 !important;
            border-bottom: 2px solid #3d425c !important;
        }
        html[data-bs-theme="dark"] .total-row {
            background-color: #24263a !important;
        }
        html[data-bs-theme="dark"] .history-content {
            background: #171827 !important;
            color: #cbd5e1 !important;
            border-color: #2d3047 !important;
        }
    </style>
<?php $__env->stopPush(); ?>
 
<?php $__env->startSection('content'); ?>
 

<div class="page-header-card shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <div class="company-name"><i class="bi bi-chat-quote-fill me-2"></i>Portal Negosiasi Penawaran Harga</div>
        <div class="company-tagline">Quotation #<?php echo e($quotation->quotation_no); ?> &bull; PT. Metinca Prima Industrial Works</div>
    </div>
    <a href="<?php echo e(route('quotations.show', $quotation->id)); ?>" class="btn btn-light btn-sm fw-semibold shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail
    </a>
</div>
 
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
        <i class="bi bi-check-circle-fill me-1"></i> <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
 
<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


<?php
    $currentNegoCount = \App\Models\Negotiate::where('quotation_id', $quotation->id)->where('action', 'negotiate')->count();
    $effectiveLimit = \App\Services\SystemSettingService::effectiveNegotiationLimit($quotation);
    $quotaExceeded = $currentNegoCount >= $effectiveLimit;
    $maxRounds = floor($effectiveLimit / 2);

    $isLocked = in_array($quotation->status, ['accepted', 'po']);
    $lastNegoItem = $negotiations->where('action', 'negotiate')->first() ?? $negotiations->first();
    $isWaitingSales = $lastNegoItem && $lastNegoItem->from_customer && !$isLocked;
    $canCustomerClose = $lastNegoItem && !$lastNegoItem->from_customer && !$isLocked;
    $isFormDisabled = $isLocked || $quotaExceeded || $isWaitingSales;
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
            <?php elseif($isWaitingSales): ?>
                <span class="text-primary fw-semibold"><i class="bi bi-hourglass-split me-1"></i>Giliran Tim Sales meninjau. Anda telah mengajukan penawaran harga terakhir.</span>
            <?php else: ?>
                <span>Sisa kuota: <strong><?php echo e($effectiveLimit - $currentNegoCount); ?>x</strong> kesempatan pengajuan penawaran. (1x Saling Balas = 1 Customer + 1 Sales).</span>
            <?php endif; ?>
        </small>
    </div>
</div>
 

<?php if($quotation->status === 'accepted'): ?>
    <?php
        $closedNego = $quotation->negotiates->whereIn('action', ['closed', 'accept'])->first();
    ?>
    <div class="mb-3 d-flex align-items-center gap-3 shadow-sm" style="background: linear-gradient(135deg, #d4edda, #c3e6cb); border: 1.5px solid #28a745; border-radius: 10px; padding: 16px;">
        <div style="font-size: 32px; line-height:1;">
            <i class="bi bi-patch-check-fill text-success"></i>
        </div>
        <div>
            <div class="fw-bold text-success" style="font-size:15px;">Negosiasi Telah Disetujui</div>
            <div class="text-muted" style="font-size:12px;">Harga yang disepakati telah difinalisasi. Negosiasi tidak dapat dilanjutkan.</div>
            
            <?php if($quotation->accepted_date): ?>
                <div class="mt-1" style="font-size:11px; color:#555;">
                    <i class="bi bi-clock me-1"></i> Ditutup pada: 
                    <strong><?php echo e(\Carbon\Carbon::parse($quotation->accepted_date)->format('d F Y')); ?> </strong>
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
<?php endif; ?>

<?php
    $origTotal = $quotation->items->sum(fn($i) => $i->price * $i->qty);
    $initialNegoTotal = 0;
    $displayPrices = [];

    foreach($quotation->items as $index => $item) {
        $lastPrice = null;
        if(isset($lastNegotiation) && $lastNegotiation && $lastNegotiation->negotiated_items) {
            $itemsArray = is_array($lastNegotiation->negotiated_items) 
                ? $lastNegotiation->negotiated_items 
                : json_decode($lastNegotiation->negotiated_items, true);
            
            if(is_array($itemsArray)) {
                $lastItem = collect($itemsArray)->firstWhere('id', $item->id);
                $lastPrice = $lastItem['negotiated_price'] ?? null;
            }
        }
        $priceFinal = old("items.{$index}.negotiated_price", $lastPrice ?? $item->price);
        $displayPrices[$item->id] = $priceFinal;
        $initialNegoTotal += ($priceFinal * $item->qty);
    }

    $diff = $origTotal - $initialNegoTotal;
    $pct  = $origTotal > 0 ? number_format(($diff / $origTotal) * 100, 1) : 0;
?>
 
<form action="<?php echo e(route('negotiate.store', $quotation->id)); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
 
    <div class="row g-3">
        
        <div class="col-lg-8">
 
            
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-file-earmark-text me-1 text-primary"></i> Quotation Information</div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Quotation No</span>
                                <span class="info-sep">:</span>
                                <span class="info-value"><?php echo e($quotation->quotation_no); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Request ID</span>
                                <span class="info-sep">:</span>
                                <span class="info-value">#<?php echo e($quotation->request_id); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Created Date</span>
                                <span class="info-sep">:</span>
                                <span class="info-value"><?php echo e($quotation->created_at->format('d F Y')); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Expired Date</span>
                                <span class="info-sep">:</span>
                                <span class="info-value"><?php echo e(\Carbon\Carbon::parse($quotation->date_expired)->format('d F Y')); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Status</span>
                                <span class="info-sep">:</span>
                                <span class="info-value">
                                    <span class="badge bg-warning text-dark"><?php echo e(ucfirst($quotation->status)); ?></span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Customer</span>
                                <span class="info-sep">:</span>
                                <span class="info-value"><?php echo e($quotation->customer->name ?? '-'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 
            
            <div class="negotiate-card card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-1">
                    <span><i class="bi bi-list-ul me-1 text-primary"></i> Item Negosiasi Penawaran Harga</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table item-table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-3 text-center" style="width: 5%;">#</th>
                                    <th style="width: 35%;">Item</th>
                                    <th class="text-center" style="width: 8%;">Qty</th>
                                    <th class="text-end" style="width: 17%;">Harga Awal</th>
                                    <th class="text-end" style="width: 22%;">Harga Nego (Rp)</th>
                                    <th class="text-end pe-3" style="width: 18%;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $quotation->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php 
                                        $currentPrice = $displayPrices[$item->id];
                                        $floorData = $floorPrices[$item->id] ?? ['floor_price' => 0, 'master_price' => 0, 'article' => null];
                                        $floorPrice = $floorData['floor_price'];
                                    ?>
                                    <tr>
                                        <td class="ps-3 text-center text-muted fw-bold"><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <div class="fw-semibold text-dark"><?php echo e($item->item); ?></div>
                                            <input type="hidden" name="items[<?php echo e($index); ?>][id]" value="<?php echo e($item->id); ?>">
                                            <?php if($isFormDisabled): ?>
                                                <input type="hidden" name="items[<?php echo e($index); ?>][negotiated_price]" value="<?php echo e($currentPrice); ?>">
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center fw-bold"><?php echo e($item->qty); ?></td>
                                        <td class="text-end">
                                            <span class="orig-price">Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?></span>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-light text-muted fw-semibold" style="font-size:0.75rem;">Rp</span>
                                                <input type="number"
                                                    step="0.01"
                                                    class="form-control form-control-sm text-end fw-semibold negotiated-price"
                                                    <?php echo e($isFormDisabled ? '' : 'name=items['.$index.'][negotiated_price]'); ?>

                                                    value="<?php echo e($currentPrice); ?>"
                                                    data-qty="<?php echo e($item->qty); ?>"
                                                    data-original="<?php echo e($item->price); ?>"
                                                    min="0" <?php echo e($isFormDisabled ? 'disabled' : 'required'); ?>

                                                    style="<?php echo e($isFormDisabled ? 'background:#f5f5f5;cursor:not-allowed;opacity:0.7;' : ''); ?>">
                                            </div>
                                        </td>
                                        <td class="text-end pe-3">
                                            <span class="subtotal-cell fw-semibold text-dark">
                                                Rp <?php echo e(number_format($currentPrice * $item->qty, 0, ',', '.')); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="4" class="text-end pe-3 py-3">
                                        <div class="orig-total-strike">
                                            Original: Rp <?php echo e(number_format($origTotal, 0, ',', '.')); ?>

                                        </div>
                                        <div class="fw-bold text-muted" style="font-size:13px">Total Negosiasi:</div>
                                    </td>
                                    <td colspan="2" class="text-end pe-3 py-3">
                                        <div class="orig-total-strike" id="origTotalDisplay">
                                            Rp <?php echo e(number_format($origTotal, 0, ',', '.')); ?>

                                        </div>
                                        <div class="neg-total fs-6" id="negTotalDisplay">
                                            Rp <?php echo e(number_format($initialNegoTotal, 0, ',', '.')); ?>

                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
 
            
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-chat-left-text me-1 text-primary"></i> Pesan & Keterangan Pengajuan Negosiasi</div>
                <div class="card-body">
                    <?php
                        $lastDeliveryDate = old('target_delivery_date', $lastNegotiation?->target_delivery_date 
                            ? \Carbon\Carbon::parse($lastNegotiation->target_delivery_date)->format('Y-m-d') 
                            : ($quotation->target_delivery_date ? \Carbon\Carbon::parse($quotation->target_delivery_date)->format('Y-m-d') : ''));
                        $lastPaymentTerms = old('payment_terms', $lastNegotiation?->payment_terms ?? $quotation->payment_terms);
                    ?>
 
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Pesan / Alasan Negosiasi <span class="text-danger">*</span></label>
                        <textarea name="negotiation_message" class="form-control form-control-sm <?php $__errorArgs = ['negotiation_message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            rows="3" placeholder="Tuliskan alasan penawaran harga (misal: penyesuaian anggaran, pemesanan kuantitas besar)..." <?php echo e($isFormDisabled ? 'disabled' : 'required'); ?>><?php echo e(old('negotiation_message')); ?></textarea>
                        <?php $__errorArgs = ['negotiation_message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
 
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold" style="font-size:13px">Ekspektasi Delivery Date</label>
                            <input type="date" name="target_delivery_date"
                                class="form-control form-control-sm <?php $__errorArgs = ['target_delivery_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e($lastDeliveryDate); ?>" <?php echo e($isFormDisabled ? 'disabled' : ''); ?>>
                            <?php $__errorArgs = ['target_delivery_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold" style="font-size:13px">Payment Terms yang Diajukan</label>
                            <input type="text" name="payment_terms"
                                class="form-control form-control-sm <?php $__errorArgs = ['payment_terms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e($lastPaymentTerms); ?>"
                                placeholder="Contoh: Net 30, Cash, DP 50%" <?php echo e($isFormDisabled ? 'disabled' : ''); ?>>
                            <?php $__errorArgs = ['payment_terms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
 
                    <div class="mb-1">
                        <label class="form-label fw-semibold" style="font-size:13px">
                            Dokumen Pendukung <span class="text-muted fw-normal">(Opsional, misal surat penawaran perbandingan)</span>
                        </label>
                        <input type="file" name="support_document"
                            class="form-control form-control-sm <?php $__errorArgs = ['support_document'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            accept=".pdf,.doc,.docx,.jpg,.png" <?php echo e($isFormDisabled ? 'disabled' : ''); ?>>
                        <?php $__errorArgs = ['support_document'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
 
        </div>
 
        
        <div class="col-lg-4">
            
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-person-badge me-1 text-primary"></i> Data Pemohon</div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Nama PIC</span>
                        <span class="info-sep">:</span>
                        <span class="info-value"><?php echo e($quotation->customer->name ?? '-'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-sep">:</span>
                        <span class="info-value" style="font-size:13px"><?php echo e($quotation->customer->email ?? '-'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Perusahaan</span>
                        <span class="info-sep">:</span>
                        <span class="info-value"><?php echo e($quotation->customer->company ?? '-'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Telepon</span>
                        <span class="info-sep">:</span>
                        <span class="info-value"><?php echo e($customerAccount->phone ?? '-'); ?></span>
                    </div>
                </div>
            </div>
 
            
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-calculator me-1 text-primary"></i> Ringkasan Negosiasi</div>
                <div class="card-body">
                    <div class="summary-item">
                        <span class="lbl">Total Harga Awal</span>
                        <span class="fw-semibold" id="summaryOriginal">Rp <?php echo e(number_format($origTotal, 0, ',', '.')); ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="lbl">Total Pengajuan Nego</span>
                        <span class="fw-bold" style="color:#d97706" id="summaryNegotiated">Rp <?php echo e(number_format($initialNegoTotal, 0, ',', '.')); ?></span>
                    </div>
                    <hr class="my-2">
                    <div class="summary-item">
                        <span class="lbl">Selisih Penurunan</span>
                        <span class="fw-bold" id="summaryDiff" style="color:#d97706">
                            <?php echo e($diff > 0 ? '-' : ''); ?>Rp <?php echo e(number_format(abs($diff), 0, ',', '.')); ?>

                        </span>
                    </div>
                    <div class="summary-item">
                        <span class="lbl">Persentase Diskon</span>
                        <span id="summaryPct" style="color:#d97706;font-size:13px;font-weight:600;">
                            <?php echo e($diff > 0 ? '-' : ''); ?><?php echo e($pct); ?>%
                        </span>
                    </div>
                </div>
            </div>
 
            
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-clock-history me-1 text-primary"></i> Riwayat Diskusi Negosiasi</div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $negotiations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $neg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="history-item <?php echo e($neg->from_customer ? 'from-customer' : 'from-pt'); ?>">
                            <div class="history-meta">
                                <strong><?php echo e($neg->from_customer ? ($quotation->customer->name ?? 'Customer') : 'Tim Sales (PT. Metinca)'); ?></strong>
                                &middot; <?php echo e($neg->created_at->format('d M Y H:i')); ?>

                            </div>
                            <div class="history-content"><?php echo e($neg->message); ?></div>
                            <?php if($neg->negotiated_total): ?>
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    <span class="badge bg-light text-dark border" style="font-size:11px">
                                        Proposed: Rp <?php echo e(number_format($neg->negotiated_total, 0, ',', '.')); ?>

                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center text-muted py-3" style="font-size:13px">
                            <i class="bi bi-chat-square-dots" style="font-size:22px;opacity:.3;display:block;margin-bottom:6px"></i>
                            Belum ada riwayat negosiasi
                        </div>
                    <?php endif; ?>
                </div>
            </div>
 
            
            <div class="negotiate-card card">
                <div class="card-body">
                    <div class="action-grid">
 
                        <?php if($isLocked): ?>
                            <button type="button" class="btn btn-warning w-100" disabled>
                                <i class="bi bi-arrow-left-right"></i> Submit Counter-Negotiation
                            </button>
                            <a href="<?php echo e(route('quotations.show', $quotation->id)); ?>" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        <?php elseif($quotaExceeded): ?>
                            <button type="button" class="btn btn-secondary w-100" disabled title="Kuota negosiasi telah habis">
                                <i class="bi bi-lock-fill me-1"></i> Kuota Negosiasi Habis (<?php echo e($currentNegoCount); ?>/<?php echo e($effectiveLimit); ?>x)
                            </button>
 
                            <?php if($canCustomerClose): ?>
                                <div class="p-2 border rounded bg-light mt-1">
                                    <p class="text-success mb-2 small fw-semibold">
                                        <i class="bi bi-info-circle me-1"></i> Anda masih dapat menyetujui dan menutup negosiasi dengan harga penawaran terakhir dari Sales.
                                    </p>
                                    <button type="button" class="btn btn-success w-100 fw-bold shadow-sm" onclick="if(confirm('Apakah Anda yakin setuju dengan penawaran harga dari Sales dan ingin menutup negosiasi ini?')){ document.getElementById('closeNegotiateForm').submit(); }">
                                        <i class="bi bi-check-circle-fill me-1"></i> Close & Setujui Harga Sales
                                    </button>
                                </div>
                            <?php endif; ?>
 
                            <a href="<?php echo e(route('quotations.show', $quotation->id)); ?>" class="btn btn-outline-secondary w-100 mt-1">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        <?php elseif($isWaitingSales): ?>
                            
                            <div class="alert alert-info py-2 px-3 mb-2 small text-center shadow-sm">
                                <i class="bi bi-hourglass-split me-1 text-primary"></i><strong>Menunggu Balasan Tim Sales</strong><br>
                                Anda telah mengajukan penawaran harga terakhir. Sesuai giliran negosiasi bolak-balik, silakan tunggu tanggapan atau tawaran balasan dari Tim Sales.
                            </div>
                            <button type="button" class="btn btn-secondary w-100 fw-semibold" disabled title="Menunggu respon/balasan dari Tim Sales">
                                <i class="bi bi-hourglass-split me-1"></i> Menunggu Balasan Tim Sales
                            </button>
 
                            <a href="<?php echo e(route('quotations.show', $quotation->id)); ?>" class="btn btn-outline-secondary w-100 mt-1">
                                <i class="bi bi-x-circle"></i> Kembali
                            </a>
                        <?php else: ?>
                            
                            <button type="submit" name="action" value="negotiate" class="btn btn-warning w-100 fw-bold text-dark shadow-sm">
                                <i class="bi bi-send-fill me-1"></i> Kirim Tawaran Harga Baru
                            </button>
 
                            <?php if($canCustomerClose): ?>
                                <div class="p-2 border rounded bg-light mt-1">
                                    <p class="text-success mb-2 small fw-semibold">
                                        <i class="bi bi-info-circle me-1"></i> Tim Sales telah mengajukan penawaran harga. Anda dapat menyetujui dan menutup negosiasi ini.
                                    </p>
                                    <button type="button" class="btn btn-success w-100 fw-bold shadow-sm" onclick="if(confirm('Apakah Anda yakin setuju dengan penawaran harga dari Sales dan ingin menutup negosiasi ini?')){ document.getElementById('closeNegotiateForm').submit(); }">
                                        <i class="bi bi-check-circle-fill me-1"></i> Close & Setujui Harga Sales
                                    </button>
                                </div>
                            <?php endif; ?>
 
                            <a href="<?php echo e(route('quotations.show', $quotation->id)); ?>" class="btn btn-outline-secondary w-100 mt-1">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        <?php endif; ?>
 
                        <?php if($quotation->status === 'accepted'): ?>
                        <div class="text-center mt-1">
                            <span class="badge bg-success px-3 py-2 w-100" style="font-size:12px;white-space:normal;line-height:1.5">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                Negosiasi telah disepakati pada
                                <?php echo e($quotation->accepted_date
                                ? \Carbon\Carbon::parse($quotation->accepted_date)->format('d F Y')
                                : '-'); ?>

                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
 
        </div>
    </div>
</form>
 

<form id="closeNegotiateForm" method="POST" action="<?php echo e(route('negotiate.close', $quotation->id)); ?>" style="display:none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PATCH'); ?>
</form>
<?php $__env->stopSection(); ?>
 
<?php $__env->startPush('scripts'); ?>
<script>
    function formatRp(n) {
        return 'Rp ' + Math.round(n).toLocaleString('id-ID');
    }
 
    function recalculate() {
        let negTotal = 0;
        let origTotal = 0;
        let hasUnderFloor = false;

        document.querySelectorAll('.negotiated-price').forEach(function(input) {
            const qty      = parseFloat(input.dataset.qty) || 0;
            const original = parseFloat(input.dataset.original) || 0;
            const floor    = parseFloat(input.dataset.floor) || 0;
            const neg      = parseFloat(input.value) || 0;
            const subtotal = neg * qty;

            negTotal  += subtotal;
            origTotal += original * qty;

            const row         = input.closest('tr');
            const subtotalCell = row.querySelector('.subtotal-cell');
            if (subtotalCell) {
                subtotalCell.textContent = formatRp(subtotal);
                subtotalCell.style.color = neg < original ? '#d97706' : '#212529';
            }
        });

        const diff = origTotal - negTotal;
        const pct  = origTotal > 0 ? ((diff / origTotal) * 100).toFixed(1) : 0;

        document.getElementById('negTotalDisplay').textContent  = formatRp(negTotal);
        document.getElementById('summaryNegotiated').textContent = formatRp(negTotal);
        document.getElementById('summaryDiff').textContent      = diff > 0 ? '-' + formatRp(diff) : formatRp(0);
        document.getElementById('summaryPct').textContent       = diff > 0 ? '-' + pct + '%' : '0%';
        document.getElementById('summaryPct').style.color       = diff > 0 ? '#d97706' : '#198754';
    }

    document.querySelectorAll('.negotiated-price').forEach(function(input) {
        input.addEventListener('input', recalculate);
    });

    recalculate();
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/quotations/negotiate.blade.php ENDPATH**/ ?>