{{-- Inlcude layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'PT. Metinca Prima Industrial Works')

{{-- Untuk menggunakan css --}}
@push('styles')
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnHub3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzMiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZVxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJwcm9kdWNlZCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWZmaW5pdHkgRGVzaWduZXIgMS4xMC4xIgogICAgICBzdEV2dDp3aGVuPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLorwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMpDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC" type="image/png">
    <link rel="stylesheet" href="assets/extensions/simple-datatables/style.css">
    <link rel="stylesheet" href="./assets/compiled/css/table-datatable.css">
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
@endpush

{{-- Isi content --}}
@section('content')
   <div class="card detail-card">
        <div class="card-header py-3 bg-primary text-white">
            <h5 class="card-title mb-0">
                <i class="bi bi-send-plus-fill"></i> Purchase Order
            </h5>
        </div>
    <section class="content">
            <div class="card-body">
                <form class="row g-2 align-items-center mb-3" method="GET" action="{{ route('purchase-orders.index') }}">
                    <div class="col-auto">
                        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $filters['start_date'] ?? '' }}" placeholder="From">
                    </div>
                    <div class="col-auto">
                        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $filters['end_date'] ?? '' }}" placeholder="To">
                    </div>
                    <div class="col-auto">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            <option value="sent" {{ (isset($filters['status']) && $filters['status']=='sent') ? 'selected' : '' }}>Sent</option>
                            <option value="amandement" {{ (isset($filters['status']) && $filters['status']=='amandement') ? 'selected' : '' }}>Amandement</option>
                            <option value="review" {{ (isset($filters['status']) && $filters['status']=='review') ? 'selected' : '' }}>Review</option>
                            <option value="contract" {{ (isset($filters['status']) && $filters['status']=='contract') ? 'selected' : '' }}>Contract</option>
                            <option value="production" {{ (isset($filters['status']) && $filters['status']=='production') ? 'selected' : '' }}>Production</option>
                            <option value="ship" {{ (isset($filters['status']) && $filters['status']=='ship') ? 'selected' : '' }}>Ship</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        <button type="submit" formaction="{{ route('purchase-orders.export') }}" class="btn btn-sm btn-success">Export</button>
                    </div>
                </form>
                <table class="table table-hover text-nowrap" id="table1">
                    <thead>
                        <tr>
                            <th><center>No</center></th>
                            <th><center>Req Id</center></th>
                            <th><center>Quotation No</center></th>
                            <th><center>PO No</center></th>
                            <th><center>Delivery Date</center></th>
                            <th><center>Sales PIC</center></th>
                            <th><center>Status</center></th>
                            <th><center>Action</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pos as $po)
                            <tr>
                                <td><center>{{ $loop->iteration }}</center></td>
                                <td><center><span class="badge badge-sm bg-light">{{ $po -> id }}</span></center></td>
                                <td><center>{{ $po->quotation->quotation_no }}</center></td>
                                <td><center>{{ $po->po_no }}</center></td>
                                <td><center>{{ \Carbon\Carbon::parse($po->delivery_request)->format('d-m-Y') }}</center></td>
                                <td><center>
                                    @if($po->quotation && $po->quotation->request && $po->quotation->request->assignment)
                                        <span class="badge badge-sm bg-success">
                                            {{ $po->quotation->request->assignment->sales->name ?? '-' }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </center></td>
                                <td>
                                    <center>
                                        @if($po->status == 'sent')
                                            <span class="badge bg-light-primary text-primary">Sent (Antrean)</span>
                                        @elseif($po->status == 'review')
                                            <span class="badge bg-light-warning text-warning">Review (4 Divisi)</span>
                                        @elseif($po->status == 'amandement')
                                            <span class="badge bg-light-danger text-danger">Amandemen</span>
                                        @elseif($po->status == 'contract')
                                            <span class="badge bg-light-info text-info">Contract (Selesai Admin)</span>
                                        @elseif($po->status == 'production')
                                            <span class="badge bg-light-success text-success">In Production (Pabrik)</span>
                                        @elseif($po->status == 'ship')
                                            <span class="badge bg-success text-white">Shipped (Dikirim)</span>
                                        @else
                                            <span class="badge bg-light text-secondary">{{ $po->status }}</span>
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{-- 1. TOMBOL DETAIL & TRACKING (UNIVERSAL) --}}
                                        <button type="button" class="btn btn-sm btn-info text-white btn-show mb-1"
                                            data-id="{{ $po->id }}" data-bs-toggle="modal"
                                            data-bs-target="#previewModal">
                                            <i class="bi bi-file-earmark-text-fill"></i> Detail
                                        </button>

                                       {{-- 2. HAK AKSES AKTOR: ADMIN ATAU STAFF SALES --}}
@if (auth()->user()->isAdmin() || (auth()->user()->isStaff() && auth()->user()->divisi == 'sales'))
    
    {{-- Jika status Amandemen ATAU PO Baru yang belum masuk internal sama sekali --}}
    @if($po->status == 'amandement' || ($po->status == 'sent' && $po->internals->count() == 0))
        <a href="{{ route('purchase-orders-internal.create', $po->id) }}"
            class="btn btn-sm btn-primary mb-1" 
            data-bs-toggle="tooltip" title="Proses Masuk ke Sistem Internal">
            <i class="bi bi-gear-fill"></i> Proses Internal
        </a>
    @else
        {{-- Sisanya (Review, Contract, atau Sent yang sudah masuk internal), matikan tombol --}}
        <button class="btn btn-sm btn-secondary mb-1" disabled>
            <i class="bi bi-check-circle-fill"></i> Sudah Diproses
        </button>
    @endif
    
@endif
                                        
                                        {{-- 3. HAK AKSES AKTOR: CUSTOMER --}}
                                        @if (auth()->user()->isCustomer())
                                            @if (in_array($po->status, ['review', 'contract']))
                                                <a href="{{ route('purchase-orders.create-amandement', $po->id) }}"
                                                    class="btn btn-sm btn-warning text-dark font-weight-bold mb-1" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Ajukan Amandemen">
                                                    <i class="bi bi-pencil-square"></i> Amandemen
                                                </a>
                                            @else
                                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Amandemen Belum Tersedia / Terkunci" class="d-inline-block mb-1">
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        <i class="bi bi-lock-fill"></i> Locked
                                                    </button>
                                                </span>
                                            @endif

                                            {{-- TEMPAT TOMBOL RIWAYAT UNTUK CUSTOMER --}}
                                            @if($po->status == 'amandement' || $po->contracts->count() > 1)
                                                <button type="button" class="btn btn-sm btn-dark text-white mb-1" data-bs-toggle="modal" data-bs-target="#historyModal{{ $po->id }}">
                                                    <i class="bi bi-clock-history"></i> Riwayat
                                                </button>
                                            @endif
                                        @endif
                                    </center>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8"><center>No data available</center></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="modal fade" id="previewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="modalContent">

                </div>
            </div>
        </div>
        
        <div class="modal fade" id="updateStatusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="editModalContent">

                </div>
            </div>
        </div>

        {{-- TEMPAT KUMPULAN TRACKING MODAL AMANDEMEN --}}
        @foreach($pos as $po)
            <div class="modal fade" id="historyModal{{ $po->id }}" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title" id="historyModalLabel"><i class="bi bi-clock-history me-2"></i>Tracking History Amandemen - {{ $po->po_no }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle">
                                    <thead class="table-secondary text-center">
                                        <tr>
                                            <th width="15%">Amandemen</th>
                                            <th width="45%">Alasan / Catatan Perubahan</th>
                                            <th width="20%">Tanggal Diajukan</th>
                                            <th width="20%">Status Review</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($po->contracts as $history)
                                            <tr>
                                                <td class="text-center fw-bold text-primary">
                                                    {{ $history->amandement_no == 0 ? '0 (Asli)' : 'Ke-' . $history->amandement_no }}
                                                </td>
                                                <td>{{ $history->alasan_amandemen ?? 'Pembuatan kontrak review pertama oleh Sales.' }}</td>
                                                <td class="text-center small">{{ $history->created_at->format('d-m-Y H:i') }}</td>
                                                <td class="text-center">
                                                    @if($history->status == 'created')
                                                        <span class="badge bg-warning text-dark">Waiting Approval</span>
                                                    @elseif($history->status == 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($history->status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Belum ada data amandemen kontrak yang tercatat.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/simple-datatables.js') }}"></script>
    <script>
        const showBtns = document.querySelectorAll('.btn-show');
        const modalShowContent = document.getElementById('modalContent');

        showBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;

                modalShowContent.innerHTML = `
            <div class="modal-body text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;

                fetch(`/purchase-orders/${id}`)
                    .then(response => response.text())
                    .then(html => {
                        modalShowContent.innerHTML = html;
                    })
                    .catch(error => {
                        modalShowContent.innerHTML = `
                    <div class="modal-body text-danger text-center">
                        Gagal memuat data
                    </div>
                `;
                        console.error(error);
                    });
            });
        });

        const editBtn = document.querySelectorAll('.btn-edit');
        const editModalContent = document.getElementById('editModalContent');

        editBtn.forEach(btn => {
            btn.addEventListener('click',()=>{
                const id = btn.dataset.id;

                editModalContent.innerHTML = `
                <div class="modal-body text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                `;

                fetch(`/purchase-orders/${id}/edit`)
                    .then(response => response.text())
                    .then(html => {
                        editModalContent.innerHTML = html;
                    })
                    .catch(error => {
                        editModalContent.innerHTML = `
                    <div class="modal-body text-danger text-center">
                        Gagal memuat data
                    </div>
                `;
                    })
            });
        });
        
        document.addEventListener('DOMContentLoaded', function () {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        });
    </script>
@endpush