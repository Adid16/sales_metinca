<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Metinca</title>



    {{-- <link rel="shortcut icon" href="{{ asset('assets/compiled/svg/favicon.svg') }}" type="image/x-icon"> --}}
    {{-- <link rel="shortcut icon"
        href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjMzIgogICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzQiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJwcm9kdWNlZCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWZmaW5pdHkgRGVzaWduZXIgMS4xMC4xIgogICAgICBzdEV2dDp3aGVuPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLolwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMpDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC"
        type="image/png"> --}}

    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}"> --}}
    @stack('styles')
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <div id="app">
        <div id = "main" class="layout-horizontal">
            <header>
                <div class = "header-top py-2">
                    <div class="container">
                        {{-- <div class="logo">
                            <a href="index.html"><img src="{{ asset('assets/compiled/svg/logo.svg') }}" alt="Logo"></a>
                        </div> --}}

                        <div class="header">
                            <h3 style="margin-left: 260px;"> @yield('title') </h3>
                        </div>

                        <div class="header-top-right">
                            <div class="dropdown d-flex align-items-center">

                                <li class="nav-item dropdown me-3">
                                    <a class="nav-link active dropdown-toggle text-gray-600" href="#"
                                        data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                        <i class="bi bi-bell bi-sub fs-4"></i>
                                        <span
                                            class="badge badge-notification bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown"
                                        aria-labelledby="dropdownMenuButton">
                                        <li class="dropdown-header d-flex justify-content-between align-items-center">
                                            <h6>Notifications</h6>
                                            @if(auth()->user()->unreadNotifications->count() > 0)
                                                <form action="{{ route('notifikasi.markAllRead') }}" method="POST" class="mb-0">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-link">Mark all read</button>
                                                </form>
                                            @endif
                                        </li>
                                        @foreach (auth()->user()->notifications as $notification)
                                            {{-- link now marks as read then redirects to target url --}}
                                            <li class="dropdown-item notification-item {{ is_null($notification->read_at) ? 'bg-light' : '' }}">
                                                <a class="d-flex align-items-center"
                                                    href="{{ route('notifikasi.read', $notification->id) }}">
                                                    <div class="notification-icon bg-primary">
                                                        <i class="bi bi-cart-check"></i>
                                                    </div>
                                                    <div class="notification-text ms-4">
                                                        <p class="notification-title font-bold">
                                                            {{ $notification->data['message'] }}
                                                        </p>
                                                        {{-- <p class="notification-subtitle font-thin text-sm">
                                                            {{ $notification->data['quotation_id'] }}
                                                        </p> --}}
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                        <li>
                                            <p class="text-center py-2 mb-0">
                                                <a href="{{ route('notifikasi') }}">See all notifications</a>
                                            </p>
                                        </li>
                                    </ul>
                                </li>
                                </ul>

                                <a href="#" id="topbarUserDropdown"
                                    class="user-dropdown d-flex align-items-center dropend dropdown-toggle "
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="avatar avatar-md2">
                                        <img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Avatar">
                                    </div>
                                    <div class="text">
                                        <h6 class="b-0 text-gray-600">{{ auth()->user()->name }}</h6>
                                        <p class="mb-0 text-sm text-gray-600">{{ ucfirst(auth()->user()->role) }}
                                            {{ ucfirst(auth()->user()->divisi) ?? '' }} </p>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg"
                                    aria-labelledby="topbarUserDropdown">
                                    @if (auth()->user()->isCustomer())
                                        <li><a class="dropdown-item" href="{{ route('account.show') }}">My Account</a></li>
                                    @endif
                                    {{-- <li><a class="dropdown-item" href="#">Settings</a></li> --}}
                                    
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
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
                                        src="{{ asset('assets/compiled/jpg/logometinca.jpg') }}" alt="Logo"
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

                            <li class="sidebar-item {{ Route::is('dashboard') ? 'active' : '' }}">
                                <a href="/" class='sidebar-link'>
                                    <i class="bi bi-grid-fill"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            {{-- <li class="sidebar-item {{ Route::is('userlist') ? 'active' : '' }}">
                            <a href="userlist" class='sidebar-link'>
                                <i class="bi bi-person-workspace"></i>
                                <span>User Management</span>
                            </a>
                        </li> --}}

                            @if (auth()->user()->isManager())
                                <li class="sidebar-item has-sub">
                                    <a href="#" class='sidebar-link'>
                                        <i class="bi bi-person-workspace"></i>
                                        <span>User Management</span>
                                    </a>
                                    <ul class="submenu ">
                                        <li class="submenu-item">
                                            <a href="{{ route('users.customer') }}" class='submenu-link'>Data
                                                Customer</a>
                                        </li>

                                        <li class="submenu-item">
                                            <a href="{{ route('users.index') }}" class='submenu-link'>Data
                                                Employee</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                            @if(!auth()->user()->isCustomer())
                            <li class="sidebar-item {{ Route::is('articles*') ? 'active' : '' }}">
                                <a href="{{ route('articles.index') }}" class='sidebar-link'>
                                    <i class="bi bi-box-seam-fill"></i>
                                    <span>Pricelist Products</span>
                                </a>
                            </li>
                            @endif

                            {{-- @if (auth()->user()->isAdmin())
                        <li class="sidebar-item {{ Route::is('users*') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}" class='sidebar-link'>
                                <i class="bi bi-person-workspace"></i>
                                <span>User Management</span>
                            </a>
                        </li>
                        @endif --}}

@if(!(auth()->user()->isManager() && auth()->user()->divisi !== 'sales'))                            <li class="sidebar-item {{ Route::is('requests-project*') ? 'active' : '' }}">
                                <a href="{{ route('requests-project.index') }}" class='sidebar-link'>
                                    <i class="bi bi-send-plus-fill"></i>
                                    <span>Request</span>
                                </a>
                            </li>
                            @endif

                            {{-- @if (!auth()->user()->isStaff()) --}}
                            <li class="sidebar-item {{ Route::is('quotations*') ? 'active' : '' }}">
                                <a href="{{ route('quotations.index') }}" class='sidebar-link'>
                                    <i class="bi bi-file-earmark-text-fill"></i>
                                    <span>Quotation</span>
                                </a>
                            </li>

                            @if (auth()->user()->isCustomer())
                            <li class="sidebar-item {{ Route::is('purchase-orders*') ? 'active' : '' }}">
                                <a href="{{ route('purchase-orders.index') }}" class='sidebar-link'>
                                    <i class="bi bi-file-earmark-richtext-fill"></i>
                                    <span>Purchase Order</span>
                                </a>
                            </li>
                            @endif

                            @if (!auth()->user()->isCustomer())
                            <li class="sidebar-item has-sub">
                                <a href="#" class='sidebar-link'>
                                    <i class="bi bi-file-earmark-richtext-fill"></i>
                                        <span>Purchase Order</span>
                                </a>
                                    <ul class="submenu ">
                                        <li class="submenu-item {{ Route::is('purchase-orders*') ? 'active' : '' }}">
                                            <a href="{{ route('purchase-orders.index') }}" class='submenu-link'>Data
                                                PO External</a>
                                        </li>

                                        <li class="submenu-item">
                                            <a href="{{ route('purchase-orders-internal.index') }}" class='submenu-link'>Data
                                                PO Internal</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif


                            @if (!auth()->user()->isCustomer())
                            <li class="sidebar-item {{ Route::is('po-schedule.index') ? 'active' : '' }}">
                                <a href="{{ route('po-schedule.index') }}" class='sidebar-link'>
                                    <i class="bi bi-calendar3"></i>
                                    <span>PO Schedule</span>
                                </a>
                            </li>
                            @endif


                            
                            {{-- @endif --}}
                            {{-- <li class="sidebar-item has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-file-earmark-text-fill"></i>
                                <span>Quotation</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item  ">
                                    <a href="{{ route('quotation') }}" class="submenu-link">New Quotation</a>
                                </li>

                                 <li class="submenu-item  ">
                                    <a href="{{ route('quotationlist') }}" class="submenu-link">Quotation List</a>

                                </li>
                            </ul>
                        </li> --}}


                            {{-- <li class="sidebar-item has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-collection-fill"></i>
                                <span>PO</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="{{ route('polistint') }}" class='submenu-link'>PO</a>
                                </li>

                                <li class="submenu-item ">
                                    <a href="{{ route('polistint') }}" class='submenu-link'>PO Amandement</a>
                                </li>
                            </ul>
                        </li> --}}

                            @if (!auth()->user()->isCustomer())
                                <li class="sidebar-item {{ Route::is('contracts*') ? 'active' : '' }}">
                                    <a href="{{ route('contracts.index') }}" class='sidebar-link'>
                                        <i class="bi bi-collection-fill"></i>
                                        <span>Contract Review Sheet</span>
                                    </a>
                                </li>
                            @endif
                            {{-- <li class="sidebar-item has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-collection-fill"></i>
                                <span>Contract</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="{{ route('contract') }}" class='submenu-link'>New Contract</a>
                                </li>

                                <li class="submenu-item ">
                                    <a href="{{ route('contractlist') }}" class='submenu-link'>Contract List</a>
                                </li>
                            </ul>
                        </li> --}}

                            {{-- ----------------------------------------------------------------------- --}}

                            {{-- <li class="sidebar-item {{ Route::is('polist') ? 'active' : '' }} ">
                            <a href="{{ route('polist') }}" class='sidebar-link'>
                                <i class="bi bi-collection-fill"></i>
                                <span>PO List</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ Route::is('poamandement') ? 'active' : ''}}">
                            <a href="{{ route('poamandement') }}" class='sidebar-link'>
                                <i class="bi bi-file-earmark-diff-fill"></i>
                                <span>PO Amandement</span>
                            </a>
                        </li>

                        <li class="sidebar-item  {{ Route::is('pocomplain') ? 'active' : '' }}">
                            <a href="{{ route('pocomplain') }}" class='sidebar-link'>
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span>PO Complain</span>
                            </a>
                        </li> --}}
                            @if (auth()->user()->isAdmin())
                                {{-- <li class="sidebar-item {{ Route::is('pohistory') ? 'active' : '' }} ">
                                    <a href="#" class='sidebar-link'>
                                        <i class="bi bi-clock-fill"></i>
                                        <span>History Activity</span>
                                    </a>
                                </li> --}}

                                {{-- <li class="sidebar-item {{ Route::is('#') ? 'active' : '' }} ">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-calendar-plus-fill"></i>
                                <span>Priority Scheduling</span>
                            </a>
                        </li> --}}
                            @endif
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
                    @yield('content')
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
        <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
        <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
        <!-- App JS -->
        <script src="{{ asset('js/app.js') }}"></script>
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
            //         App.ajax('{{ route('logout') }}', 'POST',new FormData(this)).then(response => {
            //             Swal.fire({
            //                 title: 'Berhasil!',
            //                 text: 'Anda telah logout.',
            //                 icon: 'success',
            //                 timer: 1500,
            //                 showConfirmButton: false
            //             }).then(() => {
            //                 window.location.href = '{{ route('login') }}';
            //             });

            //         }).catch(error => {
            //             console.log(error);
            //             App.error('Gagal Logout' || 'Terjadi kesalahan saat logout.');
            //         });
            //     });
            // });
        </script>
        @stack('scripts')
        <!-- Need: Apexcharts -->

</body>

</html>
