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
@endpush

{{-- Isi content --}}
@section('content')
    <div class="card detail-card">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-send-plus-fill"></i> PO Schedule
            </h5>
        </div>
    <section class="content">
        {{-- <div class="card"> --}}
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    {{-- <h5>PO List</h5> --}}
                    <form action="{{ route('po-schedule.arrange') }}" method="post">
                        @csrf
                        @method('PUT')
                        <button type="submit" onclick="confirm('apakah anda yakin re-arrange schedule?')" class="btn btn-sm btn-primary"><i class="bi bi-list"></i>Arrange Schedule</button>
                    </form>
                </div>
                <table class="table table-hover" id=table1>
                    <thead>
                        <tr>
                            <th><center>No</center></th>
                            <th><center>Quotation No</center></th>
                            <th><center>PO No</center></th>
                            <th><center>Attachments</center></th>
                            <th><center>Delivery Date</center></th>
                            <th><center>Status</center></th>
                            <th><center>Status Order</center></th>
                            <th><center>Action</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pos as $po)
                            <tr>
                                <td>
                                    <center>{{ $po->id }}</center>
                                </td>
                                <td>
                                    <center>{{ $po->quotation->quotation_no }}</center>
                                </td>
                                <td>
                                    <center>{{ $po->po_no }}</center>
                                </td>
                                <td>
                                    <center><a href="{{ asset('storage/uploads/' . $po->attachment) }}" target="_blank"
                                            class="btn btn-sm btn-info">File</a></center>
                                </td>
                                <td>
                                    <center>{{ \Carbon\Carbon::parse($po->delivery_request)->format('d-m-Y') }}</center>
                                    <span class="text-sm rounded bg-info text-white">{{ \Carbon\Carbon::parse($po->delivery_request)->diffForHumans() }}</span>
                                </td>
                                <td>
                                    <center>{{ $po->status }}</center>
                                </td>
                                <td>
                                    <center>{{ $po->status_order }}</center>
                                </td>
                                <td>
                                    <center>
                                        <span data-bs-toggle="tooltip" data-bs-placement="left" title="Detail">
                                            <button type="button" class="btn icon btn-sm btn-info btn-show"
                                                data-id="{{ $po->id }}" data-bs-toggle="modal"
                                                data-bs-target="#previewModal"><i class="bi bi-eye"></i>
                                            </button>
                                        </span>
                                        @if(auth()->user()->isManager() && auth()->user()->divisi == 'sales' && $po->status == 'contract')
                                        <span data-bs-toggle="tooltip" data-bs-placement="right" title="Edit">
                                            <button type="button" class="btn icon btn-sm btn-warning btn-edit"
                                                data-id="{{ $po->id }}" data-bs-toggle="modal"
                                                data-bs-target="#updateStatusModal"><i class="bi bi-pencil-square"></i>
                                            </button>
                                        </span>
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center></center>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6">Belum ada data PO</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        {{-- </div> --}}
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
    </section>
@endsection
@push('scripts')
    <script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/simple-datatables.js') }}"></script>
    <script>
        function injectModalHtml(container, html) {
            container.innerHTML = html;
            container.querySelectorAll('script').forEach(oldScript => {
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                oldScript.parentNode.replaceChild(newScript, oldScript);
            });
            container.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
        }

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-show');
            if (!btn) return;
            const modalShowContent = document.getElementById('modalContent');
            if (!modalShowContent) return;
            const id = btn.dataset.id;
            const internalId = btn.dataset.internalId;

            modalShowContent.innerHTML = `
                <div class="modal-body text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;

            let fetchUrl = `/purchase-orders/${id}`;
            if (internalId) {
                fetchUrl += `?internal_id=${internalId}`;
            }

            fetch(fetchUrl)
                .then(response => {
                    if (!response.ok) throw new Error('Failed loading');
                    return response.text();
                })
                .then(html => {
                    injectModalHtml(modalShowContent, html);
                })
                .catch(error => {
                    modalShowContent.innerHTML = `
                        <div class="modal-body text-danger text-center py-4">
                            Gagal memuat data
                        </div>
                    `;
                    console.error(error);
                });
        });

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-edit');
            if (!btn) return;
            const editModalContent = document.getElementById('editModalContent');
            if (!editModalContent) return;
            const id = btn.dataset.id;

            editModalContent.innerHTML = `
                <div class="modal-body text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;

            fetch(`/purchase-orders/${id}/edit`)
                .then(response => {
                    if (!response.ok) throw new Error('Failed loading edit');
                    return response.text();
                })
                .then(html => {
                    injectModalHtml(editModalContent, html);
                })
                .catch(error => {
                    editModalContent.innerHTML = `
                        <div class="modal-body text-danger text-center py-4">
                            Gagal memuat data
                        </div>
                    `;
                });
        });
    </script>
@endpush
