@extends('layouts.app')

@section('title', 'PT. Metinca Prima Industrial Works')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Articles</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('content')
<div class="card detail-card">
    <div class="card-header bg-warning text-black py-3">
        <h5 class="card-title mb-0">
            <i class="bi bi-box-seam-fill"></i> Update Detail Product
        </h5>
    </div>

    {{-- PERBAIKAN: Ditambahkan enctype agar berkas PDF bisa terunggah saat update --}}
    <form action="{{ route('articles.update', $article->id) }}" method="POST" id="articleForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-12">
                <div class="card-body">
                    {{-- INTERNAL PART NO --}}
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="part_number" class="col-sm-4 col-form-label">Internal Part No</label>
                                {{-- PERBAIKAN: Mengambil nilai dari $article->internal_part_no --}}
                                <input type="text" class="form-control form-control-sm @error('part_number') is-invalid @enderror"
                                    id="part_number" name="part_number"
                                    value="{{ old('part_number', $article->internal_part_no) }}" required>
                                @error('part_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-5 gap-1 d-flex justify-content-end align-items-center">
                            <button type="submit" class="btn btn-warning btn-sm text-black font-weight-bold">
                                <i class="fas fa-save"></i> Update Data
                            </button>
                            <a href="{{ route('articles.show', $article->id) }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>

                    {{-- ARTICLE --}}
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="article_no" class="col-sm-4 col-form-label">Article</label>
                                <input type="text" class="form-control form-control-sm @error('article_no') is-invalid @enderror"
                                    id="article_no" name="article_no"
                                    value="{{ old('article_no', $article->article_no) }}" required>
                                @error('article_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- PART NAME --}}
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="part_name" class="col-sm-4 col-form-label">Part Name</label>
                                <input type="text" class="form-control form-control-sm @error('part_name') is-invalid @enderror"
                                    id="part_name" name="part_name" value="{{ old('part_name', $article->part_name) }}">
                                @error('part_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- TAMBAHAN: KOLOM INDEX --}}
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="index_no" class="col-sm-4 col-form-label">Index</label>
                                <input type="text" class="form-control form-control-sm" id="index_no" name="index_no" value="{{ old('index_no', $article->index_no) }}">
                            </div>
                        </div>
                    </div>

                    {{-- TAMBAHAN: KOLOM BERAT --}}
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="berat" class="col-sm-4 col-form-label">Berat (Kg)</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="berat" name="berat" value="{{ old('berat', $article->berat) }}">
                            </div>
                        </div>
                    </div>

                    {{-- CUSTOMER SELECT --}}
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="customer_id" class="col-sm-4 col-form-label">Customer</label>
                                <select class="form-select form-select-sm @error('customer_id') is-invalid @enderror"
                                    id="customer_id" name="customer_id">
                                    <option value="">-- Select Customer --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id', $article->customer_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- DIE NO --}}
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="die_no" class="col-sm-4 col-form-label">Die No</label>
                                <input type="text" class="form-control form-control-sm" id="die_no" name="die_no" value="{{ old('die_no', $article->die_no) }}">
                            </div>
                        </div>
                    </div>

                    {{-- MATERIAL --}}
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="material" class="col-sm-4 col-form-label">Material</label>
                                <input type="text" class="form-control form-control-sm" id="material" name="material" value="{{ old('material', $article->material) }}">
                            </div>
                        </div>
                    </div>

                    {{-- LOKASI PENGERJAAN --}}
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="lokasi_pengerjaan" class="col-sm-4 col-form-label">Lokasi Pengerjaan</label>
                                <input type="number" class="form-control form-control-sm" id="lokasi_pengerjaan" name="lokasi_pengerjaan" value="{{ old('lokasi_pengerjaan', $article->lokasi_pengerjaan) }}">
                            </div>
                        </div>
                    </div>

                    {{-- DRAWING NO --}}
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="drawing_no" class="col-sm-4 col-form-label">Drawing No</label>
                                <input type="text" class="form-control form-control-sm" id="drawing_no" name="drawing_no" value="{{ old('drawing_no', $article->drawing_no) }}">
                            </div>
                        </div>
                    </div>

                    {{-- DRAWING REV --}}
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="drawing_rev" class="col-sm-4 col-form-label">Drawing Rev</label>
                                <input type="text" class="form-control form-control-sm" id="drawing_rev" name="drawing_rev" value="{{ old('drawing_rev', $article->drawing_rev) }}">
                            </div>
                        </div>
                    </div>

                    {{-- EFFECTIVE DATE --}}
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="effective_date" class="col-sm-4 col-form-label">Effective Date</label>
                                <input type="date" class="form-control form-control-sm" id="effective_date" name="effective_date" value="{{ old('effective_date', is_string($article->effective_date) ? $article->effective_date : ($article->effective_date?->format('Y-m-d') ?? '')) }}">
                            </div>
                        </div>
                    </div>

                    {{-- REMARK --}}
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="remark" class="col-sm-4 col-form-label">Remark</label>
                                <textarea class="form-control form-control-sm" id="remark" name="remark" rows="1">{{ old('remark', $article->remark) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- PERBAIKAN: PECAHAN INPUT HARGA (CASTING, MACHINING, TOTAL) --}}
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-1 d-flex align-items-center">
                                <label for="casting_price" class="form-label col-sm-4 col-form-label">Price Casting <span class="text-danger">*</span></label>
                                <input type="number" id="casting_price" name="casting_price" class="form-control form-control-sm font-weight-bold" value="{{ old('casting_price', $article->casting_price ?? 0) }}" required>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-1 d-flex align-items-center">
                                <label for="machining_price" class="form-label col-sm-4 col-form-label">Price Machining <span class="text-danger">*</span></label>
                                <input type="number" id="machining_price" name="machining_price" class="form-control form-control-sm font-weight-bold" value="{{ old('machining_price', $article->machining_price ?? 0) }}" required>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-2 d-flex align-items-center">
                                <label for="total_price" class="form-label col-sm-4 col-form-label font-weight-bold text-success">Total Price (Auto)</label>
                                <input type="number" id="total_price" name="price" class="form-control form-control-sm bg-light font-weight-bold text-success border-success" value="{{ old('price', $article->price ?? 0) }}" readonly>
                            </div>  
                        </div>
                    </div>

                    {{-- TAMBAHAN: UPDATE ATTACHMENT PDF --}}
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="pdf_attachment" class="col-sm-4 col-form-label">Update Drawing PDF</label>
                                <input type="file" class="form-control form-control-sm" id="pdf_attachment" name="pdf_attachment" accept=".pdf">
                                @if($article->pdf_attachment)
                                    <div class="ms-2 text-nowrap">
                                        <a href="{{ asset('storage/uploads/pricelist/' . $article->pdf_attachment) }}" target="_blank" class="badge bg-danger text-decoration-none"><i class="bi bi-file-pdf"></i> View Current PDF</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const castingInput = document.getElementById('casting_price');
            const machiningInput = document.getElementById('machining_price');
            const totalInput = document.getElementById('total_price');

            // Kalkulasi otomatis kolom total harga
            function calculateTotal() {
                const casting = parseFloat(castingInput.value) || 0;
                const machining = parseFloat(machiningInput.value) || 0;
                totalInput.value = casting + machining;
            }

            castingInput.addEventListener('input', calculateTotal);
            machiningInput.addEventListener('input', calculateTotal);
        });
    </script>
@endpush