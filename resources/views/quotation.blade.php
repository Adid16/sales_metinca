{{-- Inlcude layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'PT. Metinca Prima Industrial Works')

{{-- Untuk menggunakan css --}}
@push('styles')
    {{-- contoh --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/static/css/pages/dashboard.css') }}"> --}}
    <link rel="stylesheet" href="assets/extensions/filepond/filepond.css">
    <link rel="stylesheet" href="assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css">
    <link rel="stylesheet" href="assets/extensions/toastify-js/src/toastify.css">
    <link rel="stylesheet" href="assets/extensions/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="{{ asset('assets/css/quotation.css') }}">
@endpush

{{-- Isi content --}}
@section('content')
<div class="mb-3">
    <h3>New Quotation</h3>
</div>
<section id="horizontal-input">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><center>CALCULATION PRICE FORM</center></h5>
                    <form action="{{ route('quotation.store') }}" method="POST" id="quotationForm" enctype="multipart/form-data">
                    @csrf
                        <div class="row"> 
                            <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">No :</span>
                                <input type="text" name="quotation_no" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Date : </span>
                                <input type="date" name="date" class="form-control flatpickr-no-config">
                            </div>
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Company Name :</span>
                                <input type="text" name="company_name" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Description :</span>
                                <input type="text" name="description" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Material :</span>
                                <input type="text" name="material" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Quantity Required (pcs) :</span>
                                <input type="text" name="quantity_required_pcs" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Die Cavities :</span>
                                <input type="text" name="die_cavities" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Grade Type :</span>
                                <input type="text" name="grade_type" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Form of Supply :</span>
                                <input type="text" name="form_of_supply" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                            
                        <div class="divider">
                            <div class="devider-text">Raw Material - Cost</div>
                        </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Qty Per Mould :</span>
                                <input type="text" name="qty_per_mould_pcs" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Pattern Wax :</span>
                                <input type="text" name="pattern_wax" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Metal :</span>
                                <input type="text" name="metal" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Soluble Wax :</span>
                                <input type="text" name="soluble_wax" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Ceramic :</span>
                                <input type="text" name="ceramic" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Runner Wax :</span>
                                <input type="text" name="runner_wax" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text bg-secondary text-white" id="inputGroup-sizing-sm">Total Raw Material Cost : </span>
                                <input type="text" name="total_raw_material_cost" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="divider">
                            <div class="devider-text">Labour Time - Minutes Per Mould</div>
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Injection :</span>
                                <input type="text" name="injection" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>    

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Cut Off :</span>
                                <input type="text" name="cut_off" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                                
                        <div class="col-sm-6 mb-0">
                            <div class="input-group input-group-sm my-0">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Cleaning/Trimming :</span>
                                <input type="text" name="cleaning" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                                
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">S/Cut Off :</span>
                                <input type="text" name="scut_off" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                                
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Assembly :</span>
                                <input type="text" name="assembly" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                                
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Finishing :</span>
                                <input type="text" name="finishing" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Dipping :</span>
                                <input type="text" name="dipping" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                                
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Heat Treatment :</span>
                                <input type="text" name="heat_treatment" class="form-control" aria-label="Sizing example input">
                            </div> 
                       </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Dewaxing :</span>
                                <input type="text" name="dewaxing" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Straight :</span>
                                <input type="text" name="straight" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Burnout :</span>
                                <input type="text" name="burnout" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Repair :</span>
                                <input type="text" name="repair" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Melting :</span>
                                <input type="text" name="melting" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Blasting :</span>
                                <input type="text" name="blasting" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Knockout :</span>
                                <input type="text" name="knockout" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Inspect :</span>
                                <input type="text" name="inspect" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">W/Blast/Cer.Remove :</span>
                                <input type="text" name="w_blast" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text bg-secondary text-white" id="inputGroup-sizing-sm">Berat Casting : </span>
                                <input type="text" name="casting_weight" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text bg-secondary text-white" id="inputGroup-sizing-sm">Total Minutes Per Mould :</span>
                                <input type="text" name="total_minutes_per_mould" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text bg-secondary text-white" id="inputGroup-sizing-sm">Total :</span>
                                <input type="text" name="total_minutes_mould" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                            
                        <div class="divider">
                            <div class="devider-text">Additional Work</div>
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Machining :</span>
                                <input type="text" name="machining_add" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">X-Ray :</span>
                                <input type="text" name="x_ray" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Crack Det :</span>
                                <input type="text" name="crack_det" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Polish :</span>
                                <input type="text" name="polish" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text bg-secondary text-white" id="inputGroup-sizing-sm">Total Minutes Per Mould :</span>
                                <input type="text" name="total_minutes_mould_add" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text bg-secondary text-white" id="inputGroup-sizing-sm">Total :</span>
                                <input type="text" name="total_add" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="divider">
                            <div class="devider-text">Fixtures Required</div>
                        </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Wax :</span>
                                <input type="text" name="wax" class="form-control" aria-label="Sizing example input">
                            </div>     
                        </div>        
                           
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Fixed Overheads USD :</span>
                                <input type="text" name="fixed_overheads_usd" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Straightening :</span>
                                <input type="text" name="straightening" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                           
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">% Scrap USD :</span>
                                <input type="text" name="scrap_usd" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                                
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Machining :</span>
                                <input type="text" name="machining_fix" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                                
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Total Mould Cost:</span>
                                <input type="text" name="total_mould_cost" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                                
                        <div class="col-sm-6  offset-sm-6 mb-1" >
                            <div class="input-group input-group-sm mb-0" style="margin-right:100px;">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Piece Price USD  :</span>
                                <input type="text" name="piece_price_usd" class="form-control" aria-label="Sizing example input">
                            </div> 
                        </div>
                                    
                        <div style="margin-top:15px;">
                            <div class="form-group with-title mb-3">
                                <textarea class="form-control" name="sub_contracting" id="exampleFormControlTextarea1" rows="3"></textarea>
                                <label style="font-size: 14px;">Sub-Contracting :</label>
                            </div>
                        

                        <div class="form-group with-title mb-3">
                            <textarea class="form-control" name="director_comment_approval" id="exampleFormControlTextarea1" rows="3"></textarea>
                            <label style="font-size: 14px;">Director (Comment/Approval) :</label>
                        </div>
                                    
                        {{-- <div class="col-sm-12">
                            <input type="file" name="attachments" class="multiple-files-filepond" multiple>
                        </div> --}}

                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                        </div>
                        {{-- <a href ="#" class="btn btn-primary">Submit</a>
                        <a href ="#" class="btn btn-danger">Clear</a>                                --}}

                    </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</section>
<script src="assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js"></script>
<script src="assets/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js"></script>
<script src="assets/extensions/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js"></script>
<script src="assets/extensions/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js"></script>
<script src="assets/extensions/filepond-plugin-image-filter/filepond-plugin-image-filter.min.js"></script>
<script src="assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js"></script>
<script src="assets/extensions/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js"></script>
<script src="assets/extensions/filepond/filepond.js"></script>
<script src="assets/extensions/toastify-js/src/toastify.js"></script>
<script src="assets/static/js/pages/filepond.js"></script>
@endsection