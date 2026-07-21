@extends('layouts.app')

@section('title', 'Persetujuan Amandemen PO - PT. Metinca Prima Industrial Works')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title mb-3">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Persetujuan Amandemen PO</h3>
                <p class="text-subtitle text-muted">Review pengajuan amandemen item per-item dari customer secara teliti.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible show fade">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="section">
        <div class="card">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="card-title text-white mb-0"><i class="bi bi-patch-check-fill me-2"></i>Daftar Antrean Amandemen PO</h5>
            </div>
            <div class="card-body mt-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="15%">No. PO</th>
                                <th width="20%">Customer</th>
                                <th width="35%">Target Item & Alasan Amandemen</th>
                                <th width="15%" class="text-center">Tanggal Diajukan</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pos as $index => $po)
                                @php
                                    // Cari kontrak yang punya pengajuan amandemen
                                    $targetContract = $po->contracts->whereNotNull('alasan_amandemen')->sortByDesc('updated_at')->first() ?? $po->contract;
                                    $targetInternalId = $targetContract->purchase_order_internal_id ?? null;
                                    $targetItem = $po->internals->where('id', $targetInternalId)->first();
                                    $reason = $targetContract->alasan_amandemen ?? $po->reason ?? 'Pengajuan amandemen item.';
                                @endphp
                                <tr>
                                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                    <td>
                                        <strong class="text-primary">{{ $po->po_no }}</strong>
                                        <br><small class="text-muted">ID: #{{ $po->id }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $po->customer->name ?? 'Customer' }}</strong>
                                    </td>
                                    <td>
                                        @if($targetItem)
                                            <span class="badge bg-warning text-dark mb-1"><i class="bi bi-box-seam me-1"></i>{{ $targetItem->item }}</span>
                                            <br>
                                        @endif
                                        <span class="text-dark fst-italic">"{{ Str::limit($reason, 50) }}"</span>
                                    </td>
                                    <td class="text-center small">
                                        {{ $po->updated_at ? $po->updated_at->format('d-m-Y H:i') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm px-3 font-weight-bold" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#reviewModal{{ $po->id }}">
                                            <i class="bi bi-eye-fill me-1"></i> Review Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-check2-circle fs-3 d-block mb-2 text-success"></i>
                                        Tidak ada antrean pengajuan amandemen saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- MODAL REVIEW DETAIL & AKSI APPROVE/REJECT --}}
@foreach($pos as $po)
    @php
        // Mengidentifikasi item spesifik yang sedang dimohonkan amandemennya
        $targetContract = $po->contracts->whereNotNull('alasan_amandemen')->sortByDesc('updated_at')->first() ?? $po->contract;
        $targetInternalId = $targetContract->purchase_order_internal_id ?? null;
        $targetItem = $po->internals->where('id', $targetInternalId)->first();
        $alasanLengkap = $targetContract->alasan_amandemen ?? $po->reason ?? 'Tidak ada catatan alasan.';
    @endphp
    
    <div class="modal fade" id="reviewModal{{ $po->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                {{-- MODAL HEADER --}}
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-file-earmark-check-fill text-warning me-2"></i>Review Amandemen PO No: {{ $po->po_no }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- MODAL BODY --}}
                <div class="modal-body">
                    {{-- 1. INFORMASI UTAMA & ITEM TARGET --}}
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block">Nama Customer:</small>
                                <strong>{{ $po->customer->name ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block">No. Quotation Asal:</small>
                                <strong class="text-primary">{{ $po->quotation->quotation_no ?? '-' }}</strong>
                            </div>
                        </div>
                    </div>

                    {{-- 2. HIGHLIGHT ITEM SPESIFIK YANG DIAMANDEMEN --}}
                    @if($targetItem)
                        <div class="alert alert-light-warning border border-warning mb-3 p-3 rounded">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-exclamation-diamond-fill text-warning fs-5 me-2"></i>
                                <strong class="text-dark">Target Item Yang Diamandemen:</strong>
                            </div>
                            <div class="ms-4 small text-dark">
                                <div><strong>Nama Item:</strong> {{ $targetItem->item }}</div>
                                <div><strong>Part No / Kontrak:</strong> <code>{{ $targetContract->part_no ?? $targetContract->contract_no ?? '-' }}</code></div>
                                <div><strong>Kuantitas PO:</strong> {{ number_format($targetItem->qty) }} pcs</div>
                            </div>
                        </div>
                    @endif

                    {{-- 3. BOX ALASAN AMANDEMEN CUSTOMER --}}
                    <div class="alert alert-warning border border-warning mb-3">
                        <h6 class="fw-bold text-dark mb-1"><i class="bi bi-chat-left-text-fill me-1"></i> Alasan Amandemen dari Customer:</h6>
                        <p class="mb-0 text-dark fst-italic fs-6">"{{ $alasanLengkap }}"</p>
                    </div>

                    {{-- 4. BERKAS LAMPIRAN --}}
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-paperclip me-1"></i> Berkas Lampiran PO:</h6>
                    <div class="border rounded p-3 bg-light mb-3">
                        @if(!empty($po->attachment))
                            <div class="row g-2">
                                @foreach(explode(',', $po->attachment) as $index => $file)
                                    <div class="col-md-6 col-12">
                                        @if($index == 0)
                                            <a href="{{ asset('storage/uploads/' . trim($file)) }}" target="_blank" class="btn btn-outline-info btn-sm w-100 text-start">
                                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> <strong>PO Original (Awal)</strong>
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/uploads/' . trim($file)) }}" target="_blank" class="btn btn-outline-warning btn-sm w-100 text-start text-dark">
                                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> <strong>Berkas Amandemen Baru #{{ $index }}</strong>
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="text-muted small">Tidak ada berkas lampiran yang diunggah.</span>
                        @endif
                    </div>

                    {{-- 5. TABEL DAFTAR ITEM PO (ITEM TARGET DIBERI HIGHLIGHT) --}}
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-boxes me-1"></i> Rincian Item PO Internal:</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="table-secondary text-uppercase small">
                                <tr>
                                    <th class="text-center" width="5%">#</th>
                                    <th>Nama Item</th>
                                    <th class="text-center" width="15%">Qty</th>
                                    <th width="25%">No. Kontrak Terikat</th>
                                    <th class="text-center" width="20%">Status Amandemen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($po->internals as $i => $item)
                                    @php
                                        $isTarget = ($targetInternalId && $item->id == $targetInternalId);
                                    @endphp
                                    <tr class="{{ $isTarget ? 'table-warning fw-bold' : '' }}">
                                        <td class="text-center">{{ $i + 1 }}</td>
                                        <td>
                                            {{ $item->item }}
                                            @if($isTarget)
                                                <span class="badge bg-warning text-dark ms-1"><i class="bi bi-pencil-square"></i> Item Diamandemen</span>
                                            @endif
                                        </td>
                                        <td class="text-center fw-bold">{{ number_format($item->qty) }}</td>
                                        <td>
                                            @if($item->contract)
                                                <span class="badge bg-primary">{{ $item->contract->contract_no }}</span>
                                            @else
                                                <span class="text-muted small fst-italic">Belum dibuat</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($isTarget)
                                                <span class="badge bg-warning text-dark">Pending Review</span>
                                            @else
                                                <span class="badge bg-secondary">Normal</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted small py-2">Belum ada rincian item internal.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- 6. AREA AKSI DENGAN HIDDEN ITEM KEY --}}
                    <hr>
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-check2-square me-1"></i> Keputusan Persetujuan:</h6>
                    
                    {{-- FORM REJECT (COLLAPSE BOX) --}}
                    <div class="collapse mb-3" id="rejectCollapse{{ $po->id }}">
                        <div class="card card-body bg-light-danger border border-danger p-3">
                            <form action="{{ route('purchase-orders.reject-amandement', $po->id) }}" method="POST">
                                @csrf
                                {{-- HIDDEN KEY: MENGUNCI ITEM SPESIFIK --}}
                                @if($targetInternalId)
                                    <input type="hidden" name="purchase_order_internal_id" value="{{ $targetInternalId }}">
                                @endif

                                <label for="alasan_penolakan" class="form-label text-danger fw-bold">Alasan Penolakan (Wajib Diisi untuk Customer):</label>
                                <textarea name="alasan_penolakan" class="form-control mb-2" rows="2" placeholder="Tuliskan alasan penolakan amandemen item ini secara jelas..." required></textarea>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="collapse" data-bs-target="#rejectCollapse{{ $po->id }}">Batal</button>
                                    <button type="submit" class="btn btn-sm btn-danger fw-bold"><i class="bi bi-x-circle-fill me-1"></i> Konfirmasi Tolak Amandemen</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- FORM APPROVE --}}
                    <form action="{{ route('purchase-orders.approve-amandement', $po->id) }}" method="POST" id="approveForm{{ $po->id }}">
                        @csrf
                        {{-- HIDDEN KEY: MENGUNCI ITEM SPESIFIK --}}
                        @if($targetInternalId)
                            <input type="hidden" name="purchase_order_internal_id" value="{{ $targetInternalId }}">
                        @endif

                        <div class="mb-2">
                            <label for="catatan" class="form-label small text-muted">Catatan Disetujui (Opsional):</label>
                            <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Contoh: Amandemen item disetujui, siap masuk alur produksi.">
                        </div>
                    </form>
                </div>

                {{-- MODAL FOOTER --}}
                <div class="modal-footer bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-danger btn-sm font-weight-bold px-3" data-bs-toggle="collapse" data-bs-target="#rejectCollapse{{ $po->id }}">
                            <i class="bi bi-x-circle me-1"></i> Reject
                        </button>
                        
                        <button type="submit" form="approveForm{{ $po->id }}" class="btn btn-success btn-sm font-weight-bold px-3">
                            <i class="bi bi-check-circle me-1"></i> Approve
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection