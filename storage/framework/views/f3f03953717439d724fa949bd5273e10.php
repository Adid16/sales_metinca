<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?> - Metinca</title>



    
    

    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app-dark.css')); ?>">
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>
    <script src="<?php echo e(asset('assets/static/js/initTheme.js')); ?>"></script>
    <div id="app">
        <div id = "main" class="layout-horizontal">
            <header>
                <div class = "header-top py-2">
                    <div class="container">
                        

                        <div class="header">
                            <h3 style="margin-left: 260px;"> <?php echo $__env->yieldContent('title'); ?> </h3>
                        </div>

                        <div class="header-top-right">
                            <div class="dropdown d-flex align-items-center">

                                <li class="nav-item dropdown me-3">
                                    <a class="nav-link active dropdown-toggle text-gray-600" href="#"
                                        data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                        <i class="bi bi-bell bi-sub fs-4"></i>
                                        <span
                                            class="badge badge-notification bg-danger"><?php echo e(auth()->user()->unreadNotifications->count()); ?></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown"
                                        aria-labelledby="dropdownMenuButton">
                                        <li class="dropdown-header d-flex justify-content-between align-items-center">
                                            <h6>Notifications</h6>
                                            <?php if(auth()->user()->unreadNotifications->count() > 0): ?>
                                                <form action="<?php echo e(route('notifikasi.markAllRead')); ?>" method="POST" class="mb-0">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm btn-link">Mark all read</button>
                                                </form>
                                            <?php endif; ?>
                                        </li>
                                        <?php $__currentLoopData = auth()->user()->notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            
                                            <li class="dropdown-item notification-item <?php echo e(is_null($notification->read_at) ? 'bg-light' : ''); ?>">
                                                <a class="d-flex align-items-center"
                                                    href="<?php echo e(route('notifikasi.read', $notification->id)); ?>">
                                                    <div class="notification-icon bg-primary">
                                                        <i class="bi bi-cart-check"></i>
                                                    </div>
                                                    <div class="notification-text ms-4">
                                                        <p class="notification-title font-bold">
                                                            <?php echo e($notification->data['message']); ?>

                                                        </p>
                                                        
                                                    </div>
                                                </a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <p class="text-center py-2 mb-0">
                                                <a href="<?php echo e(route('notifikasi')); ?>">See all notifications</a>
                                            </p>
                                        </li>
                                    </ul>
                                </li>
                                </ul>

                                <a href="#" id="topbarUserDropdown"
                                    class="user-dropdown d-flex align-items-center dropend dropdown-toggle "
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="avatar avatar-md2">
                                        <img src="<?php echo e(asset('assets/compiled/jpg/1.jpg')); ?>" alt="Avatar">
                                    </div>
                                    <div class="text">
                                        <h6 class="b-0 text-gray-600"><?php echo e(auth()->user()->name); ?></h6>
                                        <p class="mb-0 text-sm text-gray-600"><?php echo e(ucfirst(auth()->user()->role)); ?>

                                            <?php echo e(ucfirst(auth()->user()->divisi) ?? ''); ?> </p>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg"
                                    aria-labelledby="topbarUserDropdown">
                                    <?php if(auth()->user()->isCustomer()): ?>
                                        <li><a class="dropdown-item" href="<?php echo e(route('account.show')); ?>">My Account</a></li>
                                    <?php endif; ?>
                                    
                                    
                                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-left me-2"></i>
                                            Logout
                                        </button>
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div id="sidebar">
                <div class="sidebar-wrapper active">
                    <div class="sidebar-header position-relative">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="logo">
                                <a href="dashboard" class="fs-6"><img
                                        src="<?php echo e(asset('assets/compiled/jpg/logometinca.jpg')); ?>" alt="Logo"
                                        srcset="" style="width: 25px; height: auto;">Product Sales</a>
                            </div>
                            <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    aria-hidden="true" role="img" class="iconify iconify--system-uicons"
                                    width="20" height="20" preserveAspectRatio="xMidYMid meet"
                                    viewBox="0 0 21 21">
                                    <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                            opacity=".3"></path>
                                        <g transform="translate(-210 -1)">
                                            <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                            <circle cx="220.5" cy="11.5" r="4"></circle>
                                            <path
                                                d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2">
                                            </path>
                                        </g>
                                    </g>
                                </svg>
                                <div class="form-check form-switch fs-6">
                                    <input class="form-check-input  me-0" type="checkbox" id="toggle-dark"
                                        style="cursor: pointer">
                                    <label class="form-check-label"></label>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    aria-hidden="true" role="img" class="iconify iconify--mdi" width="20"
                                    height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                                    </path>
                                </svg>
                            </div>
                            <div class="sidebar-toggler  x">
                                <a href="#" class="sidebar-hide d-xl-none d-block"><i
                                        class="bi bi-x bi-middle"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-menu">
                        <ul class="menu">
                            <li class="sidebar-title">Menu</li>

                            <li class="sidebar-item <?php echo e(Route::is('dashboard') ? 'active' : ''); ?>">
                                <a href="/" class='sidebar-link'>
                                    <i class="bi bi-grid-fill"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            

                            <?php if(auth()->user()->isManager()): ?>
                                <li class="sidebar-item has-sub">
                                    <a href="#" class='sidebar-link'>
                                        <i class="bi bi-person-workspace"></i>
                                        <span>User Management</span>
                                    </a>
                                    <ul class="submenu ">
                                        <li class="submenu-item">
                                            <a href="<?php echo e(route('users.customer')); ?>" class='submenu-link'>Data
                                                Customer</a>
                                        </li>

                                        <li class="submenu-item">
                                            <a href="<?php echo e(route('users.index')); ?>" class='submenu-link'>Data
                                                Employee</a>
                                        </li>
                                    </ul>
                                </li>
                            <?php endif; ?>

                            <?php if(!auth()->user()->isCustomer()): ?>
                            <li class="sidebar-item <?php echo e(Route::is('articles*') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('articles.index')); ?>" class='sidebar-link'>
                                    <i class="bi bi-box-seam-fill"></i>
                                    <span>Pricelist Products</span>
                                </a>
                            </li>
                            <?php endif; ?>

                            

<?php if(!(auth()->user()->isManager() && auth()->user()->divisi !== 'sales')): ?>                            <li class="sidebar-item <?php echo e(Route::is('requests-project*') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('requests-project.index')); ?>" class='sidebar-link'>
                                    <i class="bi bi-send-plus-fill"></i>
                                    <span>Request</span>
                                </a>
                            </li>
                            <?php endif; ?>

                            
                            <li class="sidebar-item <?php echo e(Route::is('quotations*') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('quotations.index')); ?>" class='sidebar-link'>
                                    <i class="bi bi-file-earmark-text-fill"></i>
                                    <span>Quotation</span>
                                </a>
                            </li>

                            
                            <?php if(auth()->user()->isAdmin() || (auth()->user()->isManager() && auth()->user()->divisi === 'sales')): ?>
                            <li class="sidebar-item <?php echo e(Route::is('negotiations*') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('negotiations.index')); ?>" class='sidebar-link d-flex justify-content-between align-items-center'>
                                    <div>
                                        <i class="bi bi-chat-square-quote-fill"></i>
                                        <span>Negosiasi</span>
                                    </div>
                                    <?php
                                        $sidebarPendingNego = \App\Models\Negotiate::where('requires_manager_approval', true)
                                            ->where('manager_approval_status', 'pending')
                                            ->count();
                                    ?>
                                    <?php if($sidebarPendingNego > 0): ?>
                                        <span class="badge bg-danger rounded-pill" style="font-size: 0.65rem; padding: 2px 6px;" title="<?php echo e($sidebarPendingNego); ?> butuh approval harga">
                                            <?php echo e($sidebarPendingNego); ?>

                                        </span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if(auth()->user()->isCustomer()): ?>
                            <li class="sidebar-item <?php echo e(Route::is('purchase-orders*') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('purchase-orders.index')); ?>" class='sidebar-link'>
                                    <i class="bi bi-file-earmark-richtext-fill"></i>
                                    <span>Purchase Order</span>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if(!auth()->user()->isCustomer()): ?>
                            <li class="sidebar-item has-sub">
                                <a href="#" class='sidebar-link'>
                                    <i class="bi bi-file-earmark-richtext-fill"></i>
                                        <span>Purchase Order</span>
                                </a>
                                    <ul class="submenu ">
                                        <li class="submenu-item <?php echo e(Route::is('purchase-orders*') ? 'active' : ''); ?>">
                                            <a href="<?php echo e(route('purchase-orders.index')); ?>" class='submenu-link'>Data
                                                PO External</a>
                                        </li>

                                        <li class="submenu-item">
                                            <a href="<?php echo e(route('purchase-orders-internal.index')); ?>" class='submenu-link'>Data
                                                PO Internal</a>
                                        </li>

                                         
                                        <li class="submenu-item <?php echo e(Route::is('purchase-orders.approval-amandement') ? 'active' : ''); ?>">
                                            <a href="<?php echo e(route('purchase-orders.approval-amandement')); ?>" class='submenu-link d-flex justify-content-between align-items-center'>
                                                <span>PO Amandemen</span>
                                                <?php
                                                    $pendingAmandement = \App\Models\Contract::where('status', 'amandement_pending')->count()
                                                        + \App\Models\PurchaseOrder::where('status', 'amandement_pending')->whereDoesntHave('contracts')->count();
                                                ?>
                                                <?php if($pendingAmandement > 0): ?>
                                                    <span class="badge bg-danger" style="font-size: 0.65rem; padding: 2px 6px;"><?php echo e($pendingAmandement); ?></span>
                                                <?php endif; ?>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            <?php endif; ?>


                            <?php if(!auth()->user()->isCustomer()): ?>
                            <li class="sidebar-item <?php echo e(Route::is('po-schedule.index') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('po-schedule.index')); ?>" class='sidebar-link'>
                                    <i class="bi bi-calendar3"></i>
                                    <span>PO Schedule</span>
                                </a>
                            </li>
                            <?php endif; ?>


                            
                            
                            


                            

                            <?php if(!auth()->user()->isCustomer()): ?>
                                <li class="sidebar-item <?php echo e(Route::is('contracts*') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('contracts.index')); ?>" class='sidebar-link'>
                                        <i class="bi bi-collection-fill"></i>
                                        <span>Contract Review Sheet</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            

                            

                            
                            <?php if(auth()->user()->isAdmin()): ?>
                                

                                
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="main">
                <header>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-lg-0">
                </header>

                <div class="main-content">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>


                <footer>
                    <div class="footer clearfix mb-0 text-muted">
                        <div class="float-start">
                            <p>2025 &copy; Sistem Informasi Universitas Darma Persada</p>
                        </div>
                        <div class="float-end">
                            <p>Crafted with
                                by <a href="si.unsada.ac.id"></a></p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="<?php echo e(asset('assets/static/js/components/dark.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js')); ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="<?php echo e(asset('assets/compiled/js/app.js')); ?>?v=<?php echo e(@filemtime(public_path('assets/compiled/js/app.js')) ?: '2.0.1'); ?>"></script>
        <!-- App JS -->
        <script src="<?php echo e(asset('js/app.js')); ?>?v=<?php echo e(@filemtime(public_path('js/app.js')) ?: '2.0.1'); ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            // document.getElementById('formLogout').addEventListener('submit', function(e){
            //     e.preventDefault();
            //     Swal.fire({
            //         title: 'Yakin ingin logout?',
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonText: 'Ya, Logout',
            //         cancelButtonText: 'Batal'
            //     }).then((result) => {
            //         App.ajax('<?php echo e(route('logout')); ?>', 'POST',new FormData(this)).then(response => {
            //             Swal.fire({
            //                 title: 'Berhasil!',
            //                 text: 'Anda telah logout.',
            //                 icon: 'success',
            //                 timer: 1500,
            //                 showConfirmButton: false
            //             }).then(() => {
            //                 window.location.href = '<?php echo e(route('login')); ?>';
            //             });

            //         }).catch(error => {
            //             console.log(error);
            //             App.error('Gagal Logout' || 'Terjadi kesalahan saat logout.');
            //         });
            //     });
            // });
        </script>
        <?php echo $__env->yieldPushContent('scripts'); ?>
        <!-- Need: Apexcharts -->

</body>

</html>
<?php /**PATH C:\laragon\www\sales_metinca\resources\views/layouts/app.blade.php ENDPATH**/ ?>