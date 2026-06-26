<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,
    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'>
        <text y='.9em' font-size='90'>👁️</text>
    </svg>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Sistem Pakar Diagnosis Penyakit Mata menggunakan metode
               Certainty Factor dan Forward Chaining. Diagnosa awal Katarak,
               Glaukoma, Konjungtivitis, dan Retinopati Diabetik.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — Admin Sistem Pakar Mata</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --biru-utama: #2E86AB;
            --biru-gelap: #1a5276;
            --hijau-mint: #57CC99;
            --sidebar-bg: #1a2e3b;
            --sidebar-w: 260px;
            --topbar-h: 64px;
            --putih: #FFFFFF;
            --abu-terang: #F4F6F9;
            --teks-abu: #6c757d;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--abu-terang);
            margin: 0;
            padding: 0;
        }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .sidebar-brand .brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--biru-utama), var(--hijau-mint));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .sidebar-brand .brand-text {
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            line-height: 1.3;
        }

        .sidebar-brand .brand-text span {
            display: block;
            font-weight: 400;
            font-size: 0.75rem;
            opacity: 0.6;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
        }

        .nav-section-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255, 255, 255, 0.35);
            padding: 0.75rem 1.5rem 0.4rem;
        }

        .nav-item-custom a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.5rem;
            color: rgba(255, 255, 255, 0.65);
            text-decoration: none;
            font-size: 0.88rem;
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }

        .nav-item-custom a:hover {
            color: white;
            background: rgba(255, 255, 255, 0.06);
            border-left-color: rgba(255, 255, 255, 0.3);
        }

        .nav-item-custom a.active {
            color: white;
            background: rgba(46, 134, 171, 0.25);
            border-left-color: var(--hijau-mint);
        }

        .nav-item-custom a i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .admin-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--biru-utama), var(--hijau-mint));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.85rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .admin-info-text {
            flex: 1;
            min-width: 0;
        }

        .admin-info-text .admin-name {
            color: white;
            font-size: 0.82rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-info-text .admin-role {
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.72rem;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.5rem;
            background: rgba(220, 53, 69, 0.15);
            color: #ff6b7a;
            border: 1px solid rgba(220, 53, 69, 0.25);
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: rgba(220, 53, 69, 0.3);
            color: #ff4757;
        }

        /* ── Topbar ── */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: white;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
            z-index: 999;
        }

        .topbar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            color: #495057;
            cursor: pointer;
            padding: 0.25rem;
        }

        .topbar-breadcrumb {
            flex: 1;
        }

        .topbar-breadcrumb .page-title {
            font-size: 1rem;
            font-weight: 700;
            color: #212529;
            margin: 0;
            line-height: 1;
        }

        .topbar-breadcrumb .breadcrumb {
            margin: 0;
            padding: 0;
            font-size: 0.78rem;
        }

        .topbar-breadcrumb .breadcrumb-item a {
            color: var(--biru-utama);
            text-decoration: none;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .topbar-date {
            font-size: 0.8rem;
            color: var(--teks-abu);
        }

        /* ── Main Content ── */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
        }

        .main-content {
            padding: 1.75rem;
        }

        /* ── Cards ── */
        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #f0f0f0;
            border-radius: 14px 14px 0 0 !important;
            padding: 1rem 1.25rem;
            font-weight: 600;
            font-size: 0.92rem;
        }

        /* ── Tombol ── */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--biru-utama), var(--hijau-mint));
            border: none;
            color: white;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            transition: opacity 0.2s, transform 0.1s;
        }

        .btn-primary-custom:hover {
            opacity: 0.9;
            color: white;
            transform: translateY(-1px);
        }

        /* ── Alert ── */
        .alert {
            border-radius: 10px;
            font-size: 0.88rem;
        }

        /* ── Tabel ── */
        .table {
            font-size: 0.88rem;
        }

        .table th {
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--teks-abu);
            border-bottom: 2px solid #f0f0f0;
        }

        .table td {
            vertical-align: middle;
        }

        /* ── Overlay sidebar mobile ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        /* ── Responsif ── */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                display: block;
            }

            .topbar {
                left: 0;
            }

            .topbar-toggle {
                display: block;
            }

            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- Overlay untuk mobile --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    {{-- ── SIDEBAR ── --}}
    <aside class="sidebar" id="sidebar">

        {{-- Brand --}}
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-eye-fill"></i>
            </div>
            <div class="brand-text">
                Sistem Pakar Mata
                <span>Admin Panel</span>
            </div>
        </a>

        {{-- Navigasi --}}
        <nav class="sidebar-nav">

            <div class="nav-section-label">Utama</div>

            <div class="nav-item-custom">
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </div>

            <div class="nav-section-label">Manajemen Data</div>

            <div class="nav-item-custom">
                <a href="{{ route('admin.penyakit.index') }}"
                    class="{{ request()->routeIs('admin.penyakit.*') ? 'active' : '' }}">
                    <i class="bi bi-virus"></i>
                    Penyakit
                </a>
            </div>

            <div class="nav-item-custom">
                <a href="{{ route('admin.gejala.index') }}"
                    class="{{ request()->routeIs('admin.gejala.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-pulse"></i>
                    Gejala
                </a>
            </div>

            <div class="nav-item-custom">
                <a href="{{ route('admin.aturan.index') }}"
                    class="{{ request()->routeIs('admin.aturan.*') ? 'active' : '' }}">
                    <i class="bi bi-diagram-3"></i>
                    Aturan / Relasi CF
                </a>
            </div>

            <div class="nav-section-label">Diagnosa</div>

            <div class="nav-item-custom">
                <a href="{{ route('admin.diagnosa.index') }}"
                    class="{{ request()->routeIs('admin.diagnosa.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-medical"></i>
                    Data Diagnosa
                </a>
            </div>

            <div class="nav-section-label">Pengaturan</div>

            @if (Auth::guard('admin')->user()?->isSuperAdmin())
                <div class="nav-item-custom">
                    <a href="{{ route('admin.admin.index') }}"
                        class="{{ request()->routeIs('admin.admin.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        Manajemen Admin
                    </a>
                </div>
            @endif

        </nav>

        {{-- Footer Sidebar --}}
        <div class="sidebar-footer">
            <div class="admin-info">
                <div class="admin-avatar">
                    {{ strtoupper(substr(Auth::guard('admin')->user()?->nama ?? 'A', 0, 1)) }}
                </div>
                <div class="admin-info-text">
                    <div class="admin-name">
                        {{ Auth::guard('admin')->user()?->nama ?? 'Admin' }}
                    </div>
                    <div class="admin-role">Administrator</div>
                </div>
            </div>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </button>
            </form>
        </div>

    </aside>

    {{-- ── TOPBAR ── --}}
    <header class="topbar">
        <button class="topbar-toggle" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>

        <div class="topbar-breadcrumb">
            <h6 class="page-title">@yield('page-title', 'Dashboard')</h6>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Admin</a>
                    </li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>

        <div class="topbar-right">
            <span class="topbar-date d-none d-md-block">
                <i class="bi bi-calendar3 me-1"></i>
                {{ now()->translatedFormat('d F Y') }}
            </span>
        </div>
    </header>

    {{-- ── MAIN CONTENT ── --}}
    <div class="main-wrapper">
        <main class="main-content">

            {{-- Alert Global --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3"
                    role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3"
                    role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('show');
            document.getElementById('sidebarOverlay').classList.remove('show');
        }
    </script>

    @stack('scripts')
</body>

</html>
