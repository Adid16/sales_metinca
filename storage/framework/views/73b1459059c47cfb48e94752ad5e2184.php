<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo e(asset('assets/extensions/simple-datatables/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/table-datatable.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app-dark.css')); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        /* ================= TABEL UTAMA (NORMAL TANPA SCROLL) ================= */
        /* ================= TABEL UTAMA (NORMAL TANPA SCROLL) ================= */
        .table-main-wrapper {
            width: 100% !important;
            overflow-x: visible !important;
        }

        .table-main {
            width: 100% !important;
            margin-bottom: 0 !important;
        }

        .table-main th, 
        .table-main td {
            vertical-align: middle !important;
            padding: 0.65rem 0.75rem !important;
            font-size: 0.875rem !important; /* Normal 14px */
        }

        /* ================= SUBTABLE CONTAINER (SCROLL KHUSUS SUB-ITEM) ================= */
        .subtable-container-cell {
            max-width: 0 !important; /* Mencegah tabel utama ikut melebar/scrolling */
            width: 100% !important;
            padding: 0 !important;
            border: none !important;
        }

        .subtable-box {
            background-color: #f2f5fc;
            padding: 10px 12px !important;
            border-left: 4px solid #435ebe !important; /* Garis biru tebal di kiri */
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .subtable-scroll-area {
            overflow-x: auto !important; /* Scroll horizontal khusus di sub-item */
            width: 100% !important;
            -webkit-overflow-scrolling: touch;
        }

        .table-detail {
            min-width: 1080px !important; /* Lebar minimum nyaman agar tombol aksi & kolom lapang */
            width: 100% !important;
            font-size: 0.78rem !important;
            background-color: #ffffff;
            margin-bottom: 0 !important;
        }

        .table-detail th {
            background-color: #dbe5ff !important; /* Header soft blue */
            color: #2b3a67 !important;
            font-weight: 700 !important;
            padding: 8px 10px !important;
            border-bottom: 1px solid #c5d4fb !important;
            vertical-align: middle !important;
            white-space: nowrap !important;
            font-size: 0.78rem !important;
        }

        .table-detail td {
            padding: 8px 10px !important;
            line-height: 1.35 !important;
            vertical-align: middle !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        /* Badge & Tombol Akses di Subtable (Lega & Tidak Dempet) */
        .badge-pricelist {
            background-color: #e6f4ea !important;
            color: #137333 !important;
            border: 1px solid #a8dab5 !important;
            font-size: 0.72rem !important;
            padding: 4px 8px !important;
            border-radius: 4px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            white-space: nowrap;
        }

        .btn-add-pricelist {
            background-color: #198754 !important;
            color: #ffffff !important;
            font-size: 0.72rem !important;
            padding: 4px 8px !important;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            border: none;
            white-space: nowrap;
        }

        .btn-add-pricelist:hover {
            background-color: #157347 !important;
            color: #ffffff !important;
        }

        .btn-buat-kontrak {
            background-color: #435ebe !important;
            color: #ffffff !important;
            font-size: 0.72rem !important;
            padding: 4px 8px !important;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            border: none;
            white-space: nowrap;
        }

        .btn-buat-kontrak:hover {
            background-color: #324796 !important;
            color: #ffffff !important;
        }

        .badge-kontrak-dibuat {
            background-color: #e2e3e5 !important;
            color: #41464b !important;
            border: 1px solid #c6c7c8 !important;
            font-size: 0.72rem !important;
            padding: 4px 8px !important;
            border-radius: 4px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            white-space: nowrap;
        }

        .btn-collapse-toggle .bi-chevron-down {
            transition: transform 0.2s ease-in-out;
        }
        .btn-collapse-toggle[aria-expanded="true"] .bi-chevron-down {
            transform: rotate(180deg);
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="card shadow-sm mb-3" style="border-left: 4px solid #17a2b8;">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-md-8">
                <label class="form-label fw-bold text-info mb-1">Cek Ketersediaan Barang (Database Pricelist / QC)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" id="search_article" class="form-control" placeholder="Ketik Nomor Artikel (Contoh: ART-001) lalu tekan Enter..." autocomplete="off">
                    <button class="btn btn-info text-white" type="button" id="btn_search">Cari Data</button>
                </div>
                <small class="text-muted mt-1 d-block">* Masukkan kode artikel untuk melihat informasi spesifikasi teknis kartu master QC.</small>
            </div>
            
            <div class="col-md-4 text-center mt-3 mt-md-0 d-none" id="qc_action_area">
                <p class="text-danger small fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill"></i> Data Tidak Ditemukan!</p>
                <button type="button" class="btn btn-sm btn-danger" id="btn_send_qc" data-bs-toggle="tooltip" title="Kirim notifikasi ke QC untuk melengkapi master data">
                    <i class="bi bi-send-fill"></i> Send to QC
                </button>
            </div>
        </div>
    </div>
</div>


<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary py-3">
        <h5 class="mb-0 fw-bold text-white">
            <i class="bi bi-file-earmark-richtext-fill me-2"></i>Purchase Order Internal
        </h5>
    </div>

    <div class="card-body">

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-1"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        
        <form class="row g-2 align-items-center mb-3 mt-1" method="GET">
            <div class="col-md-3">
                <div class="d-flex align-items-center gap-1">
                    <label class="form-label small mb-0 text-nowrap">From : </label>
                    <input type="date" name="start_date" class="form-control form-control-sm" value="<?php echo e($filters['start_date'] ?? ''); ?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center gap-1">
                    <label class="form-label small mb-0 text-nowrap">To : </label>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="<?php echo e($filters['end_date'] ?? ''); ?>">
                </div>
            </div>
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm"
                    placeholder="Cari PO No / Customer / Quotation..."
                    value="<?php echo e($filters['search'] ?? ''); ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="<?php echo e(route('purchase-orders-internal.index')); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>

        
        <div class="table-responsive">
            <table class="table table-hover text-nowrap align-middle table-main mb-0" id="table-po-internal">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 45px;">No</th>
                        <th class="text-center">Req Id</th>
                        <th class="text-center">Quotation No</th>
                        <th class="text-center">Purchase Order No</th>
                        <th>Customer</th>
                        <th class="text-center">Sales PIC</th>
                        <th class="text-center">Items</th>
                        <th class="text-center">Delivery Date</th>
                        <th class="text-center">Entry Date</th>
                        <th class="text-center" style="width: 90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody style="color:#212529;">
                    <?php
                        $poList = isset($pos) ? $pos : (isset($items) ? $items->groupBy('purchase_order_id') : collect());
                    ?>

                    <?php $__empty_1 = true; $__currentLoopData = $poList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $poData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $po = isset($pos) ? $poData : ($poData->first()->purchaseOrder ?? null);
                            $internals = isset($pos) ? $po->internals : $poData;
                            $poId = $po->id ?? $key;
                        ?>

                        <!-- PARENT ROW -->
                        <tr>
                            <td class="text-center fw-semibold text-muted"><?php echo e($loop->iteration); ?></td>
                            <td class="text-center">
                                <span class="badge bg-primary px-2 py-1"><?php echo e($po->quotation->request_id ?? $po->request_id ?? '-'); ?></span>
                            </td>
                            <td class="text-center fw-semibold text-secondary">
                                <?php echo e($po->quotation->quotation_no ?? '-'); ?>

                            </td>
                            <td class="text-center fw-bold text-dark">
                                <?php echo e($po->po_no ?? '-'); ?>

                            </td>
                            <td class="fw-semibold">
                                <?php echo e($po->customer->name ?? $po->quotation->customer->name ?? '-'); ?>

                            </td>
                            <td class="text-center">
                                <?php if($po->sales_pic): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                        <i class="bi bi-person-fill me-1"></i><?php echo e($po->sales_pic->name); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary px-2 py-1" style="font-size: 11px;">
                                    <?php echo e($internals->count()); ?> item
                                </span>
                            </td>
                            <td class="text-center fw-semibold text-danger">
                                <?php echo e(isset($po->delivery_request) ? \Carbon\Carbon::parse($po->delivery_request)->format('d M Y') : '-'); ?>

                            </td>
                            <td class="text-center text-muted">
                                <?php echo e($po->created_at ? $po->created_at->format('d M Y') : '-'); ?>

                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-1">
                                    <button type="button" class="btn btn-sm btn-info text-white btn-show shadow-sm" data-id="<?php echo e($poId); ?>" data-bs-toggle="modal" data-bs-target="#previewModal" title="Detail PO">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light border btn-collapse-toggle shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-po-<?php echo e($poId); ?>" aria-expanded="false" title="Pilih item untuk buat kontrak">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- CHILD ROW (EXPANDABLE SUB-TABLE LEGA & SCROLLABLE SUB-ITEM) -->
                        <tr class="p-0 border-0">
                            <td colspan="10" class="subtable-container-cell">
                                <div class="collapse subtable-box" id="collapse-po-<?php echo e($poId); ?>">
                                    <div class="subtable-scroll-area">
                                        <table class="table table-bordered align-middle table-detail shadow-sm">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 35px;">#</th>
                                                    <th style="min-width: 110px;">Article</th>
                                                    <th style="min-width: 110px;">Part No</th>
                                                    <th style="min-width: 200px;">Item / Part Name</th>
                                                    <th style="min-width: 130px;">Material</th>
                                                    <th style="min-width: 190px;">Spesifikasi</th>
                                                    <th class="text-center text-nowrap" style="min-width: 60px;">Qty</th>
                                                    <th class="text-end text-nowrap" style="min-width: 110px;">Subtotal</th>
                                                    <th class="text-center text-nowrap" style="min-width: 210px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__empty_2 = true; $__currentLoopData = $internals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $internal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                    <?php
                                                        $articleObj = null;
                                                        if (!empty($internal->article)) {
                                                            $articleObj = \App\Models\Article::where('article_no', $internal->article)
                                                                ->orWhere('internal_part_no', $internal->article)
                                                                ->first();
                                                        }
                                                        if (!$articleObj && !empty($internal->part_no) && $internal->part_no !== '-') {
                                                            $articleObj = \App\Models\Article::where('internal_part_no', $internal->part_no)
                                                                ->orWhere('article_no', $internal->part_no)
                                                                ->first();
                                                        }
                                                        if (!$articleObj && !empty($internal->item)) {
                                                            $articleObj = \App\Models\Article::where('part_name', $internal->item)->first();
                                                        }
                                                        $hasPricelist = !empty($articleObj);

                                                        // CEK KONTRAK SPESIFIK UNTUK ITEM INTERNAL INI
                                                        $latestContract = \App\Models\Contract::where('purchase_order_internal_id', $internal->id)
                                                            ->orderByDesc('amandement_no')
                                                            ->first();
                                                        
                                                        $itemStatus = $latestContract ? strtolower($latestContract->status) : 'none';

                                                        // Tentukan nilai Article dan Part No secara akurat
                                                        $displayArticle = (!empty($internal->article) && $internal->article !== '-')
                                                            ? $internal->article
                                                            : ($articleObj->article_no ?? '-');

                                                        $displayPartNo = (!empty($latestContract->part_no) && $latestContract->part_no !== '-')
                                                            ? $latestContract->part_no
                                                            : (!empty($articleObj->internal_part_no) && $articleObj->internal_part_no !== '-'
                                                                ? $articleObj->internal_part_no
                                                                : (!empty($articleObj->part_number) && $articleObj->part_number !== '-'
                                                                    ? $articleObj->part_number
                                                                    : (!empty($internal->part_no) && $internal->part_no !== '-'
                                                                        ? $internal->part_no
                                                                        : '-')));
                                                    ?>
                                                    <tr>
                                                        <td class="text-center text-muted fw-semibold"><?php echo e($loop->iteration); ?></td>
                                                        <td class="text-secondary fw-semibold">
                                                            <?php echo e($displayArticle); ?>

                                                        </td>
                                                        <td class="text-secondary fw-semibold">
                                                            <?php echo e($displayPartNo); ?>

                                                        </td>
                                                        <td class="fw-bold text-dark">
                                                            <div><?php echo e($internal->item); ?></div>
                                                            <?php if($itemStatus == 'amandement_pending'): ?>
                                                                <div class="mt-1">
                                                                    <span class="badge bg-warning text-dark" style="font-size: 10px;" title="Pengajuan amandemen item ini sedang dalam peninjauan Sales">
                                                                        <i class="bi bi-clock-history me-1"></i> Pending Amandemen
                                                                    </span>
                                                                </div>
                                                            <?php elseif($itemStatus == 'rejected'): ?>
                                                                <div class="mt-1">
                                                                    <span class="badge bg-danger text-white" style="font-size: 10px;" title="Pengajuan amandemen item ini ditolak oleh Sales; Kontrak sebelumnya tetap berlaku">
                                                                        <i class="bi bi-x-circle me-1"></i> Amandemen Ditolak
                                                                    </span>
                                                                </div>
                                                            <?php elseif($latestContract && $latestContract->amandement_no > 0): ?>
                                                                <div class="mt-1">
                                                                    <span class="badge bg-danger text-white" style="font-size: 10px;" title="Item ini telah mengalami amandemen spesifikasi/qty">
                                                                        <i class="bi bi-pencil-square me-1"></i> Amandemen #<?php echo e($latestContract->amandement_no); ?>

                                                                    </span>
                                                                </div>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-muted">
                                                            <?php echo e($internal->material ?? '-'); ?>

                                                        </td>
                                                        <td class="text-muted" style="line-height: 1.35;">
                                                            <?php if(!empty($internal->spesifikasi)): ?>
                                                                <?php $__currentLoopData = explode('|', $internal->spesifikasi); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $specPart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php if(trim($specPart)): ?>
                                                                        <div><?php echo e(trim($specPart)); ?></div>
                                                                    <?php endif; ?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php else: ?>
                                                                -
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center fw-bold text-nowrap">
                                                            <?php echo e(number_format($internal->qty)); ?>

                                                        </td>
                                                        <td class="text-end fw-semibold text-success text-nowrap">
                                                            Rp <?php echo e(number_format($internal->subtotal ?? ($internal->price * $internal->qty), 0, ',', '.')); ?>

                                                        </td>

                                                        
                                                        <td class="text-center text-nowrap">
                                                            <div class="d-flex flex-row align-items-center justify-content-center gap-2 text-nowrap">
                                                                
                                                                <?php
                                                                    $salesPic = $po->sales_pic;
                                                                    $isPicOrAdmin = auth()->user()->isAdmin() || (auth()->user()->isManager() && auth()->user()->divisi == 'sales') || (auth()->user()->isStaff() && auth()->user()->divisi == 'sales' && (!$salesPic || $salesPic->id === auth()->id()));
                                                                ?>

                                                                
                                                                <?php if($hasPricelist): ?>
                                                                    <span class="badge-pricelist">
                                                                        <i class="bi bi-check-circle-fill"></i> Sudah di Pricelist
                                                                    </span>
                                                                <?php elseif($isPicOrAdmin): ?>
                                                                    <a href="<?php echo e(route('articles.create', [
                                                                        'article_no'  => $internal->article ?? '',
                                                                        'part_number' => $internal->part_no ?? ($internal->article ?? ''),
                                                                        'part_name'   => $internal->item,
                                                                        'material'    => $internal->material ?? '',
                                                                        'price'       => (float)($internal->unit_price ?? $internal->price ?? 0)
                                                                    ])); ?>" class="btn-add-pricelist" title="Tambah ke Master Pricelist">
                                                                        <i class="bi bi-plus-circle-fill"></i> + Pricelist
                                                                    </a>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary text-white" style="font-size: 0.72rem; padding: 4px 8px;">
                                                                        <i class="bi bi-person-lock me-1"></i> Non-PIC
                                                                    </span>
                                                                <?php endif; ?>

                                                                
                                                                <?php if($itemStatus == 'amandement_pending'): ?>
                                                                    
                                                                    <span class="badge-kontrak-dibuat" style="background-color: #fff3cd !important; color: #664d03 !important; border: 1px solid #ffecb5 !important;" title="Pengajuan amandemen item ini sedang dalam peninjauan / persetujuan Sales">
                                                                        <i class="bi bi-clock-history me-1"></i> Pending Amandemen
                                                                    </span>
                                                                <?php elseif($itemStatus == 'amandement'): ?>
                                                                    
                                                                    <?php if($isPicOrAdmin): ?>
                                                                        <a href="<?php echo e(route('purchase-orders-internal.create', ['purchaseOrder' => $poId, 'internal_id' => $internal->id])); ?>" 
                                                                           class="btn-buat-kontrak" style="background-color: #ffc107 !important; color: #000 !important; font-weight: 700;" title="Amandemen telah disetujui. Harap proses ulang data amandemen ke PO Internal terlebih dahulu sebelum membuat kontrak baru">
                                                                            <i class="bi bi-arrow-repeat me-1"></i> Proses Amandemen
                                                                        </a>
                                                                    <?php else: ?>
                                                                        <span class="badge-kontrak-dibuat" title="Menunggu proses Sales PIC">
                                                                            <i class="bi bi-clock me-1"></i> Amandemen
                                                                        </span>
                                                                    <?php endif; ?>
                                                                <?php elseif(!$latestContract || $itemStatus == 'created'): ?>
                                                                    
                                                                    <?php if($isPicOrAdmin): ?>
                                                                        <a href="<?php echo e(route('purchase-orders.create-contract', ['idPO' => $poId, 'internal_id' => $internal->id])); ?>" 
                                                                           class="btn-buat-kontrak" title="Buat Lembar Review Kontrak Untuk Item Ini">
                                                                            <i class="bi bi-briefcase-fill"></i> Buat Kontrak
                                                                        </a>
                                                                    <?php else: ?>
                                                                        <span class="badge-kontrak-dibuat" title="Hanya Sales PIC (<?php echo e($salesPic->name ?? 'Sales PIC'); ?>) yang berhak membuat kontrak">
                                                                            <i class="bi bi-person-lock me-1"></i> Sales PIC: <?php echo e($salesPic->name ?? 'PIC'); ?>

                                                                        </span>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    
                                                                    <span class="badge-kontrak-dibuat" title="Kontrak untuk item ini telah dibuat dan sedang diproses">
                                                                        <i class="bi bi-check-circle-fill text-success me-1"></i> Sudah Dibuat
                                                                    </span>
                                                                <?php endif; ?>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                    <tr>
                                                        <td colspan="9" class="text-center text-muted fst-italic py-2">
                                                            Belum ada rincian item internal.
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted fst-italic py-4">
                                <i class="bi bi-folder-x fs-4 d-block mb-1"></i> Belum ada data PO Internal.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if(isset($items) && method_exists($items, 'links')): ?>
            <div class="d-flex justify-content-between align-items-center mt-3 px-2 flex-wrap gap-2">
                <small class="text-muted">
                    Menampilkan <strong><?php echo e($items->firstItem() ?? 0); ?></strong> sampai <strong><?php echo e($items->lastItem() ?? 0); ?></strong> dari <strong><?php echo e($items->total()); ?></strong> entries
                </small>
                <div>
                    <?php echo e($items->appends($filters ?? [])->links('pagination::bootstrap-5')); ?>

                </div>
            </div>
        <?php elseif(isset($pos) && method_exists($pos, 'links')): ?>
            <div class="d-flex justify-content-between align-items-center mt-3 px-2 flex-wrap gap-2">
                <small class="text-muted">
                    Menampilkan <strong><?php echo e($pos->firstItem() ?? 0); ?></strong> sampai <strong><?php echo e($pos->lastItem() ?? 0); ?></strong> dari <strong><?php echo e($pos->total()); ?></strong> entries
                </small>
                <div>
                    <?php echo e($pos->appends($filters ?? [])->links('pagination::bootstrap-5')); ?>

                </div>
            </div>
        <?php endif; ?>
    </div>
</div>


<div class="modal fade" id="previewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" id="modalContent"></div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search_article');
        const btnSearch   = document.getElementById('btn_search');
        const qcArea      = document.getElementById('qc_action_area');
        const btnSendQC   = document.getElementById('btn_send_qc');

        function performQuickSearch() {
            const article = searchInput.value.trim();
            if(article === '') return;

            Swal.fire({ title: 'Mencari Artikel...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});

            fetch(`/articles/requirements/${encodeURIComponent(article)}`)
                .then(response => {
                    if (!response.ok) throw new Error('Not found');
                    return response.json();
                })
                .then(res => {
                    if(res.success && res.data) {
                        qcArea.classList.add('d-none');
                        
                        let specText = [];
                        if (res.data.drawing_no) specText.push(`<b>Drawing No:</b> ${res.data.drawing_no}`);
                        if (res.data.berat) specText.push(`<b>Berat:</b> ${res.data.berat} Kg`);

                        Swal.fire({
                            icon: 'success',
                            title: 'Data Card Master Terfetch!',
                            html: `
                                <div class="text-start border p-3 rounded bg-light mt-2 small" style="line-height: 1.6;">
                                    <div><b>Nama Item:</b> ${res.data.part_name ?? '-'}</div>
                                    <div><b>Material:</b> ${res.data.material ?? '-'}</div>
                                    <div>${specText.join('<br>')}</div>
                                    <div class="text-success fw-bold mt-1"><b>Master Price:</b> Rp ${(res.data.total_price ?? res.data.price ?? 0).toLocaleString('id-ID')}</div>
                                </div>
                            `,
                            confirmButtonText: 'Tutup Tinjauan'
                        });
                    }
                })
                .catch(() => {
                    qcArea.classList.remove('d-none');
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Data Tidak Ditemukan!', 
                        text: 'Nomor Artikel tidak terdaftar di database master. Silakan ajukan penambahan data ke tim QC melalui tombol Send to QC di atas.', 
                        confirmButtonText: 'Mengerti' 
                    });
                });
        }

        btnSearch?.addEventListener('click', performQuickSearch);
        searchInput?.addEventListener('keypress', function(e) {
            if(e.key === 'Enter') { e.preventDefault(); performQuickSearch(); }
        });

        btnSendQC?.addEventListener('click', function() {
            const article = searchInput.value.trim();
            Swal.fire({
                title: 'Kirim Permintaan QC?',
                text: `Meminta QC untuk membuat spesifikasi barang: ${article.toUpperCase()}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Terkirim!', 'Permintaan penambahan master artikel telah diteruskan ke QC.', 'success');
                    qcArea.classList.add('d-none');
                    searchInput.value = '';
                }
            });
        });

        // Modal Show Detail Fetch dengan Delegated Listener & Script Execution
        function injectModalHtml(container, html) {
            container.innerHTML = html;
            container.querySelectorAll('script').forEach(oldScript => {
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                oldScript.parentNode.replaceChild(newScript, oldScript);
            });
            container.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
        }

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
                            Gagal memuat data detail
                        </div>
                    `;
                    console.error(error);
                });
        });

        // Tooltip Initialization
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
    });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/purchase-orders-internal/index.blade.php ENDPATH**/ ?>