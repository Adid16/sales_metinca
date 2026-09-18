<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/contact_page.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/extensions/filepond/filepond.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">
                <i class="bi bi-person-fill me-3"></i>CONTAC US
            </h1>
            <p class="hero-description">
                Get in touch with us to request a quotation for your project.
            </p>
        </div>
    </section>

    
    <section class="content-section">
        <div class="container">
            <div class="accordion-collapse-show">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <div class="accordion-button">
                            <i class="bi bi-person me-3"></i> Contact Us
                        </div>
                    </h2>

                    <div class="accordion-collapse show">
                        <div class="accordion-body">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    
                                    <p class="fw-bold mb-0">Ir. Awad Umar</p>
                                    <p class="fw-normal mb-0">P : +62 21 4600828/4616030</p>
                                    <p class="fw-normal mb-0">F : +62 21 4600832</p>
                                    <p class="fw-normal">E : info@metinca-prima.co.id</p>

                                    <p class="fw-bold mb-0">Abdul Latief Hasan, B.Eng,M.Eng</p>
                                    <p class="fw-normal mb-0">P : +62 21 4600828/4616030</p>
                                    <p class="fw-normal mb-0">F : +62 21 4600832</p>
                                    <p class="fw-normal">E : info@metinca-prima.co.id</p>

                                    <p class="fw-bold mb-0">Ir. M.H.N. Rasyid</p>
                                    <p class="fw-normal mb-0">P : +62 21 4600828/4616030</p>
                                    <p class="fw-normal mb-0">F : +62 21 4600832</p>
                                    <p class="fw-normal">E : info@metinca-prima.co.id</p>

                                    <p class="fw-bold mb-0">Syaugy Awad Umar</p>
                                    <p class="fw-normal mb-0">P : +62 21 88368880</p>
                                    <p class="fw-normal mb-0">F : +62 21 88368881</p>
                                    <p class="fw-normal">E : info@metinca-prima.co.id</p>

                                    <p class="fw-bold mb-0">Ir.Nur Lukman Yudianto</p>
                                    <p class="fw-normal mb-0">P : +62 21 4600828/4616030</p>
                                    <p class="fw-normal mb-0">F : +62 21 4600832</p>
                                    <p class="fw-normal">E : info@metinca-prima.co.id</p>

                                    <p class="fw-bold mb-0">Ir.R.Deden A. Solahuddin</p>
                                    <p class="fw-normal mb-0">P : +62 21 4600828/4616030</p>
                                    <p class="fw-normal mb-0">F : +62 21 4600832</p>
                                    <p class="fw-normal">E : info@metinca-prima.co.id</p>

                                    <p class="fw-bold mb-0">Singgih Prihendy</p>
                                    <p class="fw-normal mb-0">P : +62 21 4600828/4616030</p>
                                    <p class="fw-normal mb-0">F : +62 21 4600832</p>
                                    <p class="fw-normal">E : info@metinca-prima.co.id</p>
                                    
                                </div>
                                <div class="col-md-8">
                                    <div class="feature-card">
                                        <h4 class="fw-bold text-center mb-5">Request Quotation Or Your Other Project</h4>
                                        <div class="col-md-12 mb-4">
                                            <form method="POST" action="<?php echo e(route('requests-project.store')); ?>" enctype="multipart/form-data">
                                                <?php echo csrf_field(); ?>
                                                <div class="row">
                                                    <!-- KIRI -->
                                                    <div class="col-md-6">
                                                        <label class="fw-bold mb-1">Name</label>
                                                        <input type="text" name="name" class="form-control mb-3"
                                                            placeholder="Your Name" required>

                                                        

                                                        <label class="fw-bold mb-1">Company</label>
                                                        <input type="text" name="company" class="form-control mb-3"
                                                            placeholder="Your Company" required>

                                                        <label class="fw-bold mb-1">Email</label>
                                                        <input type="email" name="email" class="form-control mb-3"
                                                            placeholder="Your Email" required>
                                                        <div class="mb-3">
                                                            <label for="attachment"
                                                                class="form-label fw-bold required-field">Attachment</label>
                                                            <input type="file" required multiple data-max-files="3"
                                                                class="form-control filepond multiple-files-filepond with-validation-filepond <?php $__errorArgs = ['attachment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                id="attachment" name="attachment[]" accept=".pdf">
                                                            <?php $__errorArgs = ['attachment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                            <small class="form-help-text">Upload relevant files (Max:
                                                                5MB)</small>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-lg-6">

                                                        <label class="fw-bold mb-1">Phone</label>
                                                        <input type="text" name="phone" class="form-control mb-3"
                                                            placeholder="Your Phone Number" required>

                                                        <label class="fw-bold mb-1">Subject</label>
                                                        <select name="subject" class="form-select mb-3" required>
                                                            <option value="">Choose Subject</option>
                                                            <option value="quotation">Quotation</option>
                                                            <option value="meet and greet">Meet And Greet</option>
                                                            <option value="other project">Other Project</option>
                                                        </select>

                                                        <label class="fw-bold mb-1">Request Recipient</label>
                                                        <select name="sales_id" class="form-select mb-4" required>
                                                            <option value="">Choose Recipient</option>
                                                            <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            
                                                            
                                                        </select>
                                                        <label class="fw-bold mb-1">Message</label>
                                                        <textarea name="message" class="form-control mb-4" rows="8" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <button type="submit" name="submit" class="btn btn-primary">Submit
                                                        Request</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="row">
                                            <?php if(session('success')): ?>
                                                <div class="alert alert-success alert-dismissible fade show"
                                                    role="alert">
                                                    <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                </div>
                                            <?php endif; ?>

                                            <?php if(session('error')): ?>
                                                <div class="alert alert-danger alert-dismissible fade show"
                                                    role="alert">
                                                    <i class="bi bi-exclamation-triangle"></i> <?php echo e(session('error')); ?>

                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-4">
                                        <div class="row">
                                            <div class="col-md-4 googlemaps">
                                                <label class="fw-bold mb-1">Jakarta</label>
                                                <iframe
                                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.450062138981!2d106.91577910000001!3d-6.2042093000000005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e698b54c52199c5%3A0x81c3caedcc3d7e1!2sPT.%20METINCA%20PRIMA%20INDUSTRIAL%20WORKS!5e0!3m2!1sid!2sid!4v1768932183145!5m2!1sid!2sid"
                                                    width="100%" height="220" style="border:0;" allowfullscreen=""
                                                    loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                                </iframe>
                                            </div>
                                            <div class="col-md-4 googlemaps">
                                                <label class="fw-bold mb-1">Bekasi</label>
                                                <iframe
                                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.010275062908!2d107.05025687429948!3d-6.262375961308321!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e698fbe6b108265%3A0x9c1e75568d9a4d6b!2sPT.%20Metinca%20Prima%20Industrial%20Work!5e0!3m2!1sen!2sus!4v1768932465733!5m2!1sen!2sus"
                                                    width="100%" height="220" style="border:0;" allowfullscreen=""
                                                    loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                                </iframe>
                                            </div>
                                            <div class="col-md-4 googlemaps">
                                                <label class="fw-bold mb-1">Salatiga</label>
                                                <iframe
                                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.8183404223605!2d110.50560817431585!3d-7.374247372576046!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a79f7aaaaaaab%3A0xa50a38509947f5d3!2sPT.%20Prima%20Metinca%20I.W.%20Salatiga!5e0!3m2!1sen!2sus!4v1768932570859!5m2!1sen!2sus"
                                                    width="100%" height="220" style="border:0;" allowfullscreen=""
                                                    loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                                </iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/extensions/filepond/filepond.js')); ?>"></script>
    <script
        src="<?php echo e(asset('assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js')); ?>">
    </script>
    <script
        src="<?php echo e(asset('assets/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js')); ?>">
    </script>
    <script>
        // Filepond: Multiple Files
        FilePond.create(document.querySelector(".multiple-files-filepond"), {
            credits: null,
            allowImagePreview: false,
            allowMultiple: true,
            allowFileEncode: false,
            required: false,
            storeAsFile: true,
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.home2', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sales_metinca\resources\views/customer_home/contacts.blade.php ENDPATH**/ ?>