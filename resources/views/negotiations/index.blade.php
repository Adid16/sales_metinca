@extends('layouts.app')

@section('title', 'Negosiasi Quotation - PT. Metinca Prima Industrial Works')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <style>
        .stat-card {
            border-radius: 12px;
            transition: all 0.25s ease;
            border: 1px solid #e9ecef;
            background: #fff;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        }
        .nav-pills .nav-link {
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.2s ease;
        }
        .nav-pills .nav-link:not(.active) {
            background-color: #f8fafc;
            color: #495057;
            border: 1px solid #e2e8f0;
        }
        .nav-pills .nav-link:not(.active):hover {
            background-color: #e2e8f0;
            color: #0d6efd;
        }
        .table th {
            background-color: #f8fafc;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #495057;
            border-bottom: 2px solid #e2e8f0;
            padding: 12px 14px;
        }
        .table td {
            padding: 12px 14px;
            vertical-align: middle;
        }
        .badge-pending-pulse {
            animation: pulse-glow 1.5s infinite;
        }
        @keyframes pulse-glow {
            0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
            70% { box-shadow: 0 0 0 8px rgba(220, 53, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }
    </style>
@endpush

@section('content')
<div class="page-heading mb-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3 class="mb-1 text-primary"><i class="bi bi-chat-square-quote-fill me-2"></i>Daftar Negosiasi Quotation</h3>
            <p class="text-subtitle text-muted mb-0" style="font-size: 0.88rem;">
                Kelola putaran negosiasi penawaran harga, pantau kuota putaran, dan kesepakatan harga bersama Customer.
            </p>
        </div>
        <div>
            <span class="badge bg-light-primary text-primary border border-primary px-3 py-2" style="font-size: 0.82rem;">
                <i class="bi bi-shield-lock-fill me-1"></i> Hak Akses: <strong>Sales / Manager Sales / Admin</strong>
            </span>
        </div>
    </div>
</div>

<div class="page-content">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- STATISTIC CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 bg-light-primary text-primary me-3">
                        <i class="bi bi-chat-left-dots-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Semua Negosiasi</div>
                        <h4 class="mb-0 fw-bold">{{ $allCount }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 bg-light-warning text-warning me-3">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Sedang Berjalan</div>
                        <h4 class="mb-0 fw-bold text-warning">{{ $activeCount }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 bg-light-success text-success me-3">
                        <i class="bi bi-check-circle-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Disepakati / PO</div>
                        <h4 class="mb-0 fw-bold text-success">{{ $acceptedCount }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT CARD --}}
    <div class="card shadow-sm border" style="border-radius: 12px; overflow: hidden;">
        {{-- FILTER PILL TABS CONTAINER (CLEAN & NON-OVERLAPPING) --}}
        <div class="p-3 bg-white border-bottom">
            <ul class="nav nav-pills gap-2 flex-wrap" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $tab === 'all' ? 'active bg-primary text-white' : '' }}" href="{{ route('negotiations.index', ['tab' => 'all']) }}">
                        <i class="bi bi-grid-fill me-1"></i> Semua Negosiasi 
                        <span class="badge {{ $tab === 'all' ? 'bg-white text-primary' : 'bg-secondary' }} ms-1 rounded-pill">{{ $allCount }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab === 'active' ? 'active bg-warning text-dark' : '' }}" href="{{ route('negotiations.index', ['tab' => 'active']) }}">
                        <i class="bi bi-chat-dots me-1 {{ $tab === 'active' ? 'text-dark' : 'text-warning' }}"></i> Sedang Berjalan (Active)
                        <span class="badge {{ $tab === 'active' ? 'bg-white text-dark' : 'bg-light-warning text-warning border' }} ms-1 rounded-pill">{{ $activeCount }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab === 'accepted' ? 'active bg-success text-white' : '' }}" href="{{ route('negotiations.index', ['tab' => 'accepted']) }}">
                        <i class="bi bi-check-circle-fill me-1 {{ $tab === 'accepted' ? 'text-white' : 'text-success' }}"></i> Disepakati / Selesai
                        <span class="badge {{ $tab === 'accepted' ? 'bg-white text-success' : 'bg-light-success text-success border' }} ms-1 rounded-pill">{{ $acceptedCount }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 5%;" class="text-center">#</th>
                            <th style="width: 25%;">Quotation & Customer</th>
                            <th style="width: 18%;">Total Penawaran</th>
                            <th style="width: 15%;" class="text-center">Counter Putaran</th>
                            <th style="width: 17%;">Status</th>
                            <th style="width: 10%;">Update Terakhir</th>
                            <th style="width: 10%;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quotations as $index => $quotation)
                            @php
                                $lastNego = $quotation->negotiates->first();
                                $currentNegoCount = $quotation->negotiates->where('action', 'negotiate')->count();
                                $effectiveLimit = \App\Services\SystemSettingService::effectiveNegotiationLimit($quotation);
                                $quotaExceeded = $currentNegoCount >= $effectiveLimit;
                                $maxRounds = floor($effectiveLimit / 2);
                            @endphp
                            <tr>
                                <td class="text-center fw-bold text-muted">
                                    {{ $quotations->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="fw-bold text-primary">
                                        <a href="{{ route('quotations.show', $quotation->id) }}" class="text-decoration-none">
                                            {{ $quotation->quotation_no }}
                                        </a>
                                    </div>
                                    <div class="small text-dark fw-semibold">
                                        <i class="bi bi-person-fill text-muted me-1"></i>{{ $quotation->customer->name ?? '-' }}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.78rem;">
                                        <i class="bi bi-building text-muted me-1"></i>{{ $quotation->company ?? ($quotation->customer->company ?? '-') }}
                                    </div>
                                </td>
                                <td>
                                    @if($lastNego && $lastNego->negotiated_total)
                                        <div class="fw-bold text-dark" style="font-size: 0.92rem;">
                                            Rp {{ number_format($lastNego->negotiated_total, 0, ',', '.') }}
                                        </div>
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            Dari: <strong>{{ $lastNego->from_customer ? 'Customer' : 'Sales' }}</strong>
                                        </small>
                                    @else
                                        <div class="fw-bold text-dark">
                                            Rp {{ number_format($quotation->items->sum(fn($i) => $i->price * $i->qty), 0, ',', '.') }}
                                        </div>
                                        <small class="text-muted" style="font-size: 0.75rem;">Harga Awal</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $quotaExceeded ? 'bg-danger' : 'bg-light-info text-info border border-info' }} px-2 py-1" style="font-size: 0.8rem;">
                                        Ke-{{ $currentNegoCount }} / {{ $effectiveLimit }}x
                                    </span>
                                    <div class="text-muted mt-1" style="font-size: 0.72rem;">
                                        ({{ $maxRounds }}x Saling Balas)
                                    </div>
                                </td>
                                <td>
                                    @if($quotation->status === 'accepted')
                                        <span class="badge bg-success" style="font-size: 0.78rem;"><i class="bi bi-check-circle-fill me-1"></i>Accepted / Disepakati</span>
                                    @elseif($quotation->status === 'po')
                                        <span class="badge bg-primary" style="font-size: 0.78rem;"><i class="bi bi-bag-check-fill me-1"></i>PO Diterbitkan</span>
                                    @elseif($quotation->status === 'negotiating')
                                        <span class="badge bg-warning text-dark" style="font-size: 0.78rem;"><i class="bi bi-hourglass-split me-1"></i>Sedang Berjalan</span>
                                    @elseif($quotation->status === 'rejected')
                                        <span class="badge bg-danger" style="font-size: 0.78rem;"><i class="bi bi-x-circle-fill me-1"></i>Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary" style="font-size: 0.78rem;">{{ ucfirst($quotation->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($lastNego)
                                        <div class="fw-semibold text-dark" style="font-size: 0.8rem;">
                                            {{ $lastNego->created_at->format('d M Y') }}
                                        </div>
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            {{ $lastNego->created_at->format('H:i') }} WIB
                                        </small>
                                    @else
                                        <span class="text-muted" style="font-size: 0.8rem;">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('negotiate.show-nego', $quotation->id) }}" class="btn btn-sm btn-primary fw-semibold shadow-sm" title="Buka ruang negosiasi & kelola harga">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Kelola
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-chat-square-dots fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                        <h6>Tidak ada data negosiasi pada kategori ini.</h6>
                                        <p class="small text-muted mb-0">Semua pengajuan negosiasi harga dari Customer akan muncul di halaman ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($quotations->hasPages())
                <div class="p-3 d-flex justify-content-end border-top">
                    {{ $quotations->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
