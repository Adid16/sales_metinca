{{-- Include layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'PT. Metinca Prima Industrial Works')

{{-- Untuk menggunakan css --}}
@push('styles')
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS%.0LyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjMzIgogICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzQiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJwcm9kdWNlZCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWZmaW5pdHkgRGVzaWduZXIgMS4xMC4xIgogICAgICBzdEV2dDp3aGVuPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLolwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMpDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC" type="image/png">
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
            <i class="bi bi-file-earmark-text-fill"></i> Quotations
        </h5>
    </div>

<section class="section">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card-body py-0">
        <form class="mb-3" method="GET" action="{{ route('quotations.index') }}">
            <div class="row g-2 align-items-end mt-0">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="d-flex align-items-center gap-1">
                        <label class="form-label small mb-0 text-nowrap">From : </label>
                        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $filters['start_date'] ?? '' }}" placeholder="From">
                    </div>
                </div>
        
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="d-flex align-items-center gap-1">
                        <label class="form-label small mb-0 text-nowrap">To : </label>
                        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $filters['end_date'] ?? '' }}" placeholder="To">                      
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-2">
                    <div class="d-flex align-items-center gap-1">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            <option value="created" {{ (isset($filters['status']) && $filters['status']=='created') ? 'selected' : '' }}>Created</option>
                            <option value="sent" {{ (isset($filters['status']) && $filters['status']=='sent') ? 'selected' : '' }}>Sent</option>
                            <option value="negotiating" {{ (isset($filters['status']) && $filters['status']=='negotiating') ? 'selected' : '' }}>Negotiating</option>
                            <option value="accepted" {{ (isset($filters['status']) && $filters['status']=='accepted') ? 'selected' : '' }}>Accepted</option>
                            <option value="po" {{ (isset($filters['status']) && $filters['status']=='po') ? 'selected' : '' }}>PO</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-4 d-flex flex-wrap gap-1">
                    <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
                    <button type="submit" formaction="{{ route('quotations.export') }}" class="btn btn-success btn-sm">Export</button>
                    @if(!auth()->user()->isCustomer())
                        <a href="{{ route('quotations.create') }}" class="btn btn-primary btn-sm"> Add Quotation</a>
                    @endif  
                </div>
            </div>
        </form>

        <div class="table-responsive">
        <table class="table table-hover align-middle" id="table1">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th class="text-center">Req ID</th>
                    <th>No Quotation</th>
                    <th>Customer / Perusahaan</th>
                    <th class="text-center">Tgl Quotation</th>
                    <th class="text-center">Tgl Expired</th>
                    <th class="text-center">Sales PIC</th>
                    <th class="text-center" width="18%">Aksi</th>
                    <th class="text-center" width="12%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quotations as $quotation)
                    <tr>
                        <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                        <td class="text-center">
                            <span class="badge bg-light-primary text-primary fw-bold">{{ $quotation->request_id ?? '-' }}</span>
                        </td>
                        <td class="fw-bold text-primary">{{ $quotation->quotation_no }}</td>
                        <td>
                            <strong>{{ $quotation->customer->company ?? $quotation->customer->name ?? '-' }}</strong>
                            @if(!empty($quotation->customer->name) && !empty($quotation->customer->company))
                                <br><small class="text-muted"><i class="bi bi-person me-1"></i>{{ $quotation->customer->name }}</small>
                            @endif
                        </td>
                        <td class="text-center small">{{ \Carbon\Carbon::parse($quotation->created_at)->format('d M Y') }}</td>
                        <td class="text-center small">
                            {{ $quotation->date_expired ? \Carbon\Carbon::parse($quotation->date_expired)->format('d M Y') : '-' }}
                        </td>
                        <td class="text-center">
                            @if($quotation->request && $quotation->request->assignment)
                                <span class="badge bg-success">
                                    {{ $quotation->request->assignment->sales->name ?? 'N/A' }}
                                </span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif    
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-1">
                                {{-- TOMBOL UTAMA DETAIL UNTUK MEMBUKA HALAMAN LENGKAP QUOTATION & DATA PO --}}
                                <a href="{{ route('quotations.show', $quotation->id) }}" 
                                   class="btn btn-sm btn-info text-white fw-bold px-3 py-1" 
                                   title="Buka Detail Lengkap Quotation & Data PO Terhubung">
                                    <i class="bi bi-eye-fill me-1"></i> Detail
                                </a>

                                {{-- TOMBOL KIRIM KETIKA STATUS MASIH CAN SEND --}}
                                @if($quotation->canSend() && (auth()->user()->isAdmin() || (auth()->user()->isStaff() && auth()->user()->divisi == 'sales')))
                                    <form method="POST" action="{{ route('quotations.send', $quotation->id) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" onclick="return confirm('Apakah anda yakin ingin mengirim quotation ini ke Customer?')" class="btn btn-sm btn-primary px-2 py-1" title="Kirim Ke Customer">
                                            <i class="bi bi-send-fill"></i> Kirim
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                        <td class="text-center">
                            @php $lastNego = $quotation->negotiates?->first(); @endphp

                            @if($quotation->status == 'created')
                                <span class="badge bg-secondary">Created</span>
                                <br><small class="text-muted" style="font-size:10px">
                                    {{ \Carbon\Carbon::parse($quotation->created_at)->format('d M Y') }}
                                </small>

                            @elseif($quotation->status == 'sent')
                                <span class="badge bg-info text-dark">Sent</span>
                                <br><small class="text-muted" style="font-size:10px">
                                    {{ \Carbon\Carbon::parse($quotation->updated_at)->format('d M Y') }}
                                </small>

                            @elseif($quotation->status == 'negotiating')
                                <span class="badge bg-warning text-dark">Negotiating</span>
                                @if($lastNego)
                                    <br><small class="text-muted" style="font-size:10px">
                                        {{ $lastNego->created_at->format('d M Y') }}
                                    </small>
                                @endif

                            @elseif($quotation->status == 'accepted')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle-fill me-1"></i>Accepted
                                </span>
                                <br><small class="text-muted" style="font-size:10px">
                                    {{ $quotation->accepted_date ? \Carbon\Carbon::parse($quotation->accepted_date)->format('d M Y') : '-' }}
                                </small>

                            @elseif($quotation->status == 'po')
                                <span class="badge bg-primary">PO</span>

                            @else
                                <span class="badge bg-secondary">{{ $quotation->status }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">Belum ada data quotation.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</section>

@push('scripts')
    <script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector("#table1")) {
                let dataTable = new simpleDatatables.DataTable("#table1");
            }
        });
    </script>
@endpush
@endsection