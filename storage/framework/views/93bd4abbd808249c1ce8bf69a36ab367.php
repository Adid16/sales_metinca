<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metinca - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/login.css')); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="bi bi-bag-check-fill"></i>
                </div>
                <h1 class="brand-name">Sales</h1>
                
            </div>

            <!-- Alert Example (hidden by default) -->
            <div class="alert alert-danger d-none" id="errorAlert" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <span id="errorMessage">Email atau password salah!</span>
            </div>

            <!-- Login Form -->
            <form id="loginForm">
                <!-- Username/Email -->
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="username" class="form-label">
                        <i class="bi bi-person-fill me-1"></i>Email
                    </label>
                    <div class="input-group">
                        <input type="text" name="email" class="form-control with-icon" id="username" placeholder="Masukkan username atau email" required>
                        <span class="input-icon">
                            <i class="bi bi-person"></i>
                        </span>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock-fill me-1"></i>Password
                    </label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control with-icon" id="password" placeholder="Masukkan password" required>
                        <span class="input-icon password-toggle" onclick="togglePassword()">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>

                <!-- Remember & Forgot -->
                

                <!-- Login Button -->
                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </button>
            </form>

            
            <div class="signup-link">
                Untuk kendala login hubungi Admin.
                <div>
                    <a href="/home">Homepage</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
    <script src="<?php echo e(asset('js/app.js')); ?>"></script>
    <script>
        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }

        // Handle Login Form Submit
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            // const remember = document.getElementById('remember').checked;

            var formData = new FormData(this);

            // Simulate login validation
            if (username && password) {
                // Show success message (in real app, this would be an API call)
                //console.log('Login attempt:', { username, password, remember });

                App.loading('Proses login');

                App.ajax('<?php echo e(route('login.store')); ?>', 'POST',formData).then(response => {
                    // Handle successful login
                    // For example, redirect to dashboard
                    Swal.fire({
                        title: 'Login Berhasil',
                        // text: 'Welcome Back!',
                        icon: 'success',
                        confirmButtonText: 'Lanjutkan'
                    }).then(() => {
                    window.location.href = '<?php echo e(route('dashboard')); ?>';
                    });
                }).catch(error => {
                    // Handle login error
                    App.closeLoading();
                    App.error('Gagal Login',error.response.data.message || 'Terjadi kesalahan saat login.');
                });
                // Example: Show error
                // showError('Username atau password salah!');

                // Example: Successful login redirect
                //alert('Login berhasil! Redirecting...');
                // window.location.href = 'dashboard.html';
            }
        });

        // Show Error Message
        function showError(message) {
            const errorAlert = document.getElementById('errorAlert');
            const errorMessage = document.getElementById('errorMessage');

            errorMessage.textContent = message;
            errorAlert.classList.remove('d-none');

            // Auto hide after 5 seconds
            setTimeout(() => {
                errorAlert.classList.add('d-none');
            }, 5000);
        }



        // Hide error alert when user starts typing
        document.getElementById('username').addEventListener('input', function() {
            document.getElementById('errorAlert').classList.add('d-none');
        });

        document.getElementById('password').addEventListener('input', function() {
            document.getElementById('errorAlert').classList.add('d-none');
        });
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\sales_metinca\resources\views/auth/login.blade.php ENDPATH**/ ?>