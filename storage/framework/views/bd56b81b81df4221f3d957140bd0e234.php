<?php $__env->startSection('title', 'Persetujuan Amandemen PO - PT. Metinca Prima Industrial Works'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app-dark.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div class="page-title mb-3">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Persetujuan Amandemen PO</h3>
                <p class="text-subtitle text-muted">Review pengajuan amandemen item per-item dari customer secara teliti.</p>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <section class="section">
        <div class="card">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="card-title text-white mb-0"><i class="bi bi-patch-check-fill me-2"></i>Daftar Antrean Amandemen PO</h5>
            </div>
            <div class="card-body mt-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="15%">No. PO</th>
                                <th width="20%">Customer</th>
                                <th width="35%">Target Item & Alasan Amandemen</th>
                                <th width="15%" class="text-center">Tanggal Diajukan</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $counter = 0; ?>
                            <?php $__empty_1 = true; $__currentLoopData = $pendingContracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $counter++;
                                    $targetItem = $contract->purchaseOrderInternal;
                                    $po = $contract->purchase_order ?? ($targetItem ? $targetItem->purchaseOrder : null);
                                    $customer = $contract->customer ?? ($po ? $po->customer : null);
                                    $targetInternalId = $targetItem->id ?? null;
                                    $reason = $contract->alasan_amandemen ?? 'Pengajuan amandemen item.';
                                ?>
                                 <tr>
                                    <td class="text-center fw-bold"><?php echo e($counter); ?></td>
                                    <td>
                                        <strong class="text-primary"><?php echo e($targetItem->po_no ?? $po->po_no ?? $contract->order_no ?? '-'); ?></strong>
                                        <?php if($po): ?>
                                            <br><small class="text-muted">PO ID: #<?php echo e($po->id); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo e($customer->name ?? '-'); ?></strong>
                                    </td>
                                    <td>
                                        <?php if($targetItem): ?>
                                            <span class="badge bg-warning text-dark mb-1"><i class="bi bi-box-seam me-1"></i><?php echo e($targetItem->item); ?> (<?php echo e(number_format($targetItem->qty)); ?> pcs)</span>
                                            <br>
                                        <?php endif; ?>
                                        <span class="text-dark fst-italic">"<?php echo e(Str::limit($reason, 60)); ?>"</span>
                                    </td>
                                    <td class="text-center small">
                                        <?php echo e($contract->updated_at ? $contract->updated_at->format('d-m-Y H:i') : '-'); ?>

                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm px-3 font-weight-bold" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#reviewModalContract<?php echo e($contract->id); ?>">
                                            <i class="bi bi-eye-fill me-1"></i> Review Detail
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-check2-circle fs-3 d-block mb-2 text-success"></i>
                                        Tidak ada antrean pengajuan amandemen saat ini.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>


<?php $__currentLoopData = $pendingContracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $targetItem = $contract->purchaseOrderInternal;
        $po = $contract->purchase_order ?? ($targetItem ? $targetItem->purchaseOrder : null);
        $customer = $contract->customer ?? ($po ? $po->customer : null);
        $targetInternalId = $targetItem->id ?? null;
        $poId = $po->id ?? $contract->purchase_order_id;
        $alasanLengkap = $contract->alasan_amandemen ?? 'Tidak ada catatan alasan.';
    ?>
    
    <div class="modal fade" id="reviewModalContract<?php echo e($contract->id); ?>" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-file-earmark-check-fill text-warning me-2"></i>Review Amandemen Item: <?php echo e($targetItem->item ?? $po->po_no ?? $contract->order_no); ?>

                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                
                <div class="modal-body">
                    
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block">Nama Customer:</small>
                                <strong><?php echo e($customer->name ?? '-'); ?></strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block">No. PO & Quotation Asal:</small>
                                <strong class="text-primary"><?php echo e($po->po_no ?? $contract->order_no ?? '-'); ?></strong> <small>(<?php echo e($po->quotation->quotation_no ?? '-'); ?>)</small>
                            </div>
                        </div>
                    </div>

                    
                    <?php if($targetItem): ?>
                        <div class="alert alert-light-warning border border-warning mb-3 p-3 rounded">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-exclamation-diamond-fill text-warning fs-5 me-2"></i>
                                <strong class="text-dark">Target Item Yang Diamandemen:</strong>
                            </div>
                            <div class="ms-4 small text-dark">
                                <div><strong>Nama Item:</strong> <?php echo e($targetItem->item); ?></div>
                                <div><strong>Part No / Kontrak:</strong> <code><?php echo e($contract->contract_no ?? $targetItem->part_no ?? '-'); ?></code></div>
                                <div><strong>Kuantitas PO:</strong> <?php echo e(number_format($targetItem->qty)); ?> pcs</div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <div class="alert alert-warning border border-warning mb-3">
                        <h6 class="fw-bold text-dark mb-1"><i class="bi bi-chat-left-text-fill me-1"></i> Alasan Amandemen dari Customer:</h6>
                        <p class="mb-0 text-dark fst-italic fs-6">"<?php echo e($alasanLengkap); ?>"</p>
                    </div>

                    
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-paperclip me-1"></i> Berkas Lampiran PO:</h6>
                    <div class="border rounded p-3 bg-light mb-3">
                        <?php if(!empty($po->attachment)): ?>
                            <div class="row g-2">
                                <?php $__currentLoopData = explode(',', $po->attachment); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6 col-12">
                                        <?php if($index == 0): ?>
                                            <a href="<?php echo e(asset('storage/uploads/' . trim($file))); ?>" target="_blank" class="btn btn-outline-info btn-sm w-100 text-start">
                                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> <strong>PO Original (Awal)</strong>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo e(asset('storage/uploads/' . trim($file))); ?>" target="_blank" class="btn btn-outline-warning btn-sm w-100 text-start text-dark">
                                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> <strong>Berkas Amandemen Baru #<?php echo e($index); ?></strong>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <span class="text-muted small">Tidak ada berkas lampiran yang diunggah.</span>
                        <?php endif; ?>
                    </div>

                    
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-boxes me-1"></i> Rincian Item PO Internal:</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="table-secondary text-uppercase small">
                                <tr>
                                    <th class="text-center" width="5%">#</th>
                                    <th>Nama Item</th>
                                    <th class="text-center" width="15%">Qty</th>
                                    <th width="25%">No. Kontrak Terikat</th>
                                    <th class="text-center" width="20%">Status Amandemen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $po->internals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $isTarget = ($targetInternalId && $item->id == $targetInternalId);
                                    ?>
                                    <tr class="<?php echo e($isTarget ? 'table-warning fw-bold' : ''); ?>">
                                        <td class="text-center"><?php echo e($i + 1); ?></td>
                                        <td>
                                            <?php echo e($item->item); ?>

                                            <?php if($isTarget): ?>
                                                <span class="badge bg-warning text-dark ms-1"><i class="bi bi-pencil-square"></i> Item Diamandemen</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center fw-bold"><?php echo e(number_format($item->qty)); ?></td>
                                        <td>
                                            <?php if($item->contract): ?>
                                                <span class="badge bg-primary"><?php echo e($item->contract->contract_no); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted small fst-italic">Belum dibuat</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if($isTarget): ?>
                                                <span class="badge bg-warning text-dark">Pending Review</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Normal</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted small py-2">Belum ada rincian item internal.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    
                    <hr>
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-check2-square me-1"></i> Keputusan Persetujuan:</h6>
                    
                    
                    <div class="collapse mb-3" id="rejectCollapseContract<?php echo e($contract->id); ?>">
                        <div class="card card-body bg-light-danger border border-danger p-3">
                            <form action="<?php echo e(route('purchase-orders.reject-amandement', $poId)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="contract_id" value="<?php echo e($contract->id); ?>">
                                
                                <?php if($targetInternalId): ?>
                                    <input type="hidden" name="purchase_order_internal_id" value="<?php echo e($targetInternalId); ?>">
                                <?php endif; ?>

                                <label for="alasan_penolakan_select_<?php echo e($contract->id); ?>" class="form-label text-danger fw-bold">Alasan Penolakan (Pilih Berdasarkan SOP Manufaktur):</label>
                                <select id="alasan_penolakan_select_<?php echo e($contract->id); ?>" class="form-select form-select-sm mb-2" 
                                    onchange="toggleRejectManual(this, '<?php echo e($contract->id); ?>')" required>
                                    <option value="">-- Pilih Alasan Penolakan --</option>
                                    <option value="Bahan baku sudah dipotong/disiapkan">Bahan baku sudah dipotong/disiapkan</option>
                                    <option value="Jadwal mesin stamping/casting sudah berjalan">Jadwal mesin stamping/casting sudah berjalan</option>
                                    <option value="Proses heat treatment sudah dimulai">Proses heat treatment sudah dimulai</option>
                                    <option value="Material sudah dipesan ke supplier">Material sudah dipesan ke supplier</option>
                                    <option value="Mould/cetakan sudah dalam proses produksi">Mould/cetakan sudah dalam proses produksi</option>
                                    <option value="Lainnya">Lainnya (Isi manual)</option>
                                </select>
                                <textarea id="alasan_penolakan_manual_<?php echo e($contract->id); ?>" class="form-control form-control-sm mb-2" rows="2" placeholder="Tuliskan alasan penolakan manual..." style="display:none;" oninput="updateFinalReason('<?php echo e($contract->id); ?>')"></textarea>
                                <input type="hidden" name="alasan_penolakan" id="alasan_penolakan_final_<?php echo e($contract->id); ?>" required>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="collapse" data-bs-target="#rejectCollapseContract<?php echo e($contract->id); ?>">Batal</button>
                                    <button type="submit" class="btn btn-sm btn-danger fw-bold"><i class="bi bi-x-circle-fill me-1"></i> Konfirmasi Tolak Amandemen</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    
                    <form action="<?php echo e(route('purchase-orders.approve-amandement', $poId)); ?>" method="POST" id="approveFormContract<?php echo e($contract->id); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="contract_id" value="<?php echo e($contract->id); ?>">
                        
                        <?php if($targetInternalId): ?>
                            <input type="hidden" name="purchase_order_internal_id" value="<?php echo e($targetInternalId); ?>">
                        <?php endif; ?>

                        <div class="mb-2">
                            <label for="catatan" class="form-label small text-muted">Catatan Disetujui (Opsional):</label>
                            <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Contoh: Amandemen item disetujui, siap masuk alur produksi.">
                        </div>
                    </form>
                </div>

                
                <div class="modal-footer bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-danger btn-sm font-weight-bold px-3" data-bs-toggle="collapse" data-bs-target="#rejectCollapseContract<?php echo e($contract->id); ?>">
                            <i class="bi bi-x-circle me-1"></i> Reject
                        </button>
                        
                        <button type="submit" form="approveFormContract<?php echo e($contract->id); ?>" class="btn btn-success btn-sm font-weight-bold px-3">
                            <i class="bi bi-check-circle me-1"></i> Approve
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleRejectManual(selectElem, contractId) {
    const manualBox = document.getElementById('alasan_penolakan_manual_' + contractId);
    const finalInput = document.getElementById('alasan_penolakan_final_' + contractId);
    
    if (selectElem.value === 'Lainnya') {
        manualBox.style.display = 'block';
        manualBox.required = true;
        finalInput.value = manualBox.value;
    } else {
        manualBox.style.display = 'none';
        manualBox.required = false;
        finalInput.value = selectElem.value;
    }
}

function updateFinalReason(contractId) {
    const manualBox = document.getElementById('alasan_penolakan_manual_' + contractId);
    const finalInput = document.getElementById('alasan_penolakan_final_' + contractId);
    finalInput.value = manualBox.value;
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/purchase-orders/approval-amandement.blade.php ENDPATH**/ ?>