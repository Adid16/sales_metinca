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
                <div class="card-header py-3 bg-info text-black d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-collection-fill me-2"></i>Detail Contract Review Sheet
                    </h5>
                    <div>
                        <?php if($contract->status == 'amended'): ?>
                            <span class="badge bg-danger fs-6"><i class="bi bi-lock-fill me-1"></i>Amended</span>
                        <?php elseif($contract->status == 'amandement_rejected'): ?>
                            <span class="badge bg-danger fs-6"><i class="bi bi-x-circle-fill me-1"></i>Amandemen Ditolak</span>
                        <?php elseif($contract->status == 'amandement_pending'): ?>
                            <span class="badge bg-warning text-dark fs-6"><i class="bi bi-clock-history me-1"></i>Review Amandemen</span>
                        <?php elseif($contract->status == 'revision' || $contract->status == 'rejected'): ?>
                            <span class="badge bg-danger fs-6"><i class="bi bi-x-octagon-fill me-1"></i>Ditolak</span>
                        <?php elseif($contract->status == 'production'): ?>
                            <span class="badge bg-success fs-6"><i class="bi bi-gear-wide-connected me-1"></i>In Production</span>
                        <?php elseif($contract->status == 'done'): ?>
                            <span class="badge bg-primary fs-6"><i class="bi bi-check2-all me-1"></i>Done</span>
                        <?php elseif($contract->status == 'approved'): ?>
                            <span class="badge bg-success fs-6"><i class="bi bi-check-circle-fill me-1"></i>Approved</span>
                        <?php elseif($contract->status == 'review'): ?>
                            <span class="badge bg-warning text-dark fs-6"><i class="bi bi-clock me-1"></i>Review</span>
                        <?php else: ?>
                            <span class="badge bg-secondary fs-6"><?php echo e(ucfirst($contract->status)); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                
                <?php if($contract->amandement_no > 0 && !empty($contract->alasan_amandemen) && $contract->alasan_amandemen !== '-'): ?>
                    <div class="card border-warning border-2 shadow-sm mb-3 mx-4" style="background-color: #fffdf0 !important;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-chat-left-quote-fill text-warning fs-5 me-2"></i>
                                <strong class="text-dark">Catatan / Alasan Pengajuan Amandemen dari Customer (Ke-<?php echo e($contract->amandement_no); ?>):</strong>
                            </div>
                            <div class="fst-italic text-dark ps-4 fw-bold fs-6 text-primary">
                                "<?php echo e($contract->alasan_amandemen); ?>"
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

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
                            <input type="text" class="form-control form-control-sm fw-bold bg-light text-dark" name="amendment_no" 
                                value="<?php echo e($contract->amandement_no > 0 ? 'Amandemen Ke-' . $contract->amandement_no : '0 (Original Contract)'); ?>" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">ALASAN AMANDEMEN (CUSTOMER)</label>
                            <input type="text" class="form-control form-control-sm fw-semibold bg-light text-dark" 
                                value="<?php echo e($contract->alasan_amandemen ?? '-'); ?>" readonly>
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
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">SALES PIC (PENANGGUNG JAWAB)</label>
                            <input type="text" class="form-control form-control-sm text-primary fw-bold" id="salesPicInput" value="<?php echo e($contract->sales_pic->name ?? '-'); ?>" readonly>
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
                        <?php
                            $approverId = match($dept) {
                                'sales' => $contract->sales_approver,
                                'quality' => $contract->quality_approver,
                                'ppc' => $contract->ppc_approver,
                                'design engineering' => $contract->dev_engineering_approver,
                                default => null
                            };
                            $approvedAt = match($dept) {
                                'sales' => $contract->sales_approved_at,
                                'quality' => $contract->quality_approved_at,
                                'ppc' => $contract->ppc_approved_at,
                                'design engineering' => $contract->dev_engineering_approved_at,
                                default => null
                            };
                            $approverSig = match($dept) {
                                'sales' => $contract->manager_sales_signature,
                                'quality' => $contract->manager_quality_signature,
                                'ppc' => $contract->manager_ppc_signature,
                                'design engineering' => $contract->manager_de_signature,
                                default => null
                            };
                            $rejectReason = match($dept) {
                                'sales' => $contract->sales_reject_reason,
                                'quality' => $contract->quality_reject_reason,
                                'ppc' => $contract->ppc_reject_reason,
                                'design engineering' => $contract->dev_engineering_reject_reason,
                                default => null
                            };
                            $rejectedAt = match($dept) {
                                'sales' => $contract->sales_rejected_at,
                                'quality' => $contract->quality_rejected_at,
                                'ppc' => $contract->ppc_rejected_at,
                                'design engineering' => $contract->dev_engineering_rejected_at,
                                default => null
                            };
                            $approverUser = $approverId ? \App\Models\User::find($approverId) : null;
                        ?>
                        <?php if($approverId): ?>
                            <div class="d-flex align-items-center gap-2">
                                <?php if(!empty($approverSig)): ?>
                                    <img src="<?php echo e($approverSig); ?>" alt="Signature" style="max-height: 28px; background: white; padding: 2px; border-radius: 4px;" class="border">
                                <?php endif; ?>
                                <span class="badge bg-success" style="font-size: 0.75rem;">
                                    <i class="bi bi-check-circle-fill me-1"></i>Approved: <?php echo e($approverUser->name ?? 'Manager'); ?> (<?php echo e(\Carbon\Carbon::parse($approvedAt)->format('d/m/Y')); ?>)
                                </span>
                            </div>
                        <?php elseif(!empty($rejectReason)): ?>
                            <button type="button" class="btn btn-sm btn-danger px-2 py-0 btn-show-reject-modal shadow-sm"
                                data-dept="<?php echo e($deptLabels[$dept] ?? strtoupper($dept)); ?>"
                                data-reason="<?php echo e($rejectReason); ?>"
                                data-date="<?php echo e($rejectedAt ? \Carbon\Carbon::parse($rejectedAt)->format('d/m/Y H:i') : ''); ?>"
                                title="Klik untuk melihat catatan alasan penolakan">
                                <i class="bi bi-x-circle-fill me-1"></i>Rejected (Klik untuk Alasan)
                            </button>
                        <?php else: ?>
                            <span class="badge bg-secondary" style="font-size: 0.75rem;">
                                <i class="bi bi-clock me-1"></i>Menunggu Persetujuan
                            </span>
                        <?php endif; ?>
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

                        $managerAlreadyRejected = false;
                        $managerRejectReason = null;
                        $managerRejectedAt = null;
                        if ($userDiv == 'sales' && !empty($contract->sales_reject_reason)) {
                            $managerAlreadyRejected = true;
                            $managerRejectReason = $contract->sales_reject_reason;
                            $managerRejectedAt = $contract->sales_rejected_at;
                        }
                        if ($userDiv == 'quality' && !empty($contract->quality_reject_reason)) {
                            $managerAlreadyRejected = true;
                            $managerRejectReason = $contract->quality_reject_reason;
                            $managerRejectedAt = $contract->quality_rejected_at;
                        }
                        if (in_array($userDiv, ['ppc', 'ppic']) && !empty($contract->ppc_reject_reason)) {
                            $managerAlreadyRejected = true;
                            $managerRejectReason = $contract->ppc_reject_reason;
                            $managerRejectedAt = $contract->ppc_rejected_at;
                        }
                        if (in_array($userDiv, ['design engineering', 'de']) && !empty($contract->dev_engineering_reject_reason)) {
                            $managerAlreadyRejected = true;
                            $managerRejectReason = $contract->dev_engineering_reject_reason;
                            $managerRejectedAt = $contract->dev_engineering_rejected_at;
                        }
                    ?>

                    <div class="mt-4 text-end">                                
                        
                        <?php if($isManagerOrAdmin && !in_array($contract->status, ['production', 'done'])): ?>
                            <?php if($managerAlreadyApproved): ?>
                                <span class="badge bg-success py-2 px-3 me-1 fs-6">
                                    <i class="bi bi-check-circle-fill me-1"></i> Divisi <?php echo e(strtoupper(auth()->user()->divisi ?? 'Manager')); ?> Sudah Approve
                                </span>
                            <?php elseif($managerAlreadyRejected): ?>
                                <span class="badge bg-danger py-2 px-3 me-1 fs-6 btn-show-reject-modal shadow-sm"
                                    data-dept="<?php echo e(strtoupper(auth()->user()->divisi ?? 'Manager')); ?>"
                                    data-reason="<?php echo e($managerRejectReason); ?>"
                                    data-date="<?php echo e($managerRejectedAt ? \Carbon\Carbon::parse($managerRejectedAt)->format('d/m/Y H:i') : ''); ?>"
                                    style="cursor: pointer;"
                                    title="Klik untuk melihat catatan alasan penolakan Anda">
                                    <i class="bi bi-x-circle-fill me-1"></i> Divisi <?php echo e(strtoupper(auth()->user()->divisi ?? 'Manager')); ?> Telah Menolak (Menunggu Revisi Tim Sales)
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
                            <?php
                                $salesPic = $contract->sales_pic;
                                $isPicOrAdmin = auth()->user()->isAdmin() || (auth()->user()->isManager() && $userDiv === 'sales') || (auth()->user()->isStaff() && $userDiv === 'sales' && (!$salesPic || $salesPic->id === auth()->id()));
                            ?>
                            <?php if(in_array($contract->status, ['production', 'done'])): ?>
                                <span class="badge bg-success py-2 px-3 me-1 fs-6">
                                    <i class="bi bi-gear-wide-connected me-1"></i> In Production (Dalam Produksi)
                                </span>
                            <?php elseif($all4Approved): ?>
                                <?php if($isPicOrAdmin): ?>
                                    <form action="<?php echo e(route('contracts.finalize', $contract->id)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-success text-white fw-bold px-3 shadow-sm me-1" onclick="return confirm('Seluruh 4 Divisi telah menyetujui. Apakah Anda sebagai PIC Sales yakin ingin memfinalisasi kontrak ini ke tahap In Production?')">
                                            <i class="bi bi-gear-fill me-1"></i> Finalisasi ke Produksi (Sales PIC)
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span data-bs-toggle="tooltip" title="Hanya Sales PIC (<?php echo e($salesPic->name ?? 'Sales PIC'); ?>) yang berhak memfinalisasi pesanan ini ke tahap produksi.">
                                        <button class="btn btn-sm btn-secondary px-3 shadow-sm me-1" disabled>
                                            <i class="bi bi-person-lock me-1 text-warning"></i> Menunggu Finalisasi Sales PIC (<?php echo e($salesPic->name ?? 'PIC'); ?>)
                                        </button>
                                    </span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span data-bs-toggle="tooltip" title="Finalisasi baru dapat dilakukan setelah 4 Divisi (Sales, Quality, PPIC, DE) memberikan Approve.">
                                    <button class="btn btn-sm btn-secondary px-3 shadow-sm me-1" disabled>
                                        <i class="bi bi-clock-history me-1 text-warning"></i> Finalisasi (Menunggu 4 Divisi)
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

                        
                        <a href="<?php echo e(route('contract.pdf', $contract->id)); ?>" target="_blank" class="btn btn-sm btn-danger px-3 shadow-sm me-1 fw-bold">
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Download PDF
                        </a>

                        
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
            
            <form action="<?php echo e(route('contracts.approve-manager', $contract->id)); ?>" method="POST" id="form-approve-contract">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                
                <div class="modal-body text-center">
                    <p class="mb-2 fw-bold text-dark">Silakan gambar atau upload foto tanda tangan Anda:</p>
                    
                    <div class="border rounded d-inline-block shadow-sm" style="background: #ffffff; border: 2px solid #dee2e6 !important;">
                        <canvas id="signature-pad" width="400" height="200" style="touch-action: none; cursor: crosshair;"></canvas>
                    </div>
                    <input type="hidden" name="signature" id="signature_base64">
                    <input type="file" id="upload-signature-file" accept="image/png, image/jpeg, image/jpg" class="d-none">
                    
                    <div class="mt-2 d-flex justify-content-between align-items-center" style="width: 400px; margin: 0 auto;">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-trigger-upload">
                            <i class="bi bi-upload me-1"></i> Upload Foto Tanda Tangan
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="clear-signature">
                            <i class="bi bi-eraser-fill me-1"></i> Bersihkan Area
                        </button>
                    </div>
                    <small class="text-muted d-block mt-2" style="font-size: 11px;">
                        * Anda dapat menggambar langsung di kotak atas atau klik tombol <b>Upload Foto Tanda Tangan</b>.
                    </small>
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


<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title text-white" id="rejectModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Tolak / Minta Revisi Kontrak
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('contracts.reject-manager', $contract->id)); ?>" method="POST" id="form-reject-contract">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="alert alert-warning alert-permanent py-2 small mb-3">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Anda akan menolak spesifikasi kontrak ini sebagai <b>Manager <?php echo e(strtoupper(auth()->user()->divisi ?? '')); ?></b>. Silakan berikan alasan atau instruksi revisi yang jelas untuk Tim Sales.
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label fw-bold small text-dark">Alasan Penolakan / Catatan Revisi <span class="text-danger">*</span></label>
                        <textarea name="comment" id="comment" class="form-control" rows="4" placeholder="Contoh: Spesifikasi material tidak sesuai standar drawing, harap direvisi..." required minlength="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-danger fw-bold">
                        <i class="bi bi-x-circle-fill me-1"></i> Kirim Penolakan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="auditTrailModal" tabindex="-1" aria-labelledby="auditTrailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            
            <div class="modal-header bg-gradient text-white" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div>
                    <h5 class="modal-title text-white fw-bold mb-1" id="auditTrailModalLabel">
                        <i class="bi bi-clock-history me-2"></i>Riwayat & Rekam Jejak Pesanan (End-to-End Audit Trail)
                    </h5>
                    <small class="text-white-50">
                        No. Kontrak: <strong><?php echo e($contract->contract_no); ?></strong> | No. PO: <strong><?php echo e($contract->order_no); ?></strong> | Item: <strong><?php echo e($contract->part_name ?? ($contract->internalItem->item ?? '-')); ?></strong>
                    </small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-3 p-md-4 bg-light">
                
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-3">
                        <div class="row align-items-center g-3">
                            <div class="col-md-7">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle me-3">
                                        <i class="bi bi-building fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark"><?php echo e($contract->customer->name ?? '-'); ?></h6>
                                        <small class="text-muted"><?php echo e($contract->customer->company ?? $contract->customer->account->company ?? 'Perusahaan Customer'); ?></small>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-2 small">
                                    <span class="badge bg-primary px-2 py-1"><i class="bi bi-person-badge me-1"></i> Sales PIC: <?php echo e($salesPic->name ?? 'Unassigned'); ?></span>
                                    <?php
                                        $cStat = strtolower($contract->status);
                                        $statBadge = match($cStat) {
                                            'production' => 'bg-success',
                                            'approved'   => 'bg-info text-white',
                                            'review'     => 'bg-warning text-dark',
                                            'revision'   => 'bg-danger',
                                            'amandement_pending' => 'bg-warning text-dark',
                                            'amandement' => 'bg-secondary',
                                            default      => 'bg-secondary'
                                        };
                                        $currentUser = auth()->user();
                                        $canSeePrice = $currentUser && ($currentUser->isAdmin() || $currentUser->divisi === 'sales');
                                    ?>
                                    <span class="badge <?php echo e($statBadge); ?> px-2 py-1"><i class="bi bi-flag-fill me-1"></i> Status Saat Ini: <?php echo e(ucfirst($contract->status)); ?></span>
                                    <?php if($contract->amandement_no > 0): ?>
                                        <span class="badge bg-dark px-2 py-1"><i class="bi bi-pencil-square me-1"></i> Revisi #<?php echo e($contract->amandement_no); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="row text-center g-2">
                                    <div class="col-4">
                                        <div class="p-2 border rounded bg-white shadow-xs">
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">Total Quotation</small>
                                            <span class="fs-5 fw-bold text-primary"><?php echo e($customerTotalQuotation ?? 0); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 border rounded bg-white shadow-xs">
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">Total PO</small>
                                            <span class="fs-5 fw-bold text-success"><?php echo e($customerTotalPO ?? 0); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 border rounded bg-white shadow-xs">
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">Total Kontrak</small>
                                            <span class="fs-5 fw-bold text-info"><?php echo e($customerTotalContracts ?? 0); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <ul class="nav nav-pills nav-fill bg-white p-2 rounded shadow-sm mb-3 border" id="auditTrailTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active small fw-bold py-2" id="tab-overview-btn" data-bs-toggle="pill" data-bs-target="#tab-overview" type="button" role="tab" aria-selected="true">
                            <i class="bi bi-diagram-3-fill me-1"></i> 1. Alur & Ringkasan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link small fw-bold py-2" id="tab-request-btn" data-bs-toggle="pill" data-bs-target="#tab-request" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-inbox-fill me-1"></i> 2. Request Project
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link small fw-bold py-2" id="tab-quotation-btn" data-bs-toggle="pill" data-bs-target="#tab-quotation" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-receipt me-1"></i> 3. Penawaran Awal
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link small fw-bold py-2" id="tab-nego-btn" data-bs-toggle="pill" data-bs-target="#tab-nego" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-chat-dots-fill me-1"></i> 4. Negosiasi (<?php echo e($negotiations->count()); ?>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link small fw-bold py-2" id="tab-po-btn" data-bs-toggle="pill" data-bs-target="#tab-po" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-cart-check-fill me-1"></i> 5. PO & Item Internal
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link small fw-bold py-2" id="tab-amendment-btn" data-bs-toggle="pill" data-bs-target="#tab-amendment" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-arrow-repeat me-1"></i> 6. Amandemen
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link small fw-bold py-2" id="tab-contract-btn" data-bs-toggle="pill" data-bs-target="#tab-contract" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-check2-square me-1"></i> 7. Review 4 Divisi
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link small fw-bold py-2 d-flex align-items-center" id="tab-activity-btn" data-bs-toggle="pill" data-bs-target="#tab-activity" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-journal-text me-1"></i> 8. Log Audit
                            <?php if($orderActivities->count() > 0): ?>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill ms-1 extra-small"><?php echo e($orderActivities->count()); ?></span>
                            <?php endif; ?>
                        </button>
                    </li>
                </ul>

                
                <div class="tab-content" id="auditTrailTabContent">

                    
                    <div class="tab-pane fade show active" id="tab-overview" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3 p-md-4">
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-bezier2 text-primary me-2"></i>Rekam Jejak Kronologis Siklus Transaksi Pesanan</h6>
                                <div class="timeline-stepper py-2">
                                    <div class="row g-3">
                                        
                                        <div class="col-md-3">
                                            <div class="p-3 border rounded bg-white h-100 position-relative border-start border-4 border-primary">
                                                <div class="small fw-bold text-primary mb-1">Tahap 1: Request Project</div>
                                                <div class="fw-semibold small text-dark mb-1">
                                                    <?php echo e($requestProject ? '#' . $requestProject->id . ' - ' . Str::limit($requestProject->subject, 25) : 'Inquiry Proyek'); ?>

                                                </div>
                                                <div class="text-muted extra-small mb-1">
                                                    <i class="bi bi-clock me-1"></i><?php echo e($requestProject ? $requestProject->created_at->format('d/m/Y H:i') : '-'); ?>

                                                </div>
                                                <span class="badge bg-light-primary text-primary border border-primary small">PIC: <?php echo e($salesPic->name ?? 'Unassigned'); ?></span>
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-3">
                                            <div class="p-3 border rounded bg-white h-100 position-relative border-start border-4 border-info">
                                                <div class="small fw-bold text-info mb-1">Tahap 2: Quotation & Nego</div>
                                                <div class="fw-semibold small text-dark mb-1">
                                                    <?php echo e($quotation ? $quotation->quotation_no : 'Penawaran'); ?>

                                                </div>
                                                <div class="text-muted extra-small mb-1">
                                                    <i class="bi bi-clock me-1"></i><?php echo e($quotation ? $quotation->created_at->format('d/m/Y H:i') : '-'); ?>

                                                </div>
                                                <span class="badge bg-<?php echo e($negotiations->count() > 0 ? 'warning text-dark' : 'success'); ?> small">
                                                    <?php echo e($negotiations->count() > 0 ? $negotiations->count() . 'x Nego Saling Balas' : 'Deal Sesuai Penawaran'); ?>

                                                </span>
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-3">
                                            <div class="p-3 border rounded bg-white h-100 position-relative border-start border-4 border-warning">
                                                <div class="small fw-bold text-warning mb-1">Tahap 3: Purchase Order</div>
                                                <div class="fw-semibold small text-dark mb-1">
                                                    <?php echo e($purchaseOrder->po_no ?? $contract->order_no); ?>

                                                </div>
                                                <div class="text-muted extra-small mb-1">
                                                    <i class="bi bi-calendar-check me-1"></i>Kirim: <?php echo e($purchaseOrder && $purchaseOrder->delivery_request ? \Carbon\Carbon::parse($purchaseOrder->delivery_request)->format('d/m/Y') : '-'); ?>

                                                </div>
                                                <span class="badge bg-info text-white small"><?php echo e($internalItems->count()); ?> Sub-Item Internal</span>
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-3">
                                            <div class="p-3 border rounded bg-white h-100 position-relative border-start border-4 border-success">
                                                <div class="small fw-bold text-success mb-1">Tahap 4: Review Kontrak & Produksi</div>
                                                <div class="fw-semibold small text-dark mb-1">
                                                    <?php echo e($contract->contract_no); ?>

                                                </div>
                                                <div class="text-muted extra-small mb-1">
                                                    4 Divisi: 
                                                    <span class="text-<?php echo e($contract->sales_approver ? 'success' : 'muted'); ?> fw-bold">S</span>
                                                    <span class="text-<?php echo e($contract->quality_approver ? 'success' : 'muted'); ?> fw-bold">Q</span>
                                                    <span class="text-<?php echo e($contract->ppc_approver ? 'success' : 'muted'); ?> fw-bold">P</span>
                                                    <span class="text-<?php echo e($contract->dev_engineering_approver ? 'success' : 'muted'); ?> fw-bold">D</span>
                                                </div>
                                                <span class="badge <?php echo e($statBadge); ?> small">Status: <?php echo e(ucfirst($contract->status)); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="tab-request" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3 p-md-4">
                                <?php if($requestProject): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-inbox me-2"></i>Tiket Permintaan Proyek #REQ-<?php echo e($requestProject->id); ?></h6>
                                        <span class="badge bg-secondary"><?php echo e($requestProject->created_at->format('d F Y, H:i')); ?></span>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded border">
                                                <small class="text-muted d-block mb-1">Data Pengirim / Customer:</small>
                                                <div class="fw-bold text-dark"><?php echo e($requestProject->name ?? '-'); ?></div>
                                                <div class="small text-muted"><?php echo e($requestProject->email ?? '-'); ?> | <?php echo e($requestProject->phone ?? '-'); ?></div>
                                                <div class="small fw-semibold text-primary mt-1"><?php echo e($requestProject->company ?? '-'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded border">
                                                <small class="text-muted d-block mb-1">Penugasan Sales PIC (Claimed):</small>
                                                <div class="fw-bold text-success"><i class="bi bi-person-check-fill me-1"></i> <?php echo e($salesPic->name ?? 'Unassigned'); ?></div>
                                                <div class="small text-muted"><?php echo e($salesPic->email ?? '-'); ?> (Divisi Sales)</div>
                                                <div class="small text-muted mt-1">Status: Tiket Resmi Dikelola Sales PIC</div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="p-3 bg-light rounded border">
                                                <small class="text-muted d-block mb-1">Subjek Permintaan:</small>
                                                <div class="fw-bold text-dark mb-2"><?php echo e($requestProject->subject ?? '-'); ?></div>
                                                <small class="text-muted d-block mb-1">Detail Kebutuhan & Pesan Customer:</small>
                                                <div class="p-2 bg-white rounded border small text-dark fst-italic">
                                                    <?php echo e(!empty($requestProject->message) ? $requestProject->message : 'Tidak ada catatan pesan tambahan dari customer.'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <?php if($requestProject->attachments && $requestProject->attachments->count() > 0): ?>
                                            <div class="col-12">
                                                <small class="text-muted d-block mb-1">Lampiran Dokumen / Drawing Awal Customer:</small>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <?php $__currentLoopData = $requestProject->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <a href="<?php echo e(asset('storage/' . $att->file_path)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-paperclip me-1"></i><?php echo e($att->file_name ?? 'Lampiran Drawing'); ?>

                                                        </a>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="p-4 text-center text-muted bg-light rounded">
                                        <i class="bi bi-info-circle fs-3 text-secondary mb-2 d-block"></i>
                                        Tidak ada tiket Request Project terhubung. Transaksi ini diinput langsung oleh tim sales ke sistem.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="tab-quotation" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3 p-md-4">
                                <?php if($quotation): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                        <div>
                                            <h6 class="fw-bold text-primary mb-0"><i class="bi bi-receipt me-2"></i>Quotation Resmi: <?php echo e($quotation->quotation_no); ?></h6>
                                            <small class="text-muted">Diterbitkan: <?php echo e($quotation->created_at->format('d F Y, H:i')); ?> | Expired: <?php echo e($quotation->date_expired ? \Carbon\Carbon::parse($quotation->date_expired)->format('d F Y') : '-'); ?></small>
                                        </div>
                                        <span class="badge bg-<?php echo e($quotation->status == 'accepted' || $quotation->status == 'po' ? 'success' : 'primary'); ?> px-3 py-2">
                                            Status: <?php echo e(ucfirst($quotation->status)); ?>

                                        </span>
                                    </div>
                                    <div class="row g-2 mb-3 small">
                                        <div class="col-md-6">
                                            <div class="p-2 border rounded bg-white">
                                                <span class="text-muted">Syarat Pembayaran:</span> <strong class="text-dark"><?php echo e($quotation->payment_terms ?? 'Net 30 Days'); ?></strong>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-2 border rounded bg-white">
                                                <span class="text-muted">Target Pengiriman:</span> <strong class="text-dark"><?php echo e($quotation->target_delivery_date ?? '-'); ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered align-middle mb-0">
                                            <thead class="table-light small">
                                                <tr>
                                                    <th class="text-center" style="width: 40px;">No</th>
                                                    <th>Nama Part / Artikel</th>
                                                    <th class="text-center" style="width: 80px;">Qty</th>
                                                    <th class="text-end" style="width: 150px;">Harga Satuan Awal</th>
                                                    <th class="text-end" style="width: 150px;">Subtotal Awal</th>
                                                </tr>
                                            </thead>
                                            <tbody class="small">
                                                <?php $initTotal = 0; ?>
                                                <?php $__empty_1 = true; $__currentLoopData = $quotation->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $qItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <?php 
                                                        $sub = $qItem->qty * $qItem->original_price;
                                                        $initTotal += $sub;
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo e($idx + 1); ?></td>
                                                        <td>
                                                            <strong><?php echo e($qItem->item); ?></strong>
                                                            <?php if($qItem->article): ?>
                                                                <small class="text-muted d-block">Part No: <?php echo e($qItem->article->article_no); ?></small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center fw-bold"><?php echo e(number_format($qItem->qty)); ?></td>
                                                        <?php if($canSeePrice): ?>
                                                            <td class="text-end">Rp <?php echo e(number_format($qItem->original_price, 2)); ?></td>
                                                            <td class="text-end fw-bold">Rp <?php echo e(number_format($sub, 2)); ?></td>
                                                        <?php else: ?>
                                                            <td class="text-center text-muted fst-italic"><span class="badge bg-light text-muted border"><i class="bi bi-lock-fill me-1"></i>Khusus Sales</span></td>
                                                            <td class="text-center text-muted fst-italic"><span class="badge bg-light text-muted border"><i class="bi bi-lock-fill me-1"></i>Khusus Sales</span></td>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <tr><td colspan="5" class="text-center text-muted py-2">Tidak ada item tercatat.</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot class="table-light small fw-bold">
                                                <tr>
                                                    <td colspan="4" class="text-end">Total Penawaran Awal:</td>
                                                    <td class="text-end <?php echo e($canSeePrice ? 'text-primary' : 'text-muted'); ?>">
                                                        <?php if($canSeePrice): ?>
                                                            Rp <?php echo e(number_format($initTotal, 2)); ?>

                                                        <?php else: ?>
                                                            <span class="badge bg-light text-muted border"><i class="bi bi-lock-fill me-1"></i>Khusus Divisi Sales</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="p-4 text-center text-muted bg-light rounded">
                                        <i class="bi bi-info-circle fs-3 text-secondary mb-2 d-block"></i>
                                        Tidak ada data Quotation terhubung pada kontrak ini.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="tab-nego" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3 p-md-4">
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                    <h6 class="fw-bold text-dark mb-0">
                                        <i class="bi bi-chat-left-text-fill text-primary me-2"></i>Log Siklus Riwayat Negosiasi Harga
                                    </h6>
                                    <span class="badge bg-primary"><?php echo e($negotiations->count()); ?>x Riwayat Negosiasi</span>
                                </div>
                                <?php if($negotiations->count() > 0): ?>
                                    <div class="timeline position-relative">
                                        <?php $__currentLoopData = $negotiations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $negIndex => $neg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="card border mb-3 shadow-xs <?php echo e($neg->from_customer ? 'border-primary' : 'border-success'); ?>">
                                                <div class="card-header py-2 px-3 <?php echo e($neg->from_customer ? 'bg-light-primary text-primary' : 'bg-light-success text-success'); ?> d-flex justify-content-between align-items-center">
                                                    <div class="small fw-bold">
                                                        <i class="bi bi-<?php echo e($neg->from_customer ? 'person-fill' : 'headset'); ?> me-1"></i>
                                                        Tahap Negosiasi #<?php echo e($negIndex + 1); ?>: <?php echo e($neg->user->name ?? ($neg->from_customer ? 'Customer' : 'Tim Sales')); ?>

                                                        <span class="badge bg-white <?php echo e($neg->from_customer ? 'text-primary' : 'text-success'); ?> border ms-1">
                                                            <?php echo e($neg->from_customer ? 'Pengajuan Customer' : 'Tanggapan Tim Sales'); ?>

                                                        </span>
                                                    </div>
                                                    <div class="small text-muted">
                                                        <i class="bi bi-clock me-1"></i><?php echo e($neg->created_at ? $neg->created_at->format('d M Y H:i:s') : '-'); ?>

                                                    </div>
                                                </div>
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                                        <div>
                                                            <span class="text-muted small">Total Penawaran:</span>
                                                            <?php if($canSeePrice): ?>
                                                                <span class="fs-6 fw-bold <?php echo e($neg->from_customer ? 'text-primary' : 'text-success'); ?> ms-1">
                                                                    Rp <?php echo e(number_format($neg->negotiated_total, 2)); ?>

                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge bg-light text-muted border ms-1"><i class="bi bi-lock-fill me-1"></i>Khusus Divisi Sales</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div>
                                                            <span class="badge bg-<?php echo e($neg->action == 'accept' || $neg->action == 'closed' ? 'success' : 'secondary'); ?> px-2 py-1">
                                                                Aksi: <?php echo e(ucfirst($neg->action)); ?>

                                                            </span>
                                                            <?php if($neg->requires_manager_approval): ?>
                                                                <span class="badge bg-danger px-2 py-1 ms-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>Di Bawah Batas Bawah</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>

                                                    
                                                    <?php
                                                        $itemsList = is_array($neg->negotiated_items) 
                                                            ? $neg->negotiated_items 
                                                            : (is_string($neg->negotiated_items) ? json_decode($neg->negotiated_items, true) : null);
                                                    ?>

                                                    <?php if(!empty($itemsList) && is_array($itemsList) && count($itemsList) > 0): ?>
                                                        <div class="table-responsive my-2">
                                                            <table class="table table-sm table-bordered align-middle bg-white mb-0" style="font-size: 0.8rem;">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th class="text-center" style="width: 35px;">No</th>
                                                                        <th>Nama Part / Item</th>
                                                                        <th class="text-center" style="width: 60px;">Qty</th>
                                                                        <th class="text-end" style="width: 140px;">Harga Satuan Tawaran</th>
                                                                        <th class="text-end" style="width: 140px;">Subtotal</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php $__currentLoopData = $itemsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $iIdx => $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php
                                                                            $unitPrice = (float) ($it['negotiated_price'] ?? ($it['price'] ?? 0));
                                                                            $qty = (int) ($it['qty'] ?? 1);
                                                                            $origPrice = (float) ($it['original_price'] ?? 0);
                                                                            $sub = (float) ($it['subtotal'] ?? ($unitPrice * $qty));
                                                                        ?>
                                                                        <tr>
                                                                            <td class="text-center text-muted"><?php echo e($iIdx + 1); ?></td>
                                                                            <td>
                                                                                <strong class="text-dark"><?php echo e($it['item'] ?? ($it['name'] ?? 'Part')); ?></strong>
                                                                                <?php if($canSeePrice && $origPrice > 0 && $origPrice != $unitPrice): ?>
                                                                                    <small class="text-muted d-block text-decoration-line-through" style="font-size: 0.72rem;">
                                                                                        Harga Awal: Rp <?php echo e(number_format($origPrice, 2)); ?>

                                                                                    </small>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                            <td class="text-center fw-bold"><?php echo e(number_format($qty)); ?></td>
                                                                            <?php if($canSeePrice): ?>
                                                                                <td class="text-end fw-semibold <?php echo e($neg->from_customer ? 'text-primary' : 'text-success'); ?>">
                                                                                    Rp <?php echo e(number_format($unitPrice, 2)); ?>

                                                                                </td>
                                                                                <td class="text-end fw-bold text-dark">
                                                                                    Rp <?php echo e(number_format($sub, 2)); ?>

                                                                                </td>
                                                                            <?php else: ?>
                                                                                <td class="text-center text-muted fst-italic">
                                                                                    <span class="badge bg-light text-muted border"><i class="bi bi-lock-fill me-1"></i>Khusus Sales</span>
                                                                                </td>
                                                                                <td class="text-center text-muted fst-italic">
                                                                                    <span class="badge bg-light text-muted border"><i class="bi bi-lock-fill me-1"></i>Khusus Sales</span>
                                                                                </td>
                                                                            <?php endif; ?>
                                                                        </tr>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if(!empty($neg->message)): ?>
                                                        <div class="p-2 bg-light rounded small text-dark fst-italic my-2 border">
                                                            <i class="bi bi-chat-quote me-1 text-muted"></i> "<?php echo e($neg->message); ?>"
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if($neg->manager_approved_by): ?>
                                                        <div class="p-2 bg-light-success border border-success rounded small text-success mt-2">
                                                            <i class="bi bi-check-circle-fill me-1"></i>
                                                            <strong>Disetujui Manager Sales (<?php echo e($neg->manager->name ?? 'Manager'); ?>):</strong> 
                                                            <?php echo e($neg->manager_approval_note ?? 'Persetujuan harga khusus telah dikonfirmasi.'); ?>

                                                            <span class="text-muted float-end"><?php echo e($neg->manager_approved_at ? $neg->manager_approved_at->format('d/m/Y H:i') : ''); ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="p-4 text-center text-muted bg-light rounded">
                                        <i class="bi bi-check-circle fs-3 text-success mb-2 d-block"></i>
                                        Pesanan ini disepakati langsung sesuai harga penawaran awal tanpa ada proses negosiasi harga.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="tab-po" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3 p-md-4">
                                <?php if($purchaseOrder): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-file-earmark-text-fill text-primary me-2"></i>Purchase Order: <?php echo e($purchaseOrder->po_no); ?></h6>
                                            <small class="text-muted">Tanggal: <?php echo e($purchaseOrder->created_at->format('d F Y, H:i')); ?> | Delivery Request: <?php echo e($purchaseOrder->delivery_request ? \Carbon\Carbon::parse($purchaseOrder->delivery_request)->format('d F Y') : '-'); ?></small>
                                        </div>
                                        <div>
                                            <?php if($purchaseOrder->attachment): ?>
                                                <a href="<?php echo e(asset('storage/' . $purchaseOrder->attachment)); ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-file-earmark-pdf-fill me-1"></i>Unduh PO Customer (PDF)
                                                </a>
                                            <?php endif; ?>
                                            <span class="badge bg-success ms-1 px-2 py-1">Status: <?php echo e(ucfirst($purchaseOrder->status)); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <h6 class="fw-bold text-secondary mb-2 small"><i class="bi bi-list-task me-1"></i> Rincian Sub-Item PO Internal:</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered align-middle mb-0">
                                        <thead class="table-light small">
                                            <tr>
                                                <th class="text-center" style="width: 40px;">No</th>
                                                <th>No Sub-PO</th>
                                                <th>Nama Part / Item</th>
                                                <th>Material & Spesifikasi</th>
                                                <th class="text-center" style="width: 70px;">Qty</th>
                                                <th class="text-end" style="width: 130px;">Harga Satuan</th>
                                                <th class="text-end" style="width: 140px;">Subtotal</th>
                                                <th class="text-center" style="width: 90px;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="small">
                                            <?php $poiTotal = 0; ?>
                                            <?php $__empty_1 = true; $__currentLoopData = $internalItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pIdx => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <?php $poiTotal += ($item->qty * $item->unit_price); ?>
                                                <tr class="<?php echo e($item->id == $contract->purchase_order_internal_id ? 'table-warning fw-bold' : ''); ?>">
                                                    <td class="text-center"><?php echo e($pIdx + 1); ?></td>
                                                    <td><code><?php echo e($item->po_no ?? '-'); ?></code></td>
                                                    <td>
                                                        <?php echo e($item->item); ?>

                                                        <?php if($item->id == $contract->purchase_order_internal_id): ?>
                                                            <span class="badge bg-primary ms-1">Item Aktif Ini</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted"><?php echo e($item->material ?? '-'); ?> | <?php echo e($item->spesifikasi ?? '-'); ?></small>
                                                    </td>
                                                    <td class="text-center"><?php echo e(number_format($item->qty)); ?></td>
                                                    <?php if($canSeePrice): ?>
                                                        <td class="text-end">Rp <?php echo e(number_format($item->unit_price, 2)); ?></td>
                                                        <td class="text-end fw-bold">Rp <?php echo e(number_format($item->qty * $item->unit_price, 2)); ?></td>
                                                    <?php else: ?>
                                                        <td class="text-center text-muted fst-italic"><span class="badge bg-light text-muted border"><i class="bi bi-lock-fill me-1"></i>Khusus Sales</span></td>
                                                        <td class="text-center text-muted fst-italic"><span class="badge bg-light text-muted border"><i class="bi bi-lock-fill me-1"></i>Khusus Sales</span></td>
                                                    <?php endif; ?>
                                                    <td class="text-center">
                                                        <span class="badge bg-<?php echo e($item->status == 'production' ? 'success' : 'secondary'); ?>">
                                                            <?php echo e(ucfirst($item->status)); ?>

                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr><td colspan="8" class="text-center text-muted py-2">Tidak ada item internal.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                        <tfoot class="table-light small fw-bold">
                                            <tr>
                                                <td colspan="6" class="text-end">Total PO Internal:</td>
                                                <td class="text-end text-success">
                                                    <?php if($canSeePrice): ?>
                                                        Rp <?php echo e(number_format($poiTotal, 2)); ?>

                                                    <?php else: ?>
                                                        <span class="badge bg-light text-muted border"><i class="bi bi-lock-fill me-1"></i>Khusus Divisi Sales</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="tab-amendment" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3 p-md-4">
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-arrow-repeat text-warning me-2"></i>Rekapitulasi Seluruh Pengajuan Amandemen</h6>
                                    <span class="badge bg-dark">Maksimal 2x Amandemen</span>
                                </div>

                                
                                <?php if($amendmentHistory->count() > 0): ?>
                                    <div class="table-responsive mb-3">
                                        <table class="table table-sm table-bordered align-middle">
                                            <thead class="table-light small">
                                                <tr>
                                                    <th class="text-center" style="width: 70px;">Revisi</th>
                                                    <th>Target Sub-PO / Item</th>
                                                    <th>Alasan Amandemen Customer</th>
                                                    <th class="text-center" style="width: 120px;">Status Keputusan</th>
                                                </tr>
                                            </thead>
                                            <tbody class="small">
                                                <?php $__currentLoopData = $amendmentHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td class="text-center fw-bold">
                                                            <span class="badge bg-dark">#<?php echo e($amend->amandement_no); ?></span>
                                                        </td>
                                                        <td>
                                                            <strong><?php echo e($amend->order_no); ?></strong>
                                                            <small class="text-muted d-block"><?php echo e($amend->internalItem->item ?? ($amend->part_name ?? '-')); ?></small>
                                                        </td>
                                                        <td>
                                                            <span class="fw-semibold text-dark"><?php echo e($amend->alasan_amandemen ?? '-'); ?></span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-<?php echo e($amend->status == 'production' ? 'success' : ($amend->status == 'amandement_pending' ? 'warning text-dark' : 'secondary')); ?>">
                                                                <?php echo e(ucfirst($amend->status)); ?>

                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>

                                
                                <?php if($amendmentEvents->count() > 0): ?>
                                    <h6 class="fw-bold text-secondary mb-2 small"><i class="bi bi-clock-history me-1"></i> Riwayat Keputusan Amandemen (Termasuk Penolakan & Alasan):</h6>
                                    <div class="list-group list-group-flush border rounded small mb-0">
                                        <?php $__currentLoopData = $amendmentEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aEvent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="list-group-item py-2 px-3 <?php echo e($aEvent->action_info['border']); ?> bg-white">
                                                <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-1">
                                                    <div class="d-flex align-items-center flex-wrap gap-1">
                                                        <span class="badge <?php echo e($aEvent->action_info['badge_class']); ?> extra-small">
                                                            <i class="<?php echo e($aEvent->action_info['icon']); ?> me-1"></i><?php echo e($aEvent->action_info['label']); ?>

                                                        </span>
                                                        <?php echo $aEvent->user_badge_html; ?>

                                                        <span class="fw-bold text-dark extra-small"><?php echo e($aEvent->user->name ?? 'Sistem'); ?></span>
                                                    </div>
                                                    <span class="text-muted extra-small"><i class="bi bi-clock me-1"></i><?php echo e($aEvent->formatted_time); ?></span>
                                                </div>
                                                <div class="ps-1 text-dark small leading-relaxed">
                                                    <?php echo $aEvent->formatted_html; ?>

                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php elseif($amendmentHistory->count() == 0): ?>
                                    <div class="p-4 text-center text-muted bg-light rounded">
                                        <i class="bi bi-shield-check fs-3 text-success mb-2 d-block"></i>
                                        Pesanan ini berjalan lancar dan belum pernah mengalami perubahan/amandemen.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="tab-contract" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3 p-md-4">
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-shield-lock-fill text-primary me-2"></i>Status Persetujuan Lembar Kontrak (4 Divisi Manajerial)</h6>
                                        <small class="text-muted">No. Kontrak: <strong><?php echo e($contract->contract_no); ?></strong> | Drawing: <strong><?php echo e($contract->article->drawing_no ?? '-'); ?></strong> (Rev <?php echo e($contract->article->drawing_rev ?? '-'); ?>)</small>
                                    </div>
                                    <span class="badge <?php echo e($statBadge); ?> px-3 py-2">
                                        Status: <?php echo e(ucfirst($contract->status)); ?>

                                    </span>
                                </div>

                                <div class="row g-3">
                                    
                                    <div class="col-md-6 col-lg-3">
                                        <div class="p-3 border rounded bg-white h-100 shadow-xs border-top border-3 border-<?php echo e($contract->sales_approver ? 'success' : 'warning'); ?>">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong class="text-dark small"><i class="bi bi-briefcase me-1"></i> 1. Divisi Sales</strong>
                                                <i class="bi bi-<?php echo e($contract->sales_approver ? 'check-circle-fill text-success' : 'hourglass text-warning'); ?> fs-5"></i>
                                            </div>
                                            <div class="small mb-1">
                                                <span class="text-muted">Approver:</span>
                                                <div class="fw-bold text-dark"><?php echo e($contract->salesApprover->name ?? ($contract->sales_approver ? 'Manager Sales' : 'Menunggu')); ?></div>
                                            </div>
                                            <div class="extra-small text-muted mb-2">
                                                <i class="bi bi-clock me-1"></i><?php echo e($contract->sales_approved_at ? \Carbon\Carbon::parse($contract->sales_approved_at)->format('d/m/Y H:i') : '-'); ?>

                                            </div>
                                            <span class="badge bg-<?php echo e($contract->sales_approver ? 'success' : 'warning text-dark'); ?> small w-100">
                                                <?php echo e($contract->sales_approver ? 'Approved (Ttd Digital)' : 'Pending Review'); ?>

                                            </span>
                                            <?php if($contract->sales_reject_reason): ?>
                                                <div class="p-2 bg-light-danger text-danger border border-danger rounded extra-small mt-2">
                                                    <strong>Catatan Revisi:</strong> <?php echo e($contract->sales_reject_reason); ?>

                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-6 col-lg-3">
                                        <div class="p-3 border rounded bg-white h-100 shadow-xs border-top border-3 border-<?php echo e($contract->quality_approver ? 'success' : 'warning'); ?>">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong class="text-dark small"><i class="bi bi-award me-1"></i> 2. Divisi Quality (QC)</strong>
                                                <i class="bi bi-<?php echo e($contract->quality_approver ? 'check-circle-fill text-success' : 'hourglass text-warning'); ?> fs-5"></i>
                                            </div>
                                            <div class="small mb-1">
                                                <span class="text-muted">Approver:</span>
                                                <div class="fw-bold text-dark"><?php echo e($contract->qualityApprover->name ?? ($contract->quality_approver ? 'Manager QC' : 'Menunggu')); ?></div>
                                            </div>
                                            <div class="extra-small text-muted mb-2">
                                                <i class="bi bi-clock me-1"></i><?php echo e($contract->quality_approved_at ? \Carbon\Carbon::parse($contract->quality_approved_at)->format('d/m/Y H:i') : '-'); ?>

                                            </div>
                                            <span class="badge bg-<?php echo e($contract->quality_approver ? 'success' : 'warning text-dark'); ?> small w-100">
                                                <?php echo e($contract->quality_approver ? 'Approved (Ttd Digital)' : 'Pending Review'); ?>

                                            </span>
                                            <?php if($contract->quality_reject_reason): ?>
                                                <div class="p-2 bg-light-danger text-danger border border-danger rounded extra-small mt-2">
                                                    <strong>Catatan Revisi:</strong> <?php echo e($contract->quality_reject_reason); ?>

                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-6 col-lg-3">
                                        <div class="p-3 border rounded bg-white h-100 shadow-xs border-top border-3 border-<?php echo e($contract->ppc_approver ? 'success' : 'warning'); ?>">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong class="text-dark small"><i class="bi bi-calendar3 me-1"></i> 3. Divisi PPC (PPIC)</strong>
                                                <i class="bi bi-<?php echo e($contract->ppc_approver ? 'check-circle-fill text-success' : 'hourglass text-warning'); ?> fs-5"></i>
                                            </div>
                                            <div class="small mb-1">
                                                <span class="text-muted">Approver:</span>
                                                <div class="fw-bold text-dark"><?php echo e($contract->ppcApprover->name ?? ($contract->ppc_approver ? 'Manager PPC' : 'Menunggu')); ?></div>
                                            </div>
                                            <div class="extra-small text-muted mb-2">
                                                <i class="bi bi-clock me-1"></i><?php echo e($contract->ppc_approved_at ? \Carbon\Carbon::parse($contract->ppc_approved_at)->format('d/m/Y H:i') : '-'); ?>

                                            </div>
                                            <span class="badge bg-<?php echo e($contract->ppc_approver ? 'success' : 'warning text-dark'); ?> small w-100">
                                                <?php echo e($contract->ppc_approver ? 'Approved (Ttd Digital)' : 'Pending Review'); ?>

                                            </span>
                                            <?php if($contract->ppc_reject_reason): ?>
                                                <div class="p-2 bg-light-danger text-danger border border-danger rounded extra-small mt-2">
                                                    <strong>Catatan Revisi:</strong> <?php echo e($contract->ppc_reject_reason); ?>

                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-6 col-lg-3">
                                        <div class="p-3 border rounded bg-white h-100 shadow-xs border-top border-3 border-<?php echo e($contract->dev_engineering_approver ? 'success' : 'warning'); ?>">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong class="text-dark small"><i class="bi bi-cpu me-1"></i> 4. Divisi Design Eng.</strong>
                                                <i class="bi bi-<?php echo e($contract->dev_engineering_approver ? 'check-circle-fill text-success' : 'hourglass text-warning'); ?> fs-5"></i>
                                            </div>
                                            <div class="small mb-1">
                                                <span class="text-muted">Approver:</span>
                                                <div class="fw-bold text-dark"><?php echo e($contract->devEngineeringApprover->name ?? ($contract->dev_engineering_approver ? 'Manager DE' : 'Menunggu')); ?></div>
                                            </div>
                                            <div class="extra-small text-muted mb-2">
                                                <i class="bi bi-clock me-1"></i><?php echo e($contract->dev_engineering_approved_at ? \Carbon\Carbon::parse($contract->dev_engineering_approved_at)->format('d/m/Y H:i') : '-'); ?>

                                            </div>
                                            <span class="badge bg-<?php echo e($contract->dev_engineering_approver ? 'success' : 'warning text-dark'); ?> small w-100">
                                                <?php echo e($contract->dev_engineering_approver ? 'Approved (Ttd Digital)' : 'Pending Review'); ?>

                                            </span>
                                            <?php if($contract->dev_engineering_reject_reason): ?>
                                                <div class="p-2 bg-light-danger text-danger border border-danger rounded extra-small mt-2">
                                                    <strong>Catatan Revisi:</strong> <?php echo e($contract->dev_engineering_reject_reason); ?>

                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="tab-activity" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3 p-md-4">
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-shield-shaded text-primary me-2"></i>Audit Trail & Rekam Jejak Sistem (Urutan Kronologis)</h6>
                                        <small class="text-muted">Riwayat lengkap mulai dari Request Project, Quotation, Negosiasi, PO External & Internal, Amandemen, Review 4 Divisi, hingga Finalisasi Produksi</small>
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 small fw-semibold">
                                        <i class="bi bi-journal-check me-1"></i>Total <?php echo e($orderActivities->count()); ?> Aktivitas
                                    </span>
                                </div>

                                <?php if($orderActivities->count() > 0): ?>
                                    <div class="activity-timeline-container" style="max-height: 480px; overflow-y: auto;">
                                        <div class="timeline position-relative ps-1 pe-2">
                                            <?php $__currentLoopData = $orderActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="p-3 mb-2 bg-light rounded border <?php echo e($act->action_info['border']); ?> shadow-xs">
                                                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                                        <div class="d-flex align-items-center flex-wrap gap-1">
                                                            <span class="badge <?php echo e($act->module_info['class']); ?> small px-2 py-1">
                                                                <i class="<?php echo e($act->module_info['icon']); ?> me-1"></i><?php echo e($act->module_info['name']); ?>

                                                            </span>
                                                            <span class="badge <?php echo e($act->action_info['badge_class']); ?> small px-2 py-1">
                                                                <i class="<?php echo e($act->action_info['icon']); ?> me-1"></i><?php echo e($act->action_info['label']); ?>

                                                            </span>
                                                            <?php echo $act->user_badge_html; ?>

                                                            <strong class="text-dark small ms-1"><?php echo e($act->user->name ?? 'Sistem'); ?></strong>
                                                        </div>
                                                        <div class="text-muted extra-small">
                                                            <i class="bi bi-clock me-1"></i><?php echo e(\Carbon\Carbon::parse($act->activity_time)->translatedFormat('d M Y, H:i:s')); ?> WIB
                                                            <span class="badge bg-white text-secondary border ms-1"><?php echo e($act->time_ago); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="ps-1 text-dark small leading-relaxed">
                                                        <?php echo $act->formatted_html; ?>

                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="p-4 text-center text-muted bg-light rounded">
                                        <i class="bi bi-journal-x fs-2 text-muted d-block mb-2"></i>
                                        Tidak ada catatan riwayat audit trail khusus untuk pesanan ini.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            
            <div class="modal-footer bg-white border-top py-2 px-3 d-flex justify-content-between">
                <small class="text-muted">
                    <i class="bi bi-shield-check text-success me-1"></i> Log audit trail terintegrasi penuh dari Request Project sampai Kontrak & Produksi.
                </small>
                <button type="button" class="btn btn-sm btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Tutup</button>
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
        var uploadInput = document.getElementById('upload-signature-file');
        var btnTriggerUpload = document.getElementById('btn-trigger-upload');
        var btnClear = document.getElementById('clear-signature');
        var approveForm = document.getElementById('form-approve-contract');
        var signaturePad;
        var hasUploadedImage = false;

        // Inisialisasi Signature Pad secara akurat setelah modal ditampilkan penuh
        if (approveModal && canvas) {
            approveModal.addEventListener('shown.bs.modal', function () {
                if (!signaturePad) {
                    signaturePad = new SignaturePad(canvas, {
                        backgroundColor: 'rgba(255, 255, 255, 0)',
                        penColor: 'rgb(0, 0, 0)'
                    });
                } else {
                    signaturePad.clear();
                    hasUploadedImage = false;
                    document.getElementById('signature_base64').value = '';
                }
            });
        }

        // Tombol Trigger Upload File Foto Tanda Tangan
        if (btnTriggerUpload && uploadInput) {
            btnTriggerUpload.addEventListener('click', function() {
                uploadInput.click();
            });

            uploadInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (!file.type.match('image.*')) {
                        Swal.fire({ icon: 'error', title: 'Format Salah', text: 'Harap upload file gambar (PNG, JPG, JPEG).' });
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const img = new Image();
                        img.onload = function() {
                            const ctx = canvas.getContext('2d');
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            
                            // Hitung skala gambar agar pas di dalam canvas
                            const hRatio = canvas.width / img.width;
                            const vRatio = canvas.height / img.height;
                            const ratio  = Math.min(hRatio, vRatio, 1);
                            const drawWidth = img.width * ratio;
                            const drawHeight = img.height * ratio;
                            const centerShiftX = (canvas.width - drawWidth) / 2;
                            const centerShiftY = (canvas.height - drawHeight) / 2;

                            ctx.drawImage(img, 0, 0, img.width, img.height, centerShiftX, centerShiftY, drawWidth, drawHeight);
                            document.getElementById('signature_base64').value = canvas.toDataURL('image/png');
                            hasUploadedImage = true;
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Foto Dimuat!',
                                text: 'Foto tanda tangan berhasil dimuat ke kanvas.',
                                timer: 1200,
                                showConfirmButton: false
                            });
                        };
                        img.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Tombol bersihkan tanda tangan
        if (btnClear) {
            btnClear.addEventListener('click', function () {
                if(signaturePad) signaturePad.clear();
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                document.getElementById('signature_base64').value = '';
                if (uploadInput) uploadInput.value = '';
                hasUploadedImage = false;
            });
        }

        // Validasi dan konversi canvas menjadi Base64 string sebelum disubmit ke server
        if (approveForm) {
            approveForm.addEventListener('submit', function (e) {
                var sigBase64Input = document.getElementById('signature_base64');
                
                if (signaturePad && !signaturePad.isEmpty()) {
                    sigBase64Input.value = signaturePad.toDataURL('image/png');
                }

                if (!sigBase64Input.value && (!signaturePad || signaturePad.isEmpty()) && !hasUploadedImage) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tanda Tangan Kosong!',
                        text: 'Silakan gambar tanda tangan Anda atau upload foto tanda tangan terlebih dahulu sebelum melakukan Approve.',
                        confirmButtonText: 'Siap'
                    });
                    return false;
                }
            });
        }

        // Listener untuk tombol pop-up alasan penolakan
        document.querySelectorAll('.btn-show-reject-modal').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var dept = this.getAttribute('data-dept');
                var reason = this.getAttribute('data-reason');
                var date = this.getAttribute('data-date');
                
                Swal.fire({
                    icon: 'error',
                    title: 'Penolakan oleh Manager ' + dept,
                    html: '<div class="text-start mt-2 p-3 bg-light rounded border">' +
                          '<p class="mb-1 text-muted small"><i class="bi bi-calendar-event me-1"></i> <b>Tanggal Penolakan:</b> ' + (date || '-') + '</p>' +
                          '<p class="mb-0 text-dark"><i class="bi bi-chat-left-quote-fill text-danger me-1"></i> <b>Catatan / Alasan:</b><br><span class="fst-italic text-danger fw-semibold">"' + reason + '"</span></p>' +
                          '</div>' +
                          '<div class="text-muted small mt-3"><i class="bi bi-info-circle me-1"></i> Silakan klik tombol <b>Edit</b> untuk merevisi data yang ditolak.</div>',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#435ebe'
                });
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/contracts/show.blade.php ENDPATH**/ ?>