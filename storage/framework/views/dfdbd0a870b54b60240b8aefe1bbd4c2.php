<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/extensions/flatpickr/flatpickr.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app-dark.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/quotation.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/extensions/choices.js/public/assets/styles/choices.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card detail-card">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-file-earmark-text-fill"></i> Add New Quotation
            </h5>
        </div>
    <section id="horizontal-input">
        <div class="row">
            <div class="col-md-12">
                    <div class="card-body">
                        <form action="<?php echo e(route('quotations.store')); ?>" method="POST" id="quotationForm" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class ="col-md-2">Request Id</div>
                                    <div class="col-md-9 mb-2">
                                        <input type="text" name="request_id" id="idrequest" class="form-control form-control-sm" value="<?php echo e($requestProject->id ?? ''); ?>" readonly>
                                    </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">Customer Id</div>
                                    <div class="col-md-9 mb-2">
                                        <input type="text" name="customer_id" class="form-control form-control-sm" value="<?php echo e($requestProject->customer_id ?? ''); ?>" readonly>
                                    </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">Customer Name</div>
                                    <div class="col-md-9 mb-2">
                                        <input type="text" name="customer_name" id="customerName" class="form-control form-control-sm" value="<?php echo e($requestProject->name ?? ''); ?>" readonly>
                                    </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2 ">Company</div>
                                <div class="col-md-9 mb-2">
                                    <input type="text" name="customer_company" id="company" class="form-control form-control-sm" value="<?php echo e(request('company') ?? ($requestProject->company ?? ($requestProject->customer->company ?? ($customerAccount->company ?? '')))); ?>" readonly>
                                </div>  
                            </div>

                            <div class="row">
                                <div class="col-md-2">Quotation No</div>
                                    <div class="col-md-9 mb-2">
                                        <input type="text" name="quotation_no" id="numberQuotation" class="form-control form-control-sm" value="<?php echo e($quotationNo); ?>" readonly>
                                    </div>
                            </div>

                            <div class="card shadow-sm mb-3 mt-3">
                                <div class="card-header py-2" style="background:#e9ecef;">
                                    <h6 class="mb-0 fw-bold text-uppercase" style="font-size:0.78rem; letter-spacing:1px; color:#6c757d;">
                                        <i class="bi bi-paperclip me-1"></i>Dokumen Request dari Customer (Referensi)
                                    </h6>
                                </div>
                                <div class="card-body p-2">
                                    <?php if($requestProject && $requestProject->attachments && $requestProject->attachments->count() > 0): ?>
                                        <?php $__currentLoopData = $requestProject->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="mb-3 <?php echo e(!$loop->last ? 'border-bottom pb-3' : ''); ?>">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="fw-semibold" style="font-size:13px">
                                                        <i class="bi bi-file-earmark-pdf-fill text-danger me-1"></i>
                                                        <?php echo e($attachment->document_name ?? basename($attachment->file_path)); ?>

                                                    </span>
                                                    <a href="<?php echo e(asset('storage/' . $attachment->file_path)); ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-box-arrow-up-right me-1"></i>Open new tab
                                                    </a>
                                                </div>
                                                <iframe src="<?php echo e(asset('storage/' . $attachment->file_path)); ?>" width="100%" height="400px" style="border:1px solid #dee2e6; border-radius:4px;"></iframe>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <p class="text-muted fst-italic mb-0">Tidak ada attachment dari customer.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div id="item-container">
                                <div class="row item-row mb-2">
                                    <div class="col-md-2">Item</div>
                                    <div class="col-md-3">
                                        <input type="text" name="item[]" class="form-control form-control-sm" placeholder="Item name">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="qty[]" class="form-control form-control-sm" placeholder="Qty">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="price[]" step="0.01" min="0" class="form-control form-control-sm" placeholder="Price">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-sm btn-success btn-add-item">
                                            <i class="bi bi-plus-lg"></i> 
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">Delivery Date</div>
                                <div class="col-md-9 mb-2">
                                    <input type="date" name="target_delivery_date" id="targetDeliveryDate" class="form-control form-control-sm" min="<?php echo e(now()->format('Y-m-d')); ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">Date Expired</div>
                                <div class="col-md-9 mb-2">
                                    <input type="date" required name="date_expired" id="dateExpired" class="form-control form-control-sm flatpickr">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">Payment Terms</div>
                                <div class="col-md-9 mb-2">
                                   <select name="payment_terms" id="paymentTerms" class="form-select form-select-sm">
                                        <option value="">-- Pilih Payment Terms --</option>
                                        <option value="cash">Cash</option>
                                        <option value="net_30">Net 30</option>
                                        <option value="net_60">Net 60</option>
                                        <option value="dp_50">DP 50%</option>
                                        <option value="installment">Installment</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <input type="text" id="paymentTermsCustom" class="form-control form-control-sm mt-2" placeholder="Tulis payment terms custom, misal: Net 45, DP 30% + Net 60" style="display:none;">
                                    <input type="hidden" name="payment_terms" id="paymentTermsFinal">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">Notes</div>
                                <div class="col-md-9 mb-4">
                                    <textarea name="notes" id="" cols="30" rows="2" class="form-control form-control-sm"></textarea>
                                </div>
                            </div>

                            <a href="<?php echo e(route('quotations.index')); ?>" class="btn btn-danger btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm">Create</button> 
                        </form>
                    </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/extensions/flatpickr/flatpickr.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/extensions/choices.js/public/assets/scripts/choices.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/static/js/pages/form-element-select.js')); ?>"></script>
    <script>
        flatpickr('.flatpickr', {
            enableTime: false,
            dateFormat: "Y-m-d",
        });

        const selectElement = document.getElementById('paymentTerms');
        const customInput = document.getElementById('paymentTermsCustom');
        const finalInput = document.getElementById('paymentTermsFinal');

        function updatePaymentTerms() {
            if (selectElement.value === 'other') {
                customInput.style.display = 'block';
                finalInput.value = customInput.value;
            } else {
                customInput.style.display = 'none';
                finalInput.value = selectElement.value;
            }
        }

        selectElement.addEventListener('change', updatePaymentTerms);
        customInput.addEventListener('input', function() {
            if (selectElement.value === 'other') {
                finalInput.value = this.value;
            }
        });

        document.getElementById('quotationForm').addEventListener('submit', function() {
            updatePaymentTerms();
        });

        const attachmentContainer = document.getElementById('attachment-container');

        if (document.getElementById('customerId')) {
            document.getElementById('customerId').addEventListener('change', function() {
                const customerId = this.value;
                const requestSelect = document.getElementById('selectRequest');

                requestSelect.innerHTML = '<option value="">Loading...</option>';

                if (!customerId) {
                    requestSelect.innerHTML = '<option value="">Pilih Customer dulu</option>';
                    return;
                }

                fetch(`/requests-project/customer/${customerId}`)
                    .then(response => response.json())
                    .then(data => {
                        requestSelect.innerHTML = '<option value="">Pilih Request</option>';
                        var req = data.data;
                        req.forEach(request => {
                            const date = new Date(request.created_at);
                            const formatedDate = date.toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: "short",
                                year: 'numeric'
                            });
                            const option = document.createElement('option');
                            option.value = request.id;
                            option.textContent = request.subject + " - " + formatedDate; 
                            requestSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error(error);
                        requestSelect.innerHTML = '<option value="">Gagal load data</option>';
                    });
            });
        }

        if (document.getElementById('selectRequest')) {
            document.getElementById('selectRequest').addEventListener('change', function() {
                var id = this.value;
                if (attachmentContainer) attachmentContainer.innerHTML = '';

                fetch(`/requests-project/detail/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (document.getElementById('requestMessage')) {
                            document.getElementById('requestMessage').innerText = data.data.message;
                        }

                        const attch = data.data.attachments;
                        attch.forEach(element => {
                            var linkHtml = `
                    <a href="/storage/${element.file_path}" class="btn btn-sm btn-info" target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i> ${element.document_name}
                    </a> `;
                            if (attachmentContainer) attachmentContainer.innerHTML += linkHtml;
                        });
                    })
                    .catch(error => console.error('Error:', error)); 
            });
        }
    </script>

    <script>
    document.getElementById('item-container').addEventListener('click', function (e) {
        if (e.target.closest('.btn-add-item')) {
            const container = document.getElementById('item-container');
            const newRow = document.createElement('div');
            newRow.className = 'row item-row mb-2';
            newRow.innerHTML = `
                <div class="col-md-2">Item</div>
                <div class="col-md-3">
                    <input type="text" name="item[]" class="form-control form-control-sm" placeholder="Item name">
                </div>
                <div class="col-md-2">
                    <input type="number" name="qty[]" class="form-control form-control-sm" placeholder="Qty">
                </div>
                <div class="col-md-2">
                    <input type="number" name="price[]" step="0.01" min="0" class="form-control form-control-sm" placeholder="Price">
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="button" class="btn btn-sm btn-success btn-add-item">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger btn-remove">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
            container.appendChild(newRow);
            updateRemoveButtons();
        }

        if (e.target.closest('.btn-remove')) {
            e.target.closest('.item-row').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.item-row');
        rows.forEach((row) => {
            const btn = row.querySelector('.btn-remove');
            btn.style.display = rows.length > 1 ? 'inline-block' : 'none';
        });
    }
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/quotations/create.blade.php ENDPATH**/ ?>