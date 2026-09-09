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
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title text-white mb-0"><i class="bi bi-patch-check-fill me-2"></i>Pusat Persetujuan & Riwayat Amandemen PO</h5>
                </div>
            </div>
            
            <div class="card-body mt-3">
                {{-- TAB NAVIGATION --}}
                <ul class="nav nav-tabs mb-3" id="amandementTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-tab-pane" type="button" role="tab" aria-controls="pending-tab-pane" aria-selected="true">
                            <i class="bi bi-hourglass-split me-1 text-warning"></i> Antrean Menunggu Persetujuan
                            @if(count($pendingContracts) > 0)
                                <span class="badge bg-danger rounded-pill ms-1">{{ count($pendingContracts) }}</span>
                            @else
                                <span class="badge bg-secondary rounded-pill ms-1">0</span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-tab-pane" type="button" role="tab" aria-controls="history-tab-pane" aria-selected="false">
                            <i class="bi bi-clock-history me-1 text-primary"></i> Riwayat Amandemen Selesai
                            <span class="badge bg-secondary rounded-pill ms-1">{{ count($historyContracts ?? []) }}</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="amandementTabContent">
                    {{-- TAB 1: ANTREAN PENDING --}}
                    <div class="tab-pane fade show active" id="pending-tab-pane" role="tabpanel" aria-labelledby="pending-tab" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="15%">No. PO</th>
                                        <th width="18%">Customer</th>
                                        <th width="37%">Target Item & Alasan Amandemen</th>
                                        <th width="13%" class="text-center">Tanggal Diajukan</th>
                                        <th width="12%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $counter = 0; @endphp
                                    @forelse($pendingContracts as $contract)
                                        @php
                                            $counter++;
                                            $targetItem = $contract->purchaseOrderInternal;
                                            $po = $contract->purchase_order ?? ($targetItem ? $targetItem->purchaseOrder : null);
                                            $customer = $contract->customer ?? ($po ? $po->customer : null);
                                            $targetInternalId = $targetItem->id ?? null;
                                            $reason = $contract->alasan_amandemen ?? 'Pengajuan amandemen item.';
                                            $displayItem = $targetItem ? $targetItem->item : ($contract->part_name ?? 'Item PO');
                                            $displayQty = $targetItem ? $targetItem->qty : ($contract->qty ?? 1);
                                        @endphp
                                         <tr>
                                            <td class="text-center fw-bold">{{ $counter }}</td>
                                            <td>
                                                <strong class="text-primary">{{ $targetItem->po_no ?? $po->po_no ?? $contract->order_no ?? '-' }}</strong>
                                                @if($po)
                                                    <br><small class="text-muted">PO ID: #{{ $po->id }}</small>
                                                @endif
                                            </td>
                                             @php
                                                $salesPic = $contract->sales_pic ?? ($po ? $po->sales_pic : null);
                                             @endphp
                                             <td>
                                                 <strong>{{ $customer->name ?? '-' }}</strong>
                                                 @if($salesPic)
                                                     <br><span class="badge bg-light text-primary border extra-small"><i class="bi bi-person-fill me-1"></i>PIC: {{ $salesPic->name }}</span>
                                                 @endif
                                             </td>
                                            <td>
                                                <span class="badge bg-warning text-dark mb-1"><i class="bi bi-box-seam me-1"></i>{{ $displayItem }} ({{ number_format($displayQty) }} pcs)</span>
                                                <br>
                                                <span class="text-dark fst-italic">"{{ Str::limit($reason, 80) }}"</span>
                                            </td>
                                            <td class="text-center small">
                                                {{ $contract->updated_at ? $contract->updated_at->format('d-m-Y H:i') : '-' }}
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary btn-sm px-3 font-weight-bold" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#reviewModalContract{{ $contract->id }}">
                                                    <i class="bi bi-eye-fill me-1"></i> Review Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="bi bi-check2-circle fs-2 d-block mb-2 text-success"></i>
                                                <span class="fw-semibold">Tidak ada antrean pengajuan amandemen saat ini.</span>
                                                <p class="small text-muted mb-0">Semua pengajuan amandemen telah diproses atau belum ada permintaan revisi baru dari Customer.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAB 2: RIWAYAT AMANDEMEN SELESAI --}}
                    <div class="tab-pane fade" id="history-tab-pane" role="tabpanel" aria-labelledby="history-tab" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="15%">No. PO / Kontrak</th>
                                        <th width="18%">Customer</th>
                                        <th width="32%">Target Item & Alasan Customer</th>
                                        <th width="15%" class="text-center">Hasil Keputusan</th>
                                        <th width="15%" class="text-center">Tanggal Selesai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $hCounter = 0; @endphp
                                    @forelse($historyContracts ?? [] as $hContract)
                                        @php
                                            $hCounter++;
                                            $hTargetItem = $hContract->purchaseOrderInternal;
                                            $hPo = $hContract->purchase_order ?? ($hTargetItem ? $hTargetItem->purchaseOrder : null);
                                            $hCustomer = $hContract->customer ?? ($hPo ? $hPo->customer : null);
                                            $hItem = $hTargetItem ? $hTargetItem->item : ($hContract->part_name ?? 'Item PO');
                                            $hReason = $hContract->alasan_amandemen ?? 'Revisi item PO';
                                        @endphp
                                        <tr>
                                            <td class="text-center fw-bold">{{ $hCounter }}</td>
                                            <td>
                                                <strong class="text-primary">{{ $hTargetItem->po_no ?? $hPo->po_no ?? $hContract->order_no ?? '-' }}</strong>
                                                <br><small class="text-muted">Kontrak: {{ $hContract->contract_no }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $hCustomer->name ?? '-' }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary text-white mb-1"><i class="bi bi-box-seam me-1"></i>{{ $hItem }} (Rev #{{ $hContract->amandement_no }})</span>
                                                <br>
                                                <span class="text-dark small fst-italic">"{{ Str::limit($hReason, 70) }}"</span>
                                            </td>
                                            <td class="text-center">
                                                @if($hContract->status === 'rejected')
                                                    <span class="badge bg-danger mb-1"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                                                    @if($hContract->alasan_penolakan)
                                                        <br><small class="text-danger fw-semibold" title="{{ $hContract->alasan_penolakan }}">{{ Str::limit($hContract->alasan_penolakan, 30) }}</small>
                                                    @endif
                                                @else
                                                    <span class="badge bg-success mb-1"><i class="bi bi-check-circle me-1"></i>Disetujui</span>
                                                    @if($hContract->catatan_sales)
                                                        <br><small class="text-success" title="{{ $hContract->catatan_sales }}">{{ Str::limit($hContract->catatan_sales, 30) }}</small>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center small">
                                                {{ $hContract->updated_at ? $hContract->updated_at->format('d-m-Y H:i') : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                Belum ada riwayat amandemen yang selesai diproses.
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
    </section>
</div>

{{-- MODAL REVIEW DETAIL & AKSI APPROVE/REJECT KHUSUS PER-ITEM CONTRACT --}}
@foreach($pendingContracts as $contract)
    @php
        $targetItem = $contract->purchaseOrderInternal;
        $po = $contract->purchase_order ?? ($targetItem ? $targetItem->purchaseOrder : null);
        $customer = $contract->customer ?? ($po ? $po->customer : null);
        $targetInternalId = $targetItem->id ?? null;
        $poId = $po->id ?? $contract->purchase_order_id;
        $alasanLengkap = $contract->alasan_amandemen ?? 'Tidak ada catatan alasan.';
    @endphp
    
    <div class="modal fade" id="reviewModalContract{{ $contract->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                {{-- MODAL HEADER --}}
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-file-earmark-check-fill text-warning me-2"></i>Review Amandemen Item: {{ $targetItem->item ?? $po->po_no ?? $contract->order_no }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- MODAL BODY --}}
                <div class="modal-body">
                    @php
                        $displayItemName = $targetItem ? $targetItem->item : ($contract->part_name ?? ($contract->article ? $contract->article->part_name : 'Item PO'));
                        $displayPartNo = $contract->contract_no ?? ($targetItem ? ($targetItem->part_no ?? $targetItem->article) : ($contract->part_no ?? '-'));
                        $displayQty = $targetItem ? $targetItem->qty : ($contract->qty ?? 1);
                        $displayAmendNo = $contract->amandement_no ?? 1;
                        $displayCustomerName = $customer->name ?? ($po && $po->customer ? $po->customer->name : 'Customer');
                        $displayPoNo = $po->po_no ?? $contract->order_no ?? '-';
                        $displayQuotationNo = ($po && $po->quotation) ? $po->quotation->quotation_no : ($contract->quotation ? $contract->quotation->quotation_no : '-');
                        
                        $alasanLengkap = $contract->alasan_amandemen 
                            ?? ($targetItem && $targetItem->contract ? $targetItem->contract->alasan_amandemen : null)
                            ?? ($po && $po->contracts ? $po->contracts->whereNotNull('alasan_amandemen')->last()->alasan_amandemen : null)
                            ?? ($po && $po->reason ? $po->reason : null)
                            ?? 'Customer mengajukan penyesuaian/revisi pada item pesanan ini.';
                    @endphp

                    @php
                        $modalSalesPic = $contract->sales_pic ?? ($po ? $po->sales_pic : null);
                        $currentUser = auth()->user();
                        $canProcessAmandement = $currentUser->isAdmin() 
                            || ($currentUser->isManager() && $currentUser->divisi === 'sales') 
                            || ($currentUser->isStaff() && $currentUser->divisi === 'sales' && (!$modalSalesPic || $modalSalesPic->id === $currentUser->id));
                    @endphp

                    {{-- 1. INFORMASI UTAMA CUSTOMER, PO, DAN SALES PIC --}}
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block fw-semibold">Nama Customer:</small>
                                <strong class="text-dark">{{ $displayCustomerName }}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block fw-semibold">No. PO & Quotation Asal:</small>
                                <strong class="text-primary">{{ $displayPoNo }}</strong> <small class="text-muted">({{ $displayQuotationNo }})</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block fw-semibold">Sales PIC Penanggung Jawab:</small>
                                <strong class="text-primary"><i class="bi bi-person-fill me-1"></i>{{ $modalSalesPic->name ?? '-' }}</strong>
                            </div>
                        </div>
                    </div>

                    {{-- 2. HIGHLIGHT ITEM SPESIFIK YANG DIAMANDEMEN (SELALU MUNCUL) --}}
                    <div class="card border border-warning bg-light-warning mb-3 shadow-sm" style="background-color: #fffdf2 !important;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-diamond-fill text-warning fs-5 me-2"></i>
                                    <strong class="text-dark fs-6">Target Item Yang Diamandemen:</strong>
                                </div>
                                <span class="badge bg-warning text-dark fw-bold px-2 py-1">
                                    <i class="bi bi-arrow-repeat me-1"></i>Amandemen Rev #{{ $displayAmendNo }}
                                </span>
                            </div>
                            <div class="row g-2 small text-dark ps-2">
                                <div class="col-md-5">
                                    <span class="text-muted d-block">Nama Part / Item:</span>
                                    <strong class="fs-6 text-dark">{{ $displayItemName }}</strong>
                                </div>
                                <div class="col-md-4">
                                    <span class="text-muted d-block">No. Part / Kontrak:</span>
                                    <span class="badge bg-dark text-white">{{ $displayPartNo }}</span>
                                </div>
                                <div class="col-md-3">
                                    <span class="text-muted d-block">Kuantitas PO:</span>
                                    <strong class="text-dark">{{ number_format($displayQty) }} pcs</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. BOX ALASAN AMANDEMEN CUSTOMER (SELALU MUNCUL) --}}
                    <div class="card border border-warning mb-3 shadow-sm" style="background-color: #fff8e6 !important;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-chat-left-quote-fill text-warning fs-5 me-2"></i>
                                <h6 class="fw-bold text-dark mb-0">Alasan Permintaan Amandemen dari Customer:</h6>
                            </div>
                            <div class="p-2 mt-2 bg-white rounded border border-warning-subtle text-dark fw-semibold fst-italic">
                                "{{ $alasanLengkap }}"
                            </div>
                            <small class="text-muted d-block mt-1 ps-1">
                                <i class="bi bi-info-circle me-1"></i>Harap telaah alasan di atas dengan jadwal produksi & ketersediaan material pabrik sebelum memutuskan.
                            </small>
                        </div>
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
                    <div class="collapse mb-3" id="rejectCollapseContract{{ $contract->id }}">
                        <div class="card card-body bg-light-danger border border-danger p-3">
                            <form action="{{ route('purchase-orders.reject-amandement', $poId) }}" method="POST">
                                @csrf
                                <input type="hidden" name="contract_id" value="{{ $contract->id }}">
                                {{-- HIDDEN KEY: MENGUNCI ITEM SPESIFIK --}}
                                @if($targetInternalId)
                                    <input type="hidden" name="purchase_order_internal_id" value="{{ $targetInternalId }}">
                                @endif

                                <label for="alasan_penolakan_select_{{ $contract->id }}" class="form-label text-danger fw-bold">Alasan Penolakan (Pilih Berdasarkan SOP Manufaktur):</label>
                                <select id="alasan_penolakan_select_{{ $contract->id }}" class="form-select form-select-sm mb-2" 
                                    onchange="toggleRejectManual(this, '{{ $contract->id }}')" required>
                                    <option value="">-- Pilih Alasan Penolakan --</option>
                                    <option value="Bahan baku sudah dipotong/disiapkan">Bahan baku sudah dipotong/disiapkan</option>
                                    <option value="Jadwal mesin stamping/casting sudah berjalan">Jadwal mesin stamping/casting sudah berjalan</option>
                                    <option value="Proses heat treatment sudah dimulai">Proses heat treatment sudah dimulai</option>
                                    <option value="Material sudah dipesan ke supplier">Material sudah dipesan ke supplier</option>
                                    <option value="Mould/cetakan sudah dalam proses produksi">Mould/cetakan sudah dalam proses produksi</option>
                                    <option value="Lainnya">Lainnya (Isi manual)</option>
                                </select>
                                <textarea id="alasan_penolakan_manual_{{ $contract->id }}" class="form-control form-control-sm mb-2" rows="2" placeholder="Tuliskan alasan penolakan manual..." style="display:none;" oninput="updateFinalReason('{{ $contract->id }}')"></textarea>
                                <input type="hidden" name="alasan_penolakan" id="alasan_penolakan_final_{{ $contract->id }}" required>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="collapse" data-bs-target="#rejectCollapseContract{{ $contract->id }}">Batal</button>
                                    <button type="submit" class="btn btn-sm btn-danger fw-bold"><i class="bi bi-x-circle-fill me-1"></i> Konfirmasi Tolak Amandemen</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- FORM APPROVE --}}
                    <form action="{{ route('purchase-orders.approve-amandement', $poId) }}" method="POST" id="approveFormContract{{ $contract->id }}">
                        @csrf
                        <input type="hidden" name="contract_id" value="{{ $contract->id }}">
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
                    @if($canProcessAmandement)
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-danger btn-sm font-weight-bold px-3" data-bs-toggle="collapse" data-bs-target="#rejectCollapseContract{{ $contract->id }}">
                                <i class="bi bi-x-circle me-1"></i> Reject
                            </button>
                            
                            <button type="submit" form="approveFormContract{{ $contract->id }}" class="btn btn-success btn-sm font-weight-bold px-3">
                                <i class="bi bi-check-circle me-1"></i> Approve
                            </button>
                        </div>
                    @else
                        <div class="alert alert-warning py-1 px-2 mb-0 extra-small">
                            <i class="bi bi-lock-fill me-1"></i>Hanya Sales PIC ({{ $modalSalesPic->name ?? 'Sales PIC' }}) atau Manager yang berhak memproses amandemen ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
function toggleRejectManual(selectElem, contractId) {
    const manualBox = document.getElementById('alasan_penolakan_manual_' + contractId);
    const finalInput = document.getElementById('alasan_penolakan_final_' + contractId);
    
    if (selectElem.value === 'Lainnya') {
        manualBox.style.display = 'block';
        manualBox.required = true;
        finalInput.value = manualBox.value;
    } else {
        manualBox.style.display = 'none';
        manualBox.required = false;
        finalInput.value = selectElem.value;
    }
}

function updateFinalReason(contractId) {
    const manualBox = document.getElementById('alasan_penolakan_manual_' + contractId);
    const finalInput = document.getElementById('alasan_penolakan_final_' + contractId);
    finalInput.value = manualBox.value;
}
</script>
@endpush