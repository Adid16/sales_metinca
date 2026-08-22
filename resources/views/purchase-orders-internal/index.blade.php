{{-- resources/views/purchase-orders-internal/index.blade.php --}}
@extends('layouts.app')
@section('title', 'PT. Metinca Prima Industrial Works')

@push('styles')
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        /* ================= TABEL UTAMA (NORMAL) ================= */
        .table-main th, 
        .table-main td {
            vertical-align: middle !important;
            padding: 0.65rem 0.75rem !important;
            font-size: 0.875rem !important; /* Normal 14px */
        }

        /* ================= TABEL DETAIL (100% PASSING NO SCROLL) ================= */
        .subtable-box {
            background-color: #f2f5fc;
            padding: 8px 10px !important;
            border-left: 4px solid #435ebe !important; /* Garis biru tebal di kiri */
            overflow-x: hidden !important; /* Menghilangkan scrollbar horizontal */
            width: 100% !important;
        }

        .table-detail {
            width: 100% !important;
            table-layout: fixed !important; /* Mengunci total lebar tabel pas 100% */
            font-size: 0.72rem !important; /* Super Compact ~11.5px */
            background-color: #ffffff;
            margin-bottom: 0 !important;
            white-space: normal !important;
        }

        .table-detail th {
            background-color: #dbe5ff !important; /* Header indigo/soft blue */
            color: #2b3a67 !important;
            font-weight: 700 !important;
            padding: 6px 6px !important;
            border-bottom: 1px solid #c5d4fb !important;
            vertical-align: middle !important;
        }

        .table-detail td {
            padding: 5px 6px !important;
            line-height: 1.25 !important;
            vertical-align: middle !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        /* Badge & Tombol Akses di Subtable */
        .badge-pricelist {
            background-color: #e6f4ea !important;
            color: #137333 !important;
            border: 1px solid #a8dab5 !important;
            font-size: 0.65rem !important;
            padding: 2px 5px !important;
            border-radius: 4px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 2px;
            white-space: nowrap;
        }

        .btn-add-pricelist {
            background-color: #198754 !important;
            color: #ffffff !important;
            font-size: 0.65rem !important;
            padding: 2px 6px !important;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 2px;
            border: none;
            white-space: nowrap;
        }

        .btn-add-pricelist:hover {
            background-color: #157347 !important;
            color: #ffffff !important;
        }

        .btn-buat-kontrak {
            background-color: #5a6268 !important;
            color: #ffffff !important;
            font-size: 0.65rem !important;
            padding: 2px 6px !important;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 2px;
            border: none;
            white-space: nowrap;
        }

        .btn-buat-kontrak:hover {
            background-color: #484e53 !important;
            color: #ffffff !important;
        }

        .badge-kontrak-dibuat {
            background-color: #e2e3e5 !important;
            color: #41464b !important;
            border: 1px solid #c6c7c8 !important;
            font-size: 0.65rem !important;
            padding: 2px 6px !important;
            border-radius: 4px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 3px;
            white-space: nowrap;
        }

        .btn-collapse-toggle .bi-chevron-down {
            transition: transform 0.2s ease-in-out;
        }
        .btn-collapse-toggle[aria-expanded="true"] .bi-chevron-down {
            transform: rotate(180deg);
        }
    </style>
@endpush

@section('content')

{{-- ================= KOTAK PENCARIAN ARTIKEL / QC ================= --}}
<div class="card shadow-sm mb-3" style="border-left: 4px solid #17a2b8;">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-md-8">
                <label class="form-label fw-bold text-info mb-1">Cek Ketersediaan Barang (Database Pricelist / QC)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" id="search_article" class="form-control" placeholder="Ketik Nomor Artikel (Contoh: ART-001) lalu tekan Enter..." autocomplete="off">
                    <button class="btn btn-info text-white" type="button" id="btn_search">Cari Data</button>
                </div>
                <small class="text-muted mt-1 d-block">* Masukkan kode artikel untuk melihat informasi spesifikasi teknis kartu master QC.</small>
            </div>
            
            <div class="col-md-4 text-center mt-3 mt-md-0 d-none" id="qc_action_area">
                <p class="text-danger small fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill"></i> Data Tidak Ditemukan!</p>
                <button type="button" class="btn btn-sm btn-danger" id="btn_send_qc" data-bs-toggle="tooltip" title="Kirim notifikasi ke QC untuk melengkapi master data">
                    <i class="bi bi-send-fill"></i> Send to QC
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ================= TABEL PO INTERNAL ================= --}}
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary py-3">
        <h5 class="mb-0 fw-bold text-white">
            <i class="bi bi-file-earmark-richtext-fill me-2"></i>Purchase Order Internal
        </h5>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Filter Header --}}
        <form class="row g-2 align-items-center mb-3 mt-1" method="GET">
            <div class="col-md-3">
                <div class="d-flex align-items-center gap-1">
                    <label class="form-label small mb-0 text-nowrap">From : </label>
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $filters['start_date'] ?? '' }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center gap-1">
                    <label class="form-label small mb-0 text-nowrap">To : </label>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $filters['end_date'] ?? '' }}">
                </div>
            </div>
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm"
                    placeholder="Cari PO No / Customer / Quotation..."
                    value="{{ $filters['search'] ?? '' }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="{{ route('purchase-orders-internal.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>

        {{-- TABEL UTAMA (TAMPILAN NORMAL) --}}
        <div class="table-responsive">
            <table class="table table-hover text-nowrap align-middle table-main mb-0" id="table1">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 45px;">No</th>
                        <th class="text-center">Req Id</th>
                        <th class="text-center">Quotation No</th>
                        <th class="text-center">Purchase Order No</th>
                        <th>Customer</th>
                        <th class="text-center">Items</th>
                        <th class="text-center">Delivery Date</th>
                        <th class="text-center">Entry Date</th>
                        <th class="text-center" style="width: 90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody style="color:#212529;">
                    @php
                        $poList = isset($pos) ? $pos : (isset($items) ? $items->groupBy('purchase_order_id') : collect());
                    @endphp

                    @forelse($poList as $key => $poData)
                        @php
                            $po = isset($pos) ? $poData : ($poData->first()->purchaseOrder ?? null);
                            $internals = isset($pos) ? $po->internals : $poData;
                            $poId = $po->id ?? $key;
                        @endphp

                        <!-- PARENT ROW -->
                        <tr>
                            <td class="text-center fw-semibold text-muted">{{ $loop->iteration }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary px-2 py-1">{{ $po->quotation->request_id ?? $po->request_id ?? '-' }}</span>
                            </td>
                            <td class="text-center fw-semibold text-secondary">
                                {{ $po->quotation->quotation_no ?? '-' }}
                            </td>
                            <td class="text-center fw-bold text-dark">
                                {{ $po->po_no ?? '-' }}
                            </td>
                            <td class="fw-semibold">
                                {{ $po->customer->name ?? $po->quotation->customer->name ?? '-' }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary px-2 py-1" style="font-size: 11px;">
                                    {{ $internals->count() }} item
                                </span>
                            </td>
                            <td class="text-center fw-semibold text-danger">
                                {{ isset($po->delivery_request) ? \Carbon\Carbon::parse($po->delivery_request)->format('d M Y') : '-' }}
                            </td>
                            <td class="text-center text-muted">
                                {{ $po->created_at ? $po->created_at->format('d M Y') : '-' }}
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-1">
                                    <button type="button" class="btn btn-sm btn-info text-white btn-show shadow-sm" data-id="{{ $poId }}" data-bs-toggle="modal" data-bs-target="#previewModal" title="Detail PO">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light border btn-collapse-toggle shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-po-{{ $poId }}" aria-expanded="false" title="Pilih item untuk buat kontrak">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- CHILD ROW (EXPANDABLE SUB-TABLE COMPACT & NO SCROLL) -->
                        <tr class="p-0 border-0">
                            <td colspan="9" class="p-0 border-0">
                                <div class="collapse subtable-box" id="collapse-po-{{ $poId }}">
                                    <table class="table table-bordered align-middle table-detail shadow-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 3%;">#</th>
                                                <th style="width: 10%;">Part No</th>
                                                <th style="width: 24%;">Item / Part Name</th>
                                                <th style="width: 12%;">Material</th>
                                                <th style="width: 20%;">Spesifikasi</th>
                                                <th class="text-center text-nowrap" style="width: 8%;">Qty</th>
                                                <th class="text-end text-nowrap" style="width: 11%;">Subtotal</th>
                                                <th class="text-center text-nowrap" style="width: 12%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($internals as $idx => $internal)
                                                @php
                                                    $hasPartNo = !empty($internal->part_no) && $internal->part_no !== '-';

                                                    // CEK KONTRAK SPESIFIK UNTUK ITEM INTERNAL INI
                                                    $latestContract = \App\Models\Contract::where('purchase_order_internal_id', $internal->id)
                                                        ->orderByDesc('amandement_no')
                                                        ->first();
                                                    
                                                    $itemStatus = $latestContract ? strtolower($latestContract->status) : 'none';
                                                @endphp
                                                <tr>
                                                    <td class="text-center text-muted fw-semibold">{{ $loop->iteration }}</td>
                                                    <td class="text-secondary fw-semibold">
                                                        {{ $hasPartNo ? $internal->part_no : '-' }}
                                                    </td>
                                                    <td class="fw-bold text-dark">
                                                        {{ $internal->item }}
                                                        @if($itemStatus == 'amandement_pending')
                                                            <span class="badge bg-warning text-dark ms-1" style="font-size: 10px;" title="Pengajuan amandemen item ini sedang dalam peninjauan Sales">
                                                                <i class="bi bi-clock-history me-1"></i> Pending Amandemen
                                                            </span>
                                                        @elseif($itemStatus == 'rejected')
                                                            <span class="badge bg-danger text-white ms-1" style="font-size: 10px;" title="Pengajuan amandemen item ini ditolak oleh Sales; Kontrak sebelumnya tetap berlaku">
                                                                <i class="bi bi-x-circle me-1"></i> Amandemen Ditolak
                                                            </span>
                                                        @elseif($latestContract && $latestContract->amandement_no > 0)
                                                            <span class="badge bg-danger text-white ms-1" style="font-size: 10px;" title="Item ini telah mengalami amandemen spesifikasi/qty">
                                                                <i class="bi bi-pencil-square me-1"></i> Amandemen #{{ $latestContract->amandement_no }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-muted">
                                                        {{ $internal->material ?? '-' }}
                                                    </td>
                                                    <td class="text-muted">
                                                        {{ $internal->spesifikasi ?? '-' }}
                                                    </td>
                                                    <td class="text-center fw-bold text-nowrap">
                                                        {{ number_format($internal->qty) }}
                                                    </td>
                                                    <td class="text-end fw-semibold text-success text-nowrap">
                                                        Rp {{ number_format($internal->subtotal ?? ($internal->price * $internal->qty), 0, ',', '.') }}
                                                    </td>

                                                    {{-- AKSI: MENGGUNAKAN FLEX-COLUMN (MENUMPUK ATAS-BAWAH) --}}
                                                    <td class="text-center text-nowrap">
                                                        <div class="d-flex flex-column align-items-center justify-content-center gap-1">
                                                            
                                                            {{-- 1. PRICELIST STATUS / BUTTON --}}
                                                            @if($hasPartNo)
                                                                <span class="badge-pricelist">
                                                                    <i class="bi bi-check-circle-fill"></i> Sudah di Pricelist
                                                                </span>
                                                            @else
                                                                <a href="#" class="btn-add-pricelist" title="Tambah ke Pricelist">
                                                                    <i class="bi bi-plus-circle-fill"></i> Pricelist
                                                                </a>
                                                            @endif

                                                            {{-- 2. BUAT KONTRAK STATUS / BUTTON (MURNI SPESIFIK ITEM THIS ID) --}}
                                                            @if($itemStatus == 'amandement_pending')
                                                                {{-- JIKA PENGAJUAN AMANDEMEN MASIH PENDING REVIEW SALES --}}
                                                                <span class="badge-kontrak-dibuat" style="background-color: #fff3cd !important; color: #664d03 !important; border: 1px solid #ffecb5 !important;" title="Pengajuan amandemen item ini sedang dalam peninjauan / persetujuan Sales">
                                                                    <i class="bi bi-clock-history me-1"></i> Pending Amandemen
                                                                </span>
                                                            @elseif($itemStatus == 'amandement')
                                                                {{-- JIKA AMANDEMEN DISUTUJI TAPI BELUM DIPROSES ULANG KE PO INTERNAL --}}
                                                                <a href="{{ route('purchase-orders-internal.create', ['purchaseOrder' => $poId, 'internal_id' => $internal->id]) }}" 
                                                                   class="btn-buat-kontrak" style="background-color: #ffc107 !important; color: #000 !important; font-weight: 700;" title="Amandemen telah disetujui. Harap proses ulang data amandemen ke PO Internal terlebih dahulu sebelum membuat kontrak baru">
                                                                    <i class="bi bi-arrow-repeat me-1"></i> Proses Internal Amandemen
                                                                </a>
                                                            @elseif(!$latestContract || $itemStatus == 'created')
                                                                {{-- TAMPILKAN TOMBOL BUAT KONTRAK HANYA JIKA BELUM DIBUAT ATAU SUDAH DIPROSES KE PO INTERNAL --}}
                                                                <a href="{{ route('purchase-orders.create-contract', ['idPO' => $poId, 'internal_id' => $internal->id]) }}" 
                                                                   class="btn-buat-kontrak" title="Buat Lembar Review Kontrak Untuk Item Ini">
                                                                    <i class="bi bi-briefcase-fill"></i> Buat Kontrak
                                                                </a>
                                                            @else
                                                                {{-- JIKA KONTRAK SUDAH DIBUAT (TERMASUK STATUS REJECTED/REVIEW/DONE) --}}
                                                                <span class="badge-kontrak-dibuat" title="Kontrak untuk item ini telah dibuat dan sedang diproses">
                                                                    <i class="bi bi-check-circle-fill text-success me-1"></i> Sudah Dibuat
                                                                </span>
                                                            @endif

                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted fst-italic py-2">
                                                        Belum ada rincian item internal.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted fst-italic py-4">
                                <i class="bi bi-folder-x fs-4 d-block mb-1"></i> Belum ada data PO Internal.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 px-2">
            <small class="text-muted">
                Showing entries
            </small>
            @if(isset($items) && method_exists($items, 'links'))
                {{ $items->links('pagination::bootstrap-5') }}
            @elseif(isset($pos) && method_exists($pos, 'links'))
                {{ $pos->links('pagination::bootstrap-5') }}
            @endif
        </div>
    </div>
</div>

{{-- Modal Preview Detail --}}
<div class="modal fade" id="previewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" id="modalContent"></div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/simple-datatables.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search_article');
        const btnSearch   = document.getElementById('btn_search');
        const qcArea      = document.getElementById('qc_action_area');
        const btnSendQC   = document.getElementById('btn_send_qc');

        function performQuickSearch() {
            const article = searchInput.value.trim();
            if(article === '') return;

            Swal.fire({ title: 'Mencari Artikel...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});

            fetch(`/articles/requirements/${encodeURIComponent(article)}`)
                .then(response => {
                    if (!response.ok) throw new Error('Not found');
                    return response.json();
                })
                .then(res => {
                    if(res.success && res.data) {
                        qcArea.classList.add('d-none');
                        
                        let specText = [];
                        if (res.data.drawing_no) specText.push(`<b>Drawing No:</b> ${res.data.drawing_no}`);
                        if (res.data.berat) specText.push(`<b>Berat:</b> ${res.data.berat} Kg`);

                        Swal.fire({
                            icon: 'success',
                            title: 'Data Card Master Terfetch!',
                            html: `
                                <div class="text-start border p-3 rounded bg-light mt-2 small" style="line-height: 1.6;">
                                    <div><b>Nama Item:</b> ${res.data.part_name ?? '-'}</div>
                                    <div><b>Material:</b> ${res.data.material ?? '-'}</div>
                                    <div>${specText.join('<br>')}</div>
                                    <div class="text-success fw-bold mt-1"><b>Master Price:</b> Rp ${(res.data.total_price ?? res.data.price ?? 0).toLocaleString('id-ID')}</div>
                                </div>
                            `,
                            confirmButtonText: 'Tutup Tinjauan'
                        });
                    }
                })
                .catch(() => {
                    qcArea.classList.remove('d-none');
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Data Tidak Ditemukan!', 
                        text: 'Nomor Artikel tidak terdaftar di database master. Silakan ajukan penambahan data ke tim QC melalui tombol Send to QC di atas.', 
                        confirmButtonText: 'Mengerti' 
                    });
                });
        }

        btnSearch?.addEventListener('click', performQuickSearch);
        searchInput?.addEventListener('keypress', function(e) {
            if(e.key === 'Enter') { e.preventDefault(); performQuickSearch(); }
        });

        btnSendQC?.addEventListener('click', function() {
            const article = searchInput.value.trim();
            Swal.fire({
                title: 'Kirim Permintaan QC?',
                text: `Meminta QC untuk membuat spesifikasi barang: ${article.toUpperCase()}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Terkirim!', 'Permintaan penambahan master artikel telah diteruskan ke QC.', 'success');
                    qcArea.classList.add('d-none');
                    searchInput.value = '';
                }
            });
        });

        // Modal Show Detail Fetch
        const showBtns = document.querySelectorAll('.btn-show');
        const modalShowContent = document.getElementById('modalContent');

        showBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                modalShowContent.innerHTML = `
                    <div class="modal-body text-center py-5">
                        <div class="spinner-border text-primary" role="status">
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
                            <div class="modal-body text-danger text-center py-4">
                                Gagal memuat data detail
                            </div>
                        `;
                    });
            });
        });

        // Tooltip Initialization
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
    });
    </script>
@endpush