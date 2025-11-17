<!-- resources/views/petugas/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Petugas')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            background: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #111;
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #2a53ff;
            color: white;
        }
        .sidebar .nav-link.active {
            background: #2a53ff;
        }
        .content {
            padding: 20px;
        }
        .topbar {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .sidebar .dropdown-toggle::after {
            float: right;
            margin-top: 0.3em;
        }
    </style>
</head>
<body>
<div class="d-flex">

    <!-- SIDEBAR -->
    <nav class="sidebar d-flex flex-column p-3">
        <a href="{{ route('dashboard.petugas') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-4">DRIVE AUCTION</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ route('dashboard.petugas') }}" class="nav-link {{ request()->routeIs('dashboard.petugas') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            <!-- Master Data Dropdown -->
            <li>
                <a class="nav-link dropdown-toggle text-white" data-bs-toggle="collapse" href="#masterDataMenu" role="button" aria-expanded="false" aria-controls="masterDataMenu">
                    <i class="bi bi-folder2-open me-2"></i> Master Data
                </a>
                <div class="collapse {{ request()->routeIs('petugas.*','masyarakat.*','barang.*') ? 'show' : '' }}" id="masterDataMenu">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li>
                            <a href="" class="nav-link text-white ps-4 {{ request()->routeIs('petugas.*') ? 'active' : '' }}">
                                Petugas
                            </a>
                        </li>
                        <li>
                            <a href="" class="nav-link text-white ps-4 {{ request()->routeIs('masyarakat.*') ? 'active' : '' }}">
                                Masyarakat
                            </a>
                        </li>
                        <li>
                            <a href="" class="nav-link text-white ps-4 {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                                Barang
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Laporan -->
            <li>
                <a href="" class="nav-link text-white {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text me-2"></i> Laporan
                </a>
            </li>

            <!-- Logout -->
            <li class="mt-3">
                <a href="{{ route('logout') }}" class="nav-link text-white">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </li>
        </ul>

        <hr>
        <div class="text-center small">
            Logged in as: {{ session('name') }}<br>
            Role: {{ session('role') }}
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="flex-grow-1">
        <div class="topbar">
            <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
