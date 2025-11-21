@extends('layouts.app')
@section('title', 'History Lelang')

@push('styles')
<style>
    .table-image { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
    
    /* CSS FORM FILTER */
    .form-group-flex { 
        display: flex; gap: 10px; align-items: center; flex-wrap: wrap; 
        justify-content: flex-end; /* Pastikan konten form rapat kanan */
    }
    
    .btn-filter { background: #f8f9fa; border: 1px solid #ddd; color: #666; }
    .btn-filter:hover { background: #e2e6ea; color: #333; }

    /* Status Badge */
    .status-win { color: #198754; font-weight: 700; background: rgba(25, 135, 84, 0.1); padding: 5px 10px; border-radius: 20px; font-size: 11px; }
    .status-lose { color: #dc3545; font-weight: 600; background: rgba(220, 53, 69, 0.1); padding: 5px 10px; border-radius: 20px; font-size: 11px; }
    .status-pending { color: #0dcaf0; font-weight: 600; background: rgba(13, 202, 240, 0.1); padding: 5px 10px; border-radius: 20px; font-size: 11px; }
    
    .btn-action.view { color: #2a53ff; background: rgba(42, 83, 255, 0.1); width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; text-decoration: none;}
    .btn-action.view:hover { background: #2a53ff; color: white; }

    /* Pagination Style Customization (Optional) */
    .pagination { margin-bottom: 0; justify-content: end; }
    .page-item.active .page-link { background-color: #2a53ff; border-color: #2a53ff; }
    .page-link { color: #2a53ff; }
</style>
@endpush

@section('content')

{{-- ========================== 1. ADMIN & PETUGAS ========================== --}}
@if($role == 'administrator' || $role == 'petugas')
<div class="card shadow-sm border-0" style="border-radius: 15px;">
    
    {{-- HEADER --}}
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-archive-fill me-2 text-secondary"></i> Laporan Lelang</h5>
            
            {{-- FORM FILTER (ms-auto biar mentok kanan) --}}
            <form action="{{ route('history.index') }}" method="GET" class="form-group-flex ms-auto">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar3"></i></span>
                    <input type="date" name="date_start" class="form-control" value="{{ request('date_start') }}" title="Tanggal Mulai">
                    <span class="input-group-text bg-light border-start-0 border-end-0">s/d</span>
                    <input type="date" name="date_end" class="form-control" value="{{ request('date_end') }}" title="Tanggal Akhir">
                </div>

                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari Barang..." value="{{ request('search') }}">
                </div>

                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel-fill"></i> Filter</button>
                @if(request()->has('search') || request()->has('date_start'))
                <a href="{{ route('history.index') }}" class="btn btn-sm btn-filter" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
                @endif
            </form>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr style="font-size: 13px; text-transform: uppercase;">
                        <th class="ps-4 py-3">No</th>
                        <th class="py-3">Barang</th>
                        <th class="py-3">Tgl Ditutup</th>
                        <th class="py-3">Harga Akhir</th>
                        <th class="py-3">Pemenang</th>
                        <th class="text-center py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px;">
                    @forelse ($lelangs as $index => $lelang)
                    <tr>
                        {{-- Penomoran sesuai pagination --}}
                        <td class="ps-4 text-muted">{{ $lelangs->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($lelang->barang->gambar)
                                    <img src="{{ asset('storage/'.$lelang->barang->gambar) }}" class="table-image">
                                @else
                                    <div class="table-image d-flex align-items-center justify-content-center bg-light small">No IMG</div>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $lelang->barang->nama_barang }}</div>
                                    <div class="small text-muted">Awal: Rp {{ number_format($lelang->barang->harga_awal, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $lelang->updated_at->format('d M Y') }}</td>
                        <td class="fw-bold text-success">Rp {{ number_format($lelang->harga_akhir, 0, ',', '.') }}</td>
                        <td>
                            @if($lelang->pemenang)
                                <span class="fw-semibold text-dark">{{ $lelang->pemenang->masyarakat->nama_lengkap }}</span>
                                <div class="small text-muted">@ {{ $lelang->pemenang->masyarakat->username }}</div>
                            @else
                                <span class="text-muted fst-italic">Tidak ada pemenang</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('lelang.show', ['lelang' => $lelang->id_lelang, 'source' => 'history']) }}" class="btn-action view" title="Lihat Detail">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x display-6 d-block mb-2"></i>
                            Data tidak ditemukan pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- PAGINATION LINKS ADMIN --}}
    <div class="card-footer bg-white py-3">
        {{ $lelangs->links() }}
    </div>
</div>


{{-- ========================== 2. MASYARAKAT ========================== --}}
@elseif($role == 'masyarakat')
<div class="card shadow-sm border-0" style="border-radius: 15px;">
    
    {{-- HEADER --}}
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history me-2 text-primary"></i> Riwayat Saya</h5>
            
            {{-- FORM FILTER (ms-auto biar mentok kanan) --}}
            <form action="{{ route('history.index') }}" method="GET" class="form-group-flex ms-auto">
                
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari Barang..." value="{{ request('search') }}">
                </div>

                <select name="filter_status" class="form-select form-select-sm" style="width: 130px;" onchange="this.form.submit()">
                    <option value="" {{ request('filter_status') == '' ? 'selected' : '' }}>Semua Status</option>
                    <option value="menang" {{ request('filter_status') == 'menang' ? 'selected' : '' }}>Menang</option>
                    <option value="kalah" {{ request('filter_status') == 'kalah' ? 'selected' : '' }}>Kalah</option>
                    <option value="pending" {{ request('filter_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>

                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                
                @if(request()->has('search') || request()->has('filter_status'))
                    <a href="{{ route('history.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr style="font-size: 13px; text-transform: uppercase;">
                        <th class="ps-4 py-3">No</th>
                        <th class="py-3">Barang</th>
                        <th class="py-3">Harga Pembuka</th>
                        <th class="py-3">Tawaran Anda</th>
                        <th class="py-3">Bid Tertinggi</th>
                        <th class="py-3">Waktu Bid</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3 text-center">Hasil</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px;">
                    @forelse ($myBids as $index => $bid)
                    <tr>
                        {{-- Penomoran sesuai pagination --}}
                        <td class="ps-4 text-muted">{{ $myBids->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-bold">{{ $bid->lelang->barang->nama_barang }}</div>
                            <div class="small text-muted">Petugas: {{ $bid->lelang->petugas->nama_petugas }}</div>
                        </td>
                        <td class="text-muted">Rp {{ number_format($bid->lelang->barang->harga_awal, 0, ',', '.') }}</td>
                        <td class="fw-bold text-primary">Rp {{ number_format($bid->penawaran_harga, 0, ',', '.') }}</td>
                        <td class="fw-bold text-success">Rp {{ number_format($bid->lelang->harga_akhir, 0, ',', '.') }}</td>
                        <td>{{ $bid->created_at->format('d M Y, H:i') }}</td>
                        
                        <td class="text-center">
                            @if($bid->lelang->status == 'dibuka')
                                <span class="badge bg-info-subtle text-info-emphasis rounded-pill">Masih Berjalan</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill">Sudah Ditutup</span>
                            @endif
                        </td>
                        
                        <td class="text-center">
                            @if($bid->lelang->status == 'ditutup')
                                @if($bid->lelang->harga_akhir == $bid->penawaran_harga)
                                    <span class="status-win"><i class="bi bi-trophy-fill me-1"></i> MENANG</span>
                                @else
                                    <span class="status-lose"><i class="bi bi-x-circle-fill me-1"></i> KALAH</span>
                                @endif
                            @else
                                <span class="status-pending"><i class="bi bi-hourglass-split me-1"></i> PENDING</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-emoji-neutral display-6 d-block mb-2"></i>
                            Data tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION LINKS MASYARAKAT --}}
    <div class="card-footer bg-white py-3">
        {{ $myBids->links() }}
    </div>
</div>
@endif

@endsection