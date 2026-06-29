{{-- Extend layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'PT. Metinca Prima Industrial Works')

{{-- Untuk menggunakan css --}}
@push('styles')
    <link rel="shortcut icon" href="{{ asset('assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
@endpush

{{-- Isi content --}}
@section('content')
<div class="card detail-card">
    <div class="card-header bg-info text-black py-3">
        <h5 class="card-title mb-0">
            <i class="bi bi-send-plus-fill"></i> Detail Request Project 
        </h5>
    </div>
    {{-- <div class="container-xl">
        <div class="page-header d-print-none">
            <div class="row align-items-right mb-3"> --}}
                {{-- <div class="col">
                    <h2 class="page-title">
                        Request Project Detail
                    </h2>
                </div> --}}
                {{-- <div class="col-md-12 d-flex align-items-center gap-1">
                    <div class="btn-group-action">
                       
                    </div>
                </div> --}}
            {{-- </div>
        </div>
    </div> --}}

     @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

    <section class="section">
        {{-- <div class="container-xl"> --}}
            <div class="row">
                <div class="col-lg-12">
                    {{-- Sales Information Card --}}
                    {{-- <div class="card detail-card mb-4"> --}}
                        {{-- <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-circle"></i> Sales Information
                            </h5>
                        </div> --}}
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <h5 class="card-title mb-0">Sender Request</h5>
                                <div class="d-flex gap-1 flex-wrap align-items-end justify-content-end ms-auto">
                                {{-- @if (!$requestProject->customer) --}}
                                    {{-- <span class="text-danger me-2">Belum ada data customer, silahkan buatkan terlebih dahulu</span> --}}
                                    {{-- <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalnewcustomer">
                                        <i class="bi bi-person-plus-fill"></i> Add User
                                    </button> --}}
                                {{-- @else --}}
                                
                                   @if(auth()->user()->isAdmin() || (auth()->user()->isManager() && auth()->user()->divisi === 'sales') || (auth()->user()->isStaff() && !auth()->user()->isCustomer()))
    {{-- CEK APAKAH PROJECT SUDAH DIAMBIL SALES ATAU BELUM --}}
    @if($requestProject->assignment)
        <a href="{{ route('quotations.create', ['request_id' => $requestProject->id]) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil"></i> Create Quotation
        </a>
    @else
        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Project harus diambil terlebih dahulu dengan mengklik tombol (+) di halaman list!">
            <button class="btn btn-secondary btn-sm" disabled>
                <i class="bi bi-lock-fill"></i> Create Quotation (Belum Di-assign)
            </button>
        </span>
    @endif
@endif
                                        
                                        <a href="{{ route('requests-project.index') }}" class="btn btn-sm btn-end btn-secondary">
                                            Back
                                        </a>
                                    

                                {{-- @endif --}}

                                @if (auth()->user()->isCustomer())
                                    <button type="button" class="btn btn-danger btn-sm " data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                @endif
                            </div>
                        </div>
                                <div class="row mb-2">
                                    <div class="col-md-3">Name</div>
                                    <div class="col-md-4">{{ $requestProject->name }}</div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">Company</div>
                                    <div class="col-md-4">
                                        {{ $requestProject->company }}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">Email:</div>
                                    <div class="col-md-6">
                                        <a href="mailto:{{ $requestProject->email }}">
                                            <i class="bi bi-envelope"></i> {{ $requestProject->email }}
                                        </a>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">Phone:</div>
                                    <div class="col-md-6">
                                        <a href="tel:{{ $requestProject->phone }}">
                                            <i class="bi bi-telephone"></i> {{ $requestProject->phone }}
                                        </a>
                                    </div>
                                </div>
                                
                                <hr class="border-dark opacity-50 mt-0">

                            <h5 class="card-title mb-0">Request Information</h5>
                                 <div class="row mb-2">
                                    <div class="col-md-3">Request ID</div>
                                    <div class="col-md-4"><span class="badge bg-primary">#{{ $requestProject->id }}</span></div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">Subject</div>
                                    <div class="col-md-4"><strong>{{ $requestProject->subject }}</strong></div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">Message</div>
                                    <div class="col-md-4"><strong>{{ $requestProject->message }}</strong></div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">Attachments</div>
                                        <div class="col-md-9">
                                            @if ($requestProject->attachments && count($requestProject->attachments) > 0)
                                            <ul class="list-group">
                                            @foreach ($requestProject->attachments as $attachment)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank">
                                                        <i class="bi bi-file-earmark-pdf"></i>
                                                        {{ $attachment->document_name ?? '' }}
                                                    </a>
                                                    <a href="{{ asset('storage/' . $attachment->file_path) }}" download
                                                        class="btn btn-sm btn-secondary inline-text">
                                                        <i class="bi bi-file-earmark-arrow-down"></i>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        @else
                                            <p>No attachments available.</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">Created Date</div>
                                    <div class="col-md-7">{{ $requestProject->created_at->format('d F Y H:i') }}</div>
                                </div>

                            <div class="row mb-2">
                                <div class="col-md-3">Last Updated</div>
                                <div class="col-md-7">{{ $requestProject->updated_at->format('d F Y H:i') }}</div>
                            </div>
                                <hr class="border-dark opacity-50 mt-0">

                            <h5 class="card-title mb-0">Receiver Request</h5>
                                 <div class="row mb-2">
                                    <div class="col-md-3">Sales Person</div>
                                    <div class="col-md-4">{{ $requestProject->assignment->sales->name ?? 'N/A' }}</div>
                                </div>

                                <div class="row mb-2">
                                    {{-- <div class="col-md-3">Email</div>
                                    <div class="col-md-4">{{ $requestProject->sales->email ?? 'N/A' }}</div> --}}
                                </div>
                                {{-- <hr class="border-dark opacity-50 mt-0"> --}}
                               
                        </div>
                        
                    {{-- </div> --}}

                    {{-- Request Details Card --}}
                    {{-- <div class="card detail-card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-file-text"></i> Request Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="detail-row">
                                <div class="detail-label">Request ID:</div>
                                <div class="detail-value">
                                    <span class="badge bg-primary">#{{ $requestProject->id }}</span>
                                </div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-label">Subject:</div>
                                <div class="detail-value">
                                    <strong>{{ $requestProject->subject }}</strong>
                                </div>
                            </div>

                            
                        </div>
                    </div> --}}

                    

                    {{-- Message Card --}}
                    {{-- <div class="card detail-card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-chat-dots"></i> Message
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="message-section">
                                <p>{{ $requestProject->message }}</p>
                            </div>
                        </div>
                    </div> --}}

                    {{-- attachment card --}}
                    {{-- <div class="card detail-card mt-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-paperclip"></i> Attachments
                            </h5>
                            <div class="card-body">
                                @if ($requestProject->attachments && count($requestProject->attachments) > 0)
                                    <ul class="list-group">
                                        @foreach ($requestProject->attachments as $attachment)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                    {{ $attachment->document_name ?? '' }}
                                                </a>
                                                <a href="{{ asset('storage/' . $attachment->file_path) }}" download
                                                    class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No attachments available.</p>
                                @endif
                            </div>
                        </div>
                    </div> --}}
                </div>

                {{-- Sidebar Actions --}}
               {{-- <div class="col-lg-4">
                    <div class="card detail-card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-gear"></i> Actions
                            </h5>
                        </div> --}}
                         {{-- <div class="card-body">
                            @if (!$requestProject->customer)
                                <span class="text-danger">Belum ada data customer, silahkan buatkan terlebih dahulu</span>
                                <button type="button" class="btn btn-primary my-2" data-bs-toggle="modal"
                                    data-bs-target="#modalnewcustomer">
                                    <i class="bi bi-person-plus-fill"></i> Add User
                                </button>
                            @else
                                @if (auth()->user()->isAdmin() || (auth()->user()->isStaff() && auth()->user()->divisi == 'sales'))
                                    <button type="button" class="btn btn-primary btn-sm w-100 mb-2" data-bs-toggle="modal"
                                        data-bs-target="#emailModal">
                                        <i class="bi bi-envelope"></i> Send Email
                                    </button>
                                    <a href="{{ route('quotations.create') }}" class="btn btn-warning btn-sm w-100 mb-2">
                                        <i class="bi bi-pencil"></i> Create Quotation
                                    </a>
                                     <a href="{{ route('requests-project.index') }}" class="btn btn-sm btn-end btn-secondary">
                                        Back
                                    </a>
                                @endif

                            @endif

                            @if (auth()->user()->isCustomer())
                                <button type="button" class="btn btn-danger btn-sm w-100 mb-2" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            @endif
                        </div>
                    </div> --}}

                    {{-- <div class="card detail-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle"></i> Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="info-badge">
                                <strong>Status:</strong><br>
                                <span class="badge bg-success">Active</span>
                            </div>
                            <div class="info-badge">
                                <strong>Priority:</strong><br>
                                <span class="badge bg-warning">Normal</span>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        {{-- </div> --}}
    </section>

    <div class="modal fade" id="modalnewcustomer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="background:lightblue;">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">New user</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('users.customer.request', $requestProject->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="customername">Name</label>
                            <input type="text" name="name" value="{{ old('name', $requestProject->name) }}" class="form-control form-control-sm" id="customername"
                                placeholder="Name">
                        </div>

                        <div class="form-group">
                            <label for="companyname">Company</label>
                            <input type="text" name="company" value="{{ old('company', $requestProject->company) }}" class="form-control form-control-sm" id="customername"
                                placeholder="Company">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" value="{{ old('email', $requestProject->email) }}" class="form-control form-control-sm" id="email"
                                placeholder="Email">
                        </div>

                        {{-- <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" name="username" class="form-control form-control-sm" id="Username" placeholder="Username">
                                    </div> --}}

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control form-control-sm" id="password"
                                placeholder="Password">
                        </div>
                        {{-- <input type="text" name="role" value="customer"> --}}
                        <div class="form-group">
                            <label for="role">Role</label>
                            {{-- <input type="text" name="role" value="Customer" class="form-control form-control-sm" id="role" readonly> --}}
                            <fieldset class="form-group">
                                <select class="form-select form-select-sm" id="basicSelect" name="role">
                                    <option selected>Select Role</option>
                                    <option value="customer">Customer</option>
                                </select>
                            </fieldset>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success btn-sm">Save</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Email Modal -->
    <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="emailModalLabel">Send Email</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="#" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="emailRecipient" class="form-label">Recipient Email</label>
                            <input type="email" class="form-control" id="emailRecipient"
                                value="{{ $requestProject->email }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="emailSubject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="emailSubject" name="subject"
                                placeholder="Enter email subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="emailMessage" class="form-label">Message</label>
                            <textarea class="form-control" id="emailMessage" name="message" rows="5" placeholder="Enter your message"
                                required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Send Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this request project? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="#" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Untuk menggunakan javascript --}}
@push('scripts')
    <script src="{{ asset('assets/static/js/pages/simple-datatables.js') }}"></script>
    <script>
        // Add any custom scripts here
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips if needed
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
@endpush
