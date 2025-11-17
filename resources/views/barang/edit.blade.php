@extends('layouts.app')

@section('title', 'Edit Data Barang')
@section('page-title', 'Edit Barang')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Formulir Edit Barang: {{ $barang->nama_barang }}</h5>
        <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        
        {{-- Ganti action ke route 'update' dan method ke 'PUT' --}}
        <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- PENTING: Method-nya PUT/PATCH untuk update --}}
            
            <div class="row g-3">
                
                {{-- Kolom Kiri: Input Teks --}}
                <div class="col-md-7">
                    <!-- Nama Barang -->
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" 
                               id="nama_barang" name="nama_barang" 
                               value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                        @error('nama_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Register -->
                    <div class="mb-3">
                        <label for="tgl" class="form-label">Tanggal Register</label>
                        {{-- Format 'tgl' (Carbon) jadi Y-m-d agar bisa dibaca input type date --}}
                        <input type="date" class="form-control @error('tgl') is-invalid @enderror" 
                               id="tgl" name="tgl" 
                               value="{{ old('tgl', $barang->tgl->format('Y-m-d')) }}" required>
                        @error('tgl')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Harga Awal -->
                    <div class="mb-3">
                        <label for="harga_awal" class="form-label">Harga Awal (Rp)</label>
                        <input type="number" class="form-control @error('harga_awal') is-invalid @enderror" 
                               id="harga_awal" name="harga_awal" 
                               value="{{ old('harga_awal', $barang->harga_awal) }}" min="0" required>
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
                                  id="deskripsi_barang" name="deskripsi_barang" rows="4" required>{{ old('deskripsi_barang', $barang->deskripsi_barang) }}</textarea>
                        @error('deskripsi_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Gambar Saat Ini (Preview) -->
                    <div class="mb-2">
                        <label class="form-label d-block">Gambar Saat Ini:</label>
                        @if($barang->gambar)
                            <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" style="width: 150px; height: auto; border-radius: 8px; border: 1px solid #ddd;">
                        @else
                            <span class="text-muted small">Tidak ada gambar</span>
                        @endif
                    </div>

                    <!-- Upload Gambar Baru -->
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Ganti Gambar (Opsional)</label>
                        <input class="form-control @error('gambar') is-invalid @enderror" 
                               type="file" id="gambar" name="gambar">
                        <div class="form-text">Biarkan kosong jika tidak ingin mengganti gambar.</div>
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
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save-fill me-1"></i> Update Data
                </button>
            </div>
        </form>

    </div>
</div>
@endsection