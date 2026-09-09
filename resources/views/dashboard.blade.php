{{-- Include layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'Dashboard - PT. Metinca Prima Industrial Works')

{{-- Untuk menggunakan css --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_admin.css') }}">
    <style>
        .dashboard-metric-card {
            transition: all 0.25s ease-in-out;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.06);
            background: #ffffff;
            cursor: pointer;
        }
        .dashboard-metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
            border-color: rgba(67, 94, 190, 0.4) !important;
        }
        .dashboard-metric-card .chevron-icon {
            transition: transform 0.2s ease;
            opacity: 0.4;
        }
        .dashboard-metric-card:hover .chevron-icon {
            transform: translateX(4px);
            opacity: 1;
            color: #435ebe;
        }
        html[data-bs-theme="dark"] .dashboard-metric-card {
            background: #1e1e2d !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        html[data-bs-theme="dark"] .dashboard-metric-card:hover {
            border-color: rgba(67, 94, 190, 0.8) !important;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3) !important;
        }
        html[data-bs-theme="dark"] .dashboard-metric-card h4,
        html[data-bs-theme="dark"] .dashboard-metric-card h6 {
            color: #f1f1f1 !important;
        }
    </style>
@endpush

{{-- Isi content --}}
@section('content')
<div class="page-heading mb-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3 class="mb-1 text-dark fw-bold">Dashboard Operasional</h3>
            <p class="text-subtitle text-muted mb-0">Ringkasan transaksi dan status alur kerja PT. Metinca Prima Industrial Works.</p>
        </div>
        <div>
            <span class="badge bg-light text-dark border px-3 py-2">
                <i class="bi bi-clock-history me-1 text-primary"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            {{-- GRID METRIC CARDS --}}
            <div class="row g-3 mb-4">
                @foreach ($data as $item)
                <div class="col-12 col-sm-6 col-xl-4">
                    <a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none text-reset">
                        <div class="card dashboard-metric-card shadow-sm h-100 mb-0">
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="stats-icon {{ $item['color'] ?? 'blue' }} m-0">
                                            <i class="bi {{ $item['icon'] ?? 'bi-grid' }}"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted font-semibold mb-0" style="font-size: 0.9rem;">{{ $item['label'] }}</h6>
                                            <h3 class="font-extrabold mb-0 mt-1 text-dark">{{ number_format($item['count']) }}</h3>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right chevron-icon fs-5"></i>
                                </div>
                                @if(!empty($item['subtext']))
                                    <div class="pt-2 border-top">
                                        <small class="text-muted d-flex align-items-center" style="font-size: 0.78rem;">
                                            <i class="bi bi-arrow-right-circle me-1 text-primary"></i> {{ $item['subtext'] }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- ACTIVITY HISTORY --}}
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-primary-subtle text-primary rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                    <i class="bi bi-clock-history fs-5"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0 text-dark fw-bold">Riwayat Aktivitas Terkini (Audit Trail)</h5>
                                    <small class="text-muted">Pemantauan rekam jejak aksi dan transaksi sistem secara real-time</small>
                                </div>
                            </div>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 small fw-semibold">
                                <i class="bi bi-list-check me-1"></i>15 Aktivitas Terbaru
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr class="text-secondary small fw-bold">
                                            <th width="22%" class="ps-4 py-3">Pengguna & Peran</th>
                                            <th width="20%" class="py-3">Modul & Aksi</th>
                                            <th width="40%" class="py-3">Rincian Aktivitas</th>
                                            <th width="18%" class="text-end pe-4 py-3">Waktu Eksekusi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($activity as $item)
                                            <tr class="{{ $item->action_info['border'] }}">
                                                <td class="ps-4 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar bg-light border text-primary rounded-circle p-1 me-2 d-flex align-items-center justify-content-center fw-bold" style="width: 34px; height: 34px; font-size: 13px;">
                                                            {{ strtoupper(substr($item->user->name ?? 'S', 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <div class="text-dark fw-bold small">{{ $item->user->name ?? 'Sistem' }}</div>
                                                            <div class="mt-0.5">{!! $item->user_badge_html !!}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="d-flex flex-wrap gap-1 align-items-center">
                                                        <span class="badge {{ $item->module_info['class'] }} small">
                                                            <i class="{{ $item->module_info['icon'] }} me-1"></i>{{ $item->module_info['name'] }}
                                                        </span>
                                                        <span class="badge {{ $item->action_info['badge_class'] }} small">
                                                            <i class="{{ $item->action_info['icon'] }} me-1"></i>{{ $item->action_info['label'] }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="text-dark small leading-relaxed">
                                                        {!! $item->formatted_html !!}
                                                    </div>
                                                </td>
                                                <td class="text-end pe-4 py-3">
                                                    <div class="text-dark fw-semibold small">
                                                        <i class="bi bi-clock me-1 text-muted"></i>{{ \Carbon\Carbon::parse($item->activity_time)->translatedFormat('d M Y, H:i') }} WIB
                                                    </div>
                                                    <span class="badge bg-light text-secondary border extra-small mt-1">
                                                        {{ $item->time_ago }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5 text-muted">
                                                    <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                                                    Belum ada rekam riwayat aktivitas sistem.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection