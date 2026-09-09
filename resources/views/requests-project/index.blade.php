{{-- Inlcude layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'PT. Metinca Prima Industrial Works')

{{-- Untuk menggunakan css --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/statustabel.css') }}">
@endpush


{{-- Isi content --}}
@section('content')
<div class="card detail-card">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-send-plus-fill"></i> Request Projects
            </h5>
        </div>

        <section class="section">
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
        {{-- <div class="card"> --}}
            <div class="card-body py-0">
                <form class="mb-3" method="GET" action="{{ route('requests-project.index') }}">
                    <div class="row g-2 align-items-end mt-0">
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="d-flex align-items-center gap-1">
                                <label class="form-label small mb-0 text-nowrap">From : </label>
                                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $filters['start_date'] ?? '' }}" placeholder="From">
                            </div>
                        </div>
                        
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="d-flex align-items-center gap-1">
                                <label class="form-label small mb-0 text-nowrap">To : </label>
                                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $filters['end_date'] ?? '' }}" placeholder="To">
                            </div>
                        </div>
                        
                        @if(auth()->user()->isAdmin() || auth()->user()->isManager() || (auth()->user()->role == 'staff' && auth()->user()->divisi == 'sales') || auth()->user()->isCustomer())
                        <div class="col-12 col-sm-6 col-lg-2">
                            <div class="d-flex align-items-center gap-1">
                                <select name="sales_id" class="form-select form-select-sm">
                                    <option value="">All Sales</option>
                                    @foreach($sales as $s)
                                        <option value="{{ $s->id }}" {{ (isset($filters['sales_id']) && $filters['sales_id']==$s->id) ? 'selected' : '' }}>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
            
                        <div class="col-12 col-sm-6 col-lg-4 d-flex flex-wrap gap-1">
                            @if (auth()->user()->isAdmin() || auth()->user()->isManager() || (auth()->user()->role == 'staff' && auth()->user()->divisi == 'sales') || auth()->user()->isCustomer())
                                <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
                                <a href="{{ route('requests-project.index') }}" class="btn btn-sm btn-danger">Clear</a>
                                <button type="submit" formaction="{{ route('requests-project.export') }}" class="btn btn-success btn-sm btn-end text-end">Export</button>
                            @endif
                            @if (auth()->user()->isCustomer())
                                <a href="{{ route('requests-project.create') }}" class="btn btn-primary btn-sm"> New </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover text-nowrap" id="table1">
                    <thead>
                        <tr>
                            <th><center>No</center></th>
                            <th><center>Req Id</center></th>
                            <th><center>Name</center></th>
                            <th><center>Company</center></th>
                            <th><center>Email</center></th>
                            <th><center>Subject</center></th>
                            <th><center>Request Date</center></th>
                            <th><center>Sales PIC</center></th>
                            <th><center>Action</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($projects as $project)
                            <tr>
                                <td><center>{{ $loop->iteration }}</center></td>
                                <td><center><span class="badge badge-sm bg-primary">{{ $project->id }}</span></center></td>
                                <td><center>{{ $project->name ?: ($project->customer->name ?? '-') }}</center></td>
                                <td><center>{{ $project->company ?: ($project->customer->account->company ?? ($project->customer->company ?? '-')) }}</center></td>
                                <td><center>{{ $project->email }}</center></td>
                                <td><center>{{ $project->subject }}</center></td>
                                <td><center>{{ $project->created_at->format('d F Y ') }}</center></td>
                                <td><center>
                                    {{-- MODUL 3: Badge Status Penugasan Tiket --}}
                                    @if($project->assignment)
                                        <span class="badge bg-success" title="Sudah diklaim oleh {{ $project->assignment->sales->name ?? 'Sales' }}">
                                            <i class="bi bi-person-check-fill me-1"></i>{{ $project->assignment->sales->name ?? 'Assigned' }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-exclamation-circle-fill me-1"></i>Unassigned
                                        </span>
                                    @endif
                                </center></td>
                                <td><center>
                                    <a href="{{ route('requests-project.show', $project->id) }}" class="btn btn-sm btn-info">         
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    @if(auth()->user()->isAdmin() || (auth()->user()->role == 'staff' && auth()->user()->divisi == 'sales'))                                        @if (!$project->assignment)
                                        {{-- KETIKA SUDAH DIAMBIL MAKA BUTTON TAMBAH DISABLE --}}
                                            <form action="{{ route('requests-project.assign', $project->id) }}" method="POST" class="d-inline">                                        
                                          @csrf
                                        {{-- KETIKA BELUM DIAMBIL BUTTON PLUS BISA DIKLIK --}}
                                                <button type="submit" class="btn btn-sm btn-primary"
                                                    onclick="return confirm('Ambil request project ini?')">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </form>
                                                @endif
                                    @endif 
                                    
                                </center></td>
                            </tr>
                        @empty
    <tr>
        <td colspan="9" class="text-center">No data available</td>
    </tr>
@endforelse
                    </tbody>
                </table>
            </div>
        {{-- </div> --}}
    </section>
    @push('scripts')
        <script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
        <script src="{{ asset('assets/static/js/pages/simple-datatables.js') }}"></script>
    @endpush
@endsection