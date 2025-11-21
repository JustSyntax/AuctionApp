@extends('layouts.app')

@section('title', 'Dashboard Masyarakat')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .stat-card {
        border: none;
        border-radius: 15px;
        padding: 25px;
        color: white;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }
    
    /* Gradient Colors */
    .bg-purple { background: linear-gradient(45deg, #6f42c1, #a059f5); }
    .bg-blue   { background: linear-gradient(45deg, #0d6efd, #4b94ff); }
    .bg-green  { background: linear-gradient(45deg, #198754, #42ba82); }
    .bg-orange { background: linear-gradient(45deg, #fd7e14, #ffaa5d); }

    .stat-icon {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 3.5rem;
        opacity: 0.2;
    }
    .stat-value { font-size: 2.2rem; font-weight: 800; margin-bottom: 5px; }
    .stat-label { font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; }
    
    .welcome-banner {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        margin-bottom: 30px;
        border-left: 5px solid var(--primary);
    }
</style>
@endpush

@section('content')

{{-- Welcome Banner --}}
<div class="welcome-banner d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Halo, {{ session('name') }}!</h4>
        <p class="text-muted mb-0">Selamat datang di dashboard partisipasi lelang Anda.</p>
    </div>
    <div>
        <a href="{{ route('penawaran.index') }}" class="btn btn-primary px-4 py-2 shadow-sm" style="border-radius: 30px;">
            <i class="bi bi-search me-2"></i> Cari Barang Lelang
        </a>
    </div>
</div>

<div class="row g-4">
    
    {{-- Card 1: Barang Dimenangkan --}}
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-green shadow-sm">
            <div class="stat-label">Lelang Dimenangkan</div>
            <div class="stat-value">{{ $data['won_count'] }}</div>
            <div class="small opacity-75">Barang sukses didapat</div>
            <i class="bi bi-trophy-fill stat-icon"></i>
        </div>
    </div>

    {{-- Card 2: Total Partisipasi (Bids) --}}
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-blue shadow-sm">
            <div class="stat-label">Total Tawaran</div>
            <div class="stat-value">{{ $data['total_bids'] }}</div>
            <div class="small opacity-75">Kali melakukan bid</div>
            <i class="bi bi-hand-index-thumb-fill stat-icon"></i>
        </div>
    </div>

    {{-- Card 3: Barang Diikuti --}}
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-purple shadow-sm">
            <div class="stat-label">Barang Diikuti</div>
            <div class="stat-value">{{ $data['items_joined'] }}</div>
            <div class="small opacity-75">Jenis barang berbeda</div>
            <i class="bi bi-bag-check-fill stat-icon"></i>
        </div>
    </div>

    {{-- Card 4: Total Pengeluaran --}}
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-orange shadow-sm">
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value" style="font-size: 1.5rem;">
                Rp {{ number_format($data['total_spent'] / 1000000, 0, ',', ',') }}M
            </div>
            <div class="small opacity-75">
                Full: Rp {{ number_format($data['total_spent'], 0, ',', '.') }}
            </div>
            <i class="bi bi-wallet2 stat-icon"></i>
        </div>
    </div>

</div>

{{-- Section Bawah --}}
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm p-4" style="border-radius: 15px; background-image: linear-gradient(to right, #f8f9fa, #ffffff);">
            <div class="d-flex align-items-center">
                <div class="me-4">
                    <div class="bg-white p-3 rounded-circle shadow-sm text-primary">
                        <i class="bi bi-clock-history fs-1"></i>
                    </div>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-1">Riwayat Aktivitas Anda</h5>
                    <p class="text-muted mb-2">Pantau status menang/kalah dari lelang yang pernah Anda ikuti.</p>
                    <a href="{{ route('history.index') }}" class="text-decoration-none fw-bold">
                        Lihat Riwayat Lengkap <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection