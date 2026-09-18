<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?> - Metinca</title>



    
    

    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/compiled/css/app-dark.css')); ?>">
    
    <?php echo $__env->yieldPushContent('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/dark-theme-enhancements.css')); ?>?v=<?php echo e(@filemtime(public_path('assets/css/dark-theme-enhancements.css')) ?: '1.0.0'); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/responsive-enhancements.css')); ?>?v=<?php echo e(@filemtime(public_path('assets/css/responsive-enhancements.css')) ?: '1.0.0'); ?>">
</head>

<body>
    <script src="<?php echo e(asset('assets/static/js/initTheme.js')); ?>"></script>
    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="<?php echo e(route('dashboard')); ?>" class="d-flex align-items-center gap-2 text-decoration-none">
                                <img src="<?php echo e(asset('assets/compiled/jpg/logometinca.jpg')); ?>" alt="Logo" style="width: 32px; height: 32px; object-fit: contain; border-radius: 6px;">
                                <div class="d-flex flex-column text-start" style="white-space: nowrap;">
                                    <span class="fw-bold text-primary brand-title" style="font-size: 1.15rem; line-height: 1.2; white-space: nowrap;">Metinca Prima</span>
                                    <span class="text-muted brand-subtitle" style="font-size: 0.72rem; letter-spacing: 0.5px; white-space: nowrap;">Industrial Works</span>
                                </div>
                            </a>
                        </div>
                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i
                                    class="bi bi-x bi-middle fs-4"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Main</li>

                        <li class="sidebar-item <?php echo e(Route::is('dashboard') ? 'active' : ''); ?>">
                            <a href="/" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <?php if(auth()->user()->isAdmin() || auth()->user()->isManager() || !auth()->user()->isCustomer()): ?>
                            <li class="sidebar-title">Master Data</li>

                            <?php if(auth()->user()->isAdmin()): ?>
                                <li class="sidebar-item has-sub <?php echo e(Route::is('users*') ? 'active' : ''); ?>">
                                    <a href="#" class='sidebar-link'>
                                        <i class="bi bi-person-workspace"></i>
                                        <span>User Management</span>
                                    </a>
                                    <ul class="submenu <?php echo e(Route::is('users*') ? 'submenu-open' : ''); ?>">
                                        <li class="submenu-item <?php echo e(Route::is('users.customer') ? 'active' : ''); ?>">
                                            <a href="<?php echo e(route('users.customer')); ?>" class='submenu-link'>Data Customer</a>
                                        </li>

                                        <li class="submenu-item <?php echo e(Route::is('users.index') ? 'active' : ''); ?>">
                                            <a href="<?php echo e(route('users.index')); ?>" class='submenu-link'>Data Employee</a>
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
                        <?php endif; ?>

                        <li class="sidebar-title">Transaksi</li>

                        <?php if(auth()->user()->isAdmin() || !(auth()->user()->isManager() && auth()->user()->divisi !== 'sales')): ?>
                            <li class="sidebar-item <?php echo e(Route::is('requests-project*') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('requests-project.index')); ?>" class='sidebar-link'>
                                    <i class="bi bi-send-plus-fill"></i>
                                    <span>Request Project</span>
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
                        <?php else: ?>
                            <li class="sidebar-item has-sub <?php echo e(Route::is('purchase-orders*') || Route::is('purchase-orders-internal*') ? 'active' : ''); ?>">
                                <a href="#" class='sidebar-link'>
                                    <i class="bi bi-file-earmark-richtext-fill"></i>
                                    <span>Purchase Order</span>
                                </a>
                                <ul class="submenu <?php echo e(Route::is('purchase-orders*') || Route::is('purchase-orders-internal*') ? 'submenu-open' : ''); ?>">
                                    <li class="submenu-item <?php echo e(Route::is('purchase-orders.index') ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('purchase-orders.index')); ?>" class='submenu-link'>Data PO External</a>
                                    </li>

                                    <li class="submenu-item <?php echo e(Route::is('purchase-orders-internal.index') ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('purchase-orders-internal.index')); ?>" class='submenu-link'>Data PO Internal</a>
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

                            <li class="sidebar-item <?php echo e(Route::is('contracts*') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('contracts.index')); ?>" class='sidebar-link'>
                                    <i class="bi bi-collection-fill"></i>
                                    <span>Contract Review Sheet</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="sidebar-title">Account</li>

                        <?php if(auth()->user()->isCustomer()): ?>
                            <li class="sidebar-item <?php echo e(Route::is('account*') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('account.show')); ?>" class='sidebar-link'>
                                    <i class="bi bi-person-circle"></i>
                                    <span>My Account</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="sidebar-item">
                            <form action="<?php echo e(route('logout')); ?>" method="POST" id="sidebarLogoutForm" class="d-none">
                                <?php echo csrf_field(); ?>
                            </form>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('sidebarLogoutForm').submit();" class='sidebar-link text-danger'>
                                <i class="bi bi-box-arrow-left text-danger"></i>
                                <span class="text-danger fw-semibold">Keluar</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="main" class="layout-navbar navbar-fixed">
            <header>
                <nav class="navbar navbar-expand navbar-light navbar-top px-3 py-2">
                    <div class="container-fluid px-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <a href="#" class="burger-btn text-decoration-none" id="toggleSidebarBtn" title="Toggle Sidebar">
                                <i class="bi bi-list"></i>
                            </a>
                        </div>

                        <div class="header-top-right flex-shrink-0 ms-auto">
                            <div class="d-flex align-items-center gap-2 gap-md-3">
                                
                                <div class="theme-toggle-navbar d-flex align-items-center gap-2" title="Ganti Mode Gelap / Terang">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        aria-hidden="true" role="img" class="iconify iconify--system-uicons text-warning"
                                        width="18" height="18" preserveAspectRatio="xMidYMid meet"
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
                                    <div class="form-check form-switch fs-6 mb-0 d-flex align-items-center">
                                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark"
                                            style="cursor: pointer" title="Ganti Mode Gelap / Terang">
                                        <label class="form-check-label" for="toggle-dark"></label>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        aria-hidden="true" role="img" class="iconify iconify--mdi text-primary" width="18"
                                        height="18" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                                        </path>
                                    </svg>
                                </div>

                                    
                                    <?php
                                        $unreadNotifsCount = auth()->user()->unreadNotifications->count();
                                        $dropdownNotifs = auth()->user()->notifications()->take(25)->get();
                                    ?>
                                    <div class="dropdown">
                                        <a class="nav-link dropdown-toggle notification-bell-btn position-relative" href="#"
                                            data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" title="Pemberitahuan">
                                            <i class="bi bi-bell fs-5"></i>
                                            <?php if($unreadNotifsCount > 0): ?>
                                                <span class="notification-badge-count">
                                                    <?php echo e($unreadNotifsCount > 99 ? '99+' : $unreadNotifsCount); ?>

                                                </span>
                                            <?php endif; ?>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end notification-dropdown shadow-lg p-0"
                                            aria-labelledby="dropdownMenuButton">
                                            <div class="notification-dropdown-header">
                                                <h6 class="notification-dropdown-title">
                                                    <i class="bi bi-bell-fill text-primary"></i>
                                                    <span>Notifikasi</span>
                                                    <?php if($unreadNotifsCount > 0): ?>
                                                        <span class="badge bg-primary-subtle text-primary rounded-pill ms-1" style="font-size: 0.68rem; padding: 2px 7px;">
                                                            <?php echo e($unreadNotifsCount); ?> Baru
                                                        </span>
                                                    <?php endif; ?>
                                                </h6>
                                                <?php if($unreadNotifsCount > 0): ?>
                                                    <form action="<?php echo e(route('notifikasi.markAllRead')); ?>" method="POST" class="mb-0">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="notification-mark-all-btn" title="Tandai semua sudah dibaca">
                                                            <i class="bi bi-check2-all"></i>
                                                            <span>Tandai dibaca</span>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>

                                            <div class="notification-list-scroll">
                                                <?php $__empty_1 = true; $__currentLoopData = $dropdownNotifs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <?php
                                                        $msg = $notif->data['message'] ?? 'Pemberitahuan baru';
                                                        $isUnread = is_null($notif->read_at);
                                                        $url = route('notifikasi.read', $notif->id);
                                                        $timeAgo = $notif->created_at ? $notif->created_at->diffForHumans() : '';
                                                        $docNo = $notif->data['order_no'] ?? $notif->data['quotation_no'] ?? $notif->data['po_no'] ?? null;
                                                        
                                                        // Contextual Icon & Color
                                                        $lowerMsg = strtolower($msg);
                                                        $icon = 'bi-bell-fill';
                                                        $iconClass = 'bg-primary-subtle text-primary';
                                                        
                                                        if (str_contains($lowerMsg, 'approve') || str_contains($lowerMsg, 'disetujui') || str_contains($lowerMsg, 'berhasil')) {
                                                            $icon = 'bi-check-circle-fill';
                                                            $iconClass = 'bg-success-subtle text-success';
                                                        } elseif (str_contains($lowerMsg, 'tolak') || str_contains($lowerMsg, 'reject') || str_contains($lowerMsg, 'batal') || str_contains($lowerMsg, 'gagal')) {
                                                            $icon = 'bi-x-circle-fill';
                                                            $iconClass = 'bg-danger-subtle text-danger';
                                                        } elseif (str_contains($lowerMsg, 'request') || str_contains($lowerMsg, 'permintaan') || str_contains($lowerMsg, 'ditugaskan')) {
                                                            $icon = 'bi-inbox-fill';
                                                            $iconClass = 'bg-primary-subtle text-primary';
                                                        } elseif (str_contains($lowerMsg, 'quotation') || str_contains($lowerMsg, 'penawaran')) {
                                                            $icon = 'bi-file-earmark-text-fill';
                                                            $iconClass = 'bg-info-subtle text-info';
                                                        } elseif (str_contains($lowerMsg, 'contract') || str_contains($lowerMsg, 'kontrak')) {
                                                            $icon = 'bi-file-earmark-check-fill';
                                                            $iconClass = 'bg-info-subtle text-info';
                                                        } elseif (str_contains($lowerMsg, 'po') || str_contains($lowerMsg, 'purchase order') || str_contains($lowerMsg, 'amandemen')) {
                                                            $icon = 'bi-bag-check-fill';
                                                            $iconClass = 'bg-warning-subtle text-warning';
                                                        }
                                                    ?>
                                                    <a class="notification-item-card <?php echo e($isUnread ? 'is-unread' : ''); ?>" href="<?php echo e($url); ?>">
                                                        <div class="notification-icon-box <?php echo e($iconClass); ?>">
                                                            <i class="bi <?php echo e($icon); ?>"></i>
                                                        </div>
                                                        <div class="notification-item-body">
                                                            <div class="notification-item-title <?php echo e($isUnread ? 'fw-bold text-dark' : 'text-body-secondary'); ?>">
                                                                <?php echo e($msg); ?>

                                                            </div>
                                                            <div class="notification-item-meta">
                                                                <?php if($docNo): ?>
                                                                    <span class="badge-doc-no"><?php echo e($docNo); ?></span>
                                                                <?php endif; ?>
                                                                <span class="notification-item-time">
                                                                    <i class="bi bi-clock me-1"></i><?php echo e($timeAgo); ?>

                                                                </span>
                                                                <?php if($isUnread): ?>
                                                                    <span class="unread-dot-indicator" title="Belum dibaca"></span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </a>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <div class="text-center py-4 px-3">
                                                        <div class="mb-2">
                                                            <i class="bi bi-bell-slash text-muted" style="font-size: 2.2rem; opacity: 0.5;"></i>
                                                        </div>
                                                        <div class="fw-semibold text-muted small">Belum ada notifikasi</div>
                                                        <div class="text-muted" style="font-size: 0.72rem;">Anda akan melihat riwayat pemberitahuan di sini.</div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="notification-dropdown-footer">
                                                <a href="<?php echo e(route('notifikasi')); ?>" class="btn-see-all-notif">
                                                    <span>Lihat Semua Notifikasi</span>
                                                    <i class="bi bi-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="dropdown">
                                        <a href="#" id="topbarUserDropdown"
                                            class="user-dropdown d-flex align-items-center dropdown-toggle text-decoration-none"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <div class="avatar avatar-md2 me-2">
                                                <img src="<?php echo e(asset('assets/compiled/jpg/1.jpg')); ?>" alt="Avatar">
                                            </div>
                                            <div class="text d-none d-md-block text-start">
                                                <h6 class="mb-0 text-gray-600 fw-semibold"><?php echo e(auth()->user()->name); ?></h6>
                                                <p class="mb-0 text-xs text-muted"><?php echo e(ucfirst(auth()->user()->role)); ?> <?php echo e(ucfirst(auth()->user()->divisi) ?? ''); ?></p>
                                            </div>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg"
                                            aria-labelledby="topbarUserDropdown">
                                            <?php if(auth()->user()->isCustomer()): ?>
                                                <li><a class="dropdown-item" href="<?php echo e(route('account.show')); ?>"><i class="bi bi-person me-2"></i>My Account</a></li>
                                            <?php endif; ?>
                                            <li>
                                                <form action="<?php echo e(route('logout')); ?>" method="POST" class="mb-0">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-box-arrow-left me-2"></i>
                                                        Logout
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                </header>

                <div id="main-content" class="pt-3 pb-4">
                    <div class="page-content">
                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                </div>

                <footer>
                    <div class="footer clearfix mb-0 text-muted">
                        <div class="float-start">
                            <p>2025 &copy; Sistem Informasi Universitas Darma Persada</p>
                        </div>
                        <div class="float-end">
                            <p>Crafted with by <a href="si.unsada.ac.id"></a></p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="<?php echo e(asset('assets/static/js/components/dark.js')); ?>?v=<?php echo e(@filemtime(public_path('assets/static/js/components/dark.js')) ?: '1.0.0'); ?>"></script>
        <script src="<?php echo e(asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js')); ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="<?php echo e(asset('assets/compiled/js/app.js')); ?>?v=<?php echo e(@filemtime(public_path('assets/compiled/js/app.js')) ?: '2.0.1'); ?>"></script>
        <!-- App JS -->
        <script src="<?php echo e(asset('js/app.js')); ?>?v=<?php echo e(@filemtime(public_path('js/app.js')) ?: '2.0.1'); ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            // Global helper to toggle password visibility with eye icon
            window.togglePasswordVisibility = function(inputId, buttonOrIconId) {
                const input = typeof inputId === 'string' ? document.getElementById(inputId) : inputId;
                if (!input) return;
                
                let icon = null;
                if (buttonOrIconId) {
                    if (typeof buttonOrIconId === 'string') {
                        icon = document.getElementById(buttonOrIconId);
                        if (!icon) {
                            const btn = document.querySelector(buttonOrIconId);
                            icon = btn ? (btn.tagName === 'I' ? btn : btn.querySelector('i')) : null;
                        }
                    } else if (buttonOrIconId instanceof HTMLElement) {
                        icon = buttonOrIconId.tagName === 'I' ? buttonOrIconId : buttonOrIconId.querySelector('i');
                    }
                }
                
                if (input.type === 'password') {
                    input.type = 'text';
                    if (icon) {
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    }
                } else {
                    input.type = 'password';
                    if (icon) {
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                }
            };

            // Desktop & Mobile Collapsible Responsive Sidebar System (No Freeze Guaranteed)
            (function() {
                function neutralizeMazerSidebar() {
                    if (window.sidebar) {
                        window.sidebar.createBackdrop = function() {};
                        window.sidebar.deleteBackdrop = function() {};
                        window.sidebar.toggleOverflowBody = function() {
                            document.body.style.overflowY = '';
                        };
                    }
                }

                function getSidebar() { return document.getElementById('sidebar'); }
                function getApp() { return document.getElementById('app'); }
                function isDesktop() { return window.innerWidth >= 1200; }

                function removeMobileBackdrop() {
                    document.querySelectorAll('.sidebar-backdrop').forEach(el => el.remove());
                    document.body.classList.remove('sidebar-mobile-open');
                    document.body.style.overflowY = '';
                }

                function openMobileSidebar() {
                    const sidebar = getSidebar();
                    if (!sidebar) return;
                    
                    sidebar.classList.add('active');
                    document.body.classList.add('sidebar-mobile-open');
                    
                    removeMobileBackdrop();
                    const backdrop = document.createElement('div');
                    backdrop.className = 'sidebar-backdrop show';
                    backdrop.onclick = closeMobileSidebar;
                    document.body.appendChild(backdrop);
                }

                function closeMobileSidebar() {
                    const sidebar = getSidebar();
                    if (sidebar) {
                        sidebar.classList.remove('active');
                    }
                    removeMobileBackdrop();
                }

                function toggleSidebar(e) {
                    if (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (typeof e.stopImmediatePropagation === 'function') {
                            e.stopImmediatePropagation();
                        }
                    }
                    neutralizeMazerSidebar();

                    const sidebar = getSidebar();
                    const app = getApp();

                    if (isDesktop()) {
                        // Desktop collapse / expand
                        removeMobileBackdrop();
                        const isNowCollapsed = document.body.classList.toggle('sidebar-collapsed');
                        if (app) app.classList.toggle('sidebar-collapsed', isNowCollapsed);
                        localStorage.setItem('metinca_sidebar_collapsed', isNowCollapsed ? 'true' : 'false');
                    } else {
                        // Mobile slide in / out
                        if (sidebar && sidebar.classList.contains('active')) {
                            closeMobileSidebar();
                        } else {
                            openMobileSidebar();
                        }
                    }
                }

                document.addEventListener('DOMContentLoaded', function() {
                    neutralizeMazerSidebar();

                    // Restore desktop state
                    if (isDesktop()) {
                        const isCollapsed = localStorage.getItem('metinca_sidebar_collapsed') === 'true';
                        if (isCollapsed) {
                            document.body.classList.add('sidebar-collapsed');
                            const app = getApp();
                            if (app) app.classList.add('sidebar-collapsed');
                        }
                    }

                    // Attach to toggle buttons using capture phase
                    document.querySelectorAll('.burger-btn, #toggleSidebarBtn').forEach(btn => {
                        btn.addEventListener('click', toggleSidebar, true);
                    });

                    // Attach to close buttons
                    document.querySelectorAll('.sidebar-hide, .sidebar-toggler').forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            if (typeof e.stopImmediatePropagation === 'function') {
                                e.stopImmediatePropagation();
                            }
                            closeMobileSidebar();
                        }, true);
                    });

                    // ESC key closes mobile sidebar
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            closeMobileSidebar();
                        }
                    });

                    // Handle window resize
                    window.addEventListener('resize', function() {
                        if (isDesktop()) {
                            closeMobileSidebar();
                            const isCollapsed = localStorage.getItem('metinca_sidebar_collapsed') === 'true';
                            document.body.classList.toggle('sidebar-collapsed', isCollapsed);
                            const app = getApp();
                            if (app) app.classList.toggle('sidebar-collapsed', isCollapsed);
                        } else {
                            document.body.classList.remove('sidebar-collapsed');
                            const app = getApp();
                            if (app) app.classList.remove('sidebar-collapsed');
                        }
                    });
                });
            })();
        </script>
        <?php echo $__env->yieldPushContent('scripts'); ?>
        <!-- Need: Apexcharts -->

</body>

</html>
<?php /**PATH C:\laragon\www\sales_metinca\resources\views/layouts/app.blade.php ENDPATH**/ ?>