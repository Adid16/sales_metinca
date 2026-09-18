@extends('layouts.app')

@section('title', 'Pusat Notifikasi')

@section('content')
<div class="page-heading">
    <div class="page-title mb-3">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3 class="fw-bold mb-1"><i class="bi bi-bell-fill text-primary me-2"></i>Pusat Notifikasi</h3>
                <p class="text-subtitle text-muted mb-0">Daftar semua pemberitahuan, aktivitas proyek, dan status persetujuan.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-md-end mb-3 mb-md-0">
                @if($unreadCount > 0)
                    <form action="{{ route('notifikasi.markAllRead') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm px-3 shadow-sm">
                            <i class="bi bi-check2-all me-1"></i> Tandai Semua Dibaca ({{ $unreadCount }})
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-transparent border-bottom py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-primary rounded-pill px-3 filter-notif-btn active" data-filter="all">
                    Semua <span class="badge bg-white text-primary rounded-pill ms-1">{{ $notifications->count() }}</span>
                </button>
                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 filter-notif-btn" data-filter="unread">
                    Belum Dibaca <span class="badge bg-danger text-white rounded-pill ms-1">{{ $unreadCount }}</span>
                </button>
                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 filter-notif-btn" data-filter="read">
                    Sudah Dibaca <span class="badge bg-secondary text-white rounded-pill ms-1">{{ $notifications->count() - $unreadCount }}</span>
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            @if($notifications->isEmpty())
                <div class="text-center py-5 px-3">
                    <div class="mb-3">
                        <i class="bi bi-bell-slash text-muted" style="font-size: 3.5rem; opacity: 0.4;"></i>
                    </div>
                    <h5 class="fw-bold text-secondary mb-1">Belum Ada Notifikasi</h5>
                    <p class="text-muted small mb-0">Pemberitahuan terkait order, penawaran, dan approval akan muncul di sini.</p>
                </div>
            @else
                <div class="list-group list-group-flush" id="notificationFullList">
                    @foreach($notifications as $n)
                        @php
                            $msg = $n->data['message'] ?? 'Pemberitahuan baru';
                            $isUnread = is_null($n->read_at);
                            $url = route('notifikasi.read', $n->id);
                            $timeAgo = $n->created_at ? $n->created_at->diffForHumans() : '';
                            $exactTime = $n->created_at ? $n->created_at->format('d M Y, H:i') : '';
                            $docNo = $n->data['order_no'] ?? $n->data['quotation_no'] ?? $n->data['po_no'] ?? null;
                            
                            $lowerMsg = strtolower($msg);
                            $icon = 'bi-bell-fill';
                            $iconClass = 'notif-icon-primary';
                            $categoryBadge = 'Info';
                            $categoryBadgeClass = 'bg-secondary';
                            
                            if (str_contains($lowerMsg, 'approve') || str_contains($lowerMsg, 'disetujui') || str_contains($lowerMsg, 'berhasil')) {
                                $icon = 'bi-check-circle-fill';
                                $iconClass = 'notif-icon-success';
                                $categoryBadge = 'Approved';
                                $categoryBadgeClass = 'bg-success';
                            } elseif (str_contains($lowerMsg, 'tolak') || str_contains($lowerMsg, 'reject') || str_contains($lowerMsg, 'batal') || str_contains($lowerMsg, 'gagal')) {
                                $icon = 'bi-x-circle-fill';
                                $iconClass = 'notif-icon-danger';
                                $categoryBadge = 'Ditolak';
                                $categoryBadgeClass = 'bg-danger';
                            } elseif (str_contains($lowerMsg, 'request') || str_contains($lowerMsg, 'permintaan') || str_contains($lowerMsg, 'ditugaskan')) {
                                $icon = 'bi-inbox-fill';
                                $iconClass = 'notif-icon-purple';
                                $categoryBadge = 'Request';
                                $categoryBadgeClass = 'bg-primary';
                            } elseif (str_contains($lowerMsg, 'quotation') || str_contains($lowerMsg, 'penawaran')) {
                                $icon = 'bi-file-earmark-text-fill';
                                $iconClass = 'notif-icon-primary';
                                $categoryBadge = 'Quotation';
                                $categoryBadgeClass = 'bg-primary';
                            } elseif (str_contains($lowerMsg, 'contract') || str_contains($lowerMsg, 'kontrak')) {
                                $icon = 'bi-file-earmark-check-fill';
                                $iconClass = 'notif-icon-info';
                                $categoryBadge = 'Contract';
                                $categoryBadgeClass = 'bg-info';
                            } elseif (str_contains($lowerMsg, 'po') || str_contains($lowerMsg, 'purchase order') || str_contains($lowerMsg, 'amandemen')) {
                                $icon = 'bi-bag-check-fill';
                                $iconClass = 'notif-icon-warning';
                                $categoryBadge = 'PO';
                                $categoryBadgeClass = 'bg-warning text-dark';
                            }
                        @endphp
                        <div class="list-group-item list-group-item-action p-3 px-4 notif-row {{ $isUnread ? 'is-unread bg-light-subtle' : '' }}"
                             data-status="{{ $isUnread ? 'unread' : 'read' }}"
                             style="{{ $isUnread ? 'border-left: 4px solid #435ebe;' : '' }}">
                            <div class="d-flex align-items-center gap-3">
                                <div class="notification-icon-box {{ $iconClass }} rounded-3" style="width: 44px; height: 44px; min-width: 44px; font-size: 1.25rem;">
                                    <i class="bi {{ $icon }}"></i>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge {{ $categoryBadgeClass }} rounded-pill" style="font-size: 0.7rem;">
                                                {{ $categoryBadge }}
                                            </span>
                                            @if($docNo)
                                                <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.72rem;">
                                                    {{ $docNo }}
                                                </span>
                                            @endif
                                            @if($isUnread)
                                                <span class="badge bg-primary rounded-pill" style="font-size: 0.65rem;">Baru</span>
                                            @endif
                                        </div>
                                        <div class="text-muted small" title="{{ $exactTime }}">
                                            <i class="bi bi-clock me-1"></i>{{ $timeAgo }} ({{ $exactTime }})
                                        </div>
                                    </div>
                                    <h6 class="mb-2 {{ $isUnread ? 'fw-bold text-dark' : 'text-body-secondary' }}" style="font-size: 0.95rem;">
                                        {{ $msg }}
                                    </h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ $url }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> Buka Dokumen Terkait
                                        </a>
                                        @if(!$isUnread)
                                            <span class="text-muted small ms-2"><i class="bi bi-check2 text-success me-1"></i>Sudah dibaca</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div id="noFilteredNotifs" class="text-center py-5 d-none">
                    <i class="bi bi-inbox text-muted fs-1 opacity-50 d-block mb-2"></i>
                    <p class="text-muted mb-0">Tidak ada notifikasi pada kategori ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.filter-notif-btn').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.filter-notif-btn').forEach(btn => {
            btn.classList.remove('btn-primary', 'active');
            btn.classList.add('btn-outline-secondary');
        });
        this.classList.remove('btn-outline-secondary');
        this.classList.add('btn-primary', 'active');

        const filter = this.getAttribute('data-filter');
        const rows = document.querySelectorAll('.notif-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            if (filter === 'all' || status === filter) {
                row.classList.remove('d-none');
                visibleCount++;
            } else {
                row.classList.add('d-none');
            }
        });

        const noData = document.getElementById('noFilteredNotifs');
        if (noData) {
            if (visibleCount === 0) {
                noData.classList.remove('d-none');
            } else {
                noData.classList.add('d-none');
            }
        }
    });
});
</script>
@endpush
