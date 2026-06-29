@extends('layouts.app')

@section('title', 'PT. Metinca Prima Industrial Works')

@section('content')
<div class="card detail-card">
        <div class="card-header bg-info text-black py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-box-seam-fill"></i> Detail Product
            </h5>
        </div>
    <div class="row">
        <div class="col-md-12">      
            <div class="card-body">
                <div class="row align-items-center">
                    {{-- PERUBAHAN 1: MENGGANTI part_number MENJADI internal_part_no --}}
                    <div class="col-md-7">
                        <div class="info-group mb-0 d-flex align-items-center">
                            <label class="fw-bold col-sm-4 col-form-label">Internal Part No</label>
                            <p class="form-control-plaintext">{{ $article->internal_part_no }}</p>
                        </div>
                    </div>
                    <div class="col-md-5 gap-1 d-flex justify-content-end align-items-center">
                        <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this article?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                        <a href="{{ route('articles.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>

                    <div class="col-md-7">
                        <div class="info-group mb-0 d-flex align-items-center">
                            <label class="fw-bold col-sm-4 col-form-label">Article</label>
                            <p class="form-control-plaintext">{{ $article->article_no }}</p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="info-group mb-0 d-flex align-items-center">
                            <label class="fw-bold col-sm-4 col-form-label">Part Name</label>
                            <p class="form-control-plaintext">{{ $article->part_name ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- TAMBAHAN: MENAMPILKAN DATA INDEX & BERAT BARU --}}
                    <div class="col-md-7">
                        <div class="info-group mb-0 d-flex align-items-center">
                            <label class="fw-bold col-sm-4 col-form-label">Index:</label>
                            <p class="form-control-plaintext">{{ $article->index_no ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="info-group mb-0 d-flex align-items-center">
                            <label class="fw-bold col-sm-4 col-form-label">Berat (Kg):</label>
                            <p class="form-control-plaintext">{{ $article->berat ? number_format($article->berat, 2) . ' Kg' : '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="info-group mb-0 d-flex align-items-center">
                            <label class="fw-bold col-sm-4 col-form-label">Die No:</label>
                            <p class="form-control-plaintext">{{ $article->die_no ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="info-group mb-0 d-flex align-items-center">
                            <label class="fw-bold col-sm-4 col-form-label">Material:</label>
                            <p class="form-control-plaintext">{{ $article->material ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="info-group mb-0 d-flex align-items-center">
                            <label class="fw-bold col-sm-4 col-form-label">Drawing No:</label>
                            <p class="form-control-plaintext">{{ $article->drawing_no ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="info-group mb-0 d-flex align-items-center">
                            <label class="fw-bold col-sm-4 col-form-label">Drawing Rev:</label>
                            <p class="form-control-plaintext">{{ $article->drawing_rev ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="info-group mb-0 d-flex align-items-center">
                            <label class="fw-bold col-sm-4 col-form-label">Effective Date:</label>
                            <p class="form-control-plaintext">{{ $article->effective_date ? \Carbon\Carbon::parse($article->effective_date)->format('d/m/Y') : '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="info-group mb-0 d-flex align-items-center">
                            <label class="fw-bold col-sm-4 col-form-label">Customer:</label>
                            <p class="form-control-plaintext">{{ $article->customer?->name ?? '-' }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-7">
                        <div class="info-group mb-0 d-flex align-items-center">
                            <label class="fw-bold col-sm-4 col-form-label">Lokasi Pengerjaan:</label>
                            <p class="form-control-plaintext">
                                @php
                                    $lokasiMap = [
                                        1 => 'PT. Metinca (Jakarta)',
                                        2 => 'PT. Metal Castindo',
                                        3 => 'PT. Metinca S3',
                                        4 => 'Valve',
                                    ];
                                @endphp
                                {{ $lokasiMap[$article->lokasi_pengerjaan] ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="info-group mb-0 d-flex align-items-center">
                            <label class="fw-bold col-sm-4 col-form-label">Remark:</label>
                            <p class="form-control-plaintext">{{ $article->remark ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- PERUBAHAN 2: MENCETAK VARIABEL HARGA TOTAL YANG SUDAH DIFORMAT --}}
                    <div class="col-md-7">
                        <div class="info-group mb-0 d-flex align-items-center">
                            <label class="fw-bold col-sm-4 col-form-label">Price</label>
                            <p class="form-control-plaintext text-success fw-bold">Rp {{ number_format($article->price, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- TAMBAHAN: DOWNLOAD/LIHAT BERKAS DRAWING PDF JIKA ADA --}}
                    <div class="col-md-7">
                        <div class="info-group mb-0 d-flex align-items-center">
                            <label class="fw-bold col-sm-4 col-form-label">Drawing PDF:</label>
                            <p class="form-control-plaintext">
                                @if($article->pdf_attachment)
                                    <a href="{{ asset('storage/uploads/pricelist/' . $article->pdf_attachment) }}" target="_blank" class="btn btn-xs btn-outline-danger py-0 px-2">
                                        <i class="bi bi-file-earmark-pdf-fill"></i> View Attachment PDF
                                    </a>
                                @else
                                    <span class="text-muted text-sm">No File Attached</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="info-group mb-0 d-flex align-items-center">
                            <label class="fw-bold col-sm-4 col-form-label">Created At:</label>
                            <p class="form-control-plaintext">{{ $article->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="info-group mb-0 d-flex align-items-center">
                            <label class="fw-bold col-sm-4 col-form-label">Updated At:</label>
                            <p class="form-control-plaintext">{{ $article->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .info-group {
            margin-bottom: 1rem;
        }

        .info-group label {
            display: block;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .info-group p {
            padding: 0.375rem 0;
            margin: 0;
        }

        .badge {
            font-size: 0.85em;
            padding: 0.35em 0.65em;
        }
    </style>
@endpush