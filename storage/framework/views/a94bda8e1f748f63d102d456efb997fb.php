<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/extensions/flatpickr/flatpickr.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app-dark.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/quotation.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/extensions/choices.js/public/assets/styles/choices.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            
            
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show m-0 mb-3">
                    <i class="bi bi-check-circle me-1"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show m-0 mb-3">
                    <i class="bi bi-exclamation-triangle me-1"></i><?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card shadow-sm mb-3">
                <div class="card-header py-3 bg-info text-black">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-collection-fill me-2"></i>Detail Contract Review Sheet
                    </h5>
                </div>

                <div class="card text-center mb-0 border-0">
                    <div class="card-header text-center py-3 bg-transparent">
                        <h4 class="card-title mb-1 fw-bold">LEMBAR TINJAUAN KONTRAK</h4>
                        <h6 class="mb-0 text-muted">NO : <?php echo e($contract->order_no); ?></h6>
                    </div>
                </div>
                
                <div class="card-body px-4 py-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">CUSTOMER</label>
                            <input type="text" class="form-control form-control-sm"
                                value="<?php echo e($contract->customer->name ?? '-'); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">DATA RECORD</label>
                            <input type="text" class="form-control form-control-sm "
                                id="dataRecord" name="data_record" value= "<?php echo e(\Carbon\Carbon::parse($contract->created_at)->translatedFormat('d F Y')); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">ORDER NO <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm " name="order_no" value="<?php echo e($contract->order_no); ?>" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">AMANDEMENT NO</label>
                            <input type="text" class="form-control form-control-sm" name="amendment_no" 
                                value="<?php echo e($contract->amandement_no ?? '0'); ?><?php echo e(!empty($contract->alasan_amandemen) ? ' - ' . $contract->alasan_amandemen : ''); ?>" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">PART NAME</label>
                            <input type="text" class="form-control form-control-sm " id="partName" name="part_name" value="<?php echo e($contract->part_name); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">LOCATION</label>
                            <input type="text" class="form-control form-control-sm " id="location" name="location" value="<?php echo e($contract->article->lokasi_text ?? '-'); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">ARTICLE</label>
                            <input type="text" class="form-control form-control-sm" id="articleInput" value="<?php echo e($contract->article->article_no ?? '-'); ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <?php
                $departments = ['sales', 'quality', 'ppc', 'design engineering'];
                $deptLabels = [
                    'sales' => 'SALES',
                    'quality' => 'QUALITY',
                    'ppc' => 'PPC',
                    'design engineering' => 'DEVELOPMENT ENGINEERING',
                ];
                $deptColors  = [
                    'sales'              => '#0d6efd',
                    'quality'            => '#198754',
                    'ppc'                => '#fd7e14',
                    'design engineering' => '#6f42c1',
                ];
            ?>

            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $requirements = $grouped[$dept] ?? collect();
                    $color        = $deptColors[$dept]       ?? 'black';
                ?>

                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-info d-flex justify-content-between align-items-center py-2">
                        <h6 class="mb-0 fw-bold text-uppercase" style="font-size:0.82rem; letter-spacing:1px; color:black;">
                            <?php echo e($deptLabels[$dept] ?? strtoupper($dept)); ?>

                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="30%" class="text-center">Requirement</th>
                                    <th class="text-center">Action Required / Remark</th>
                                </tr>
                            </thead>
                            <tbody class="requirement-body" data-dept="<?php echo e($deptLabels[$dept] ?? strtoupper($dept)); ?>">
                                <?php $__currentLoopData = $requirements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value="<?php echo e($req['requirement']); ?>" readonly>
                                    </td>
                                    <td>
                                        <?php
                                            $isPriceRow = strtolower($req['requirement']) == 'price';
                                            $isAuthorized = auth()->user()->role == 'admin' || strtolower(auth()->user()->divisi) == 'sales';
                                            $maskedValue = ($isPriceRow && !$isAuthorized) ? '*** RAHASIA PERUSAHAAN ***' : $req['requirement_value'];
                                        ?>

                                        <input type="text" class="form-control form-control-sm <?php echo e(($isPriceRow && !$isAuthorized) ? 'text-danger fw-bold text-center' : ''); ?>" value="<?php echo e($maskedValue); ?>" readonly>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info py-2">
                    <h6 class="mb-0 fw-bold text-uppercase" style="font-size:0.82rem; letter-spacing:1px; color:black;">
                        Others / Comment
                    </h6>
                </div>
                <div class="card-body">
                    <textarea class="form-control mt-2" rows="3" readonly><?php echo e($contract->others_comment ?? '-'); ?></textarea>

                    
                    <?php if($contract->po_pdf): ?>
                        <div class="mt-3 p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                            <div class="text-start">
                                <span class="small fw-bold text-secondary d-block">Dokumen Lampiran PO Asli:</span>
                                <span class="small text-dark fw-semibold">
                                    <i class="bi bi-file-earmark-pdf-fill text-danger me-1"></i>Dokumen_PO_<?php echo e($contract->order_no); ?>.pdf
                                </span>
                            </div>
                            <a href="<?php echo e(asset('storage/' . $contract->po_pdf)); ?>" target="_blank" class="btn btn-sm btn-danger fw-bold shadow-sm">
                                <i class="bi bi-eye-fill me-1"></i> Lihat Dokumen PO
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="mt-3 p-2 border rounded bg-light text-start">
                            <small class="text-muted"><i class="bi bi-exclamation-circle me-1"></i> Tidak ada lampiran berkas PO PDF untuk kontrak ini.</small>
                        </div>
                    <?php endif; ?>

                    
                    <?php
                        $all4Approved = $contract->sales_approver 
                                     && $contract->ppc_approver 
                                     && $contract->quality_approver 
                                     && $contract->dev_engineering_approver;

                        $userDiv = strtolower(auth()->user()->divisi ?? '');
                        $isManagerOrAdmin = in_array(auth()->user()->role, ['manager', 'admin']);
                        $isSalesStaffOrAdmin = (auth()->user()->role == 'staff' && $userDiv == 'sales') || auth()->user()->role == 'admin';

                        $managerAlreadyApproved = false;
                        if ($userDiv == 'sales' && $contract->sales_approver) $managerAlreadyApproved = true;
                        if ($userDiv == 'quality' && $contract->quality_approver) $managerAlreadyApproved = true;
                        if (in_array($userDiv, ['ppc', 'ppic']) && $contract->ppc_approver) $managerAlreadyApproved = true;
                        if (in_array($userDiv, ['design engineering', 'de']) && $contract->dev_engineering_approver) $managerAlreadyApproved = true;
                    ?>

                    <div class="mt-4 text-end">                                
                        
                        <?php if($isManagerOrAdmin && !in_array($contract->status, ['production', 'done'])): ?>
                            <?php if($managerAlreadyApproved): ?>
                                <span class="badge bg-success py-2 px-3 me-1 fs-6">
                                    <i class="bi bi-check-circle-fill me-1"></i> Divisi <?php echo e(strtoupper(auth()->user()->divisi ?? 'Manager')); ?> Sudah Approve
                                </span>
                            <?php else: ?>
                                <button type="button" data-bs-target="#rejectModal" data-bs-toggle="modal" class="btn btn-sm btn-danger px-3 shadow-sm me-1">
                                    <i class="bi bi-x-circle-fill me-1"></i> Reject / Revisi
                                </button>
                                <button type="button" class="btn btn-sm btn-success px-3 shadow-sm me-1 fw-bold" data-bs-toggle="modal" data-bs-target="#approveModal">
                                    <i class="bi bi-check-circle-fill me-1"></i> Approve Divisi <?php echo e(strtoupper(auth()->user()->divisi ?? '')); ?>

                                </button>
                            <?php endif; ?>
                        <?php endif; ?>

                        
                        <?php if($isSalesStaffOrAdmin): ?>
                            <?php if(in_array($contract->status, ['production', 'done'])): ?>
                                <span class="badge bg-success py-2 px-3 me-1 fs-6">
                                    <i class="bi bi-gear-wide-connected me-1"></i> In Production (Dalam Produksi)
                                </span>
                            <?php elseif($all4Approved): ?>
                                <form action="<?php echo e(route('contracts.finalize', $contract->id)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-success text-white fw-bold px-3 shadow-sm me-1" onclick="return confirm('Seluruh 4 Manager telah menyetujui. Apakah Anda yakin ingin memfinalisasi kontrak ini ke tahap In Production?')">
                                        <i class="bi bi-gear-fill me-1"></i> Finalisasi ke Produksi (In Production)
                                    </button>
                                </form>
                            <?php else: ?>
                                <span data-bs-toggle="tooltip" title="Finalisasi baru dapat dilakukan setelah 4 Manager (Sales, Quality, PPIC, DE) memberikan Approve.">
                                    <button class="btn btn-sm btn-secondary px-3 shadow-sm me-1" disabled>
                                        <i class="bi bi-clock-history me-1 text-warning"></i> Finalisasi (Menunggu 4 Manager)
                                    </button>
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>

                        
                        <?php if(in_array(auth()->user()->role, ['admin', 'staff']) && in_array($contract->status, ['created', 'revision', 'review'])): ?>
                            <a href="<?php echo e(route('contracts.edit', $contract->id)); ?>" class="btn btn-sm btn-warning px-3 shadow-sm me-1">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </a>
                        <?php endif; ?>

                        
                        <button type="button" class="btn btn-sm btn-info text-white px-3 shadow-sm me-1 fw-bold" data-bs-toggle="modal" data-bs-target="#auditTrailModal">
                            <i class="bi bi-clock-history me-1"></i> Riwayat / Rekam Jejak Order
                        </button>

                        
                        <a href="<?php echo e(route('contracts.index')); ?>" class="btn btn-sm btn-secondary px-3 shadow-sm">
                            <i class="bi bi-arrow-left-circle me-1"></i> Back
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>


<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white" id="approveModalLabel">
                    <i class="bi bi-pen-fill me-2"></i>Persetujuan Tinjauan Kontrak
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="<?php echo e(route('contracts.approve-manager', $contract->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                
                <div class="modal-body text-center">
                    <p class="mb-2 fw-bold text-dark">Silakan gambar tanda tangan Anda di bawah ini:</p>
                    
                    <div class="border rounded d-inline-block shadow-sm" style="background: #ffffff; border: 2px solid #dee2e6 !important;">
                        <canvas id="signature-pad" width="400" height="200"></canvas>
                    </div>
                    <input type="hidden" name="signature" id="signature_base64">
                    
                    <div class="mt-2 text-end" style="width: 400px; margin: 0 auto;">
                        <button type="button" class="btn btn-sm btn-outline-danger border-0" id="clear-signature">
                            <i class="bi bi-eraser-fill"></i> Bersihkan Area
                        </button>
                    </div>
                </div>
                
                
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-success fw-bold" id="btn-submit-approve">
                        <i class="bi bi-check-circle-fill me-1"></i> Approve Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="auditTrailModal" tabindex="-1" aria-labelledby="auditTrailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title text-white" id="auditTrailModalLabel">
                    <i class="bi bi-clock-history me-2"></i>Riwayat & Rekam Jejak Order (Audit Trail)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <div class="card bg-light border mb-3">
                    <div class="card-body py-2 px-3">
                        <h6 class="fw-bold text-primary mb-2"><i class="bi bi-person-badge me-1"></i> Rekapitulasi Riwayat Transaksi Customer: <?php echo e($contract->customer->name ?? '-'); ?></h6>
                        <div class="row text-center g-2">
                            <div class="col-4">
                                <div class="p-2 border rounded bg-white">
                                    <small class="text-muted d-block">Total Quotation</small>
                                    <span class="fs-5 fw-bold text-primary"><?php echo e($customerTotalQuotation ?? 0); ?></span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 border rounded bg-white">
                                    <small class="text-muted d-block">Total PO</small>
                                    <span class="fs-5 fw-bold text-success"><?php echo e($customerTotalPO ?? 0); ?></span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 border rounded bg-white">
                                    <small class="text-muted d-block">Total Kontrak</small>
                                    <span class="fs-5 fw-bold text-info"><?php echo e($customerTotalContracts ?? 0); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-chat-dots-fill me-1"></i> Log Siklus Negosiasi Harga pada PO Ini:</h6>
                <?php if(isset($negotiations) && $negotiations->count() > 0): ?>
                    <div class="timeline border rounded p-3 mb-3 bg-white" style="max-height: 200px; overflow-y: auto;">
                        <?php $__currentLoopData = $negotiations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $neg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-2 mb-2 rounded border-start border-3 <?php echo e($neg->from_customer ? 'border-primary bg-light' : 'border-success bg-light-success'); ?>">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <strong><?php echo e($neg->user->name ?? ($neg->from_customer ? 'Customer' : 'Sales Team')); ?></strong>
                                    <span><?php echo e($neg->created_at ? $neg->created_at->format('d-m-Y H:i') : '-'); ?></span>
                                </div>
                                <div class="small fw-semibold text-dark">
                                    Total Negosiasi: <span class="text-primary">Rp <?php echo e(number_format($neg->negotiated_total)); ?></span> | Status: <span class="badge bg-secondary"><?php echo e(ucfirst($neg->action)); ?></span>
                                </div>
                                <?php if($neg->message): ?>
                                    <div class="small text-muted fst-italic mt-1">"<?php echo e($neg->message); ?>"</div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted small border rounded p-3 bg-light mb-3">Tidak ada catatan riwayat negosiasi harga pada order ini.</p>
                <?php endif; ?>

                
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-pencil-square me-1"></i> Rekapitulasi Amandemen Item:</h6>
                <?php if(isset($amendmentHistory) && $amendmentHistory->count() > 0): ?>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-bordered text-nowrap align-middle">
                            <thead class="table-secondary small">
                                <tr>
                                    <th>Rev #</th>
                                    <th>Target Item</th>
                                    <th>Alasan Amandemen Customer</th>
                                    <th>Status Approval</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                <?php $__currentLoopData = $amendmentHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-center fw-bold">#<?php echo e($amend->amandement_no); ?></td>
                                        <td><?php echo e($amend->internalItem->item ?? '-'); ?></td>
                                        <td><?php echo e(Str::limit($amend->alasan_amandemen ?? '-', 50)); ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-<?php echo e($amend->status == 'amandement' ? 'success' : ($amend->status == 'amandement_pending' ? 'warning text-dark' : 'secondary')); ?>">
                                                <?php echo e(ucfirst($amend->status)); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted small border rounded p-3 bg-light mb-3">Order ini belum pernah mengalami perubahan/amandemen.</p>
                <?php endif; ?>

                
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-activity me-1"></i> Log Aktivitas Sistem:</h6>
                <?php if(isset($orderActivities) && $orderActivities->count() > 0): ?>
                    <ul class="list-group list-group-flush border rounded small" style="max-height: 150px; overflow-y: auto;">
                        <?php $__currentLoopData = $orderActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2">
                                <span><i class="bi bi-check2-circle text-success me-1"></i> <?php echo e($act->activity); ?></span>
                                <span class="text-muted" style="font-size: 0.75rem;"><?php echo e(\Carbon\Carbon::parse($act->activity_time)->format('d-m-Y H:i')); ?></span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted small border rounded p-3 bg-light mb-0">Tidak ada log aktivitas khusus untuk order ini.</p>
                <?php endif; ?>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var canvas = document.getElementById('signature-pad');
        var approveModal = document.getElementById('approveModal');
        var signaturePad;

        // Inisialisasi Signature Pad secara akurat setelah modal ditampilkan penuh
        approveModal.addEventListener('shown.bs.modal', function () {
            if (!signaturePad) {
                signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: 'rgb(0, 0, 0)'
                });
            } else {
                signaturePad.clear(); // Bersihkan pad lama jika terbuka kembali
            }
        });

        // Tombol bersihkan tanda tangan
        document.getElementById('clear-signature').addEventListener('click', function () {
            if(signaturePad) signaturePad.clear();
        });

        // Validasi dan konversi canvas menjadi Base64 string sebelum disubmit ke server
        document.getElementById('btn-submit-approve').addEventListener('click', function (e) {
            if (signaturePad && signaturePad.isEmpty()) {
                e.preventDefault();
                alert("Tanda tangan wajib digambar terlebih dahulu sebelum melakukan Approve!");
            } else if (signaturePad) {
                document.getElementById('signature_base64').value = signaturePad.toDataURL();
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/contracts/show.blade.php ENDPATH**/ ?>