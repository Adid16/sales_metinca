@extends('layouts.app')

@section('title', 'PT. Metinca Prima Industrial Works')

@section('content')
    <form action="{{ route('articles.store') }}" method="POST" id="articleForm" enctype="multipart/form-data">
        @csrf

        <div class="row g-2 align-items-end">
            <div class="col-12">
                <div class="card detail-card">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-box-seam-fill"></i> Add Pricelist Product
                        </h5>
                    </div>

                    <div class="card-body">      
                        {{-- INPUT ARTICLE NUMBER --}}
                        <div class="row mt-2">
                            <div class="col-md-7">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <label for="article_no" class="col-sm-4 col-form-label font-weight-bold text-primary">Article No / Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm @error('article_no') is-invalid @enderror"
                                        id="article_no" name="article_no" value="{{ old('article_no') }}" placeholder="Ketik nomor artikel di sini... (Contoh: ART-BRK-01)" required>
                                    @error('article_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-5 gap-1 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-sm btn-end"><i class="fas fa-save me-1"></i>Save Price</button>
                                <a href="{{ route('articles.index') }}" class="btn btn-danger btn-sm"><i class="fas fa-times me-1"></i>Cancel</a>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- INTERNAL PART NO (Readonly dihapus agar bisa diedit/diisi) --}}
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <label for="part_number" class="col-sm-4 col-form-label">Internal Part No</label>
                                    <input type="text" class="form-with-autofill form-control form-control-sm" id="part_number" name="part_number" value="{{ old('part_number') }}">
                                </div>
                            </div>  
                        </div>

                        {{-- PART NAME --}}
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <label for="part_name" class="col-sm-4 col-form-label">Part Name</label>
                                    <input type="text" class="form-with-autofill form-control form-control-sm" id="part_name" name="part_name" value="{{ old('part_name') }}">
                                </div>
                            </div>
                        </div>

                        {{-- INDEX --}}
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <label for="index_no" class="col-sm-4 col-form-label">Index</label>
                                    <input type="text" class="form-with-autofill form-control form-control-sm" id="index_no" name="index_no" value="{{ old('index_no') }}">
                                </div>
                            </div>
                        </div>

                        {{-- BERAT --}}
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <label for="berat" class="col-sm-4 col-form-label">Berat (Kg)</label>
                                    <input type="number" step="0.01" class="form-with-autofill form-control form-control-sm" id="berat" name="berat" value="{{ old('berat') }}">
                                </div>
                            </div>
                        </div>

                        {{-- DIE NO --}}
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <label for="die_no" class="form-label col-sm-4 col-form-label">Die No</label>
                                    <input type="text" class="form-with-autofill form-control form-control-sm" id="die_no" name="die_no" value="{{ old('die_no') }}">
                                </div>
                            </div>
                        </div>

                        {{-- MATERIAL --}}
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <label for="material" class="form-label col-sm-4 col-form-label">Material</label>
                                    <input type="text" class="form-with-autofill form-control form-control-sm" id="material" name="material" value="{{ old('material') }}">
                                </div>
                            </div>
                        </div>

                        {{-- DRAWING NO --}}
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <label for="drawing_no" class="form-label col-sm-4 col-form-label">Drawing No</label>
                                    <input type="text" class="form-with-autofill form-control form-control-sm" id="drawing_no" name="drawing_no" value="{{ old('drawing_no') }}">
                                </div>
                            </div>
                        </div>

                        {{-- DRAWING REV --}}
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <label for="drawing_rev" class="form-label col-sm-4 col-form-label">Drawing Rev</label>
                                    <input type="text" class="form-with-autofill form-control form-control-sm" id="drawing_rev" name="drawing_rev" value="{{ old('drawing_rev') }}">
                                </div>
                            </div>
                        </div>

                        {{-- EFFECTIVE DATE --}}
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <label for="effective_date" class="form-label col-sm-4">Effective Date</label>
                                    <input type="date" class="form-with-autofill form-control form-control-sm" id="effective_date" name="effective_date" value="{{ old('effective_date') }}">
                                </div>
                            </div>
                        </div>

                        {{-- CUSTOMER SELECT (Kunci pointer dihampus agar Sales bisa memilih jika kosong) --}}
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <label for="customer_id" class="col-sm-4 col-form-label">Customer</label>
                                    <select class="form-with-autofill form-select form-select-sm" id="customer_id" name="customer_id">
                                        <option value="">-- Select Customer --</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- LOKASI PENGERJAAN --}}
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-0 d-flex align-items-start">
                                    <label for="lokasi_pengerjaan" class="form-label col-sm-4 col-form-label">Lokasi Pengerjaan</label>
                                    <input type="number" class="form-with-autofill form-control form-control-sm" id="lokasi_pengerjaan" name="lokasi_pengerjaan" value="{{ old('lokasi_pengerjaan') }}" style="width:40px;">            
                                    <div class="d-flex flex-wrap text-start small text-muted ms-2 mt-1">    
                                        <span class="me-2">1. PT. Metinca (JKT)</span>
                                        <span class="me-2">2. PT. Metal Castindo</span>
                                        <span class="me-2">3. PT. Metinca S3</span>
                                        <span>4. Valve</span>
                                    </div>       
                                </div>
                            </div>
                        </div>

                        {{-- REMARK --}}
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <label for="remark" class="col-sm-4 form-label">Remark</label>
                                    <textarea class="form-with-autofill form-control form-control-sm" id="remark" name="remark" rows="1">{{ old('remark') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- GAMBAR PRODUK (Tambahkan di sini) --}}
                        <div class="row mt-2">
                            <div class="col-md-7">
                                <div class="form-group mb-0 d-flex align-items-start">
                                    <label for="image" class="form-label col-sm-4 col-form-label">Gambar (Opsional)</label>
                                    <div class="w-100">
                                        <input type="file" class="form-control form-control-sm" id="image" name="image" accept="image/*">
                                        <div class="small text-muted mt-1">Format: JPG, JPEG, PNG (Maks. 2MB)</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- INPUT PRICES --}}
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-1 d-flex align-items-center">
                                    <label for="casting_price" class="form-label col-sm-4 col-form-label">Price Casting <span class="text-danger">*</span></label>
                                    <input type="number" id="casting_price" name="casting_price" class="form-control form-control-sm font-weight-bold" value="{{ old('casting_price', 0) }}" required>
                                </div>  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-1 d-flex align-items-center">
                                    <label for="machining_price" class="form-label col-sm-4 col-form-label">Price Machining <span class="text-danger">*</span></label>
                                    <input type="number" id="machining_price" name="machining_price" class="form-control form-control-sm font-weight-bold" value="{{ old('machining_price', 0) }}" required>
                                </div>  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-2 d-flex align-items-center">
                                    <label for="total_price" class="form-label col-sm-4 col-form-label font-weight-bold text-success">Total Price (Auto)</label>
                                    <input type="number" id="total_price" name="price" class="form-control form-control-sm bg-light font-weight-bold text-success border-success" value="{{ old('price', 0) }}" readonly>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    {{-- Tambahkan CDN SweetAlert2 ini di baris paling atas dalam push scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const articleInput = document.getElementById('article_no');
            
            const partNumberInput = document.getElementById('part_number');
            const partNameInput = document.getElementById('part_name');
            const indexInput = document.getElementById('index_no');
            const beratInput = document.getElementById('berat');
            const dieNoInput = document.getElementById('die_no');
            const materialInput = document.getElementById('material');
            const drawingNoInput = document.getElementById('drawing_no');
            const drawingRevInput = document.getElementById('drawing_rev');
            const effectiveDateInput = document.getElementById('effective_date');
            const customerSelect = document.getElementById('customer_id');
            const lokasiInput = document.getElementById('lokasi_pengerjaan');
            const remarkTextarea = document.getElementById('remark');

            const castingInput = document.getElementById('casting_price');
            const machiningInput = document.getElementById('machining_price');
            const totalInput = document.getElementById('total_price');

            function calculateTotal() {
                const casting = parseFloat(castingInput.value) || 0;
                const machining = parseFloat(machiningInput.value) || 0;
                totalInput.value = casting + machining;
            }

            castingInput.addEventListener('input', calculateTotal);
            machiningInput.addEventListener('input', calculateTotal);

            if (articleInput) {
                // ANTI RELOAD SAAT TEKAN ENTER
                articleInput.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.blur();
                    }
                });

                // PROSES AMBIL DATA
                articleInput.addEventListener('change', function () {
                    const articleNo = this.value.trim();

                    if (articleNo === '') {
                        clearForm();
                        return;
                    }

                    fetch(`/articles/requirements/${encodeURIComponent(articleNo)}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Not found');
                            }
                            return response.json();
                        })
                        .then(response => {
                            if (response.success && response.data) {
                                const data = response.data;
                                
                                // 1. CEK DULU APAKAH SUDAH PUNYA HARGA
                                // (Sesuaikan nama variabel data.casting_price dengan database Anda)
                                let casting = parseFloat(data.casting_price) || 0;
                                let machining = parseFloat(data.machining_price) || 0;
                                
                                if (casting > 0 || machining > 0) {
                                    // Hilangkan semua isi form & kosongkan input artikel
                                    clearForm();
                                    articleInput.value = ''; 
                                    
                                    // Munculkan Alert Card di tengah menggunakan SweetAlert2
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Data Ditemukan',
                                        text: 'Article sudah memiliki harga!',
                                        confirmButtonText: 'Oke Mengerti',
                                        confirmButtonColor: '#0d6efd'
                                    });
                                    
                                    // Stop eksekusi di sini agar form di bawah tidak jadi terisi
                                    return; 
                                }

                                // 2. JIKA BELUM ADA HARGA -> LAKUKAN AUTOFILL
                                partNumberInput.value = data.internal_part_no ?? '';
                                partNameInput.value = data.part_name ?? '';
                                indexInput.value = data.index_no ?? '';
                                beratInput.value = data.berat ?? '';
                                dieNoInput.value = data.die_no ?? '';
                                materialInput.value = data.material ?? '';
                                drawingNoInput.value = data.drawing_no ?? '';
                                drawingRevInput.value = data.drawing_rev ?? '';
                                effectiveDateInput.value = data.effective_date ?? '';
                                customerSelect.value = data.customer_id ?? '';
                                lokasiInput.value = data.lokasi_pengerjaan ?? '';
                                remarkTextarea.value = data.remark ?? '';
                                
                                // Set harga default 0 agar Sales siap mengisi
                                castingInput.value = 0;
                                machiningInput.value = 0;
                                calculateTotal();
                            }
                        })
                        .catch(error => {
                            // JIKA TIDAK KETEMU DI DB
                            clearForm();
                            console.log('Artikel baru / data belum ada dari divisi lain.');
                        });
                });
            }

            function clearForm() {
                const inputs = document.querySelectorAll('.form-with-autofill');
                inputs.forEach(input => input.value = '');
                
                // Reset harga kembali ke 0
                castingInput.value = 0;
                machiningInput.value = 0;
                totalInput.value = 0;
            }
        });
    </script>
@endpush