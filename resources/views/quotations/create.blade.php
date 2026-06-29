{{-- Inlcude layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'PT. Metinca Prima Industrial Works')

{{-- Untuk menggunakan css --}}
@push('styles')
    {{-- contoh --}}
    <link rel="stylesheet" href="{{ asset('assets/extensions/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/quotation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
@endpush

{{-- Isi content --}}
@section('content')
    <div class="card detail-card">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-file-earmark-text-fill"></i> Add New Quotation
            </h5>
        </div>
    <section id="horizontal-input">
        <div class="row">
            <div class="col-md-12">
                    <div class="card-body">
                        <form action="{{ route('quotations.store') }}" method="POST" id="quotationForm"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class ="col-md-2">Request Id</div>
                                    <div class="col-md-9 mb-2">
                                        <input type="text" name="request_id" id="idrequest" class="form-control form-control-sm" aria-label="Sizing example input"
                                            value="{{ $requestProject->id ?? '' }}" readonly>
                                    </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">Customer Id</div>
                                    <div class="col-md-9 mb-2">
                                        <input type="text" name="customer_id" class="form-control form-control-sm" aria-label="Sizing example input"
                                            value="{{ $requestProject->customer_id ?? '' }}" readonly>
                                    </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">Customer Name</div>
                                    <div class="col-md-9 mb-2">
                                        <input type="text" name="customer_name" id="customerName" class="form-control form-control-sm" aria-label="Sizing example input"
                                            value="{{ $requestProject->name ?? '' }}" readonly>
                                    </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2 ">Company</div>
                                    <div class="col-md-9 mb-2">
                                        <input type="text" name="customer_company" id="company" class="form-control form-control-sm" aria-label="Sizing example input"
                                            value="{{ $customerAccount->company ?? '' }}" readonly>
                                    </div>  
                            </div>

                            <div class="row">
                                <div class="col-md-2">Quotation No</div>
                                    <div class="col-md-9 mb-2">
                                        <input type="text" name="quotation_no" id="numberQuotation" class="form-control form-control-sm" aria-label="Sizing example input"
                                            value="{{ $quotationNo }}" readonly>
                                    </div>
                            </div>

                            <div id="item-container">
                                <div class="row item-row mb-2">
                                    <div class="col-md-2">Item</div>
                                    <div class="col-md-3">
                                        <input type="text" name="item[]" class="form-control form-control-sm" placeholder="Item name">
                                    </div>
                                    {{-- <div class="col-md-2">Quantity</div> --}}
                                    <div class="col-md-2">
                                        <input type="number" name="qty[]" class="form-control form-control-sm" placeholder="Qty">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="price[]" step="0.01" min="0" class="form-control form-control-sm" placeholder="Price">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-sm btn-success btn-add-item">
                                        <i class="bi bi-plus-lg"></i> 
                                    </button>
                                        <button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">Date Expired</div>
                                    <div class="col-md-9 mb-2">
                                        <input type="date" required name="date_expired" id="dateExpired" class="form-control form-control-sm flatpickr">
                                    </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">Notes</div>
                                    <div class="col-md-9 mb-4">
                                        <textarea name="notes" id="" cols="30" rows="2" class="form-control form-control-sm"></textarea>
                                    </div>
                            </div>

                            <a href="{{ route('quotations.index') }}" class="btn btn-danger btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm">Create</button> 
                        </form>
                    </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/form-element-select.js') }}"></script>
    <script>
        flatpickr('.flatpickr-no-config', {
            enableTime: false,
            dateFormat: "Y-m-d",
        });
        const attachmentContainer = document.getElementById('attachment-container');

        document.getElementById('customerId').addEventListener('change', function() {
            const customerId = this.value;
            const requestSelect = document.getElementById('selectRequest');

            // reset dropdown request
            requestSelect.innerHTML = '<option value="">Loading...</option>';

            if (!customerId) {
                requestSelect.innerHTML = '<option value="">Pilih Customer dulu</option>';
                return;
            }

            fetch(`/requests-project/customer/${customerId}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    requestSelect.innerHTML = '<option value="">Pilih Request</option>';
                    var req = data.data;
                    req.forEach(request => {
                        const date = new Date(request.created_at);
                        const formatedDate = date.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: "short",
                            year: 'numeric'
                        });
                        const option = document.createElement('option');
                        option.value = request.id;
                        option.textContent = request.subject + " - " + formatedDate; // sesuaikan field
                        requestSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error(error);
                    requestSelect.innerHTML = '<option value="">Gagal load data</option>';
                });
        });

        document.getElementById('selectRequest').addEventListener('change', function() {
            var id = this.value;

            // Kosongkan kontainer saat memulai fetch baru
            attachmentContainer.innerHTML = '';

            fetch(`/requests-project/detail/${id}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    document.getElementById('requestMessage').innerText = data.data.message;

                    const attch = data.data.attachments;

                    attch.forEach(element => {
                        // Gunakan Template Literals (backtick) untuk mempermudah penulisan HTML
                        var linkHtml = `
                <a href="/storage/${element.file_path}" class="btn btn-sm btn-info" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> ${element.document_name}
                </a> `;

                        // Gunakan innerHTML += untuk menambah konten
                        attachmentContainer.innerHTML += linkHtml;
                    });
                })
                .catch(error => console.error('Error:', error)); // Tambahkan catch untuk debugging
        });
    </script>

    <script>
    document.getElementById('item-container').addEventListener('click', function (e) {
    // Handle button PLUS
    if (e.target.closest('.btn-add-item')) {
        const container = document.getElementById('item-container');
        const newRow = document.createElement('div');
        newRow.className = 'row item-row mb-2';
        newRow.innerHTML = `
            <div class="col-md-2">Item</div>
            <div class="col-md-3">
                <input type="text" name="item[]" class="form-control form-control-sm" placeholder="Item name">
            </div>
            <div class="col-md-2">
                <input type="number" name="qty[]" class="form-control form-control-sm" placeholder="Qty">
            </div>
            <div class="col-md-2">
                <input type="number" name="price[]" step="0.01" min="0" class="form-control form-control-sm" placeholder="Price">
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="button" class="btn btn-sm btn-success btn-add-item">
                    <i class="bi bi-plus-lg"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger btn-remove">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(newRow);
        updateRemoveButtons();
    }

    // Handle button TRASH
    if (e.target.closest('.btn-remove')) {
        e.target.closest('.item-row').remove();
        updateRemoveButtons();
    }
});

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.item-row');
    rows.forEach((row) => {
        const btn = row.querySelector('.btn-remove');
        btn.style.display = rows.length > 1 ? 'inline-block' : 'none';
    });
}
</script>
@endpush