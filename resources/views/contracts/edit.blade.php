{{-- Include layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'Edit Contract Review Sheet - PT. Metinca Prima Industrial Works')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (isset($errors) && $errors->any())
    <div class="alert alert-danger alert-permanent alert-dismissible fade show mb-3">
        <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> Data Gagal Disimpan:</h6>
        <ul class="mb-0 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ================= ALERT REVISI / PENOLAKAN MANAGER (PERMANEN TIDAK AKAN MENGHILANG) ================= --}}
@php
    $rejections = [];
    if (!empty($contract->sales_reject_reason)) $rejections['Manager Sales'] = $contract->sales_reject_reason;
    if (!empty($contract->quality_reject_reason)) $rejections['Manager Quality'] = $contract->quality_reject_reason;
    if (!empty($contract->ppc_reject_reason)) $rejections['Manager PPC'] = $contract->ppc_reject_reason;
    if (!empty($contract->dev_engineering_reject_reason)) $rejections['Manager Design Engineering'] = $contract->dev_engineering_reject_reason;
@endphp

@if(!empty($rejections))
    <div class="card border-danger border-2 shadow-sm mb-4 alert-permanent">
        <div class="card-header bg-danger text-white py-2 d-flex align-items-center">
            <i class="bi bi-exclamation-octagon-fill fs-5 me-2"></i>
            <h6 class="fw-bold mb-0 text-white">Catatan Permintaan Revisi / Penolakan dari Manager</h6>
        </div>
        <div class="card-body bg-danger-subtle p-3">
            <ul class="mb-2 list-unstyled">
                @foreach($rejections as $mgr => $rsn)
                    <li class="p-2 mb-2 bg-white rounded border border-danger-subtle text-dark">
                        <strong class="text-danger"><i class="bi bi-person-x-fill me-1"></i>{{ $mgr }}:</strong>
                        <span class="fst-italic fw-semibold ms-1 text-dark">"{{ $rsn }}"</span>
                    </li>
                @endforeach
            </ul>
            <div class="small text-muted bg-white p-2 rounded border">
                <i class="bi bi-info-circle-fill text-primary me-1"></i>
                Bagian departemen yang telah disetujui sebelumnya otomatis <b>terkunci</b>. Anda hanya perlu memperbaiki bagian yang diminta di atas.
            </div>
        </div>
    </div>
@endif

<form action="{{ route('contracts.update', $contract->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">

                {{-- ================= CARD 1: HEADER UTAMA ================= --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header py-3 bg-warning text-black">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-collection-fill me-2"></i>Edit Contract Review Sheet
                        </h5>
                    </div>
                </div>

                {{-- ================= CARD 2: LEMBAR TINJAUAN KONTRAK + INPUTAN ================= --}}
                <div class="card mb-0">
                    <div class="card-header text-center py-3">
                        <h4 class="card-title mb-1 fw-bold">LEMBAR TINJAUAN KONTRAK</h4>
                        <h6 class="mb-0 text-muted">NO : {{ $contract->order_no }}</h6>
                    </div>
                </div>
                <div class="card-body px-4 py-3">

                    <input type="hidden" name="customer_id" value="{{ $contract->customer_id }}">
                    <input type="hidden" name="quotation_id" value="{{ $contract->quotation_id }}">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">CUSTOMER</label>
                            <input type="text" class="form-control form-control-sm"
                                value="{{ $contract->customer->name ?? '' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">DATA RECORD</label>
                            <input type="text" class="form-control form-control-sm"
                                id="dataRecord" name="data_record" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">ORDER NO <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="order_no" value="{{ $contract->order_no }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">PART NUMBER</label>
                            <input type="text" class="form-control form-control-sm text-uppercase fw-semibold" id="partNumber" name="part_no" value="{{ $contract->part_no ?? ($contract->article->internal_part_no ?? ($contract->article->part_number ?? ($contract->article->article_no ?? ($contract->internalItem->part_no ?? '')))) }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">AMANDMENT NO</label>
                            @php
                                $currentAmandement = (int)($contract->amandement_no ?? 0);
                                $isAmandemenStatus = in_array(strtolower($contract->status), ['amandemen', 'amandement', 'amandement_pending']);
                                $suggestedAmandement = $isAmandemenStatus ? $currentAmandement : $currentAmandement;
                                
                                $displayText = $suggestedAmandement;
                                if (!empty($contract->alasan_amandemen)) {
                                    $displayText .= ': ' . $contract->alasan_amandemen;
                                }
                            @endphp
                            
                            <input type="hidden" name="amandement_no" value="{{ $suggestedAmandement }}">
                            <input type="text" class="form-control form-control-sm" value="{{ $displayText }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">PART NAME</label>
                            <input type="text" class="form-control form-control-sm" id="partName" name="part_name" value="{{ old('part_name', $contract->part_name) }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">LOCATION</label>
                            <input type="text" class="form-control form-control-sm" id="location" name="location" value="{{ old('location', $contract->article->lokasi_text ?? '-') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">ARTICLE</label>
                            <input type="hidden" id="articleId" name="article_id" value="{{ $contract->article_id }}">
                            <input type="text" class="form-control form-control-sm" id="articleInput" placeholder="Ketik kode article" value="{{ old('article_no', $contract->article->article_no ?? '-') }}">
                        </div>
                    </div>
                </div>
            </div>
            {{-- ================= END CARD 2 ================= --}}

            {{-- ================= CARD 3-6: REQUIREMENTS PER DEPARTEMEN ================= --}}
            @php
                $departments = ['sales', 'quality', 'ppc', 'design engineering'];
                $deptLabels = [
                    'sales'              => 'SALES',
                    'quality'            => 'QUALITY',
                    'ppc'                => 'PPC',
                    'design engineering' => 'DESIGN ENGINEERING',
                ];
                $deptColors  = [
                    'sales'              => '#0d6efd',
                    'quality'            => '#198754',
                    'ppc'                => '#fd7e14',
                    'design engineering' => '#6f42c1',
                ];
                $deptIcons = [
                    'sales'              => 'bi-cart-check',
                    'quality'            => 'bi-patch-check',
                    'ppc'                => 'bi-gear',
                    'design engineering' => 'bi-pencil-ruler',
                ];
                $defaultReqNames = [
                    'sales'              => ['price', 'quantity', 'delivery required', 'supply condition', 'special / customer requirement'],
                    'quality'            => ['drawing', 'standard / spec', 'inspection'],
                    'ppc'                => ['material requirement', 'pattern wax', 'purchasing', 'sub contracting'],
                    'design engineering' => ['master job card', 'wra / wi', 'dies', 'tool', 'fixtures'],
                ];
                $grouped = $contract->requirements->groupBy('requirement_from');
            @endphp

            @foreach ($departments as $dept)
                @php
                    $requirements = $grouped[$dept] ?? collect();
                    $color        = $deptColors[$dept]       ?? 'black';
                    $icon         = $deptIcons[$dept]        ?? 'bi-list-ul';

                    $isDeptApproved = match($dept) {
                        'sales'              => !empty($contract->sales_approver),
                        'quality'            => !empty($contract->quality_approver),
                        'ppc'                => !empty($contract->ppc_approver),
                        'design engineering' => !empty($contract->dev_engineering_approver),
                        default              => false
                    };

                    $deptRejectReason = match($dept) {
                        'sales'              => $contract->sales_reject_reason,
                        'quality'            => $contract->quality_reject_reason,
                        'ppc'                => $contract->ppc_reject_reason,
                        'design engineering' => $contract->dev_engineering_reject_reason,
                        default              => null
                    };
                @endphp

                <div class="card shadow-sm mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center py-2"
                        style="border-bottom: 2px solid {{ $color }}; background:#6c757d;">
                        <h6 class="mb-0 fw-bold text-uppercase text-white"
                            style="font-size:0.82rem; letter-spacing:1px;">
                            <i class="bi {{ $icon }} me-1"></i>{{ $deptLabels[$dept] ?? strtoupper($dept) }}
                        </h6>

                        <div>
                            @if($isDeptApproved)
                                <span class="badge bg-success" style="font-size: 0.75rem;">
                                    <i class="bi bi-check-circle-fill me-1"></i>Sudah Disetujui (Terkunci)
                                </span>
                            @elseif(!empty($deptRejectReason))
                                <span class="badge bg-danger" style="font-size: 0.75rem;">
                                    <i class="bi bi-x-circle-fill me-1"></i>Ditolak - Perlu Revisi
                                </span>
                                <button type="button" class="btn btn-sm btn-light ms-2 add-row"
                                    data-dept="{{ $dept }}">
                                    <i class="bi bi-plus-lg me-1"></i>Add Requirement
                                </button>
                            @else
                                <span class="badge bg-secondary me-2" style="font-size: 0.75rem;">
                                    <i class="bi bi-clock me-1"></i>Menunggu Persetujuan
                                </span>
                                <button type="button" class="btn btn-sm btn-light add-row"
                                    data-dept="{{ $dept }}">
                                    <i class="bi bi-plus-lg me-1"></i>Add Requirement
                                </button>
                            @endif
                        </div>
                    </div>

                    @if(!empty($deptRejectReason))
                        <div class="alert alert-danger alert-permanent m-2 py-2 px-3 small border border-danger-subtle bg-danger-subtle text-danger-emphasis rounded">
                            <i class="bi bi-chat-left-quote-fill me-1 text-danger"></i> <b>Catatan Penolakan Manager:</b> <span class="fst-italic fw-semibold text-dark">"{{ $deptRejectReason }}"</span>
                        </div>
                    @endif

                    <div class="card-body p-0">
                        <table class="table table-bordered align-middle mb-0">
                            <thead style="background:#e9ecef;">
                                <tr>
                                    <th width="30%" class="text-center">Requirement</th>
                                    <th class="text-center">Action Required / Remark</th>
                                    <th width="60px" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="requirement-body" data-dept="{{ $dept }}">
                                @foreach ($requirements as $index => $req)
                                @php
                                    $isDefault = in_array(strtolower(trim($req->requirement)), $defaultReqNames[$dept] ?? []);
                                @endphp
                                <tr>
                                    <td>
                                        <input type="hidden"
                                            name="requirements[{{ $req->id }}][id]"
                                            value="{{ $req->id }}">

                                        <input type="text"
                                            name="requirements[{{ $req->id }}][requirement]"
                                            class="form-control form-control-sm {{ $isDefault || $isDeptApproved ? 'bg-light fw-semibold text-dark' : '' }}"
                                            value="{{ $req->requirement }}"
                                            {{ $isDefault || $isDeptApproved ? 'readonly' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text"
                                            name="requirements[{{ $req->id }}][value]"
                                            class="form-control form-control-sm {{ $isDeptApproved ? 'bg-light text-muted' : '' }}"
                                            value="{{ $req->requirement_value }}"
                                            placeholder="Keterangan / Value"
                                            {{ $isDeptApproved ? 'readonly' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        @if(!$isDefault && !$isDeptApproved)
                                            <button type="button" class="btn btn-sm btn-danger remove-row" title="Hapus Baris Kustom">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @else
                                            <span class="text-muted small fw-bold" title="Terkunci">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- END CARD DEPT --}}

            @endforeach

            {{-- ================= CARD TERAKHIR: OTHERS COMMENT + FILE UPDATE ================= --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header py-2"
                    style="background:#6c757d;">
                    <h6 class="mb-0 fw-bold text-uppercase text-white"
                        style="font-size:0.82rem; letter-spacing:1px;">
                        Others / Comment
                    </h6>
                </div>
                <div class="card-body">
                    <textarea name="others_comment" class="form-control mt-2" rows="3"
                        placeholder="Tulis komentar tambahan...">{{ $contract->others_comment }}</textarea>

                    {{-- Komponen File Upload PO PDF Baru & Deteksi Berkas Lama --}}
                    <div class="mt-3 text-start">
                        <label class="form-label mb-1 fw-semibold small text-dark">
                            <i class="bi bi-file-earmark-pdf-fill text-danger"></i> UPDATE DOKUMEN PO ASLI (PDF)
                        </label>

                        @if($contract->po_pdf)
                            <div class="mb-2 p-2 border rounded bg-light d-flex justify-content-between align-items-center" style="font-size: 12px;">
                                <span>
                                    <i class="bi bi-file-earmark-pdf-fill text-danger me-1"></i> Berkas PO Saat Ini: 
                                    <a href="{{ asset('storage/' . $contract->po_pdf) }}" target="_blank" class="fw-bold text-primary text-decoration-underline">
                                        Lihat PDF Terupload
                                    </a>
                                </span>
                                <span class="badge bg-secondary text-dark">Kosongkan jika tidak ingin diubah</span>
                            </div>
                        @endif

                        <input type="file" name="po_pdf" class="form-control form-control-sm" accept="application/pdf">
                        <small class="text-muted" style="font-size: 11px;">* Format file wajib PDF (Maksimal 2MB)</small>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-sm btn-light border">Batal</a>
                        <button type="submit" class="btn btn-sm btn-primary fw-bold px-3">
                            <i class="bi bi-save me-1"></i> Simpan & Ajukan Ulang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // AUTO ISI DATA RECORD
    const today = new Date();
    const day   = String(today.getDate()).padStart(2, '0');
    const monthNames = ['January','February','March','April','May','June',
        'July','August','September','October','November','December'];
    const month = monthNames[today.getMonth()];
    const year  = today.getFullYear();
    const dataRecordEl = document.getElementById('dataRecord');
    if (dataRecordEl) {
        dataRecordEl.value = day + ' ' + month + ' ' + year;
    }

    // ADD ROW REQUIREMENT
    document.querySelectorAll('.add-row').forEach(button => {
        button.addEventListener('click', function () {
            const dept = this.dataset.dept;
            const tbody = document.querySelector(`.requirement-body[data-dept="${dept}"]`);
            if (!tbody) return;

            const index = Date.now();
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <input type="hidden" name="requirements[new_${index}][from]" value="${dept}">
                    <input type="text" name="requirements[new_${index}][requirement]" class="form-control form-control-sm" placeholder="Nama Requirement Baru" required>
                </td>
                <td>
                    <input type="text" name="requirements[new_${index}][value]" class="form-control form-control-sm" placeholder="Keterangan / Value" required>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-row" title="Hapus Baris">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
    });

    // REMOVE ROW
    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('tr').remove();
        }
    });
});
</script>
@endpush