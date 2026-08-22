 
<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works'); ?>
 
<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app-dark.css')); ?>">
    <style>
        .negotiate-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .negotiate-card .card-header {
            border-radius: 12px 12px 0 0;
            padding: 10px 18px;
            font-weight: 600;
            font-size: 12px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            background-color: #f0f4f8;
            color: #555;
            border-bottom: 1px solid #e0e6ed;
        }
        .negotiate-card .card-body { padding: 18px; }
        .info-row { display: flex; margin-bottom: 8px; font-size: 14px; }
        .info-label { width: 130px; color: #888; flex-shrink: 0; }
        .info-sep { margin-right: 8px; color: #ccc; }
        .info-value { font-weight: 500; color: #333; }
        .page-header-card {
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
            border-radius: 12px;
            padding: 18px 24px;
            color: white;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .page-header-card .company-name { font-size: 18px; font-weight: 700; }
        .page-header-card .company-tagline { font-size: 12px; opacity: .85; margin-top: 2px; }
        .item-table th {
            background-color: #f5f7fa;
            font-size: 12px;
            font-weight: 600;
            color: #666;
            border-bottom: 2px solid #e0e6ed;
        }
        .item-table td { vertical-align: middle; font-size: 13px; }
        .orig-price { font-size: 11px; color: #bbb; text-decoration: line-through; display: block; }
        .input-rp { position: relative; }
        .input-rp .prefix { position: absolute; left: 8px; top: 50%; transform: translateY(-50%); font-size: 12px; color: #aaa; pointer-events: none; }
        .input-rp input { padding-left: 32px; }
        .total-row { background-color: #f5f7fa; font-weight: 600; }
        .neg-total { color: #e65100; font-weight: 600; }
        .orig-total-strike { color: #bbb; text-decoration: line-through; font-size: 12px; }
        .summary-item { display: flex; justify-content: space-between; font-size: 13px; padding: 5px 0; }
        .summary-item .lbl { color: #888; }
        .action-grid { display: grid; gap: 8px; }
        .history-item { border-left: 3px solid #e0e6ed; padding-left: 12px; margin-bottom: 12px; }
        .history-item.from-customer { border-left-color: #7c4dff; }
        .history-item.from-pt { border-left-color: #00bcd4; }
        .history-meta { font-size: 11px; color: #bbb; margin-bottom: 3px; }
        .history-content { font-size: 13px; color: #555; }
    </style>
<?php $__env->stopPush(); ?>
 
<?php $__env->startSection('content'); ?>
 

<div class="page-header-card">
    <div>
        <div class="company-name">PT. Metinca Prima Industrial Works</div>
        <div class="company-tagline">Manufacturing & Industrial Solutions</div>
    </div>
    <a href="<?php echo e(route('quotations.show', $quotation->id)); ?>" class="btn btn-light btn-sm">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>
 
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
 
<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle"></i> <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


<?php
    $currentNegoCount = \App\Models\Negotiate::where('quotation_id', $quotation->id)->where('action', 'negotiate')->count();
    $effectiveLimit = \App\Services\SystemSettingService::effectiveNegotiationLimit($quotation);
    $quotaExceeded = $currentNegoCount >= $effectiveLimit;
?>

<div class="alert <?php echo e($quotaExceeded ? 'alert-danger' : 'alert-info'); ?> d-flex justify-content-between align-items-center mb-3">
    <div>
        <i class="bi <?php echo e($quotaExceeded ? 'bi-exclamation-octagon-fill text-danger' : 'bi-info-circle-fill text-info'); ?> me-2 fs-5"></i>
        <strong>Batas Negosiasi Harga:</strong> Counter Negosiasi Ke-<strong><?php echo e($currentNegoCount); ?></strong> dari Maksimal <strong><?php echo e($effectiveLimit); ?>x</strong>
        <?php if($quotation->negotiation_override_quota > 0): ?>
            <span class="badge bg-warning text-dark ms-1">(Termasuk Override +<?php echo e($quotation->negotiation_override_quota); ?>x)</span>
        <?php endif; ?>
        <?php if($quotaExceeded): ?>
            <br><small class="text-danger">Kuota negosiasi telah habis. Form negosiasi dikunci.</small>
        <?php endif; ?>
    </div>
    
    
    <?php if(auth()->user()->isAdmin() || (auth()->user()->isManager() && auth()->user()->divisi === 'sales')): ?>
        <button type="button" class="btn btn-sm btn-warning text-dark fw-bold ms-3 text-nowrap" data-bs-toggle="modal" data-bs-target="#overrideModalShowNego">
            <i class="bi bi-plus-circle-fill me-1"></i> Manager Override (+Kuota)
        </button>
    <?php endif; ?>
</div>


<?php if(auth()->user()->isAdmin() || (auth()->user()->isManager() && auth()->user()->divisi === 'sales')): ?>
<div class="modal fade" id="overrideModalShowNego" tabindex="-1" aria-hidden="true">
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
                            <option value="2">+2 Kali Negosiasi Tambahan</option>
                            <option value="3">+3 Kali Negosiasi Tambahan</option>
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
 

<?php if($quotation->status === 'accepted'): ?>
    <?php
        $closedNego = $quotation->negotiates->whereIn('action', ['closed', 'accept'])->first();
    ?>
    <div class="mb-3 d-flex align-items-center gap-3" style="background: linear-gradient(135deg, #d4edda, #c3e6cb); border: 1.5px solid #28a745; border-radius: 10px; padding: 16px;">
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
    // LOGIKA UTAMA: Hitung data history nego dari server-side agar langsung sinkron saat halaman terbuka
    $origTotal = $quotation->items->sum(fn($i) => $i->price * $i->qty);
    $initialNegoTotal = 0;
    $displayPrices = [];

    foreach($quotation->items as $index => $item) {
        $lastPrice = null;
        if(isset($lastNegotiation) && $lastNegotiation && $lastNegotiation->negotiated_items) {
            // Amankan konversi data JSON string maupun Array bawaan model
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
    $isAccepted = $quotation->status === 'accepted';
?>
 
<form action="<?php echo e(route('negotiate.store', $quotation->id)); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
 
    <div class="row">
        
        <div class="col-lg-8">
 
            
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-file-earmark-text me-1"></i> Quotation Information</div>
                <div class="card-body">
                    <div class="row">
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
                <div class="card-header"><i class="bi bi-list-ul me-1"></i> Pricelist Item Negotiation</div>
                <div class="card-body p-0">
                    <table class="table item-table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3" style="width:44px">No</th>
                                <th>Item</th>
                                <th class="text-center" style="width:60px">Qty</th>
                                <th class="text-end" style="width:130px">Original Price</th>
                                <th class="text-end" style="width:170px">Negotiated Price</th>
                                <th class="text-end pe-3" style="width:120px">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $quotation->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $lastPrice = null;
                                    
                                    if(isset($lastNegotiation) && $lastNegotiation && $lastNegotiation->negotiated_items) {
                                        // Paksa bongkar teks JSON mentah dari database menjadi Array PHP
                                        $itemsArray = is_array($lastNegotiation->negotiated_items) 
                                            ? $lastNegotiation->negotiated_items 
                                            : json_decode($lastNegotiation->negotiated_items, true);
                                        
                                        // Jika berhasil dibongkar menjadi array, cari harganya berdasarkan ID
                                        if(is_array($itemsArray)) {
                                            $lastItem  = collect($itemsArray)->firstWhere('id', $item->id);
                                            $lastPrice = $lastItem['negotiated_price'] ?? null;
                                        }
                                    }
                                    
                                    // Ambil harga nego terakhir, jika belum pernah nego pakai harga asli quotation
                                    $displayPrice = old("items.{$index}.negotiated_price", $lastPrice ?? $item->price);
                                ?>
                                
                                <tr>
                                    <td class="ps-3 text-center"><?php echo e($loop->iteration); ?></td>
                                    <td>
                                        <?php echo e($item->item); ?>

                                        <input type="hidden" name="items[<?php echo e($index); ?>][id]" value="<?php echo e($item->id); ?>">
                                        <?php if($isAccepted): ?>
                                            <input type="hidden" name="items[<?php echo e($index); ?>][negotiated_price]" value="<?php echo e($displayPrice); ?>">
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?php echo e($item->qty); ?></td>
                                    <td class="text-end">
                                        <span class="orig-price">Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?></span>
                                    </td>
                                    <td>
                                        <div class="input-rp">
                                            <span class="prefix">Rp</span>
                                            <input type="number"
                                                class="form-control form-control-sm negotiated-price"
                                                <?php echo e($isAccepted ? '' : 'name=items['.$index.'][negotiated_price]'); ?>

                                                value="<?php echo e($displayPrice); ?>"
                                                data-qty="<?php echo e($item->qty); ?>"
                                                data-original="<?php echo e($item->price); ?>"
                                                min="0" <?php echo e($isAccepted ? 'disabled' : 'required'); ?>

                                                style="<?php echo e($isAccepted ? 'background:#f5f5f5;cursor:not-allowed;opacity:0.7;' : ''); ?>">
                                        </div>
                                    </td>
                                    <td class="text-end pe-3">
                                        <span class="subtotal-cell fw-semibold">
                                            Rp <?php echo e(number_format($displayPrice * $item->qty, 0, ',', '.')); ?>

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
                                    <div style="font-size:13px">Negotiated total:</div>
                                </td>
                                <td colspan="2" class="text-end pe-3 py-3">
                                    <div class="orig-total-strike" id="origTotalDisplay">
                                        Rp <?php echo e(number_format($origTotal, 0, ',', '.')); ?>

                                    </div>
                                    <div class="neg-total" id="negTotalDisplay">
                                        Rp <?php echo e(number_format($initialNegoTotal, 0, ',', '.')); ?>

                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
 
            
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-chat-left-text me-1"></i> Negotiation Message</div>
                <div class="card-body">
                    <?php
                        $lastDeliveryDate = old('target_delivery_date', $lastNegotiation?->target_delivery_date 
                            ? \Carbon\Carbon::parse($lastNegotiation->target_delivery_date)->format('Y-m-d') 
                            : ($quotation->target_delivery_date ? \Carbon\Carbon::parse($quotation->target_delivery_date)->format('Y-m-d') : ''));
                        
                        $lastPayment = old('payment_terms', $lastNegotiation?->payment_terms ?? $quotation->payment_terms ?? '');
                        $presetValues    = ['cash', 'net_30', 'net_60', 'dp_50', 'installment'];
                        $isCustomPayment = $lastPayment && !in_array($lastPayment, $presetValues);
                        $isCustomer      = Auth::user()->isCustomer();
                        $ptDisabled      = $quotation->status === 'accepted' || $isCustomer;
                    ?>
 
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Target Delivery Date</label>
                        <input type="date" name="target_delivery_date" class="form-control" value="<?php echo e($lastDeliveryDate); ?>" min="<?php echo e(now()->format('Y-m-d')); ?>" <?php echo e($isAccepted ? 'disabled' : ''); ?> style="<?php echo e($isAccepted ? 'background:#f5f5f5;cursor:not-allowed;' : ''); ?>">
                        <?php if($isAccepted): ?>
                            <input type="hidden" name="target_delivery_date" value="<?php echo e($lastDeliveryDate); ?>">
                        <?php endif; ?>
                        <div class="form-text" style="font-size:12px">Opsional — jika ada permintaan khusus terkait jadwal pengiriman.</div>
                    </div>
 
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Payment Terms</label>
                        <select name="payment_terms" class="form-select" <?php echo e($ptDisabled ? 'disabled' : ''); ?> style="<?php echo e($isCustomer ? 'background:#f5f5f5;cursor:not-allowed;' : ''); ?>">
                            <option value="">-- Pilih Payment Terms --</option>
                            <option value="cash" <?php echo e($lastPayment == 'cash' ? 'selected' : ''); ?>>Cash</option>
                            <option value="net_30" <?php echo e($lastPayment == 'net_30' ? 'selected' : ''); ?>>Net 30</option>
                            <option value="net_60" <?php echo e($lastPayment == 'net_60' ? 'selected' : ''); ?>>Net 60</option>
                            <option value="dp_50" <?php echo e($lastPayment == 'dp_50' ? 'selected' : ''); ?>>DP 50%</option>
                            <option value="installment" <?php echo e($lastPayment == 'installment' ? 'selected' : ''); ?>>Installment</option>
                            <?php if($isCustomPayment): ?>
                                <option value="<?php echo e($lastPayment); ?>" selected><?php echo e($lastPayment); ?> (custom)</option>
                            <?php endif; ?>
                        </select>
                        <?php if($isCustomer || $quotation->status === 'accepted'): ?>
                            <input type="hidden" name="payment_terms" value="<?php echo e($lastPayment); ?>">
                        <?php endif; ?>
                        <?php if($isCustomer): ?>
                            <div class="form-text" style="font-size:11px;color:#e65100">
                                <i class="bi bi-lock-fill"></i> Payment terms hanya bisa diatur oleh staff.
                            </div>
                        <?php endif; ?>
                    </div>
 
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Reason / Message <span class="text-danger">*</span></label>
                        <textarea name="negotiation_message" class="form-control <?php $__errorArgs = ['negotiation_message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="4" placeholder="Tuliskan alasan negosiasi atau pesan tambahan untuk pihak PT..." <?php echo e($isAccepted ? 'disabled' : 'required'); ?> style="<?php echo e($isAccepted ? 'background:#f5f5f5;cursor:not-allowed;' : ''); ?>"><?php echo e(old('negotiation_message', $lastNegotiation?->message ?? '')); ?></textarea>
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
                        <div class="form-text" style="font-size:12px">Sampaikan alasan negosiasi harga dengan jelas agar dapat dipertimbangkan.</div>
                    </div>
 
                    <div class="mb-0">
                        <label class="form-label fw-semibold" style="font-size:13px">Supporting Document (Opsional)</label>
                        <?php if($lastNegotiation && $lastNegotiation->support_document): ?>
                            <div class="form-control d-flex align-items-center gap-2" style="background:#f5f5f5">
                                <i class="bi bi-paperclip"></i>
                                <a href="<?php echo e(asset('storage/' . $lastNegotiation->support_document)); ?>" target="_blank" style="font-size:13px">
                                    <?php echo e(basename($lastNegotiation->support_document)); ?>

                                </a>
                            </div>
                        <?php else: ?>
                            <input type="file" name="support_document" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png" <?php echo e($isAccepted ? 'disabled' : ''); ?>>
                        <?php endif; ?>
                        <div class="form-text" style="font-size:12px">Upload dokumen pendukung jika ada (penawaran kompetitor, referensi harga, dll).</div>
                    </div>
                </div>
            </div>
        </div>
 
        
        <div class="col-lg-4">
            
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-person-badge me-1"></i> PIC Information</div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">PIC</span>
                        <span class="info-sep">:</span>
                        <span class="info-value"><?php echo e($quotation->customer->name ?? '-'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-sep">:</span>
                        <span class="info-value" style="font-size:13px"><?php echo e($quotation->customer->email ?? '-'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Company</span>
                        <span class="info-sep">:</span>
                        <span class="info-value"><?php echo e($quotation->customer->company ?? '-'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone</span>
                        <span class="info-sep">:</span>
                        <span class="info-value"><?php echo e($customerAccount->phone ?? '-'); ?></span>
                    </div>
                </div>
            </div>
 
            
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-calculator me-1"></i> Negotiation Summary</div>
                <div class="card-body">
                    <div class="summary-item">
                        <span class="lbl">Original total</span>
                        <span class="fw-semibold" id="summaryOriginal">Rp <?php echo e(number_format($origTotal, 0, ',', '.')); ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="lbl">Negotiated total</span>
                        <span class="fw-bold" style="color:#e65100" id="summaryNegotiated">Rp <?php echo e(number_format($initialNegoTotal, 0, ',', '.')); ?></span>
                    </div>
                    <hr class="my-2">
                    <div class="summary-item">
                        <span class="lbl">Difference</span>
                        <span class="fw-bold" id="summaryDiff" style="color:#e65100">
                            <?php echo e($diff > 0 ? '-' : ''); ?>Rp <?php echo e(number_format(abs($diff), 0, ',', '.')); ?>

                        </span>
                    </div>
                    <div class="summary-item">
                        <span class="lbl">Discount %</span>
                        <span id="summaryPct" style="color:#e65100;font-size:13px">
                            <?php echo e($diff > 0 ? '-' : ''); ?><?php echo e($pct); ?>%
                        </span>
                    </div>
                </div>
            </div>
 
            
            <div class="negotiate-card card">
                <div class="card-header"><i class="bi bi-clock-history me-1"></i> Negotiation History</div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $negotiations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $neg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="history-item <?php echo e($neg->from_customer ? 'from-customer' : 'from-pt'); ?>">
                            <div class="history-meta">
                                <strong><?php echo e($neg->from_customer ? ($quotation->customer->name ?? 'Customer') : 'PT. Metinca'); ?></strong>
                                &middot; <?php echo e($neg->created_at->format('d M Y H:i')); ?>

                            </div>
                            <div class="history-content"><?php echo e($neg->message); ?></div>
                            <?php if($neg->negotiated_total): ?>
                                <div class="mt-1">
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

                        <?php 
                            $isLocked = in_array($quotation->status, ['accepted', 'po']); 
                            $lastNegoItem = $negotiations->first();
                            $canSalesClose = $lastNegoItem && $lastNegoItem->from_customer && !$isLocked;
                        ?>

                        <?php if($isLocked): ?>
                            <button type="button" class="btn btn-warning w-100" disabled>
                                <i class="bi bi-arrow-left-right"></i> Submit Negotiation
                            </button>
                            <button type="button" class="btn btn-success w-100" disabled>
                                <i class="bi bi-lock-fill"></i> Close Negotiate
                            </button>
                            <a href="<?php echo e(route('quotations.show', $quotation->id)); ?>" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-left"></i> Kembali ke Detail
                            </a>
                        <?php else: ?>
                            <button type="submit" name="action" value="negotiate" class="btn btn-warning w-100">
                                <i class="bi bi-send-fill me-1"></i> Kirim Balasan Tawaran Sales
                            </button>
 
                            <?php if($canSalesClose): ?>
                                <div style="border-top: 1px dashed #e0e6ed; padding-top: 8px; margin-top: 4px;">
                                    <p class="text-success mb-2 small fw-semibold">
                                        <i class="bi bi-info-circle me-1"></i> Customer telah mengajukan tawaran harga. Anda dapat menyetujui dan menutup negosiasi ini.
                                    </p>
                                    <button type="button" class="btn btn-success w-100 fw-bold" onclick="submitCloseNegotiate()">
                                        <i class="bi bi-check-circle-fill me-1"></i> Close & Setujui Harga Customer
                                    </button>
                                </div>
                            <?php elseif($lastNegoItem && !$lastNegoItem->from_customer): ?>
                                <div style="border-top: 1px dashed #e0e6ed; padding-top: 8px; margin-top: 4px;">
                                    <div class="alert alert-info py-2 px-3 mb-0 small text-center">
                                        <i class="bi bi-hourglass-split me-1"></i> Menunggu Customer meninjau tawaran harga dari Anda.
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div style="border-top: 1px dashed #e0e6ed; padding-top: 8px; margin-top: 4px;">
                                <button type="submit" name="action" value="accept" class="btn btn-primary w-100" onclick="return confirm('Yakin menerima quotation ini?\nHarga quotation akan langsung difinalisasi.')">
                                    <i class="bi bi-check-circle me-1"></i> Accept Quotation (Harga Awal)
                                </button>
                            </div>

                            <a href="<?php echo e(route('quotations.show', $quotation->id)); ?>" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail
                            </a>
                        <?php endif; ?>
                    </div>
 
                    <div class="text-center mt-2" style="font-size:11px;color:#aaa">
                        <?php if(!$isLocked): ?>
                            <?php echo e($negotiations->count()); ?> putaran negosiasi berjalan
                        <?php else: ?>
                            Negosiasi selesai &middot; <?php echo e($negotiations->count()); ?> putaran
                        <?php endif; ?>
                    </div>
                </div>
            </div>
 
        </div>
    </div>
</form>
 

<?php if($negotiations->count() > 0 && !in_array($quotation->status, ['accepted', 'po'])): ?>
    <form id="closeNegotiateForm" method="POST" action="<?php echo e(route('negotiate.close', $quotation->id)); ?>" style="display:none;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PATCH'); ?>
    </form>
<?php endif; ?>
<?php $__env->stopSection(); ?>
 
<?php $__env->startPush('scripts'); ?>
<script>
    function formatRp(n) {
        return 'Rp ' + Math.round(n).toLocaleString('id-ID');
    }
 
    function recalculate() {
        let negTotal = 0;
        let origTotal = 0;
 
        document.querySelectorAll('.negotiated-price').forEach(function(input) {
            const qty      = parseFloat(input.dataset.qty) || 0;
            const original = parseFloat(input.dataset.original) || 0;
            const neg      = parseFloat(input.value) || 0;
            const subtotal = neg * qty;
 
            negTotal  += subtotal;
            origTotal += original * qty;
 
            const row         = input.closest('tr');
            const subtotalCell = row.querySelector('.subtotal-cell');
            if (subtotalCell) {
                subtotalCell.textContent = formatRp(subtotal);
                subtotalCell.style.color = neg < original ? '#e65100' : '#333';
            }
        });
 
        const diff = origTotal - negTotal;
        const pct  = origTotal > 0 ? ((diff / origTotal) * 100).toFixed(1) : 0;
 
        document.getElementById('negTotalDisplay').textContent  = formatRp(negTotal);
        document.getElementById('summaryNegotiated').textContent = formatRp(negTotal);
        document.getElementById('summaryDiff').textContent      = diff > 0 ? '-' + formatRp(diff) : formatRp(0);
        document.getElementById('summaryPct').textContent       = diff > 0 ? '-' + pct + '%' : '0%';
        document.getElementById('summaryPct').style.color       = diff > 0 ? '#e65100' : '#43a047';
    }
 
    document.querySelectorAll('.negotiated-price').forEach(function(input) {
        input.addEventListener('input', recalculate);
    });
 
    recalculate();
</script>
 
<script>
    function submitCloseNegotiate() {
        if (confirm('Yakin menutup negosiasi?\nHarga nego terakhir jadi harga final.')) {
            document.getElementById('closeNegotiateForm').submit();
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/quotations/show-nego.blade.php ENDPATH**/ ?>