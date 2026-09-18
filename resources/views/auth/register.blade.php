<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metinca - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="bi bi-person-fill"></i>
                </div>
                <h1 class="brand-name" style="margin-bottom:0px;">Customer</h1>
                {{-- <p class="brand-tagline">The Starter App for metinca application web program</p> --}}
            </div>

            <!-- Alert Example (hidden by default) -->
            <div class="alert alert-danger d-none" id="errorAlert" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <span id="errorMessage">Invalid email or password!</span>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('register.store') }}" id="RegisterForm" style="margin-top:0px;">
                <!-- Username/Email -->
                @csrf
                <div class="row">
                    <div class="col-md-12 ">
                        <div class= "form-group mb-1 d-flex align-items-center">
                            <label for="name" class="form-label small mb-0" style="min-width: 80px;">Name</label>
                            <input type="text" name="name" class="form-control form-control-sm @error('name') is-invalid @enderror" 
                                id="name" placeholder="Enter your name" required>
                        </div>    
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 ">
                        <div class= "form-group mb-1 d-flex align-items-center">
                            <label for="company" class="form-label small mb-0" style="min-width: 80px;">Company</label>
                            <input type="text" name="company" class="form-control form-control-sm @error('company') is-invalid @enderror" 
                                id="company" placeholder="Enter your name" required>
                        </div>    
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 ">
                        <div class= "form-group mb-1 d-flex align-items-center">
                            <label for="username" class="form-label small mb-0" style="min-width: 80px;">Email</label>
                            <input type="text" name="email" class="form-control form-control-sm @error('email') is-invalid @enderror" 
                                id="username" placeholder="Enter your email" required>
                        </div>    
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group mb-2 d-flex align-items-center">
                            <label for="password" class="form-label small mb-0" style="min-width: 80px;">Password</label>
                            <div class="input-group input-group-sm flex-grow-1">
                                <input type="password" name="password" class="form-control form-control-sm @error('password') is-invalid @enderror" 
                                    id="password" placeholder="Enter your password" required minlength="6">
                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="toggleRegisterPassword('password', this)" title="Lihat/Sembunyikan Password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>    
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-2 d-flex align-items-center">
                            <label for="password_confirmation" class="form-label small mb-0" style="min-width: 80px;">Confirm</label>
                            <div class="input-group input-group-sm flex-grow-1">
                                <input type="password" name="password_confirmation" class="form-control form-control-sm @error('password_confirmation') is-invalid @enderror" 
                                    id="password_confirmation" placeholder="Confirm your password" required minlength="6">
                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="toggleRegisterPassword('password_confirmation', this)" title="Lihat/Sembunyikan Password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        @error('password_confirmation')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Remember & Forgot -->
                {{-- <div class="remember-forgot">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember">
                        <label class="form-check-label" for="remember">
                            Ingat saya
                        </label>
                    </div>
                    <a href="#" class="forgot-link">Lupa password?</a>
                </div> --}}

                <!-- Register Button -->
                <button type="submit" class="btn-register" style="margin-top:1px; margin-bottom:1px;" >
                    Register
                </button>
            </form>

            {{-- <!-- Divider -->
            <div class="divider">
                <span>atau masuk dengan</span>
            </div>

            <!-- Social Login -->
            <div class="social-login">
                <button class="btn-social" onclick="socialLogin('google')">
                    <i class="bi bi-google"></i>
                    Google
                </button>
                <button class="btn-social" onclick="socialLogin('microsoft')">
                    <i class="bi bi-microsoft"></i>
                    Microsoft
                </button>
            </div>--}}

            <!-- Sign Up Link -->
            <div class="signup-link" style="margin-bottom:0px; margin-top:15px;">
                Already have account? <a href="/customer/login">Login!</a> <a href="/customer">Homepage</a>
            </div> 
            {{-- <div class="signup-link"> --}}
                {{-- For your login issue just email us in loginissue@gmail.com  --}}
                {{-- <a href="/customer" style="margin-top:0px; margin-bottom:0px;">Homepage</a> --}}
            {{-- </div> --}}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Toggle Password Visibility
        function showError(message) {
            const alert = document.getElementById('errorAlert');
            document.getElementById('errorMessage').innerText = message;
            alert.classList.remove('d-none');
        }
        
        function toggleRegisterPassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn ? btn.querySelector('i') : null;
            if (!input) return;
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
        }

        // Handle Login Form Submit
        document.getElementById('RegisterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const name = document.getElementById('name').value;
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            //const remember = document.getElementById('remember').checked;

            var formData = new FormData(this);

            // Simulate login validation
            if (!name || !username || !password || !passwordConfirmation) 
                {
                    showError('Please fill in all fields!');
                    return;
                }

            if (password !== passwordConfirmation) 
            {
                showError('Passwords do not match!');
                return;
            }
                var formData = new FormData(this);

    App.loading('Authentication process');

    App.ajax('/register', 'POST', formData).then(response => {
        Swal.fire({
            title: 'Register Success',
            icon: 'success',
            confirmButtonText: 'Login Now'
        }).then(() => {
            window.location.href = '/login';
        });
    }).catch(error => {
        App.closeLoading();
        const errors = error.response?.data?.errors;
        if (errors) {
            // Tampilkan error validasi Laravel
            const firstError = Object.values(errors)[0][0];
            showError(firstError);
        } else {
            showError(error.response?.data?.message || 'Register failed. Please try again.');
        }
    });
});
    </script>
</body>
</html>
