<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Drive Auction')</title>

    {{-- 1. CSS BOOTSTRAP (LOKAL) --}}
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    
    {{-- 2. ICON BOOTSTRAP (LOKAL) --}}
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    
    {{-- 3. FONT MONTSERRAT (LOKAL - Ganti Link Google Font) --}}
    <link href="{{ asset('assets/css/montserrat.css') }}" rel="stylesheet">

    <style>
        :root {
            --bg-body: #f4f6f8;       
            --bg-sidebar: #111111;    
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
            height: 100vh; 
            background-color: var(--bg-sidebar);
            position: fixed;
            top: 0; left: 0;
            padding: 25px 20px;
            display: flex;        
            flex-direction: column; 
            z-index: 1000;
            border-right: 1px solid #333;
        }

        /* LOGO */
        .brand-logo {
            font-size: 20px;
            font-weight: 800;
            color: white;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            white-space: nowrap;
        }
        .brand-logo i {
            font-size: 24px;
            margin-right: 10px;
            color: var(--primary);
        }

        /* MENU WRAPPER */
        .nav-wrapper {
            flex-grow: 1;    
            overflow-y: auto; 
            scrollbar-width: none; 
        }
        .nav-wrapper::-webkit-scrollbar { display: none; }

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

        /* FOOTER USER */
        .sidebar-footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #222;
            flex-shrink: 0; 
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
            padding: 40px; 
            min-height: 100vh;
        }
        
        .page-header { margin-bottom: 30px; }
        .page-header h2 { font-weight: 700; color: #1a1a1a; margin-bottom: 5px; }
        .page-header p { color: #666; font-size: 14px; }
        
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
        .card-header { border-bottom: 1px solid #f0f0f0; padding: 20px 25px; }
        .card-body { padding: 25px; }

        /* --- DROPDOWN FIX --- */
        .nav-link:focus { box-shadow: none; }
        
        .nav-link[aria-expanded="true"] {
            color: #ffffff !important;
            background-color: rgba(255,255,255, 0.05);
        }

        .nav-link.collapsed {
            color: var(--text-sidebar);
            background-color: transparent;
        }

        .nav-link .bi-chevron-down { transition: transform 0.3s ease; }
        .nav-link[aria-expanded="true"] .bi-chevron-down { transform: rotate(180deg); }
    </style>
    
    @stack('styles')
    
</head>
<body>

<nav class="sidebar">
    
    <div class="brand-logo">
        <i class="bi bi-car-front-fill"></i>
        DRIVE AUCTION
    </div>

    <div class="nav-wrapper">
        
        @php
            $dashboardRoute = session('role') == 'masyarakat' ? route('dashboard.masyarakat') : route('dashboard.petugas');
        @endphp
        <a href="{{ $dashboardRoute }}" class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
            <span><i class="bi bi-grid-fill icon-left"></i> Overview</span>
        </a>

        @if(session('role') == 'masyarakat')
            <div class="menu-label">Menu</div>
            
            <a href="{{ route('penawaran.index') }}" class="nav-link {{ request()->routeIs('penawaran.*') ? 'active' : '' }}">
                <span><i class="bi bi-tag-fill icon-left"></i> Penawaran</span>
            </a>
            
            <a href="{{ route('history.index') }}" class="nav-link {{ request()->routeIs('history.index') ? 'active' : '' }}">
                <span><i class="bi bi-clock-history icon-left"></i> Riwayat Saya</span>
            </a>
        @endif


        @if(session('role') == 'administrator' || session('role') == 'petugas')
            <div class="menu-label">Management</div>

            <a class="nav-link collapsed" data-bs-toggle="collapse" 
               href="#masterData" role="button"
               aria-expanded="{{ request()->routeIs('petugas.*','masyarakat.*','barang.*') ? 'true' : 'false' }}">
                <span><i class="bi bi-database-fill icon-left"></i> Master Data</span>
                <i class="bi bi-chevron-down small" style="font-size: 10px;"></i>
            </a>
            <div class="collapse {{ request()->routeIs('petugas.*','masyarakat.*','barang.*') ? 'show' : '' }}" id="masterData">
                <div class="collapse-inner">
                    @if(session('role') == 'administrator')
                        <a href="{{ route('petugas.index') }}" class="sub-link {{ request()->routeIs('petugas.*') ? 'active' : '' }}">Data Petugas</a>
                        <a href="{{ route('masyarakat.index') }}" class="sub-link {{ request()->routeIs('masyarakat.*') ? 'active' : '' }}">Data Masyarakat</a>
                    @endif
                    
                    <a href="{{ route('barang.index') }}" class="sub-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">Data Barang</a>
                </div>
            </div>

            @if(session('role') == 'petugas')
            <a class="nav-link collapsed mt-1" data-bs-toggle="collapse" 
               href="#lelangMenu" role="button"
               aria-expanded="{{ request()->routeIs('lelang.*', 'history.index') ? 'true' : 'false' }}"> <span><i class="bi bi-hammer icon-left"></i> Lelang</span>
                <i class="bi bi-chevron-down small" style="font-size: 10px;"></i>
            </a>
            
            <div class="collapse {{ request()->routeIs('lelang.*', 'history.index') ? 'show' : '' }}" id="lelangMenu">
                <div class="collapse-inner">
                    <a href="{{ route('lelang.index') }}" class="sub-link {{ request()->routeIs('lelang.*') ? 'active' : '' }}">Kelola Lelang</a>
                    
                    <a href="{{ route('history.index') }}" class="sub-link {{ request()->routeIs('history.index') ? 'active' : '' }}">
                        Riwayat Lelang
                    </a>
                </div>
            </div>
            @endif

            <a href="{{ route('laporan.index') }}" class="nav-link mt-1 {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <span><i class="bi bi-file-earmark-text-fill icon-left"></i> Laporan</span>
            </a>
        @endif
    </div>

    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="avatar">
                {{ substr(session('name'), 0, 1) }}
            </div>
            <div class="user-info">
                <h6>{{ Str::limit(session('name'), 14) }}</h6>
                <small>{{ ucfirst(session('role')) }}</small>
            </div>
        </div>
        
        <a href="{{ route('logout') }}" class="btn-logout">
            <i class="bi bi-box-arrow-left me-1"></i> Logout
        </a>
    </div>

</nav>

<div class="main-wrapper">
    <div class="page-header">
        <h2>@yield('page-title', 'Dashboard')</h2>
        <p class="mb-0">Selamat datang kembali di panel {{ session('role') }}.</p>
    </div>

    <div class="content-body">
        @yield('content')
    </div>
</div>

{{-- 4. JS BOOTSTRAP (LOKAL - Ganti Link CDN) --}}
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

@stack('scripts')
</body>
</html>