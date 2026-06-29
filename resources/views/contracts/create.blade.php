@extends('layouts.app')

@section('title', 'PT. Metinca Prima Industrial Works')

@section('content')

{{-- Perbaikan: Ditambahkan atribut enctype="multipart/form-data" agar form bisa mengirim file PDF --}}
<form action="{{ route('contracts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">

                {{-- ================= CARD 1: HEADER UTAMA ================= --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-collection-fill me-2"></i>Create New Contract Review Sheet
                        </h5>
                    </div>
                </div>

                {{-- ================= CARD 2: LEMBAR TINJAUAN KONTRAK + INPUTAN ================= --}}
                <div class="card mb-0">
                    <div class="card-header text-center py-3">
                        <h4 class="card-title mb-1 fw-bold">LEMBAR TINJAUAN KONTRAK</h4>
                        <h6 class="mb-0 text-muted">NO : {{ $po->quotation->quotation_no ?? '-' }}</h6>
                    </div>
                    <div class="card-body px-4 py-3">

                        <input type="hidden" name="customer_id" value="{{ $po->customer_id }}">
                        <input type="hidden" name="quotation_id" value="{{ $po->quotation->id }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small">CUSTOMER</label>
                                <input type="text" class="form-control form-control-sm bg-light"
                                    value="{{ $po->customer->name ?? '' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small">DATA RECORD</label>
                                <input type="text" class="form-control form-control-sm bg-light"
                                    id="dataRecord" name="data_record" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small">ORDER NO <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm bg-light" name="order_no" value="{{ $po->po_no ?? '' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small">PART NO</label>
                                <input type="text" class="form-control form-control-sm bg-light" id="partNumber" name="part_no" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small">AMANDMENT NO</label>
                                <input type="text" class="form-control form-control-sm" name="amendment_no">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small">PART NAME</label>
                                <input type="text" class="form-control form-control-sm bg-light" id="partName" name="part_name" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small">LOCATION</label>
                                <input type="text" class="form-control form-control-sm bg-light" id="location" name="location" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold small">ARTICLE</label>
                                <input type="hidden" id="articleId" name="article_id">
                                <input type="text" class="form-control form-control-sm" id="articleInput" placeholder="Ketik kode article lalu tekan enter">
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
                        $requirements = $deptRequirement[$dept] ?? [];
                        $color        = $deptColors[$dept]       ?? 'black';
                        $icon         = $deptIcons[$dept]        ?? 'bi-list-ul';
                    @endphp

                    <div class="card shadow-sm mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center py-2"
                            style="border-bottom: 2px solid {{ $color }}; background:#6c757d;">
                            <h6 class="mb-0 fw-bold text-uppercase"
                                style="font-size:0.82rem; letter-spacing:1px; color:black;">
                                <i class="bi {{ $icon }} me-1"></i>{{ $dept }}
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
                                                name="requirements[{{ $dept }}][{{ $index }}][requirement_from]"
                                                value="{{ $dept }}">
                                            <input type="text"
                                                name="requirements[{{ $dept }}][{{ $index }}][requirement]"
                                                class="form-control form-control-sm"
                                                value="{{ $req['requirement'] }}">
                                        </td>
                                        <td>
                                            <input type="text"
                                                name="requirements[{{ $dept }}][{{ $index }}][requirement_value]"
                                                class="form-control form-control-sm"
                                                value="{{ $req['requirement_value'] }}">
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
                @endforeach

                {{-- ================= CARD TERAKHIR: OTHERS COMMENT + UPLOAD FILE ================= --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header py-2" style="background:#6c757d;">
                        <h6 class="mb-0 fw-bold text-uppercase" style="font-size:0.82rem; letter-spacing:1px; color:black;">
                            Others / Comment
                        </h6>
                    </div>
                    <div class="card-body">
                        <textarea name="others_comment" class="form-control mt-2" rows="3" placeholder="Tulis komentar tambahan..."></textarea>

                        {{-- Perbaikan: Komponen Input Upload PO PDF Asli ditempatkan di bawah komentar --}}
                        <div class="mt-3">
                            <label class="form-label mb-1 fw-semibold small text-dark">
                                 UPLOAD DOKUMEN PO (PDF) <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="po_pdf" class="form-control form-control-sm" accept="application/pdf">
                            <small class="text-muted" style="font-size: 11px;">* Format file wajib PDF (Maksimal 2MB)</small>
                        </div>

                        {{-- BUTTONS --}}
                        <div class="d-flex justify-content-end mt-3 gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            <a href="{{ route('purchase-orders.index') }}" class="btn btn-sm btn-danger">Back</a>
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

    // AUTO ISI DATA RECORD TANGGAL HARI INI
    const today = new Date();
    const day   = String(today.getDate()).padStart(2, '0');
    const monthNames = ['January','February','March','April','May','June',
        'July','August','September','October','November','December'];
    const month = monthNames[today.getMonth()];
    const year  = today.getFullYear();
    document.getElementById('dataRecord').value = day + ' ' + month + ' ' + year;

    // ADD REQUIREMENT ROW DYNAMICALLY
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

    // AUTO FILL KETIKA ARTICLE NO DI ENTER
    document.getElementById('articleInput').addEventListener('keydown', function (e) {
        if (e.key !== 'Enter') return;
        e.preventDefault();

        const articleNo = this.value.trim();
        if (!articleNo) return;

        const input = this;
        input.classList.remove('border-success', 'border-danger');
        input.classList.add('border-warning');

        fetch(`/articles/requirements/${encodeURIComponent(articleNo)}`)
            .then(res => {
                if (!res.ok) throw new Error('Not found');
                return res.json();
            })
            .then(response => {
                input.classList.remove('border-warning');

                if (response.success && response.data) {
                    const d = response.data;

                    document.getElementById('partNumber').value = d.internal_part_no ?? d.part_number ?? '';
                    document.getElementById('partName').value   = d.part_name   || '';
                    document.getElementById('articleId').value  = d.id          || '';

                    const locationMap = {
                        1: 'PT. Metinca (Jakarta)',
                        2: 'PT. Metal Castindo',
                        3: 'PT. Metinca S3',
                        4: 'Valve',
                    };
                    document.getElementById('location').value = locationMap[d.lokasi_pengerjaan] || '';

                    // AUTO FILL PRICE DI KARTU DEPARTEMEN SALES
                    document.querySelectorAll('input[name^="requirements[sales]"]').forEach(inp => {
                        if (inp.name.includes('[requirement]') && inp.value.toLowerCase() === 'price') {
                            const idx = inp.name.match(/requirements\[sales\]\[(\d+)\]\[requirement\]/)[1];
                            const val = document.querySelector(`input[name="requirements[sales]2[${idx}][requirement_value]"]`) 
                                     || document.querySelector(`input[name="requirements[sales][${idx}][requirement_value]"]`);
                            if (val) val.value = d.price || '0';
                        }
                    });

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
                    clearHeaderFields(input);
                }
            })
            .catch(() => {
                input.classList.remove('border-warning');
                clearHeaderFields(input);
            });
    });

    // FIELD CLEANER FUNCTION
    function clearHeaderFields(input) {
        document.getElementById('partNumber').value = '';
        document.getElementById('partName').value   = '';
        document.getElementById('articleId').value  = '';
        document.getElementById('location').value   = '';

        input.classList.add('border-danger');
        setTimeout(() => input.classList.remove('border-danger'), 2000);
        alert('Nomor Article tidak ditemukan di sistem Pricelist!');
    }
});
</script>
@endpush