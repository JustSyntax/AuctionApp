@extends('layouts.app')

@section('title', 'Daftar Lelang')
@section('page-title', 'Lelang Aktif')

@push('styles')
<style>
    .auction-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        height: 100%;
    }
    .auction-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .card-img-wrapper {
        height: 200px;
        overflow: hidden;
        position: relative;
    }
    .card-img-top {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .badge-open {
        position: absolute;
        top: 15px; right: 15px;
        background: #198754; /* Hijau */
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    .price-tag {
        color: var(--primary);
        font-weight: 800;
        font-size: 1.1rem;
    }
    
    /* Search Container Minimalis */
    .search-container {
        background: white;
        padding: 15px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        margin-bottom: 30px;
        display: flex;
        justify-content: center; /* Tengahin Search Bar */
    }
    .input-search {
        border-radius: 30px 0 0 30px; /* Rounded kiri */
        padding: 12px 25px;
        border: 1px solid #e0e0e0;
        background: #f8f9fa;
        border-right: none;
    }
    .input-search:focus {
        background: #fff;
        box-shadow: none;
        border-color: var(--primary);
    }
    .btn-search {
        border-radius: 0 30px 30px 0; /* Rounded kanan */
        padding-left: 25px;
        padding-right: 25px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')

{{-- SEARCH BAR SECTION (MINIMALIS) --}}
<div class="search-container">
    <form action="{{ route('penawaran.index') }}" method="GET" style="width: 100%; max-width: 600px;">
        <div class="input-group">
            <input type="text" name="search" class="form-control input-search" placeholder="Cari mobil yang anda inginkan..." value="{{ request('search') }}">
            <button class="btn btn-primary btn-search" type="submit">
                <i class="bi bi-search me-2"></i> Cari
            </button>
        </div>
    </form>
</div>


{{-- GRID CARD --}}
<div class="row g-4">
    @forelse($lelangs as $lelang)
    <div class="col-md-6 col-lg-4 col-xl-3">
        <a href="{{ route('penawaran.show', $lelang->id_lelang) }}" class="text-decoration-none text-dark">
            <div class="card auction-card shadow-sm">
                
                <div class="card-img-wrapper">
                    <div class="badge-open"><i class="bi bi-broadcast me-1"></i> OPEN BIDDING</div>
                    @if($lelang->barang->gambar)
                        <img src="{{ asset('storage/'.$lelang->barang->gambar) }}" class="card-img-top" alt="Gambar">
                    @else
                        <div class="d-flex justify-content-center align-items-center h-100 bg-light text-muted">
                            <i class="bi bi-image display-4"></i>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    <h5 class="card-title fw-bold mb-1">{{ $lelang->barang->nama_barang }}</h5>
                    <p class="text-muted small mb-3 text-truncate">{{ $lelang->barang->deskripsi_barang }}</p>

                    <div class="mb-3">
                        <small class="d-block text-muted" style="font-size: 11px;">Harga Saat Ini:</small>
                        @if($lelang->harga_akhir > 0)
                            <div class="price-tag">Rp {{ number_format($lelang->harga_akhir, 0, ',', '.') }}</div>
                        @else
                            <div class="price-tag text-secondary">Rp {{ number_format($lelang->barang->harga_awal, 0, ',', '.') }}</div>
                        @endif
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-trophy-fill text-warning me-2"></i>
                            @if($lelang->pemenang)
                                <div class="d-flex flex-column">
                                    <span class="text-muted" style="font-size: 10px;">TERTINGGI:</span>
                                    <span class="fw-bold text-dark" style="font-size: 13px;">{{ $lelang->pemenang->masyarakat->nama_lengkap }}</span>
                                </div>
                            @else
                                <span class="text-muted fst-italic small">Belum ada bid</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <div class="mb-3"><i class="bi bi-search text-muted" style="font-size: 50px;"></i></div>
        <h5 class="text-muted">Barang lelang tidak ditemukan.</h5>
        @if(request('search'))
            <a href="{{ route('penawaran.index') }}" class="btn btn-outline-primary btn-sm mt-2">Lihat Semua Barang</a>
        @endif
    </div>
    @endforelse
</div>

@endsection