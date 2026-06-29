    @extends('layouts.app')

    @section('title')
        PT. Metinca Prima Industrial Works
    @endsection

    @section('content')
    <div class="card shadow-sm">

        {{-- Header --}}
        <div class="card-header d-flex justify-content-between bg-info align-items-center py-3">
            <h5 class="mb-0 text-black fw-bold">
                <i class="bi bi-file-earmark-text-fill me-2"></i>Quotation Detail
            </h5>
            {{-- <div class="d-flex gap-2">
                @php
                    $allowedUser = auth()->user()->isCustomer();
                    $allowedStatus = in_array($quotation->status, ['sent', 'accepted']);
                @endphp
                @if ($allowedUser && $allowedStatus)
                    <a href="#" class="btn btn-sm btn-warning fw-semibold">
                        <i class="bi bi-chat-left-text me-1"></i>Negotiate
                    </a>
                    <a href="{{ route('purchase-orders.create') }}" class="btn btn-sm btn-light fw-semibold text-primary">
                        <i class="bi bi-bag-check me-1"></i>Create PO
                    </a>
                @endif
            </div> --}}
        </div>

        <div class="card-body px-4 py-4">

            {{-- Company Header Strip --}}
            {{-- <div class="border rounded p-3 mb-4" style="background: #f8f9fa; border-left: 5px solid #0d6efd !important;">--}}
                <div class="d-flex justify-content-between align-items-center"> 
                    <div>
                        <h6 class="mb-0 fw-bold text-uppercase" style="color: black; letter-spacing: 1px;">PT. Metinca Prima Industrial Works</h6>
                        <small class="text-muted">Manufacturing & Industrial Solutions</small>
                    </div>
                    <div class="d-flex gap-1 ">
                        @if (auth()->user()->isCustomer())
                        <a href="{{ route('negotiate.show', ['quotation' => $quotation->id]) }}" class="btn btn-sm btn-warning">
                            Negotiate
                        </a>
                        <a href="{{ route('purchase-orders.create', ['quotation_id' => $quotation->id]) }}" class="btn btn-sm btn-primary">
                            Create PO
                        </a>
                        @endif

                        {{-- @if (!auth()->user()->isCustomer())
                        <a href="#" class="btn btn-sm btn-warning fw-semibold">
                            View Negotiate
                        </a>
                        @endif --}}

                        {{-- Tambahkan di atas tombol action, setelah card header --}}
                        @if(auth()->user()->isStaff() || auth()->user()->isManager() || auth()->user()->isAdmin())
                        @if($quotation->status == 'created')
        <a href="{{ route('quotations.edit', $quotation->id) }}" class="btn btn-warning btn-sm text-dark font-weight-bold">
            <i class="bi bi-pencil-square"></i> Edit Quotation
        </a>
    @else
        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Quotation sudah dikirim ke customer dan sedang dalam tahap {{ $quotation->status }}, data tidak dapat diubah.">
            <button class="btn btn-secondary btn-sm" disabled>
                <i class="bi bi-lock-fill"></i> Quotation Locked
            </button>
        </span>
    @endif
                            <a href="{{ route('negotiate.show-nego', $quotation->id) }}" class="btn btn-sm btn-warning">
                                View Negotiate
                                @if($negotiations->count() > 0)
                                    <span class="badge bg-danger ms-1">{{ $negotiations->count() }}</span>
                                @endif
                            </a>
                        @endif

                        <a href="{{ route('quotations.export-pdf', $quotation->id) }}" class="btn btn-sm btn-danger">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </a>
                        <a href="{{ route('quotations.index') }}" class="btn btn-sm btn-light fw-semibold">
                            Back
                        </a>
                        {{-- <span class="badge px-3 py-2 fs-6
                            @if($quotation->status == 'draft') bg-secondary
                            @elseif($quotation->status == 'sent') bg-info text-dark
                            @elseif($quotation->status == 'accepted') bg-success
                            @elseif($quotation->status == 'rejected') bg-danger
                            @else bg-secondary @endif
                            text-uppercase fw-bold">
                            {{ $quotation->status }}
                        </span> --}}
                    </div>
                </div>
            {{-- </div> --}}

            <div class="row g-4">

                {{-- Kolom Kiri: Info Quotation --}}
                <div class="col-md-7">
                    <div class="card border mt-3">
                        <div class="card-header py-2 px-3" style="background: #e9ecef;">
                            <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.78rem;">
                                <i class="bi bi-file-text me-1"></i>Quotation Information 
                            </h6>
                        </div>
                        <div class="card-body py-2 px-3">
                            <div class="d-flex mb-1">
                                <span class="text-muted col-4">Quotation No</span>
                                <span class="text-muted col-1">:</span>
                                <span class="fw-semibold col-12">{{ $quotation->quotation_no }}</span>
                            </div>
                            <div class="d-flex mb-1">
                                <span class="text-muted col-4">Request ID</span>
                                <span class="text-muted col-1">:</span>
                                <span class="fw-semibold col-12">{{ $quotation->request_id ?? '-' }}</span>
                            </div>
                            <div class="d-flex mb-1">
                                <span class="text-muted col-4">Created Date</span>
                                <span class="text-muted col-1">:</span>
                                <span class="fw-semibold col-12">{{ \Carbon\Carbon::parse($quotation->created_at)->format('d F Y') }}</span>
                            </div>
                            <div class="d-flex">
                                <span class="text-muted col-4">Expired Date</span>
                                <span class="text-muted col-1">:</span>
                                <div class="fw-semibold col-8">
                                    {{ \Carbon\Carbon::parse($quotation->date_expired)->format('d F Y') }}
                                    <span class="badge bg-danger small">
                                        {{ \Carbon\Carbon::parse($quotation->date_expired)->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Info Customer --}}
                <div class="col-md-5">
                    <div class="card border mt-3">
                        <div class="card-header py-2 px-3" style="background: #e9ecef;">
                            <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.78rem;">
                                <i class="bi bi-person-badge me-1"></i>PIC Information
                            </h6>
                        </div>
                        <div class="card-body py-2 px-3">
                            <div class="d-flex mb-1">
                                <span class="text-muted col-3">PIC</span>
                                <span class="text-muted col-1">:</span>
                                <span class="col-8">{{ $quotation->customer->name ?? '-' }}</span>
                            </div>
                            <div class="d-flex mb-1">
                                <span class="text-muted col-3">Email</span>
                                <span class="text-muted col-1">:</span>
                                <span class="col-8">{{ $quotation->customer->email ?? '-' }}</span>
                            </div>
                            <div class="d-flex mb-1">
                                <span class="text-muted col-3">Company</span>
                                <span class="text-muted col-1">:</span>
                                <span class="col-8">{{ $customerAccount->company ?? '-' }}</span>
                            </div>
                            <div class="d-flex">
                                <span class="text-muted col-3">Phone</span>
                                <span class="text-muted col-1">:</span>
                                <span class="col-8">{{ $customerAccount->phone ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Item Quotation --}}
            <div class = "mt-0">
                <div class="card border">
                    <div class="card-header px-3 py-2" style="background: #e9ecef;">
                        <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.78rem;">
                            <i class="bi bi-list-ul me-1"></i>Pricelist Item Quotation
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="bg-light text-dark">
                                    <tr>
                                        <th width="5%" class="text-center py-2">No</th>
                                        <th class="text-center py-2">Item</th>
                                        <th width="10%" class="text-center py-2">Qty</th>
                                        <th width="20%" class="text-center py-2">Unit Price</th>
                                        <th width="20%" class="text-center py-2">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($quotation->items as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $item->item }}</td>
                                            <td class="text-center">{{ $item->qty }}</td>
                                            <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($item->qty * $item->price, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted fst-italic py-3">Tidak ada item</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot style="background: #f0f4ff;">
                                    <tr>
                                        <th colspan="4" class="text-end py-2">TOTAL</th>
                                        <th class="text-end py-2 fw-bold">
                                            Rp {{ number_format($quotation->items->sum(fn($i) => $i->qty * $i->price), 0, ',', '.') }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes & Attachments --}}
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border h-100">
                        <div class="card-header py-2 px-3" style="background: #e9ecef;">
                            <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.78rem;">
                                <i class="bi bi-sticky me-1"></i>Message
                            </h6>
                        </div>
                        <div class="card-body py-2 px-3">
                            <p class="mb-0 text-muted fst-italic">{{ $quotation->notes ?? 'Tidak ada catatan.' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border h-100">
                        <div class="card-header py-2 px-3" style="background: #e9ecef;">
                            <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.78rem;">
                                <i class="bi bi-paperclip me-1"></i>Request Attachments
                            </h6>
                        </div>
                        <div class="d-flex gap-1 align-content-start py-2 px-3">
                            @forelse ($quotation->request->attachments as $reqs)
                                <a href="/storage/{{ $reqs->file_path }}"  target="_blank">
                                    <i class="bi bi-file-earmark-pdf me-1"></i>{{ $reqs->document_name }}
                                </a>
                            @empty
                                <span class="text-muted fst-italic">Tidak ada lampiran.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer strip --}}
            <div class="mt-4 pt-3 border-top text-muted small d-flex justify-content-between">
                <span><i class="bi bi-clock me-1"></i>Created: {{ \Carbon\Carbon::parse($quotation->created_at)->format('d F Y, H:i') }} WIB</span>
                <span>{{ $quotation->quotation_no }}</span>
            </div>

        </div>
    </div>

        @endsection