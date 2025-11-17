<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Drive Auction')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-body: #f4f6f8;       /* Background Konten Putih/Abu Soft */
            --bg-sidebar: #111111;    /* Sidebar Hitam */
            --primary: #2a53ff;       
            --text-sidebar: #a0a0a0;
            --text-sidebar-hover: #ffffff;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-body);
            margin: 0;
            overflow-x: hidden;
        }

        /* === SIDEBAR === */
        .sidebar {
            width: 260px;
            height: 100vh; /* Wajib full height */
            background-color: var(--bg-sidebar);
            position: fixed;
            top: 0; left: 0;
            padding: 25px 20px;
            display: flex;        /* Aktifkan Flexbox */
            flex-direction: column; /* Susun ke bawah */
            z-index: 1000;
            border-right: 1px solid #333;
        }

        /* LOGO FIX */
        .brand-logo {
            font-size: 20px; /* Ukuran dipaskan biar ga bablas */
            font-weight: 800;
            color: white;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            white-space: nowrap; /* Mencegah teks turun baris */
        }
        .brand-logo i {
            font-size: 24px;
            margin-right: 10px;
            color: var(--primary);
        }

        /* MENU CONTAINER (Agar Footer Terndorong ke Bawah) */
        .nav-wrapper {
            flex-grow: 1;    /* Kunci: Mengambil sisa ruang kosong */
            overflow-y: auto; /* Scroll jika menu kepanjangan */
            scrollbar-width: none; /* Hide scrollbar Firefox */
        }
        .nav-wrapper::-webkit-scrollbar { display: none; /* Hide scrollbar Chrome */ }

        .menu-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #666;
            margin-top: 20px;
            margin-bottom: 10px;
            padding-left: 12px;
            font-weight: 700;
        }

        .nav-link {
            color: var(--text-sidebar);
            font-weight: 500;
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 5px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            font-size: 14px;
        }

        .nav-link:hover {
            color: var(--text-sidebar-hover);
            background-color: rgba(255,255,255, 0.08);
        }

        .nav-link.active {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 15px rgba(42, 83, 255, 0.4);
        }

        .nav-link i.icon-left {
            font-size: 1.1rem;
            margin-right: 12px;
            width: 20px; text-align: center;
        }

        /* Submenu */
        .collapse-inner {
            padding-left: 12px;
            margin-top: 5px;
            border-left: 1px solid #333;
            margin-left: 22px;
        }
        .sub-link {
            color: var(--text-sidebar);
            font-size: 13px;
            padding: 8px 12px;
            display: block;
            text-decoration: none;
            transition: .2s;
        }
        .sub-link:hover { color: white; padding-left: 18px; }
        .sub-link.active { color: white; font-weight: 600; }

        /* FOOTER USER (Fixed at Bottom) */
        .sidebar-footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #222;
            flex-shrink: 0; /* Jangan menyusut */
        }

        .user-profile {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 0 5px;
        }
        .avatar {
            width: 40px; height: 40px;
            background: #222;
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            margin-right: 12px;
            border: 1px solid #333;
        }
        .user-info h6 { margin: 0; color: white; font-size: 14px; font-weight: 600; }
        .user-info small { color: #777; font-size: 11px; }

        .btn-logout {
            width: 100%;
            background: rgba(255, 50, 50, 0.1);
            color: #ff4d4d;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: block;
            transition: .3s;
        }
        .btn-logout:hover { background: #ff4d4d; color: white; }

        /* === MAIN CONTENT === */
        .main-wrapper {
            margin-left: 260px;
            padding: 40px; /* Padding besar karena ga ada topbar */
            min-height: 100vh;
        }
        
        /* Judul Halaman di Content Area */
        .page-header {
            margin-bottom: 30px;
        }
        .page-header h2 {
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
        }
        .page-header p { color: #666; font-size: 14px; }
        
        /* -- Style Tambahan (bisa dipindah ke push('styles') ) -- */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        }
        .card-header {
            border-bottom: 1px solid #f0f0f0;
            padding: 20px 25px;
        }
        .card-body {
            padding: 25px;
        }
        
    </style>
    
    @stack('styles')
    
</head>
<body>

<!-- SIDEBAR -->
<nav class="sidebar">
    
    <!-- 1. LOGO -->
    <div class="brand-logo">
        <i class="bi bi-car-front-fill"></i>
        DRIVE AUCTION
    </div>

    <!-- 2. MENU (Dibungkus nav-wrapper biar bisa discroll dan push footer ke bawah) -->
    <div class="nav-wrapper">
        
        <!-- MENU UMUM -->
        @php
            $dashboardRoute = session('role') == 'masyarakat' ? route('dashboard.masyarakat') : route('dashboard.petugas');
        @endphp
        <a href="{{ $dashboardRoute }}" class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
            <span><i class="bi bi-grid-fill icon-left"></i> Overview</span>
        </a>

        <!-- MENU MASYARAKAT -->
        @if(session('role') == 'masyarakat')
            <div class="menu-label">Menu</div>
            <a href="#" class="nav-link {{ request()->routeIs('penawaran.*') ? 'active' : '' }}">
                <span><i class="bi bi-tag-fill icon-left"></i> Penawaran</span>
            </a>
        @endif

        <!-- MENU ADMIN & PETUGAS -->
        @if(session('role') == 'administrator' || session('role') == 'petugas')
            <div class="menu-label">Management</div>

            <!-- Master Data Dropdown -->
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#masterData" role="button">
                <span><i class="bi bi-database-fill icon-left"></i> Master Data</span>
                <i class="bi bi-chevron-down small" style="font-size: 10px;"></i>
            </a>
            <div class="collapse {{ request()->routeIs('petugas.*','masyarakat.*','barang.*') ? 'show' : '' }}" id="masterData">
                <div class="collapse-inner">
                    @if(session('role') == 'administrator')
                        <a href="{{ route('petugas.index') }}" class="sub-link {{ request()->routeIs('petugas.*') ? 'active' : '' }}">Data Petugas</a>
                        <a href="{{ route('masyarakat.index') }}" class="sub-link {{ request()->routeIs('masyarakat.*') ? 'active' : '' }}">Data Masyarakat</a>
                    @endif
                    
                    <!-- INI YANG DIUBAH -->
                    <a href="{{ route('barang.index') }}" class="sub-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">Data Barang</a>
                    
                </div>
            </div>

            <!-- Lelang Dropdown (Petugas Only) -->
            @if(session('role') == 'petugas')
            <a class="nav-link collapsed mt-1" data-bs-toggle="collapse" href="#lelangMenu" role="button">
                <span><i class="bi bi-hammer icon-left"></i> Lelang</span>
                <i class="bi bi-chevron-down small" style="font-size: 10px;"></i>
            </a>
            <div class="collapse {{ request()->routeIs('lelang.*') ? 'show' : '' }}" id="lelangMenu">
                <div class="collapse-inner">
                    <a href="#" class="sub-link">Buka Lelang</a>
                    <a href="#" class="sub-link">Riwayat Lelang</a>
                </div>
            </div>
            @endif

            <!-- Laporan -->
            <a href="#" class="nav-link mt-1 {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <span><i class="bi bi-file-earmark-text-fill icon-left"></i> Laporan</span>
            </a>
        @endif
    </div>

    <!-- 3. FOOTER (User & Logout) -->
    <div class="sidebar-footer">
        <!-- User Profile -->
        <div class="user-profile">
            <div class="avatar">
                {{ substr(session('name'), 0, 1) }}
            </div>
            <div class="user-info">
                <h6>{{ Str::limit(session('name'), 14) }}</h6>
                <small>{{ ucfirst(session('role')) }}</small>
            </div>
        </div>
        
        <!-- Logout Button -->
        <a href="{{ route('logout') }}" class="btn-logout">
            <i class="bi bi-box-arrow-left me-1"></i> Logout
        </a>
    </div>

</nav>

<!-- MAIN CONTENT -->
<div class="main-wrapper">
    <!-- Karena topbar dihapus, kita sediakan area judul disini agar content tidak polosan -->
    <div class="page-header">
        <h2>@yield('page-title', 'Dashboard')</h2>
        <p class="mb-0">Selamat datang kembali di panel {{ session('role') }}.</p>
    </div>

    <div class="content-body">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>