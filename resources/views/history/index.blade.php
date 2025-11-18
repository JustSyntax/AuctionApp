@extends('layouts.app')

@section('title', 'History Lelang')
@section('page-title', 'Riwayat Lelang')

@push('styles')
<style>
    .table-image { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
    
    /* Status Badge untuk Masyarakat */
    .status-win { color: #198754; font-weight: 700; background: rgba(25, 135, 84, 0.1); padding: 5px 10px; border-radius: 20px; font-size: 11px; }
    .status-lose { color: #dc3545; font-weight: 600; background: rgba(220, 53, 69, 0.1); padding: 5px 10px; border-radius: 20px; font-size: 11px; }
    .status-pending { color: #0dcaf0; font-weight: 600; background: rgba(13, 202, 240, 0.1); padding: 5px 10px; border-radius: 20px; font-size: 11px; }

    .btn-action { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; background: transparent; text-decoration: none; }
    .btn-action.view { color: #2a53ff; background: rgba(42, 83, 255, 0.1); }
    .btn-action.view:hover { background: #2a53ff; color: white; }
</style>
@endpush

@section('content')

{{-- === TAMPILAN ADMIN / PETUGAS === --}}
@if($role == 'administrator' || $role == 'petugas')
<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-archive-fill me-2 text-secondary"></i> Laporan Lelang Selesai</h5>
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
                        <td class="ps-4 text-muted">{{ $index + 1 }}</td>
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
                            {{-- Kita tambahkan parameter 'source' => 'history' --}}
                            <a href="{{ route('lelang.show', ['lelang' => $lelang->id_lelang, 'source' => 'history']) }}" 
                            class="btn-action view" title="Lihat Detail">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Belum ada lelang yang ditutup.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- === TAMPILAN MASYARAKAT === --}}
@elseif($role == 'masyarakat')
<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history me-2 text-primary"></i> Riwayat Penawaran Saya</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr style="font-size: 13px; text-transform: uppercase;">
                        <th class="ps-4 py-3">No</th>
                        <th class="py-3">Barang</th>
                        <th class="py-3">Tawaran Anda</th>
                        <th class="py-3">Waktu Bid</th>
                        <th class="py-3 text-center">Status Lelang</th>
                        <th class="py-3 text-center">Hasil</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px;">
                    @forelse ($myBids as $index => $bid)
                    <tr>
                        <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold">{{ $bid->lelang->barang->nama_barang }}</div>
                            <div class="small text-muted">Petugas: {{ $bid->lelang->petugas->nama_petugas }}</div>
                        </td>
                        <td class="fw-bold text-primary">Rp {{ number_format($bid->penawaran_harga, 0, ',', '.') }}</td>
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
                                {{-- Cek apakah harga akhir lelang == harga tawaran user ini --}}
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
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-emoji-neutral display-6 d-block mb-2"></i>
                            Anda belum pernah melakukan penawaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection