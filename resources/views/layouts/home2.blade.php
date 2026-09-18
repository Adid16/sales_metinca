<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT.METINCA PRIMA INDUSTRIAL WORKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/homepage.css') }}?v={{ @filemtime(public_path('assets/css/homepage.css')) ?: '1.0.0' }}">
    <link rel="stylesheet" href="{{ asset('assets/css/menu-modal.css') }}?v={{ @filemtime(public_path('assets/css/menu-modal.css')) ?: '1.0.0' }}">
    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fs-5" href="/customer">
                PT. METINCA PRIMA INDUSTRIAL WORKS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/customer">Home</a>
                        {{-- <a class="nav-link {{ request()->routeIs('home.main') ? 'active' :'' }}" href="/home">Home</a> --}}
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/customer#profile">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/customer/divisions">Divisions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer_home.products') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer_home.facilities') }}">Facilities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer_home.gallery') }}">Gallery</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer_home.contact') }}">Contact</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer_home.login') }}">
                            <i class="bi bi-person-fill"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

   @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>PT. METINCA PRIMA INDUSTRIAL WORKS</h5>
                    <p>
                        The #1 Precision Casting and Tooling Facility in Indonesia
                    </p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Products</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">Investment Casting</a></li>
                        <li class="mb-2"><a href="#">Sand Casting</a></li>
                        <li class="mb-2"><a href="#">Valve</a></li>

                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contact</h5>

                    <p><i class="fas fa-phone me-2"></i>+62 21 1234 5678</p>
                    <p><i class="fas fa-envelope me-2"></i>info@metinca-prima.co.id</p>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center pt-3">
                <p>&copy; 2025 Metinca. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    @stack('scripts')
</body>

</html>
