
<div class="modal-header bg-primary text-white">
    <h5 class="modal-title text-white" id="exampleModalLabel">
        <i class="bi bi-file-earmark-text-fill me-2"></i>Detail Purchase Order: <?php echo e($po->po_no); ?>

    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


<div class="modal-body">

    
    
    
    <?php if($po->contract && $po->contract->status === 'rejected' && $po->contract->alasan_penolakan): ?>
        <div class="card border border-danger mb-4 p-3 rounded shadow-sm bg-light-danger" style="background-color: #fff5f5 !important;">
            <div class="d-flex align-items-center mb-1">
                <i class="bi bi-x-circle-fill fs-5 text-danger me-2"></i>
                <strong class="text-danger">Pengajuan Amandemen Ditolak</strong>
            </div>
            <div class="text-dark small ms-4">
                <strong>Alasan Penolakan:</strong> <span class="fw-bold text-danger"><?php echo e($po->contract->alasan_penolakan); ?></span>
            </div>
            <div class="text-muted extra-small ms-4 mt-1 fst-italic">
                *Pengajuan amandemen telah dibatalkan. Pesanan (PO) awal Anda tetap berlanjut sesuai kesepakatan sebelumnya.
            </div>
        </div>
    <?php endif; ?>

    
    <?php if($po->status === 'amandement_pending'): ?>
        <div class="card border border-warning mb-4 p-3 rounded shadow-sm bg-light-warning" style="background-color: #fffdf0 !important;">
            <div class="d-flex align-items-center">
                <i class="bi bi-hourglass-split fs-5 text-dark me-2"></i>
                <span class="text-dark small">
                    <strong>Status Amandemen:</strong> Pengajuan amandemen Anda telah diterima dan saat ini sedang dalam antrean verifikasi oleh Sales / Manager.
                </span>
            </div>
        </div>
    <?php endif; ?>
    
    
    <div class="card bg-light border mb-4">
        <div class="card-body py-3">
            <h6 class="font-weight-bold mb-3 text-dark">
                <i class="bi bi-geo-alt-fill text-danger me-1"></i> Status Pelacakan Logistik & Produksi:
                <?php if(isset($selectedItem) && $selectedItem): ?>
                    <span class="badge bg-primary text-white ms-1 fw-normal" style="font-size: 0.8rem;">Item: <?php echo e($selectedItem->item); ?></span>
                <?php endif; ?>
            </h6>
            
            
            <?php
                $activeStatus = strtolower($po->status ?? 'sent');

                if (isset($selectedItem) && $selectedItem) {
                    $itemContract = $selectedItem->contract ?? ($selectedItem->contracts ? $selectedItem->contracts->last() : null);
                    if ($itemContract) {
                        $cStatus = strtolower($itemContract->status);
                        if (in_array($cStatus, ['approved', 'done', 'contract'])) {
                            $activeStatus = (strtolower($po->status) === 'production') ? 'production' : 'contract';
                        } elseif ($cStatus === 'rejected') {
                            $activeStatus = 'rejected';
                        } elseif ($cStatus === 'amandement_pending') {
                            $activeStatus = 'amandement_pending';
                        } elseif (in_array($cStatus, ['amandement', 'amandement_approved'])) {
                            $activeStatus = 'amandement';
                        } elseif (in_array($cStatus, ['review', 'created', 'revision'])) {
                            $activeStatus = 'review';
                        } else {
                            $activeStatus = $cStatus;
                        }
                    }
                } else {
                    if (strtolower($po->status) === 'production') {
                        $activeStatus = 'production';
                    } else {
                        $itemStatuses = [];
                        foreach ($po->internals as $internal) {
                            $c = $internal->contract ?? ($internal->contracts ? $internal->contracts->last() : null);
                            if ($c) {
                                $itemStatuses[] = strtolower($c->status);
                            }
                        }
                        if (in_array('approved', $itemStatuses) || in_array('done', $itemStatuses) || in_array('contract', $itemStatuses)) {
                            $activeStatus = 'contract';
                        } elseif (in_array('amandement', $itemStatuses) || in_array('amandement_approved', $itemStatuses)) {
                            $activeStatus = 'amandement';
                        } elseif (in_array('review', $itemStatuses) || in_array('created', $itemStatuses) || in_array('revision', $itemStatuses)) {
                            $activeStatus = 'review';
                        } elseif (in_array('amandement_pending', $itemStatuses)) {
                            $activeStatus = 'amandement_pending';
                        } elseif (in_array('rejected', $itemStatuses)) {
                            $activeStatus = 'rejected';
                        }
                    }
                }

                $progressWidth = '20%';
                $barColor = 'bg-info';
                $statusLabel = strtoupper($activeStatus);

                if ($activeStatus == 'sent') { 
                    $progressWidth = '20%'; $barColor = 'bg-info'; $statusLabel = 'SENT';
                }
                elseif ($activeStatus == 'amandement_pending') { 
                    $progressWidth = '35%'; $barColor = 'bg-warning text-dark'; $statusLabel = 'REVIEW AMANDEMEN';
                }
                elseif ($activeStatus == 'review') { 
                    $progressWidth = '50%'; $barColor = 'bg-warning text-dark'; $statusLabel = 'REVIEW KONTRAK';
                }
                elseif ($activeStatus == 'amandement') { 
                    $progressWidth = '65%'; $barColor = 'bg-warning text-dark'; $statusLabel = 'AMANDEMEN DISETUJUI';
                }
                elseif ($activeStatus == 'contract') { 
                    $progressWidth = '80%'; $barColor = 'bg-primary'; $statusLabel = 'KONTRAK DISETUJUI';
                }
                elseif ($activeStatus == 'production') { 
                    $progressWidth = '100%'; $barColor = 'bg-success'; $statusLabel = 'IN PRODUCTION';
                }
                elseif ($activeStatus == 'rejected') { 
                    $progressWidth = '100%'; $barColor = 'bg-danger'; $statusLabel = 'REJECTED';
                }
            ?>

            <div class="progress mb-2" style="height: 25px; border-radius: 6px; overflow: hidden;">
                <div class="progress-bar <?php echo e($barColor); ?> progress-bar-striped progress-bar-animated font-weight-bold text-center" 
                     role="progressbar" 
                     style="width: <?php echo e($progressWidth); ?>; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">
                     <?php echo e($statusLabel); ?>

                </div>
            </div>

            
            <div class="bg-white border rounded p-3 mt-3 shadow-sm">
                <div class="d-flex align-items-start">
                    <i class="bi bi-info-circle-fill text-primary me-2 mt-1" style="font-size: 1.1rem;"></i>
                    <div>
                        <strong class="text-dark">Informasi Sistem:</strong>
                        <p class="text-muted small mb-0 mt-1">
                            <?php if($activeStatus == 'sent'): ?>
                                Dokumen PO baru saja Anda kirim ke sistem. Saat ini sedang menunggu antrean verifikasi awal oleh Admin/Sales internal.
                            <?php elseif($activeStatus == 'amandement_pending'): ?>
                                Berkas amandemen baru saja diunggah. Saat ini sedang menunggu verifikasi dan keputusan (Approve/Reject) dari tim Sales / Manager.
                            <?php elseif($activeStatus == 'review'): ?>
                                Dokumen kontrak item sedang dalam peninjauan ketat secara paralel oleh 4 divisi (Sales, PPC, Quality, dan Dev Engineering).
                            <?php elseif($activeStatus == 'amandement'): ?>
                                Amandemen disetujui! Dokumen resmi diperbarui di sistem PO External dan siap dilanjutkan ke alur produksi.
                            <?php elseif($activeStatus == 'contract'): ?>
                                Administrasi & kontrak selesai disahkan! Berkas Anda aman dan pesanan sudah masuk daftar antrean mesin pengecoran pabrik.
                            <?php elseif($activeStatus == 'production'): ?>
                                <span class="text-success font-weight-bold">PRODUKSI BERJALAN: Seluruh tinjauan kontrak telah disahkan dan pesanan saat ini sedang dalam proses pengerjaan di lantai produksi PT. Metinca Prima Industrial Works. Data PO telah dikunci demi keselamatan produksi.</span>
                            <?php elseif($activeStatus == 'rejected'): ?>
                                <span class="text-danger font-weight-bold">Pengajuan amandemen untuk item ini ditolak. Pesanan/Kontrak sebelumnya tetap berlanjut sesuai kesepakatan.</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <h6 class="font-weight-bold mb-3 text-dark"><i class="bi bi-card-list me-1 text-primary"></i> Informasi Dokumen:</h6>
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered small">
                    <tr>
                        <th class="bg-light text-secondary" width="40%">Nomor PO</th>
                        <td><strong><?php echo e($po->po_no); ?></strong></td>
                    </tr>
                    <tr>
                        <th class="bg-light text-secondary">No. Quotation Asal</th>
                        <td><?php echo e($po->quotation->quotation_no ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light text-secondary">Nama Customer</th>
                        <td><?php echo e($po->customer->name ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light text-secondary">Jenis Urgensi Order</th>
                        <td>
                            <span class="badge <?php echo e($po->status_order == 'urgent' ? 'bg-light-danger text-danger' : 'bg-light-success text-success'); ?>">
                                <?php echo e(strtoupper($po->status_order ?? 'REGULAR')); ?>

                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="col-md-6 col-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered small">
                    <tr>
                        <th class="bg-light text-secondary" width="40%">Delivery Request</th>
                        <td><strong><?php echo e($po->delivery_request ? \Carbon\Carbon::parse($po->delivery_request)->format('d-m-Y') : '-'); ?></strong></td>
                    </tr>
                    <tr>
                        <th class="bg-light text-secondary">Sales PIC Internal</th>
                        <td>
                            <span class="badge bg-light-secondary text-secondary">
                                <?php echo e($po->quotation->request->assignment->sales->name ?? 'Belum Ditugaskan'); ?>

                            </span>
                        </td>
                    </tr>
                    
                    
                    
                    <?php
                        if (isset($selectedItem) && $selectedItem) {
                            $targetContract = \App\Models\Contract::where('purchase_order_internal_id', $selectedItem->id)
                                ->orderByDesc('amandement_no')
                                ->first();
                        } else {
                            $targetContract = \App\Models\Contract::where('order_no', $po->po_no)
                                ->orderByDesc('amandement_no')
                                ->first();
                        }
                        $isItemAmandemen = $targetContract && $targetContract->amandement_no > 0;
                    ?>
                    <tr>
                        <th class="bg-light text-secondary">Riwayat Referensi</th>
                        <td>
                            <?php if($isItemAmandemen): ?>
                                <span class="badge bg-warning text-white">Amandemen Ke-<?php echo e($targetContract->amandement_no); ?></span>
                            <?php else: ?>
                                <span class="text-muted">Original PO (Belum Diamandemen)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    
                    
                    <tr>
                        <th class="bg-light text-secondary">Berkas Lampiran</th>
                        <td>
                            <?php
                                $poFiles = !empty($po->attachment) ? explode(',', $po->attachment) : [];
                                $originalFile = count($poFiles) > 0 ? trim($poFiles[0]) : null;
                                $itemAmandementFile = ($targetContract && !empty($targetContract->po_pdf)) ? $targetContract->po_pdf : null;
                            ?>

                            <div class="d-flex flex-column gap-1">
                                
                                <?php if($originalFile): ?>
                                    <?php
                                        $origPath = str_starts_with($originalFile, 'uploads/') || str_starts_with($originalFile, 'contracts/')
                                            ? asset('storage/' . $originalFile)
                                            : asset('storage/uploads/' . $originalFile);
                                    ?>
                                    <a href="<?php echo e($origPath); ?>" target="_blank" class="btn btn-sm btn-info text-white py-1 px-2 mb-1 d-block text-start">
                                        <i class="bi bi-cloud-arrow-down-fill me-1"></i> Lihat PO Original
                                    </a>
                                <?php endif; ?>

                                
                                <?php if($isItemAmandemen): ?>
                                    <?php if($itemAmandementFile): ?>
                                        <?php
                                            $amdPath = str_starts_with($itemAmandementFile, 'uploads/') || str_starts_with($itemAmandementFile, 'contracts/')
                                                ? asset('storage/' . $itemAmandementFile)
                                                : asset('storage/uploads/' . $itemAmandementFile);
                                        ?>
                                        <a href="<?php echo e($amdPath); ?>" target="_blank" class="btn btn-sm btn-warning text-white py-1 px-2 mb-1 d-block text-start">
                                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Berkas Amandemen <?php echo e($targetContract->amandement_no); ?>

                                        </a>
                                    <?php elseif(count($poFiles) > 1 && (!isset($selectedItem) || !$selectedItem)): ?>
                                        <?php $__currentLoopData = $poFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($index > 0): ?>
                                                <a href="<?php echo e(asset('storage/uploads/' . trim($file))); ?>" target="_blank" class="btn btn-sm btn-warning text-white py-1 px-2 mb-1 d-block text-start">
                                                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Berkas Amandemen <?php echo e($index); ?>

                                                </a>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if(!$originalFile && (!$isItemAmandemen || !$itemAmandementFile)): ?>
                                    <span class="text-muted small">Tidak ada lampiran berkas</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    
    <?php if($isItemAmandemen && ($targetContract->alasan_amandemen || $po->reason || $po->notes)): ?>
        <div class="row mt-2">
            <div class="col-12">
                <div class="form-group mb-0">
                    <label class="font-weight-bold text-dark small mb-1">Catatan / Alasan Perubahan Dokumen (Customer):</label>
                    <div class="p-2 border rounded bg-light small text-dark fst-italic">
                        "<?php echo e($targetContract->alasan_amandemen ?? ($po->reason ?? ($po->notes ?? ''))); ?>"
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    
    <?php if($isItemAmandemen && !empty($targetContract->alasan_penolakan)): ?>
        <div class="row mt-2">
            <div class="col-12">
                <div class="form-group mb-0">
                    <label class="font-weight-bold text-danger small mb-1"><i class="bi bi-x-octagon-fill me-1"></i> Catatan Penolakan Amandemen (Sales / Manajemen):</label>
                    <div class="p-2 border border-danger rounded bg-light-danger small text-danger fw-semibold">
                        "<?php echo e($targetContract->alasan_penolakan); ?>"
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- ========================================== -->
    <!-- 1. TABEL RINGKASAN ITEM PESANAN -->
    <!-- ========================================== -->
    <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-table me-2 text-primary"></i>Daftar Item Pesanan:</h6>
            <span class="badge bg-light text-secondary border small">
                <?php echo e($po->internals->count() > 0 ? $po->internals->count() . ' Item Terdaftar' : ($po->quotation?->items->count() ?? 0) . ' Item (Quotation)'); ?>

            </span>
        </div>

        <?php
            $itemsList = $po->internals->count() > 0 ? $po->internals : ($po->quotation?->items ?? collect());
            $isUsingInternals = $po->internals->count() > 0;
            $totalQty = 0;
            $grandTotal = 0;
        ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle small mb-0">
                <thead class="table-light">
                    <tr class="text-secondary text-uppercase" style="font-size: 0.75rem;">
                        <th class="text-center" style="width: 35px;">#</th>
                        <th>Nama Item</th>
                        <th>Article / Part No</th>
                        <th class="text-center" style="width: 70px;">Qty</th>
                        <th class="text-end" style="width: 120px;">Harga Satuan</th>
                        <th class="text-end" style="width: 130px;">Subtotal</th>
                        <th class="text-center" style="width: 140px;">Status Review</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $itemsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $isHighlight = isset($selectedItem) && $selectedItem && $selectedItem->id == $item->id;
                            $itemName = $item->item ?? '-';
                            $articleCode = $item->article ?? ($item->article?->article_no ?? $item->article?->internal_part_no ?? '-');
                            $partNo = $item->contract->part_no ?? ($item->part_no ?? ($item->article?->internal_part_no ?? '-'));
                            
                            $qty = (int)($item->qty ?? 1);
                            $unitPrice = (float)($item->unit_price ?? $item->price ?? $item->negotiated_price ?? $item->original_price ?? 0);
                            $subtotal = (float)($item->subtotal ?? ($qty * $unitPrice));
                            $totalQty += $qty;
                            $grandTotal += $subtotal;

                            $itemContract = $isUsingInternals ? ($item->contract ?? ($item->contracts ? $item->contracts->sortByDesc('amandement_no')->first() : null)) : null;

                            if ($itemContract && in_array($itemContract->status, ['approved', 'done', 'contract'])) {
                                $displayStatus = (strtolower($po->status) === 'production') ? 'In Production' : 'Kontrak Disetujui';
                                $badgeClass = (strtolower($po->status) === 'production') ? 'bg-success text-white' : 'bg-primary text-white';
                            } elseif ($itemContract && $itemContract->status === 'rejected') {
                                $displayStatus = 'Amandemen Ditolak';
                                $badgeClass = 'bg-danger text-white';
                            } elseif ($itemContract && $itemContract->status === 'amandement_pending') {
                                $displayStatus = 'Review Amandemen';
                                $badgeClass = 'bg-warning text-dark';
                            } elseif ($itemContract && in_array($itemContract->status, ['amandement', 'amandement_approved'])) {
                                $displayStatus = 'Amandemen Disetujui';
                                $badgeClass = 'bg-success text-white';
                            } elseif ($itemContract && in_array($itemContract->status, ['review', 'created', 'revision'])) {
                                $displayStatus = 'Review Kontrak';
                                $badgeClass = 'bg-warning text-dark';
                            } else {
                                $displayStatus = $isUsingInternals ? ucfirst($po->status) : 'Menunggu Sales';
                                $badgeClass = ($po->status == 'sent') ? 'bg-info text-white' : 'bg-secondary text-white';
                            }
                        ?>
                        <tr class="<?php echo e($isHighlight ? 'table-warning border-warning fw-bold' : ''); ?>">
                            <td class="text-center"><?php echo e($index + 1); ?></td>
                            <td>
                                <span class="fw-semibold text-dark"><?php echo e($itemName); ?></span>
                                <?php if($isHighlight): ?>
                                    <span class="badge bg-primary ms-1" style="font-size: 0.68rem;">Dipilih</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <code><?php echo e($articleCode); ?></code>
                                <?php if($partNo && $partNo !== '-'): ?>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Part: <?php echo e($partNo); ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-center fw-bold"><?php echo e(number_format($qty)); ?></td>
                            <td class="text-end text-success fw-semibold">Rp <?php echo e(number_format($unitPrice, 0, ',', '.')); ?></td>
                            <td class="text-end fw-bold text-dark">Rp <?php echo e(number_format($subtotal, 0, ',', '.')); ?></td>
                            <td class="text-center">
                                <span class="badge <?php echo e($badgeClass); ?> px-2 py-1" style="font-size: 0.72rem;"><?php echo e($displayStatus); ?></span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">Belum ada data item.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <?php if($itemsList->count() > 0): ?>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="3" class="text-end text-uppercase text-secondary" style="font-size: 0.75rem;">Total Keseluruhan Pesanan:</td>
                            <td class="text-center text-primary fs-6"><?php echo e(number_format($totalQty)); ?> Pcs</td>
                            <td class="text-end text-muted">-</td>
                            <td class="text-end text-primary fs-6">Rp <?php echo e(number_format($grandTotal, 0, ',', '.')); ?></td>
                            <td class="text-center"></td>
                        </tr>
                    </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 2. DETAIL LENGKAP SPESIFIKASI & AMANDEMEN PER-ITEM -->
    <!-- ========================================== -->
    <?php
        $hasSelectedItem = isset($selectedItem) && $selectedItem;
    ?>

    <div class="mt-4 pt-3 border-top">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-card-checklist me-2 text-primary"></i>
                    <?php if($hasSelectedItem): ?>
                        Detail Item: <span class="text-primary"><?php echo e($selectedItem->item); ?></span>
                    <?php else: ?>
                        Detail Lengkap Masing-Masing Item (<?php echo e($itemsList->count()); ?>):
                    <?php endif; ?>
                </h6>
                <small class="text-muted" id="detail-section-subtitle">
                    <?php if($hasSelectedItem): ?>
                        Menampilkan rincian spesifikasi & riwayat amandemen khusus untuk item yang dipilih.
                    <?php else: ?>
                        Spesifikasi mendalam, alasan perubahan, dan berkas amandemen untuk seluruh item.
                    <?php endif; ?>
                </small>
            </div>
            <?php if($hasSelectedItem && $itemsList->count() > 1): ?>
                <button type="button" class="btn btn-xs btn-outline-primary fw-semibold" id="btn-show-all-cards" onclick="showAllItemCards()">
                    <i class="bi bi-grid me-1"></i>Tampilkan Seluruh Item (<?php echo e($itemsList->count()); ?>)
                </button>
            <?php endif; ?>
        </div>

        <div class="d-flex flex-column gap-3" id="item-cards-container">
            <?php $__currentLoopData = $itemsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $isMatchSelected = $hasSelectedItem && $selectedItem->id == $item->id;
                    $shouldHideInitially = $hasSelectedItem && !$isMatchSelected;

                    $itemName = $item->item ?? '-';
                    $articleCode = $item->article ?? ($item->article?->article_no ?? $item->article?->internal_part_no ?? '-');
                    $partNo = $item->contract->part_no ?? ($item->part_no ?? ($item->article?->internal_part_no ?? '-'));
                    $material = $item->material ?? ($item->article?->material ?? '-');
                    
                    $spesifikasi = $item->spesifikasi ?? '';
                    if (empty($spesifikasi) && isset($item->article) && $item->article) {
                        $specsArr = [];
                        if (!empty($item->article->drawing_no)) {
                            $specsArr[] = 'Dwg: ' . $item->article->drawing_no . (!empty($item->article->drawing_rev) ? ' Rev: ' . $item->article->drawing_rev : '');
                        }
                        if (!empty($item->article->berat)) {
                            $specsArr[] = 'Berat: ' . $item->article->berat . ' Kg';
                        }
                        if (!empty($item->article->remark)) {
                            $specsArr[] = 'Remark: ' . $item->article->remark;
                        }
                        $spesifikasi = implode(' | ', $specsArr);
                    }

                    $qty = (int)($item->qty ?? 1);
                    $unitPrice = (float)($item->unit_price ?? $item->price ?? $item->negotiated_price ?? $item->original_price ?? 0);
                    $subtotal = (float)($item->subtotal ?? ($qty * $unitPrice));

                    $deliveryDateStr = '-';
                    if (!empty($item->delivery_date)) {
                        $deliveryDateStr = $item->delivery_date instanceof \Carbon\Carbon ? $item->delivery_date->format('d M Y') : \Carbon\Carbon::parse($item->delivery_date)->format('d M Y');
                    } elseif (!empty($po->delivery_request)) {
                        $deliveryDateStr = \Carbon\Carbon::parse($po->delivery_request)->format('d M Y');
                    }

                    $itemContract = $isUsingInternals ? ($item->contract ?? ($item->contracts ? $item->contracts->sortByDesc('amandement_no')->first() : null)) : null;
                    $itemAmendCount = $isUsingInternals ? \App\Models\Contract::where('purchase_order_internal_id', $item->id)->where('amandement_no', '>', 0)->count() : 0;
                    if ($itemContract && $itemContract->amandement_no > $itemAmendCount) {
                        $itemAmendCount = $itemContract->amandement_no;
                    }

                    if ($itemContract && in_array($itemContract->status, ['approved', 'done', 'contract'])) {
                        $displayStatus = (strtolower($po->status) === 'production') ? 'In Production' : 'Kontrak Disetujui';
                        $badgeClass = (strtolower($po->status) === 'production') ? 'bg-success text-white' : 'bg-primary text-white';
                    } elseif ($itemContract && $itemContract->status === 'rejected') {
                        $displayStatus = 'Amandemen Ditolak';
                        $badgeClass = 'bg-danger text-white';
                    } elseif ($itemContract && $itemContract->status === 'amandement_pending') {
                        $displayStatus = 'Review Amandemen';
                        $badgeClass = 'bg-warning text-dark';
                    } elseif ($itemContract && in_array($itemContract->status, ['amandement', 'amandement_approved'])) {
                        $displayStatus = 'Amandemen Disetujui';
                        $badgeClass = 'bg-success text-white';
                    } elseif ($itemContract && in_array($itemContract->status, ['review', 'created', 'revision'])) {
                        $displayStatus = 'Review Kontrak (4 Divisi)';
                        $badgeClass = 'bg-warning text-dark';
                    } else {
                        $displayStatus = $isUsingInternals ? ucfirst($po->status) : 'Menunggu PO Internal';
                        $badgeClass = ($po->status == 'sent') ? 'bg-info text-white' : 'bg-secondary text-white';
                    }

                    $isItemAmended = $itemContract && $itemContract->amandement_no > 0;
                ?>

                <div class="card border <?php echo e($isMatchSelected ? 'border-primary shadow-sm' : 'border-secondary-subtle'); ?> rounded mb-0 item-detail-card-wrapper" 
                     id="item-detail-card-<?php echo e($index + 1); ?>" 
                     style="<?php echo e($shouldHideInitially ? 'display: none;' : ''); ?>">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center flex-wrap gap-2 <?php echo e($isMatchSelected ? 'bg-primary text-white' : 'bg-light text-dark'); ?>">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge <?php echo e($isMatchSelected ? 'bg-white text-primary' : 'bg-primary text-white'); ?> fw-bold">Item #<?php echo e($index + 1); ?></span>
                            <span class="fw-bold fs-6"><?php echo e($itemName); ?></span>
                            <?php if($isMatchSelected): ?>
                                <span class="badge bg-warning text-dark ms-1"><i class="bi bi-check-circle-fill me-1"></i>Item Dipilih</span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="badge <?php echo e($badgeClass); ?> px-2 py-1"><?php echo e($displayStatus); ?></span>
                            <?php if($isItemAmended): ?>
                                <span class="badge bg-warning text-dark border"><i class="bi bi-pencil-square me-1"></i>Amandemen Ke-<?php echo e($itemContract->amandement_no); ?> (<?php echo e($itemAmendCount); ?>/2)</span>
                            <?php else: ?>
                                <span class="badge bg-light text-secondary border">Original (0/2)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            
                            <?php
                                $articleObj = $itemContract?->article ?? ($item->articleModel ?? (\App\Models\Article::where('article_no', $articleCode)->first()));
                                $matText = $articleObj?->material ?? ($item->material ?? '-');
                                $dwgText = $articleObj?->drawing_no ? ($articleObj->drawing_no . (!empty($articleObj->drawing_rev) ? ' (Rev: ' . $articleObj->drawing_rev . ')' : '')) : ($spesifikasi ?: null);
                            ?>
                            <div class="col-md-6 border-end">
                                <h6 class="text-secondary fw-bold small mb-2"><i class="bi bi-box-seam me-1 text-primary"></i> Rincian Pesanan:</h6>
                                <table class="table table-sm table-borderless small mb-0">
                                    <tr>
                                        <td class="text-muted" style="width: 140px;">Nama Item</td>
                                        <td><strong class="text-dark"><?php echo e($itemName); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Part No</td>
                                        <td><span class="badge bg-light text-dark border"><?php echo e($partNo); ?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Material / Bahan</td>
                                        <td><span class="fw-semibold text-dark"><?php echo e($matText); ?></span></td>
                                    </tr>
                                    <?php if($dwgText && $dwgText !== '-'): ?>
                                        <tr>
                                            <td class="text-muted">Drawing & Rev</td>
                                            <td><span class="text-secondary"><?php echo e($dwgText); ?></span></td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td class="text-muted">Target Kirim</td>
                                        <td><span class="fw-semibold text-danger"><i class="bi bi-calendar-event me-1"></i><?php echo e($deliveryDateStr); ?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Jumlah Pesanan</td>
                                        <td><span class="fw-bold fs-6 text-dark"><?php echo e(number_format($qty)); ?> Pcs</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Harga Satuan</td>
                                        <td><span class="fw-bold text-success">Rp <?php echo e(number_format($unitPrice, 0, ',', '.')); ?></span> <small class="text-muted">(Kesepakatan)</small></td>
                                    </tr>
                                    <tr class="border-top">
                                        <td class="text-muted fw-bold">Subtotal</td>
                                        <td><span class="fw-bold fs-6 text-primary">Rp <?php echo e(number_format($subtotal, 0, ',', '.')); ?></span></td>
                                    </tr>
                                </table>
                            </div>

                            
                            <div class="col-md-6 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="text-secondary fw-bold small mb-0"><i class="bi bi-journal-check me-1 text-primary"></i> Verifikasi Tinjauan Kontrak:</h6>
                                        <?php if($itemContract && $itemContract->contract_no): ?>
                                            <span class="badge bg-light-primary text-primary border border-primary small" style="font-size: 0.72rem;">
                                                <i class="bi bi-file-earmark-spreadsheet me-1"></i><?php echo e($itemContract->contract_no); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    
                                    <div class="p-2 rounded bg-light border mb-2 small">
                                        <div class="text-muted fw-semibold mb-1" style="font-size: 0.72rem;">Verifikasi 4 Divisi (Pabrik Metinca):</div>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php if($itemContract && $itemContract->sales_approver): ?>
                                                <span class="badge bg-success text-white" style="font-size: 0.68rem;" title="Approved: <?php echo e($itemContract->sales_approved_at); ?>">
                                                    <i class="bi bi-check-circle-fill me-1"></i>Sales: Ok
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark" style="font-size: 0.68rem;"><i class="bi bi-clock me-1"></i>Sales: Review</span>
                                            <?php endif; ?>

                                            <?php if($itemContract && $itemContract->quality_approver): ?>
                                                <span class="badge bg-success text-white" style="font-size: 0.68rem;" title="Approved: <?php echo e($itemContract->quality_approved_at); ?>">
                                                    <i class="bi bi-check-circle-fill me-1"></i>Quality: Ok
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark" style="font-size: 0.68rem;"><i class="bi bi-clock me-1"></i>Quality: Review</span>
                                            <?php endif; ?>

                                            <?php if($itemContract && $itemContract->ppc_approver): ?>
                                                <span class="badge bg-success text-white" style="font-size: 0.68rem;" title="Approved: <?php echo e($itemContract->ppc_approved_at); ?>">
                                                    <i class="bi bi-check-circle-fill me-1"></i>PPC: Ok
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark" style="font-size: 0.68rem;"><i class="bi bi-clock me-1"></i>PPC: Review</span>
                                            <?php endif; ?>

                                            <?php if($itemContract && $itemContract->dev_engineering_approver): ?>
                                                <span class="badge bg-success text-white" style="font-size: 0.68rem;" title="Approved: <?php echo e($itemContract->dev_engineering_approved_at); ?>">
                                                    <i class="bi bi-check-circle-fill me-1"></i>DE: Ok
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark" style="font-size: 0.68rem;"><i class="bi bi-clock me-1"></i>DE: Review</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-1 small border-bottom pb-1">
                                        <span class="text-muted">Status Produksi:</span>
                                        <span class="badge <?php echo e($badgeClass); ?>"><?php echo e($displayStatus); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2 small border-bottom pb-1">
                                        <span class="text-muted">Kuota Amandemen Item:</span>
                                        <span class="fw-bold <?php echo e($itemAmendCount >= 2 ? 'text-danger' : 'text-success'); ?>"><?php echo e($itemAmendCount); ?> dari 2 digunakan</span>
                                    </div>

                                    
                                    <?php if($itemContract && $itemContract->requirements && $itemContract->requirements->count() > 0): ?>
                                        <?php
                                            $technicalRequirements = $itemContract->requirements->filter(function($req) {
                                                $reqName = strtolower(trim($req->requirement ?? ''));
                                                $val = trim($req->requirement_value ?? '');
                                                if (empty($val) || in_array(strtolower($val), ['-', 'ok', 'ada', 'good', 'sd'])) return false;
                                                return !in_array($reqName, [
                                                    'price', 'quantity', 'delivery required', 'delivery_required', 
                                                    'target delivery date', 'unit price', 'supply condition', 'supply_condition',
                                                    'dies', 'tool', 'fixtures', 'pattern wax', 'master job card', 'wra / wi', 'sub contracting', 'purchasing'
                                                ]);
                                            });
                                        ?>
                                        <?php if($technicalRequirements->count() > 0): ?>
                                            <div class="mb-2 p-2 rounded bg-light border" style="font-size: 0.72rem;">
                                                <div class="fw-semibold text-secondary mb-1"><i class="bi bi-shield-check me-1 text-success"></i>Ketentuan Mutu / Standar Spesifikasi:</div>
                                                <ul class="mb-0 ps-3 text-muted">
                                                    <?php $__currentLoopData = $technicalRequirements->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li><strong><?php echo e(strtoupper($req->requirement_from)); ?>:</strong> <?php echo e($req->requirement); ?> (<?php echo e($req->requirement_value); ?>)</li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    
                                    <?php if($isItemAmended && !empty($itemContract->alasan_amandemen)): ?>
                                        <div class="p-2 rounded bg-light-warning border border-warning mb-2 small">
                                            <div class="fw-bold text-dark mb-1">
                                                <i class="bi bi-chat-left-quote-fill text-warning me-1"></i>Alasan Pengajuan Amandemen:
                                            </div>
                                            <div class="text-dark fst-italic">"<?php echo e($itemContract->alasan_amandemen); ?>"</div>
                                            
                                            <?php if(!empty($itemContract->po_pdf)): ?>
                                                <?php
                                                    $pdfPath = str_starts_with($itemContract->po_pdf, 'uploads/') || str_starts_with($itemContract->po_pdf, 'contracts/')
                                                        ? asset('storage/' . $itemContract->po_pdf)
                                                        : asset('storage/uploads/' . $itemContract->po_pdf);
                                                ?>
                                                <div class="mt-2">
                                                    <a href="<?php echo e($pdfPath); ?>" target="_blank" class="btn btn-xs btn-warning text-dark fw-semibold py-1 px-2">
                                                        <i class="bi bi-file-earmark-pdf-fill me-1 text-danger"></i>Lihat Berkas Amandemen Item #<?php echo e($itemContract->amandement_no); ?>

                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    
                                    <?php if($isItemAmended && !empty($itemContract->alasan_penolakan)): ?>
                                        <div class="p-2 rounded bg-light-danger border border-danger mb-2 small text-danger">
                                            <div class="fw-bold mb-1"><i class="bi bi-x-octagon-fill me-1"></i>Catatan Penolakan Sales / Manajemen:</div>
                                            <div>"<?php echo e($itemContract->alasan_penolakan); ?>"</div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                
                                <?php if($itemContract && Auth::check() && !Auth::user()->isCustomer()): ?>
                                    <div class="mb-2">
                                        <a href="<?php echo e(route('contracts.show', $itemContract->id)); ?>" target="_blank" class="btn btn-xs btn-outline-info text-dark fw-semibold py-1 px-2 d-inline-block">
                                            <i class="bi bi-file-earmark-spreadsheet me-1"></i>Buka Lembar Tinjauan Kontrak (CRS)
                                        </a>
                                    </div>
                                <?php endif; ?>

                                
                                <?php if(Auth::check() && Auth::user()->role === 'customer'): ?>
                                    <div class="pt-2 border-top d-flex justify-content-end align-items-center gap-2">
                                        <?php if(strtolower($po->status) === 'production'): ?>
                                            <button class="btn btn-sm btn-secondary" disabled>
                                                <i class="bi bi-lock-fill me-1 text-warning"></i> In Production (Terkunci)
                                            </button>
                                        <?php elseif($itemContract && $itemContract->status === 'amandement_pending'): ?>
                                            <button class="btn btn-sm btn-warning text-dark" disabled>
                                                <i class="bi bi-hourglass-split me-1"></i> Sedang Ditinjau Sales & Manager
                                            </button>
                                        <?php elseif($itemContract && in_array($itemContract->status, ['amandement', 'amandement_approved'])): ?>
                                            <button class="btn btn-sm btn-success text-white" disabled>
                                                <i class="bi bi-check-circle-fill me-1"></i> Amandemen Disetujui (Diproses)
                                            </button>
                                        <?php elseif($itemAmendCount >= 2): ?>
                                            <button class="btn btn-sm btn-secondary" disabled title="Batas maksimal 2x amandemen telah tercapai">
                                                <i class="bi bi-slash-circle me-1 text-danger"></i> Batas Maksimal Amandemen (2/2)
                                            </button>
                                        <?php elseif(!$isUsingInternals || !$itemContract): ?>
                                            <button class="btn btn-sm btn-light border text-muted" disabled>
                                                <i class="bi bi-clock-history me-1"></i> Menunggu Pembuatan Kontrak Sales
                                            </button>
                                        <?php else: ?>
                                            <a href="<?php echo e(route('purchase-orders.create-amandement', ['id' => $po->id, 'internal_id' => $item->id])); ?>" 
                                               class="btn btn-sm btn-warning text-dark fw-bold px-3 py-2 shadow-sm">
                                                <i class="bi bi-pencil-square me-1"></i> Ajukan Amandemen Item Ini (Ke-<?php echo e($itemAmendCount + 1); ?>/2)
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <script>
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
    </script>

</div>


<div class="modal-footer">
    <button type="button" class="btn btn-danger font-weight-bold" data-bs-dismiss="modal">Close</button>
</div><?php /**PATH C:\laragon\www\sales_metinca\resources\views/purchase-orders/show-partial.blade.php ENDPATH**/ ?>