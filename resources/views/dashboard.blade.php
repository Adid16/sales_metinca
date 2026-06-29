{{-- Inlcude layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'PT. Metinca Prima Industrial Works')

{{-- Untuk menggunakan css --}}
@push('styles')
    {{-- contoh --}}
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_admin.css') }}">
    <style>
        /* CSS tambahan untuk memastikan teks di dalam card menyesuaikan saat mode gelap aktif */
        html[data-bs-theme="dark"] .card h6 {
            color: #ffffff !important;
        }
    </style>
@endpush

{{-- Isi content --}}
@section('content')
<div class="mb-3">
    <h3>Dashboard</h3>
</div>
    <div class="page-content">
        <section class="row">
            <div class="col-20 col-lg-20">
                <div class="row">
                    @foreach ($data as $item)
                    <div class="col-6 col-lg-3 col-md-6">
                        {{-- PERBAIKAN: Mengubah 'card card-{{ $item["color"] }}' menjadi 'card' biasa agar mendukung mode gelap secara native --}}
                        <div class="card shadow-sm">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon {{ $item['color'] }} mb-2">
                                            <i class="bi {{ $item['icon'] }}"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="font-bold">{{ $item['label'] }}</h6>
                                        <h6 class="font-extrabold mb-0">{{ $item['count'] }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Activity History</h4>
                            </div>
                            <div class="card-body">
                               <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Activity</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($activity as $item)
                                            <tr>
                                                <td>{{ $item->user->name }}</td>
                                                <td>{{ $item->activity }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->activity_time)->format('d M Y, H:i') }} WIB</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </section>
    </div>

@endsection

{{-- Untuk menggunakan js --}}
@push('scripts')
    <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/dashboard.js') }}"></script>
@endpush