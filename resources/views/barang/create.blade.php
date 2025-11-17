@extends('layouts.app')

@section('title', 'Tambah Data Barang')
@section('page-title', 'Tambah Barang Baru')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Formulir Data Barang</h5>
        <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        
        <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                
                {{-- Kolom Kiri: Input Teks --}}
                <div class="col-md-7">
                    <!-- Nama Barang -->
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" 
                               id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}" required>
                        @error('nama_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Register -->
                    <div class="mb-3">
                        <label for="tgl" class="form-label">Tanggal Register</label>
                        <input type="date" class="form-control @error('tgl') is-invalid @enderror" 
                               id="tgl" name="tgl" value="{{ old('tgl', date('Y-m-d')) }}" required>
                        @error('tgl')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Harga Awal -->
                    <div class="mb-3">
                        <label for="harga_awal" class="form-label">Harga Awal (Rp)</label>
                        <input type="number" class="form-control @error('harga_awal') is-invalid @enderror" 
                               id="harga_awal" name="harga_awal" value="{{ old('harga_awal') }}" min="0" required>
                        @error('harga_awal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Kolom Kanan: Deskripsi & Gambar --}}
                <div class="col-md-5">
                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label for="deskripsi_barang" class="form-label">Deskripsi Singkat</label>
                        <textarea class="form-control @error('deskripsi_barang') is-invalid @enderror" 
                                  id="deskripsi_barang" name="deskripsi_barang" rows="4" required>{{ old('deskripsi_barang') }}</textarea>
                        @error('deskripsi_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Upload Gambar -->
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Upload Gambar (Opsional)</label>
                        <input class="form-control @error('gambar') is-invalid @enderror" 
                               type="file" id="gambar" name="gambar">
                        <div class="form-text">Maks. 2MB (Format: jpg, png, webp)</div>
                        @error('gambar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-end">
                <a href="{{ route('barang.index') }}" class="btn btn-light me-2">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save-fill me-1"></i> Simpan Data
                </button>
            </div>
        </form>

    </div>
</div>
@endsection