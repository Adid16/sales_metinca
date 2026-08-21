@extends('layouts.app')

@section('title', 'PT. Metinca Prima Industrial Works')

@push('styles')
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnHub3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzMiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZVxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJwcm9kdWNlZCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWZmaW5pdHkgRGVzaWduZXIgMS4xMC4xIgogICAgICBzdEV2dDp3aGVuPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLEhj0SnqslRPSEyKnpCRYdT/p/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFolAACwBh3WGLAhbgCRIYYinwLorwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAvg3o0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC" type="image/png">
    <link rel="stylesheet" href="assets/extensions/simple-datatables/style.css">
    <link rel="stylesheet" href="./assets/compiled/css/table-datatable.css">
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
@endpush

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

                <table class="table table-hover align-middle text-nowrap" id="poTable">
                    <thead>
                        <tr>
                            <th><center>No</center></th>
                            <th><center>Req Id</center></th>
                            <th><center>Quotation No</center></th>
                            <th><center>PO No</center></th>
                            <th><center>Part / Item Name</center></th>
                            <th><center>Delivery Date</center></th>
                            <th><center>Sales PIC</center></th>
                            <th><center>Status</center></th>
                            <th><center>Action</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pos as $po)
                            @php
                                $poStatus       = strtolower($po->status);
                                $internalsCount = $po->internals ? $po->internals->count() : 0;
                                $quotationCount = ($po->quotation && $po->quotation->items) ? $po->quotation->items->count() : 0;
                                
                                // Total item sebenarnya dari transaksi PO ini
                                $totalItemCount   = max($internalsCount, $quotationCount, 1);
                                $isMultiItem      = $totalItemCount > 1;
                                $isFullyProcessed = ($internalsCount >= $totalItemCount) && ($totalItemCount > 0);

                                $firstItemName = '-';
                                if ($po->internals && $po->internals->count() > 0) {
                                    $firstItemName = $po->internals->first()->item ?? '-';
                                } elseif ($po->quotation && $po->quotation->items->count() > 0) {
                                    $firstItemName = $po->quotation->items->first()->item ?? '-';
                                }
                            @endphp
                            
                            {{-- BARIS UTAMA (1 BARIS PER MASTER PO) --}}
                            <tr class="table-group-divider">
                                <td><center>{{ $loop->iteration }}</center></td>
                                <td><center><span class="badge badge-sm bg-light text-dark">#{{ $po->id }}</span></center></td>
                                <td><center>{{ $po->quotation->quotation_no ?? '-' }}</center></td>
                                <td><center><span class="fw-bold text-primary">{{ $po->po_no }}</span></center></td>
                                <td>
                                    <center>
                                        @if($isMultiItem)
                                            <span class="badge bg-light-primary text-primary fw-bold">
                                                <i class="bi bi-boxes me-1"></i>{{ $totalItemCount }} Item(s)
                                            </span>
                                            @if($internalsCount > 0)
                                                <br><small class="text-success fw-semibold">({{ $internalsCount }}/{{ $totalItemCount }} Diproses)</small>
                                            @endif
                                        @else
                                            <span class="fw-bold text-dark">{{ $firstItemName }}</span>
                                        @endif
                                    </center>
                                </td>
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
                                        @if($poStatus == 'amandement_pending')
                                            <span class="badge bg-light-danger text-danger fw-bold"><i class="bi bi-hourglass-split me-1"></i>Amandemen Pending</span>
                                        @elseif($poStatus == 'amandement')
                                            <span class="badge bg-danger text-white fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i>Amandemen</span>
                                        @elseif(in_array($poStatus, ['contract', 'approved']))
                                            <span class="badge bg-light-info text-info">Contract</span>
                                        @elseif($poStatus == 'production')
                                            <span class="badge bg-light-success text-success">In Production</span>
                                        @elseif($poStatus == 'ship')
                                            <span class="badge bg-success text-white">Shipped</span>
                                        @else
                                            <span class="badge bg-light-primary text-primary">{{ ucfirst($po->status) }}</span>
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{-- 1. BUTTON DETAIL PO MASTER --}}
                                        <button type="button" class="btn btn-sm btn-info text-white btn-show mb-1"
                                            data-id="{{ $po->id }}" data-bs-toggle="modal"
                                            data-bs-target="#previewModal">
                                            <i class="bi bi-file-earmark-text-fill"></i> Detail
                                        </button>

                                        {{-- 2. AKSI UNTUK CUSTOMER (PO 1 ITEM LANGSUNG PROSES / STATUS AMANDEMEN DI SINI) --}}
                                        @if(auth()->user()->role == 'customer')
                                            @if(!$isMultiItem)
                                                @php
                                                    $firstInternal = $po->internals->first();
                                                    $masterContract = $firstInternal ? ($firstInternal->contract ?? ($firstInternal->contracts ? $firstInternal->contracts->last() : null)) : null;
                                                    if (!$masterContract && $po->contracts) {
                                                        $masterContract = $po->contracts->where('status', 'rejected')->first() 
                                                            ?? $po->contracts->whereNotNull('alasan_penolakan')->first()
                                                            ?? $po->contracts->last();
                                                    }

                                                    $masterRawStatus  = strtolower($masterContract ? $masterContract->status : '');
                                                    $masterIsRejected = ($masterRawStatus == 'rejected') || ($masterContract && !empty($masterContract->alasan_penolakan) && $poStatus != 'amandement_pending');
                                                    $masterIsPending  = ($poStatus == 'amandement_pending' || $masterRawStatus == 'amandement_pending');
                                                    $masterIsApproved = ($poStatus == 'amandement' || $masterRawStatus == 'amandement');
                                                @endphp

                                                @if($masterIsRejected)
                                                    {{-- BILA DITOLAK: TOMBOL BUKA MODAL DETAIL ALASAN PENOLAKAN --}}
                                                    <button type="button" class="btn btn-sm btn-danger text-white fw-bold mb-1"
                                                            data-bs-toggle="modal" data-bs-target="#rejectReasonMasterModal{{ $po->id }}">
                                                        <i class="bi bi-x-circle me-1"></i> Ditolak (Lihat Alasan)
                                                    </button>
                                                @elseif($masterIsPending)
                                                    <button class="btn btn-sm btn-secondary mb-1 fw-semibold" disabled title="Pengajuan amandemen sedang dalam peninjauan Sales & Manager">
                                                        <i class="bi bi-clock-history me-1"></i> Pending Review
                                                    </button>
                                                @elseif($masterIsApproved)
                                                    <button class="btn btn-sm btn-success text-white mb-1 fw-semibold" disabled title="Amandemen telah disetujui dan sedang diproses Sales">
                                                        <i class="bi bi-check-circle me-1"></i> Disetujui
                                                    </button>
                                                @else
                                                    <a href="{{ route('purchase-orders.create-amandement', ['id' => $po->id, 'internal_id' => $firstInternal->id ?? null]) }}"
                                                       class="btn btn-sm btn-warning mb-1 text-dark fw-semibold"
                                                       data-bs-toggle="tooltip" title="Ajukan Amandemen Perubahan PO">
                                                        <i class="bi bi-pencil-square me-1"></i> Ajukan Amandemen
                                                    </a>
                                                @endif
                                            @endif
                                        @endif

                                        {{-- 3. AKSI UNTUK SALES / ADMIN (PO 1 ITEM) --}}
                                        @if(!$isMultiItem)
                                            @if (auth()->user()->isAdmin() || (auth()->user()->isStaff() && auth()->user()->divisi == 'sales'))
                                                @if($poStatus == 'amandement_pending' || ($masterContract && $masterContract->status == 'amandement_pending'))
                                                    <button class="btn btn-sm btn-secondary mb-1 fw-semibold" disabled title="PO / Kontrak sedang dalam proses pengajuan amandemen oleh Customer. Harap selesaikan review di menu PO Amandement.">
                                                        <i class="bi bi-clock-history me-1 text-warning"></i> Amandemen Pending
                                                    </button>
                                                @elseif(in_array($poStatus, ['amandement']) || ($masterContract && $masterContract->status == 'amandement'))
                                                    <a href="{{ route('purchase-orders-internal.create', ['purchaseOrder' => $po->id, 'internal_id' => $firstInternal->id ?? null]) }}"
                                                        class="btn btn-sm btn-warning text-dark fw-bold mb-1" 
                                                        data-bs-toggle="tooltip" title="Amandemen disetujui. Klik untuk memproses ulang ke Sistem Internal">
                                                        <i class="bi bi-arrow-repeat me-1"></i> Proses Internal (Amandemen)
                                                    </a>
                                                @elseif(!$isFullyProcessed && in_array($poStatus, ['sent', 'created']))
                                                    <a href="{{ route('purchase-orders-internal.create', ['purchaseOrder' => $po->id]) }}"
                                                        class="btn btn-sm btn-primary mb-1" 
                                                        data-bs-toggle="tooltip" title="Proses Masuk ke Sistem Internal">
                                                        <i class="bi bi-gear-fill me-1"></i> Proses Internal
                                                    </a>
                                                @else
                                                    <button class="btn btn-sm btn-secondary mb-1" disabled>
                                                        <i class="bi bi-check-circle-fill me-1"></i> Sudah Diproses
                                                    </button>
                                                @endif
                                            @endif
                                        @else
                                            {{-- 4. AKSI UNTUK PO MULTI-ITEM (PAKAI DROPDOWN RINCIAN ITEM) --}}
                                            <button class="btn btn-sm btn-outline-primary mb-1" type="button" 
                                                    data-bs-toggle="collapse" data-bs-target="#collapsePoItems{{ $po->id }}" 
                                                    aria-expanded="false">
                                                <i class="bi bi-chevron-down me-1"></i> Rincian Item ({{ $totalItemCount }})
                                            </button>
                                        @endif
                                    </center>
                                </td>
                            </tr>

                            {{-- SUB-BARIS DROPDOWN HANYA DITAMPILKAN JIKA PO MULTI-ITEM (2+ ITEM) --}}
                            @if($isMultiItem)
                                <tr class="collapse border-0 bg-light" id="collapsePoItems{{ $po->id }}">
                                    <td colspan="9" class="p-3">
                                        <div class="card shadow-sm border mb-0">
                                            <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                                                <strong class="text-dark small"><i class="bi bi-list-nested me-1 text-primary"></i> RINCIAN ITEM UNTUK PO: {{ $po->po_no }}</strong>
                                                <small class="text-muted">Status: {{ $internalsCount }}/{{ $totalItemCount }} Item Diproses</small>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered align-middle mb-0 small">
                                                        <thead class="table-secondary text-secondary">
                                                            <tr>
                                                                <th class="text-center" width="40px">#</th>
                                                                <th class="text-center" width="160px">Sub-PO / ID Item</th>
                                                                <th>Nama Item / Part Name</th>
                                                                <th class="text-center" width="100px">Qty</th>
                                                                <th class="text-center" width="130px">Harga Satuan</th>
                                                                <th class="text-center" width="120px">Status Item</th>
                                                                <th class="text-center" width="220px">Aksi Item</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $quotationItems = ($po->quotation && $po->quotation->items->count() > 0) ? $po->quotation->items : collect();
                                                                $internalsItems = $po->internals ?? collect();

                                                                // Jika Sales menambah item baru di PO Internal sehingga internals > quotation
                                                                $displayItems = ($internalsItems->count() > $quotationItems->count()) ? $internalsItems : $quotationItems;
                                                            @endphp
                                                            @foreach($displayItems as $subIdx => $qItem)
                                                                @php
                                                                    $qItemId = $qItem->id ?? null;
                                                                    $targetPoNo = $po->po_no . '-' . ($subIdx + 1);

                                                                    // 1. Match spesifik berdasarkan Sub-PO No (PO-xxxx-1, PO-xxxx-2)
                                                                    $internalItem = $po->internals ? $po->internals->firstWhere('po_no', $targetPoNo) : null;

                                                                    // 2. Fallback match berdasarkan urutan slot (index ke-N) jika po_no cocok/default
                                                                    if (!$internalItem && $po->internals && $po->internals->values()->has($subIdx)) {
                                                                        $candidate = $po->internals->values()->get($subIdx);
                                                                        if ($candidate && ($candidate->po_no === $targetPoNo || !$candidate->po_no || $candidate->po_no === $po->po_no)) {
                                                                            $internalItem = $candidate;
                                                                        }
                                                                    }

                                                                    // 3. Fallback pencocokan berdasarkan nama item jika belum ditemukan
                                                                    if (!$internalItem && $po->internals && isset($qItem->item)) {
                                                                        $internalItem = $po->internals->first(function($i) use ($qItem) {
                                                                            return strtolower(trim($i->item)) === strtolower(trim($qItem->item));
                                                                        });
                                                                    }

                                                                    $itemContract  = $internalItem ? ($internalItem->contract ?? ($internalItem->contracts ? $internalItem->contracts->last() : null)) : null;
                                                                    if (!$itemContract && $po->contracts) {
                                                                        $itemContract = $po->contracts->first(function($c) use ($qItem, $targetPoNo) {
                                                                            return ($c->order_no === $targetPoNo) || (isset($c->part_name) && isset($qItem->item) && strtolower(trim($c->part_name)) === strtolower(trim($qItem->item)));
                                                                        });
                                                                    }

                                                                    $itemRawStatus = strtolower($itemContract ? $itemContract->status : ($internalItem ? 'created' : 'belum_diproses'));
                                                                    $isPendingAmandement = ($itemRawStatus == 'amandement_pending');
                                                                    $isApprovedAmandement = ($itemRawStatus == 'amandement' || $itemRawStatus == 'amandement_approved');
                                                                    $isRejectedAmandement = ($itemRawStatus == 'rejected');
                                                                @endphp
                                                                <tr>
                                                                    <td class="text-center fw-bold">{{ $subIdx + 1 }}</td>
                                                                    <td class="text-center">
                                                                        <span class="badge {{ $internalItem ? 'bg-dark' : 'bg-secondary' }}">
                                                                            {{ $internalItem->po_no ?? $targetPoNo }}
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <strong class="text-dark">{{ $internalItem->item ?? $qItem->item ?? '-' }}</strong>
                                                                        @if($internalItem && ($internalItem->part_no || $internalItem->article_no))
                                                                            <br><small class="text-muted">Part No: {{ $internalItem->part_no ?? $internalItem->article_no }}</small>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center fw-bold">{{ number_format($internalItem->qty ?? $qItem->qty ?? 1) }} pcs</td>
                                                                    <td class="text-center">Rp {{ number_format($internalItem->unit_price ?? $qItem->price ?? 0, 0, ',', '.') }}</td>
                                                                    <td class="text-center">
                                                                        @if(in_array($itemRawStatus, ['contract', 'approved']))
                                                                            <span class="badge bg-light-info text-info"><i class="bi bi-file-earmark-check me-1"></i>Contract</span>
                                                                        @elseif($itemRawStatus == 'review')
                                                                            <span class="badge bg-light-warning text-warning"><i class="bi bi-search me-1"></i>Review</span>
                                                                        @elseif($itemRawStatus == 'production')
                                                                            <span class="badge bg-light-success text-success"><i class="bi bi-gear-wide-connected me-1"></i>In Production</span>
                                                                        @elseif($itemRawStatus == 'ship')
                                                                            <span class="badge bg-success text-white"><i class="bi bi-truck me-1"></i>Shipped</span>
                                                                        @elseif(in_array($itemRawStatus, ['created', 'sent']))
                                                                            <span class="badge bg-light-primary text-primary"><i class="bi bi-check-circle me-1"></i>Created</span>
                                                                        @else
                                                                            <span class="badge bg-light text-secondary">Belum Diproses</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                         @if (auth()->user()->isAdmin() || (auth()->user()->isStaff() && auth()->user()->divisi == 'sales'))
                                                                             @if($isPendingAmandement)
                                                                                 {{-- BILA SEDANG DIAMANDEMEN: TOMBOL PROSES INTERNAL / KONTRAK DISABLED UNTUK SALES --}}
                                                                                 <button class="btn btn-xs btn-secondary me-1 mb-1 fw-semibold" disabled title="Item ini sedang dalam proses pengajuan amandemen oleh Customer. Harap selesaikan review di menu PO Amandement.">
                                                                                     <i class="bi bi-clock-history me-1 text-warning"></i> Amandemen Pending
                                                                                 </button>
                                                                                 <button type="button" class="btn btn-xs btn-info text-white btn-show me-1 mb-1"
                                                                                     data-id="{{ $po->id }}" @if($internalItem) data-internal-id="{{ $internalItem->id }}" @endif data-bs-toggle="modal"
                                                                                     data-bs-target="#previewModal">
                                                                                     <i class="bi bi-eye"></i> Detail
                                                                                 </button>
                                                                             @elseif($itemRawStatus == 'amandement')
                                                                                {{-- BILA AMANDEMEN TELAH DI-ACC SALES: TOMBOL PROSES INTERNAL ULANG DIAKTIFKAN KEMBALI --}}
                                                                                <a href="{{ route('purchase-orders-internal.create', ['purchaseOrder' => $po->id, 'internal_id' => $internalItem->id ?? null, 'quotation_item_id' => $qItemId]) }}"
                                                                                   class="btn btn-xs btn-warning text-dark fw-bold mb-1 me-1"
                                                                                   data-bs-toggle="tooltip" title="Amandemen item telah disetujui. Klik untuk memproses ulang data amandemen ke PO Internal.">
                                                                                    <i class="bi bi-arrow-repeat me-1"></i> Proses Internal (Amandemen)
                                                                                </a>
                                                                                <button type="button" class="btn btn-xs btn-info text-white btn-show me-1 mb-1"
                                                                                    data-id="{{ $po->id }}" @if($internalItem) data-internal-id="{{ $internalItem->id }}" @endif data-bs-toggle="modal"
                                                                                    data-bs-target="#previewModal">
                                                                                    <i class="bi bi-eye"></i> Detail
                                                                                </button>
                                                                            @elseif($internalItem)
                                                                                <button class="btn btn-xs btn-secondary me-1 mb-1" disabled title="Item ini sudah diproses ke PO Internal">
                                                                                    <i class="bi bi-check-circle-fill me-1"></i> Sudah Diproses
                                                                                </button>
                                                                                <button type="button" class="btn btn-xs btn-info text-white btn-show me-1 mb-1"
                                                                                    data-id="{{ $po->id }}" data-internal-id="{{ $internalItem->id }}" data-bs-toggle="modal"
                                                                                    data-bs-target="#previewModal">
                                                                                    <i class="bi bi-eye"></i> Detail
                                                                                </button>
                                                                                <a href="{{ route('purchase-orders-internal.create', ['purchaseOrder' => $po->id, 'internal_id' => $internalItem->id]) }}" 
                                                                                   class="btn btn-xs btn-outline-secondary mb-1">
                                                                                    <i class="bi bi-pencil"></i> Edit PO Int
                                                                                </a>
                                                                            @else
                                                                                <a href="{{ route('purchase-orders-internal.create', ['purchaseOrder' => $po->id, 'quotation_item_id' => $qItemId]) }}"
                                                                                   class="btn btn-xs btn-primary mb-1">
                                                                                    <i class="bi bi-gear-fill me-1"></i> Proses Internal
                                                                                </a>
                                                                            @endif
                                                                        @else
                                                                            {{-- AKSI UNTUK CUSTOMER DI DALAM RINCIAN ITEM DROPDOWN --}}
                                                                            <button type="button" class="btn btn-xs btn-info text-white btn-show me-1 mb-1"
                                                                                data-id="{{ $po->id }}" @if($internalItem) data-internal-id="{{ $internalItem->id }}" @endif data-bs-toggle="modal"
                                                                                data-bs-target="#previewModal">
                                                                                <i class="bi bi-eye"></i> Detail
                                                                            </button>

                                                                            @if($isRejectedAmandement)
                                                                                {{-- BILA DITOLAK: TAMPILKAN TOMBOL BUKA MODAL DETAIL ALASAN PENOLAKAN --}}
                                                                                <button type="button" class="btn btn-xs btn-danger text-white fw-bold mb-1 me-1"
                                                                                        data-bs-toggle="modal" data-bs-target="#rejectReasonModal{{ $po->id }}_{{ $subIdx }}">
                                                                                    <i class="bi bi-x-circle me-1"></i> Ditolak (Lihat Alasan)
                                                                                </button>
                                                                            @elseif($isPendingAmandement)
                                                                                {{-- BILA PENDING: TOMBOL DISABLED --}}
                                                                                <button class="btn btn-xs btn-secondary mb-1 fw-semibold" disabled title="Pengajuan amandemen sedang dalam peninjauan Sales & Manager">
                                                                                    <i class="bi bi-clock-history me-1"></i> Pending Review
                                                                                </button>
                                                                            @elseif($isApprovedAmandement)
                                                                                {{-- BILA APPROVED: TOMBOL DISABLED SEMENTARA DIPROSES SALES --}}
                                                                                <button class="btn btn-xs btn-success text-white mb-1 fw-semibold" disabled title="Amandemen telah disetujui dan sedang diproses Sales">
                                                                                    <i class="bi bi-check-circle me-1"></i> Disetujui
                                                                                </button>
                                                                            @elseif(!$internalItem || !$itemContract)
                                                                                {{-- OPSI A: BILA PO BARU DITERBITKAN & BELUM DIPROSES SALES --}}
                                                                                <button class="btn btn-xs btn-secondary mb-1 fw-semibold" disabled title="PO baru diterbitkan, menunggu Sales memproses PO Internal / Kontrak pertama kali">
                                                                                    <i class="bi bi-clock-history me-1"></i> Menunggu Review Sales
                                                                                </button>
                                                                            @else
                                                                                {{-- BILA KONTRAK AWAL SUDAH DIBUAT SALES: TOMBOL AJUKAN AMANDEMEN AKTIF --}}
                                                                                <a href="{{ route('purchase-orders.create-amandement', ['id' => $po->id, 'internal_id' => $internalItem->id ?? null]) }}"
                                                                                   class="btn btn-xs btn-warning text-dark mb-1 fw-semibold"
                                                                                   data-bs-toggle="tooltip" title="Ajukan Amandemen Khusus Item Ini">
                                                                                    <i class="bi bi-pencil-square me-1"></i> Ajukan Amandemen
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="modal fade" id="previewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="modalContent"></div>
            </div>
        </div>
        
        <div class="modal fade" id="updateStatusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="editModalContent"></div>
            </div>
        </div>

        {{-- MODAL TRACKING HISTORY AMANDEMEN SPESIFIK PER-ITEM --}}
        @foreach($pos as $po)
            @if($po->internals && $po->internals->count() > 0)
                @foreach($po->internals as $item)
                    <div class="modal fade" id="historyModalItem{{ $item->id }}" tabindex="-1" aria-labelledby="historyModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-dark text-white">
                                    <h5 class="modal-title" id="historyModalLabel{{ $item->id }}">
                                        <i class="bi bi-clock-history me-2"></i>Tracking History Amandemen - {{ $item->po_no ?? ($po->po_no . '-' . $item->id) }} ({{ $item->item }})
                                    </h5>
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
                                                @php
                                                    $itemHistories = $item->contracts ?? ($po->contracts ? $po->contracts->where('purchase_order_internal_id', $item->id) : []);
                                                @endphp

                                                @forelse($itemHistories as $history)
                                                    <tr>
                                                        <td class="text-center fw-bold text-primary">
                                                            {{ $history->amandement_no == 0 ? '0 (Asli)' : 'Ke-' . $history->amandement_no }}
                                                        </td>
                                                        <td>{{ $history->alasan_amandemen ?? 'Pembuatan kontrak review pertama oleh Sales.' }}</td>
                                                        <td class="text-center small">{{ $history->created_at ? $history->created_at->format('d-m-Y H:i') : '-' }}</td>
                                                        <td class="text-center">
                                                            @php $hStatus = strtolower($history->status); @endphp
                                                            @if(in_array($hStatus, ['created', 'amandement_pending', 'amandement', 'review']))
                                                                <span class="badge bg-warning text-dark">Waiting Approval</span>
                                                            @elseif(in_array($hStatus, ['approved', 'contract']))
                                                                <span class="badge bg-success">Approved</span>
                                                            @else
                                                                <span class="badge bg-secondary">{{ ucfirst($history->status) }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">Belum ada data amandemen kontrak yang tercatat untuk item ini.</td>
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
            @endif
        @endforeach

        {{-- MODAL DETAIL ALASAN PENOLAKAN AMANDEMEN (DITARUH DI LUAR TABEL AGAR TIDAK BENTROK DATATABLES DOM) --}}
        @if(auth()->check() && auth()->user()->role == 'customer')
            @foreach($pos as $po)
                @php
                    $quotationItems = ($po->quotation && $po->quotation->items->count() > 0) ? $po->quotation->items : collect();
                    $internalsItems = $po->internals ?? collect();
                    $displayItems = ($internalsItems->count() > $quotationItems->count()) ? $internalsItems : $quotationItems;

                    // Cari kontrak penolakan untuk master PO
                    $firstInternal = $po->internals->first();
                    $masterContract = $firstInternal ? ($firstInternal->contract ?? ($firstInternal->contracts ? $firstInternal->contracts->last() : null)) : null;
                    if (!$masterContract && $po->contracts) {
                        $masterContract = $po->contracts->whereNotNull('alasan_penolakan')->sortByDesc('updated_at')->first()
                            ?? $po->contracts->where('status', 'rejected')->first()
                            ?? $po->contracts->last();
                    }

                    $reasonTextMaster = $masterContract->alasan_penolakan ?? null;
                    if (empty($reasonTextMaster) && $po->contracts) {
                        $cWithReason = $po->contracts->firstWhere('alasan_penolakan', '!=', null);
                        if ($cWithReason) {
                            $reasonTextMaster = $cWithReason->alasan_penolakan;
                        }
                    }
                @endphp
                <div class="modal fade text-start" id="rejectReasonMasterModal{{ $po->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title text-white"><i class="bi bi-exclamation-triangle-fill me-2"></i> Penolakan Amandemen PO</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-dark">
                                <div class="mb-3">
                                    <small class="text-muted d-block fw-bold">Nomor Purchase Order (PO):</small>
                                    <span class="badge bg-dark fs-6">{{ $po->po_no }}</span>
                                </div>

                                @if($masterContract && !empty($masterContract->alasan_amandemen))
                                    <div class="alert alert-light border mb-3">
                                        <small class="text-muted d-block fw-bold mb-1"><i class="bi bi-chat-left-text me-1"></i> Alasan Amandemen Anda:</small>
                                        <span class="fst-italic text-dark">"{{ $masterContract->alasan_amandemen }}"</span>
                                    </div>
                                @endif

                                <div class="alert alert-danger border border-danger mb-3 shadow-sm">
                                    <h6 class="fw-bold text-danger mb-2"><i class="bi bi-x-octagon-fill me-1"></i> Alasan Penolakan dari Staff Sales / Manajemen:</h6>
                                    <div class="p-3 bg-white rounded border border-danger text-danger fw-bold fs-6">
                                        "{{ !empty($reasonTextMaster) ? $reasonTextMaster : 'Pengajuan amandemen tidak dapat disetujui oleh Staff Sales saat ini.' }}"
                                    </div>
                                </div>

                                <div class="p-3 bg-light rounded border mb-0">
                                    <p class="small text-dark mb-0">
                                        <i class="bi bi-info-circle-fill text-primary me-1"></i> <strong>Tujuan & Konfirmasi:</strong><br>
                                        Dengan mengeklik <strong>"Saya Mengerti (OK)"</strong>, Anda menyetujui/mengonfirmasi telah membaca alasan penolakan ini. Status penolakan item akan di-reset sehingga Anda dapat mengajukan amandemen baru bila diperlukan.
                                    </p>
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <form action="{{ route('purchase-orders.acknowledge-amandement', $po->id) }}" method="POST" id="ackMasterForm{{ $po->id }}">
                                    @csrf
                                    @if($firstInternal)
                                        <input type="hidden" name="purchase_order_internal_id" value="{{ $firstInternal->id }}">
                                    @endif
                                    <button type="button" class="btn btn-secondary btn-sm me-1" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-danger btn-sm fw-bold">
                                        <i class="bi bi-check-circle me-1"></i> Saya Mengerti (OK)
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. MODAL REJECT UNTUK RINCIAN ITEM DROPDOWN --}}
                @foreach($displayItems as $subIdx => $qItem)
                    @php
                        $targetPoNo = $po->po_no . '-' . ($subIdx + 1);
                        $internalItem = $po->internals ? $po->internals->firstWhere('po_no', $targetPoNo) : null;
                        if (!$internalItem && $po->internals && $po->internals->values()->has($subIdx)) {
                            $internalItem = $po->internals->values()->get($subIdx);
                        }

                        $itemContract = $internalItem 
                            ? \App\Models\Contract::where('purchase_order_internal_id', $internalItem->id)->latest('id')->first()
                            : \App\Models\Contract::where('order_no', $targetPoNo)->latest('id')->first();

                        if (!$itemContract) {
                            $itemContract = \App\Models\Contract::where('order_no', $po->po_no)->latest('id')->first();
                        }

                        $reasonTextItem = ($itemContract && $itemContract->status === 'rejected') ? $itemContract->alasan_penolakan : null;
                    @endphp

                    <div class="modal fade text-start" id="rejectReasonModal{{ $po->id }}_{{ $subIdx }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title text-white"><i class="bi bi-exclamation-triangle-fill me-2"></i> Penolakan Amandemen Item PO</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-dark">
                                    <div class="mb-3">
                                        <small class="text-muted d-block fw-bold">Nomor PO & Item Target:</small>
                                        <span class="badge bg-dark fs-6">{{ $po->po_no }}</span> - <strong class="fs-6 text-primary">{{ $internalItem->item ?? $qItem->item ?? '-' }}</strong>
                                    </div>

                                    @if($itemContract && !empty($itemContract->alasan_amandemen))
                                        <div class="alert alert-light border mb-3">
                                            <small class="text-muted d-block fw-bold mb-1"><i class="bi bi-chat-left-text me-1"></i> Alasan Amandemen Anda:</small>
                                            <span class="fst-italic text-dark">"{{ $itemContract->alasan_amandemen }}"</span>
                                        </div>
                                    @endif

                                    <div class="alert alert-danger border border-danger mb-3 shadow-sm">
                                        <h6 class="fw-bold text-danger mb-2"><i class="bi bi-x-octagon-fill me-1"></i> Alasan Penolakan dari Staff Sales / Manajemen:</h6>
                                        <div class="p-3 bg-white rounded border border-danger text-danger fw-bold fs-6">
                                            "{{ !empty($reasonTextItem) ? $reasonTextItem : 'Pengajuan amandemen tidak dapat disetujui oleh Staff Sales saat ini.' }}"
                                        </div>
                                    </div>

                                    <div class="p-3 bg-light rounded border mb-0">
                                        <p class="small text-dark mb-0">
                                            <i class="bi bi-info-circle-fill text-primary me-1"></i> <strong>Tujuan & Konfirmasi:</strong><br>
                                            Dengan mengeklik <strong>"Saya Mengerti (OK)"</strong>, Anda menyetujui/mengonfirmasi telah membaca alasan penolakan ini. Status penolakan item akan di-reset sehingga Anda dapat mengajukan amandemen baru bila diperlukan.
                                        </p>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light">
                                    <form action="{{ route('purchase-orders.acknowledge-amandement', $po->id) }}" method="POST" id="ackForm{{ $po->id }}_{{ $subIdx }}">
                                        @csrf
                                        @if($internalItem)
                                            <input type="hidden" name="purchase_order_internal_id" value="{{ $internalItem->id }}">
                                        @endif
                                        <button type="button" class="btn btn-secondary btn-sm me-1" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-danger btn-sm fw-bold">
                                            <i class="bi bi-check-circle me-1"></i> Saya Mengerti (OK)
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        @endif

    </section>
@endsection

@push('scripts')
    <script>
        const showBtns = document.querySelectorAll('.btn-show');
        const modalShowContent = document.getElementById('modalContent');

        showBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const internalId = btn.dataset.internalId;

                modalShowContent.innerHTML = `
                    <div class="modal-body text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `;

                let fetchUrl = `/purchase-orders/${id}`;
                if (internalId) {
                    fetchUrl += `?internal_id=${internalId}`;
                }

                fetch(fetchUrl)
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