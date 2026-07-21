{{-- Inlcude layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'PT. Metinca Prima Industrial Works')

{{-- Untuk menggunakan css --}}
@push('styles')
    {{-- contoh --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/static/css/pages/dashboard.css') }}"> --}}
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon"
        href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjMzIgogICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzQiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJwcm9kdWNlZCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWZmaW5pdHkgRGVzaWduZXIgMS4xMC4xIgogICAgICBzdEV2dDp3aGVuPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLolwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMpDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC"
        type="image/png">

    <link rel="stylesheet" href="assets/extensions/simple-datatables/style.css">


    <link rel="stylesheet" href="./assets/compiled/css/table-datatable.css">
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="{{ asset('assets/css/statustabel.css') }}">
@endpush


{{-- Isi content --}}
@section('content')
<div class="card detail-card">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-send-plus-fill"></i> Request Projects
            </h5>
        </div>

        <section class="section">
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
            <div class="card-body py-0">
                <form class="mb-3" method="GET" action="{{ route('requests-project.index') }}">
                    <div class="row g-2 align-items-end mt-0">
                        <div class= "col-md-3">
                            <div class="d-flex align-items-center gap-1">
                                <label class="form-label small mb-0 text-nowrap">From : </label>
                                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $filters['start_date'] ?? '' }}" placeholder="From">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-1">
                                <label class="form-label small mb-0 text-nowrap">To : </label>
                                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $filters['end_date'] ?? '' }}" placeholder="To">
                            </div>
                        </div>
                        
                        @if(auth()->user()->isAdmin() || auth()->user()->isManager() || (auth()->user()->role == 'staff' && auth()->user()->divisi == 'sales') || auth()->user()->isCustomer())
                        <div class="col-md-2">
                            <div class="d-flex align-items-center gap-1">
                                <select name="sales_id" class="form-select form-select-sm">
                                    <option value="">All Sales</option>
                                    @foreach($sales as $s)
                                        <option value="{{ $s->id }}" {{ (isset($filters['sales_id']) && $filters['sales_id']==$s->id) ? 'selected' : '' }}>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
            
                    <div class="col-md-2 d-flex gap-1">
                        {{-- <div class="d-flex align-items-center gap-1"> --}}
                            @if (auth()->user()->isAdmin() || auth()->user()->isManager() || (auth()->user()->role == 'staff' && auth()->user()->divisi == 'sales') || auth()->user()->isCustomer())
                                <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
                                <a href = "{{ route('requests-project.index') }}" class="btn btn-sm btn-danger">Clear</a>
                                {{-- <button type="submit" class="btn btn-sm btn-danger">Clear</button> --}}
                                <button type="submit" formaction="{{ route('requests-project.export') }}" class="btn btn-success btn-sm btn-end text-end">Export</button>
                            @endif
                            @if (auth()->user()->isCustomer())
                                <a href="{{ route('requests-project.create') }}" class="btn btn-primary btn-sm"> New </a>
                            @endif
                        {{-- </div> --}}
                    </div>
                </form>
            </div>
                <table class="table table-hover text-nowrap" id="table1">
                    <thead>
                        <tr>
                            <th><center>No</center></th>
                            <th><center>Req Id</center></th>
                            <th><center>Name</center></th>
                            <th><center>Company</center></th>
                            <th><center>Email</center></th>
                            <th><center>Subject</center></th>
                            <th><center>Request Date</center></th>
                            <th><center>Sales PIC</center></th>
                            <th><center>Action</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($projects as $project)
                            <tr>
                                <td><center>{{ $loop->iteration }}</center></td>
                                <td><center><span class="badge badge-sm bg-primary">{{ $project->id }}</span></center></td>
                                <td><center>{{ $project->name }}</center></td>
                                <td><center>{{ $project->company }}</center></td>
                                <td><center>{{ $project->email }}</center></td>
                                <td><center>{{ $project->subject }}</center></td>
                                <td><center>{{ $project->created_at->format('d F Y ') }}</center></td>
                                <td><center>
                                    @if($project->assignment)
                                        <span class="badge badge-sm bg-success">{{ $project->assignment->sales->name }}</span>
                                    @else
                                        <span class="text-muted"></span>
                                    @endif
                                </center></td>
                                <td><center>
                                    <a href="{{ route('requests-project.show', $project->id) }}" class="btn btn-sm btn-info">         
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    @if(auth()->user()->isAdmin() || (auth()->user()->role == 'staff' && auth()->user()->divisi == 'sales'))                                        @if (!$project->assignment)
                                        {{-- KETIKA SUDAH DIAMBIL MAKA BUTTON TAMBAH DISABLE --}}
                                            <form action="{{ route('requests-project.assign', $project->id) }}" method="POST" class="d-inline">                                        
                                          @csrf
                                        {{-- KETIKA BELUM DIAMBIL BUTTON PLUS BISA DIKLIK --}}
                                                <button type="submit" class="btn btn-sm btn-primary"
                                                    onclick="return confirm('Ambil request project ini?')">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </form>
                                                @endif
                                    @endif 
                                    
                                    {{-- <button type="submit" class="btn btn-sm btn-primary"
                                        onclick="return confirm('Ambil request project ini?')">
                                        <i class="bi bi-plus"></i>
                                    </button> --}}
                                </form>
                                   
                                </center></td>
                            </tr>
                        @empty
    <tr>
        <td colspan="9" class="text-center">No data available</td>
    </tr>
@endforelse
                    </tbody>
                </table>
            </div>
        {{-- </div> --}}
    </section>
    @push('scripts')
        <script src="assets/extensions/simple-datatables/umd/simple-datatables.js"></script>
        <script src="assets/static/js/pages/simple-datatables.js"></script>
        <script>
            let dataTable = new simpleDatatables.DataTable("#table1");
        </script>
    @endpush
@endsection