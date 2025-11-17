@extends('layouts.app')

@section('title', 'Master Data Barang')
@section('page-title', 'Data Barang')

@push('styles')
<style>
    /* Styling Header Card biar Search bar muat dan rapi */
    .card-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap; /* Biar responsif kalau layar kecil */
        gap: 15px;
    }
    
    /* Styling Search Box */
    .search-box {
        position: relative;
        max-width: 300px;
        width: 100%;
    }
    .search-box input {
        border-radius: 20px;
        padding-left: 35px; /* Space buat icon search */
        font-size: 14px;
        border: 1px solid #e0e0e0;
        background: #f9f9f9;
    }
    .search-box input:focus {
        background: #fff;
        border-color: #2a53ff;
        box-shadow: none;
    }
    .search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
        font-size: 14px;
    }

    /* Styling Image di Tabel */
    .table-image {
        width: 60px;
        height: 45px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #eee;
        transition: transform 0.2s;
    }
    .table-image:hover {
        transform: scale(1.5); /* Efek zoom dikit pas di hover */
        z-index: 10;
        position: relative;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .table-placeholder {
        width: 60px;
        height: 45px;
        border-radius: 6px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ccc;
        font-size: 10px;
        border: 1px dashed #ddd;
    }

    /* Styling Action Buttons (Clean Style) */
    .btn-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: none;
        transition: .2s;
        background: transparent;
    }
    /* Warna Edit: Biru soft */
    .btn-action.edit { color: #2a53ff; background: rgba(42, 83, 255, 0.1); }
    .btn-action.edit:hover { background: #2a53ff; color: white; }
    
    /* Warna Delete: Merah soft */
    .btn-action.delete { color: #ff4d4d; background: rgba(255, 77, 77, 0.1); }
    .btn-action.delete:hover { background: #ff4d4d; color: white; }

</style>
@endpush


@section('content')
<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-header bg-white card-header-flex py-3">
        
        {{-- Judul & Tombol Tambah --}}
        <div class="d-flex align-items-center gap-3">
            <h5 class="mb-0 fw-bold text-dark">List Barang</h5>
            <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm px-3" style="border-radius: 20px;">
                <i class="bi bi-plus-lg me-1"></i> Baru
            </a>
        </div>

        {{-- Search Bar --}}
        <form action="{{ route('barang.index') }}" method="GET" class="search-box">
            <i class="bi bi-search"></i>
            {{-- name="search" ini nanti ditangkap controller --}}
            <input type="text" name="search" class="form-control" placeholder="Cari nama barang..." value="{{ request('search') }}">
        </form>

    </div>
    <div class="card-body p-0">
        
        {{-- Alert Sukses --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert" style="border-radius: 10px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <th class="ps-4 py-3">No</th>
                        <th class="py-3">Gambar</th>
                        <th class="py-3">Nama Barang</th>
                        <th class="py-3">Tgl Register</th>
                        <th class="py-3">Harga Awal</th>
                        <th class="py-3">Deskripsi</th>
                        <th class="text-center py-3" width="120px">Aksi</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px;">
                    @forelse ($barangs as $index => $barang)
                    <tr>
                        <td class="ps-4 fw-semibold text-muted">{{ $index + 1 }}</td>
                        <td>
                            @if($barang->gambar)
                                <img src="{{ asset('storage/' . $barang->gambar) }}" alt="img" class="table-image">
                            @else
                                <div class="table-placeholder"><i class="bi bi-image"></i></div>
                            @endif
                        </td>
                        <td class="fw-bold text-dark">{{ $barang->nama_barang }}</td>
                        <td class="text-muted">{{ $barang->tgl->format('d M Y') }}</td>
                        <td class="fw-semibold text-primary">Rp {{ number_format($barang->harga_awal, 0, ',', '.') }}</td>
                        <td class="text-muted small">{{ Str::limit($barang->deskripsi_barang, 40) }}</td>
                        
                        {{-- ACTION BUTTONS YANG BARU --}}
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                {{-- Edit --}}
                                <a href="{{ route('barang.edit', $barang->id_barang) }}" class="btn-action edit" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>

                                {{-- Hapus (Form) --}}
                                <form action="{{ route('barang.destroy', $barang->id_barang) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus {{ $barang->nama_barang }}? Data yang dihapus tidak dapat dikembalikan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action delete" title="Hapus">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <div class="mb-2"><i class="bi bi-box-seam" style="font-size: 30px; color: #eee;"></i></div>
                            Data barang tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Jika pakai pagination, taruh linknya disini --}}
        {{-- <div class="p-3"> {{ $barangs->links() }} </div> --}}
    </div>
</div>
@endsection