<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Sistem Pakar Mata</title>

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
            --abu-terang: #F8F9FA;
        }

        body {
            background: linear-gradient(135deg, var(--biru-utama) 0%, #1a5276 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            background: var(--putih);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 440px;
        }

        .login-header {
            background: linear-gradient(135deg, var(--biru-utama), var(--hijau-mint));
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .login-header .logo-icon {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }

        .login-header h4 {
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .login-header p {
            font-size: 0.85rem;
            opacity: 0.85;
            margin: 0;
        }

        .login-body {
            padding: 2rem;
        }

        .form-control {
            border-radius: 10px;
            border: 1.5px solid #dee2e6;
            padding: 0.65rem 1rem;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: var(--biru-utama);
            box-shadow: 0 0 0 3px rgba(46, 134, 171, 0.15);
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
            background: var(--abu-terang);
            border: 1.5px solid #dee2e6;
            border-right: none;
            color: var(--biru-utama);
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--biru-utama), var(--hijau-mint));
            border: none;
            border-radius: 10px;
            padding: 0.7rem;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            width: 100%;
            transition: opacity 0.2s, transform 0.1s;
        }

        .btn-login:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .toggle-password {
            cursor: pointer;
            border-radius: 0 10px 10px 0 !important;
            border: 1.5px solid #dee2e6;
            border-left: none;
            background: var(--abu-terang);
            color: #6c757d;
        }

        .toggle-password:hover {
            color: var(--biru-utama);
        }

        .alert {
            border-radius: 10px;
            font-size: 0.9rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #495057;
        }

        .footer-text {
            text-align: center;
            font-size: 0.8rem;
            color: #adb5bd;
            padding: 1rem 2rem;
            border-top: 1px solid #f0f0f0;
        }
    </style>
</head>
<body>

<div class="login-card">
    {{-- Header --}}
    <div class="login-header">
        <div class="logo-icon">
            <i class="bi bi-eye-fill"></i>
        </div>
        <h4>Sistem Pakar Mata</h4>
        <p>Panel Administrator</p>
    </div>

    {{-- Body --}}
    <div class="login-body">
        <h5 class="fw-700 mb-1">Selamat Datang</h5>
        <p class="text-muted mb-4" style="font-size:0.9rem;">
            Masukkan kredensial admin untuk melanjutkan.
        </p>

        {{-- Alert Error --}}
        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- Alert Success --}}
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Form Login --}}
        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope-fill"></i>
                    </span>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="admin@sistemmata.com"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        autofocus
                    >
                </div>
                @error('email')
                    <div class="text-danger mt-1" style="font-size:0.82rem;">
                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                    >
                    <button type="button" class="btn toggle-password" onclick="togglePassword()">
                        <i class="bi bi-eye-fill" id="toggle-icon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="text-danger mt-1" style="font-size:0.82rem;">
                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label text-muted" for="remember" style="font-size:0.9rem;">
                        Ingat saya
                    </label>
                </div>
            </div>

            {{-- Tombol Login --}}
            <button type="submit" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Dashboard
            </button>
        </form>
    </div>

    <div class="footer-text">
        &copy; {{ date('Y') }} Sistem Pakar Diagnosis Penyakit Mata
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('toggle-icon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
        }
    }
</script>

</body>
</html>