@extends('layouts.app')

@section('title', 'Detail: ' . $lelang->barang->nama_barang)
@section('page-title', 'Penawaran Lelang')

@push('styles')
<style>
    .img-detail {
        width: 100%; height: 400px; object-fit: cover; border-radius: 15px; border: 1px solid #eee;
    }
    .card-bid-box {
        background: linear-gradient(145deg, #ffffff, #f0f0f0);
        border: none; border-radius: 15px;
    }
    .current-price-big {
        font-size: 2.5rem; font-weight: 800; color: var(--primary);
    }
    .history-list {
        max-height: 300px; overflow-y: auto;
    }
    .history-item {
        border-bottom: 1px solid #eee; padding: 10px 0;
    }
    .badge-leader {
        background: #ffc107; color: #000; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold;
    }
</style>
@endpush

@section('content')

<a href="{{ route('penawaran.index') }}" class="btn btn-outline-secondary btn-sm mb-4">
    <i class="bi bi-arrow-left"></i> Kembali ke Daftar
</a>

<div class="row g-4">
    
    {{-- KOLOM KIRI: GAMBAR & DESKRIPSI --}}
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 p-3">
            @if($lelang->barang->gambar)
                <img src="{{ asset('storage/'.$lelang->barang->gambar) }}" class="img-detail mb-3">
            @else
                <div class="img-detail d-flex align-items-center justify-content-center bg-light">
                    <i class="bi bi-image display-1 text-muted"></i>
                </div>
            @endif
            
            <h3 class="fw-bold">{{ $lelang->barang->nama_barang }}</h3>
            <span class="badge bg-success mb-3">OPEN BIDDING</span>
            <p class="text-muted">{{ $lelang->barang->deskripsi_barang }}</p>
            <div class="d-flex align-items-center mt-3">
                <i class="bi bi-person-workspace me-2 text-secondary"></i>
                <small class="text-muted">Host Lelang: <strong>{{ $lelang->petugas->nama_petugas }}</strong></small>
            </div>
            <hr>
            
            <h5 class="fw-bold mb-3"><i class="bi bi-clock-history"></i> Riwayat Penawaran</h5>
            <div class="history-list pe-2">
                @forelse($lelang->history as $hist)
                    <div class="history-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-dark">
                                {{ $hist->masyarakat->nama_lengkap }}
                                @if($loop->first) <span class="badge-leader">LEADER</span> @endif
                            </div>
                            <small class="text-muted">{{ $hist->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="fw-bold {{ $loop->first ? 'text-success' : 'text-secondary' }}">
                            Rp {{ number_format($hist->penawaran_harga, 0, ',', '.') }}
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-3">Belum ada yang menawar. Jadilah yang pertama!</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: KOTAK BID --}}
    <div class="col-lg-5">
        <div class="card shadow border-0 card-bid-box">
            <div class="card-body p-4">
                <h5 class="text-muted text-uppercase small fw-bold mb-3">Harga Tertinggi Saat Ini</h5>
                
                {{-- Logic Tampilan Harga --}}
                <div class="current-price-big mb-2">
                    @if($lelang->harga_akhir > 0)
                        Rp {{ number_format($lelang->harga_akhir, 0, ',', '.') }}
                    @else
                        Rp {{ number_format($lelang->barang->harga_awal, 0, ',', '.') }}
                    @endif
                </div>

                {{-- Info Pemegang Bid --}}
                <div class="mb-4">
                    @if($lelang->pemenang)
                        <div class="d-flex align-items-center p-3 bg-white rounded shadow-sm">
                            <div class="me-3">
                                <div class="avatar bg-warning text-dark">{{ substr($lelang->pemenang->masyarakat->nama_lengkap, 0, 1) }}</div>
                            </div>
                            <div>
                                <small class="text-muted d-block">Pemegang Bid Tertinggi:</small>
                                <span class="fw-bold">{{ $lelang->pemenang->masyarakat->nama_lengkap }}</span>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">Belum ada penawaran.</div>
                    @endif
                </div>

                <hr>

                {{-- FORM BID --}}
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('penawaran.store', $lelang->id_lelang) }}" method="POST">
                    @csrf
                    <label class="form-label fw-bold">Masukkan Penawaran Anda</label>
                    
                    <div class="input-group mb-2">
                        <span class="input-group-text fw-bold">Rp</span>
                        <input type="number" name="penawaran_harga" class="form-control form-control-lg @error('penawaran_harga') is-invalid @enderror" 
                               placeholder="Contoh: {{ $lelang->harga_akhir > 0 ? $lelang->harga_akhir + 10000 : $lelang->barang->harga_awal + 10000 }}" oninput="if(this.value.length > 16) this.value = this.value.slice(0, 16);"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                    </div>
                    @error('penawaran_harga')
                        <div class="text-danger small mb-3">{{ $message }}</div>
                    @enderror
                    
                    <div class="form-text mb-3">
                        Min bid: Rp {{ number_format(($lelang->harga_akhir > 0 ? $lelang->harga_akhir : $lelang->barang->harga_awal) + 1, 0, ',', '.') }}
                    </div>

                    {{-- Logic Tombol: Disable jika user sendiri yang memimpin --}}
                    @if($lelang->pemenang && $lelang->pemenang->id_user == session('user_id'))
                        <button type="button" class="btn btn-secondary w-100 py-3" disabled>
                            <i class="bi bi-check-circle-fill me-2"></i> Anda Memimpin Penawaran
                        </button>
                    @else
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm">
                            <i class="bi bi-capslock-fill me-2"></i> TAWAR SEKARANG
                        </button>
                    @endif
                </form>

            </div>
        </div>
        
        <div class="alert alert-warning mt-3 border-0 shadow-sm d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
            <div class="small line-height-sm">
                <strong>Perhatian:</strong> Penawaran yang sudah diajukan tidak dapat dibatalkan. Pastikan harga sesuai.
            </div>
        </div>
    </div>

</div>
@endsection