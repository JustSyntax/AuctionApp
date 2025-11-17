@extends('layouts.app')
@section('title', 'Edit Petugas')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Petugas: {{ $petugas->nama_petugas }}</h5>
        <a href="{{ route('petugas.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('petugas.update', $petugas->id_petugas) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Petugas</label>
                    <input type="text" name="nama_petugas" class="form-control @error('nama_petugas') is-invalid @enderror" value="{{ old('nama_petugas', $petugas->nama_petugas) }}" required>
                    @error('nama_petugas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $petugas->username) }}" required>
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label">Level Akses</label>
                    <select name="id_level" class="form-select @error('id_level') is-invalid @enderror" required>
                        @foreach($levels as $lvl)
                            <option value="{{ $lvl->id_level }}" {{ $petugas->id_level == $lvl->id_level ? 'selected' : '' }}>
                                {{ ucfirst($lvl->level) }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Isi jika ingin ganti password">
                    <small class="text-muted">Min 8 karakter.</small>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-warning"><i class="bi bi-save-fill me-1"></i> Update Data</button>
            </div>
        </form>
    </div>
</div>
@endsection