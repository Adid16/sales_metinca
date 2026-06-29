{{-- resources/views/purchase-orders-internal/index.blade.php --}}
@extends('layouts.app')
@section('title', 'PT. Metinca Prima Industrial Works')
 
@push('styles')
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon"
        href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnHub3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjMzIgogICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzQiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJjcmVhdGVkIiBzdEV2dDpkaXN0cmlidXRvcj0iQWZmaW5pdHkgRGVzaWduZXIiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFmZmluaXR5IERlc2lnbmVyIDEuMTAuMSIgc3RFXZ06d2hlbj0iMjAyMi0wMy0zMVQxMDo1MDoyMyswMjowMCIvPgogICAgPC9yZGY6U2VxPgogICA8L3htcE1NOkhpc3Rvcnk+CiAgPC9yZGY6RGVzY3JpcHRpb24+CiA8L3JkZjpSREY+CjwveDpteG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLubwIrKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7usMMAAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KdOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC"
        type="image/png">

    <link rel="stylesheet" href="assets/extensions/simple-datatables/style.css">
    <link rel="stylesheet" href="./assets/compiled/css/table-datatable.css">
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush
 
@section('content')

{{-- ================= KOTAK PENCARIAN ARTIKEL ================= --}}
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
 
        {{-- Filter --}}
        <form class="row g-2 align-items-center mb-2 mt-2" method="GET">
            <div class= "col-md-3">
                <div class="d-flex align-items-center gap-1">
                    <label class="form-label small mb-0 text-nowrap">From : </label>
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $filters['start_date'] ?? '' }}" placeholder="From">
                </div>
            </div>
           <div class= "col-md-3">
                <div class="d-flex align-items-center gap-1">
                    <label class="form-label small mb-0 text-nowrap">To : </label>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $filters['end_date'] ?? '' }}" placeholder="To">
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
                <a href="{{ route('purchase-orders-internal.index') }}"
                    class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
 
        <div>
            <table class="table table-responsive table-hover text-nowrap" id="table1">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Req Id</th>
                        <th class="text-center">No PO</th>
                        <th class="text-center">Quotation No</th>
                        <th class="text-center">Customer</th>
                        <th class="text-center">Item</th>
                        <th class="text-center">Material</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-center">Delivery Date</th>
                        <th class="text-center">Tanggal Input</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody style="color:#212529;">
                    @forelse($items as $i => $item)
                        @php
                            $contract = \App\Models\Contract::where('order_no', $item->purchaseOrder->po_no)->first();
                            $isAmandemen = $contract && in_array(strtolower($contract->status), ['amandemen', 'amandement']);
                            
                            // LOGIKA DETEKSI DINAMIS: Cek apakah amandemen ini sudah disubmit ulang ke PO Internal
                            $alreadyProcessedInternal = false;
                            if ($isAmandemen) {
                                $poLastUpdate = $item->updated_at;
                                if ($item->purchaseOrder && $item->purchaseOrder->updated_at > $poLastUpdate) {
                                    $poLastUpdate = $item->purchaseOrder->updated_at;
                                }
                                
                                // Jika waktu update data PO lebih baru dari pembuatan berkas amandemen kontrak, artinya sudah diproses
                                if ($poLastUpdate > $contract->updated_at) {
                                    $alreadyProcessedInternal = true;
                                }
                            }
                        @endphp
                    <tr>
                        <td class="text-center">{{ $items->firstItem() + $i }}</td>
                        <td class="text-center"><span class="badge badge-sm bg-light text-dark border">{{ $item->id }}</span></td>
                        <td class="fw-semibold text-center text-primary">{{ $item->po_no ?? '-' }}</td>
                        <td class="text-center">{{ $item->purchaseOrder->quotation->quotation_no ?? '-' }}</td>
                        <td class="text-center">{{ $item->purchaseOrder->customer->name ?? '-' }}</td>
                        <td class="text-nowrap fw-semibold">{{ $item->item }}</td>
                        <td class="text-center">{{ $item->material ?? '-' }}</td>
                        <td class="text-center fw-bold">{{ number_format($item->qty) }} {{ $item->satuan ?? 'Pcs' }}</td>
                        <td class="text-end fw-semibold text-success">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                        <td class="text-center text-danger fw-semibold">
                            {{ $item->delivery_date?->format('d M Y') ?? '-' }}
                        </td>
                        <td class="text-center text-muted">{{ $item->created_at->format('d M Y') }}</td>
                        
                        <td class="text-center">
                            {{-- JIKA SEDANG AMANDEMEN DAN BELUM DIPROSES SALES KE INTERNAL --}}
                            @if($isAmandemen && !$alreadyProcessedInternal)
                                <span class="badge bg-warning text-black fw-bold px-2 py-1 shadow-sm" style="font-size: 11px;">
                                    <i class="bi bi-exclamation-circle-fill me-1"></i>Amandemen
                                </span>
                            @else
                                {{-- ALUR NORMAL / AMANDEMEN YANG SUDAH BERHASIL DIPROSES KE INTERNAL (TOMBOL TERBUKA KEMBALI) --}}
                                <a href="{{ route('purchase-orders-internal.show-item', $item->id) }}"
                                    class="btn btn-sm btn-info shadow-sm" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->isAdmin() || (auth()->user()->isStaff() && auth()->user()->divisi == 'sales'))
                                    
                                    @if($contract && !$isAmandemen)
                                        <button class="btn btn-sm btn-secondary shadow-sm" disabled data-bs-toggle="tooltip" title="Kontrak Sudah Selesai">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </button>
                                    @else
                                        {{-- Jika amandemen sudah diproses ke internal, arahkan tombol untuk update lembar kontrak --}}
                                        @if($isAmandemen)
                                            <a href="{{ route('contracts.edit', $contract->id) }}"
                                                class="btn btn-sm btn-warning text-dark fw-bold shadow-sm" data-bs-toggle="tooltip"
                                                data-bs-trigger="hover" title="Update Kontrak Amandemen">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('purchase-orders.create-contract', $item->purchaseOrder->id) }}"
                                                class="btn btn-sm btn-dark shadow-sm" data-bs-toggle="tooltip"
                                                data-bs-trigger="hover" title="Create Contract">
                                                <i class="bi bi-collection-fill"></i>
                                            </a>
                                        @endif
                                    @endif

                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted fst-italic py-4">
                            <i class="bi bi-folder-x fs-4 d-block mb-1"></i> Belum ada data PO Internal.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
 
        <div class="d-flex justify-content-between align-items-center mt-3 px-2">
            <small class="text-muted">
                Showing {{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }} of {{ $items->total() }} entries
            </small>
            {{ $items->links() }}
        </div>
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

        btnSearch.addEventListener('click', performQuickSearch);
        searchInput.addEventListener('keypress', function(e) {
            if(e.key === 'Enter') { e.preventDefault(); performQuickSearch(); }
        });

        btnSendQC.addEventListener('click', function() {
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
    });
    </script>
@endpush