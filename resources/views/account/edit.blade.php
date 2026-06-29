@extends('layouts.app')
@section('title')PT. Metinca Prima Industrial Works 
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-warning py-3">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-person-circle me-2"></i>Edit My Account
        </h5>
    </div>
    <div class="card-body px-4 py-4">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('account.update') }}" method="POST">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Info Akun (readonly) --}}
            <div class = "page-content">
                <section class = "row">
                    <div class = "col-12">
                        <div class = "d-flex justify-content-between align-items-center">
                            <h5 class="mb-02 fw-bold"> 
                                <i class="bi bi-person me-1"></i>Detail Account
                            </h5>  
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-success btn-sm fw-semibold">
                                    Save
                                </button>
                            </div>                          
                        </div>
                        <div class="border rounded p-3 mt-2" style="border-left: 4px solid #c0392b !important;">
                            <div class = "row g-2">
                                <div class = "col-md-4 pe-4">
                                    <div class = "form-group mb-0 d-flex align-items-center text-black">
                                        <label class="col-sm-3 col-form-label">Name</label>
                                        <input class = "form-control form-control-sm bg-light" value="{{ $user->name }}" readonly>
                                    </div>
                                </div>
                                <div class = "col-md-4 ps-4">
                                    <div class = "form-group mb-0 d-flex align-items-center text-black">
                                        <label class="col-sm-3 col-form-label">Email</label>
                                        <input class = "form-control form-control-sm bg-light" value="{{ $user->email }}" readonly>
                                    </div>
                                </div>
                                <div class = "col-md-4 ps-4">
                                    <div class = "form-group mb-0 d-flex align-items-center text-black">
                                        <label class="col-sm-3 col-form-label">Position</label>
                                        <input class = "form-control form-control-sm" 
                                            name="position" value="{{ old('position', $account->position) }}" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class = "d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 mt-4 fw-bold"> 
                                <i class="bi bi-building me-1"></i>Company Detail
                            </h5>  
                        </div>
                        <div class="border rounded p-3 mt-2" style="border-left: 4px solid #e67e22 !important;">
                            <div class = "row g-2">
                                <div class = "col-md-6 pe-3">
                                <div class = "form-group mb-0 d-flex align-items-center text-black">
                                    <label class="col-sm-3 col-form-label">Company</label>
                                    <input class = "form-control form-control-sm" 
                                        name="company" value="{{ $account->company ?? '-' }}">
                                </div>
                            </div>
                            <div class = "col-md-5 ps-4">
                                <div class = "form-group mb-0 d-flex align-items-center text-black">
                                    <label class="col-sm-3 col-form-label">Phone</label>
                                    <input class = "form-control form-control-sm" 
                                        name="phone" value="{{ $account->phone ?? '-' }}">
                                </div>
                            </div>
                            <div class = "col-md-6 pe-3">
                                <div class = "form-group mb-0 d-flex align-items-center text-black">
                                    <label class="col-sm-3 col-form-label">City</label>
                                    <input class = "form-control form-control-sm" 
                                        name="city" value="{{ old('city', $account->city) }}">
                                </div>
                            </div>
                            <div class = "col-md-5 ps-4">
                                <div class = "form-group mb-0 d-flex align-items-center text-black">
                                    <label class="col-sm-3 col-form-label">Fax</label>
                                    <input class = "form-control form-control-sm" 
                                        name="fax" value="{{ old('fax', $account->fax) }}">
                                </div>
                            </div>
                            <div class = "col-md-6 pe-3">
                                <div class = "form-group mb-0 d-flex align-items-center text-black">
                                    <label class="col-sm-3 col-form-label">Address</label>
                                    <textarea class = "form-control form-control-sm" 
                                        name="address" rows="3" >{{ old('address', $account->address) }}
                                    </textarea>
                                </div>
                            </div>
                            <div class = "col-md-5 ps-4">
                                <div class = "form-group mb-0 d-flex align-items-center text-black">
                                    <label class="col-sm-3 col-form-label">ZIP</label>
                                    <input class = "form-control form-control-sm" 
                                        name="zip" value="{{ old('zip', $account->zip) }}">
                                </div>
                            </div> 
                            </div>
                        </div>

                    </div>
                </section>
            </div>
        </form>
    </div>
</div>
@endsection