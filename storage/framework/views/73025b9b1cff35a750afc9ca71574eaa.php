<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works'); ?>
 
<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app-dark.css')); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<?php $__env->stopPush(); ?>
 
<?php $__env->startSection('content'); ?>
 

<div class="card shadow-sm mb-3">
    <div class="card-header d-flex justify-content-between align-items-center py-3 
        <?php echo e($purchaseOrder->internals->count() > 0 ? 'bg-warning' : 'bg-primary'); ?>">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-file-earmark-plus-fill me-2"
                style="color: <?php echo e($purchaseOrder->internals->count() > 0 ? '#000' : '#fff'); ?> !important;"></i>
            <span style="color: <?php echo e($purchaseOrder->internals->count() > 0 ? '#000' : '#fff'); ?> !important;">
                <?php echo e($purchaseOrder->internals->count() > 0 ? 'Edit' : 'Input'); ?> PO Internal
            </span>
        </h5>
    </div>
    <div class="card-body py-3 px-4">
        <div class="row g-2">
            <div class="col-md-3">
                <small class="text-muted d-block">PO No (External)</small>
                <span class="fw-bold"><?php echo e($purchaseOrder->po_no ?? '-'); ?></span>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">PO No (Internal Item)</small>
                <span class="fw-bold text-primary"><?php echo e($itemPoNo ?? $purchaseOrder->po_no ?? '-'); ?></span>
            </div>
            <div class="col-md-2">
                <small class="text-muted d-block">Quotation No</small>
                <span class="fw-semibold"><?php echo e($purchaseOrder->quotation->quotation_no ?? '-'); ?></span>
            </div>
            <div class="col-md-2">
                <small class="text-muted d-block">Customer</small>
                <span><?php echo e($purchaseOrder->customer->name ?? '-'); ?></span>
            </div>
            <div class="col-md-2">
                <small class="text-muted d-block">Delivery Request</small>
                <span class="text-danger fw-semibold">
                    <?php echo e($purchaseOrder->delivery_request
                        ? \Carbon\Carbon::parse($purchaseOrder->delivery_request)->format('d M Y')
                        : '-'); ?>

                </span>
            </div>
        </div>
    </div>
</div>
 
<?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


<?php if(isset($contract) && ($contract->alasan_amandemen || $contract->catatan_sales)): ?>
    <div class="alert alert-warning border-warning shadow-sm d-flex align-items-start mb-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning"></i>
        <div class="w-100">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="alert-heading fw-bold mb-1 text-dark">
                    <i class="bi bi-pencil-square me-1"></i> Catatan Amandemen Item (Revisi #<?php echo e($contract->amandement_no); ?>)
                </h6>
                <span class="badge bg-warning text-dark">Amandemen Active</span>
            </div>
            
            <?php if($contract->alasan_amandemen): ?>
                <p class="mb-1 small text-dark">
                    <strong>Alasan Amandemen Customer:</strong> <?php echo e($contract->alasan_amandemen); ?>

                </p>
            <?php endif; ?>
            
            <?php if($contract->catatan_sales): ?>
                <p class="mb-0 small text-dark">
                    <strong>Catatan Tim Sales:</strong> <?php echo e($contract->catatan_sales); ?>

                </p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
 

<div class="card shadow-sm mb-3">
    <div class="card-header py-2" style="background:#e9ecef;">
        <h6 class="mb-0 fw-bold text-uppercase" style="font-size:0.78rem; letter-spacing:1px; color:#6c757d;">
            <i class="bi bi-paperclip me-1"></i>Dokumen PO dari Customer (Terbaru)
        </h6>
    </div>
    <div class="card-body p-2">
        <?php
            $attachments = [];
            $rawAttr = $purchaseOrder->attachment;
            
            if (is_array($rawAttr)) {
                $attachments = $rawAttr;
            } elseif (is_string($rawAttr)) {
                $decoded = json_decode($rawAttr, true);
                if (is_array($decoded)) {
                    $attachments = $decoded;
                } elseif (str_contains($rawAttr, ',')) {
                    $attachments = explode(',', $rawAttr);
                } elseif (!empty($rawAttr)) {
                    $attachments = [$rawAttr];
                }
            }

            $latestFile = count($attachments) > 0 ? end($attachments) : null;
        ?>

        <?php if($latestFile): ?>
            <?php 
                $cleanFile = trim(trim($latestFile), '"\''); 
            ?>
            
            <?php if(!empty($cleanFile)): ?>
                <div class="mb-2">
                    <a href="<?php echo e(asset('storage/uploads/' . $cleanFile)); ?>"
                        target="_blank" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Buka File Terbaru: <?php echo e(basename($cleanFile)); ?>

                    </a>
                </div>
                <iframe src="<?php echo e(asset('storage/uploads/' . $cleanFile)); ?>"
                    width="100%" height="400px"
                    style="border:1px solid #ccc;" class="rounded"></iframe>
            <?php endif; ?>
        <?php else: ?>
            <div class="p-2 border rounded bg-light text-center">
                <small class="text-muted"><i class="bi bi-exclamation-circle me-1"></i> File attachment tidak ditemukan.</small>
            </div>
        <?php endif; ?>
    </div>
</div>


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
                <small class="text-muted mt-1 d-block">* Menarik data otomatis ke kolom "Item #1" di form bawah. Anda juga bisa langsung mengetik kode artikel di dalam kolom baris item mana pun lalu tekan Enter.</small>
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
    <div class="card-header py-2" style="background:#e9ecef;">
        <h6 class="mb-0 fw-bold text-uppercase" style="font-size:0.78rem; letter-spacing:1px; color:#0d6efd;">
            <i class="bi bi-list-check me-1"></i>Detail Item PO Internal
            <small class="text-muted fw-normal text-lowercase ms-2">— input berdasarkan dokumen di atas</small>
        </h6>
    </div>
    <div class="card-body mt-2">
 
        <?php
            $isEdit = isset($selectedItem) || ($purchaseOrder->internals->count() > 0 && !$quotationItemId);
            $action = $isEdit
                ? route('purchase-orders-internal.update', $purchaseOrder->id)
                : route('purchase-orders-internal.store', $purchaseOrder->id);
        ?>
 
        <form action="<?php echo e($action); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php if($isEdit): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
            
            
            <input type="hidden" name="purchase_order_id" value="<?php echo e($purchaseOrder->id); ?>">
            <?php if(isset($selectedItem)): ?>
                <input type="hidden" name="purchase_order_internal_id" value="<?php echo e($selectedItem->id); ?>">
            <?php endif; ?>
 
            <div id="item-body">
                <?php
                    if ($selectedItem) {
                        $itemsToEdit = collect([$selectedItem]);
                    } elseif ($quotationItemId) {
                        $itemsToEdit = collect();
                    } else {
                        $itemsToEdit = $purchaseOrder->internals;
                    }
                ?>
                
                <?php $__empty_1 = true; $__currentLoopData = $itemsToEdit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="border rounded p-3 mb-3 item-row">
                    <input type="hidden" name="internal_id[]" value="<?php echo e($item->id); ?>">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <span class="fw-bold text-secondary">Item #<span class="row-no"><?php echo e($index + 1); ?></span></span>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove"><i class="bi bi-trash"></i> Hapus</button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">CUSTOMER ORDER NO</label>
                            <input type="text" name="po_no[]" class="form-control form-control-sm" value="<?php echo e($item->po_no ?? $itemPoNo ?? $purchaseOrder->po_no); ?>" placeholder="No PO">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small text-dark"><i class="bi bi-keyboard text-info"></i> Article (Tekan Enter)</label>
                            <input type="text" name="article[]" class="form-control form-control-sm form-article text-uppercase fw-semibold" value="<?php echo e($item->article ?? ''); ?>" placeholder="Ketik Kode Artikel lalu tekan Enter">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Nama Item <span class="text-danger">*</span></label>
                            <input type="text" name="item[]" class="form-control form-control-sm form-item" value="<?php echo e($item->item); ?>" placeholder="Nama item" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Material</label>
                            <input type="text" name="material[]" class="form-control form-control-sm form-material" value="<?php echo e($item->material); ?>" placeholder="Material">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Spesifikasi (Drawing/Berat)</label>
                            <input type="text" name="spesifikasi[]" class="form-control form-control-sm form-spesifikasi" value="<?php echo e($item->spesifikasi); ?>" placeholder="Spesifikasi teknis">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Qty <span class="text-danger">*</span></label>
                            <input type="number" name="qty[]" class="form-control form-control-sm qty-input" value="<?php echo e($item->qty); ?>" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Unit Price <span class="text-danger">*</span></label>
                            <input type="number" name="unit_price[]" class="form-control form-control-sm price-input" value="<?php echo e($item->unit_price); ?>" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Subtotal</label>
                            <input type="text" class="form-control form-control-sm subtotal-cell bg-light fw-semibold"
                                value="Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Delivery Date</label>
                            <input type="date" name="delivery_date[]" class="form-control form-control-sm" value="<?php echo e($item->delivery_date?->format('Y-m-d')); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Supplier/Vendor</label>
                            <input type="text" name="supplier[]" class="form-control form-control-sm" value="<?php echo e($item->supplier); ?>" placeholder="Supplier/Vendor">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">PIC Buyer</label>
                            <input type="text" name="pic_buyer[]" class="form-control form-control-sm" value="<?php echo e($item->pic_buyer); ?>" placeholder="Nama PIC">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">Perusahaan Buyer</label>
                            <input type="text" name="company_buyer[]" class="form-control form-control-sm" value="<?php echo e($item->company_buyer); ?>" placeholder="Nama perusahaan">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label mb-1 fw-semibold small">Notes</label>
                            <input type="text" name="notes[]" class="form-control form-control-sm" value="<?php echo e($item->notes); ?>" placeholder="Catatan tambahan">
                        </div>
                    </div>
                </div>
                
                
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <?php
                        $qItemsToInput = $qItem ? collect([$qItem]) : ($purchaseOrder->quotation?->items ?? collect());
                    ?>
                    <?php $__currentLoopData = $qItemsToInput; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qIndex => $qItemRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border rounded p-3 mb-3 item-row" style="border-left: 4px solid #0d6efd !important;">
                        <input type="hidden" name="internal_id[]" value="">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-primary">Item #<span class="row-no"><?php echo e(isset($qItemIndex) ? $qItemIndex : ($qIndex + 1)); ?></span></span>
                            <button type="button" class="btn btn-sm btn-danger btn-remove"><i class="bi bi-trash me-1"></i>Hapus</button>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">No PO</label>
                                <input type="text" name="po_no[]" class="form-control form-control-sm" value="<?php echo e($itemPoNo ?? $purchaseOrder->po_no ?? ''); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-dark"><i class="bi bi-keyboard text-info"></i> Article (Tekan Enter)</label>
                                <input type="text" name="article[]" class="form-control form-control-sm form-article text-uppercase fw-semibold" placeholder="Ketik Kode Artikel lalu tekan Enter">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Nama Item <span class="text-danger">*</span></label>
                                <input type="text" name="item[]" class="form-control form-control-sm form-item" value="<?php echo e($qItemRow->item); ?>" placeholder="Nama item" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Material</label>
                                <input type="text" name="material[]" class="form-control form-control-sm form-material" placeholder="Material">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Spesifikasi (Drawing/Berat)</label>
                                <input type="text" name="spesifikasi[]" class="form-control form-control-sm form-spesifikasi" placeholder="Spesifikasi teknis">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Qty <span class="text-danger">*</span></label>
                                <input type="number" name="qty[]" class="form-control form-control-sm qty-input" value="<?php echo e($qItemRow->qty); ?>" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Unit Price <span class="text-danger">*</span></label>
                                <input type="number" name="unit_price[]" class="form-control form-control-sm price-input" value="<?php echo e((int)$qItemRow->price); ?>" min="0" step="0.01" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Subtotal</label>
                                <input type="text" class="form-control form-control-sm subtotal-cell bg-light fw-semibold" 
                                    value="Rp <?php echo e(number_format($qItemRow->qty * $qItemRow->price, 0, ',', '.')); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Delivery Date</label>
                                <input type="date" name="delivery_date[]" class="form-control form-control-sm" value="<?php echo e($purchaseOrder->delivery_request ? \Carbon\Carbon::parse($purchaseOrder->delivery_request)->format('Y-m-d') : ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Supplier/Vendor</label>
                                <input type="text" name="supplier[]" class="form-control form-control-sm" placeholder="Supplier/Vendor">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">PIC Buyer</label>
                                <input type="text" name="pic_buyer[]" class="form-control form-control-sm" value="<?php echo e($purchaseOrder->customer->name ?? ''); ?>" placeholder="Nama PIC">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small text-muted">Perusahaan Buyer</label>
                                <input type="text" name="company_buyer[]" class="form-control form-control-sm" value="<?php echo e($purchaseOrder->company ?? ($purchaseOrder->quotation->company ?? '')); ?>" placeholder="Nama perusahaan">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label mb-1 fw-semibold small text-muted">Notes</label>
                                <input type="text" name="notes[]" class="form-control form-control-sm" placeholder="Catatan tambahan">
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>

            
            <div class="border rounded p-3 mb-3 d-flex justify-content-between align-items-center" style="background:#f0f4ff;">
                <span class="fw-bold">TOTAL MULTI-ITEMS</span>
                <span class="fw-bold fs-5" id="grand-total">Rp 0</span>
            </div>
 
            <div class="d-flex justify-content-end mt-3 gap-1">
                <button type="button" class="btn btn-sm btn-success" id="btn-add-row">
                    <i class="bi bi-plus-lg me-1"></i>Add item
                </button>
                <button type="submit" class="btn btn-primary btn-sm fw-semibold">
                    Save
                </button>
                <a href="<?php echo e(route('purchase-orders.index')); ?>" class="btn btn-sm btn-light">
                    Back
                </a>
            </div>
        </form>
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

        document.getElementById('item-body').addEventListener('keydown', function(e) {
            if (e.target.classList.contains('form-article') && e.key === 'Enter') {
                e.preventDefault();

                const inputField = e.target;
                const articleCode = inputField.value.trim();
                if (articleCode === '') return;

                const currentRow = inputField.closest('.item-row');

                Swal.fire({ 
                    title: 'Memuat Data...', 
                    allowOutsideClick: false, 
                    didOpen: () => { Swal.showLoading(); }
                });

                fetch(`/articles/requirements/${encodeURIComponent(articleCode)}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Not found');
                        return response.json();
                    })
                    .then(res => {
                        if (res.success && res.data) {
                            currentRow.querySelector('.form-item').value = res.data.part_name ?? '';
                            currentRow.querySelector('.form-material').value = res.data.material ?? '';
                            
                            let specText = [];
                            if (res.data.drawing_no) specText.push(`Drawing: ${res.data.drawing_no}`);
                            if (res.data.berat) specText.push(`Berat: ${res.data.berat} Kg`);
                            currentRow.querySelector('.form-spesifikasi').value = specText.join(' | ');

                            if (res.data.total_price || res.data.price) {
                                currentRow.querySelector('.price-input').value = res.data.total_price || res.data.price;
                            }

                            const qty = parseFloat(currentRow.querySelector('.qty-input').value) || 0;
                            const price = parseFloat(currentRow.querySelector('.price-input').value) || 0;
                            currentRow.querySelector('.subtotal-cell').value = 'Rp ' + (qty * price).toLocaleString('id-ID');
                            updateTotal();

                            Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Data spesifikasi artikel dimuat.', timer: 1200, showConfirmButton: false });
                        }
                    })
                    .catch(() => {
                        Swal.fire({ 
                            icon: 'warning', 
                            title: 'Artikel Baru!', 
                            text: 'Kode artikel tidak ditemukan di database. Silakan isi data teknis secara manual.',
                            confirmButtonText: 'Siap' 
                        });
                    });
            }
        });

        function performSearch() {
            const article = searchInput.value.trim();
            if(article === '') return;

            Swal.fire({ title: 'Mencari...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});

            fetch(`/articles/requirements/${encodeURIComponent(article)}`)
                .then(response => {
                    if (!response.ok) throw new Error('Not found');
                    return response.json();
                })
                .then(res => {
                    if(res.success && res.data) {
                        qcArea.classList.add('d-none');
                        
                        const firstRow = document.querySelector('#item-body .item-row');
                        if (firstRow) {
                            firstRow.querySelector('.form-article').value = article;
                            firstRow.querySelector('.form-item').value = res.data.part_name ?? '';
                            firstRow.querySelector('.form-material').value = res.data.material ?? '';
                            
                            let specText = [];
                            if (res.data.drawing_no) specText.push(`Drawing: ${res.data.drawing_no}`);
                            if (res.data.berat) specText.push(`Berat: ${res.data.berat} Kg`);
                            firstRow.querySelector('.form-spesifikasi').value = specText.join(' | ');
                            
                            if (res.data.total_price || res.data.price) {
                                firstRow.querySelector('.price-input').value = res.data.total_price || res.data.price;
                            }
                            
                            const qty = parseFloat(firstRow.querySelector('.qty-input').value) || 0;
                            const price = parseFloat(firstRow.querySelector('.price-input').value) || 0;
                            firstRow.querySelector('.subtotal-cell').value = 'Rp ' + (qty * price).toLocaleString('id-ID');
                            updateTotal();
                        }

                        Swal.fire({ icon: 'success', title: 'Data Ditemukan', text: 'Data berhasil ditarik ke Item #1', timer: 1500, showConfirmButton: false });
                    }
                })
                .catch(error => {
                    qcArea.classList.remove('d-none');
                    const firstRow = document.querySelector('#item-body .item-row');
                    if (firstRow) firstRow.querySelector('.form-article').value = article;

                    Swal.fire({ icon: 'warning', title: 'Barang Baru!', text: 'Nomor Artikel tidak ditemukan. Silakan klik "Send to QC".', confirmButtonText: 'Mengerti' });
                });
        }

        btnSearch.addEventListener('click', performSearch);
        searchInput.addEventListener('keypress', function(e) {
            if(e.key === 'Enter') { e.preventDefault(); performSearch(); }
        });

        btnSendQC.addEventListener('click', function() {
            const article = searchInput.value.trim();
            Swal.fire({
                title: 'Kirim Permintaan QC?',
                text: `Meminta QC untuk membuat spesifikasi barang: ${article}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Terkirim!', 'Permintaan telah dikirim ke QC.', 'success');
                }
            });
        });

        updateTotal();
    });

    function newRow(no) {
        return `
        <div class="border rounded p-3 mb-3 item-row" style="border-left: 4px solid #0d6efd !important;">
            <input type="hidden" name="internal_id[]" value="">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold text-primary">Item #<span class="row-no">${no}</span></span>
                <button type="button" class="btn btn-sm btn-danger btn-remove">
                    <i class="bi bi-trash me-1"></i>Hapus
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-6"><label class="form-label small text-muted mb-1">No PO</label>
                    <input type="text" name="po_no[]" class="form-control form-control-sm" value="<?php echo e($itemPoNo ?? $purchaseOrder->po_no ?? ''); ?>" readonly></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1 text-dark"><i class="bi bi-keyboard text-info"></i> Article (Tekan Enter)</label>
                    <input type="text" name="article[]" class="form-control form-control-sm form-article text-uppercase fw-semibold" placeholder="Ketik Kode Artikel lalu tekan Enter"></div>            
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Nama Item <span class="text-danger">*</span></label>
                    <input type="text" name="item[]" class="form-control form-control-sm form-item" placeholder="Nama item" required></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Material</label>
                    <input type="text" name="material[]" class="form-control form-control-sm form-material" placeholder="Material"></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Spesifikasi (Drawing/Berat)</label>
                    <input type="text" name="spesifikasi[]" class="form-control form-control-sm form-spesifikasi" placeholder="Spesifikasi teknis"></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Qty <span class="text-danger">*</span></label>
                    <input type="number" name="qty[]" class="form-control form-control-sm qty-input" value="1" min="1" required></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Unit Price <span class="text-danger">*</span></label>
                    <input type="number" name="unit_price[]" class="form-control form-control-sm price-input" value="0" min="0" step="0.01" required></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Subtotal</label>
                    <input type="text" class="form-control form-control-sm subtotal-cell bg-light fw-semibold" value="Rp 0" readonly></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Delivery Date</label>
                    <input type="date" name="delivery_date[]" class="form-control form-control-sm" value="<?php echo e($purchaseOrder->delivery_request ? \Carbon\Carbon::parse($purchaseOrder->delivery_request)->format('Y-m-d') : ''); ?>"></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Supplier/Vendor</label>
                    <input type="text" name="supplier[]" class="form-control form-control-sm" placeholder="Supplier/Vendor"></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">PIC Buyer</label>
                    <input type="text" name="pic_buyer[]" class="form-control form-control-sm" value="<?php echo e($purchaseOrder->customer->name ?? ''); ?>" placeholder="Nama PIC"></div>
                <div class="col-md-6"><label class="form-label small text-muted mb-1">Perusahaan Buyer</label>
                    <input type="text" name="company_buyer[]" class="form-control form-control-sm" value="<?php echo e($purchaseOrder->company ?? ($purchaseOrder->quotation->company ?? '')); ?>" placeholder="Nama perusahaan"></div>
                <div class="col-md-12"><label class="form-label small text-muted mb-1">Notes</label>
                    <input type="text" name="notes[]" class="form-control form-control-sm" placeholder="Catatan tambahan"></div>
            </div>
        </div>`;
    }

    document.getElementById('btn-add-row').addEventListener('click', function () {
        const count = document.querySelectorAll('#item-body .item-row').length + 1;
        document.getElementById('item-body').insertAdjacentHTML('beforeend', newRow(count));
        updateTotal();
    });

    document.getElementById('item-body').addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove')) {
            if (document.querySelectorAll('#item-body .item-row').length > 1) {
                e.target.closest('.item-row').remove();
                updateRowNumbers();
                updateTotal();
            }
        }
    });

    document.getElementById('item-body').addEventListener('input', function (e) {
        if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
            const row   = e.target.closest('.item-row');
            const qty   = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            row.querySelector('.subtotal-cell').value = 'Rp ' + (qty * price).toLocaleString('id-ID');
            updateTotal();
        }
    });

    function updateRowNumbers() {
        document.querySelectorAll('#item-body .item-row').forEach((row, i) => {
            const no = row.querySelector('.row-no');
            if (no) no.textContent = i + 1;
        });
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-cell').forEach(cell => {
            total += parseInt(cell.value.replace(/[^0-9]/g, '')) || 0;
        });
        document.getElementById('grand-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
    // MODUL 6: Auto-fill Spesifikasi & Harga dari Master Article
    document.getElementById('item-body').addEventListener('change', function (e) {
        if (e.target.name === 'article[]' && e.target.value.trim() !== '') {
            const articleNo = e.target.value.trim();
            const row = e.target.closest('.item-row');
            
            fetch('/article-requirements/' + encodeURIComponent(articleNo))
                .then(res => res.json())
                .then(res => {
                    if (res.success && res.data) {
                        const data = res.data;
                        if (data.material && row.querySelector('input[name="material[]"]')) {
                            row.querySelector('input[name="material[]"]').value = data.material;
                        }
                        if (data.drawing_no && row.querySelector('textarea[name="spesifikasi[]"]')) {
                            row.querySelector('textarea[name="spesifikasi[]"]').value = 'Drawing No: ' + data.drawing_no + (data.drawing_rev ? ' (Rev: ' + data.drawing_rev + ')' : '');
                        }
                        if (data.price && row.querySelector('.price-input')) {
                            row.querySelector('.price-input').value = data.price;
                            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                            row.querySelector('.subtotal-cell').value = 'Rp ' + (qty * data.price).toLocaleString('id-ID');
                            updateTotal();
                        }
                    }
                })
                .catch(err => console.log('Article lookup error:', err));
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/purchase-orders-internal/create.blade.php ENDPATH**/ ?>