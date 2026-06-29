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
        href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjMzIgogICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzQiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZVxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjUtMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJwcm9kdWNlZCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWZmaW5pdHkgRGVzaWduZXIgMS4xMC4xIgogICAgICBzdEV2dDp3aGVuPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLorwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMpDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC"
        type="image/png">

    <link rel="stylesheet" href="assets/extensions/simple-datatables/style.css">

    <link rel="stylesheet" href="./assets/compiled/css/table-datatable.css">
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="{{ asset('assets/css/statustabel.css') }}">
@endpush

@section('content')
    <div class="card detail-card">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-send-plus-fill"></i> Contract Review Sheet
            </h5>
        </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12">

                {{-- FLASH MESSAGE --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card-body py-2">
                    <form class="row g-2 align-items-center mt-0" method="GET" action="{{ route('contracts.index') }}">
                        <div class="col-md-3">
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
                        <div class="col-auto">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Status</option>
                                <option value="created" {{ (isset($filters['status']) && $filters['status']=='created') ? 'selected' : '' }}>Created</option>
                                <option value="revision" {{ (isset($filters['status']) && $filters['status']=='revision') ? 'selected' : '' }}>Revision</option>
                                <option value="done" {{ (isset($filters['status']) && $filters['status']=='done') ? 'selected' : '' }}>Done</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="dept" class="form-select form-select-sm">
                                <option value="">Departements</option>
                                <option value="sales" {{ (isset($filters['dept']) && $filters['dept']=='sales') ? 'selected' : '' }}>Sales</option>
                                <option value="quality" {{ (isset($filters['dept']) && $filters['dept']=='quality') ? 'selected' : '' }}>Quality</option>
                                <option value="ppc" {{ (isset($filters['dept']) && $filters['dept']=='ppc') ? 'selected' : '' }}>PPC</option>
                                <option value="design engineering" {{ (isset($filters['dept']) && $filters['dept']=='design engineering') ? 'selected' : '' }}>Development Engineering</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                            <button type="submit" formaction="{{ route('contracts.export') }}" class="btn btn-sm btn-success">Export</button>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle table-bordered" id="table1">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="50">No</th>
                                    <th class="text-center">Req Id</th>
                                    <th class="text-center">Quotation No</th>
                                    <th class="text-center">PO No</th>
                                    <th class="text-center">Tinjauan Kontrak</th>
                                    <th class="text-center">Part No</th>
                                    <th class="text-center">Part Name</th>
                                    <th class="text-center">Manager Sales</th>
                                    <th class="text-center">Manager Quality</th>
                                    <th class="text-center">Manager PPC</th>
                                    <th class="text-center">Manager DE</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" width="140">Action</th>
                                </tr>
                            </tbody>
                            <tbody>
                                @forelse ($contracts as $index => $contract)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-sm bg-dark">{{ $contract->id }}</span>
                                        </td>
                                        <td>{{ $contract->quotation->quotation_no ?? '-' }}</td>
                                        <td class="fw-semibold">{{ $contract->order_no }}</td>
                                        <td class="fw-semibold">{{ $contract->contract_no }}</td>
                                        <td>{{ $contract->part_no ?? '-' }}</td>
                                        <td>{{ $contract->part_name ?? '-' }}</td>
                                        <td class="text-center">{!! $contract->sales_approver ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-exclamation-circle-fill text-warning"></i>' !!}</td>
                                        <td class="text-center">{!! $contract->quality_approver ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-exclamation-circle-fill text-warning"></i>' !!}</td>
                                        <td class="text-center">{!! $contract->ppc_approver ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-exclamation-circle-fill text-warning"></i>' !!}</td>
                                        <td class="text-center">{!! $contract->dev_engineering_approver ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-exclamation-circle-fill text-warning"></i>' !!}</td>
                                        
                                        {{-- 1. PERUBAHAN KOLOM STATUS (MENDETEKSI AMENDED) --}}
                                        <td class="text-center">
                                            @if($contract->status == 'amended')
                                                <span class="badge bg-light-danger text-danger fw-bold">Amended (History)</span>
                                            @else
                                                <span class="badge bg-light-primary text-primary">{{ ucfirst($contract->status) ?? '-' }}</span>
                                            @endif
                                        </td>

                                        {{-- 2. PERUBAHAN KOLOM ACTION (MENGUNCI JIKA AMENDED) --}}
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('contracts.show', $contract->id) }}"
                                                    class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                @if($contract->status == 'amended')
                                                    <span class="badge bg-secondary ms-1 d-flex align-items-center" data-bs-toggle="tooltip" title="Kontrak ini versi usang">
                                                        <i class="bi bi-lock-fill me-1"></i> Locked
                                                    </span>
                                                @else
                                                    @if(in_array(auth()->user()->role,['admin','staff']) && in_array($contract->status,['created','revision']) )
                                                        <a href="{{ route('contracts.edit', $contract->id) }}"
                                                            class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Edit Kontrak">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('contracts.destroy', $contract->id) }}"
                                                            method="POST" onsubmit="return confirm('Hapus contract ini?')" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Hapus">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif

                                               {{-- Tombol PDF: Selalu aktif untuk Staff Sales, ATAU aktif untuk semua (termasuk Manager) jika ke-4 divisi sudah Approve --}}
                                                @if( (auth()->user()->role == 'staff' && strtolower(auth()->user()->divisi) == 'sales') || ($contract->sales_approver && $contract->ppc_approver && $contract->quality_approver && $contract->dev_engineering_approver) )
                                                    <a target="_blank" href="{{ route('contract.pdf', $contract->id) }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Download PDF">
                                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center text-muted">
                                            No data available
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    <div class="mt-3">
                        {{ $contracts->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="assets/extensions/simple-datatables/umd/simple-datatables.js"></script>
    <script src="assets/static/js/pages/simple-datatables.js"></script>
    <script>
        let dataTable = new simpleDatatables.DataTable("#table1");
        
        // Inisialisasi Tooltip Bootstrap mase
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
@endpush