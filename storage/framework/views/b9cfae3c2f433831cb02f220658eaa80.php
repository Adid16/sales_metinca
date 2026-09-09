<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/extensions/simple-datatables/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/table-datatable.css')); ?>">
    <style>
        #poTable {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        #poTable thead th {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #4b5563;
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            padding: 12px 14px;
            vertical-align: middle;
        }
        #poTable tbody td {
            padding: 12px 14px;
            vertical-align: middle;
            font-size: 0.875rem;
            border-bottom: 1px solid #f1f5f9;
        }
        #poTable tbody tr:hover {
            background-color: #f8faff !important;
        }
        .btn-action-group .btn {
            font-size: 0.78rem;
            padding: 0.28rem 0.6rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            font-weight: 600;
        }
        .bg-primary-subtle {
            background-color: #eef2ff !important;
            color: #4f46e5 !important;
        }
        .bg-success-subtle {
            background-color: #ecfdf5 !important;
            color: #059669 !important;
        }
        .bg-warning-subtle {
            background-color: #fffbeb !important;
            color: #d97706 !important;
        }
        .bg-info-subtle {
            background-color: #f0f9ff !important;
            color: #0284c7 !important;
        }

        /* Dark Mode Specific Overrides */
        html[data-bs-theme="dark"] #poTable thead th {
            color: #e2e8f0 !important;
            background-color: #222438 !important;
            border-bottom: 2px solid #3d425c !important;
        }
        html[data-bs-theme="dark"] #poTable tbody td {
            border-bottom: 1px solid #2a2d3e !important;
            color: #c2c2d9 !important;
        }
        html[data-bs-theme="dark"] #poTable tbody tr:hover {
            background-color: rgba(67, 94, 190, 0.14) !important;
        }
        html[data-bs-theme="dark"] .bg-primary-subtle {
            background-color: rgba(67, 94, 190, 0.25) !important;
            color: #93b0ff !important;
        }
        html[data-bs-theme="dark"] .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.25) !important;
            color: #5eead4 !important;
        }
        html[data-bs-theme="dark"] .bg-warning-subtle {
            background-color: rgba(245, 158, 11, 0.22) !important;
            color: #fde047 !important;
        }
        html[data-bs-theme="dark"] .bg-info-subtle {
            background-color: rgba(13, 202, 240, 0.22) !important;
            color: #67e8f9 !important;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
   <div class="card detail-card">
        <div class="card-header py-3 bg-primary text-white">
            <h5 class="card-title mb-0">
                <i class="bi bi-send-plus-fill"></i> Purchase Order
            </h5>
        </div>
    <section class="content">
            <div class="card-body">
                
                <?php
                    $currentType = request('type', '');
                ?>
                <ul class="nav nav-tabs mb-3" id="poTypeTabs">
                    <li class="nav-item">
                        <a class="nav-link <?php echo e($currentType == '' ? 'active' : ''); ?>" 
                           href="<?php echo e(route('purchase-orders.index', array_merge(request()->except('type','page'), []))); ?>">
                            <i class="bi bi-list-ul me-1"></i>Semua PO
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e($currentType == 'new' ? 'active' : ''); ?>" 
                           href="<?php echo e(route('purchase-orders.index', array_merge(request()->except('type','page'), ['type' => 'new']))); ?>">
                            <i class="bi bi-plus-circle me-1"></i>PO Baru
                            <span class="badge bg-success ms-1">New</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e($currentType == 'amandement' ? 'active' : ''); ?>" 
                           href="<?php echo e(route('purchase-orders.index', array_merge(request()->except('type','page'), ['type' => 'amandement']))); ?>">
                            <i class="bi bi-arrow-repeat me-1"></i>PO Amandemen
                            <span class="badge bg-warning text-dark ms-1">Revisi</span>
                        </a>
                    </li>
                </ul>
                <form class="row g-2 align-items-center mb-3" method="GET" action="<?php echo e(route('purchase-orders.index')); ?>">
                    <div class="col-12 col-sm-auto">
                        <input type="date" name="start_date" class="form-control form-control-sm" value="<?php echo e($filters['start_date'] ?? ''); ?>" placeholder="From">
                    </div>
                    <div class="col-12 col-sm-auto">
                        <input type="date" name="end_date" class="form-control form-control-sm" value="<?php echo e($filters['end_date'] ?? ''); ?>" placeholder="To">
                    </div>
                    <div class="col-12 col-sm-auto">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            <option value="sent" <?php echo e((isset($filters['status']) && $filters['status']=='sent') ? 'selected' : ''); ?>>Sent</option>
                            <option value="amandement" <?php echo e((isset($filters['status']) && $filters['status']=='amandement') ? 'selected' : ''); ?>>Amandement</option>
                            <option value="review" <?php echo e((isset($filters['status']) && $filters['status']=='review') ? 'selected' : ''); ?>>Review</option>
                            <option value="contract" <?php echo e((isset($filters['status']) && $filters['status']=='contract') ? 'selected' : ''); ?>>Contract</option>
                            <option value="production" <?php echo e((isset($filters['status']) && $filters['status']=='production') ? 'selected' : ''); ?>>Production</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-auto d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        <button type="submit" formaction="<?php echo e(route('purchase-orders.export')); ?>" class="btn btn-sm btn-success">Export</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="poTable">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 45px;">No</th>
                            <th class="text-center" style="width: 140px;">Quotation No</th>
                            <th class="text-center" style="width: 170px;">PO No</th>
                            <th class="text-start" style="min-width: 220px;">Part / Item Name</th>
                            <th class="text-center" style="width: 120px;">Delivery Date</th>
                            <th class="text-center" style="width: 130px;">Sales PIC</th>
                            <th class="text-center" style="width: 140px;">Status</th>
                            <th class="text-center" style="min-width: 220px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $pos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $po): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $poStatus       = strtolower($po->status);
                                $internalsCount = $po->internals ? $po->internals->count() : 0;
                                $quotationCount = ($po->quotation && $po->quotation->items) ? $po->quotation->items->count() : 0;
                                
                                // Total item sebenarnya dari transaksi PO ini
                                $totalItemCount   = max($internalsCount, $quotationCount, 1);
                                $isMultiItem      = $totalItemCount > 1;
                                $isFullyProcessed = ($internalsCount >= $totalItemCount) && ($totalItemCount > 0);

                                $firstItemName = '-';
                                $firstArticleOrPart = null;
                                if ($po->internals && $po->internals->count() > 0) {
                                    $firstInternalRow = $po->internals->first();
                                    $firstItemName = $firstInternalRow->item ?? '-';
                                    $firstArticleOrPart = $firstInternalRow->part_no ?? $firstInternalRow->article_no ?? null;
                                } elseif ($po->quotation && $po->quotation->items->count() > 0) {
                                    $firstQuotationRow = $po->quotation->items->first();
                                    $firstItemName = $firstQuotationRow->item ?? '-';
                                }
                            ?>
                            
                            
                            <tr class="table-group-divider">
                                <td class="text-center text-muted fw-semibold"><?php echo e($loop->iteration); ?></td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-2 py-1"><?php echo e($po->quotation->quotation_no ?? '-'); ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-primary"><?php echo e($po->po_no); ?></span>
                                    
                                    <?php
                                        $isCurrentlyAmandement = in_array($poStatus, ['amandement', 'amandement_pending'])
                                            || ($po->internals && $po->internals->contains(function($i) {
                                                return in_array(strtolower($i->status ?? ''), ['amandement', 'amandement_pending'])
                                                    || ($i->contract && in_array(strtolower($i->contract->status ?? ''), ['amandement', 'amandement_pending']));
                                            }));
                                        $hasPastAmendment = $po->internals && $po->internals->flatMap(function($i) {
                                            return \App\Models\Contract::where('purchase_order_internal_id', $i->id)
                                                ->where('amandement_no', '>', 0)->get();
                                        })->count() > 0;
                                        $maxAmendNo = $po->internals ? $po->internals->flatMap(function($i) {
                                            return \App\Models\Contract::where('purchase_order_internal_id', $i->id)->pluck('amandement_no');
                                        })->max() : 0;
                                    ?>
                                    <?php if($isCurrentlyAmandement): ?>
                                        <div class="mt-1"><span class="badge bg-warning text-dark px-2 py-0" style="font-size:0.68rem;"><i class="bi bi-arrow-repeat me-1"></i>Amandemen Rev #<?php echo e($maxAmendNo ?: 1); ?></span></div>
                                    <?php else: ?>
                                        <div class="mt-1"><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0" style="font-size:0.68rem;"><i class="bi bi-plus-circle me-1"></i>PO Baru</span></div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-start">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fw-semibold">
                                            <i class="bi bi-boxes me-1"></i><?php echo e($totalItemCount); ?> Item(s)
                                        </span>
                                        <?php if($internalsCount > 0): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fw-semibold">
                                                <i class="bi bi-check2 me-1"></i><?php echo e($internalsCount); ?>/<?php echo e($totalItemCount); ?> Diproses
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-center text-nowrap">
                                    <i class="bi bi-calendar-event me-1 text-muted"></i><?php echo e(\Carbon\Carbon::parse($po->delivery_request)->format('d-m-Y')); ?>

                                </td>
                                <td class="text-center">
                                    <?php if($po->quotation && $po->quotation->request && $po->quotation->request->assignment): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            <i class="bi bi-person-fill me-1"></i><?php echo e($po->quotation->request->assignment->sales->name ?? '-'); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($poStatus == 'amandement_pending'): ?>
                                        <span class="badge bg-warning-subtle text-dark border border-warning px-2 py-1"><i class="bi bi-hourglass-split me-1 text-warning"></i>Amandemen Pending</span>
                                    <?php elseif($poStatus == 'amandement'): ?>
                                        <span class="badge bg-danger text-white px-2 py-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>Amandemen</span>
                                    <?php elseif(in_array($poStatus, ['contract', 'approved'])): ?>
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1"><i class="bi bi-file-earmark-check me-1"></i>Contract</span>
                                    <?php elseif($poStatus == 'production'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-gear-wide-connected me-1"></i>In Production</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1"><i class="bi bi-send me-1"></i><?php echo e(ucfirst($po->status)); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1 flex-wrap btn-action-group">
                                        
                                        <button type="button" class="btn btn-sm btn-info text-white btn-show"
                                            data-id="<?php echo e($po->id); ?>" data-bs-toggle="modal"
                                            data-bs-target="#previewModal">
                                            <i class="bi bi-file-earmark-text-fill me-1"></i> Detail
                                        </button>

                                        
                                        <button class="btn btn-sm btn-outline-primary fw-semibold" type="button" 
                                                data-bs-toggle="collapse" data-bs-target="#collapsePoItems<?php echo e($po->id); ?>" 
                                                aria-expanded="false">
                                            <i class="bi bi-chevron-down me-1"></i> Rincian Item (<?php echo e($totalItemCount); ?>)
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            
                            <tr class="collapse border-0 bg-light" id="collapsePoItems<?php echo e($po->id); ?>">
                                <td colspan="8" class="p-3">
                                    <div class="card shadow-sm border mb-0">
                                            <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                                                <strong class="text-dark small"><i class="bi bi-list-nested me-1 text-primary"></i> RINCIAN ITEM UNTUK PO: <?php echo e($po->po_no); ?></strong>
                                                <small class="text-muted">Status: <?php echo e($internalsCount); ?>/<?php echo e($totalItemCount); ?> Item Diproses</small>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered align-middle mb-0 small">
                                                        <thead class="table-secondary text-secondary">
                                                            <tr>
                                                                <th class="text-center" width="40px">#</th>
                                                                <th class="text-center" width="160px">Sub-PO / ID Item</th>
                                                                <th>Nama Item / Part Name</th>
                                                                <th class="text-center" width="100px">Qty</th>
                                                                <th class="text-center" width="130px">Harga Satuan</th>
                                                                <th class="text-center" width="120px">Status Item</th>
                                                                <th class="text-center" width="220px">Aksi Item</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $quotationItems = ($po->quotation && $po->quotation->items->count() > 0) ? $po->quotation->items : collect();
                                                                $internalsItems = $po->internals ?? collect();

                                                                // Jika Sales menambah item baru di PO Internal sehingga internals > quotation
                                                                $displayItems = ($internalsItems->count() > $quotationItems->count()) ? $internalsItems : $quotationItems;
                                                                $usedInternalIds = [];
                                                            ?>
                                                            <?php $__currentLoopData = $displayItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subIdx => $qItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php
                                                                    $qItemId = $qItem->id ?? null;
                                                                    $targetPoNo = $po->po_no . '-' . ($subIdx + 1);

                                                                    // 1. Match spesifik berdasarkan Sub-PO No (PO-xxxx-1, PO-xxxx-2, dst)
                                                                    $internalItem = $po->internals ? $po->internals->reject(fn($i) => in_array($i->id, $usedInternalIds))->firstWhere('po_no', $targetPoNo) : null;

                                                                    // 2. Fallback pencocokan berdasarkan nama item jika belum ditemukan via Sub-PO No
                                                                    if (!$internalItem && $po->internals && isset($qItem->item)) {
                                                                        $internalItem = $po->internals->reject(fn($i) => in_array($i->id, $usedInternalIds))->first(function($i) use ($qItem) {
                                                                            return strtolower(trim($i->item)) === strtolower(trim($qItem->item));
                                                                        });
                                                                    }

                                                                    if ($internalItem) {
                                                                        $usedInternalIds[] = $internalItem->id;
                                                                    }

                                                                    $itemContract  = $internalItem ? ($internalItem->contract ?? ($internalItem->contracts ? $internalItem->contracts->last() : null)) : null;
                                                                    if (!$itemContract && $po->contracts) {
                                                                        $itemContract = $po->contracts->first(function($c) use ($qItem, $targetPoNo) {
                                                                            return ($c->order_no === $targetPoNo) || (isset($c->part_name) && isset($qItem->item) && strtolower(trim($c->part_name)) === strtolower(trim($qItem->item)));
                                                                        });
                                                                    }

                                                                    $itemRawStatus = strtolower($itemContract ? $itemContract->status : ($internalItem ? 'created' : 'belum_diproses'));
                                                                    $isPendingAmandement = ($itemRawStatus == 'amandement_pending');
                                                                    $isApprovedAmandement = ($itemRawStatus == 'amandement' || $itemRawStatus == 'amandement_approved');
                                                                    $isRejectedAmandement = ($itemRawStatus == 'rejected');
                                                                ?>
                                                                <tr>
                                                                    <td class="text-center fw-bold"><?php echo e($subIdx + 1); ?></td>
                                                                    <td class="text-center">
                                                                        <span class="badge <?php echo e($internalItem ? 'bg-dark' : 'bg-secondary'); ?>">
                                                                            <?php echo e($internalItem->po_no ?? $targetPoNo); ?>

                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <strong class="text-dark"><?php echo e($internalItem->item ?? $qItem->item ?? '-'); ?></strong>
                                                                        <?php if($internalItem && ($internalItem->part_no || $internalItem->article_no)): ?>
                                                                            <br><small class="text-muted">Part No: <?php echo e($internalItem->part_no ?? $internalItem->article_no); ?></small>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td class="text-center fw-bold"><?php echo e(number_format($internalItem->qty ?? $qItem->qty ?? 1)); ?> pcs</td>
                                                                    <td class="text-center">Rp <?php echo e(number_format($internalItem->unit_price ?? $qItem->price ?? 0, 0, ',', '.')); ?></td>
                                                                    <td class="text-center">
                                                                        <?php
                                                                            $hasItemRejection = $itemContract && (!empty($itemContract->sales_reject_reason) || !empty($itemContract->quality_reject_reason) || !empty($itemContract->ppc_reject_reason) || !empty($itemContract->dev_engineering_reject_reason));
                                                                        ?>
                                                                        <?php if(in_array($itemRawStatus, ['production', 'done'])): ?>
                                                                            <span class="badge bg-light-success text-success"><i class="bi bi-gear-wide-connected me-1"></i>In Production</span>
                                                                        <?php elseif($hasItemRejection): ?>
                                                                            <span class="badge bg-light-danger text-danger"><i class="bi bi-exclamation-triangle me-1"></i>Perlu Revisi</span>
                                                                        <?php elseif($itemRawStatus == 'review'): ?>
                                                                            <span class="badge bg-light-warning text-warning"><i class="bi bi-search me-1"></i>Review</span>
                                                                        <?php elseif(in_array($itemRawStatus, ['contract', 'approved'])): ?>
                                                                            <span class="badge bg-light-info text-info"><i class="bi bi-file-earmark-check me-1"></i>Contract</span>
                                                                        <?php elseif(in_array($itemRawStatus, ['created', 'sent'])): ?>
                                                                            <span class="badge bg-light-primary text-primary"><i class="bi bi-check-circle me-1"></i>PO Internal OK</span>
                                                                        <?php elseif($itemRawStatus == 'amandement_pending'): ?>
                                                                            <span class="badge bg-light-warning text-warning"><i class="bi bi-clock-history me-1"></i>Amandemen Pending</span>
                                                                        <?php elseif($itemRawStatus == 'amandement'): ?>
                                                                            <span class="badge bg-light-danger text-danger"><i class="bi bi-exclamation-octagon me-1"></i>Amandemen Approved</span>
                                                                        <?php else: ?>
                                                                            <span class="badge bg-light text-secondary">Belum Diproses</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php if(auth()->user()->isAdmin() || (auth()->user()->isStaff() && auth()->user()->divisi == 'sales')): ?>
                                                                            <?php
                                                                                $isAmended = ($itemContract && $itemContract->amandement_no > 0) || str_contains(strtolower($po->status), 'amandemen') || ($internalItem && strtolower($internalItem->status) == 'amandement');
                                                                            ?>

                                                                            <?php if($isPendingAmandement): ?>
                                                                                
                                                                                <a href="<?php echo e(route('purchase-orders.approval-amandement')); ?>" class="btn btn-xs btn-warning text-dark fw-bold me-1 mb-1" title="Item ini sedang dalam pengajuan amandemen oleh Customer. Klik untuk review.">
                                                                                    <i class="bi bi-clock-history me-1"></i> Amandemen Pending
                                                                                </a>
                                                                                <button type="button" class="btn btn-xs btn-info text-white btn-show me-1 mb-1"
                                                                                    data-id="<?php echo e($po->id); ?>" <?php if($internalItem): ?> data-internal-id="<?php echo e($internalItem->id); ?>" <?php endif; ?> data-bs-toggle="modal"
                                                                                    data-bs-target="#previewModal">
                                                                                    <i class="bi bi-eye"></i> Detail
                                                                                </button>
                                                                            <?php elseif($itemRawStatus == 'amandement' || ($internalItem && $internalItem->status == 'amandement')): ?>
                                                                                
                                                                                <a href="<?php echo e(route('purchase-orders-internal.create', ['purchaseOrder' => $po->id, 'internal_id' => $internalItem->id ?? null, 'quotation_item_id' => $qItemId])); ?>"
                                                                                   class="btn btn-xs btn-warning text-dark fw-bold mb-1 me-1"
                                                                                   data-bs-toggle="tooltip" title="Amandemen item telah disetujui. Klik untuk memproses ulang data amandemen ke PO Internal.">
                                                                                    <i class="bi bi-arrow-repeat me-1"></i> Proses Internal (Amandemen)
                                                                                </a>
                                                                                <button type="button" class="btn btn-xs btn-info text-white btn-show me-1 mb-1"
                                                                                    data-id="<?php echo e($po->id); ?>" <?php if($internalItem): ?> data-internal-id="<?php echo e($internalItem->id); ?>" <?php endif; ?> data-bs-toggle="modal"
                                                                                    data-bs-target="#previewModal">
                                                                                    <i class="bi bi-eye"></i> Detail
                                                                                </button>
                                                                            <?php elseif($internalItem): ?>
                                                                                
                                                                                <?php if($itemContract && in_array($itemRawStatus, ['production', 'done'])): ?>
                                                                                    <a href="<?php echo e(route('contracts.show', $itemContract->id)); ?>" class="btn btn-xs btn-success text-white fw-bold me-1 mb-1" title="Kontrak telah 100% disetujui 4 Manager">
                                                                                        <i class="bi bi-check-circle-fill me-1"></i> In Production
                                                                                    </a>
                                                                                <?php elseif($itemContract && $hasItemRejection): ?>
                                                                                    <a href="<?php echo e(route('contracts.edit', $itemContract->id)); ?>" class="btn btn-xs btn-danger text-white fw-bold me-1 mb-1" title="Ada catatan revisi dari Manager. Klik untuk memperbaiki">
                                                                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Revisi Kontrak
                                                                                    </a>
                                                                                <?php elseif($itemContract && in_array($itemRawStatus, ['review', 'waiting_approval'])): ?>
                                                                                    <a href="<?php echo e(route('contracts.show', $itemContract->id)); ?>" class="btn btn-xs btn-warning text-dark fw-bold me-1 mb-1" title="Kontrak sedang di-review oleh 4 Manager">
                                                                                        <i class="bi bi-search me-1"></i> Review Kontrak
                                                                                    </a>
                                                                                <?php else: ?>
                                                                                    
                                                                                    <a href="<?php echo e(route('contracts.create', ['idPO' => $po->id, 'internal_id' => $internalItem->id])); ?>" 
                                                                                       class="btn btn-xs <?php echo e($isAmended ? 'btn-warning text-dark' : 'btn-success text-white'); ?> fw-bold me-1 mb-1" 
                                                                                       title="PO Internal sudah dibuat. Klik untuk membuat Contract Review Sheet">
                                                                                        <i class="bi bi-file-earmark-plus me-1"></i> <?php echo e($isAmended ? 'Buat Kontrak Amandemen' : 'Buat Kontrak'); ?>

                                                                                    </a>
                                                                                <?php endif; ?>

                                                                                <button type="button" class="btn btn-xs btn-info text-white btn-show me-1 mb-1"
                                                                                    data-id="<?php echo e($po->id); ?>" data-internal-id="<?php echo e($internalItem->id); ?>" data-bs-toggle="modal"
                                                                                    data-bs-target="#previewModal">
                                                                                    <i class="bi bi-eye"></i> Detail
                                                                                </button>
                                                                                <?php if(!in_array($itemRawStatus, ['production', 'done'])): ?>
                                                                                    <a href="<?php echo e(route('purchase-orders-internal.create', ['purchaseOrder' => $po->id, 'internal_id' => $internalItem->id])); ?>" 
                                                                                       class="btn btn-xs btn-outline-secondary mb-1" title="Edit PO Internal">
                                                                                        <i class="bi bi-pencil"></i> Edit PO Int
                                                                                    </a>
                                                                                <?php endif; ?>
                                                                            <?php else: ?>
                                                                                
                                                                                <a href="<?php echo e(route('purchase-orders-internal.create', ['purchaseOrder' => $po->id, 'quotation_item_id' => $qItemId])); ?>"
                                                                                   class="btn btn-xs btn-primary mb-1">
                                                                                    <i class="bi bi-gear-fill me-1"></i> Proses Internal
                                                                                </a>
                                                                                <button type="button" class="btn btn-xs btn-info text-white btn-show me-1 mb-1"
                                                                                    data-id="<?php echo e($po->id); ?>" data-bs-toggle="modal"
                                                                                    data-bs-target="#previewModal">
                                                                                    <i class="bi bi-eye"></i> Detail
                                                                                </button>
                                                                            <?php endif; ?>
                                                                        <?php else: ?>
                                                                            
                                                                            <button type="button" class="btn btn-xs btn-info text-white btn-show me-1 mb-1"
                                                                                data-id="<?php echo e($po->id); ?>" <?php if($internalItem): ?> data-internal-id="<?php echo e($internalItem->id); ?>" <?php endif; ?> data-bs-toggle="modal"
                                                                                data-bs-target="#previewModal">
                                                                                <i class="bi bi-eye"></i> Detail
                                                                            </button>

                                                                            <?php
                                                                                $isItemProduction = in_array(strtolower($itemContract->status ?? ''), ['production', 'done']) 
                                                                                    || in_array(strtolower($internalItem->status ?? ''), ['production', 'done']) 
                                                                                    || in_array($poStatus, ['production', 'done']);
                                                                                $itemAmendCount = (int) ($itemContract ? ($itemContract->amandement_no ?? 0) : 0);
                                                                                $maxLimit = \App\Services\SystemSettingService::maxAmendmentLimit();
                                                                                $isItemQuotaExceeded = ($itemAmendCount >= $maxLimit);
                                                                            ?>

                                                                            <?php if($isRejectedAmandement): ?>
                                                                                
                                                                                <button type="button" class="btn btn-xs btn-danger text-white fw-bold mb-1 me-1"
                                                                                        data-bs-toggle="modal" data-bs-target="#rejectReasonModal<?php echo e($po->id); ?>_<?php echo e($subIdx); ?>">
                                                                                    <i class="bi bi-x-circle me-1"></i> Ditolak (Lihat Alasan)
                                                                                </button>
                                                                            <?php elseif($isPendingAmandement): ?>
                                                                                
                                                                                <button class="btn btn-xs btn-secondary mb-1 fw-semibold" disabled title="Pengajuan amandemen sedang dalam peninjauan Sales & Manager">
                                                                                    <i class="bi bi-clock-history me-1"></i> Pending Review
                                                                                </button>
                                                                            <?php elseif($isApprovedAmandement): ?>
                                                                                
                                                                                <button class="btn btn-xs btn-success text-white mb-1 fw-semibold" disabled title="Amandemen telah disetujui dan sedang diproses Sales">
                                                                                    <i class="bi bi-check-circle me-1"></i> Disetujui
                                                                                </button>
                                                                            <?php elseif($isItemProduction): ?>
                                                                                
                                                                                <button class="btn btn-xs btn-secondary mb-1 fw-semibold" disabled title="Item ini sudah masuk tahap produksi (In Production) dan tidak dapat diamandemen lagi.">
                                                                                    <i class="bi bi-lock-fill me-1 text-warning"></i> In Production
                                                                                </button>
                                                                            <?php elseif($isItemQuotaExceeded): ?>
                                                                                
                                                                                <button class="btn btn-xs btn-secondary mb-1 fw-semibold" disabled title="Batas maksimal amandemen (<?php echo e($maxLimit); ?>x) telah tercapai untuk item ini.">
                                                                                    <i class="bi bi-slash-circle me-1 text-danger"></i> Amandemen Maks (<?php echo e($itemAmendCount); ?>/<?php echo e($maxLimit); ?>)
                                                                                </button>
                                                                            <?php elseif(!$internalItem || !$itemContract): ?>
                                                                                
                                                                                <button class="btn btn-xs btn-secondary mb-1 fw-semibold" disabled title="PO baru diterbitkan, menunggu Sales memproses PO Internal / Kontrak pertama kali">
                                                                                    <i class="bi bi-clock-history me-1"></i> Menunggu Review Sales
                                                                                </button>
                                                                            <?php else: ?>
                                                                                
                                                                                <a href="<?php echo e(route('purchase-orders.create-amandement', ['id' => $po->id, 'internal_id' => $internalItem->id ?? null])); ?>"
                                                                                   class="btn btn-xs btn-warning text-dark mb-1 fw-semibold"
                                                                                   data-bs-toggle="tooltip" title="Ajukan Amandemen Khusus Item Ini (Amandemen ke-<?php echo e($itemAmendCount + 1); ?>/<?php echo e($maxLimit); ?>)">
                                                                                    <i class="bi bi-pencil-square me-1"></i> Ajukan Amandemen
                                                                                </a>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        <?php if($pos->hasPages()): ?>
            <div class="card-footer bg-transparent border-0 py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted">
                        Menampilkan <?php echo e($pos->firstItem() ?? 0); ?>–<?php echo e($pos->lastItem() ?? 0); ?> dari <?php echo e($pos->total()); ?> data PO
                    </small>
                    <div>
                        <?php echo e($pos->appends(request()->query())->links('pagination::bootstrap-5')); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="modal fade" id="previewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="modalContent"></div>
            </div>
        </div>
        
        <div class="modal fade" id="updateStatusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="editModalContent"></div>
            </div>
        </div>

        
        <?php $__currentLoopData = $pos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $po): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($po->internals && $po->internals->count() > 0): ?>
                <?php $__currentLoopData = $po->internals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="modal fade" id="historyModalItem<?php echo e($item->id); ?>" tabindex="-1" aria-labelledby="historyModalLabel<?php echo e($item->id); ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-dark text-white">
                                    <h5 class="modal-title" id="historyModalLabel<?php echo e($item->id); ?>">
                                        <i class="bi bi-clock-history me-2"></i>Tracking History Amandemen - <?php echo e($item->po_no ?? ($po->po_no . '-' . $item->id)); ?> (<?php echo e($item->item); ?>)
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered align-middle">
                                            <thead class="table-secondary text-center">
                                                <tr>
                                                    <th width="15%">Amandemen</th>
                                                    <th width="45%">Alasan / Catatan Perubahan</th>
                                                    <th width="20%">Tanggal Diajukan</th>
                                                    <th width="20%">Status Review</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $itemHistories = $item->contracts ?? ($po->contracts ? $po->contracts->where('purchase_order_internal_id', $item->id) : []);
                                                ?>

                                                <?php $__empty_1 = true; $__currentLoopData = $itemHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <tr>
                                                        <td class="text-center fw-bold text-primary">
                                                            <?php echo e($history->amandement_no == 0 ? '0 (Asli)' : 'Ke-' . $history->amandement_no); ?>

                                                        </td>
                                                        <td><?php echo e($history->alasan_amandemen ?? 'Pembuatan kontrak review pertama oleh Sales.'); ?></td>
                                                        <td class="text-center small"><?php echo e($history->created_at ? $history->created_at->format('d-m-Y H:i') : '-'); ?></td>
                                                        <td class="text-center">
                                                            <?php $hStatus = strtolower($history->status); ?>
                                                            <?php if(in_array($hStatus, ['created', 'amandement_pending', 'amandement', 'review'])): ?>
                                                                <span class="badge bg-warning text-dark">Waiting Approval</span>
                                                            <?php elseif(in_array($hStatus, ['approved', 'contract'])): ?>
                                                                <span class="badge bg-success">Approved</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary"><?php echo e(ucfirst($history->status)); ?></span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">Belum ada data amandemen kontrak yang tercatat untuk item ini.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if(auth()->check() && auth()->user()->role == 'customer'): ?>
            <?php $__currentLoopData = $pos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $po): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $quotationItems = ($po->quotation && $po->quotation->items->count() > 0) ? $po->quotation->items : collect();
                    $internalsItems = $po->internals ?? collect();
                    $displayItems = ($internalsItems->count() > $quotationItems->count()) ? $internalsItems : $quotationItems;

                    // Cari kontrak penolakan untuk master PO
                    $firstInternal = $po->internals->first();
                    $masterContract = $firstInternal ? ($firstInternal->contract ?? ($firstInternal->contracts ? $firstInternal->contracts->last() : null)) : null;
                    if (!$masterContract && $po->contracts) {
                        $masterContract = $po->contracts->whereNotNull('alasan_penolakan')->sortByDesc('updated_at')->first()
                            ?? $po->contracts->where('status', 'rejected')->first()
                            ?? $po->contracts->last();
                    }

                    $reasonTextMaster = $masterContract->alasan_penolakan ?? null;
                    if (empty($reasonTextMaster) && $po->contracts) {
                        $cWithReason = $po->contracts->firstWhere('alasan_penolakan', '!=', null);
                        if ($cWithReason) {
                            $reasonTextMaster = $cWithReason->alasan_penolakan;
                        }
                    }
                ?>
                <div class="modal fade text-start" id="rejectReasonMasterModal<?php echo e($po->id); ?>" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title text-white"><i class="bi bi-exclamation-triangle-fill me-2"></i> Penolakan Amandemen PO</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-dark">
                                <div class="mb-3">
                                    <small class="text-muted d-block fw-bold">Nomor Purchase Order (PO):</small>
                                    <span class="badge bg-dark fs-6"><?php echo e($po->po_no); ?></span>
                                </div>

                                <?php if($masterContract && !empty($masterContract->alasan_amandemen)): ?>
                                    <div class="card border mb-3 p-3 bg-light rounded">
                                        <small class="text-muted d-block fw-bold mb-1"><i class="bi bi-chat-left-text me-1"></i> Alasan Amandemen Anda:</small>
                                        <span class="fst-italic text-dark">"<?php echo e($masterContract->alasan_amandemen); ?>"</span>
                                    </div>
                                <?php endif; ?>

                                <div class="card border border-danger mb-3 shadow-sm bg-light-danger" style="background-color: #fff5f5 !important;">
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-danger mb-2"><i class="bi bi-x-octagon-fill me-1"></i> Alasan Penolakan dari Staff Sales / Manajemen:</h6>
                                        <div class="p-3 bg-white rounded border border-danger text-danger fw-bold fs-6">
                                            "<?php echo e(!empty($reasonTextMaster) ? $reasonTextMaster : 'Pengajuan amandemen tidak dapat disetujui oleh Staff Sales saat ini.'); ?>"
                                        </div>
                                    </div>
                                </div>

                                <div class="p-3 bg-light rounded border mb-0">
                                    <p class="small text-dark mb-0">
                                        <i class="bi bi-info-circle-fill text-primary me-1"></i> <strong>Tujuan & Konfirmasi:</strong><br>
                                        Dengan mengeklik <strong>"Saya Mengerti (OK)"</strong>, Anda mengonfirmasi telah membaca alasan penolakan ini. Pengajuan yang ditolak ini tetap terhitung sebagai 1x pengajuan revisi. Anda masih dapat mengajukan amandemen perbaikan jika batas maksimal (2x) belum terpenuhi.
                                    </p>
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <form action="<?php echo e(route('purchase-orders.acknowledge-amandement', $po->id)); ?>" method="POST" id="ackMasterForm<?php echo e($po->id); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php if($firstInternal): ?>
                                        <input type="hidden" name="purchase_order_internal_id" value="<?php echo e($firstInternal->id); ?>">
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-secondary btn-sm me-1" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-danger btn-sm fw-bold">
                                        <i class="bi bi-check-circle me-1"></i> Saya Mengerti (OK)
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                
                <?php $__currentLoopData = $displayItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subIdx => $qItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $targetPoNo = $po->po_no . '-' . ($subIdx + 1);
                        $internalItem = $po->internals ? $po->internals->firstWhere('po_no', $targetPoNo) : null;
                        if (!$internalItem && $po->internals && $po->internals->values()->has($subIdx)) {
                            $internalItem = $po->internals->values()->get($subIdx);
                        }

                        $itemContract = $internalItem 
                            ? \App\Models\Contract::where('purchase_order_internal_id', $internalItem->id)->latest('id')->first()
                            : \App\Models\Contract::where('order_no', $targetPoNo)->latest('id')->first();

                        if (!$itemContract) {
                            $itemContract = \App\Models\Contract::where('order_no', $po->po_no)->latest('id')->first();
                        }

                        $reasonTextItem = ($itemContract && $itemContract->status === 'rejected') ? $itemContract->alasan_penolakan : null;
                    ?>

                    <div class="modal fade text-start" id="rejectReasonModal<?php echo e($po->id); ?>_<?php echo e($subIdx); ?>" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title text-white"><i class="bi bi-exclamation-triangle-fill me-2"></i> Penolakan Amandemen Item PO</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-dark">
                                    <div class="mb-3">
                                        <small class="text-muted d-block fw-bold">Nomor PO & Item Target:</small>
                                        <span class="badge bg-dark fs-6"><?php echo e($po->po_no); ?></span> - <strong class="fs-6 text-primary"><?php echo e($internalItem->item ?? $qItem->item ?? '-'); ?></strong>
                                    </div>

                                    <?php if($itemContract && !empty($itemContract->alasan_amandemen)): ?>
                                        <div class="card border mb-3 p-3 bg-light rounded">
                                            <small class="text-muted d-block fw-bold mb-1"><i class="bi bi-chat-left-text me-1"></i> Alasan Amandemen Anda:</small>
                                            <span class="fst-italic text-dark">"<?php echo e($itemContract->alasan_amandemen); ?>"</span>
                                        </div>
                                    <?php endif; ?>

                                    <div class="card border border-danger mb-3 shadow-sm bg-light-danger" style="background-color: #fff5f5 !important;">
                                        <div class="card-body p-3">
                                            <h6 class="fw-bold text-danger mb-2"><i class="bi bi-x-octagon-fill me-1"></i> Alasan Penolakan dari Staff Sales / Manajemen:</h6>
                                            <div class="p-3 bg-white rounded border border-danger text-danger fw-bold fs-6">
                                                "<?php echo e(!empty($reasonTextItem) ? $reasonTextItem : 'Pengajuan amandemen tidak dapat disetujui oleh Staff Sales saat ini.'); ?>"
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-3 bg-light rounded border mb-0">
                                        <p class="small text-dark mb-0">
                                            <i class="bi bi-info-circle-fill text-primary me-1"></i> <strong>Tujuan & Konfirmasi:</strong><br>
                                            Dengan mengeklik <strong>"Saya Mengerti (OK)"</strong>, Anda mengonfirmasi telah membaca alasan penolakan ini. Pengajuan yang ditolak ini tetap terhitung sebagai 1x pengajuan revisi. Anda masih dapat mengajukan amandemen perbaikan jika batas maksimal (2x) belum terpenuhi.
                                        </p>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light">
                                    <form action="<?php echo e(route('purchase-orders.acknowledge-amandement', $po->id)); ?>" method="POST" id="ackForm<?php echo e($po->id); ?>_<?php echo e($subIdx); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php if($internalItem): ?>
                                            <input type="hidden" name="purchase_order_internal_id" value="<?php echo e($internalItem->id); ?>">
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-secondary btn-sm me-1" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-danger btn-sm fw-bold">
                                            <i class="bi bi-check-circle me-1"></i> Saya Mengerti (OK)
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        // Global Helpers untuk Tracking Modal Item Focus & Show All
        window.showAllItemCards = function() {
            document.querySelectorAll('.item-detail-card-wrapper').forEach(function(card) {
                card.style.display = 'block';
            });
            const btn = document.getElementById('btn-show-all-cards');
            if (btn) btn.style.display = 'none';
            const subtitle = document.getElementById('detail-section-subtitle');
            if (subtitle) subtitle.textContent = 'Spesifikasi mendalam, alasan perubahan, dan berkas amandemen untuk seluruh item.';
        };

        window.focusItemCard = function(index) {
            const targetEl = document.getElementById('item-detail-card-' + index);
            if (targetEl) {
                targetEl.style.display = 'block';
                targetEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                targetEl.classList.add('border-primary', 'shadow-lg');
                targetEl.style.transition = 'all 0.3s ease';
                targetEl.style.boxShadow = '0 0 18px rgba(13, 110, 253, 0.45)';
                
                setTimeout(() => {
                    targetEl.classList.remove('shadow-lg');
                    targetEl.style.boxShadow = '';
                }, 2500);
            }
        };

        // Helper untuk inject HTML beserta eksekusi script di dalamnya
        function injectModalHtml(container, html) {
            container.innerHTML = html;
            container.querySelectorAll('script').forEach(oldScript => {
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                oldScript.parentNode.replaceChild(newScript, oldScript);
            });
            // Re-init tooltips inside injected content
            container.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
        }

        // Delegated Event Listener untuk Tombol Detail (.btn-show)
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-show');
            if (!btn) return;

            const modalShowContent = document.getElementById('modalContent');
            if (!modalShowContent) return;

            const id = btn.dataset.id;
            const internalId = btn.dataset.internalId;

            modalShowContent.innerHTML = `
                <div class="modal-body text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="text-muted small mt-2">Memuat rincian pesanan...</div>
                </div>
            `;

            let fetchUrl = `/purchase-orders/${id}`;
            if (internalId) {
                fetchUrl += `?internal_id=${internalId}`;
            }

            fetch(fetchUrl)
                .then(response => {
                    if (!response.ok) throw new Error('Gagal memuat status: ' + response.statusText);
                    return response.text();
                })
                .then(html => {
                    injectModalHtml(modalShowContent, html);
                })
                .catch(error => {
                    modalShowContent.innerHTML = `
                        <div class="modal-body text-danger text-center py-4">
                            <i class="bi bi-exclamation-octagon fs-2 d-block mb-2"></i>
                            Gagal memuat data Purchase Order.
                        </div>
                    `;
                    console.error('Error fetching PO detail:', error);
                });
        });

        // Delegated Event Listener untuk Tombol Edit (.btn-edit)
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-edit');
            if (!btn) return;

            const editModalContent = document.getElementById('editModalContent');
            if (!editModalContent) return;

            const id = btn.dataset.id;

            editModalContent.innerHTML = `
                <div class="modal-body text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="text-muted small mt-2">Memuat form edit...</div>
                </div>
            `;

            fetch(`/purchase-orders/${id}/edit`)
                .then(response => {
                    if (!response.ok) throw new Error('Gagal memuat status edit: ' + response.statusText);
                    return response.text();
                })
                .then(html => {
                    injectModalHtml(editModalContent, html);
                })
                .catch(error => {
                    editModalContent.innerHTML = `
                        <div class="modal-body text-danger text-center py-4">
                            <i class="bi bi-exclamation-octagon fs-2 d-block mb-2"></i>
                            Gagal memuat form edit.
                        </div>
                    `;
                    console.error('Error fetching PO edit:', error);
                });
        });
        
        document.addEventListener('DOMContentLoaded', function () {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/purchase-orders/index.blade.php ENDPATH**/ ?>