@extends('layouts.app')

@section('title') Customers @endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center py-3"
        style="background-color: #0d6efd; color: white;">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-people-fill me-2"></i>CUSTOMER ACCOUNTS
        </h5>
        <a href="{{ route('customers.create') }}" class="btn btn-sm btn-light fw-semibold text-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Customer
        </a>
    </div>

    <div class="card-body">

        {{-- Alert --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Search --}}
        <form method="GET" action="{{ route('customers.index') }}" class="mb-3">
            <div class="input-group" style="max-width: 350px;">
                <input type="text" name="search" class="form-control form-control-sm"
                    placeholder="Cari nama, email, company..."
                    value="{{ $search ?? '' }}">
                <button class="btn btn-sm btn-primary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
                @if($search)
                    <a href="{{ route('customers.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-x"></i>
                    </a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle">
                <thead style="background-color: #0d6efd; color: white;">
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Company</th>
                        <th>Jabatan</th>
                        <th>Kota</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $i => $customer)
                        <tr>
                            <td class="text-center">{{ $customers->firstItem() + $i }}</td>
                            <td class="fw-semibold">{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone ?? '-' }}</td>
                            <td>{{ $customer->company ?? '-' }}</td>
                            <td>{{ $customer->position ?? '-' }}</td>
                            <td>{{ $customer->city ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('customers.show', $customer) }}"
                                    class="btn btn-sm btn-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('customers.edit', $customer) }}"
                                    class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('customers.destroy', $customer) }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin hapus customer ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted fst-italic py-3">
                                Tidak ada data customer.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-between align-items-center mt-2">
            <small class="text-muted">
                Menampilkan {{ $customers->firstItem() ?? 0 }}–{{ $customers->lastItem() ?? 0 }}
                dari {{ $customers->total() }} data
            </small>
            {{ $customers->links() }}
        </div>

    </div>
</div>
@endsection