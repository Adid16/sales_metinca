
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
                            $activeStatus = in_array(strtolower($po->status), ['production', 'ship']) ? strtolower($po->status) : 'contract';
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
                    if (in_array(strtolower($po->status), ['production', 'ship'])) {
                        $activeStatus = strtolower($po->status);
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

                $progressWidth = '15%';
                $barColor = 'bg-info';
                $statusLabel = strtoupper($activeStatus);

                if ($activeStatus == 'sent') { 
                    $progressWidth = '15%'; $barColor = 'bg-info'; $statusLabel = 'SENT';
                }
                elseif ($activeStatus == 'amandement_pending') { 
                    $progressWidth = '30%'; $barColor = 'bg-warning text-dark'; $statusLabel = 'REVIEW AMANDEMEN';
                }
                elseif ($activeStatus == 'review') { 
                    $progressWidth = '45%'; $barColor = 'bg-warning text-dark'; $statusLabel = 'REVIEW KONTRAK';
                }
                elseif ($activeStatus == 'amandement') { 
                    $progressWidth = '55%'; $barColor = 'bg-warning text-dark'; $statusLabel = 'AMANDEMEN DISETUJUI';
                }
                elseif ($activeStatus == 'contract') { 
                    $progressWidth = '65%'; $barColor = 'bg-primary'; $statusLabel = 'KONTRAK DISETUJUI';
                }
                elseif ($activeStatus == 'production') { 
                    $progressWidth = '85%'; $barColor = 'bg-danger'; $statusLabel = 'PRODUCTION';
                }
                elseif ($activeStatus == 'ship') { 
                    $progressWidth = '100%'; $barColor = 'bg-success'; $statusLabel = 'SHIPPED';
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
                                <span class="text-danger font-weight-bold">PERINGATAN: Cairan logam sudah mulai dicor di lantai produksi PT. Metinca Prima. Spesifikasi data PO telah DIKUNCI TOTAL demi keselamatan produksi.</span>
                            <?php elseif($activeStatus == 'ship'): ?>
                                <span class="text-success font-weight-bold">PRODUKSI SELESAI! Produk pengecoran logam Anda telah lolos uji kualitas penuh dan saat ini dalam perjalanan pengiriman ke lokasi Anda.</span>
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
                    
                    
                    <tr>
                        <th class="bg-light text-secondary">Riwayat Referensi</th>
                        <td>
                            <?php
                                $latestContract = \App\Models\Contract::where('order_no', $po->po_no)
                                                                    ->orderByDesc('amandement_no')
                                                                    ->first();
                            ?>
                            <?php if($latestContract && $latestContract->amandement_no > 0): ?>
                                <span class="badge bg-warning text-white">Amandemen Ke-<?php echo e($latestContract->amandement_no); ?></span>
                            <?php else: ?>
                                <span class="text-muted">Original PO (Belum Diamandemen)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    
                    
                    <tr>
                        <th class="bg-light text-secondary">Berkas Lampiran</th>
                        <td>
                            <?php if(!empty($po->attachment)): ?>
                                <div class="d-flex flex-column gap-1">
                                    <?php $__currentLoopData = explode(',', $po->attachment); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($index == 0): ?>
                                            <a href="<?php echo e(asset('storage/uploads/' . trim($file))); ?>" target="_blank" class="btn btn-sm btn-info text-white py-1 px-2 mb-1 d-block text-start">
                                                <i class="bi bi-cloud-arrow-down-fill me-1"></i> Lihat PO Original
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo e(asset('storage/uploads/' . trim($file))); ?>" target="_blank" class="btn btn-sm btn-warning text-white py-1 px-2 mb-1 d-block text-start">
                                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Berkas Amandemen <?php echo e($index); ?>

                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <span class="text-muted small">Tidak ada lampiran berkas</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    
    <?php if($po->reason || $po->notes || ($latestContract && $latestContract->alasan_amandemen)): ?>
        <div class="row mt-2">
            <div class="col-12">
                <div class="form-group mb-0">
                    <label class="font-weight-bold text-dark small mb-1">Catatan / Alasan Perubahan Dokumen (Customer):</label>
                    <div class="p-2 border rounded bg-light small text-dark fst-italic">
                        "<?php echo e($po->reason ?? ($po->notes ?? ($latestContract->alasan_amandemen ?? ''))); ?>"
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    
    <?php if($latestContract && !empty($latestContract->alasan_penolakan)): ?>
        <div class="row mt-2">
            <div class="col-12">
                <div class="form-group mb-0">
                    <label class="font-weight-bold text-danger small mb-1"><i class="bi bi-x-octagon-fill me-1"></i> Catatan Penolakan Amandemen (Sales / Manajemen):</label>
                    <div class="p-2 border border-danger rounded bg-light-danger small text-danger fw-semibold">
                        "<?php echo e($latestContract->alasan_penolakan); ?>"
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- ========================================== -->
    <!-- DAFTAR ITEM & TINJAUAN KONTRAK PER-ITEM -->
    <!-- ========================================== -->
    <div class="mt-4">
        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-boxes me-2"></i>Rincian Item & Tinjauan Kontrak:</h6>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr class="text-secondary small text-uppercase">
                        <th class="text-center" style="width: 40px;">#</th>
                        <th>Part No</th>
                        <th>Nama Item / Part Name</th>
                        <th class="text-center">Qty</th>
                        <th>No. Tinjauan Kontrak</th>
                        <th class="text-center">Status Review</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $po->internals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $isHighlight = isset($selectedItem) && $selectedItem->id == $item->id;
                            
                            // Penentuan Status Item secara Dinamis di Modal Detail
                            if ($item->contract && in_array($item->contract->status, ['approved', 'done', 'contract'])) {
                                $displayStatus = in_array(strtolower($po->status), ['production', 'ship']) ? ucfirst($po->status) : 'Contract';
                                $badgeClass = 'bg-info text-dark';
                            } elseif ($item->contract && $item->contract->status === 'rejected') {
                                $displayStatus = 'Rejected';
                                $badgeClass = 'bg-danger text-white';
                            } elseif ($item->contract && $item->contract->status === 'amandement_pending') {
                                $displayStatus = 'Review Amandemen';
                                $badgeClass = 'bg-warning text-dark';
                            } elseif ($item->contract && in_array($item->contract->status, ['review', 'created', 'revision'])) {
                                $displayStatus = 'Review Kontrak';
                                $badgeClass = 'bg-warning text-dark';
                            } else {
                                // Jika kontrak 'created' atau belum ada, ikuti status PO Header
                                $displayStatus = ucfirst($po->status);
                                $badgeClass = ($po->status == 'sent') ? 'bg-primary text-white' : (($po->status == 'review') ? 'bg-warning text-dark' : 'bg-secondary');
                            }
                        ?>
                        <tr class="<?php echo e($isHighlight ? 'table-warning fw-bold' : ''); ?>">
                            <td class="text-center fw-bold"><?php echo e($index + 1); ?></td>
                            <td><code><?php echo e($item->contract->part_no ?? $item->part_no ?? '-'); ?></code></td>
                            <td class="fw-semibold text-dark">
                                <?php echo e($item->item); ?>

                                <?php if($isHighlight): ?>
                                    <span class="badge bg-primary ms-1"><i class="bi bi-check-circle-fill me-1"></i>Item Dipilih</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center fw-bold"><?php echo e(number_format($item->qty)); ?></td>
                            <td>
                                <?php if($item->contract): ?>
                                    <span class="badge bg-primary px-2 py-1"><?php echo e($item->contract->contract_no); ?></span>
                                <?php else: ?>
                                    <span class="text-muted small fst-italic">Belum Dibuat</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span class="badge <?php echo e($badgeClass); ?> px-2 py-1"><?php echo e($displayStatus); ?></span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Belum ada rincian item internal.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>


<div class="modal-footer">
    <button type="button" class="btn btn-danger font-weight-bold" data-bs-dismiss="modal">Close</button>
</div><?php /**PATH C:\laragon\www\sales_metinca\resources\views/purchase-orders/show-partial.blade.php ENDPATH**/ ?>