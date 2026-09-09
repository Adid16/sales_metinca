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
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title text-white mb-0"><i class="bi bi-patch-check-fill me-2"></i>Pusat Persetujuan & Riwayat Amandemen PO</h5>
                </div>
            </div>
            
            <div class="card-body mt-3">
                
                <ul class="nav nav-tabs mb-3" id="amandementTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-tab-pane" type="button" role="tab" aria-controls="pending-tab-pane" aria-selected="true">
                            <i class="bi bi-hourglass-split me-1 text-warning"></i> Antrean Menunggu Persetujuan
                            <?php if(count($pendingContracts) > 0): ?>
                                <span class="badge bg-danger rounded-pill ms-1"><?php echo e(count($pendingContracts)); ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary rounded-pill ms-1">0</span>
                            <?php endif; ?>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-tab-pane" type="button" role="tab" aria-controls="history-tab-pane" aria-selected="false">
                            <i class="bi bi-clock-history me-1 text-primary"></i> Riwayat Amandemen Selesai
                            <span class="badge bg-secondary rounded-pill ms-1"><?php echo e(count($historyContracts ?? [])); ?></span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="amandementTabContent">
                    
                    <div class="tab-pane fade show active" id="pending-tab-pane" role="tabpanel" aria-labelledby="pending-tab" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="15%">No. PO</th>
                                        <th width="18%">Customer</th>
                                        <th width="37%">Target Item & Alasan Amandemen</th>
                                        <th width="13%" class="text-center">Tanggal Diajukan</th>
                                        <th width="12%" class="text-center">Aksi</th>
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
                                            $displayItem = $targetItem ? $targetItem->item : ($contract->part_name ?? 'Item PO');
                                            $displayQty = $targetItem ? $targetItem->qty : ($contract->qty ?? 1);
                                        ?>
                                         <tr>
                                            <td class="text-center fw-bold"><?php echo e($counter); ?></td>
                                            <td>
                                                <strong class="text-primary"><?php echo e($targetItem->po_no ?? $po->po_no ?? $contract->order_no ?? '-'); ?></strong>
                                                <?php if($po): ?>
                                                    <br><small class="text-muted">PO ID: #<?php echo e($po->id); ?></small>
                                                <?php endif; ?>
                                            </td>
                                             <?php
                                                $salesPic = $contract->sales_pic ?? ($po ? $po->sales_pic : null);
                                             ?>
                                             <td>
                                                 <strong><?php echo e($customer->name ?? '-'); ?></strong>
                                                 <?php if($salesPic): ?>
                                                     <br><span class="badge bg-light text-primary border extra-small"><i class="bi bi-person-fill me-1"></i>PIC: <?php echo e($salesPic->name); ?></span>
                                                 <?php endif; ?>
                                             </td>
                                            <td>
                                                <span class="badge bg-warning text-dark mb-1"><i class="bi bi-box-seam me-1"></i><?php echo e($displayItem); ?> (<?php echo e(number_format($displayQty)); ?> pcs)</span>
                                                <br>
                                                <span class="text-dark fst-italic">"<?php echo e(Str::limit($reason, 80)); ?>"</span>
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
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="bi bi-check2-circle fs-2 d-block mb-2 text-success"></i>
                                                <span class="fw-semibold">Tidak ada antrean pengajuan amandemen saat ini.</span>
                                                <p class="small text-muted mb-0">Semua pengajuan amandemen telah diproses atau belum ada permintaan revisi baru dari Customer.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="history-tab-pane" role="tabpanel" aria-labelledby="history-tab" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="15%">No. PO / Kontrak</th>
                                        <th width="18%">Customer</th>
                                        <th width="32%">Target Item & Alasan Customer</th>
                                        <th width="15%" class="text-center">Hasil Keputusan</th>
                                        <th width="15%" class="text-center">Tanggal Selesai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $hCounter = 0; ?>
                                    <?php $__empty_1 = true; $__currentLoopData = $historyContracts ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hContract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $hCounter++;
                                            $hTargetItem = $hContract->purchaseOrderInternal;
                                            $hPo = $hContract->purchase_order ?? ($hTargetItem ? $hTargetItem->purchaseOrder : null);
                                            $hCustomer = $hContract->customer ?? ($hPo ? $hPo->customer : null);
                                            $hItem = $hTargetItem ? $hTargetItem->item : ($hContract->part_name ?? 'Item PO');
                                            $hReason = $hContract->alasan_amandemen ?? 'Revisi item PO';
                                        ?>
                                        <tr>
                                            <td class="text-center fw-bold"><?php echo e($hCounter); ?></td>
                                            <td>
                                                <strong class="text-primary"><?php echo e($hTargetItem->po_no ?? $hPo->po_no ?? $hContract->order_no ?? '-'); ?></strong>
                                                <br><small class="text-muted">Kontrak: <?php echo e($hContract->contract_no); ?></small>
                                            </td>
                                            <td>
                                                <strong><?php echo e($hCustomer->name ?? '-'); ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary text-white mb-1"><i class="bi bi-box-seam me-1"></i><?php echo e($hItem); ?> (Rev #<?php echo e($hContract->amandement_no); ?>)</span>
                                                <br>
                                                <span class="text-dark small fst-italic">"<?php echo e(Str::limit($hReason, 70)); ?>"</span>
                                            </td>
                                            <td class="text-center">
                                                <?php if($hContract->status === 'rejected'): ?>
                                                    <span class="badge bg-danger mb-1"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                                                    <?php if($hContract->alasan_penolakan): ?>
                                                        <br><small class="text-danger fw-semibold" title="<?php echo e($hContract->alasan_penolakan); ?>"><?php echo e(Str::limit($hContract->alasan_penolakan, 30)); ?></small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="badge bg-success mb-1"><i class="bi bi-check-circle me-1"></i>Disetujui</span>
                                                    <?php if($hContract->catatan_sales): ?>
                                                        <br><small class="text-success" title="<?php echo e($hContract->catatan_sales); ?>"><?php echo e(Str::limit($hContract->catatan_sales, 30)); ?></small>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center small">
                                                <?php echo e($hContract->updated_at ? $hContract->updated_at->format('d-m-Y H:i') : '-'); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                Belum ada riwayat amandemen yang selesai diproses.
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
                    <?php
                        $displayItemName = $targetItem ? $targetItem->item : ($contract->part_name ?? ($contract->article ? $contract->article->part_name : 'Item PO'));
                        $displayPartNo = $contract->contract_no ?? ($targetItem ? ($targetItem->part_no ?? $targetItem->article) : ($contract->part_no ?? '-'));
                        $displayQty = $targetItem ? $targetItem->qty : ($contract->qty ?? 1);
                        $displayAmendNo = $contract->amandement_no ?? 1;
                        $displayCustomerName = $customer->name ?? ($po && $po->customer ? $po->customer->name : 'Customer');
                        $displayPoNo = $po->po_no ?? $contract->order_no ?? '-';
                        $displayQuotationNo = ($po && $po->quotation) ? $po->quotation->quotation_no : ($contract->quotation ? $contract->quotation->quotation_no : '-');
                        
                        $alasanLengkap = $contract->alasan_amandemen 
                            ?? ($targetItem && $targetItem->contract ? $targetItem->contract->alasan_amandemen : null)
                            ?? ($po && $po->contracts ? $po->contracts->whereNotNull('alasan_amandemen')->last()->alasan_amandemen : null)
                            ?? ($po && $po->reason ? $po->reason : null)
                            ?? 'Customer mengajukan penyesuaian/revisi pada item pesanan ini.';
                    ?>

                    <?php
                        $modalSalesPic = $contract->sales_pic ?? ($po ? $po->sales_pic : null);
                        $currentUser = auth()->user();
                        $canProcessAmandement = $currentUser->isAdmin() 
                            || ($currentUser->isManager() && $currentUser->divisi === 'sales') 
                            || ($currentUser->isStaff() && $currentUser->divisi === 'sales' && (!$modalSalesPic || $modalSalesPic->id === $currentUser->id));
                    ?>

                    
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block fw-semibold">Nama Customer:</small>
                                <strong class="text-dark"><?php echo e($displayCustomerName); ?></strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block fw-semibold">No. PO & Quotation Asal:</small>
                                <strong class="text-primary"><?php echo e($displayPoNo); ?></strong> <small class="text-muted">(<?php echo e($displayQuotationNo); ?>)</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block fw-semibold">Sales PIC Penanggung Jawab:</small>
                                <strong class="text-primary"><i class="bi bi-person-fill me-1"></i><?php echo e($modalSalesPic->name ?? '-'); ?></strong>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card border border-warning bg-light-warning mb-3 shadow-sm" style="background-color: #fffdf2 !important;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-diamond-fill text-warning fs-5 me-2"></i>
                                    <strong class="text-dark fs-6">Target Item Yang Diamandemen:</strong>
                                </div>
                                <span class="badge bg-warning text-dark fw-bold px-2 py-1">
                                    <i class="bi bi-arrow-repeat me-1"></i>Amandemen Rev #<?php echo e($displayAmendNo); ?>

                                </span>
                            </div>
                            <div class="row g-2 small text-dark ps-2">
                                <div class="col-md-5">
                                    <span class="text-muted d-block">Nama Part / Item:</span>
                                    <strong class="fs-6 text-dark"><?php echo e($displayItemName); ?></strong>
                                </div>
                                <div class="col-md-4">
                                    <span class="text-muted d-block">No. Part / Kontrak:</span>
                                    <span class="badge bg-dark text-white"><?php echo e($displayPartNo); ?></span>
                                </div>
                                <div class="col-md-3">
                                    <span class="text-muted d-block">Kuantitas PO:</span>
                                    <strong class="text-dark"><?php echo e(number_format($displayQty)); ?> pcs</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card border border-warning mb-3 shadow-sm" style="background-color: #fff8e6 !important;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-chat-left-quote-fill text-warning fs-5 me-2"></i>
                                <h6 class="fw-bold text-dark mb-0">Alasan Permintaan Amandemen dari Customer:</h6>
                            </div>
                            <div class="p-2 mt-2 bg-white rounded border border-warning-subtle text-dark fw-semibold fst-italic">
                                "<?php echo e($alasanLengkap); ?>"
                            </div>
                            <small class="text-muted d-block mt-1 ps-1">
                                <i class="bi bi-info-circle me-1"></i>Harap telaah alasan di atas dengan jadwal produksi & ketersediaan material pabrik sebelum memutuskan.
                            </small>
                        </div>
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
                    <?php if($canProcessAmandement): ?>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-danger btn-sm font-weight-bold px-3" data-bs-toggle="collapse" data-bs-target="#rejectCollapseContract<?php echo e($contract->id); ?>">
                                <i class="bi bi-x-circle me-1"></i> Reject
                            </button>
                            
                            <button type="submit" form="approveFormContract<?php echo e($contract->id); ?>" class="btn btn-success btn-sm font-weight-bold px-3">
                                <i class="bi bi-check-circle me-1"></i> Approve
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning py-1 px-2 mb-0 extra-small">
                            <i class="bi bi-lock-fill me-1"></i>Hanya Sales PIC (<?php echo e($modalSalesPic->name ?? 'Sales PIC'); ?>) atau Manager yang berhak memproses amandemen ini.
                        </div>
                    <?php endif; ?>
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