@extends('layouts.user')

@section('title', 'Data Diri')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">

        {{-- Header --}}
        <div class="text-center mb-4">
            <div style="
                width:64px;height:64px;border-radius:18px;margin:0 auto 1rem;
                background:linear-gradient(135deg,#2E86AB,#57CC99);
                display:flex;align-items:center;justify-content:center;
                font-size:1.8rem;color:white;
            ">
                <i class="bi bi-person-fill"></i>
            </div>
            <h4 class="fw-bold mb-1">Data Diri</h4>
            <p class="text-muted" style="font-size:0.9rem;">
                Masukkan data diri untuk melanjutkan diagnosa.
                PIN digunakan untuk mengakses riwayat diagnosa Anda.
            </p>
        </div>

        <div class="card-user bg-white p-4">

            <form action="{{ route('user.diagnosa.proses-form') }}" method="POST"
                  id="form-data-diri">
                @csrf

                {{-- Nama --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-person me-1 text-primary"></i>
                        Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           name="nama"
                           class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama') }}"
                           placeholder="Masukkan nama lengkap Anda"
                           autocomplete="name">
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nomor HP --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-phone me-1 text-primary"></i>
                        Nomor HP <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           name="no_hp"
                           class="form-control @error('no_hp') is-invalid @enderror"
                           value="{{ old('no_hp') }}"
                           placeholder="Contoh: 08123456789"
                           maxlength="15"
                           inputmode="numeric"
                           id="no_hp">
                    @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text" id="status-hp"></div>
                </div>

                {{-- PIN --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-lock me-1 text-primary"></i>
                        PIN <span class="text-danger">*</span>
                        <span class="text-muted fw-normal">(4–6 digit)</span>
                    </label>
                    <div class="input-group">
                        <input type="password"
                               name="pin"
                               id="pin"
                               class="form-control @error('pin') is-invalid @enderror"
                               placeholder="Masukkan PIN Anda"
                               maxlength="6"
                               inputmode="numeric">
                        <button type="button" class="btn btn-outline-secondary"
                                onclick="togglePin('pin','icon-pin')">
                            <i class="bi bi-eye" id="icon-pin"></i>
                        </button>
                    </div>
                    @error('pin')
                        <div class="text-danger mt-1" style="font-size:0.82rem;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Konfirmasi PIN (hanya untuk pengguna baru) --}}
                <div class="mb-4" id="wrap-konfirmasi" style="display:none;">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-lock-fill me-1 text-success"></i>
                        Konfirmasi PIN <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="password"
                               name="pin_konfirmasi"
                               id="pin_konfirmasi"
                               class="form-control @error('pin_konfirmasi') is-invalid @enderror"
                               placeholder="Ulangi PIN Anda"
                               maxlength="6"
                               inputmode="numeric">
                        <button type="button" class="btn btn-outline-secondary"
                                onclick="togglePin('pin_konfirmasi','icon-konfirmasi')">
                            <i class="bi bi-eye" id="icon-konfirmasi"></i>
                        </button>
                    </div>
                    @error('pin_konfirmasi')
                        <div class="text-danger mt-1" style="font-size:0.82rem;">{{ $message }}</div>
                    @enderror
                    <div class="form-text text-success">
                        <i class="bi bi-info-circle me-1"></i>
                        Nomor HP belum terdaftar. PIN akan dibuat untuk akun baru Anda.
                    </div>
                </div>

                <button type="submit" class="btn btn-utama w-100">
                    <i class="bi bi-arrow-right-circle me-2"></i>
                    Lanjut ke Pilih Gejala
                </button>
            </form>

        </div>

        {{-- Info PIN --}}
        <div class="mt-3 p-3 rounded" style="background:rgba(46,134,171,0.06);border-radius:12px!important;">
            <p class="mb-1 fw-semibold" style="font-size:0.85rem;color:#2E86AB;">
                <i class="bi bi-shield-lock me-1"></i> Tentang PIN
            </p>
            <ul class="mb-0 text-muted ps-3" style="font-size:0.82rem;">
                <li>PIN digunakan untuk mengakses riwayat diagnosa Anda.</li>
                <li>Jika nomor HP sudah terdaftar, masukkan PIN yang sama.</li>
                <li>PIN salah 3 kali berturut-turut akan dikunci 5 menit.</li>
                <li>Lupa PIN? Anda bisa reset PIN namun riwayat akan terhapus.</li>
            </ul>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePin(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    // Cek apakah HP sudah terdaftar (live check via fetch)
    const hpInput       = document.getElementById('no_hp');
    const statusHp      = document.getElementById('status-hp');
    const wrapKonfirmasi = document.getElementById('wrap-konfirmasi');
    let   debounceTimer;

    hpInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const hp = this.value.trim();

        if (hp.length < 9) {
            statusHp.textContent = '';
            wrapKonfirmasi.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(async () => {
            try {
                const res  = await fetch(`/diagnosa/cek-hp?no_hp=${encodeURIComponent(hp)}`);
                const data = await res.json();

                if (data.terdaftar) {
                    statusHp.innerHTML =
                        '<span class="text-primary"><i class="bi bi-check-circle me-1"></i>' +
                        'Nomor HP terdaftar. Masukkan PIN Anda.</span>';
                    wrapKonfirmasi.style.display = 'none';
                } else {
                    statusHp.innerHTML =
                        '<span class="text-success"><i class="bi bi-plus-circle me-1"></i>' +
                        'Nomor HP baru. Buat PIN untuk akun Anda.</span>';
                    wrapKonfirmasi.style.display = 'block';
                }
            } catch {
                statusHp.textContent = '';
            }
        }, 500);
    });

    // Jika ada error validasi pin_konfirmasi, tampilkan wrap
    @error('pin_konfirmasi')
        wrapKonfirmasi.style.display = 'block';
    @enderror
</script>
@endpush