@extends('layouts.app')
@section('title', 'Tambah Petugas')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Registrasi Petugas Baru</h5>
        <a href="{{ route('petugas.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('petugas.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Petugas <span class="text-danger">*</span></label>
                    <input type="text" name="nama_petugas" class="form-control @error('nama_petugas') is-invalid @enderror" value="{{ old('nama_petugas') }}" required>
                    @error('nama_petugas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label">Level Akses <span class="text-danger">*</span></label>
                    <select name="id_level" class="form-select @error('id_level') is-invalid @enderror" required>
                        <option value="" disabled selected>-- Pilih Level --</option>
                        @foreach($levels as $lvl)
                            <option value="{{ $lvl->id_level }}" {{ old('id_level') == $lvl->id_level ? 'selected' : '' }}>
                                {{ ucfirst($lvl->level) }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save-fill me-1"></i> Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection