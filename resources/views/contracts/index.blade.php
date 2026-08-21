@extends('layouts.app')

@section('title', 'PT. Metinca Prima Industrial Works')

@push('styles')
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/statustabel.css') }}">
@endpush

@section('content')
    <div class="card detail-card shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-send-plus-fill me-2"></i>Contract Review Sheet
            </h5>
        </div>

        <div class="page-content">
            <section class="row">
                <div class="col-12">

                    {{-- FLASH MESSAGE --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3">
                            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="card-body py-2">
                        <form class="row g-2 align-items-center mt-0" method="GET" action="{{ route('contracts.index') }}">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-1">
                                    <label class="form-label small mb-0 text-nowrap">From : </label>
                                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $filters['start_date'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-1">
                                    <label class="form-label small mb-0 text-nowrap">To : </label>
                                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $filters['end_date'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-auto">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">Status</option>
                                    <option value="created" {{ (isset($filters['status']) && $filters['status']=='created') ? 'selected' : '' }}>Created</option>
                                    <option value="revision" {{ (isset($filters['status']) && $filters['status']=='revision') ? 'selected' : '' }}>Revision</option>
                                    <option value="done" {{ (isset($filters['status']) && $filters['status']=='done') ? 'selected' : '' }}>Done</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="dept" class="form-select form-select-sm">
                                    <option value="">Departements</option>
                                    <option value="sales" {{ (isset($filters['dept']) && $filters['dept']=='sales') ? 'selected' : '' }}>Sales</option>
                                    <option value="quality" {{ (isset($filters['dept']) && $filters['dept']=='quality') ? 'selected' : '' }}>Quality</option>
                                    <option value="ppc" {{ (isset($filters['dept']) && $filters['dept']=='ppc') ? 'selected' : '' }}>PPC</option>
                                    <option value="design engineering" {{ (isset($filters['dept']) && $filters['dept']=='design engineering') ? 'selected' : '' }}>Development Engineering</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                <button type="submit" formaction="{{ route('contracts.export') }}" class="btn btn-sm btn-success">Export</button>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle table-bordered" id="table1">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="40">No</th>
                                        <th class="text-center">Req Id</th>
                                        <th class="text-center">Quotation No</th>
                                        <th class="text-center">PO No</th>
                                        <th class="text-center">Tinjauan Kontrak</th>
                                        <th class="text-center">Part No</th>
                                        <th class="text-center">Part Name</th>
                                        <th class="text-center">Manager Sales</th>
                                        <th class="text-center">Manager Quality</th>
                                        <th class="text-center">Manager PPC</th>
                                        <th class="text-center">Manager DE</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center" width="120">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($contracts as $index => $contract)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-sm bg-primary">{{ $contract->quotation->request_id ?? $contract->id }}</span>
                                            </td>
                                            <td>{{ $contract->quotation->quotation_no ?? '-' }}</td>
                                            <td class="fw-semibold">{{ $contract->order_no }}</td>
                                            <td class="fw-semibold text-secondary">{{ $contract->contract_no }}</td>

                                            {{-- DIBACA SPESIFIK SESUAI KONTRAK ITEM BARANG --}}
                                            <td>{{ $contract->part_no ?? $contract->internalItem->part_no ?? '-' }}</td>
                                            <td class="fw-bold">{{ $contract->part_name ?? $contract->internalItem->item ?? '-' }}</td>

                                            <td class="text-center">{!! $contract->sales_approver ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-exclamation-circle-fill text-warning"></i>' !!}</td>
                                            <td class="text-center">{!! $contract->quality_approver ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-exclamation-circle-fill text-warning"></i>' !!}</td>
                                            <td class="text-center">{!! $contract->ppc_approver ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-exclamation-circle-fill text-warning"></i>' !!}</td>
                                            <td class="text-center">{!! $contract->dev_engineering_approver ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-exclamation-circle-fill text-warning"></i>' !!}</td>
                                            
                                            <td class="text-center">
                                                @if($contract->status == 'amended')
                                                    <span class="badge bg-light-danger text-danger fw-bold">Amended</span>
                                                @else
                                                    <span class="badge bg-light-primary text-primary">{{ ucfirst($contract->status) ?? '-' }}</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('contracts.show', $contract->id) }}"
                                                        class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>

                                                    @if($contract->status == 'amended')
                                                        <span class="badge bg-secondary ms-1 d-flex align-items-center" data-bs-toggle="tooltip" title="Kontrak terunci (Amended)">
                                                            <i class="bi bi-lock-fill"></i>
                                                        </span>
                                                    @else
                                                        @php
                                                            $all4Approved = $contract->sales_approver 
                                                                         && $contract->quality_approver 
                                                                         && $contract->ppc_approver 
                                                                         && $contract->dev_engineering_approver;
                                                        @endphp
                                                        @if(auth()->user()->isAdmin() || (auth()->user()->isStaff() && auth()->user()->divisi == 'sales'))
                                                            @if($all4Approved && !in_array($contract->status, ['production', 'done']))
                                                                <form action="{{ route('contracts.finalize', $contract->id) }}" method="POST" class="d-inline" onsubmit="return confirm('4 Manager telah menyetujui. Memfinalisasi kontrak ini ke tahap In Production (Dalam Produksi)?')">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-success text-white fw-bold" data-bs-toggle="tooltip" title="Finalisasi Kontrak ke Tahap In Production (Dalam Produksi)">
                                                                        <i class="bi bi-gear-fill"></i> Finalisasi
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            <a href="{{ route('contracts.edit', $contract->id) }}"
                                                                class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Edit Kontrak">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Contract Review Sheet ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Hapus Kontrak">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif

                                                    @if( (auth()->user()->role == 'staff' && strtolower(auth()->user()->divisi) == 'sales') || ($contract->sales_approver && $contract->ppc_approver && $contract->quality_approver && $contract->dev_engineering_approver) )
                                                        <a target="_blank" href="{{ route('contract.pdf', $contract->id) }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Download PDF">
                                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="13" class="text-center text-muted fst-italic py-4">
                                                Belum ada data Contract Review Sheet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- PAGINATION --}}
                        <div class="mt-3">
                            {{ $contracts->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/simple-datatables.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
@endpush