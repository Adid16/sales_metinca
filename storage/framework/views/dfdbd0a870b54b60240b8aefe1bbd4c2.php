<?php $__env->startSection('title', 'PT. Metinca Prima Industrial Works'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/extensions/flatpickr/flatpickr.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app-dark.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/quotation.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/extensions/choices.js/public/assets/styles/choices.css')); ?>">
    <style>
        .table-items thead th {
            background-color: #f1f4f8;
            font-size: 0.82rem;
            color: #495057;
            font-weight: 600;
        }
        .table-items td {
            vertical-align: middle;
        }
        .pricelist-badge-container:empty {
            display: none;
        }
    </style>
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

                            
                            <div class="card shadow-sm mb-3 border">
                                <div class="card-header py-2" style="background:#e9ecef;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-bold text-uppercase" style="font-size:0.78rem; letter-spacing:1px; color:#495057;">
                                            <i class="bi bi-cart-plus me-1 text-primary"></i>Item Quotation
                                        </h6>
                                        <span class="badge bg-white text-secondary border">
                                            <i class="bi bi-info-circle me-1 text-primary"></i>Pricelist terhubung (Bisa cari / ketik manual)
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm align-middle mb-2 table-items">
                                            <thead class="text-center">
                                                <tr>
                                                    <th style="width: 4%;">#</th>
                                                    <th style="width: 32%;">Cari dari Pricelist <small class="text-muted fw-normal">(Article / Produk)</small></th>
                                                    <th style="width: 28%;">Nama Item / Deskripsi <span class="text-danger">*</span></th>
                                                    <th style="width: 10%;">Qty <span class="text-danger">*</span></th>
                                                    <th style="width: 12%;">Harga Satuan (Rp) <span class="text-danger">*</span></th>
                                                    <th style="width: 10%;">Subtotal (Rp)</th>
                                                    <th style="width: 4%;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="item-container">
                                                <tr class="item-row">
                                                    <td class="text-center fw-bold row-num text-muted">1</td>
                                                    <td>
                                                        <input type="hidden" name="article_id[]" class="article-id-input">
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                                                            <input type="text" 
                                                                   class="form-control form-control-sm search-pricelist" 
                                                                   list="pricelist-options" 
                                                                   placeholder="Cari Article / Part Name..." 
                                                                   autocomplete="off">
                                                        </div>
                                                        <div class="pricelist-badge-container mt-1"></div>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="item[]" class="form-control form-control-sm item-name-input" placeholder="Nama item" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="qty[]" min="1" class="form-control form-control-sm text-center qty-input" placeholder="Qty" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="price[]" step="0.01" min="0" class="form-control form-control-sm text-end price-input" placeholder="Harga" required>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm text-end subtotal-cell bg-light fw-semibold" value="Rp 0" readonly>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove" style="display:none;" title="Hapus baris">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-light">
                                                    <td colspan="5" class="text-end fw-bold">Grand Total:</td>
                                                    <td class="text-end fw-bold text-primary" id="grand-total-text">Rp 0</td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <button type="button" class="btn btn-sm btn-success btn-add-item">
                                            <i class="bi bi-plus-lg me-1"></i> Tambah Item
                                        </button>
                                        <small class="text-muted fst-italic">
                                            * Jika barang ada di pricelist, cari lewat kolom <strong>Cari dari Pricelist</strong> untuk auto-fill nama & harga. Jika barang baru/custom, langsung ketik pada kolom <strong>Nama Item</strong> & <strong>Harga Satuan</strong>.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            
                            <datalist id="pricelist-options">
                                <?php if(isset($articles)): ?>
                                    <?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($art->article_no); ?> - <?php echo e($art->part_name); ?>">
                                            [Pricelist] <?php echo e($art->article_no); ?> | <?php echo e($art->part_name); ?> (Rp <?php echo e(number_format($art->price, 0, ',', '.')); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </datalist>

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
        const articlesData = <?php echo json_encode($articles ?? [], 15, 512) ?>;

        function findArticleMatch(val) {
            if (!val || typeof val !== 'string') return null;
            const cleanVal = val.trim().toLowerCase();
            if (cleanVal === '') return null;

            // 1. Check exact datalist pattern: "${article_no} - ${part_name}"
            let found = articlesData.find(a => `${a.article_no} - ${a.part_name}`.toLowerCase() === cleanVal);
            if (found) return found;

            // 2. Check exact article_no
            found = articlesData.find(a => a.article_no && a.article_no.toLowerCase() === cleanVal);
            if (found) return found;

            // 3. Check exact internal_part_no
            found = articlesData.find(a => a.internal_part_no && a.internal_part_no.toLowerCase() === cleanVal);
            if (found) return found;

            // 4. Check exact part_name
            found = articlesData.find(a => a.part_name && a.part_name.toLowerCase() === cleanVal);
            if (found) return found;

            // 5. Check if search term contains article_no
            found = articlesData.find(a => a.article_no && cleanVal.includes(a.article_no.toLowerCase()));
            if (found) return found;

            // 6. Check partial part_name match (only if query >= 3 chars)
            if (cleanVal.length >= 3) {
                found = articlesData.find(a => a.part_name && a.part_name.toLowerCase().includes(cleanVal));
                if (found) return found;
            }

            return null;
        }

        function applyArticleToRow(row, article) {
            const itemNameInput = row.querySelector('.item-name-input');
            const priceInput = row.querySelector('.price-input');
            const qtyInput = row.querySelector('.qty-input');
            const articleIdInput = row.querySelector('.article-id-input');
            const badgeContainer = row.querySelector('.pricelist-badge-container');

            if (article) {
                const basePrice = Math.round(article.price_list || article.price || 0);
                itemNameInput.value = article.part_name || '';
                priceInput.value = basePrice;
                priceInput.readOnly = true;
                priceInput.classList.add('bg-light', 'fw-semibold');
                if (articleIdInput) articleIdInput.value = article.id;
                if (!qtyInput.value || parseFloat(qtyInput.value) <= 0) {
                    qtyInput.value = 1;
                }

                if (badgeContainer) {
                    const priceFormatted = Number(basePrice).toLocaleString('id-ID');
                    badgeContainer.innerHTML = `
                        <span class="badge bg-light-success text-success border border-success py-1 px-2" style="font-size: 0.75rem;" title="Harga resmi master Price List (Terkunci otomatis)">
                            <i class="bi bi-lock-fill me-1"></i><strong>${article.article_no || ''}</strong> &bull; Price List: Rp ${priceFormatted}
                        </span>
                    `;
                }
            } else {
                priceInput.readOnly = false;
                priceInput.classList.remove('bg-light', 'fw-semibold');
                if (articleIdInput) articleIdInput.value = '';
                if (badgeContainer) {
                    badgeContainer.innerHTML = '';
                }
            }
            updateRowSubtotal(row);
            updateGrandTotal();
        }

        function updateRowSubtotal(row) {
            const qty = parseFloat(row.querySelector('.qty-input')?.value) || 0;
            const price = parseFloat(row.querySelector('.price-input')?.value) || 0;
            const subtotal = qty * price;
            const subtotalCell = row.querySelector('.subtotal-cell');
            if (subtotalCell) {
                subtotalCell.value = 'Rp ' + subtotal.toLocaleString('id-ID');
            }
        }

        function updateGrandTotal() {
            let grandTotal = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input')?.value) || 0;
                const price = parseFloat(row.querySelector('.price-input')?.value) || 0;
                grandTotal += (qty * price);
            });
            const grandTotalEl = document.getElementById('grand-total-text');
            if (grandTotalEl) {
                grandTotalEl.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
            }
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll('.item-row');
            rows.forEach((row, index) => {
                const numCell = row.querySelector('.row-num');
                if (numCell) numCell.textContent = index + 1;
                const removeBtn = row.querySelector('.btn-remove');
                if (removeBtn) {
                    removeBtn.style.display = rows.length > 1 ? 'inline-block' : 'none';
                }
            });
        }

        document.getElementById('item-container').addEventListener('input', function(e) {
            const row = e.target.closest('.item-row');
            if (!row) return;

            if (e.target.classList.contains('search-pricelist')) {
                const val = e.target.value;
                const match = findArticleMatch(val);
                if (match) {
                    applyArticleToRow(row, match);
                } else if (val.trim() === '') {
                    const badgeContainer = row.querySelector('.pricelist-badge-container');
                    if (badgeContainer) badgeContainer.innerHTML = '';
                }
            }

            if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
                updateRowSubtotal(row);
                updateGrandTotal();
            }
        });

        document.getElementById('item-container').addEventListener('change', function(e) {
            const row = e.target.closest('.item-row');
            if (!row) return;

            if (e.target.classList.contains('search-pricelist')) {
                const val = e.target.value.trim();
                if (val) {
                    const match = findArticleMatch(val);
                    if (match) {
                        applyArticleToRow(row, match);
                    }
                }
            }

            if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
                updateRowSubtotal(row);
                updateGrandTotal();
            }
        });

        document.getElementById('item-container').addEventListener('keydown', function(e) {
            if (e.target.classList.contains('search-pricelist') && e.key === 'Enter') {
                e.preventDefault();
                const val = e.target.value.trim();
                if (!val) return;

                const row = e.target.closest('.item-row');
                const match = findArticleMatch(val);
                if (match) {
                    applyArticleToRow(row, match);
                    return;
                }

                // Fallback pencarian remote ke server
                fetch(`/articles/requirements/${encodeURIComponent(val)}`)
                    .then(res => res.json())
                    .then(res => {
                        if (res.success && res.data) {
                            applyArticleToRow(row, res.data);
                        } else {
                            const badgeContainer = row.querySelector('.pricelist-badge-container');
                            if (badgeContainer) {
                                badgeContainer.innerHTML = `<span class="badge bg-light-secondary text-muted border" style="font-size:0.75rem;"><i class="bi bi-info-circle me-1"></i>Belum di pricelist (Ketik manual)</span>`;
                            }
                        }
                    })
                    .catch(() => {
                        const badgeContainer = row.querySelector('.pricelist-badge-container');
                        if (badgeContainer) {
                            badgeContainer.innerHTML = `<span class="badge bg-light-secondary text-muted border" style="font-size:0.75rem;"><i class="bi bi-info-circle me-1"></i>Belum di pricelist (Ketik manual)</span>`;
                        }
                    });
            }
        });

        document.querySelector('.btn-add-item').addEventListener('click', function() {
            const container = document.getElementById('item-container');
            const newTr = document.createElement('tr');
            newTr.className = 'item-row';
            newTr.innerHTML = `
                <td class="text-center fw-bold row-num text-muted"></td>
                <td>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" 
                               class="form-control form-control-sm search-pricelist" 
                               list="pricelist-options" 
                               placeholder="Cari Article / Part Name..." 
                               autocomplete="off">
                    </div>
                    <div class="pricelist-badge-container mt-1"></div>
                </td>
                <td>
                    <input type="text" name="item[]" class="form-control form-control-sm item-name-input" placeholder="Nama item" required>
                </td>
                <td>
                    <input type="number" name="qty[]" min="1" class="form-control form-control-sm text-center qty-input" placeholder="Qty" required>
                </td>
                <td>
                    <input type="number" name="price[]" step="0.01" min="0" class="form-control form-control-sm text-end price-input" placeholder="Harga" required>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm text-end subtotal-cell bg-light fw-semibold" value="Rp 0" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove" title="Hapus baris">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            container.appendChild(newTr);
            updateRowNumbers();
            updateGrandTotal();
        });

        document.getElementById('item-container').addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove')) {
                const rows = document.querySelectorAll('.item-row');
                if (rows.length > 1) {
                    e.target.closest('.item-row').remove();
                    updateRowNumbers();
                    updateGrandTotal();
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            updateRowNumbers();
            updateGrandTotal();
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/quotations/create.blade.php ENDPATH**/ ?>