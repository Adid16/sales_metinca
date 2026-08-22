{{-- Extend layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'PT. Metinca Prima Industrial Works')

{{-- Untuk menggunakan css --}}
@push('styles')
    <link rel="shortcut icon" href="{{ asset('assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <style>
        .form-card {
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .form-section-title {
            font-weight: 600;
            color: #495057;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e9ecef;
        }

        .required-field::after {
            content: ' *';
            color: #dc3545;
        }

        .form-help-text {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        .info-badge {
            display: inline-block;
            background-color: #e7f3ff;
            border-left: 3px solid #0d6efd;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border-radius: 0.25rem;
        }

        .info-badge strong {
            color: #0d6efd;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css') }}">
@endpush

{{-- Isi content --}}
@section('content')
    {{-- <div class="container-xl">
        <div class="page-header d-print-none">
            <div class="row align-items-center mb-3">
                <div class="col">
                    <h2 class="page-title">
                        Create Request Project
                    </h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('requests-project.index') }}" class="btn btn-secondary">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div> --}}

    <section class="section">
        <div class="container-xl">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card form-card">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-plus-circle"></i> New Request Project Form
                            </h5>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            {{-- Display validation errors --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <h4 class="alert-title">
                                        <i class="bi bi-exclamation-triangle"></i> Validation Errors
                                    </h4>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('requests-project.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                {{-- Sales Person (Optional) --}}
                                <div class="mt-3 mb-3">
                                    <label for="sales_id" class="form-label required-field">Sales Person</label>
                                    <select class="form-select @error('sales_id') is-invalid @enderror" id="sales_id" name="sales_id" required>
                                        {{-- <option value="">-- Select Role --</option> --}}
                                        <option value="sales" {{ old('sales_id') == 'sales' ? 'selected' : '' }}>
                                            Sales
                                        </option>
                                    </select>
                                    @error('sales_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-help-text">Sales person will be assigned automatically</small>
                                </div>

                                <h6 class="form-section-title">
                                    <i class="bi bi-person"></i> PIC Requester Information
                                </h6>

                                {{-- Name --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label required-field">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') ?? auth()->user()->name }}"
                                        placeholder="Enter requester name" required
                                        {{ auth()->user()->isCustomer() ? 'readonly' : '' }}>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label for="email" class="form-label required-field">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') ?? auth()->user()->email }}"
                                        placeholder="Enter email address" required
                                        {{ auth()->user()->isCustomer() ? 'readonly' : '' }}>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Phone (Auto-fill dari Account jika Customer) --}}
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" 
                                        value="{{ old('phone') ?? (auth()->user()->account->phone ?? '') }}"
                                        placeholder="Enter phone number (e.g., +62 xxx xxxx xxxx)"
                                        {{ auth()->user()->isCustomer() ? 'readonly' : '' }}>
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Company (Auto-fill dari User jika Customer) --}}
                                <div class="mb-3">
                                    <label for="company" class="form-label">Company Name</label>
                                    <input type="text" class="form-control @error('company') is-invalid @enderror"
                                        id="company" name="company"
                                        value="{{ old('company') ?? (auth()->user()->account->company ?? auth()->user()->company ?? '') }}"
                                        placeholder="Enter company name"
                                        {{ auth()->user()->isCustomer() ? 'readonly' : '' }}>
                                    @error('company')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Hidden: customer_id --}}
                                @if(auth()->user()->isCustomer())
                                    <input type="hidden" name="customer_id" value="{{ auth()->id() }}">
                                @endif

                                <h6 class="form-section-title">
                                    <i class="bi bi-file-text"></i> Request Details
                                </h6>

                                {{-- Subject --}}
                                <div class="mb-3">
                                    <label for="subject" class="form-label required-field">Subject</label>
                                    <select class="form-select @error('subject') is-invalid @enderror" id="subject"
                                        name="subject" required>
                                        <option value="">-- Select Subject --</option>
                                        <option value="quotation" {{ old('subject') == 'quotation' ? 'selected' : '' }}>
                                            Quotation
                                        </option>
                                        <option value="other project"
                                            {{ old('subject') == 'other project' ? 'selected' : '' }}>
                                            Other Project
                                        </option>
                                        <option value="meeting"
                                            {{ old('subject') == 'meeting' ? 'selected' : '' }}>
                                            Meeting
                                        </option>
                                    </select>
                                    @error('subject')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Message --}}
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="1"
                                        placeholder="Enter your message or project details">{{ old('message') }}</textarea>
                                    @error('message')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-help-text">Provide details about your request or project</small>
                                </div>

                                {{-- Attachment --}}
                                <div class="mb-3">
                                    <label for="attachment" class="form-label required-field">Attachment</label>
                                    <input type="file" required multiple data-max-files="3"
                                        class="form-control filepond multiple-files-filepond with-validation-filepond @error('attachment') is-invalid @enderror"
                                        id="attachment" name="attachment[]" accept=".pdf">
                                    @error('attachment')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-help-text">Upload relevant files (Max: 5MB)</small>
                                </div>

                                {{-- Form Actions --}}
                                <div class="form-footer">
                                    <a href="{{ route('requests-project.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-lg"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg"></i> Create Request
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Sidebar Information --}}
                <div class="col-lg-4">
                    <div class="card form-card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0 text-danger">
                                <i class="bi bi-info-circle"></i> Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="info-badge">
                                <strong>Required Fields:</strong><br>
                                Fields marked with <span style="color: #dc3545;">*</span> are required
                            </div>
                            <div class="info-badge">
                                <strong>Subject Types:</strong><br>
                                <ul class="mb-0" style="margin-top: 0.5rem; padding-left: 1.5rem;">
                                    <li>Quotation - Request for price quotation</li>
                                    <li>Other Project - General project inquiry</li>
                                    <li>Meet and Greet - Initial meeting request</li>
                                </ul>
                            </div>
                            <div class="info-badge">
                                <strong>Tips:</strong><br>
                                <ul class="mb-0" style="margin-top: 0.5rem; padding-left: 1.5rem;">
                                    <li>Be specific in your message</li>
                                    <li>Include relevant project details</li>
                                    <li>Provide accurate contact information</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

{{-- Untuk menggunakan javascript --}}
@push('scripts')
    {{-- <script src="{{ asset('assets/static/js/pages/simple-datatables.js') }}"></script> --}}
    <script src="{{ asset('assets/extensions/filepond/filepond.js') }}"></script>
    <script
        src="{{ asset('assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}">
    </script>
    <script
        src="{{ asset('assets/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}">
    </script>
    <script>
        // Filepond: Multiple Files
        FilePond.create(document.querySelector(".multiple-files-filepond"), {
            credits: null,
            allowImagePreview: false,
            allowMultiple: true,
            allowFileEncode: false,
            required: false,
            storeAsFile: true,
        });

        // FilePond.create(document.querySelector(".with-validation-filepond"), {
        //     credits: null,
        //     allowImagePreview: false,
        //     allowMultiple: true,
        //     allowFileEncode: false,
        //     required: true,
        //     acceptedFileTypes: ["image/png"],
        //     fileValidateTypeDetectType: (source, type) =>
        //         new Promise((resolve, reject) => {
        //             // Do custom type detection here and return with promise
        //             resolve(type)
        //         }),
        //     storeAsFile: true,
        // });

        document.addEventListener('DOMContentLoaded', function() {
            // Form validation feedback
            (function() {
                'use strict'
                window.addEventListener('load', function() {
                    var forms = document.querySelectorAll('.needs-validation')
                    Array.prototype.slice.call(forms).forEach(function(form) {
                        form.addEventListener('submit', function(event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }
                            form.classList.add('was-validated')
                        }, false)
                    })
                }, false)
            })()
        });
    </script>
@endpush
