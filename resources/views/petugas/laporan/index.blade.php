@extends('layouts.app')
@section('title', 'Laporan Lelang')

@push('styles')
{{-- 1. Gunakan CSS Select2 LOKAL (Sudah didownload sebelumnya) --}}
<link href="{{ asset('assets/vendor/select2/select2.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2-bootstrap-5-theme.min.css') }}" />

<style>
    .select2-container .select2-selection--single {
        height: 31px !important;
        font-size: 0.875rem;
        display: flex; align-items: center;
    }
    .select2-container--bootstrap-5 .select2-dropdown .select2-results__options { font-size: 0.875rem; }
</style>
@endpush

@section('content')
<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-file-earmark-bar-graph-fill me-2 text-primary"></i> Laporan Hasil Lelang</h5>
            
            {{-- TOMBOL EXPORT PDF --}}
            <a href="{{ route('laporan.print', request()->all()) }}" target="_blank" class="btn btn-danger btn-sm px-3">
                <i class="bi bi-file-pdf-fill me-1"></i> Cetak PDF
            </a>
        </div>
    </div>

    {{-- FORM FILTER --}}
    <div class="card-body border-bottom bg-light">
        <form action="{{ route('laporan.index') }}" method="GET" class="row g-3 align-items-end">
            
            {{-- Filter Tanggal --}}
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Periode Tanggal (Tutup Lelang)</label>
                <div class="input-group input-group-sm">
                    <input type="date" name="date_start" class="form-control" value="{{ request('date_start') }}">
                    <span class="input-group-text bg-white">s/d</span>
                    <input type="date" name="date_end" class="form-control" value="{{ request('date_end') }}">
                </div>
            </div>

            {{-- Filter Petugas (Hanya Admin) --}}
            @if(session('role') == 'administrator')
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Petugas Penanggung Jawab</label>
                <select name="id_petugas" id="selectPetugas" class="form-select form-select-sm">
                    <option value="">-- Semua Petugas --</option>
                    @foreach($petugasList as $p)
                        <option value="{{ $p->id_petugas }}" {{ request('id_petugas') == $p->id_petugas ? 'selected' : '' }}>
                            {{ $p->nama_petugas }} (@ {{ $p->username }})
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="col-md-3">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i> Filter Data</button>
                <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>

    {{-- TABEL DATA --}}
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr style="font-size: 12px; text-transform: uppercase;">
                        <th class="py-3 ps-3">No</th>
                        <th class="py-3 text-center">ID Lelang</th>
                        <th class="py-3">Petugas</th>
                        <th class="py-3 text-center">Tgl Lelang</th>
                        <th class="py-3">Nama Barang</th>
                        <th class="py-3 text-end">Harga Awal</th>
                        <th class="py-3">Pemenang</th>
                        <th class="py-3 text-end pe-3">Harga Akhir (Bid)</th>
                    </tr>
                </thead>
                <tbody style="font-size: 13px;">
                    @forelse($laporan as $index => $item)
                    <tr>
                        <td class="ps-3">{{ $index + 1 }}</td>
                        <td class="text-center fw-bold text-muted">{{ $item->id_lelang }}</td>
                        <td class="fw-bold">{{ $item->petugas->nama_petugas }}</td>
                        <td class="text-center">{{ $item->tgl_lelang->format('d/m/Y') }}</td>
                        <td>{{ $item->barang->nama_barang }}</td>
                        <td class="text-end text-muted">Rp {{ number_format($item->barang->harga_awal, 0, ',', '.') }}</td>
                        <td>
                            @if($item->pemenang)
                                {{ $item->pemenang->masyarakat->nama_lengkap }}
                            @else
                                <span class="text-muted fst-italic">- Tidak Ada -</span>
                            @endif
                        </td>
                        <td class="text-end pe-3 fw-bold text-success">Rp {{ number_format($item->harga_akhir, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">Tidak ada data laporan.</td>
                    </tr>
                    @endforelse
                </tbody>
                {{-- FOOTER TOTAL --}}
                <tfoot class="bg-light fw-bold">
                    <tr>
                        <td colspan="7" class="text-end py-3 text-uppercase">Total Pendapatan Lelang :</td>
                        <td class="text-end py-3 pe-3 text-primary" style="font-size: 15px;">
                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- 2. Gunakan JS LOKAL (Sudah didownload sebelumnya) --}}
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/select2/select2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#selectPetugas').select2({
            theme: 'bootstrap-5',
            placeholder: "-- Cari Petugas --",
            allowClear: true,
            width: '100%' 
        });
    });
</script>
@endpush