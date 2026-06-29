{{-- 1. MODAL HEADER --}}
<div class="modal-header bg-primary text-white">
    <h5 class="modal-title text-white" id="exampleModalLabel">
        <i class="bi bi-file-earmark-text-fill me-2"></i>Detail Purchase Order: {{ $po->po_no }}
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

{{-- 2. MODAL BODY --}}
<div class="modal-body">
    
    {{-- ==================== LIVE TRACKING PROGRESS BAR ==================== --}}
    <div class="card bg-light border mb-4">
        <div class="card-body py-3">
            <h6 class="font-weight-bold mb-3 text-dark">
                <i class="bi bi-geo-alt-fill text-danger me-1"></i> Status Pelacakan Logistik & Produksi:
            </h6>
            
            {{-- Logika Penentuan Persentase Dan Warna Bar Berdasarkan Status Riil --}}
            @php
                $progressWidth = '15%';
                $barColor = 'bg-secondary';
                if ($po->status == 'sent') { $progressWidth = '15%'; $barColor = 'bg-info'; }
                elseif (in_array($po->status, ['review', 'amandement'])) { $progressWidth = '40%'; $barColor = 'bg-warning text-dark'; }
                elseif ($po->status == 'contract') { $progressWidth = '65%'; $barColor = 'bg-primary'; }
                elseif ($po->status == 'production') { $progressWidth = '85%'; $barColor = 'bg-danger'; }
                elseif ($po->status == 'ship') { $progressWidth = '100%'; $barColor = 'bg-success'; }
            @endphp

            <div class="progress mb-2" style="height: 25px; border-radius: 6px; overflow: hidden;">
                <div class="progress-bar {{ $barColor }} progress-bar-striped progress-bar-animated font-weight-bold text-center" 
                     role="progressbar" 
                     style="width: {{ $progressWidth }}; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">
                     {{ $po->status }}
                </div>
            </div>

            {{-- Kalimat Penjelasan Status Dinamis --}}
            <div class="bg-white border rounded p-3 mt-3 shadow-sm">
                <div class="d-flex align-items-start">
                    <i class="bi bi-info-circle-fill text-primary me-2 mt-1" style="font-size: 1.1rem;"></i>
                    <div>
                        <strong class="text-dark">Informasi Sistem:</strong>
                        <p class="text-muted small mb-0 mt-1">
                            @if($po->status == 'sent')
                                Dokumen PO baru saja Anda kirim ke sistem. Saat ini sedang menunggu antrean verifikasi awal oleh Admin/Sales internal.
                            @elseif($po->status == 'review')
                                Dokumen sedang dalam peninjauan ketat secara paralel oleh 4 divisi (Sales, PPC, Quality, dan Dev Engineering).
                            @elseif($po->status == 'amandement')
                                Dokumen masuk tahap negosiasi ulang karena adanya pengajuan berkas amandemen/revisi data dari sisi Anda.
                            @elseif($po->status == 'contract')
                                Administrasi & kontrak selesai disahkan! Berkas Anda aman dan pesanan sudah masuk daftar antrean mesin pengecoran pabrik.
                            @elseif($po->status == 'production')
                                <span class="text-danger font-weight-bold">PERINGATAN: Cairan logam sudah mulai dicor di lantai produksi PT. Metinca Prima. Spesifikasi data PO telah DIKUNCI TOTAL demi keselamatan produksi.</span>
                            @elseif($po->status == 'ship')
                                <span class="text-success font-weight-bold">PRODUKSI SELESAI! Produk pengecoran logam Anda telah lolos uji kualitas penuh dan saat ini dalam perjalanan pengiriman ke lokasi Anda.</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== DETAIL RINCIAN DATA DOKUMEN ==================== --}}
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
                                {{ strtoupper($po->status_order) }}
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
                        <td><strong>{{ $po->delivery_request ? \Carbon\Carbon::parse($po->delivery_request)->format('d-m-Y') : '-' }}</strong></td>
                    </tr>
                    <tr>
                        <th class="bg-light text-secondary">Sales PIC Internal</th>
                        <td>
                            <span class="badge bg-light-secondary text-secondary">
                                {{ $po->quotation->request->assignment->sales->name ?? 'Belum Ditugaskan' }}
                            </span>
                        </td>
                    </tr>
                    
                    {{-- PERUBAHAN 1: LOGIKA PENENTUAN NOMOR REVISI AMANDEMEN DARI TABEL KONTRAK --}}
                    <tr>
                        <th class="bg-light text-secondary">Riwayat Referensi</th>
                        <td>
                            @php
                                $latestContract = \App\Models\Contract::where('order_no', $po->po_no)
                                                    ->orderByDesc('amandement_no')
                                                    ->first();
                            @endphp
                            @if($latestContract && $latestContract->amandement_no > 0)
                                <span class="badge bg-warning text-white">Amandemen Ke-{{ $latestContract->amandement_no }}</span>
                            @else
                                <span class="text-muted">Original PO (Belum Diamandemen)</span>
                            @endif
                        </td>
                    </tr>
                    
                    {{-- PERUBAHAN 2: MEMECAH STRING KOMA JADI MULTIPLE LAMPIRAN DOWNLOAD BUTTON --}}
                    <tr>
                        <th class="bg-light text-secondary">Berkas Lampiran</th>
                        <td>
                            @if(!empty($po->attachment))
                                <div class="d-flex flex-column gap-1">
                                    @foreach(explode(',', $po->attachment) as $index => $file)
                                        @if($index == 0)
                                            <a href="{{ asset('storage/uploads/' . trim($file)) }}" target="_blank" class="btn btn-sm btn-info text-white py-1 px-2 mb-1 d-block text-start">
                                                <i class="bi bi-cloud-arrow-down-fill me-1"></i> Lihat PO Original
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/uploads/' . trim($file)) }}" target="_blank" class="btn btn-sm btn-warning text-white py-1 px-2 mb-1 d-block text-start">
                                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Berkas Amandemen {{ $index }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted small">Tidak ada lampiran berkas</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- PERUBAHAN 3: MENAMPILKAN ALASAN DARI FORM AMANDEMEN SECARA REALTIME --}}
    @if($po->reason || $po->notes || ($latestContract && $latestContract->alasan_amandemen))
        <div class="row mt-2">
            <div class="col-12">
                <div class="form-group mb-0">
                    <label class="font-weight-bold text-dark small mb-1">Catatan / Alasan Perubahan Dokumen:</label>
                    <div class="p-2 border rounded bg-light small text-dark italic">
                        "{{ $po->reason ?? ($po->notes ?? ($latestContract->alasan_amandemen ?? '')) }}"
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- 3. MODAL FOOTER --}}
<div class="modal-footer">
    <button type="button" class="btn btn-danger font-weight-bold" data-bs-dismiss="modal">Close</button>
</div>