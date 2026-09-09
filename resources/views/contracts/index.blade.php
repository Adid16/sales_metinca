@extends('layouts.app')

@section('title', 'Contract Review Sheet - PT. Metinca Prima Industrial Works')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        /* ================= TABEL UTAMA (NORMAL TANPA SCROLL) ================= */
        .table-main-wrapper {
            width: 100% !important;
            overflow-x: visible !important;
        }

        .table-main {
            width: 100% !important;
            margin-bottom: 0 !important;
        }

        .table-main th, 
        .table-main td {
            vertical-align: middle !important;
            padding: 0.65rem 0.75rem !important;
            font-size: 0.875rem !important;
        }

        /* ================= SUBTABLE CONTAINER (SCROLL KHUSUS SUB-ITEM) ================= */
        .subtable-container-cell {
            max-width: 0 !important; /* Mencegah tabel utama ikut melebar/scrolling */
            width: 100% !important;
            padding: 0 !important;
            border: none !important;
        }

        .subtable-box {
            background-color: #f2f5fc;
            padding: 10px 12px !important;
            border-left: 4px solid #435ebe !important; /* Garis biru tebal di kiri */
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .subtable-scroll-area {
            overflow-x: auto !important; /* Scroll horizontal khusus di sub-item */
            width: 100% !important;
            -webkit-overflow-scrolling: touch;
        }

        .table-detail {
            min-width: 1100px !important; /* Lebar minimum nyaman agar tidak numpuk */
            width: 100% !important;
            font-size: 0.76rem !important;
            background-color: #ffffff;
            margin-bottom: 0 !important;
            white-space: normal !important;
        }

        .table-detail th {
            background-color: #dbe5ff !important; /* Header soft blue */
            color: #2b3a67 !important;
            font-weight: 700 !important;
            padding: 7px 8px !important;
            border-bottom: 1px solid #c5d4fb !important;
            vertical-align: middle !important;
            font-size: 0.76rem !important;
            white-space: nowrap !important;
        }

        .table-detail td {
            padding: 7px 8px !important;
            line-height: 1.35 !important;
            vertical-align: middle !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            white-space: normal !important;
        }

        .table-detail .badge-status-sub {
            display: inline-block !important;
            max-width: 100% !important;
            white-space: nowrap !important;
            padding: 5px 8px !important;
            font-size: 0.72rem !important;
        }

        .table-detail .action-btns {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 4px !important;
            white-space: nowrap !important;
        }

        .table-detail .action-btns .btn {
            padding: 3px 6px !important;
            font-size: 0.75rem !important;
        }

        .btn-collapse-toggle .bi-chevron-down {
            transition: transform 0.2s ease-in-out;
        }
        .btn-collapse-toggle[aria-expanded="true"] .bi-chevron-down {
            transform: rotate(180deg);
        }

        /* Dark Mode Specific Overrides */
        html[data-bs-theme="dark"] .subtable-box {
            background-color: #161726 !important;
            border-left: 4px solid #435ebe !important;
        }
        html[data-bs-theme="dark"] .table-detail {
            background-color: #1e1e2d !important;
            color: #c2c2d9 !important;
        }
        html[data-bs-theme="dark"] .table-detail th {
            background-color: #282b42 !important;
            color: #93b0ff !important;
            border-bottom: 1px solid #363954 !important;
        }
        html[data-bs-theme="dark"] .table-detail td {
            background-color: #1e1e2d !important;
            color: #c2c2d9 !important;
            border-color: #2d3047 !important;
        }
    </style>
@endpush

@section('content')
    <div class="card detail-card shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0 text-white">
                <i class="bi bi-file-earmark-check-fill me-2"></i>Contract Review Sheet
            </h5>
        </div>

        <div class="page-content">
            <section class="row">
                <div class="col-12">

                    {{-- FLASH MESSAGE --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3">
                            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show m-3">
                            <i class="bi bi-exclamation-triangle me-1"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="card-body py-2">
                        <form class="row g-2 align-items-center mt-0" method="GET" action="{{ route('contracts.index') }}">
                            <div class="col-12 col-sm-6 col-md-4 col-xl-2">
                                <div class="d-flex align-items-center gap-1">
                                    <label class="form-label small mb-0 text-nowrap">From :</label>
                                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $filters['start_date'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 col-xl-2">
                                <div class="d-flex align-items-center gap-1">
                                    <label class="form-label small mb-0 text-nowrap">To :</label>
                                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $filters['end_date'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 col-xl-2">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">Semua Status</option>
                                    <option value="created" {{ (isset($filters['status']) && $filters['status']=='created') ? 'selected' : '' }}>Created</option>
                                    <option value="review" {{ (isset($filters['status']) && $filters['status']=='review') ? 'selected' : '' }}>Review</option>
                                    <option value="approved" {{ (isset($filters['status']) && $filters['status']=='approved') ? 'selected' : '' }}>Approved</option>
                                    <option value="amended" {{ (isset($filters['status']) && $filters['status']=='amended') ? 'selected' : '' }}>Amended</option>
                                    <option value="production" {{ (isset($filters['status']) && $filters['status']=='production') ? 'selected' : '' }}>In Production</option>
                                    <option value="done" {{ (isset($filters['status']) && $filters['status']=='done') ? 'selected' : '' }}>Done</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 col-xl-2">
                                <select name="dept" class="form-select form-select-sm">
                                    <option value="">Semua Departemen</option>
                                    <option value="sales" {{ (isset($filters['dept']) && $filters['dept']=='sales') ? 'selected' : '' }}>Sales</option>
                                    <option value="quality" {{ (isset($filters['dept']) && $filters['dept']=='quality') ? 'selected' : '' }}>Quality</option>
                                    <option value="ppc" {{ (isset($filters['dept']) && $filters['dept']=='ppc') ? 'selected' : '' }}>PPC</option>
                                    <option value="design engineering" {{ (isset($filters['dept']) && $filters['dept']=='design engineering') ? 'selected' : '' }}>Design Engineering</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 col-xl-2">
                                <input type="text" name="search" class="form-control form-control-sm"
                                    placeholder="Cari No Kontrak / PO / Customer..."
                                    value="{{ $filters['search'] ?? '' }}">
                            </div>
                            <div class="col-12 col-xl-auto d-flex flex-wrap gap-1">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-search me-1"></i>Filter
                                </button>
                                <a href="{{ route('contracts.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                                <button type="submit" formaction="{{ route('contracts.export') }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-file-earmark-excel me-1"></i>Export
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        {{-- TABEL UTAMA (PO LEVEL) --}}
                        <div class="table-responsive">
                            <table class="table table-hover text-nowrap align-middle table-main mb-0" id="table1">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center" style="width: 45px;">No</th>
                                        <th class="text-center">Req Id</th>
                                        <th class="text-center">Quotation No</th>
                                        <th class="text-center">Purchase Order No</th>
                                        <th>Customer</th>
                                        <th class="text-center">Total Kontrak</th>
                                        <th class="text-center">Entry Date</th>
                                        <th class="text-center" style="width: 90px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($contracts as $poKey => $contractGroup)
                                        @php
                                            $firstContract = $contractGroup->first();
                                            $po = $firstContract->internalItem->purchaseOrder ?? ($firstContract->purchaseOrder ?? null);
                                            $quotation = $firstContract->quotation ?? ($po ? $po->quotation : null);
                                            $customer = $firstContract->customer ?? ($po ? $po->customer : null);
                                            $collapseId = 'collapse-po-' . $loop->iteration;
                                        @endphp

                                        <!-- PARENT ROW (PO UTAMA) -->
                                        <tr>
                                            <td class="text-center fw-semibold text-muted">{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-primary px-2 py-1">{{ $quotation->request_id ?? ($firstContract->quotation_id ?? '-') }}</span>
                                            </td>
                                            <td class="text-center fw-semibold text-secondary">
                                                {{ $quotation->quotation_no ?? '-' }}
                                            </td>
                                            <td class="text-center fw-bold text-dark">
                                                {{ $po->po_no ?? ($firstContract->order_no ?? $poKey) }}
                                            </td>
                                            <td class="fw-semibold">
                                                {{ $customer->name ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info text-dark px-2 py-1" style="font-size: 11px;">
                                                    <i class="bi bi-file-earmark-text me-1"></i>{{ $contractGroup->count() }} Kontrak Item
                                                </span>
                                            </td>
                                            <td class="text-center text-muted">
                                                {{ $firstContract->created_at ? $firstContract->created_at->format('d M Y') : '-' }}
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-light border btn-collapse-toggle shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false" title="Buka / Tutup Rincian Contract Review Sheet">
                                                    <i class="bi bi-chevron-down"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- CHILD ROW (EXPANDABLE SUB-TABLE KONTRAK ITEM - SCROLL MANDIRI & LEGA) -->
                                        <tr class="p-0 border-0">
                                            <td colspan="8" class="subtable-container-cell">
                                                <div class="collapse subtable-box" id="{{ $collapseId }}">
                                                    <div class="subtable-scroll-area">
                                                        <table class="table table-bordered align-middle table-detail shadow-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center" style="width: 35px;">#</th>
                                                                    <th style="width: 145px;">No. Tinjauan Kontrak</th>
                                                                    <th style="width: 110px;">Article</th>
                                                                    <th style="width: 120px;">Part Number</th>
                                                                    <th style="min-width: 170px;">Part Name</th>
                                                                    <th class="text-center" style="width: 55px;">Qty</th>
                                                                    <th class="text-center" style="width: 50px;" title="Approval Manager Sales">Sales</th>
                                                                    <th class="text-center" style="width: 50px;" title="Approval Manager Quality">QC</th>
                                                                    <th class="text-center" style="width: 50px;" title="Approval Manager PPC">PPC</th>
                                                                    <th class="text-center" style="width: 50px;" title="Approval Manager Development Engineering">DE</th>
                                                                    <th class="text-center" style="width: 140px;">Status</th>
                                                                    <th class="text-center" style="width: 140px;">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($contractGroup as $cIdx => $contract)
                                                                    @php
                                                                        $displayArticle = (!empty($contract->internalItem->article) && $contract->internalItem->article !== '-')
                                                                            ? $contract->internalItem->article
                                                                            : ($contract->article->article_no ?? '-');

                                                                        $displayPartNo = (!empty($contract->part_no) && $contract->part_no !== '-')
                                                                            ? $contract->part_no
                                                                            : (!empty($contract->article->internal_part_no) && $contract->article->internal_part_no !== '-'
                                                                                ? $contract->article->internal_part_no
                                                                                : (!empty($contract->article->part_number) && $contract->article->part_number !== '-'
                                                                                    ? $contract->article->part_number
                                                                                    : (!empty($contract->internalItem->part_no) && $contract->internalItem->part_no !== '-'
                                                                                        ? $contract->internalItem->part_no
                                                                                        : '-')));
                                                                        
                                                                        $all4Approved = $contract->sales_approver 
                                                                                     && $contract->quality_approver 
                                                                                     && $contract->ppc_approver 
                                                                                     && $contract->dev_engineering_approver;
                                                                    @endphp
                                                                    <tr>
                                                                        <td class="text-center text-muted fw-semibold">{{ $loop->iteration }}</td>
                                                                        <td>
                                                                            <span class="badge bg-primary px-2 py-1">{{ $contract->contract_no }}</span>
                                                                            @if($contract->sales_pic)
                                                                                <div class="mt-1">
                                                                                    <small class="text-muted d-block" style="font-size: 10px;" title="Sales PIC: {{ $contract->sales_pic->name }}">
                                                                                        <i class="bi bi-person-fill text-primary"></i> {{ $contract->sales_pic->name }}
                                                                                    </small>
                                                                                </div>
                                                                            @endif
                                                                            @if($contract->amandement_no > 0)
                                                                                <div class="mt-1">
                                                                                    <span class="badge bg-danger text-white" style="font-size: 10px;">
                                                                                        Amandemen #{{ $contract->amandement_no }}
                                                                                    </span>
                                                                                </div>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-secondary fw-semibold">
                                                                            {{ $displayArticle }}
                                                                        </td>
                                                                        <td class="text-secondary fw-semibold">
                                                                            {{ $displayPartNo }}
                                                                        </td>
                                                                        <td class="fw-bold text-dark">
                                                                            {{ $contract->part_name ?? ($contract->internalItem->item ?? '-') }}
                                                                        </td>
                                                                        <td class="text-center fw-bold">
                                                                            {{ number_format($contract->internalItem->qty ?? 0) }}
                                                                        </td>

                                                                        {{-- 4 APPROVER COLUMNS --}}
                                                                        <td class="text-center">
                                                                            @if($contract->sales_approver)
                                                                                <span class="badge bg-success" data-bs-toggle="tooltip" title="Disetujui Sales ({{ $contract->sales_approved_at ? \Carbon\Carbon::parse($contract->sales_approved_at)->format('d/m/Y') : 'OK' }})"><i class="bi bi-check-circle-fill"></i></span>
                                                                            @elseif(!empty($contract->sales_reject_reason))
                                                                                <button type="button" class="btn btn-sm btn-danger p-0 px-1 border-0 btn-show-reject-modal shadow-sm" data-dept="Sales" data-reason="{{ $contract->sales_reject_reason }}" data-date="{{ $contract->sales_rejected_at ? \Carbon\Carbon::parse($contract->sales_rejected_at)->format('d/m/Y H:i') : '' }}" title="Ditolak oleh Manager Sales. Klik untuk melihat alasan."><i class="bi bi-x-circle-fill"></i></button>
                                                                            @else
                                                                                <span class="badge bg-warning text-dark" data-bs-toggle="tooltip" title="Menunggu persetujuan Sales"><i class="bi bi-clock-history"></i></span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-center">
                                                                            @if($contract->quality_approver)
                                                                                <span class="badge bg-success" data-bs-toggle="tooltip" title="Disetujui Quality ({{ $contract->quality_approved_at ? \Carbon\Carbon::parse($contract->quality_approved_at)->format('d/m/Y') : 'OK' }})"><i class="bi bi-check-circle-fill"></i></span>
                                                                            @elseif(!empty($contract->quality_reject_reason))
                                                                                <button type="button" class="btn btn-sm btn-danger p-0 px-1 border-0 btn-show-reject-modal shadow-sm" data-dept="Quality" data-reason="{{ $contract->quality_reject_reason }}" data-date="{{ $contract->quality_rejected_at ? \Carbon\Carbon::parse($contract->quality_rejected_at)->format('d/m/Y H:i') : '' }}" title="Ditolak oleh Manager Quality. Klik untuk melihat alasan."><i class="bi bi-x-circle-fill"></i></button>
                                                                            @else
                                                                                <span class="badge bg-warning text-dark" data-bs-toggle="tooltip" title="Menunggu persetujuan Quality"><i class="bi bi-clock-history"></i></span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-center">
                                                                            @if($contract->ppc_approver)
                                                                                <span class="badge bg-success" data-bs-toggle="tooltip" title="Disetujui PPC ({{ $contract->ppc_approved_at ? \Carbon\Carbon::parse($contract->ppc_approved_at)->format('d/m/Y') : 'OK' }})"><i class="bi bi-check-circle-fill"></i></span>
                                                                            @elseif(!empty($contract->ppc_reject_reason))
                                                                                <button type="button" class="btn btn-sm btn-danger p-0 px-1 border-0 btn-show-reject-modal shadow-sm" data-dept="PPC" data-reason="{{ $contract->ppc_reject_reason }}" data-date="{{ $contract->ppc_rejected_at ? \Carbon\Carbon::parse($contract->ppc_rejected_at)->format('d/m/Y H:i') : '' }}" title="Ditolak oleh Manager PPC. Klik untuk melihat alasan."><i class="bi bi-x-circle-fill"></i></button>
                                                                            @else
                                                                                <span class="badge bg-warning text-dark" data-bs-toggle="tooltip" title="Menunggu persetujuan PPC"><i class="bi bi-clock-history"></i></span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-center">
                                                                            @if($contract->dev_engineering_approver)
                                                                                <span class="badge bg-success" data-bs-toggle="tooltip" title="Disetujui DE ({{ $contract->dev_engineering_approved_at ? \Carbon\Carbon::parse($contract->dev_engineering_approved_at)->format('d/m/Y') : 'OK' }})"><i class="bi bi-check-circle-fill"></i></span>
                                                                            @elseif(!empty($contract->dev_engineering_reject_reason))
                                                                                <button type="button" class="btn btn-sm btn-danger p-0 px-1 border-0 btn-show-reject-modal shadow-sm" data-dept="Design Engineering" data-reason="{{ $contract->dev_engineering_reject_reason }}" data-date="{{ $contract->dev_engineering_rejected_at ? \Carbon\Carbon::parse($contract->dev_engineering_rejected_at)->format('d/m/Y H:i') : '' }}" title="Ditolak oleh Manager DE. Klik untuk melihat alasan."><i class="bi bi-x-circle-fill"></i></button>
                                                                            @else
                                                                                <span class="badge bg-warning text-dark" data-bs-toggle="tooltip" title="Menunggu persetujuan DE"><i class="bi bi-clock-history"></i></span>
                                                                            @endif
                                                                        </td>

                                                                        {{-- STATUS --}}
                                                                        <td class="text-center">
                                                                            @if($contract->status == 'amended')
                                                                                <span class="badge bg-light-danger text-danger fw-bold badge-status-sub">Amended</span>
                                                                            @elseif($contract->status == 'amandement_rejected')
                                                                                <span class="badge bg-danger text-white fw-bold badge-status-sub">Amandemen Ditolak</span>
                                                                            @elseif($contract->status == 'amandement_pending')
                                                                                <span class="badge bg-warning text-dark fw-bold badge-status-sub">Review Amandemen</span>
                                                                            @elseif($contract->status == 'revision' || $contract->status == 'rejected')
                                                                                <span class="badge bg-danger text-white fw-bold badge-status-sub">Ditolak</span>
                                                                            @elseif($contract->status == 'production')
                                                                                <span class="badge bg-success text-white fw-bold badge-status-sub">In Production</span>
                                                                            @elseif($contract->status == 'done')
                                                                                <span class="badge bg-primary text-white fw-bold badge-status-sub">Done</span>
                                                                            @elseif($contract->status == 'approved')
                                                                                <span class="badge bg-info text-dark fw-bold badge-status-sub">Approved</span>
                                                                            @elseif($contract->status == 'review')
                                                                                <span class="badge bg-warning text-dark fw-bold badge-status-sub">Review</span>
                                                                            @else
                                                                                <span class="badge bg-light-primary text-primary fw-bold badge-status-sub">{{ ucfirst($contract->status) ?? '-' }}</span>
                                                                            @endif
                                                                        </td>

                                                                        {{-- ACTION (RINGKAS & RAPI) --}}
                                                                        <td class="text-center">
                                                                            <div class="action-btns">
                                                                                {{-- LIHAT DETAIL --}}
                                                                                <a href="{{ route('contracts.show', $contract->id) }}"
                                                                                    class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Lihat Detail Kontrak">
                                                                                    <i class="bi bi-eye-fill"></i>
                                                                                </a>

                                                                                @if($contract->status == 'amended')
                                                                                    <span class="badge bg-secondary p-1" data-bs-toggle="tooltip" title="Kontrak terunci (Amended)">
                                                                                        <i class="bi bi-lock-fill"></i>
                                                                                    </span>
                                                                                @else
                                                                                    @if(auth()->user()->isAdmin() || (auth()->user()->isStaff() && auth()->user()->divisi == 'sales'))
                                                                                        {{-- FINALISASI KE IN PRODUCTION (JIKA 4 APPROVER OK) --}}
                                                                                        @if($all4Approved && !in_array($contract->status, ['production', 'done']))
                                                                                            @php
                                                                                                $salesPic = $contract->sales_pic;
                                                                                                $isPicOrAdmin = auth()->user()->isAdmin() || (auth()->user()->isManager() && auth()->user()->divisi == 'sales') || (auth()->user()->isStaff() && auth()->user()->divisi == 'sales' && (!$salesPic || $salesPic->id === auth()->id()));
                                                                                            @endphp
                                                                                            @if($isPicOrAdmin)
                                                                                                <form action="{{ route('contracts.finalize', $contract->id) }}" method="POST" class="d-inline" onsubmit="return confirm('4 Divisi telah menyetujui. Memfinalisasi kontrak ini ke tahap In Production (Dalam Produksi)?')">
                                                                                                    @csrf
                                                                                                    <button type="submit" class="btn btn-sm btn-success text-white fw-bold" data-bs-toggle="tooltip" title="Finalisasi Kontrak ke Tahap In Production (Sales PIC: {{ $salesPic->name ?? 'Sales' }})">
                                                                                                        <i class="bi bi-gear-fill"></i>
                                                                                                    </button>
                                                                                                </form>
                                                                                            @else
                                                                                                <span data-bs-toggle="tooltip" title="Menunggu finalisasi Sales PIC ({{ $salesPic->name ?? 'Sales' }})">
                                                                                                    <button type="button" class="btn btn-sm btn-secondary" disabled>
                                                                                                        <i class="bi bi-person-lock"></i>
                                                                                                    </button>
                                                                                                </span>
                                                                                            @endif
                                                                                        @endif

                                                                                        {{-- EDIT KONTRAK --}}
                                                                                        <a href="{{ route('contracts.edit', $contract->id) }}"
                                                                                            class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Edit Kontrak">
                                                                                            <i class="bi bi-pencil-fill"></i>
                                                                                        </a>

                                                                                        {{-- HAPUS KONTRAK --}}
                                                                                        <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Contract Review Sheet ini?')">
                                                                                            @csrf
                                                                                            @method('DELETE')
                                                                                            <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Hapus Kontrak">
                                                                                                <i class="bi bi-trash-fill"></i>
                                                                                            </button>
                                                                                        </form>
                                                                                    @endif
                                                                                @endif

                                                                                {{-- CETAK / DOWNLOAD PDF --}}
                                                                                <a target="_blank" href="{{ route('contract.pdf', $contract->id) }}" class="btn btn-sm btn-danger text-white" data-bs-toggle="tooltip" title="Download / Cetak PDF">
                                                                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                                                                </a>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="12" class="text-center text-muted fst-italic py-2">
                                                                            Belum ada data Contract Review Sheet untuk PO ini.
                                                                        </td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted fst-italic py-4">
                                                Belum ada data Contract Review Sheet yang ditemukan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- PAGINATION --}}
                        <div class="mt-3">
                            {{ $contracts->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Listener untuk tombol pop-up alasan penolakan pada tabel
            document.querySelectorAll('.btn-show-reject-modal').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    var dept = this.getAttribute('data-dept');
                    var reason = this.getAttribute('data-reason');
                    var date = this.getAttribute('data-date');
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Penolakan oleh Manager ' + dept,
                        html: '<div class="text-start mt-2 p-3 bg-light rounded border">' +
                              '<p class="mb-1 text-muted small"><i class="bi bi-calendar-event me-1"></i> <b>Tanggal Penolakan:</b> ' + (date || '-') + '</p>' +
                              '<p class="mb-0 text-dark"><i class="bi bi-chat-left-quote-fill text-danger me-1"></i> <b>Catatan / Alasan Penolakan:</b><br><span class="fst-italic text-danger fw-semibold">"' + reason + '"</span></p>' +
                              '</div>' +
                              '<div class="text-muted small mt-3"><i class="bi bi-info-circle me-1"></i> Silakan masuk ke menu <b>Edit Kontrak</b> untuk merevisi data yang diminta.</div>',
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#435ebe'
                    });
                });
            });
        });
    </script>
@endpush