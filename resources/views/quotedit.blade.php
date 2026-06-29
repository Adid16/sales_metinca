{{-- Inlcude layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'PT. Metinca Prima Industrial Works')

{{-- Untuk menggunakan css --}}
@push('styles')
    {{-- contoh --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/static/css/pages/dashboard.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/toastify-js/src/toastify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/quotation.css') }}">
@endpush

@php
    $isReadOnly = $quotation ? true : false;
@endphp

{{-- Isi content --}}
@section('content')
<section id="horizontal-input">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><center>CALCULATION PRICE FORM</center></h5>
                    <form action="{{ route('quotation.update', $quotation->id) }}" method="POST" id="quotationForm">
                    @csrf @method('PUT')
                        <div class="row"> 
                            <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">No :</span>
                                <input type="text" name="quotation_no" class="form-control" aria-label="Sizing example input" 
                                        value="{{ $quotation->quotation_no }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Date : </span>
                                <input type="date" name="date" class="form-control flatpickr-no-config"
                                    value="{{ $quotation->date }}" disabled>
                            </div>
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Company Name :</span>
                                <input type="text" name="company_name" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->company_name }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Description :</span>
                                <input type="text" name="description" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->description }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Material :</span>
                                <input type="text" name="material" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->material }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Quantity Required (pcs) :</span>
                                <input type="text" name="quantity_required_pcs" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->quantity_required_pcs }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Die Cavities :</span>
                                <input type="text" name="die_cavities" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->die_cavities }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Grade Type :</span>
                                <input type="text" name="grade_type" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->grade_type }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Form of Supply :</span>
                                <input type="text" name="form_of_supply" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->form_of_supply }}" disabled>
                            </div> 
                        </div>
                            
                        <div class="divider">
                            <div class="devider-text">Raw Material - Cost</div>
                        </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Qty Per Mould :</span>
                                <input type="text" name="qty_per_mould_pcs" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->qty_per_mould_pcs }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Pattern Wax :</span>
                                <input type="text" name="pattern_wax" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->pattern_wax }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Metal :</span>
                                <input type="text" name="metal" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->metal }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Soluble Wax :</span>
                                <input type="text" name="soluble_wax" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->soluble_wax }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Ceramic :</span>
                                <input type="text" name="ceramic" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->ceramic }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Runner Wax :</span>
                                <input type="text" name="runner_wax" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->runner_wax }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text bg-secondary text-white" id="inputGroup-sizing-sm">Total Raw Material Cost : </span>
                                <input type="text" name="total_raw_material_cost" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->total_raw_material_cost }}" disabled>
                            </div> 
                        </div>

                        <div class="divider">
                            <div class="devider-text">Labour Time - Minutes Per Mould</div>
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Injection :</span>
                                <input type="text" name="injection" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->injection }}" disabled>
                            </div> 
                        </div>    

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Cut Off :</span>
                                <input type="text" name="cut_off" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->cut_off }}" disabled>
                            </div> 
                        </div>
                                
                        <div class="col-sm-6 mb-0">
                            <div class="input-group input-group-sm my-0">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Cleaning/Trimming :</span>
                                <input type="text" name="cleaning" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->cleaning }}" disabled>
                            </div> 
                        </div>
                                
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">S/Cut Off :</span>
                                <input type="text" name="scut_off" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->scut_off }}" disabled>
                            </div> 
                        </div>
                                
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Assembly :</span>
                                <input type="text" name="assembly" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->assembly }}" disabled>
                            </div> 
                        </div>
                                
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Finishing :</span>
                                <input type="text" name="finishing" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->finishing }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Dipping :</span>
                                <input type="text" name="dipping" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->dipping }}" disabled>
                            </div> 
                        </div>
                                
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Heat Treatment :</span>
                                <input type="text" name="heat_treatment" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->heat_treatment }}" disabled>
                            </div> 
                       </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Dewaxing :</span>
                                <input type="text" name="dewaxing" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->dewaxing }}" disabled>
                            </div> 
                        </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3" disabled>
                                <span class="input-group-text" id="inputGroup-sizing-sm">Straight :</span>
                                <input type="text" name="straight" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->straight }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3" disabled>
                                <span class="input-group-text" id="inputGroup-sizing-sm">Burnout :</span>
                                <input type="text" name="burnout" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->burnout }}" disabled>
                            </div> 
                        </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Repair :</span>
                                <input type="text" name="repair" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->repair }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Melting :</span>
                                <input type="text" name="melting" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->melting }}" disabled>
                            </div> 
                        </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Blasting :</span>
                                <input type="text" name="blasting" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->blasting }}" disabled>
                            </div> 
                        </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Knockout :</span>
                                <input type="text" name="knockout" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->knockout }}" disabled>
                            </div> 
                        </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Inspect :</span>
                                <input type="text" name="inspect" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->inspect }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">W/Blast/Cer.Remove :</span>
                                <input type="text" name="w_blast" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->w_blast }}" disabled>
                            </div> 
                        </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text bg-secondary text-white" id="inputGroup-sizing-sm">Berat Casting : </span>
                                <input type="text" name="casting_weight" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->casting_weight }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text bg-secondary text-white" id="inputGroup-sizing-sm">Total Minutes Per Mould :</span>
                                <input type="text" name="total_minutes_per_mould" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->total_minutes_per_mould }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-12 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text bg-secondary text-white" id="inputGroup-sizing-sm">Total :</span>
                                <input type="text" name="total_minutes_mould" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->total_minutes_mould }}" disabled>
                            </div> 
                        </div>
                            
                        <div class="divider">
                            <div class="devider-text">Additional Work</div>
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Machining :</span>
                                <input type="text" name="machining_add" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->machining_add }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">X-Ray :</span>
                                <input type="text" name="x_ray" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->x_ray }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Crack Det :</span>
                                <input type="text" name="crack_det" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->crack_det }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Polish :</span>
                                <input type="text" name="polish" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->polish }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text bg-secondary text-white" id="inputGroup-sizing-sm">Total Minutes Per Mould :</span>
                                <input type="text" name="total_minutes_mould_add" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->total_minutes_mould_add }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text bg-secondary text-white" id="inputGroup-sizing-sm">Total :</span>
                                <input type="text" name="total_add" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->total_add }}" disabled>
                            </div> 
                        </div>

                        <div class="divider">
                            <div class="devider-text">Fixtures Required</div>
                        </div>
                            
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Wax :</span>
                                <input type="text" name="wax" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->wax }}" disabled>
                            </div>     
                        </div>        
                           
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Fixed Overheads USD :</span>
                                <input type="text" name="fixed_overheads_usd" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->fixed_overheads_usd }}" disabled>
                            </div> 
                        </div>

                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Straightening :</span>
                                <input type="text" name="straightening" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->straightening }}" disabled>
                            </div> 
                        </div>
                           
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">% Scrap USD :</span>
                                <input type="text" name="scrap_usd" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->scrap_usd }}" disabled>
                            </div> 
                        </div>
                                
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Machining :</span>
                                <input type="text" name="machining_fix" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->machining_fix }}" disabled>
                            </div> 
                        </div>
                                
                        <div class="col-sm-6 mb-1">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Total Mould Cost:</span>
                                <input type="text" name="total_mould_cost" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->total_mould_cost }}" disabled>
                            </div> 
                        </div>
                                
                        <div class="col-sm-6  offset-sm-6 mb-1" >
                            <div class="input-group input-group-sm mb-0" style="margin-right:100px;">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Piece Price USD  :</span>
                                <input type="text" name="piece_price_usd" class="form-control" aria-label="Sizing example input"
                                        value="{{ $quotation->piece_price_usd }}" disabled>
                            </div> 
                        </div>
                                    
                        <div style="margin-top:15px;">
                            <div class="form-group with-title mb-3">
                                <textarea class="form-control" name="sub_contracting" id="exampleFormControlTextarea1" rows="3" disabled>
                                    {{ $quotation->sub_contracting }}
                                </textarea>
                                <label style="font-size: 14px;">Sub-Contracting :</label>
                            </div>
                        

                        <div class="form-group with-title mb-3">
                            <textarea class="form-control" name="director_comment_approval" id="exampleFormControlTextarea1" rows="3" disabled>
                                {{ $quotation->director_comment_approval }}
                            </textarea>
                            <label style="font-size: 14px;">Director (Comment/Approval) :</label>
                        </div>
                                    
                        <div class="col-sm-12">
                            <input type="file" name="attachments" class="multiple-files-filepond" multiple>
                            <small class="text-muted">Current file: {{ $quotation->attachments }}</small>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" id="btnedit" class="btn btn-primary me-1 mb-1" @if(!$isReadOnly) style="display:none;" @endif>Edit</button>
                            <button type="submit" id="btnsbmt" class="btn btn-success me-1 mb-1" @if($isReadOnly) style="display:none;" @endif>Submit</button>
                            <button type="button" id="btnreset" class="btn btn-light-secondary me-1 mb-1" @if($isReadOnly) style="display:none;" @endif>Reset</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnEdit = document.getElementById('btnedit');
        const btnSubmit = document.getElementById('btnsbmt');
        const btnReset = document.getElementById('btnreset');
        const mainForm = document.getElementById('quotationForm'); // Definisikan form utama
        const inputs = document.querySelectorAll('#quotationForm input, #quotationForm textarea');

        // Fungsi saat tombol EDIT diklik
        if(btnEdit) {
            btnEdit.addEventListener('click', function () {
                // 1. Hapus readonly dari semua input
                inputs.forEach(el => {
                    // Cek agar input Quotation No tetap readonly jika diperlukan
                    el.disabled = false;
                    el.removeAttribute('readonly');
                });

                // 2. Atur visibilitas tombol
                btnEdit.style.display = 'none';
                btnSubmit.style.display = 'inline-block';
                btnReset.style.display = 'inline-block';
                
                // 3. Fokus ke input pertama
                if(inputs.length > 0) inputs[1].focus(); // index 1 karena index 0 biasanya token csrf atau nomor
            });
        }

        // Fungsi saat tombol RESET/CANCEL diklik
        if(btnReset) {
            btnReset.addEventListener('click', function () {
                if(confirm('Apakah Anda yakin ingin membatalkan perubahan?')) {
                    // 1. Reset isi form ke nilai awal (reload halaman adalah cara paling aman untuk reset data dari DB)
                    // Atau gunakan mainForm.reset() jika ingin sekedar kosongkan, tapi lebih baik reload untuk ambil data DB
                    window.location.reload(); 

                    /* Opsi jika tidak mau reload (Manual reset UI):
                    mainForm.reset(); 
                    inputs.forEach(el => el.setAttribute('readonly', true));
                    btnEdit.style.display = 'inline-block';
                    btnSubmit.style.display = 'none';
                    btnReset.style.display = 'none';
                    */
                }
            });
        }
    });
</script>

<script src="{{ asset('assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-image-filter/filepond-plugin-image-filter.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond/filepond.js') }}"></script>
<script src="{{ asset('assets/extensions/toastify-js/src/toastify.js') }}"></script>
<script src="{{ asset('assets/static/js/pages/filepond.js') }}"></script>
@endsection