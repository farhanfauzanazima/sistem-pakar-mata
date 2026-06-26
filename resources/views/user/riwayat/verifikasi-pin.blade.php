@extends('layouts.user')

@section('title', 'Verifikasi PIN')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">

            <div class="text-center mb-4">
                <div
                    style="
                width:64px;height:64px;border-radius:18px;margin:0 auto 1rem;
                background:linear-gradient(135deg,#2E86AB,#57CC99);
                display:flex;align-items:center;justify-content:center;
                font-size:1.8rem;color:white;
            ">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h4 class="fw-bold mb-1">Verifikasi PIN</h4>
                <p class="text-muted" style="font-size:0.9rem;">
                    Halo, <strong>{{ (session('pasien') ?? $pasien)->nama }}</strong>!
                    Masukkan PIN untuk mengakses riwayat Anda.
                </p>
            </div>

            <div class="card-user bg-white p-4">
                @if (session('error'))
                    <div class="alert alert-danger d-flex align-items-center gap-2 mb-3" style="border-radius:10px;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        {{ session('error') }}
                    </div>
                @endif
                <form action="{{ route('user.riwayat.verifikasi') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-lock me-1 text-primary"></i>
                            PIN (4–6 digit)
                        </label>
                        <div class="input-group">
                            <input type="password" name="pin" id="pin-input"
                                class="form-control @error('pin') is-invalid @enderror" placeholder="Masukkan PIN"
                                maxlength="6" inputmode="numeric" autofocus>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePin()">
                                <i class="bi bi-eye" id="pin-icon"></i>
                            </button>
                        </div>
                        @error('pin')
                            <div class="text-danger mt-1" style="font-size:0.82rem;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-utama w-100 mb-3">
                        <i class="bi bi-unlock me-2"></i>Masuk ke Riwayat
                    </button>

                    <a href="{{ route('user.riwayat.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </form>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('user.riwayat.reset-pin') }}" class="text-muted" style="font-size:0.85rem;">
                    <i class="bi bi-key me-1"></i>Lupa PIN? Reset di sini
                </a>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePin() {
            const input = document.getElementById('pin-input');
            const icon = document.getElementById('pin-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
@endpush
