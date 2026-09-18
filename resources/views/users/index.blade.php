{{-- Inlcude layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'Manajemen User - PT. Metinca Prima Industrial Works')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable.css') }}">
    <style>
        .avatar-initial {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.75rem;
            color: #ffffff;
            flex-shrink: 0;
        }
        .badge-role-admin {
            background-color: rgba(239, 68, 68, 0.15) !important;
            color: #ef4444 !important;
            border: 1px solid rgba(239, 68, 68, 0.3);
            font-weight: 600;
        }
        .badge-role-manager {
            background-color: rgba(245, 158, 11, 0.15) !important;
            color: #f59e0b !important;
            border: 1px solid rgba(245, 158, 11, 0.3);
            font-weight: 600;
        }
        .badge-role-staff {
            background-color: rgba(59, 130, 246, 0.15) !important;
            color: #3b82f6 !important;
            border: 1px solid rgba(59, 130, 246, 0.3);
            font-weight: 600;
        }
        .badge-plant-jakarta {
            background-color: rgba(14, 165, 233, 0.12) !important;
            color: #0284c7 !important;
            border: 1px solid rgba(14, 165, 233, 0.25);
        }
        .badge-plant-salatiga {
            background-color: rgba(168, 85, 247, 0.12) !important;
            color: #9333ea !important;
            border: 1px solid rgba(168, 85, 247, 0.25);
        }
        .badge-plant-bekasi {
            background-color: rgba(16, 185, 129, 0.12) !important;
            color: #059669 !important;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }
        .modal-custom-dark .modal-content {
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.45);
        }
        .form-label-custom {
            font-size: 0.72rem;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #8e9bb0;
            text-transform: uppercase;
            margin-bottom: 0.35rem;
        }
    </style>
@endpush

{{-- Isi Content --}}
@section('content')
    <div class="page-heading mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-1">Manajemen User</h3>
                <p class="text-muted mb-0">Kelola akun dan hak akses pengguna sistem</p>
            </div>
        </div>
    </div>

    <section class="section">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header border-0 pb-0 pt-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h5 class="card-title mb-0 fw-bold">Daftar Pengguna</h5>
                    <small class="text-muted">Total {{ $users->count() }} akun terdaftar</small>
                </div>
                
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <form action="{{ route('users.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        <select name="role" class="form-select form-select-sm" style="min-width: 120px;">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                        <button type="submit" class="btn btn-secondary btn-sm px-3">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                        @if(request()->filled('role'))
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        @endif
                    </form>

                    <button type="button" class="btn btn-primary btn-sm px-3 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalnewcustomer">
                        <i class="bi bi-plus-lg me-1"></i> Tambah User
                    </button>

                    <a href="{{ route('users.export', $filters ?? []) }}" class="btn btn-success btn-sm px-3 shadow-sm">
                        <i class="bi bi-file-earmark-excel me-1"></i> Export
                    </a>
                </div>
            </div>    
            
            <div class="card-body px-4 pt-3 pb-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="table1">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Departemen</th>
                                <th class="text-center">Plant / Cabang</th>
                                <th class="text-center">Created at</th>
                                <th class="text-center">Update at</th>
                                <th class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                @php
                                    // Generate initials
                                    $words = preg_split('/\s+/', trim($user->name));
                                    $initials = '';
                                    if (count($words) >= 2) {
                                        $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                                    } else {
                                        $initials = strtoupper(substr($user->name, 0, 2));
                                    }
                                    
                                    // Generate consistent avatar color
                                    $palette = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#6366f1', '#14b8a6'];
                                    $colorIndex = abs(crc32($user->name . $user->email)) % count($palette);
                                    $avatarBg = $palette[$colorIndex];

                                    // Role badge
                                    $role = strtolower($user->role ?? 'staff');
                                    $roleClass = 'badge-role-staff';
                                    if ($role === 'admin') $roleClass = 'badge-role-admin';
                                    elseif ($role === 'manager') $roleClass = 'badge-role-manager';

                                    // Plant badge
                                    $plant = $user->plant ?? 'Jakarta';
                                    $plantClass = 'badge-plant-jakarta';
                                    if (strtolower($plant) === 'salatiga') $plantClass = 'badge-plant-salatiga';
                                    elseif (strtolower($plant) === 'bekasi') $plantClass = 'badge-plant-bekasi';
                                @endphp
                                <tr>
                                    <td class="text-center text-muted fw-semibold">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-initial me-2 shadow-sm" style="background-color: {{ $avatarBg }};">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <span class="fw-semibold text-body">{{ $user->name }}</span>
                                                @if(auth()->id() === $user->id)
                                                    <span class="badge bg-secondary-subtle text-secondary ms-1 px-1 py-0" style="font-size: 0.65rem;">Anda</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted" style="font-size: 0.88rem;">{{ $user->email }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $roleClass }} px-2 py-1 rounded" style="font-size: 0.75rem;">
                                            {{ ucfirst($user->role ?? '-') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($user->divisi)
                                            <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem;">
                                                {{ ucwords($user->divisi) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $plantClass }} px-2 py-1 rounded" style="font-size: 0.75rem;">
                                            <i class="bi bi-geo-alt-fill me-1"></i>{{ $plant }}
                                        </span>
                                    </td>
                                    <td class="text-center text-muted" style="font-size: 0.82rem;">
                                        {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                                    </td>
                                    <td class="text-center text-muted" style="font-size: 0.82rem;">
                                        {{ $user->updated_at ? $user->updated_at->format('d M Y') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" 
                                                class="btn btn-outline-warning btn-sm btn-edit" 
                                                data-user-id="{{ $user->id }}" 
                                                data-bs-target="#modalEditCustomer" 
                                                data-bs-toggle="modal"
                                                title="Edit User">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus User">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                        Tidak ada data pengguna ditemukan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>   
                </div>    
            </div>
        </div>
    </section>
    
    <!-- Modal Tambah Pengguna -->
    <div class="modal fade modal-custom-dark" id="modalnewcustomer" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="modalNewUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0 pt-3 px-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-plus text-primary me-2 fs-5"></i>
                        <h5 class="modal-title fw-bold fs-6 mb-0" id="modalNewUserLabel">Tambah Pengguna</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 pt-3 pb-4">
                    <form action="{{ route('users.store') }}" method="POST" id="formCreateUser">
                        @csrf
                        
                        <!-- NAMA LENGKAP -->
                        <div class="mb-3">
                            <label class="form-label-custom">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" placeholder="Masukkan nama" required>
                        </div>

                        <!-- EMAIL -->
                        <div class="mb-3">
                            <label class="form-label-custom">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@metinca.com" required>
                        </div>

                        <!-- ROLE -->
                        <div class="mb-3">
                            <label class="form-label-custom">Role</label>
                            <select name="role" id="createRoleSelect" class="form-select" onchange="handleCreateRoleChange()" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin">Admin</option>
                                <option value="manager">Manager</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>

                        <!-- DEPARTEMEN -->
                        <div class="mb-3" id="createDivisiGroup">
                            <label class="form-label-custom">Departemen</label>
                            <select name="divisi" id="createDivisiSelect" class="form-select">
                                <option value="">-- Pilih Departemen --</option>
                                <option value="sales">Sales</option>
                                <option value="ppc">PPC</option>
                                <option value="quality">Quality</option>
                                <option value="design engineering">Design Engineering</option>
                            </select>
                            <small class="text-muted d-none" id="createDivisiStaffNote" style="font-size: 0.78rem;">
                                <i class="bi bi-info-circle me-1"></i>Role Staff dikhususkan untuk divisi Sales.
                            </small>
                        </div>

                        <!-- PLANT / CABANG -->
                        <div class="mb-3">
                            <label class="form-label-custom">Plant / Cabang</label>
                            <select name="plant" class="form-select" required>
                                <option value="">-- Pilih Plant / Cabang --</option>
                                <option value="Jakarta" selected>Jakarta</option>
                                <option value="Salatiga">Salatiga</option>
                                <option value="Bekasi">Bekasi</option>
                            </select>
                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-3">
                            <label class="form-label-custom">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="createPasswordInput" class="form-control" placeholder="Min. 6 karakter" required minlength="6">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('createPasswordInput', this)" title="Lihat/Sembunyikan Password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- KONFIRMASI PASSWORD -->
                        <div class="mb-4">
                            <label class="form-label-custom">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="createPasswordConfirmInput" class="form-control" placeholder="Ulangi password" required minlength="6">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('createPasswordConfirmInput', this)" title="Lihat/Sembunyikan Password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Pengguna -->
    <div class="modal fade modal-custom-dark" id="modalEditCustomer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
            <div class="modal-content" id="editModalContent">
                <div class="p-4 text-center text-muted">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                    Memuat data pengguna...
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/simple-datatables.js') }}"></script>
    <script>
        // Password toggle handlers
        function toggleCreatePassword() {
            const input = document.getElementById('createPasswordInput');
            const icon = document.getElementById('createEyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }

        function toggleCreateConfirmPassword() {
            const input = document.getElementById('createPasswordConfirmInput');
            const icon = document.getElementById('createConfirmEyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }

        // Dynamic Role-Department handler for Create Modal
        function handleCreateRoleChange() {
            const role = document.getElementById('createRoleSelect').value;
            const divisiGroup = document.getElementById('createDivisiGroup');
            const divisiSelect = document.getElementById('createDivisiSelect');
            const staffNote = document.getElementById('createDivisiStaffNote');

            if (role === 'admin') {
                divisiGroup.classList.add('d-none');
                divisiSelect.value = '';
                divisiSelect.removeAttribute('required');
                staffNote.classList.add('d-none');
            } else if (role === 'staff') {
                divisiGroup.classList.remove('d-none');
                divisiSelect.value = 'sales';
                divisiSelect.setAttribute('required', 'required');
                staffNote.classList.remove('d-none');
                // Lock other options
                Array.from(divisiSelect.options).forEach(opt => {
                    if (opt.value && opt.value !== 'sales') {
                        opt.disabled = true;
                    } else {
                        opt.disabled = false;
                    }
                });
            } else if (role === 'manager') {
                divisiGroup.classList.remove('d-none');
                divisiSelect.setAttribute('required', 'required');
                staffNote.classList.add('d-none');
                // Unlock all options
                Array.from(divisiSelect.options).forEach(opt => {
                    opt.disabled = false;
                });
                if (divisiSelect.value === '') {
                    divisiSelect.value = 'sales';
                }
            } else {
                divisiGroup.classList.remove('d-none');
                divisiSelect.removeAttribute('required');
                staffNote.classList.add('d-none');
                Array.from(divisiSelect.options).forEach(opt => {
                    opt.disabled = false;
                });
            }
        }

        // Validate password match on submit
        document.getElementById('formCreateUser').addEventListener('submit', function(e) {
            const pass = document.getElementById('createPasswordInput').value;
            const confirm = document.getElementById('createPasswordConfirmInput').value;
            if (pass !== confirm) {
                e.preventDefault();
                alert('Konfirmasi password tidak cocok dengan password!');
            }
        });

        // Edit button click event
        const editButtons = document.querySelectorAll('.btn-edit');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const editModalContent = document.querySelector('#editModalContent');
                editModalContent.innerHTML = '<div class="p-4 text-center text-muted"><div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>Memuat data pengguna...</div>';

                fetch(`/users/${userId}/edit`)
                    .then(response => response.text())
                    .then(html => {
                        editModalContent.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error fetching edit form:', error);
                        editModalContent.innerHTML = '<div class="p-4 text-center text-danger"><i class="bi bi-exclamation-circle me-2"></i>Gagal memuat form edit.</div>';
                    });
            });
        });
    </script>
@endpush
