<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,
    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'>
        <text y='.9em' font-size='90'>👁️</text>
    </svg>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
      content="Sistem Pakar Diagnosis Penyakit Mata menggunakan metode
               Certainty Factor dan Forward Chaining. Diagnosa awal Katarak,
               Glaukoma, Konjungtivitis, dan Retinopati Diabetik.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Sistem Pakar Mata') — Diagnosis Penyakit Mata</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --biru-utama: #2E86AB;
            --biru-muda: #A8DADC;
            --hijau-mint: #57CC99;
            --putih: #FFFFFF;
            --abu-terang: #F4F6F9;
            --teks-gelap: #212529;
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
            padding-top: 70px;
        }

        /* ── Navbar ── */
        .navbar-custom {
            background: white;
            border-bottom: 1px solid #e9ecef;
            padding: 0 1.5rem;
            height: 70px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
        }

        .navbar-brand-custom {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
        }

        .brand-icon-user {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--biru-utama), var(--hijau-mint));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .brand-text-user {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--teks-gelap);
            line-height: 1.2;
        }

        .brand-text-user span {
            display: block;
            font-weight: 400;
            font-size: 0.72rem;
            color: var(--teks-abu);
        }

        .navbar-nav-custom {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .navbar-nav-custom a {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 0.85rem;
            color: var(--teks-abu);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .navbar-nav-custom a:hover,
        .navbar-nav-custom a.active {
            color: var(--biru-utama);
            background: rgba(46, 134, 171, 0.08);
        }

        .btn-diagnosa-nav {
            background: linear-gradient(135deg, var(--biru-utama), var(--hijau-mint));
            color: white !important;
            border-radius: 8px;
            padding: 0.5rem 1rem !important;
            font-weight: 600 !important;
        }

        .btn-diagnosa-nav:hover {
            opacity: 0.9;
            background: linear-gradient(135deg, var(--biru-utama), var(--hijau-mint)) !important;
            color: white !important;
        }

        .navbar-toggler-custom {
            display: none;
            background: none;
            border: 1.5px solid #dee2e6;
            border-radius: 8px;
            padding: 0.4rem 0.6rem;
            cursor: pointer;
            font-size: 1.1rem;
            color: var(--teks-abu);
        }

        .navbar-collapse-custom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex: 1;
            margin-left: 2rem;
        }

        .mobile-menu {
            display: none;
            flex-direction: column;
            background: white;
            border-top: 1px solid #e9ecef;
            padding: 0.75rem 1.5rem;
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            z-index: 999;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .mobile-menu.show {
            display: flex;
        }

        .mobile-menu a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 0;
            color: var(--teks-abu);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            border-bottom: 1px solid #f0f0f0;
        }

        .mobile-menu a:last-child {
            border-bottom: none;
        }

        .mobile-menu a:hover {
            color: var(--biru-utama);
        }

        /* ── Footer ── */
        .footer-custom {
            background: var(--sidebar-bg, #1a2e3b);
            background: #1a2e3b;
            color: rgba(255, 255, 255, 0.6);
            text-align: center;
            padding: 1.5rem;
            font-size: 0.82rem;
            margin-top: 3rem;
        }

        .footer-custom a {
            color: var(--hijau-mint);
            text-decoration: none;
        }

        /* ── Konten ── */
        .page-content {
            min-height: calc(100vh - 70px - 80px);
        }

        /* ── Card user ── */
        .card-user {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
        }

        /* ── Tombol utama user ── */
        .btn-utama {
            background: linear-gradient(135deg, var(--biru-utama), var(--hijau-mint));
            border: none;
            color: white;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.65rem 1.5rem;
            transition: opacity 0.2s, transform 0.1s;
        }

        .btn-utama:hover {
            opacity: 0.9;
            color: white;
            transform: translateY(-1px);
        }

        /* ── Alert ── */
        .alert {
            border-radius: 10px;
            font-size: 0.88rem;
        }

        /* ── Responsif ── */
        @media (max-width: 767.98px) {
            .navbar-collapse-custom {
                display: none;
            }

            .navbar-toggler-custom {
                display: block;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- ── NAVBAR ── --}}
    <nav class="navbar-custom">
        <a href="{{ route('beranda') }}" class="navbar-brand-custom">
            <div class="brand-icon-user">
                <i class="bi bi-eye-fill"></i>
            </div>
            <div class="brand-text-user">
                Sistem Pakar Mata
                <span>Diagnosis Penyakit Mata</span>
            </div>
        </a>

        <div class="navbar-collapse-custom">
            <ul class="navbar-nav-custom">
                <li>
                    <a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'active' : '' }}">
                        <i class="bi bi-house"></i> Beranda
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.diagnosa.form') }}"
                        class="{{ request()->routeIs('user.diagnosa.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard2-heart"></i> Diagnosa
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.riwayat.index') }}"
                        class="{{ request()->routeIs('user.riwayat.*') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Riwayat
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.tentang') }}"
                        class="{{ request()->routeIs('user.tentang') ? 'active' : '' }}">
                        <i class="bi bi-info-circle"></i> Tentang
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav-custom">
                <li>
                    <a href="{{ route('user.diagnosa.form') }}" class="btn-diagnosa-nav">
                        <i class="bi bi-search-heart"></i> Mulai Diagnosa
                    </a>
                </li>
            </ul>
        </div>

        <button class="navbar-toggler-custom" onclick="toggleMobileMenu()">
            <i class="bi bi-list"></i>
        </button>
    </nav>

    {{-- Mobile Menu --}}
    <div class="mobile-menu" id="mobileMenu">
        <a href="{{ route('beranda') }}"><i class="bi bi-house"></i> Beranda</a>
        <a href="{{ route('user.diagnosa.form') }}"><i class="bi bi-clipboard2-heart"></i> Diagnosa</a>
        <a href="{{ route('user.riwayat.index') }}"><i class="bi bi-clock-history"></i> Riwayat</a>
        <a href="{{ route('user.tentang') }}"><i class="bi bi-info-circle"></i> Tentang</a>
    </div>

    {{-- ── KONTEN HALAMAN ── --}}
    <div class="page-content">
        <div class="container py-4">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    {{-- ── FOOTER ── --}}
    <footer class="footer-custom">
        <p class="mb-1">
            &copy; {{ date('Y') }} Sistem Pakar Diagnosis Penyakit Mata
        </p>
        <p class="mb-0">
            Menggunakan metode <strong style="color:#57CC99">Certainty Factor</strong>
            &amp; <strong style="color:#57CC99">Forward Chaining</strong>
        </p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <script>
        function toggleMobileMenu() {
            document.getElementById('mobileMenu').classList.toggle('show');
        }

        // Tutup mobile menu saat klik di luar
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('mobileMenu');
            const toggler = document.querySelector('.navbar-toggler-custom');
            if (!menu.contains(e.target) && !toggler.contains(e.target)) {
                menu.classList.remove('show');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
