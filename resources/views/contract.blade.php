{{-- Inlcude layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'PT. Metinca Prima Industrial Works') 

{{-- Untuk menggunakan css --}}
@push('styles')
    {{-- contoh --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/static/css/pages/dashboard.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/all.css') }}">
@endpush

{{-- Isi content UNTUK NEW CONTRACT--}}
@section('content')
<div class="mb-3">
    <h3 class="card-title">Contract</h3>
</div>
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card card-kontrak">
                <div class="card-header">
                    <h3 class="card-title"><center>TINJAUAN KONTRAK</center></h3>
                    <h4 class="card-title"><center>NO :  </center></h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="row align-items-center">
                                        <label for="customer-column" class="col-4 col-form-label">CUSTOMER</label>
                                        <label for="customer-column" class="col-1 col-form-label">:</label>
                                        <div class="col-7">
                                            <input type="text" id="customer-horizontal" class="form-control form-control-sm" name="fcustomer-column">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="row align-items-center">
                                        <label for="customer-column" class="col-4 col-form-label">DATA RECORD</label>
                                        <label for="customer-column" class="col-1 col-form-label">:</label>
                                        <div class="col-7">
                                            <input type="text" id="customer-horizontal" class="form-control form-control-sm" name="fcustomer-column">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="row align-items-center">
                                        <label for="customer-column" class="col-4 col-form-label">ORDER NO</label>
                                        <label for="customer-column" class="col-1 col-form-label">:</label>
                                        <div class="col-7">
                                            <input type="text" id="customer-horizontal" class="form-control form-control-sm" name="fcustomer-column">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="row align-items-center">
                                        <label for="customer-column" class="col-4 col-form-label">PART NO</label>
                                        <label for="customer-column" class="col-1 col-form-label">:</label>
                                        <div class="col-7">
                                            <input type="text" id="customer-horizontal" class="form-control form-control-sm" name="fcustomer-column">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="row align-items-center">
                                        <label for="customer-column" class="col-4 col-form-label">AMANDEMENT</label>
                                        <label for="customer-column" class="col-1 col-form-label">:</label>
                                        <div class="col-7">
                                            <input type="text" id="customer-horizontal" class="form-control form-control-sm" name="fcustomer-column">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="row align-items-center">
                                        <label for="customer-column" class="col-4 col-form-label">PART NAME</label>
                                        <label for="customer-column" class="col-1 col-form-label">:</label>
                                        <div class="col-7">
                                            <input type="text" id="customer-horizontal" class="form-control form-control-sm" name="fcustomer-column">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="row align-items-center">
                                        <label for="customer-column" class="col-4 col-form-label">LOCATION</label>
                                        <label for="customer-column" class="col-1 col-form-label">:</label>
                                        <div class="col-7">
                                            <input type="text" id="customer-horizontal" class="form-control form-control-sm" name="fcustomer-column">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="row align-items-center">
                                        <label for="customer-column" class="col-4 col-form-label">ARTIKEL NO</label>
                                        <label for="customer-column" class="col-1 col-form-label">:</label>
                                        <div class="col-7">
                                            <input type="text" id="customer-horizontal" class="form-control form-control-sm" name="fcustomer-column">
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- <div class = "mt-4">
                                    <h5>Departemen Sales</h5>
                                </div>

                                <div class = "mt-4">
                                    <h5>Departemen Quality</h5>
                                </div>
                                
                                <div class = "mt-4">
                                    <h5>Departemen PPC</h5>
                                </div>        
                                
                                <div class = "mt-4">
                                    <h5>Departemen Sales</h5>
                                </div> --}}
    
                            </div>
                    </div>
                </div>
            </div>
            <div class="card card-sales shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">SALES</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form">
                            <div class="row">
                                <div class="bg-white p-6">
                                    
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card card-quality shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">QUALITY</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form">
                            <div class="row">
                                <div class="col-md-6 col-12">

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card card-ppc shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">PPC</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form">
                            <div class="row">
                                <div class="col-md-6 col-12">

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- <div class="card card-development shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">DEVELOPMENT</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form">
                            <div class="row">
                                <div class="card border-custom-orange rounded-3 overflow-hidden shadow-sm"> --}}
            
            <div class="bg-custom-orange p-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-box">
                        DE
                    </div>
                    <div>
                        <h2 class="h4 text-white fw-bold mb-0">Design Engineering</h2>
                        <small class="text-white opacity-75">Department DE - Requirements</small>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-add-req px-3 py-2 d-flex align-items-center gap-2">
                    <i class="bi bi-plus-lg"></i> Add Requirement
                </button>
            </div>

            <div class="card-body p-4 bg-white">
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No.</th>
                                <th>Requirement</th>
                                <th class="text-center" style="width: 80px;">Check</th>
                                <th>Value / Description</th>
                                <th class="text-center" style="width: 80px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach($requirements as $index => $req) --}}
                            <tr>
                                <td></td>
                                <td>
                                    <input type="text" 
                                           name="requirements[][label]" 
                                           class="form-control form-control-sm" 
                                           value="" 
                                           placeholder="Nama requirement">
                                </td>
                                <td class="text-center">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="requirements[][checked]" 
                                           value="1" 
                                           style="width: 1.2em; height: 1.2em;">
                                </td>
                                <td>
                                    <input type="text" 
                                           name="requirements[][value]" 
                                           class="form-control form-control-sm text-muted" 
                                           placeholder="Detail atau nilai requirement">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-link text-danger p-0">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                            {{-- @endforeach --}}
                        </tbody>
                    </table>
                </div>

                <div class="action-box p-3 rounded-3 mt-4">
                    <label for="remarkDE" class="form-label fw-semibold text-secondary small">
                        Action Required / Remark untuk Design Engineering
                    </label>
                    <textarea class="form-control focus-ring focus-ring-warning" 
                              id="remarkDE" 
                              name="remark" 
                              rows="3" 
                              placeholder="Tambahkan catatan atau instruksi khusus untuk departemen ini..."></textarea>
                </div>

            </div>
        </div>
                            {{-- </div>
                        </form>
                    </div>
                </div>
            </div> --}}






                {{-- <div class="card-header">
                    <h3 class="card-title"><center>TINJAUAN KONTRAK</center></h3>
                    <h4 class="card-title"><center>NO :  </center></h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form">
                            <div class="row"> --}}

        </div>
    </div>
    </form>
</section>
@endsection