@extends('layouts.app')

@section('title', 'Data Petugas')
@section('page-title', 'Manajemen Petugas')

@push('styles')
<style>
    .card-header-flex { display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap; }
    .search-box { position: relative; max-width: 300px; width: 100%; }
    .search-box input { border-radius: 20px; padding-left: 35px; background: #f9f9f9; border: 1px solid #e0e0e0; }
    .search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #aaa; }
    
    /* Badge Level */
    .badge-level { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; }
    .lvl-admin { background: rgba(111, 66, 193, 0.1); color: #6f42c1; border: 1px solid rgba(111, 66, 193, 0.2); }
    .lvl-petugas { background: rgba(13, 202, 240, 0.1); color: #0dcaf0; border: 1px solid rgba(13, 202, 240, 0.2); }

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
            <h5 class="mb-0 fw-bold text-dark">Data Petugas</h5>
            <a href="{{ route('petugas.create') }}" class="btn btn-primary btn-sm px-3" style="border-radius: 20px;">
                <i class="bi bi-plus-lg me-1"></i> Baru
            </a>
        </div>
        <form action="{{ route('petugas.index') }}" method="GET" class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="search" class="form-control" placeholder="Cari Nama / Username..." value="{{ request('search') }}">
        </form>
    </div>
    <div class="card-body p-0">
        
        {{-- Alert --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger m-3">{{ $errors->first() }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr style="font-size: 13px; text-transform: uppercase;">
                        <th class="ps-4 py-3">No</th>
                        <th class="py-3">Nama Petugas</th>
                        <th class="py-3">Username</th>
                        <th class="py-3 text-center">Level</th>
                        <th class="text-center py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px;">
                    @forelse ($petugas as $index => $p)
                    <tr>
                        <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $p->nama_petugas }}</td>
                        <td class="text-muted">@ {{ $p->username }}</td>
                        <td class="text-center">
                            @if($p->level->level == 'administrator')
                                <span class="badge-level lvl-admin">Administrator</span>
                            @else
                                <span class="badge-level lvl-petugas">Petugas</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('petugas.edit', $p->id_petugas) }}" class="btn-action edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                {{-- Cegah hapus diri sendiri --}}
                                @if($p->id_petugas != session('user_id'))
                                <form action="{{ route('petugas.destroy', $p->id_petugas) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus petugas {{ $p->nama_petugas }}?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action delete"><i class="bi bi-trash-fill"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Data tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection