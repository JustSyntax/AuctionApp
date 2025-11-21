@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@push('styles')
<style>
    .stat-card {
        border: none;
        border-radius: 15px;
        padding: 20px;
        color: white;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s;
    }
    .stat-card:hover { transform: translateY(-5px); }
    
    /* Warna Card */
    .bg-gradient-primary { background: linear-gradient(45deg, #4e73df, #224abe); }
    .bg-gradient-success { background: linear-gradient(45deg, #1cc88a, #13855c); }
    .bg-gradient-warning { background: linear-gradient(45deg, #f6c23e, #dda20a); }
    .bg-gradient-info    { background: linear-gradient(45deg, #36b9cc, #258391); }
    .bg-gradient-danger  { background: linear-gradient(45deg, #e74a3b, #be2617); }

    .stat-icon {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 3rem;
        opacity: 0.2;
    }
    .stat-value { font-size: 2rem; font-weight: 700; }
    .stat-label { font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; }

    /* Leaderboard Style */
    .leaderboard-card { border: none; border-radius: 15px; overflow: hidden; }
    .rank-badge {
        width: 30px; height: 30px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%; font-weight: bold; color: white;
    }
    .rank-1 { background-color: #ffd700; box-shadow: 0 0 10px #ffd700; } /* Emas */
    .rank-2 { background-color: #c0c0c0; } /* Perak */
    .rank-3 { background-color: #cd7f32; } /* Perunggu */
    .rank-other { background-color: #f0f0f0; color: #555; }
</style>
@endpush

@section('content')

{{-- ======================= TAMPILAN ADMINISTRATOR ======================= --}}
@if($role == 'administrator')
    <div class="row g-4 mb-4">
        <!-- Card 1: Total Pendapatan -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-gradient-success shadow">
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-value">Rp {{ number_format($data['total_pendapatan'] / 1000000, 1, ',', '.') }}M</div>
                <div class="small mt-1">Rp {{ number_format($data['total_pendapatan'], 0, ',', '.') }} (Full)</div>
                <i class="bi bi-cash-coin stat-icon"></i>
            </div>
        </div>

        <!-- Card 2: Total Petugas -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-gradient-primary shadow">
                <div class="stat-label">Total Petugas</div>
                <div class="stat-value">{{ $data['total_petugas'] }}</div>
                <i class="bi bi-people-fill stat-icon"></i>
            </div>
        </div>

        <!-- Card 3: Total Masyarakat -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-gradient-info shadow">
                <div class="stat-label">Total Masyarakat</div>
                <div class="stat-value">{{ $data['total_masyarakat'] }}</div>
                <i class="bi bi-person-vcard-fill stat-icon"></i>
            </div>
        </div>

        <!-- Card 4: Total Barang -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-gradient-warning shadow">
                <div class="stat-label">Total Aset Barang</div>
                <div class="stat-value">{{ $data['total_barang'] }}</div>
                <i class="bi bi-box-seam-fill stat-icon"></i>
            </div>
        </div>
    </div>

    <!-- LEADERBOARD SECTION -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card leaderboard-card shadow-sm">
                <div class="card-header bg-white py-3 d-flex align-items-center">
                    <h5 class="m-0 font-weight-bold text-dark">
                        Top Performance Petugas
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-3">Rank</th>
                                    <th>Nama Petugas</th>
                                    <th>Username</th>
                                    <th class="text-center">Lelang Selesai</th>
                                    <th class="text-end pe-4">Efisiensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['top_petugas'] as $index => $p)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="rank-badge {{ $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : 'rank-other')) }}">
                                                {{ $index + 1 }}
                                            </div>
                                        </td>
                                        <td class="fw-bold">{{ $p->nama_petugas }}</td>
                                        <td class="text-muted">@ {{ $p->username }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-success rounded-pill px-3">{{ $p->lelang_count }} Closed</span>
                                        </td>
                                        <td class="text-end pe-4">
                                            {{-- Simple logic: makin banyak lelang, makin tinggi progress bar --}}
                                            @php $percent = min(($p->lelang_count * 5), 100); @endphp
                                            <div class="progress" style="height: 6px; width: 100px; display: inline-flex;">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percent }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">Belum ada data performa petugas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-5">
                    <img src="{{ asset('assets/image/admin-icon.png') }}" alt="Admin" style="width: 150px; opacity: 0.8;">
                    <h4 class="mt-4 fw-bold">Panel Administrator</h4>
                    <p class="text-muted">Pantau seluruh aktivitas lelang, kelola data master, dan cetak laporan kinerja petugas dari sini.</p>
                    <a href="{{ route('laporan.index') }}" class="btn btn-outline-primary mt-2">Lihat Laporan Lengkap</a>
                </div>
            </div>
        </div>
    </div>

{{-- ======================= TAMPILAN PETUGAS BIASA ======================= --}}
@else
    <div class="row g-4">
        <div class="col-12">
            <div class="alert alert-primary border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                <div>
                    <strong>Selamat Datang, {{ session('name') }}!</strong>
                    <br>Ini adalah statistik kinerja lelang yang Anda tangani secara pribadi.
                </div>
            </div>
        </div>

        <!-- Card 1: Lelang Sedang Dibuka -->
        <div class="col-md-4">
            <div class="card stat-card bg-gradient-info shadow">
                <div class="stat-label">Lelang Aktif (Dibuka)</div>
                <div class="stat-value">{{ $data['lelang_dibuka'] }}</div>
                <i class="bi bi-broadcast stat-icon"></i>
            </div>
        </div>

        <!-- Card 2: Lelang Selesai -->
        <div class="col-md-4">
            <div class="card stat-card bg-gradient-success shadow">
                <div class="stat-label">Lelang Sukses (Ditutup)</div>
                <div class="stat-value">{{ $data['lelang_ditutup'] }}</div>
                <i class="bi bi-check-circle-fill stat-icon"></i>
            </div>
        </div>

        <!-- Card 3: Total Pendapatan Pribadi -->
        <div class="col-md-4">
            <div class="card stat-card bg-gradient-warning shadow">
                <div class="stat-label">Total Omset Anda</div>
                <div class="stat-value">Rp {{ number_format($data['pendapatan_saya'], 0, ',', '.') }}</div>
                <i class="bi bi-wallet2 stat-icon"></i>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-12 mt-4">
            <h5 class="fw-bold mb-3">Aksi Cepat</h5>
            <div class="d-flex gap-3">
                <a href="{{ route('lelang.create') }}" class="btn btn-primary py-3 px-4 shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Buka Lelang Baru
                </a>
                <a href="{{ route('lelang.index') }}" class="btn btn-outline-secondary py-3 px-4">
                    <i class="bi bi-list-ul me-2"></i> Kelola Lelang Saya
                </a>
            </div>
        </div>
    </div>
@endif

@endsection