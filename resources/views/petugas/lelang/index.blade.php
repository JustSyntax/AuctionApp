@extends('layouts.app')

@section('title', 'Lelang Saya')
@section('page-title', 'Kelola Lelang')

@push('styles')
<style>
    .card-header-flex { display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap; }
    .search-box { position: relative; max-width: 300px; width: 100%; }
    .search-box input { border-radius: 20px; padding-left: 35px; background: #f9f9f9; border: 1px solid #e0e0e0; }
    .search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #aaa; }

    .table-image { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
    
    /* --- BUTTON STATUS (TOGGLE) --- */
    .btn-status {
        border: none; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 700;
        text-transform: uppercase; cursor: pointer; transition: .2s; width: 100px;
    }
    
    /* Kalau DIBUKA -> Klik untuk TUTUP */
    .btn-status.buka { background: rgba(13, 202, 240, 0.15); color: #0dcaf0; border: 1px solid rgba(13, 202, 240, 0.2); }
    .btn-status.buka:hover { background: #0dcaf0; color: white; }
    .btn-status.buka:hover::after { content: " (TUTUP)"; } /* Teks berubah pas hover */
    
    /* Kalau DITUTUP -> Klik untuk BUKA */
    .btn-status.tutup { background: rgba(108, 117, 125, 0.15); color: #6c757d; border: 1px solid rgba(108, 117, 125, 0.2); }
    .btn-status.tutup:hover { background: #6c757d; color: white; }
    .btn-status.tutup:hover::after { content: " (BUKA)"; }

    /* --- ACTION BUTTONS --- */
    .btn-action { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; background: transparent; cursor: pointer; text-decoration: none; }
    
    .btn-action.edit { color: #2a53ff; background: rgba(42, 83, 255, 0.1); }
    .btn-action.edit:hover { background: #2a53ff; color: white; }

    .btn-action.delete { color: #ff4d4d; background: rgba(255, 77, 77, 0.1); }
    .btn-action.delete:hover { background: #ff4d4d; color: white; }
    
    /* Disabled Delete Style */
    .btn-action.delete[disabled] { opacity: 0.3; cursor: not-allowed; }
    .btn-action.delete[disabled]:hover { background: transparent; color: #ff4d4d; }
</style>
@endpush

@section('content')
<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-header bg-white card-header-flex py-3">
        <div class="d-flex align-items-center gap-3">
            <h5 class="mb-0 fw-bold text-dark">Lelang Saya</h5>
            <a href="{{ route('lelang.create') }}" class="btn btn-primary btn-sm px-3" style="border-radius: 20px;">
                <i class="bi bi-hammer me-1"></i> Buka Lelang
            </a>
        </div>
        <form action="{{ route('lelang.index') }}" method="GET" class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="search" class="form-control" placeholder="Cari Barang..." value="{{ request('search') }}">
        </form>
    </div>
    <div class="card-body p-0">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger m-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr style="font-size: 13px; text-transform: uppercase;">
                        <th class="ps-4 py-3">No</th>
                        <th class="py-3">Gambar</th>
                        <th class="py-3">Barang</th>
                        <th class="py-3">Tgl Lelang</th>
                        <th class="py-3">Harga Awal</th>
                        <th class="py-3">Harga Akhir</th>
                        <th class="py-3">Pemenang</th>
                        <th class="py-3 text-center">Status (Klik)</th>
                        <th class="text-center py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px;">
                    @forelse ($lelangs as $index => $lelang)
                    <tr>
                        <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($lelang->barang->gambar)
                                    <img src="{{ asset('storage/'.$lelang->barang->gambar) }}" alt="Gambar Barang" class="table-image">
                                @else
                                    <div class="table-image d-flex align-items-center justify-content-center bg-light text-muted small">No IMG</div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="fw-bold">{{ $lelang->barang->nama_barang }}</div>
                            </div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($lelang->tgl_lelang)->format('d M Y') }}</td>
                        <td class="text-primary fw-semibold">Rp {{ number_format($lelang->barang->harga_awal, 0, ',', '.') }}</td>
                        
                        {{-- Harga Akhir (Tertinggi) --}}
                        <td class="fw-bold">
                            @if($lelang->harga_akhir > 0)
                                Rp {{ number_format($lelang->harga_akhir, 0, ',', '.') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- Pemenang --}}
                        <td>
                            @if($lelang->status == 'ditutup' && $lelang->pemenang)
                                <span class="fw-semibold text-success">{{ $lelang->pemenang->masyarakat->nama_lengkap }}</span>
                            @else
                                <span class="text-muted" style="font-style: italic; font-size: 12px;">Belum ada</span>
                            @endif
                        </td>

                        {{-- STATUS TOGGLE BUTTON --}}
                        <td class="text-center">
                            <form action="{{ route('lelang.tutup', $lelang->id_lelang) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status_toggle" value="true">
                                
                                @if($lelang->status == 'dibuka')
                                    <button type="submit" class="btn-status buka" title="Klik untuk Menutup Lelang">
                                        Dibuka
                                    </button>
                                @else
                                    <button type="submit" class="btn-status tutup" title="Klik untuk Membuka Kembali">
                                        Ditutup
                                    </button>
                                @endif
                            </form>
                        </td>

                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                
                                <a href="{{ route('lelang.show', $lelang->id_lelang) }}" class="btn-action edit" title="Lihat Detail">
                                    <i class="bi bi-eye-fill"></i>
                                </a>

                                {{-- TOMBOL HAPUS (Hanya Aktif Jika BELUM ada pemenang/history) --}}
                                @if(!$lelang->pemenang)
                                    <form action="{{ route('lelang.destroy', $lelang->id_lelang) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data lelang ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                                    </form>
                                @else
                                    {{-- Tombol Mati (Disabled) --}}
                                    <button type="button" class="btn-action delete" disabled title="Tidak bisa dihapus karena sudah ada riwayat penawaran">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                @endif
                                
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">Belum ada lelang yang Anda buka.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection