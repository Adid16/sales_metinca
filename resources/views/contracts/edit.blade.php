{{-- Include layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'PT. Metinca Prima Industrial Works')

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

{{-- ====== TAMBAHKAN BLOK KODE INI UNTUK MENAMPILKAN ERROR VALIDASI ====== --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> Data Gagal Disimpan:</h6>
        <ul class="mb-0 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
{{-- ===================================================================== --}}

{{-- PERBAIKAN: Ditambahkan enctype="multipart/form-data" agar form ini bisa memproses upload file PDF baru --}}
<form action="{{ route('contracts.update', $contract->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">

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
                            <label class="form-label mb-1 fw-semibold small">PART NO</label>
                            <input type="text" class="form-control form-control-sm" id="partNumber" name="part_no" value="{{ $contract->part_no }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">AMANDMENT NO</label>
                            @php
                                // 1. Ambil angka amandemen terakhir (jika null maka 0)
                                $currentAmandement = (int)($contract->amandement_no ?? $contract->amandment_no ?? 0);
                                
                                // 2. Jika status kontrak sedang 'amandement', otomatis tambahkan 1
                                $isAmandemenStatus = in_array(strtolower($contract->status), ['amandemen', 'amandement']);
                                $suggestedAmandement = $isAmandemenStatus ? $currentAmandement + 1 : $currentAmandement;
                                
                                // 3. Gabungkan angka dan alasan untuk tampilan
                                $displayText = $suggestedAmandement;
                                if ($isAmandemenStatus && !empty($contract->alasan_amandemen)) {
                                    $displayText .= ': ' . $contract->alasan_amandemen;
                                }
                            @endphp
                            
                            {{-- Input hidden: ini yang akan dikirim dan disimpan ke database (hanya angka) --}}
                            <input type="hidden" name="amandement_no" value="{{ $suggestedAmandement }}">
                            
                            {{-- Input text: ini hanya untuk tampilan visual di layar (angka + alasan) --}}
                            <input type="text" class="form-control form-control-sm" value="{{ $displayText }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">PART NAME</label>
                            <input type="text" class="form-control form-control-sm" id="partName" name="part_name" value="{{ old('part_name', $contract->part_name) }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">LOCATION</label>
                            <input type="text" class="form-control form-control-sm" id="location" name="location" value="{{ old('location', $contract->article->lokasi_text) ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold small">ARTICLE</label>
                                <input type="hidden" id="articleId" name="article_id" value="{{ $contract->article_id }}">
                                <input type="text" class="form-control form-control-sm" id="articleInput" placeholder="Ketik kode article lalu tekan enter" value="{{ old('article_no', $contract->article->article_no ?? '-') }}">
                        </div>
                    </div>
                </div>
            </div>
            {{-- ================= END CARD 2 ================= --}}

            {{-- ================= CARD 3-6: REQUIREMENTS PER DEPARTEMEN ================= --}}
            @php
                $departments = ['sales', 'quality', 'ppc', 'design engineering'];
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
                $grouped = $contract->requirements->groupBy('requirement_from');
            @endphp

            @foreach ($departments as $dept)
                @php
                    $requirements = $grouped[$dept] ?? collect();
                    $color        = $deptColors[$dept]       ?? 'black';
                    $icon         = $deptIcons[$dept]        ?? 'bi-list-ul';
                @endphp

                <div class="card shadow-sm mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center py-2"
                        style=" solid {{ $color }}; background:#6c757d;">
                        <h6 class="mb-0 fw-bold text-uppercase"
                            style="font-size:0.82rem; letter-spacing:1px; color:black;">
                            <i class=""></i>{{ $dept }}
                        </h6>
                        <button type="button" class="btn btn-sm btn-secondary add-row"
                            data-dept="{{ $dept }}">
                            <i class="bi bi-plus-lg me-1"></i>Add Requirement
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered align-middle mb-0">
                            <thead style="background:#e9ecef;">
                                <tr>
                                    <th width="30%"><center>Requirement</center></th>
                                    <th><center>Action Required / Remark</center></th>
                                    <th width="60px" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="requirement-body" data-dept="{{ $dept }}">
                                    @foreach ($requirements as $index => $req)
                                    <tr>
                                        <td>
                                            <input type="hidden"
                                                name="requirements[{{ $req->id }}][id]"
                                                value="{{ $req->id }}">

                                            <input type="text"
                                                name="requirements[{{ $req->id }}][requirement]"
                                                class="form-control form-control-sm"
                                                value="{{ $req->requirement }}">
                                        </td>
                                        <td>
                                            <input type="text"
                                                name="requirements[{{ $req->id }}][value]"
                                                class="form-control form-control-sm"
                                                value="{{ $req->requirement_value }}">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="bi bi-trash"></i>
                                            </button>
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
                        style="solid #6c757d; background:#6c757d;">
                        <h6 class="mb-0 fw-bold text-uppercase"
                            style="font-size:0.82rem; letter-spacing:1px; color:black;">
                            Others / Comment
                        </h6>
                    </div>
                    <div class="card-body">
                        <textarea name="others_comment" class="form-control mt-2" rows="3"
                            placeholder="Tulis komentar tambahan...">{{ $contract->others_comment }}</textarea>

                        {{-- PERBAIKAN: Penambahan Komponen File Upload PO PDF Baru & Deteksi Berkas Lama --}}
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
                            <a href="{{ route('contracts.index') }}" class="btn btn-sm btn-light">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-primary">
                                Update
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
    document.getElementById('dataRecord').value = day + ' ' + month + ' ' + year;

    // ADD REQUIREMENT ROW
    document.querySelectorAll('.add-row').forEach(button => {
        button.addEventListener('click', function () {
            const dept  = this.dataset.dept;
            const tbody = document.querySelector(`.requirement-body[data-dept="${dept}"]`);
            const index = tbody.children.length;
            const row   = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <input type="hidden" name="requirements[${dept}][${index}][requirement_from]" value="${dept}">
                    <input type="text" name="requirements[${dept}][${index}][requirement]" class="form-control form-control-sm" placeholder="Requirement">
                </td>
                <td>
                    <input type="text" name="requirements[${dept}][${index}][requirement_value]" class="form-control form-control-sm" placeholder="Value / Description">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-row"><i class="bi bi-trash"></i></button>
                </td>`;
            tbody.appendChild(row);
        });
    });

    // REMOVE REQUIREMENT ROW
    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-row')) {
            const row   = e.target.closest('tr');
            const tbody = row.closest('tbody');
            if (tbody.children.length > 1) row.remove();
        }
    });

    // AUTO FILL DARI ARTICLE
    document.getElementById('articleInput').addEventListener('keydown', function (e) {
        if (e.key !== 'Enter') return;
        e.preventDefault();

        const articleNo = this.value.trim();
        if (!articleNo) return;

        const input = this;
        input.classList.remove('border-success', 'border-danger');
        input.classList.add('border-warning');

        fetch(`/article-requirements/${articleNo}`)
            .then(res => res.json())
            .then(data => {
                input.classList.remove('border-warning');

                if (data.data) {
                    const d = data.data;

                    document.getElementById('partNumber').value = d.part_number || '';
                    document.getElementById('partName').value   = d.part_name   || '';
                    document.getElementById('articleId').value  = d.id          || '';

                    const locationMap = {
                        1: 'PT. Metinca (Jakarta)',
                        2: 'PT. Metal Castindo',
                        3: 'PT. Metinca S3',
                        4: 'Valve',
                    };
                    document.getElementById('location').value = locationMap[d.lokasi_pengerjaan] || '';

                    // Auto fill Drawing di quality
                    document.querySelectorAll('input[name^="requirements[quality]"]').forEach(inp => {
                        if (inp.name.includes('[requirement]') && inp.value.toLowerCase() === 'drawing') {
                            const idx = inp.name.match(/requirements\[quality\]\[(\d+)\]\[requirement\]/)[1];
                            const val = document.querySelector(`input[name="requirements[quality][${idx}][requirement_value]"]`);
                            if (val) val.value = d.drawing_no || '';
                        }
                    });

                    // Auto fill Material Requirement di ppc
                    document.querySelectorAll('input[name^="requirements[ppc]"]').forEach(inp => {
                        if (inp.name.includes('[requirement]') && inp.value.toLowerCase() === 'material requirement') {
                            const idx = inp.name.match(/requirements\[ppc\]\[(\d+)\]\[requirement\]/)[1];
                            const val = document.querySelector(`input[name="requirements[ppc][${idx}][requirement_value]"]`);
                            if (val) val.value = d.material || '';
                        }
                    });

                    input.classList.add('border-success');
                    setTimeout(() => input.classList.remove('border-success'), 2000);

                } else {
                    document.getElementById('partNumber').value = '';
                    document.getElementById('partName').value   = '';
                    document.getElementById('articleId').value  = '';
                    document.getElementById('location').value   = '';

                    input.classList.add('border-danger');
                    setTimeout(() => input.classList.remove('border-danger'), 2000);
                    alert('Article tidak ditemukan.');
                }
            })
            .catch(() => {
                input.classList.remove('border-warning');
                alert('Gagal mengambil data artikel.');
            });
    });

});
</script>
@endpush