{{-- Include layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'PT. Metinca Prima Industrial Works')

@push('styles')
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/all.css') }}"> --}}
@endpush

@section('content')

<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card shadow-sm mb-3">
                <div class="card-header py-3 bg-info text-black">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-collection-fill me-2"></i>Detail Contract Review Sheet
                    </h5>
                </div>

                {{-- ================= CARD 2: LEMBAR TINJAUAN KONTRAK + INPUTAN ================= --}}
                <div class="card mb-0">
                    <div class="card-header text-center py-3">
                        <h4 class="card-title mb-1 fw-bold">LEMBAR TINJAUAN KONTRAK</h4>
                        <h6 class="mb-0 text-muted">NO : {{ $contract->order_no }}</h6>
                    </div>
                </div>
                <div class="card-body px-4 py-3">

                        <input type="hidden" name="customer_id" value="">
                        <input type="hidden" name="quotation_id" value="">

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
                            
                            {{-- BAGIAN AMANDEMENT NO YANG SUDAH DIUPDATE --}}
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
                                    <input type="hidden" id="articleId" name="article_id" value="{{ $contract->article_id }}">
                                    <input type="text" class="form-control form-control-sm" id="articleInput" value="{{ $contract->article->article_no ?? '-' }}"  readonly>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ================= END CARD 2 ================= --}}
                
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
                    $deptIcons = [
                        'sales'              => 'bi-cart-check',
                        'quality'            => 'bi-patch-check',
                        'ppc'                => 'bi-gear',
                        'design engineering' => 'bi-pencil-ruler',
                    ];
                    $deptRequirement = [
                        'sales' => [
                            ['requirement' => 'Price',                          'requirement_value' => ''],
                            ['requirement' => 'Quantity',                       'requirement_value' => ''],
                            ['requirement' => 'Delivery Required',              'requirement_value' => ''],
                            ['requirement' => 'Supply Condition',               'requirement_value' => ''],
                            ['requirement' => 'Special / Customer Requirement','requirement_value' => ''],
                        ],
                        'quality' => [
                            ['requirement' => 'Drawing',        'requirement_value' => ''],
                            ['requirement' => 'Standard / Spec','requirement_value' => ''],
                            ['requirement' => 'Inspection',     'requirement_value' => ''],
                        ],
                        'ppc' => [
                            ['requirement' => 'Material Requirement','requirement_value' => ''],
                            ['requirement' => 'Pattern Wax',         'requirement_value' => ''],
                            ['requirement' => 'Purchasing',          'requirement_value' => ''],
                            ['requirement' => 'Sub Contracting',     'requirement_value' => ''],
                        ],
                        'design engineering' => [
                            ['requirement' => 'Master Job Card','requirement_value' => ''],
                            ['requirement' => 'WRA / WI',       'requirement_value' => ''],
                            ['requirement' => 'Dies',           'requirement_value' => ''],
                            ['requirement' => 'Tool',           'requirement_value' => ''],
                            ['requirement' => 'Fixtures',       'requirement_value' => ''],
                        ],
                    ];
                @endphp

                @foreach ($departments as $dept)
                    @php
                        $requirements = $grouped[$dept] ?? collect();
                        $color        = $deptColors[$dept]       ?? 'black';
                        $icon         = $deptIcons[$dept]        ?? 'bi-list-ul';
                    @endphp

                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-info d-flex justify-content-between align-items-center py-2"
                            style=" solid {{ $color }}; ">
                            <h6 class="mb-0 fw-bold text-uppercase"
                                style="font-size:0.82rem; letter-spacing:1px; color:black;">
                                <i class=""></i>{{ $deptLabels[$dept] ?? strtoupper($dept) }}
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered align-middle mb-0">
                                <thead style="background:#e9ecef;">
                                    <tr>
                                        <th width="30%"><center>Requirement</center></th>
                                        <th><center>Action Required / Remark</center></th>
                                    </tr>
                                </thead>
                                <tbody class="requirement-body" data-dept="{{ $deptLabels[$dept] ?? strtoupper($dept) }}">
                                    @foreach ($requirements as $index => $req)
                                    <tr>
                                        <td>
                                            <input type="hidden"
                                                name="requirements[{{ $deptLabels[$dept] ?? strtoupper($dept) }}][{{ $index }}][requirement_from]"
                                                value="{{ $deptLabels[$dept] ?? strtoupper($dept) }}">
                                            <input type="text"
                                                name="requirements[{{ $deptLabels[$dept] ?? strtoupper($dept) }}][{{ $index }}][requirement]"
                                                class="form-control form-control-sm"
                                                value="{{ $req['requirement'] }}" readonly>
                                        </td>
                                        <td>
                                            @php
                                                // 1. Cek apakah baris requirement ini adalah 'Price'
                                                $isPriceRow = strtolower($req['requirement']) == 'price';
                                                
                                                // 2. Cek apakah user yang login adalah Admin atau berasal dari Divisi Sales
                                                $isAuthorized = auth()->user()->role == 'admin' || strtolower(auth()->user()->divisi) == 'sales';
                                                
                                                // 3. Tentukan nilai yang ditampilkan
                                                $maskedValue = ($isPriceRow && !$isAuthorized) ? '*** RAHASIA PERUSAHAAN ***' : $req['requirement_value'];
                                            @endphp

                                            <input type="text"
                                                name="requirements[{{ $deptLabels[$dept] ?? strtoupper($dept) }}][{{ $index }}][requirement_value]"
                                                class="form-control form-control-sm {{ ($isPriceRow && !$isAuthorized) ? 'text-danger fw-bold text-center' : '' }}"
                                                value="{{ $maskedValue }}" readonly>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- END CARD DEPT --}}

                @endforeach

                {{-- ================= CARD TERAKHIR: OTHERS COMMENT + FILE VIEW ================= --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-info py-2"
                        style="solid #6c757d; ">
                        <h6 class="mb-0 fw-bold text-uppercase"
                            style="font-size:0.82rem; letter-spacing:1px; color:black;">
                            Others / Comment
                        </h6>
                    </div>
                    <div class="card-body">
                        <textarea name="others_comment" class="form-control mt-2" rows="3" readonly>{{ $contract->others_comment ?? '-' }}</textarea>

                        {{-- Perbaikan: Penambahan Tampilan Komponen Akses File PO PDF Asli --}}
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

                        {{-- BUTTONS --}}
                        <div class=" mt-3 text-end">                                
                            
                            {{-- TOMBOL FINALISASI: Khusus Staff Sales & Aktif jika 4 Manager sudah Approve --}}
                            @if(auth()->user()->role == 'staff' && strtolower(auth()->user()->divisi) == 'sales')
                                {{-- Perbaikan di sini: Memastikan status belum masuk ke 'production', 'shipment', atau 'done' --}}
                                @if($contract->sales_approver && $contract->ppc_approver && $contract->quality_approver && $contract->dev_engineering_approver && in_array($contract->status, ['created', 'amandement', 'revision']))
                                    <form action="{{ route('contracts.finalize', $contract->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary fw-bold" onclick="return confirm('Finalisasi kontrak ini dan teruskan ke tahap Produksi?')">
                                            <i class="bi bi-send-check-fill"></i> Finalisasi ke Produksi
                                        </button>
                                    </form>
                                @endif
                            @endif

                            @if (in_array(auth()->user()->role, ['manager', 'admin']) && in_array($contract->status, ['created', 'revision']))
                                <a href="#" data-bs-target="#rejectModal" data-bs-toggle="modal" class="btn btn-sm btn-danger">Reject</a>
                                
                                {{-- TOMBOL APPROVE BARU (MEMANGGIL MODAL TANDA TANGAN) --}}
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                                    Approve
                                </button>
                            @endif

                            @if (in_array(auth()->user()->role, ['admin', 'staff']) && in_array($contract->status, ['created', 'revision']))
                                <a href="{{ route('contracts.edit', $contract->id) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                            @endif
                            <a href="{{ route('contracts.index') }}" class="btn btn-sm btn-secondary">
                                Back
                            </a>
                        </div>

            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="approveModalLabel">Persetujuan Tinjauan Kontrak</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- Pastikan URL action-nya sesuai dengan route approval mase (sebelumnya approve-manager) --}}
            <form action="{{ route('contracts.approve-manager', $contract->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body text-center">
                    <p class="mb-2 fw-bold text-dark">Silakan gambar tanda tangan Anda di bawah ini:</p>
                    
                    <div class="border rounded d-inline-block" style="background: #f8f9fa;">
                        <canvas id="signature-pad" width="400" height="200"></canvas>
                    </div>
                    <input type="hidden" name="signature" id="signature_base64">
                    
                    <div class="mt-2 text-end" style="width: 400px; margin: 0 auto;">
                        <button type="button" class="btn btn-sm btn-outline-danger" id="clear-signature">
                            <i class="bi bi-eraser"></i> Bersihkan Area
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn @if(auth()->user()->role == 'staff' && strtolower(auth()->user()->divisi) == 'sales')
                                {{-- Perbaikan di sini: Memastikan status belum masuk ke 'production', 'shipment', atau 'done' --}}
                                @if($contract->sales_approver && $contract->ppc_approver && $contract->quality_approver && $contract->dev_engineering_approver && in_array($contract->status, ['created', 'amandement', 'revision']))
                                    <form action="{{ route('contracts.finalize', $contract->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary fw-bold" onclick="return confirm('Finalisasi kontrak ini dan teruskan ke tahap Produksi?')">
                                            <i class="bi bi-send-check-fill"></i> Finalisasi ke Produksi
                                        </button>
                                    </form>
                                @endif
                            @endif

                            @if (in_array(auth()->user()->role, ['manager', 'admin']) && in_array($contract->status, ['created', 'revision']))
                                <a href="#" data-bs-target="#rejectModal" data-bs-toggle="modal" class="btn btn-sm btn-danger">Reject</a>
                                
                                {{-- TOMBOL APPROVE BARU (MEMANGGIL MODAL TANDA TANGAN) --}}
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                                    Approve
                                </button>
                            @endif

                            @if (in_array(auth()->user()->role, ['admin', 'staff']) && in_array($contract->status, ['created', 'revision']))
                                <a href="{{ route('contracts.edit', $contract->id) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                            @endif
                            <a href="{{ route('contracts.index') }}" class="btn btn-sm btn-secondary">
                                Back
                            </a>
                        </div>

            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="approveModalLabel">Persetujuan Tinjauan Kontrak</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- Pastikan URL action-nya sesuai dengan route approval mase (sebelumnya approve-manager) --}}
            <form action="{{ route('contracts.approve-manager', $contract->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body text-center">
                    <p class="mb-2 fw-bold text-dark">Silakan gambar tanda tangan Anda di bawah ini:</p>
                    
                    <div class="border rounded d-inline-block" style="background: #f8f9fa;">
                        <canvas id="signature-pad" width="400" height="200"></canvas>
                    </div>
                    <input type="hidden" name="signature" id="signature_base64">
                    
                    <div class="mt-2 text-end" style="width: 400px; margin: 0 auto;">
                        <button type="button" class="btn btn-sm btn-outline-danger" id="clear-signature">
                            <i class="bi bi-eraser"></i> Bersihkan Area
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="btn-submit-approve">
                        <i class="bi bi-check-circle"></i> Approve Sekarang
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

        // Inisialisasi Signature Pad hanya ketika modal terbuka agar ukuran canvas akurat
        approveModal.addEventListener('shown.bs.modal', function () {
            if (!signaturePad) {
                signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: 'rgb(0, 0, 0)'
                });
            }
        });

        // Tombol hapus tanda tangan
        document.getElementById('clear-signature').addEventListener('click', function () {
            if(signaturePad) signaturePad.clear();
        });

        // Validasi sebelum submit form
        document.getElementById('btn-submit-approve').addEventListener('click', function (e) {
            if (signaturePad && signaturePad.isEmpty()) {
                e.preventDefault();
                alert("Tanda tangan wajib diisi sebelum Approve!");
            } else if (signaturePad) {
                document.getElementById('signature_base64').value = signaturePad.toDataURL();
            }
        });
    });
</script>
@endpush