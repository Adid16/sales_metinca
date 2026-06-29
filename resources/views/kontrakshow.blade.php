    <div class="card detail-card">
        <div class="card-header py-3 bg-info text-black">
            <h5 class="card-title mb-0">
                <i class="bi bi-collection-fill"></i> Detail Contract Review Sheet
            </h5>
        </div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="page-content">
        <section class="row">
            <div class="col-12">

                {{-- ================= HEADER ================= --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h5 class="mb-1">Order No</h5>
                                <p class="fw-semibold">{{ $contract->order_no }}</p>
                            </div>
                            <div class="col-md-4">
                                <h5 class="mb-1">Contract No</h5>
                                <p class="fw-semibold">{{ $contract->contract_no }}</p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <h5 class="mb-1">Quotation No</h5>
                                <p class="fw-semibold">
                                    {{ $contract->quotation->quotation_no ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================= MAIN INFO ================= --}}
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Contract Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Customer</label>
                                <div class="form-control form-control-sm bg-light">
                                    {{ $contract->customer->name ?? '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Amendment No</label>
                                <div class="form-control form-control-sm bg-light">
                                    {{ $contract->amendment_no ?? '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Part No</label>
                                <div class="form-control form-control-sm bg-light">
                                    {{ $contract->part_no ?? '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Part Name</label>
                                <div class="form-control form-control-sm bg-light">
                                    {{ $contract->part_name ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================= REQUIREMENTS ================= --}}

                @foreach ($grouped as $dept => $items)
                    <div class="card mt-3 shadow-sm">
                        <div class="card-header">
                            <h4 class="card-title text-uppercase">{{ $dept }}</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="40">No</th>
                                        <th>Requirement</th>
                                        <th>Value / Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $i => $req)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $req->requirement }}</td>
                                            <td>{{ $req->requirement_value ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach

                {{-- ================= OTHERS COMMENT ================= --}}
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">Others Comment</h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            {{ $contract->others_comment ?? '-' }}
                        </p>
                    </div>
                </div>

                {{-- ================= ACTION ================= --}}
                <div class="card mt-3">
                    <div class="card-body text-end">
                        <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
                            Back
                        </a>
                        @if (auth()->user()->role == 'manager' && in_array($contract->status, ['created', 'revision']))
                            <a href="#" data-bs-target="#rejectModal" data-bs-toggle="modal"
                                class="btn btn-danger">Reject Contract</a>
                            <form action="{{ route('contracts.approve-manager', $contract->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">Approve <i class="bi bi-check"></i></button>
                            </form>
                        @endif

                        @if (in_array(auth()->user()->role, ['admin', 'staff']) && in_array($contract->status, ['created', 'revision']))
                            <a href="{{ route('contracts.edit', $contract->id) }}" class="btn btn-warning">
                                Edit
                            </a>
                        @endif
                    </div>
                </div>

            </div>
            <div class="modal fade" id="rejectModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content" id="modalContent">
                        <div class="modal-header text-white bg-danger">
                            <h5>Reject</h5>
                        </div>
                        <form action="{{ route('contracts.reject-manager', $contract->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="modal-body p-3">
                                <h5 class="text-secondary">Berikan alasan anda : </h5>
                                <textarea name="comment" id="comment" class="form-control"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success btn-sm">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>




    

    {{-- ===============================EDIT CONTRACT======================================================== --}}
    @extends('layouts.app')

@section('title', 'PT. Metinca Prima Industrial Works')

@push('styles')
{{-- <link rel="stylesheet" href="{{ asset('assets/css/all.css') }}"> --}}
@endpush

@section('content')

<div class="mb-3">
    <h3 class="card-title">Edit Contract</h3>
</div>

<form action="{{ route('contracts.update', $contract->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- ================= HEADER CONTRACT ================= --}}
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card card-kontrak">
                    <div class="card-header text-center">
                        <h3 class="card-title">TINJAUAN KONTRAK</h3>
                        <h4 class="card-title">NO : {{ $contract->order_no }}</h4>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            {{-- CUSTOMER --}}
                            <div class="col-md-6 col-12">
                                <div class="row align-items-center mb-2">
                                    <label class="col-4 col-form-label">CUSTOMER</label>
                                    <label class="col-1 col-form-label">:</label>
                                    <div class="col-7">
                                        <input type="hidden" name="customer_id" value="{{ $contract->customer_id }}">
                                        <input type="text" class="form-control form-control-sm"
                                            value="{{ $contract->customer->name ?? '-' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            {{-- ORDER NO --}}
                            <div class="col-md-6 col-12">
                                <div class="row align-items-center mb-2">
                                    <label class="col-4 col-form-label">ORDER NO</label>
                                    <label class="col-1 col-form-label">:</label>
                                    <div class="col-7">
                                        <input type="text" name="order_no"
                                            class="form-control form-control-sm"
                                            value="{{ old('order_no', $contract->order_no) }}">
                                    </div>
                                </div>
                            </div>

                            {{-- AMENDMENT --}}
                            <div class="col-md-6 col-12">
                                <div class="row align-items-center mb-2">
                                    <label class="col-4 col-form-label">AMENDMENT</label>
                                    <label class="col-1 col-form-label">:</label>
                                    <div class="col-7">
                                        <input type="text" name="amandment_no"
                                            class="form-control form-control-sm"
                                            value="{{ old('amandment_no', $contract->amandment_no) }}">
                                    </div>
                                </div>
                            </div>

                            {{-- PART NO --}}
                            <div class="col-md-6 col-12">
                                <div class="row align-items-center mb-2">
                                    <label class="col-4 col-form-label">PART NO</label>
                                    <label class="col-1 col-form-label">:</label>
                                    <div class="col-7">
                                        <input type="text" name="part_no"
                                            class="form-control form-control-sm"
                                            value="{{ old('part_no', $contract->part_no) }}">
                                    </div>
                                </div>
                            </div>

                            {{-- PART NAME --}}
                            <div class="col-md-6 col-12">
                                <div class="row align-items-center mb-2">
                                    <label class="col-4 col-form-label">PART NAME</label>
                                    <label class="col-1 col-form-label">:</label>
                                    <div class="col-7">
                                        <input type="text" name="part_name"
                                            class="form-control form-control-sm"
                                            value="{{ old('part_name', $contract->part_name) }}">
                                    </div>
                                </div>
                            </div>

                            {{-- QUOTATION ID (Hidden) --}}
                            <input type="hidden" name="quotation_id" value="{{ $contract->quotation_id }}">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= REQUIREMENTS ================= --}}
    @foreach (['sales', 'quality', 'ppc', 'design engineering'] as $dept)
    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="card-title text-uppercase">{{ $dept }}</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Requirement</th>
                            <th>Value</th>
                            <th style="width:80px">Action</th>
                        </tr>
                    </thead>
                    <tbody class="requirements-wrapper">
                        @php
                            $reqs = $contract->requirements->where('requirement_from', $dept);
                        @endphp

                        @forelse ($reqs as $index => $req)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <input type="hidden" name="requirements[{{ $req->id }}][id]" value="{{ $req->id }}">
                                <input type="hidden" name="requirements[{{ $req->id }}][from]" value="{{ $dept }}">
                                <input type="text" name="requirements[{{ $req->id }}][requirement]"
                                    class="form-control form-control-sm"
                                    value="{{ $req->requirement }}">
                            </td>
                            <td>
                                <input type="text" name="requirements[{{ $req->id }}][value]"
                                    class="form-control form-control-sm"
                                    value="{{ $req->requirement_value }}">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger remove-row">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No requirements</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach

    {{-- ================= ACTION ================= --}}
    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('contracts.index') }}" class="btn btn-light">Cancel</a>
        <button type="submit" class="btn btn-primary">
            Update Contract
        </button>
    </div>

</form>
@endsection

