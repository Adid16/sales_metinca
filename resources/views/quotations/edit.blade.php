{{-- Include layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'PT. Metinca Prima Industrial Works')

{{-- Untuk menggunakan css --}}
@push('styles')
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
                <i class="bi bi-file-earmark-text-fill"></i> Edit Quotation
            </h5>
        </div>
    <section id="horizontal-input">
        <div class="row">
            <div class="col-md-12">
                    <div class="card-body">
                        <form action="{{ route('quotations.update', $quotation->id) }}" method="POST" id="quotationForm"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-2">Request Id</div>
                                    <div class="col-md-9 mb-2">
                                        <input type="text" name="request_id" id="idrequest" class="form-control form-control-sm"
                                            value="{{ $quotation->request_id ?? '' }}" readonly>
                                    </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">Customer Id</div>
                                    <div class="col-md-9 mb-2">
                                        <input type="text" name="customer_id" class="form-control form-control-sm"
                                            value="{{ $quotation->customer_id ?? '' }}" readonly>
                                    </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">Customer Name</div>
                                    <div class="col-md-9 mb-2">
                                        <input type="text" name="customer_name" id="customerName" class="form-control form-control-sm"
                                            value="{{ $quotation->customer->name ?? '' }}" readonly>
                                    </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">Company</div>
                                    <div class="col-md-9 mb-2">
                                        <input type="text" name="customer_company" id="company" class="form-control form-control-sm"
                                            value="{{ $quotation->company ?? ($quotation->requestProject->company ?? '') }}" readonly>
                                    </div>  
                            </div>

                            <div class="row">
                                <div class="col-md-2">Quotation No</div>
                                    <div class="col-md-9 mb-2">
                                        <input type="text" name="quotation_no" id="numberQuotation" class="form-control form-control-sm"
                                            value="{{ $quotation->quotation_no }}" readonly>
                                    </div>
                            </div>

                            {{-- MULTIPLE ITEMS CONTAINER (POPULATED WITH EXISTING DATA) --}}
                            <div id="item-container">
                                @foreach($quotation->items as $index => $item)
                                <div class="row item-row mb-2">
                                    <div class="col-md-2">Item</div>
                                    <div class="col-md-3">
                                        <input type="hidden" name="item_ids[]" value="{{ $item->id }}">
                                        <input type="text" name="item[]" class="form-control form-control-sm" value="{{ $item->item }}" placeholder="Item name" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="qty[]" class="form-control form-control-sm" value="{{ $item->qty }}" placeholder="Qty" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="price[]" step="0.01" min="0" class="form-control form-control-sm" value="{{ (int)$item->price }}" placeholder="Price" required>
                                    </div>
                                    <div class="col-md-2 d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-success btn-add-item">
                                            <i class="bi bi-plus-lg"></i> 
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger btn-remove">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="row">
                                <div class="col-md-2">Date Expired</div>
                                    <div class="col-md-9 mb-2">
                                        <input type="date" required name="date_expired" id="dateExpired" class="form-control form-control-sm flatpickr" 
                                            value="{{ \Carbon\Carbon::parse($quotation->date_expired ?? $quotation->expired_date)->format('Y-m-d') }}">
                                    </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">Notes</div>
                                    <div class="col-md-9 mb-4">
                                        <textarea name="notes" id="notes" cols="30" rows="2" class="form-control form-control-sm">{{ $quotation->notes ?? $quotation->message }}</textarea>
                                    </div>
                            </div>

                            <a href="{{ route('quotations.show', $quotation->id) }}" class="btn btn-danger btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button> 
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
        flatpickr('.flatpickr', {
            enableTime: false,
            dateFormat: "Y-m-d",
        });

        // Jalankan fungsi pengkondisian tombol trash saat pertama kali halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            updateRemoveButtons();
        });

        document.getElementById('item-container').addEventListener('click', function (e) {
            // Handle button PLUS
            if (e.target.closest('.btn-add-item')) {
                const container = document.getElementById('item-container');
                const newRow = document.createElement('div');
                newRow.className = 'row item-row mb-2';
                newRow.innerHTML = `
                    <div class="col-md-2">Item</div>
                    <div class="col-md-3">
                        <input type="hidden" name="item_ids[]" value="">
                        <input type="text" name="item[]" class="form-control form-control-sm" placeholder="Item name" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="qty[]" class="form-control form-control-sm" placeholder="Qty" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="price[]" step="0.01" min="0" class="form-control form-control-sm" placeholder="Price" required>
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