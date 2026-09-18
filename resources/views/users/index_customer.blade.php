{{-- Inlcude layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'PT. Metinca Prima Industrial Works')

{{-- Untuk menggunakan css --}}
@push('styles')
    {{-- contoh --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/static/css/pages/dashboard.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/all.css') }}"> --}}
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon"
        href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjMzIgogICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzQiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJwcm9kdWNlZCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWZmaW5pdHkgRGVzaWduZXIgMS4xMC4xIgogICAgICBzdEV2dDp3aGVuPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLolwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMpDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC"
        type="image/png">

    <link rel="stylesheet" href="assets/extensions/simple-datatables/style.css">


    <link rel="stylesheet" href="./assets/compiled/css/table-datatable.css">
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
@endpush

{{-- Isi Content --}}
@section('content')
    <section class="section">
        <div class="card detail-card">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="card-title mb-0">
                    <i class="bi bi-person-workspace"></i> Customer Account 
                </h5>
            </div> 
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- <div class="card"> --}}
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-end">
                    <form action="{{ route('users.index') }}" method="GET" class="row g-1 align-items-end w-75 mt-2">
                        <div class="col-md-3">
                            {{-- <label class="form-label">Role</label> --}}
                            <select name="role" class="form-select form-select-sm my-0">
                                <option value="">All Roles</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff
                                </option>
                                <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                            </select>
                        </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-secondary btn-sm my-0">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-danger btn-sm my-0">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>
                </form>

                <button type="submit" class="btn btn-sm btn-primary my-0" style="margin-left:35px;" 
                        data-bs-toggle="modal" data-bs-target="#modalnewcustomer">
                        <i class="bi bi-person-plus-fill"></i> Add User
                </button>

                <a href="{{ route('users.customers.export', $filters ?? []) }}" class="btn btn-success btn-sm my-0"><i
                    class="bi bi-file-spreadsheet"></i> Export
                </a>

                    <div class="modal fade" id="modalnewcustomer" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header py-2" style="background:lightblue;">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add New user</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('users.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="customername">Name</label>
                                            <input type="text" name="name" class="form-control form-control-sm"
                                                id="customername" placeholder="Name">
                                        </div>

                                        <div class="form-group">
                                            <label for="companyname">Company</label>
                                            <input type="text" name="company" class="form-control form-control-sm"
                                                id="customername" placeholder="Company">
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="text" name="email" class="form-control form-control-sm"
                                                id="email" placeholder="Email">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="password">Password</label>
                                            <div class="input-group">
                                                <input type="password" name="password" class="form-control form-control-sm"
                                                    id="newCustPassword" placeholder="Min. 6 karakter" required minlength="6">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="togglePasswordVisibility('newCustPassword', this)" title="Lihat/Sembunyikan Password">
                                                    <i class="bi bi-eye" id="newCustEye"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="password_confirmation">Konfirmasi Password</label>
                                            <div class="input-group">
                                                <input type="password" name="password_confirmation" class="form-control form-control-sm"
                                                    id="newCustPasswordConfirm" placeholder="Ulangi password" required minlength="6">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="togglePasswordVisibility('newCustPasswordConfirm', this)" title="Lihat/Sembunyikan Password">
                                                    <i class="bi bi-eye" id="newCustConfirmEye"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="role">Role</label>
                                            <fieldset class="form-group">
                                                <select class="form-select form-select-sm" id="basicSelect"
                                                    name="role">
                                                    <option selected value="customer">Customer</option>
                                                </select>
                                            </fieldset>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger btn-sm"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success btn-sm">Save</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mt-3" id="table1">
                        <thead>
                            <tr>
                                <th><center>No</center></th>
                                <th><center>Nama</center></th>
                                <th><center>Company</center></th>
                                {{-- <th><center>Country</center></th> --}}
                                <th><center>Email</center></th>
                                {{-- <th><center>Password</center></th> --}}
                                {{-- <th><center>Phone</center></th> --}}
                                <th><center>Role</center></th>
                                {{-- <th><center>Address</center></th> --}}
                                <th><center>Action</center></th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 14px;">
                            @forelse ($users as $user)
                                <tr>
                                    <td><center>{{ $loop->iteration }}</center></td>
                                    <td style="width: 70px;">{{ $user->name }}</td>
                                    <td><center>{{ $user->company }}</center></td>
                                    <td><center>{{ $user->email }}</center></td>
                                    {{-- <td><center>{{ $user->password }}</center></td> --}}
                                    <td><center>{{ $user->role }} {{ ucfirst($user->divisi) ?? '' }}</center></td>
                                    <td><center>
                                            <button class="btn btn-sm btn-warning btn-edit" data-user-id="{{ $user->id }}" data-bs-toggle="modal" data-bs-target="#modalEditCustomer"><i
                                                    class="bi bi-pencil-square"></i></button>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Apakah anda yakin ingin menghapus user ini?')"
                                                    class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                    </center></td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center text-muted">No data available</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            {{-- </div> --}}
        
    </section>
    <div class="modal fade" id="modalEditCustomer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="editModalContent">


            </div>
        </div>
    </div>
    @push('scripts')
        <script src="assets/extensions/simple-datatables/umd/simple-datatables.js"></script>
        <script src="assets/static/js/pages/simple-datatables.js"></script>
        <script>
            // let dataTable = new simpleDatatables.DataTable("#table1");

            function toggleNewCustPass(inputId, iconId) {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                }
            }

            // Edit button click event
            const editButtons = document.querySelectorAll('.btn-edit');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    console.log('Fetching edit form for user ID:', userId);
                    fetch(`/users/${userId}/edit`)
                        .then(response => response.text())
                        .then(html => {
                            document.querySelector('#editModalContent').innerHTML = html;
                        })
                        .catch(error => {
                            console.error('Error fetching edit form:', error);
                        });
                });
            });

        </script>
    @endpush
@endsection
