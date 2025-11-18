@extends('layouts.app')

@section('title', 'Detail Lelang: ' . $lelang->barang->nama_barang)
@section('page-title', 'Detail Lelang')

@push('styles')
<style>
    /* Card Kiri - Gambar */
    .img-detail-lelang {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #eee;
    }
    
    /* Card Kanan - Pemenang */
    .card-pemenang {
        border-left: 3px solid var(--primary);
    }
    .card-pemenang-dibuka {
        border-left: 3px solid #0dcaf0; /* Info (Biru Muda) */
    }
    .card-pemenang-kosong {
        border-left: 3px solid #6c757d; /* Abu */
    }
    .pemenang-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: bold;
    }
    .pemenang-harga {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary);
    }
    
    /* Card Bawah - History */
    .history-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 15px;
        border-bottom: 1px solid #f0f0f0;
    }
    .history-item:last-child {
        border-bottom: none;
    }
    .history-user {
        font-weight: 600;
    }
    .history-price {
        font-weight: 700;
        color: #198754; /* Hijau */
    }
    .history-time {
        font-size: 12px;
        color: #999;
    }
</style>
@endpush

@section('content')

{{-- Tombol Back --}}
@if(request('source') == 'history')
    <a href="{{ route('history.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke History
    </a>
@else
    <a href="{{ route('lelang.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke List
    </a>
@endif

<div class="row g-4">
    
    <!-- === KOLOM KIRI (DETAIL BARANG) === -->
    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            @if($lelang->barang->gambar)
                <img src="{{ asset('storage/'.$lelang->barang->gambar) }}" class="card-img-top img-detail-lelang" alt="{{ $lelang->barang->nama_barang }}">
            @else
                <div class="img-detail-lelang d-flex align-items-center justify-content-center bg-light text-muted">
                    <i class="bi bi-image" style="font-size: 50px;"></i>
                </div>
            @endif
            
            <div class="card-body p-4">
                <!-- Status Badge -->
                @if($lelang->status == 'dibuka')
                    <span class="badge rounded-pill bg-info text-dark mb-2" style="font-size: 12px;">
                        <i class="bi bi-broadcast"></i> LELANG DIBUKA
                    </span>
                @else
                    <span class="badge rounded-pill bg-secondary mb-2" style="font-size: 12px;">
                        <i class="bi bi-lock-fill"></i> LELANG DITUTUP
                    </span>
                @endif
                
                <h3 class="fw-bold mb-1">{{ $lelang->barang->nama_barang }}</h3>
                <p class="text-muted">{{ $lelang->barang->deskripsi_barang }}</p>

                <ul class="list-group list-group-flush mt-4">
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Harga Awal</span>
                        <strong class="text-primary">Rp {{ number_format($lelang->barang->harga_awal, 0, ',', '.') }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Tanggal Lelang</span>
                        <strong>{{ $lelang->tgl_lelang->format('d M Y') }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Petugas Penanggung Jawab</span>
                        <strong>{{ $lelang->petugas->nama_petugas }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- === KOLOM KANAN (PEMENANG) === -->
    <div class="col-lg-5">
        
        {{-- Logika 1: Lelang DITUTUP dan ADA PEMENANG --}}
        @if($lelang->status == 'ditutup' && $lelang->pemenang)
            <div class="card shadow-sm border-0 card-pemenang">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-trophy-fill text-warning me-2"></i> Pemenang Lelang</h5>
                </div>
                <div class="card-body text-center p-4">
                    <div class="pemenang-avatar mx-auto mb-3">
                        {{ substr($lelang->pemenang->masyarakat->nama_lengkap, 0, 1) }}
                    </div>
                    <h4 class="fw-bold mb-1">{{ $lelang->pemenang->masyarakat->nama_lengkap }}</h4>
                    <p class="text-muted mb-3">@ {{ $lelang->pemenang->masyarakat->username }}</p>
                    
                    <hr>
                    <p class="mb-1 text-muted">Menang dengan harga:</p>
                    <div class="pemenang-harga mb-3">
                        Rp {{ number_format($lelang->pemenang->penawaran_harga, 0, ',', '.') }}
                    </div>
                    
                    <small class="text-muted d-block px-3">
                        Alamat: {{ $lelang->pemenang->masyarakat->alamat }}
                    </small>
                </div>
            </div>

        {{-- Logika 2: Lelang MASIH DIBUKA --}}
        @elseif($lelang->status == 'dibuka')
            <div class="card shadow-sm border-0 card-pemenang-dibuka">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history text-info me-2"></i> Status Lelang</h5>
                </div>
                <div class="card-body text-center p-4">
                    <i class="bi bi-broadcast display-4 text-info"></i>
                    <h4 class="mt-3 mb-1">Lelang Masih Dibuka</h4>
                    <p class="text-muted">Saat ini lelang masih menerima penawaran dari masyarakat.</p>
                </div>
            </div>

        {{-- Logika 3: Lelang DITUTUP tapi KOSONG (Tidak ada pemenang) --}}
        @else
            <div class="card shadow-sm border-0 card-pemenang-kosong">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-x-circle-fill text-secondary me-2"></i> Lelang Ditutup</h5>
                </div>
                <div class="card-body text-center p-4">
                    <i class="bi bi-box-seam display-4 text-muted"></i>
                    <h4 class="mt-3 mb-1">Tidak Ada Pemenang</h4>
                    <p class="text-muted">Lelang ini ditutup tanpa ada penawaran yang masuk.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- === CARD BAWAH (HISTORY BID) === -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-list-ol me-2"></i> Riwayat Penawaran</h5>
            </div>
            <div class="card-body p-0">
                @forelse($lelang->history as $history)
                    <div class="history-item">
                        <div>
                            <div class="history-user">{{ $history->masyarakat->nama_lengkap }}</div>
                            <div class="history-time text-muted">@ {{ $history->masyarakat->username }}</div>
                        </div>
                        <div>
                            <div class="history-price">Rp {{ number_format($history->penawaran_harga, 0, ',', '.') }}</div>
                            <div class="history-time text-end">{{ $history->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center p-5 text-muted">
                        <i class="bi bi-hourglass-split" style="font-size: 30px;"></i>
                        <p class="mt-2 mb-0">Belum ada penawaran yang masuk.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection