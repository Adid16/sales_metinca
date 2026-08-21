{{-- Include layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'PT. Metinca Prima Industrial Works')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/quotation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
@endpush

@section('content')

<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            
            {{-- ================= CARD 1 & 2: LEMBAR TINJAUAN KONTRAK + METADATA ================= --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header py-3 bg-info text-black">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-collection-fill me-2"></i>Detail Contract Review Sheet
                    </h5>
                </div>

                <div class="card text-center mb-0 border-0">
                    <div class="card-header text-center py-3 bg-transparent">
                        <h4 class="card-title mb-1 fw-bold">LEMBAR TINJAUAN KONTRAK</h4>
                        <h6 class="mb-0 text-muted">NO : {{ $contract->order_no }}</h6>
                    </div>
                </div>
                
                <div class="card-body px-4 py-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">CUSTOMER</label>
                            <input type="text" class="form-control form-control-sm"
                                value="{{ $contract->customer->name ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">DATA RECORD</label>
                            <input type="text" class="form-control form-control-sm "
                                id="dataRecord" name="data_record" value= "{{ \Carbon\Carbon::parse($contract->created_at)->translatedFormat('d F Y') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">ORDER NO <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm " name="order_no" value="{{ $contract->order_no }}" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">AMANDEMENT NO</label>
                            <input type="text" class="form-control form-control-sm" name="amendment_no" 
                                value="{{ $contract->amandement_no ?? '0' }}{{ !empty($contract->alasan_amandemen) ? ' - ' . $contract->alasan_amandemen : '' }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">PART NAME</label>
                            <input type="text" class="form-control form-control-sm " id="partName" name="part_name" value="{{ $contract->part_name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">LOCATION</label>
                            <input type="text" class="form-control form-control-sm " id="location" name="location" value="{{ $contract->article->lokasi_text ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">ARTICLE</label>
                            <input type="text" class="form-control form-control-sm" id="articleInput" value="{{ $contract->article->article_no ?? '-' }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- ================= CARD 3-6: REQUIREMENTS PER DEPARTEMEN ================= --}}
            @php
                $departments = ['sales', 'quality', 'ppc', 'design engineering'];
                $deptLabels = [
                    'sales' => 'SALES',
                    'quality' => 'QUALITY',
                    'ppc' => 'PPC',
                    'design engineering' => 'DEVELOPMENT ENGINEERING',
                ];
                $deptColors  = [
                    'sales'              => '#0d6efd',
                    'quality'            => '#198754',
                    'ppc'                => '#fd7e14',
                    'design engineering' => '#6f42c1',
                ];
            @endphp

            @foreach ($departments as $dept)
                @php
                    $requirements = $grouped[$dept] ?? collect();
                    $color        = $deptColors[$dept]       ?? 'black';
                @endphp

                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-info d-flex justify-content-between align-items-center py-2">
                        <h6 class="mb-0 fw-bold text-uppercase" style="font-size:0.82rem; letter-spacing:1px; color:black;">
                            {{ $deptLabels[$dept] ?? strtoupper($dept) }}
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="30%" class="text-center">Requirement</th>
                                    <th class="text-center">Action Required / Remark</th>
                                </tr>
                            </thead>
                            <tbody class="requirement-body" data-dept="{{ $deptLabels[$dept] ?? strtoupper($dept) }}">
                                @foreach ($requirements as $index => $req)
                                <tr>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value="{{ $req['requirement'] }}" readonly>
                                    </td>
                                    <td>
                                        @php
                                            $isPriceRow = strtolower($req['requirement']) == 'price';
                                            $isAuthorized = auth()->user()->role == 'admin' || strtolower(auth()->user()->divisi) == 'sales';
                                            $maskedValue = ($isPriceRow && !$isAuthorized) ? '*** RAHASIA PERUSAHAAN ***' : $req['requirement_value'];
                                        @endphp

                                        <input type="text" class="form-control form-control-sm {{ ($isPriceRow && !$isAuthorized) ? 'text-danger fw-bold text-center' : '' }}" value="{{ $maskedValue }}" readonly>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            {{-- ================= CARD TERAKHIR: OTHERS COMMENT + FILE VIEW + ACTION BUTTONS ================= --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info py-2">
                    <h6 class="mb-0 fw-bold text-uppercase" style="font-size:0.82rem; letter-spacing:1px; color:black;">
                        Others / Comment
                    </h6>
                </div>
                <div class="card-body">
                    <textarea class="form-control mt-2" rows="3" readonly>{{ $contract->others_comment ?? '-' }}</textarea>

                    {{-- Lampiran PO PDF --}}
                    @if($contract->po_pdf)
                        <div class="mt-3 p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                            <div class="text-start">
                                <span class="small fw-bold text-secondary d-block">Dokumen Lampiran PO Asli:</span>
                                <span class="small text-dark fw-semibold">
                                    <i class="bi bi-file-earmark-pdf-fill text-danger me-1"></i>Dokumen_PO_{{ $contract->order_no }}.pdf
                                </span>
                            </div>
                            <a href="{{ asset('storage/' . $contract->po_pdf) }}" target="_blank" class="btn btn-sm btn-danger fw-bold shadow-sm">
                                <i class="bi bi-eye-fill me-1"></i> Lihat Dokumen PO
                            </a>
                        </div>
                    @else
                        <div class="mt-3 p-2 border rounded bg-light text-start">
                            <small class="text-muted"><i class="bi bi-exclamation-circle me-1"></i> Tidak ada lampiran berkas PO PDF untuk kontrak ini.</small>
                        </div>
                    @endif

                    {{-- TOMBOL OPERASIONAL HALAMAN UTAMA --}}
                    @php
                        $all4Approved = $contract->sales_approver 
                                     && $contract->ppc_approver 
                                     && $contract->quality_approver 
                                     && $contract->dev_engineering_approver;

                        $userDiv = strtolower(auth()->user()->divisi ?? '');
                        $isManagerOrAdmin = in_array(auth()->user()->role, ['manager', 'admin']);
                        $isSalesStaffOrAdmin = (auth()->user()->role == 'staff' && $userDiv == 'sales') || auth()->user()->role == 'admin';

                        $managerAlreadyApproved = false;
                        if ($userDiv == 'sales' && $contract->sales_approver) $managerAlreadyApproved = true;
                        if ($userDiv == 'quality' && $contract->quality_approver) $managerAlreadyApproved = true;
                        if (in_array($userDiv, ['ppc', 'ppic']) && $contract->ppc_approver) $managerAlreadyApproved = true;
                        if (in_array($userDiv, ['design engineering', 'de']) && $contract->dev_engineering_approver) $managerAlreadyApproved = true;
                    @endphp

                    <div class="mt-4 text-end">                                
                        {{-- 1. INDIKATOR ATAU TOMBOL UNTUK MANAGER APPROVE --}}
                        @if ($isManagerOrAdmin && !in_array($contract->status, ['production', 'done']))
                            @if ($managerAlreadyApproved)
                                <span class="badge bg-success py-2 px-3 me-1 fs-6">
                                    <i class="bi bi-check-circle-fill me-1"></i> Divisi {{ strtoupper(auth()->user()->divisi ?? 'Manager') }} Sudah Approve
                                </span>
                            @else
                                <button type="button" data-bs-target="#rejectModal" data-bs-toggle="modal" class="btn btn-sm btn-danger px-3 shadow-sm me-1">
                                    <i class="bi bi-x-circle-fill me-1"></i> Reject / Revisi
                                </button>
                                <button type="button" class="btn btn-sm btn-success px-3 shadow-sm me-1 fw-bold" data-bs-toggle="modal" data-bs-target="#approveModal">
                                    <i class="bi bi-check-circle-fill me-1"></i> Approve Divisi {{ strtoupper(auth()->user()->divisi ?? '') }}
                                </button>
                            @endif
                        @endif

                        {{-- 2. TOMBOL FINALISASI UNTUK STAFF SALES / ADMIN --}}
                        @if ($isSalesStaffOrAdmin)
                            @if (in_array($contract->status, ['production', 'done']))
                                <span class="badge bg-success py-2 px-3 me-1 fs-6">
                                    <i class="bi bi-gear-wide-connected me-1"></i> In Production (Dalam Produksi)
                                </span>
                            @elseif ($all4Approved)
                                <form action="{{ route('contracts.finalize', $contract->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success text-white fw-bold px-3 shadow-sm me-1" onclick="return confirm('Seluruh 4 Manager telah menyetujui. Apakah Anda yakin ingin memfinalisasi kontrak ini ke tahap In Production?')">
                                        <i class="bi bi-gear-fill me-1"></i> Finalisasi ke Produksi (In Production)
                                    </button>
                                </form>
                            @else
                                <span data-bs-toggle="tooltip" title="Finalisasi baru dapat dilakukan setelah 4 Manager (Sales, Quality, PPIC, DE) memberikan Approve.">
                                    <button class="btn btn-sm btn-secondary px-3 shadow-sm me-1" disabled>
                                        <i class="bi bi-clock-history me-1 text-warning"></i> Finalisasi (Menunggu 4 Manager)
                                    </button>
                                </span>
                            @endif
                        @endif

                        {{-- 3. TOMBOL EDIT (Admin & Staff Sales) --}}
                        @if (in_array(auth()->user()->role, ['admin', 'staff']) && in_array($contract->status, ['created', 'revision', 'review']))
                            <a href="{{ route('contracts.edit', $contract->id) }}" class="btn btn-sm btn-warning px-3 shadow-sm me-1">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </a>
                        @endif

                        {{-- 4. TOMBOL KEMBALI --}}
                        <a href="{{ route('contracts.index') }}" class="btn btn-sm btn-secondary px-3 shadow-sm">
                            <i class="bi bi-arrow-left-circle me-1"></i> Back
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

{{-- ============================================
     MODAL COMPONENT: APPROVAL + SIGNATURE PAD
     ============================================ --}}
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white" id="approveModalLabel">
                    <i class="bi bi-pen-fill me-2"></i>Persetujuan Tinjauan Kontrak
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('contracts.approve-manager', $contract->id) }}" method="POST">
                @csrf
                @value && @method('PATCH')
                
                <div class="modal-body text-center">
                    <p class="mb-2 fw-bold text-dark">Silakan gambar tanda tangan Anda di bawah ini:</p>
                    
                    <div class="border rounded d-inline-block shadow-sm" style="background: #ffffff; border: 2px solid #dee2e6 !important;">
                        <canvas id="signature-pad" width="400" height="200"></canvas>
                    </div>
                    <input type="hidden" name="signature" id="signature_base64">
                    
                    <div class="mt-2 text-end" style="width: 400px; margin: 0 auto;">
                        <button type="button" class="btn btn-sm btn-outline-danger border-0" id="clear-signature">
                            <i class="bi bi-eraser-fill"></i> Bersihkan Area
                        </button>
                    </div>
                </div>
                
                {{-- PERBAIKAN: Isi Modal Footer Bersih & Sesuai Fungsinya --}}
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-success fw-bold" id="btn-submit-approve">
                        <i class="bi bi-check-circle-fill me-1"></i> Approve Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var canvas = document.getElementById('signature-pad');
        var approveModal = document.getElementById('approveModal');
        var signaturePad;

        // Inisialisasi Signature Pad secara akurat setelah modal ditampilkan penuh
        approveModal.addEventListener('shown.bs.modal', function () {
            if (!signaturePad) {
                signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: 'rgb(0, 0, 0)'
                });
            } else {
                signaturePad.clear(); // Bersihkan pad lama jika terbuka kembali
            }
        });

        // Tombol bersihkan tanda tangan
        document.getElementById('clear-signature').addEventListener('click', function () {
            if(signaturePad) signaturePad.clear();
        });

        // Validasi dan konversi canvas menjadi Base64 string sebelum disubmit ke server
        document.getElementById('btn-submit-approve').addEventListener('click', function (e) {
            if (signaturePad && signaturePad.isEmpty()) {
                e.preventDefault();
                alert("Tanda tangan wajib digambar terlebih dahulu sebelum melakukan Approve!");
            } else if (signaturePad) {
                document.getElementById('signature_base64').value = signaturePad.toDataURL();
            }
        });
    });
</script>
@endpush