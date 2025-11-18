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
    .user-bidder {
        font-size: 12px;
        color: #888;
        background: #f8f9fa;
        padding: 5px 10px;
        border-radius: 8px;
        display: inline-block;
    }
</style>
@endpush

@section('content')

<div class="row g-4">
    @forelse($lelangs as $lelang)
    <div class="col-md-6 col-lg-4 col-xl-3">
        {{-- Link pembungkus agar seluruh card bisa diklik --}}
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
        <div class="mb-3"><i class="bi bi-box-seam text-muted" style="font-size: 50px;"></i></div>
        <h5 class="text-muted">Belum ada lelang yang dibuka saat ini.</h5>
    </div>
    @endforelse
</div>

@endsection