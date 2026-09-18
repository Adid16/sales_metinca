{{-- 1. MODAL HEADER --}}
<div class="modal-header bg-primary text-white">
    <h5 class="modal-title text-white" id="exampleModalLabel">
        <i class="bi bi-file-earmark-text-fill me-2"></i>Detail Purchase Order: {{ $po->po_no }}
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

{{-- 2. MODAL BODY --}}
<div class="modal-body">

    @php
        $itemsList = $po->internals->count() > 0 ? $po->internals : ($po->quotation?->items ?? collect());
        $isUsingInternals = $po->internals->count() > 0;
        $totalQty = 0;
        $grandTotal = 0;

        $hasSelectedItem = isset($selectedItem) && $selectedItem;
        $activeItemIndex = 1;
        if ($hasSelectedItem) {
            foreach($itemsList as $idx => $it) {
                if ($selectedItem->id == $it->id || (isset($selectedItem->item) && strtolower(trim($selectedItem->item)) === strtolower(trim($it->item)))) {
                    $activeItemIndex = $idx + 1;
                    break;
                }
            }
        }
    @endphp

    {{-- ==================== TOP ITEM SECTION CONTAINER (DYNAMIC PER-ITEM) ==================== --}}
    @if($itemsList->count() > 0)
        <div id="top-item-container">
            @foreach($itemsList as $index => $item)
                @php
                    $itemIdx = $index + 1;
                    $isInitialActive = ($itemIdx === $activeItemIndex);
                    $itemContract = $isUsingInternals ? ($item->contract ?? ($item->contracts ? $item->contracts->sortByDesc('amandement_no')->first() : null)) : null;
                    $targetContract = $itemContract ?? ($isUsingInternals 
                        ? \App\Models\Contract::where('purchase_order_internal_id', $item->id)->orderByDesc('amandement_no')->first()
                        : \App\Models\Contract::where('order_no', $po->po_no)->orderByDesc('amandement_no')->first());
                    $isItemAmandemen = $targetContract && $targetContract->amandement_no > 0;
                    
                    // Active Status Penentuan Per-Item
                    $activeStatus = 'sent';
                    if ($itemContract) {
                        $cStatus = strtolower($itemContract->status);
                        if (in_array($cStatus, ['production', 'done'])) {
                            $activeStatus = 'production';
                        } elseif (in_array($cStatus, ['approved', 'contract'])) {
                            $activeStatus = 'contract';
                        } elseif ($cStatus === 'rejected') {
                            $activeStatus = 'rejected';
                        } elseif ($cStatus === 'amandement_pending') {
                            $activeStatus = 'amandement_pending';
                        } elseif (in_array($cStatus, ['amandement', 'amandement_approved'])) {
                            $activeStatus = 'amandement';
                        } elseif (in_array($cStatus, ['review', 'created', 'revision'])) {
                            $activeStatus = 'review';
                        } else {
                            $activeStatus = $cStatus;
                        }
                    } elseif ($isUsingInternals && isset($item->status)) {
                        $iStatus = strtolower($item->status);
                        if (in_array($iStatus, ['production', 'done'])) {
                            $activeStatus = 'production';
                        } elseif (in_array($iStatus, ['approved', 'contract'])) {
                            $activeStatus = 'contract';
                        } elseif (in_array($iStatus, ['amandement', 'amandement_pending', 'review'])) {
                            $activeStatus = $iStatus;
                        } else {
                            $activeStatus = 'review';
                        }
                    } else {
                        $activeStatus = strtolower($po->status ?? 'sent');
                    }

                    $progressWidth = '20%';
                    $barColor = 'bg-info';
                    $statusLabel = strtoupper($activeStatus);

                    if ($activeStatus == 'sent') { 
                        $progressWidth = '20%'; $barColor = 'bg-info'; $statusLabel = 'SENT';
                    }
                    elseif ($activeStatus == 'amandement_pending') { 
                        $progressWidth = '35%'; $barColor = 'bg-warning text-dark'; $statusLabel = 'REVIEW AMANDEMEN';
                    }
                    elseif ($activeStatus == 'review') { 
                        $progressWidth = '50%'; $barColor = 'bg-warning text-dark'; $statusLabel = 'REVIEW KONTRAK';
                    }
                    elseif ($activeStatus == 'amandement') { 
                        $progressWidth = '65%'; $barColor = 'bg-warning text-dark'; $statusLabel = 'AMANDEMEN DISETUJUI';
                    }
                    elseif ($activeStatus == 'contract') { 
                        $progressWidth = '80%'; $barColor = 'bg-primary'; $statusLabel = 'KONTRAK DISETUJUI';
                    }
                    elseif ($activeStatus == 'production') { 
                        $progressWidth = '100%'; $barColor = 'bg-success'; $statusLabel = 'IN PRODUCTION';
                    }
                    elseif ($activeStatus == 'rejected') { 
                        $progressWidth = '100%'; $barColor = 'bg-danger'; $statusLabel = 'REJECTED';
                    }
                @endphp

                <div id="top-item-view-{{ $itemIdx }}" class="top-item-view {{ $isInitialActive ? '' : 'd-none' }}">
                    {{-- ALERT 1: JIKA AMANDEMEN DITOLAK BY SALES / MANAGER --}}
                    @if($itemContract && $itemContract->status === 'rejected' && $itemContract->alasan_penolakan)
                        <div class="card border border-danger mb-4 p-3 rounded shadow-sm bg-danger-subtle">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-x-circle-fill fs-5 text-danger me-2"></i>
                                <strong class="text-danger">Pengajuan Amandemen Item Ditolak (Item #{{ $itemIdx }})</strong>
                            </div>
                            <div class="text-dark small ms-4">
                                <strong>Alasan Penolakan:</strong> <span class="fw-bold text-danger">{{ $itemContract->alasan_penolakan }}</span>
                            </div>
                            <div class="text-muted extra-small ms-4 mt-1 fst-italic">
                                *Pengajuan amandemen untuk item ini telah dibatalkan. Pesanan (PO) awal Anda tetap berlanjut sesuai kesepakatan sebelumnya.
                            </div>
                        </div>
                    @elseif(!$itemContract && $po->contract && $po->contract->status === 'rejected' && $po->contract->alasan_penolakan)
                        <div class="card border border-danger mb-4 p-3 rounded shadow-sm bg-danger-subtle">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-x-circle-fill fs-5 text-danger me-2"></i>
                                <strong class="text-danger">Pengajuan Amandemen Ditolak</strong>
                            </div>
                            <div class="text-dark small ms-4">
                                <strong>Alasan Penolakan:</strong> <span class="fw-bold text-danger">{{ $po->contract->alasan_penolakan }}</span>
                            </div>
                            <div class="text-muted extra-small ms-4 mt-1 fst-italic">
                                *Pengajuan amandemen telah dibatalkan. Pesanan (PO) awal Anda tetap berlanjut sesuai kesepakatan sebelumnya.
                            </div>
                        </div>
                    @endif

                    {{-- ALERT 2: JIKA MASIH MENUNGGU PERSETUJUAN --}}
                    @if(($itemContract && $itemContract->status === 'amandement_pending') || (!$itemContract && $po->status === 'amandement_pending'))
                        <div class="card border border-warning mb-4 p-3 rounded shadow-sm bg-warning-subtle">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-hourglass-split fs-5 text-warning me-2"></i>
                                <span class="text-dark small">
                                    <strong>Status Amandemen (Item #{{ $itemIdx }}):</strong> Pengajuan amandemen untuk item ini telah diterima dan saat ini sedang dalam antrean verifikasi oleh Sales / Manager.
                                </span>
                            </div>
                        </div>
                    @endif

                    {{-- LIVE TRACKING PROGRESS BAR --}}
                    <div class="card bg-light border mb-4">
                        <div class="card-body py-3">
                            <h6 class="font-weight-bold mb-3 text-dark">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i> Status Pelacakan Logistik & Produksi:
                                <span class="badge bg-primary text-white ms-1 fw-normal" style="font-size: 0.8rem;">Item #{{ $itemIdx }}: {{ $item->item ?? '-' }}</span>
                            </h6>
                            
                            <div class="progress mb-2" style="height: 25px; border-radius: 6px; overflow: hidden;">
                                <div class="progress-bar {{ $barColor }} progress-bar-striped progress-bar-animated font-weight-bold text-center" 
                                     role="progressbar" 
                                     style="width: {{ $progressWidth }}; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">
                                     {{ $statusLabel }}
                                </div>
                            </div>

                            {{-- Kalimat Penjelasan Status Dinamis --}}
                            <div class="bg-white border rounded p-3 mt-3 shadow-sm">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-info-circle-fill text-primary me-2 mt-1" style="font-size: 1.1rem;"></i>
                                    <div>
                                        <strong class="text-dark">Informasi Sistem:</strong>
                                        <p class="text-muted small mb-0 mt-1">
                                            @if($activeStatus == 'sent')
                                                Dokumen PO baru saja Anda kirim ke sistem. Saat ini sedang menunggu antrean verifikasi awal oleh Admin/Sales internal.
                                            @elseif($activeStatus == 'amandement_pending')
                                                Berkas amandemen item baru saja diunggah. Saat ini sedang menunggu verifikasi dan keputusan (Approve/Reject) dari tim Sales / Manager.
                                            @elseif($activeStatus == 'review')
                                                Dokumen kontrak item sedang dalam peninjauan ketat secara paralel oleh 4 divisi (Sales, PPC, Quality, dan Dev Engineering).
                                            @elseif($activeStatus == 'amandement')
                                                Amandemen item disetujui! Dokumen resmi diperbarui di sistem PO External dan siap dilanjutkan ke alur produksi.
                                            @elseif($activeStatus == 'contract')
                                                Administrasi & kontrak item selesai disahkan! Berkas Anda aman dan pesanan sudah masuk daftar antrean mesin pengecoran pabrik.
                                            @elseif($activeStatus == 'production')
                                                <span class="text-success font-weight-bold">PRODUKSI BERJALAN: Seluruh tinjauan kontrak telah disahkan dan pesanan saat ini sedang dalam proses pengerjaan di lantai produksi PT. Metinca Prima Industrial Works. Data PO telah dikunci demi keselamatan produksi.</span>
                                            @elseif($activeStatus == 'rejected')
                                                <span class="text-danger font-weight-bold">Pengajuan amandemen untuk item ini ditolak. Pesanan/Kontrak sebelumnya tetap berlanjut sesuai kesepakatan.</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DETAIL RINCIAN DATA DOKUMEN --}}
                    <h6 class="font-weight-bold mb-3 text-dark"><i class="bi bi-card-list me-1 text-primary"></i> Informasi Dokumen:</h6>
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered small">
                                    <tr>
                                        <th class="bg-light text-secondary" width="40%">Nomor PO</th>
                                        <td><strong>{{ $po->po_no }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-secondary">No. Quotation Asal</th>
                                        <td>{{ $po->quotation->quotation_no ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-secondary">Nama Customer</th>
                                        <td>{{ $po->customer->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-secondary">Jenis Urgensi Order</th>
                                        <td>
                                            <span class="badge {{ $po->status_order == 'urgent' ? 'bg-light-danger text-danger' : 'bg-light-success text-success' }}">
                                                {{ strtoupper($po->status_order ?? 'REGULAR') }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered small">
                                    <tr>
                                        <th class="bg-light text-secondary" width="40%">Delivery Request</th>
                                        <td>
                                            <strong>
                                                @if(!empty($item->delivery_date))
                                                    {{ $item->delivery_date instanceof \Carbon\Carbon ? $item->delivery_date->format('d-m-Y') : \Carbon\Carbon::parse($item->delivery_date)->format('d-m-Y') }}
                                                @elseif($po->delivery_request)
                                                    {{ \Carbon\Carbon::parse($po->delivery_request)->format('d-m-Y') }}
                                                @else
                                                    -
                                                @endif
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-secondary">Sales PIC Internal</th>
                                        <td>
                                            <span class="badge bg-light-secondary text-secondary">
                                                {{ $po->quotation->request->assignment->sales->name ?? ($po->salesPic->name ?? 'Belum Ditugaskan') }}
                                            </span>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <th class="bg-light text-secondary">Riwayat Referensi</th>
                                        <td>
                                            @if($isItemAmandemen)
                                                <span class="badge bg-warning text-white">Amandemen Ke-{{ $targetContract->amandement_no }}</span>
                                            @else
                                                <span class="text-muted">Original PO (Belum Diamandemen)</span>
                                            @endif
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <th class="bg-light text-secondary">Berkas Lampiran</th>
                                        <td>
                                            @php
                                                $poFiles = !empty($po->attachment) ? explode(',', $po->attachment) : [];
                                                $originalFile = count($poFiles) > 0 ? trim($poFiles[0]) : null;
                                                $itemAmandementFile = ($targetContract && !empty($targetContract->po_pdf)) ? $targetContract->po_pdf : null;
                                            @endphp

                                            <div class="d-flex flex-column gap-1">
                                                {{-- 1. Tombol PO Original (Selalu tampil jika ada berkas awal) --}}
                                                @if($originalFile)
                                                    @php
                                                        $origPath = str_starts_with($originalFile, 'uploads/') || str_starts_with($originalFile, 'contracts/')
                                                            ? asset('storage/' . $originalFile)
                                                            : asset('storage/uploads/' . $originalFile);
                                                    @endphp
                                                    <a href="{{ $origPath }}" target="_blank" class="btn btn-sm btn-info text-white py-1 px-2 mb-1 d-block text-start">
                                                        <i class="bi bi-cloud-arrow-down-fill me-1"></i> Lihat PO Original
                                                    </a>
                                                @endif

                                                {{-- 2. Tombol Berkas Amandemen (HANYA MUNCUL JIKA ITEM INI MEMANG SUDAH DIAMANDEMEN) --}}
                                                @if($isItemAmandemen && $itemAmandementFile)
                                                    @php
                                                        $amdPath = str_starts_with($itemAmandementFile, 'uploads/') || str_starts_with($itemAmandementFile, 'contracts/')
                                                            ? asset('storage/' . $itemAmandementFile)
                                                            : asset('storage/uploads/' . $itemAmandementFile);
                                                    @endphp
                                                    <a href="{{ $amdPath }}" target="_blank" class="btn btn-sm btn-warning text-white py-1 px-2 mb-1 d-block text-start">
                                                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Berkas Amandemen {{ $targetContract->amandement_no }}
                                                    </a>
                                                @endif

                                                @if(!$originalFile && (!$isItemAmandemen || !$itemAmandementFile))
                                                    <span class="text-muted small">Tidak ada lampiran berkas</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- MENAMPILKAN ALASAN DARI FORM AMANDEMEN SECARA REALTIME KHUSUS ITEM INI --}}
                    @if($isItemAmandemen && ($targetContract->alasan_amandemen || $po->reason || $po->notes))
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold text-dark small mb-1">Catatan / Alasan Perubahan Dokumen (Customer):</label>
                                    <div class="p-2 border rounded bg-light small text-dark fst-italic">
                                        "{{ $targetContract->alasan_amandemen ?? ($po->reason ?? ($po->notes ?? '')) }}"
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- MENAMPILKAN ALASAN PENOLAKAN DARI SALES / MANAJEMEN BILA STATUS SEDANG DITOLAK / REVISI --}}
                    @if(!empty($targetContract->alasan_penolakan) && in_array($targetContract->status, ['rejected', 'revision']))
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold text-danger small mb-1"><i class="bi bi-x-octagon-fill me-1"></i> Catatan Penolakan / Permintaan Revisi (Sales / Manajemen):</label>
                                    <div class="p-2 border border-danger rounded bg-light-danger small text-danger fw-semibold">
                                        "{{ $targetContract->alasan_penolakan }}"
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="card bg-light border mb-4">
            <div class="card-body py-3">
                <h6 class="font-weight-bold mb-2 text-dark">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> Status Pelacakan:
                    <span class="badge bg-secondary text-white ms-1">{{ strtoupper($po->status ?? 'SENT') }}</span>
                </h6>
                <p class="text-muted small mb-0">Tidak ada item terdaftar dalam Purchase Order ini.</p>
            </div>
        </div>
    @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle small mb-0">
                <thead class="table-light">
                    <tr class="text-secondary text-uppercase" style="font-size: 0.75rem;">
                        <th class="text-center" style="width: 35px;">#</th>
                        <th>Nama Item</th>
                        <th>Article / Part No</th>
                        <th class="text-center" style="width: 70px;">Qty</th>
                        <th class="text-end" style="width: 120px;">Harga Satuan</th>
                        <th class="text-end" style="width: 130px;">Subtotal</th>
                        <th class="text-center" style="width: 140px;">Status Review</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($itemsList as $index => $item)
                        @php
                            $rowIdx = $index + 1;
                            $isHighlight = ($rowIdx === $activeItemIndex);
                            $itemName = $item->item ?? '-';
                            
                            if ($item->article instanceof \App\Models\Article) {
                                $articleCode = $item->article->article_no ?? ($item->article->internal_part_no ?? '-');
                                $partNo = $item->contract->part_no ?? ($item->part_no ?? ($item->article->internal_part_no ?? '-'));
                            } elseif (is_string($item->article) && !empty($item->article)) {
                                $articleCode = $item->article;
                                $partNo = $item->contract->part_no ?? ($item->part_no ?? '-');
                            } else {
                                $articleCode = $item->article_no ?? ($item->part_no ?? '-');
                                $partNo = $item->contract->part_no ?? ($item->part_no ?? '-');
                            }
                            
                            $qty = (int)($item->qty ?? 1);
                            $unitPrice = (float)($item->unit_price ?? $item->price ?? $item->negotiated_price ?? $item->original_price ?? 0);
                            $subtotal = (float)($item->subtotal ?? ($qty * $unitPrice));
                            $totalQty += $qty;
                            $grandTotal += $subtotal;

                            $itemContract = $isUsingInternals ? ($item->contract ?? ($item->contracts ? $item->contracts->sortByDesc('amandement_no')->first() : null)) : null;

                            if ($itemContract && in_array($itemContract->status, ['production'])) {
                                $displayStatus = 'In Production';
                                $badgeClass = 'bg-success text-white';
                            } elseif ($itemContract && in_array($itemContract->status, ['done'])) {
                                $displayStatus = 'Done';
                                $badgeClass = 'bg-primary text-white';
                            } elseif ($itemContract && in_array($itemContract->status, ['approved', 'contract'])) {
                                $displayStatus = 'Kontrak Disetujui';
                                $badgeClass = 'bg-primary text-white';
                            } elseif ($itemContract && $itemContract->status === 'rejected') {
                                $displayStatus = 'Amandemen Ditolak';
                                $badgeClass = 'bg-danger text-white';
                            } elseif ($itemContract && $itemContract->status === 'amandement_pending') {
                                $displayStatus = 'Review Amandemen';
                                $badgeClass = 'bg-warning text-dark';
                            } elseif ($itemContract && in_array($itemContract->status, ['amandement', 'amandement_approved'])) {
                                $displayStatus = 'Amandemen Disetujui';
                                $badgeClass = 'bg-success text-white';
                            } elseif ($itemContract && in_array($itemContract->status, ['review', 'created', 'revision'])) {
                                $displayStatus = 'Review Kontrak';
                                $badgeClass = 'bg-warning text-dark';
                            } else {
                                $displayStatus = $isUsingInternals ? ucfirst($po->status) : 'Menunggu Sales';
                                $badgeClass = ($po->status == 'sent') ? 'bg-info text-white' : 'bg-secondary text-white';
                            }
                        @endphp
                        <tr id="po-item-summary-row-{{ $rowIdx }}" 
                            class="po-summary-row {{ $isHighlight ? 'table-primary border-primary fw-bold active-item-row' : '' }}" 
                            style="cursor: pointer;"
                            onclick="selectPoItemTab({{ $rowIdx }})"
                            title="Klik untuk melihat rincian spesifikasi Item #{{ $rowIdx }}">
                            <td class="text-center">{{ $rowIdx }}</td>
                            <td>
                                <span class="fw-semibold item-name-text text-dark">{{ $itemName }}</span>
                                <span class="badge bg-primary ms-1 badge-selected {{ $isHighlight ? '' : 'd-none' }}" style="font-size: 0.68rem;">Dipilih</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $articleCode }}</span>
                                @if($partNo && $partNo !== '-' && $partNo !== $articleCode)
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Part: {{ $partNo }}</small>
                                @endif
                            </td>
                            <td class="text-center fw-bold">{{ number_format($qty) }}</td>
                            <td class="text-end text-success fw-semibold">Rp {{ number_format($unitPrice, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold text-dark">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="badge {{ $badgeClass }} px-2 py-1" style="font-size: 0.72rem;">{{ $displayStatus }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">Belum ada data item.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($itemsList->count() > 0)
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="3" class="text-end text-uppercase text-secondary" style="font-size: 0.75rem;">Total Keseluruhan Pesanan:</td>
                            <td class="text-center text-primary fs-6">{{ number_format($totalQty) }} Pcs</td>
                            <td class="text-end text-muted">-</td>
                            <td class="text-end text-primary fs-6">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                            <td class="text-center"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 2. DETAIL LENGKAP SPESIFIKASI & AMANDEMEN PER-ITEM -->
    <!-- ========================================== -->
    <div class="mt-4 pt-3 border-top">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-card-checklist me-2 text-primary"></i>
                    Rincian Spesifikasi & Verifikasi Item Pesanan
                </h6>
                <small class="text-muted" id="detail-section-subtitle">
                    Pilih tab di bawah untuk melihat rincian spesifikasi, status verifikasi 4 divisi, dan pengajuan amandemen per-item.
                </small>
            </div>
        </div>

        @if($itemsList->count() > 1)
            <ul class="nav nav-pills gap-2 mb-3" id="poItemPills" role="tablist">
                @foreach($itemsList as $index => $item)
                    @php
                        $tabIdx = $index + 1;
                        $isTabActive = ($tabIdx === $activeItemIndex);
                        $pillItemName = $item->item ?? ('Item #' . $tabIdx);
                    @endphp
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-1 px-3 fw-semibold po-item-tab-btn {{ $isTabActive ? 'active' : '' }}" 
                                id="pills-tab-item-{{ $tabIdx }}" 
                                data-item-index="{{ $tabIdx }}"
                                data-bs-toggle="pill" 
                                data-bs-target="#pills-item-pane-{{ $tabIdx }}" 
                                type="button" 
                                role="tab"
                                onclick="syncPoItemSelection({{ $tabIdx }})">
                            <i class="bi bi-box-seam me-1"></i> #{{ $tabIdx }}: {{ Str::limit($pillItemName, 22) }}
                        </button>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="tab-content" id="poItemPillsContent">
            @foreach($itemsList as $index => $item)
                @php
                    $tabIdx = $index + 1;
                    $isTabActive = ($tabIdx === $activeItemIndex);
                    $isMatchSelected = ($tabIdx === $activeItemIndex);

                    $itemName = $item->item ?? '-';
                    if ($item->article instanceof \App\Models\Article) {
                        $articleCode = $item->article->article_no ?? ($item->article->internal_part_no ?? '-');
                        $partNo = $item->contract->part_no ?? ($item->part_no ?? ($item->article->internal_part_no ?? '-'));
                        $material = $item->material ?? ($item->article->material ?? '-');
                    } elseif (is_string($item->article) && !empty($item->article)) {
                        $articleCode = $item->article;
                        $partNo = $item->contract->part_no ?? ($item->part_no ?? '-');
                        $material = $item->material ?? '-';
                    } else {
                        $articleCode = $item->article_no ?? ($item->part_no ?? '-');
                        $partNo = $item->contract->part_no ?? ($item->part_no ?? '-');
                        $material = $item->material ?? '-';
                    }
                    
                    $spesifikasi = $item->spesifikasi ?? '';
                    if (empty($spesifikasi) && $item->article instanceof \App\Models\Article) {
                        $specsArr = [];
                        if (!empty($item->article->drawing_no)) {
                            $specsArr[] = 'Dwg: ' . $item->article->drawing_no . (!empty($item->article->drawing_rev) ? ' Rev: ' . $item->article->drawing_rev : '');
                        }
                        if (!empty($item->article->berat)) {
                            $specsArr[] = 'Berat: ' . $item->article->berat . ' Kg';
                        }
                        if (!empty($item->article->remark)) {
                            $specsArr[] = 'Remark: ' . $item->article->remark;
                        }
                        $spesifikasi = implode(' | ', $specsArr);
                    }

                    $qty = (int)($item->qty ?? 1);
                    $unitPrice = (float)($item->unit_price ?? $item->price ?? $item->negotiated_price ?? $item->original_price ?? 0);
                    $subtotal = (float)($item->subtotal ?? ($qty * $unitPrice));

                    $deliveryDateStr = '-';
                    if (!empty($item->delivery_date)) {
                        $deliveryDateStr = $item->delivery_date instanceof \Carbon\Carbon ? $item->delivery_date->format('d M Y') : \Carbon\Carbon::parse($item->delivery_date)->format('d M Y');
                    } elseif (!empty($po->delivery_request)) {
                        $deliveryDateStr = \Carbon\Carbon::parse($po->delivery_request)->format('d M Y');
                    }

                    $itemContract = $isUsingInternals ? ($item->contract ?? ($item->contracts ? $item->contracts->sortByDesc('amandement_no')->first() : null)) : null;
                    $itemAmendCount = $isUsingInternals ? \App\Models\Contract::where('purchase_order_internal_id', $item->id)->where('amandement_no', '>', 0)->count() : 0;
                    if ($itemContract && $itemContract->amandement_no > $itemAmendCount) {
                        $itemAmendCount = $itemContract->amandement_no;
                    }

                    if ($itemContract && in_array($itemContract->status, ['production'])) {
                        $displayStatus = 'In Production';
                        $badgeClass = 'bg-success text-white';
                    } elseif ($itemContract && in_array($itemContract->status, ['done'])) {
                        $displayStatus = 'Done';
                        $badgeClass = 'bg-primary text-white';
                    } elseif ($itemContract && in_array($itemContract->status, ['approved', 'contract'])) {
                        $displayStatus = 'Kontrak Disetujui';
                        $badgeClass = 'bg-primary text-white';
                    } elseif ($itemContract && $itemContract->status === 'rejected') {
                        $displayStatus = 'Amandemen Ditolak';
                        $badgeClass = 'bg-danger text-white';
                    } elseif ($itemContract && $itemContract->status === 'amandement_pending') {
                        $displayStatus = 'Review Amandemen';
                        $badgeClass = 'bg-warning text-dark';
                    } elseif ($itemContract && in_array($itemContract->status, ['amandement', 'amandement_approved'])) {
                        $displayStatus = 'Amandemen Disetujui';
                        $badgeClass = 'bg-success text-white';
                    } elseif ($itemContract && in_array($itemContract->status, ['review', 'created', 'revision'])) {
                        $displayStatus = 'Review Kontrak (4 Divisi)';
                        $badgeClass = 'bg-warning text-dark';
                    } else {
                        $displayStatus = $isUsingInternals ? ucfirst($po->status) : 'Menunggu PO Internal';
                        $badgeClass = ($po->status == 'sent') ? 'bg-info text-white' : 'bg-secondary text-white';
                    }

                    $isItemAmended = $itemContract && $itemContract->amandement_no > 0;
                @endphp

                <div class="tab-pane fade {{ $isTabActive ? 'show active' : '' }}" id="pills-item-pane-{{ $tabIdx }}" role="tabpanel">
                    <div class="card border border-primary shadow-sm rounded mb-0">
                        <div class="card-header py-2 d-flex justify-content-between align-items-center flex-wrap gap-2 bg-light text-dark border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary text-white fw-bold">Item #{{ $tabIdx }}</span>
                                <span class="fw-bold fs-6">{{ $itemName }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <span class="badge {{ $badgeClass }} px-2 py-1">{{ $displayStatus }}</span>
                                @if($isItemAmended)
                                    <span class="badge bg-warning text-dark border"><i class="bi bi-pencil-square me-1"></i>Amandemen Ke-{{ $itemContract->amandement_no }} ({{ $itemAmendCount }}/2)</span>
                                @else
                                    <span class="badge bg-light text-secondary border">Original (0/2)</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                {{-- Kolom Kiri: Spesifikasi & Komersial (Fokus Customer) --}}
                                @php
                                    $articleObj = $itemContract?->article ?? ($item->article instanceof \App\Models\Article ? $item->article : ($item->articleModel ?? (\App\Models\Article::where('article_no', $articleCode)->first())));
                                    $matText = $articleObj?->material ?? ($item->material ?? '-');
                                    $dwgText = $articleObj?->drawing_no ? ($articleObj->drawing_no . (!empty($articleObj->drawing_rev) ? ' (Rev: ' . $articleObj->drawing_rev . ')' : '')) : ($spesifikasi ?: null);
                                @endphp
                                <div class="col-md-6 border-end">
                                    <h6 class="text-secondary fw-bold small mb-2"><i class="bi bi-box-seam me-1 text-primary"></i> Rincian Pesanan:</h6>
                                    <table class="table table-sm table-borderless small mb-0">
                                        <tr>
                                            <td class="text-muted" style="width: 140px;">Nama Item</td>
                                            <td><strong class="text-dark">{{ $itemName }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Part No</td>
                                            <td><span class="badge bg-light text-dark border">{{ $partNo }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Material / Bahan</td>
                                            <td><span class="fw-semibold text-dark">{{ $matText }}</span></td>
                                        </tr>
                                        @if($dwgText && $dwgText !== '-')
                                            <tr>
                                                <td class="text-muted">Drawing & Rev</td>
                                                <td><span class="text-secondary">{{ $dwgText }}</span></td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="text-muted">Target Kirim</td>
                                            <td><span class="fw-semibold text-danger"><i class="bi bi-calendar-event me-1"></i>{{ $deliveryDateStr }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Jumlah Pesanan</td>
                                            <td><span class="fw-bold fs-6 text-dark">{{ number_format($qty) }} Pcs</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Harga Satuan</td>
                                            <td><span class="fw-bold text-success">Rp {{ number_format($unitPrice, 0, ',', '.') }}</span> <small class="text-muted">(Kesepakatan)</small></td>
                                        </tr>
                                        <tr class="border-top">
                                            <td class="text-muted fw-bold">Subtotal</td>
                                            <td><span class="fw-bold fs-6 text-primary">Rp {{ number_format($subtotal, 0, ',', '.') }}</span></td>
                                        </tr>
                                    </table>
                                </div>

                                {{-- Kolom Kanan: Status Kontrak, Alasan Amandemen & Aksi --}}
                                <div class="col-md-6 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="text-secondary fw-bold small mb-0"><i class="bi bi-journal-check me-1 text-primary"></i> Verifikasi Tinjauan Kontrak:</h6>
                                            @if($itemContract && $itemContract->contract_no)
                                                <span class="badge bg-light-primary text-primary border border-primary small" style="font-size: 0.72rem;">
                                                    <i class="bi bi-file-earmark-spreadsheet me-1"></i>{{ $itemContract->contract_no }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        {{-- Status Verifikasi 4 Divisi --}}
                                        <div class="p-2 rounded bg-light border mb-2 small">
                                            <div class="text-muted fw-semibold mb-1" style="font-size: 0.72rem;">Verifikasi 4 Divisi (Pabrik Metinca):</div>
                                            <div class="d-flex flex-wrap gap-1">
                                                @if($itemContract && $itemContract->sales_approver)
                                                    <span class="badge bg-success text-white" style="font-size: 0.68rem;" title="Approved: {{ $itemContract->sales_approved_at }}">
                                                        <i class="bi bi-check-circle-fill me-1"></i>Sales: Ok
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark" style="font-size: 0.68rem;"><i class="bi bi-clock me-1"></i>Sales: Review</span>
                                                @endif

                                                @if($itemContract && $itemContract->quality_approver)
                                                    <span class="badge bg-success text-white" style="font-size: 0.68rem;" title="Approved: {{ $itemContract->quality_approved_at }}">
                                                        <i class="bi bi-check-circle-fill me-1"></i>Quality: Ok
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark" style="font-size: 0.68rem;"><i class="bi bi-clock me-1"></i>Quality: Review</span>
                                                @endif

                                                @if($itemContract && $itemContract->ppc_approver)
                                                    <span class="badge bg-success text-white" style="font-size: 0.68rem;" title="Approved: {{ $itemContract->ppc_approved_at }}">
                                                        <i class="bi bi-check-circle-fill me-1"></i>PPC: Ok
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark" style="font-size: 0.68rem;"><i class="bi bi-clock me-1"></i>PPC: Review</span>
                                                @endif

                                                @if($itemContract && $itemContract->dev_engineering_approver)
                                                    <span class="badge bg-success text-white" style="font-size: 0.68rem;" title="Approved: {{ $itemContract->dev_engineering_approved_at }}">
                                                        <i class="bi bi-check-circle-fill me-1"></i>DE: Ok
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark" style="font-size: 0.68rem;"><i class="bi bi-clock me-1"></i>DE: Review</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-1 small border-bottom pb-1">
                                            <span class="text-muted">Status Produksi:</span>
                                            <span class="badge {{ $badgeClass }}">{{ $displayStatus }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2 small border-bottom pb-1">
                                            <span class="text-muted">Kuota Amandemen Item:</span>
                                            <span class="fw-bold {{ $itemAmendCount >= 2 ? 'text-danger' : 'text-success' }}">{{ $itemAmendCount }} dari 2 digunakan</span>
                                        </div>

                                        {{-- Catatan Khusus & Standar Spesifikasi Mutu (Quality / PPC) Bila Ada --}}
                                        @if($itemContract && $itemContract->requirements && $itemContract->requirements->count() > 0)
                                            @php
                                                $technicalRequirements = $itemContract->requirements->filter(function($req) {
                                                    $reqName = strtolower(trim($req->requirement ?? ''));
                                                    $val = trim($req->requirement_value ?? '');
                                                    if (empty($val) || in_array(strtolower($val), ['-', 'ok', 'ada', 'good', 'sd'])) return false;
                                                    return !in_array($reqName, [
                                                        'price', 'quantity', 'delivery required', 'delivery_required', 
                                                        'target delivery date', 'unit price', 'supply condition', 'supply_condition',
                                                        'dies', 'tool', 'fixtures', 'pattern wax', 'master job card', 'wra / wi', 'sub contracting', 'purchasing'
                                                    ]);
                                                });
                                            @endphp
                                            @if($technicalRequirements->count() > 0)
                                                <div class="mb-2 p-2 rounded bg-light border" style="font-size: 0.72rem;">
                                                    <div class="fw-semibold text-secondary mb-1"><i class="bi bi-shield-check me-1 text-success"></i>Ketentuan Mutu / Standar Spesifikasi:</div>
                                                    <ul class="mb-0 ps-3 text-muted">
                                                        @foreach($technicalRequirements->take(4) as $req)
                                                            <li><strong>{{ strtoupper($req->requirement_from) }}:</strong> {{ $req->requirement }} ({{ $req->requirement_value }})</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        @endif

                                        {{-- Alasan Pengajuan Amandemen (Customer) --}}
                                        @if($isItemAmended && !empty($itemContract->alasan_amandemen))
                                            <div class="p-2 rounded bg-light-warning border border-warning mb-2 small">
                                                <div class="fw-bold text-dark mb-1">
                                                    <i class="bi bi-chat-left-quote-fill text-warning me-1"></i>Alasan Pengajuan Amandemen:
                                                </div>
                                                <div class="text-dark fst-italic">"{{ $itemContract->alasan_amandemen }}"</div>
                                                
                                                @if(!empty($itemContract->po_pdf))
                                                    @php
                                                        $pdfPath = str_starts_with($itemContract->po_pdf, 'uploads/') || str_starts_with($itemContract->po_pdf, 'contracts/')
                                                            ? asset('storage/' . $itemContract->po_pdf)
                                                            : asset('storage/uploads/' . $itemContract->po_pdf);
                                                    @endphp
                                                    <div class="mt-2">
                                                        <a href="{{ $pdfPath }}" target="_blank" class="btn btn-xs btn-warning text-dark fw-semibold py-1 px-2">
                                                            <i class="bi bi-file-earmark-pdf-fill me-1 text-danger"></i>Lihat Berkas Amandemen Item #{{ $itemContract->amandement_no }}
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Alasan Penolakan / Revisi Bila Status Sedang Ditolak / Revisi --}}
                                        @if(!empty($itemContract->alasan_penolakan) && in_array($itemContract->status, ['rejected', 'revision']))
                                            <div class="p-2 rounded bg-light-danger border border-danger mb-2 small text-danger">
                                                <div class="fw-bold mb-1"><i class="bi bi-x-octagon-fill me-1"></i>Catatan Penolakan / Permintaan Revisi:</div>
                                                <div>"{{ $itemContract->alasan_penolakan }}"</div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Link Tinjauan Kontrak Resmi Untuk Sales/Internal --}}
                                    @if($itemContract && Auth::check() && !Auth::user()->isCustomer())
                                        <div class="mb-2">
                                            <a href="{{ route('contracts.show', $itemContract->id) }}" target="_blank" class="btn btn-xs btn-outline-info text-dark fw-semibold py-1 px-2 d-inline-block">
                                                <i class="bi bi-file-earmark-spreadsheet me-1"></i>Buka Lembar Tinjauan Kontrak (CRS)
                                            </a>
                                        </div>
                                    @endif

                                    {{-- Action Button Untuk Customer --}}
                                    @if(Auth::check() && Auth::user()->role === 'customer')
                                        <div class="pt-2 border-top d-flex justify-content-end align-items-center gap-2">
                                            @php
                                                $isThisItemProd = ($itemContract && in_array(strtolower($itemContract->status ?? ''), ['production', 'done'])) 
                                                    || ($item && in_array(strtolower($item->status ?? ''), ['production', 'done']));
                                            @endphp
                                            @if($isThisItemProd)
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    <i class="bi bi-lock-fill me-1 text-warning"></i> In Production (Terkunci)
                                                </button>
                                            @elseif($itemContract && $itemContract->status === 'amandement_pending')
                                                <button class="btn btn-sm btn-warning text-dark" disabled>
                                                    <i class="bi bi-hourglass-split me-1"></i> Sedang Ditinjau Sales & Manager
                                                </button>
                                            @elseif($itemContract && in_array($itemContract->status, ['amandement', 'amandement_approved']))
                                                <button class="btn btn-sm btn-success text-white" disabled>
                                                    <i class="bi bi-check-circle-fill me-1"></i> Amandemen Disetujui (Diproses)
                                                </button>
                                            @elseif($itemAmendCount >= 2)
                                                <button class="btn btn-sm btn-secondary" disabled title="Batas maksimal 2x amandemen telah tercapai">
                                                    <i class="bi bi-slash-circle me-1 text-danger"></i> Batas Maksimal Amandemen (2/2)
                                                </button>
                                            @elseif(!$isUsingInternals || !$itemContract)
                                                <button class="btn btn-sm btn-light border text-muted" disabled>
                                                    <i class="bi bi-clock-history me-1"></i> Menunggu Pembuatan Kontrak Sales
                                                </button>
                                            @else
                                                <a href="{{ route('purchase-orders.create-amandement', ['id' => $po->id, 'internal_id' => $item->id]) }}" 
                                                   class="btn btn-sm btn-warning text-dark fw-bold px-3 py-2 shadow-sm">
                                                    <i class="bi bi-pencil-square me-1"></i> Ajukan Amandemen Item Ini (Ke-{{ $itemAmendCount + 1 }}/2)
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        window.syncPoItemSelection = function(index) {
            // 1. Update Top Item Section (Progress bar, system info, document details, amendment notes)
            document.querySelectorAll('.top-item-view').forEach(function(el) {
                el.classList.add('d-none');
            });
            const targetTop = document.getElementById('top-item-view-' + index);
            if (targetTop) {
                targetTop.classList.remove('d-none');
            }

            // 2. Update Table Summary Rows
            document.querySelectorAll('.po-summary-row').forEach(function(row) {
                row.classList.remove('table-primary', 'border-primary', 'fw-bold', 'active-item-row', 'table-warning', 'border-warning');
                const badge = row.querySelector('.badge-selected');
                if (badge) badge.classList.add('d-none');
            });

            const targetRow = document.getElementById('po-item-summary-row-' + index);
            if (targetRow) {
                targetRow.classList.add('table-primary', 'border-primary', 'fw-bold', 'active-item-row');
                const badge = targetRow.querySelector('.badge-selected');
                if (badge) badge.classList.remove('d-none');
            }
        };

        window.selectPoItemTab = function(index) {
            const triggerEl = document.getElementById('pills-tab-item-' + index);
            if (triggerEl) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
                    const tab = bootstrap.Tab.getOrCreateInstance(triggerEl);
                    tab.show();
                } else {
                    triggerEl.click();
                }
            }
            window.syncPoItemSelection(index);
            const paneEl = document.getElementById('pills-item-pane-' + index);
            if (paneEl) {
                paneEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        };

        (function() {
            const pillsContainer = document.getElementById('poItemPills');
            if (pillsContainer) {
                pillsContainer.addEventListener('shown.bs.tab', function(event) {
                    const btn = event.target;
                    const idx = btn ? btn.getAttribute('data-item-index') : null;
                    if (idx) {
                        window.syncPoItemSelection(idx);
                    }
                });
            }
        })();
    </script>

</div>

{{-- 3. MODAL FOOTER --}}
<div class="modal-footer">
    <button type="button" class="btn btn-danger font-weight-bold" data-bs-dismiss="modal">Close</button>
</div>