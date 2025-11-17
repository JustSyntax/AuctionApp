@extends('layouts.app')

@section('title', 'Data Masyarakat')
@section('page-title', 'Manajemen Masyarakat')

@push('styles')
<style>
    /* ... style lama search-box dll biarkan saja ... */
    .card-header-flex { display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap; }
    .search-box { position: relative; max-width: 300px; width: 100%; }
    .search-box input { border-radius: 20px; padding-left: 35px; background: #f9f9f9; border: 1px solid #e0e0e0; }
    .search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #aaa; }
    
    /* BUTTON STATUS TOGGLE */
    .btn-status {
        border: none; padding: 5px 15px; border-radius: 20px; font-size: 11px; font-weight: 700;
        text-transform: uppercase; transition: .3s; cursor: pointer;
    }
    /* Kalau Aktif (Klik biar blokir) */
    .btn-status.aktif { background: rgba(40, 167, 69, 0.15); color: #28a745; }
    .btn-status.aktif:hover { background: #28a745; color: white; }
    .btn-status.aktif::after { content: " (AKTIF)"; }
    .btn-status.aktif:hover::after { content: " (BLOKIR?)"; }
    
    /* Kalau Diblokir (Klik biar aktif) */
    .btn-status.blokir { background: rgba(220, 53, 69, 0.15); color: #dc3545; }
    .btn-status.blokir:hover { background: #dc3545; color: white; }
    .btn-status.blokir::after { content: " (BLOKIR)"; }
    .btn-status.blokir:hover::after { content: " (AKTIFKAN?)"; }

    /* Action Buttons */
    .btn-action { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; background: transparent; }
    .btn-action.edit { color: #2a53ff; background: rgba(42, 83, 255, 0.1); }
    .btn-action.edit:hover { background: #2a53ff; color: white; }
    .btn-action.delete { color: #ff4d4d; background: rgba(255, 77, 77, 0.1); }
    .btn-action.delete:hover { background: #ff4d4d; color: white; }
</style>
@endpush

@section('content')
<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-header bg-white card-header-flex py-3">
        <div class="d-flex align-items-center gap-3">
            <h5 class="mb-0 fw-bold text-dark">Data Masyarakat</h5>
            <a href="{{ route('masyarakat.create') }}" class="btn btn-primary btn-sm px-3" style="border-radius: 20px;">
                <i class="bi bi-plus-lg me-1"></i> Baru
            </a>
        </div>
        <form action="{{ route('masyarakat.index') }}" method="GET" class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="search" class="form-control" placeholder="Cari NIK / Nama..." value="{{ request('search') }}">
        </form>
    </div>
    <div class="card-body p-0">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger m-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr style="font-size: 13px; text-transform: uppercase;">
                        <th class="ps-4 py-3">No</th>
                        <th class="py-3">NIK</th>
                        <th class="py-3">Nama Lengkap</th>
                        <th class="py-3">Username</th>
                        <th class="py-3">Telp</th>
                        <th class="py-3 text-center">Status Akun</th>
                        <th class="text-center py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px;">
                    @forelse ($masyarakats as $index => $mas)
                    <tr>
                        <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $mas->nik }}</td>
                        <td>{{ $mas->nama_lengkap }}</td>
                        <td class="text-muted">@ {{ $mas->username }}</td>
                        <td>{{ $mas->telp }}</td>
                        
                        {{-- KOLOM STATUS (KLIK UNTUK GANTI) --}}
                        <td class="text-center">
                            <form action="{{ route('masyarakat.toggleStatus', $mas->id_user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-status {{ $mas->status == 'aktif' ? 'aktif' : 'blokir' }}"
                                    title="Klik untuk mengubah status">
                                    {{-- Teks diurus CSS ::after --}}
                                </button>
                            </form>
                        </td>

                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('masyarakat.edit', $mas->id_user) }}" class="btn-action edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('masyarakat.destroy', $mas->id_user) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user {{ $mas->nama_lengkap }}?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action delete"><i class="bi bi-trash-fill"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Data tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection