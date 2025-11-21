@extends('layouts.app')
@section('title', 'Edit Masyarakat')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit User: {{ $masyarakat->nama_lengkap }}</h5>
        <a href="{{ route('masyarakat.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('masyarakat.update', $masyarakat->id_user) }}" method="POST">
            @csrf @method('PUT')
            
            {{-- Status kita hidden aja, karena status editnya lewat tombol di index --}}
            <input type="hidden" name="status" value="{{ $masyarakat->status }}">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">NIK</label>
                    <input type="number" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik', $masyarakat->nik) }}" oninput="if(this.value.length > 16) this.value = this.value.slice(0, 16);"
                       onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                    @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $masyarakat->nama_lengkap) }}" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $masyarakat->username) }}" required>
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Isi hanya jika ingin mengganti">
                    <small class="text-muted">Minimal 8 karakter & tidak boleh sama dengan password lama.</small>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                </div>

                <div class="col-md-12">
                    <label class="form-label">No. Telepon</label>
                    <input type="number" name="telp" class="form-control @error('telp') is-invalid @enderror" value="{{ old('telp', $masyarakat->telp) }}" oninput="if(this.value.length > 13) this.value = this.value.slice(0, 13);"
                       onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2" required>{{ old('alamat', $masyarakat->alamat) }}</textarea>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-warning"><i class="bi bi-save-fill me-1"></i> Update Data</button>
            </div>
        </form>
    </div>
</div>
@endsection