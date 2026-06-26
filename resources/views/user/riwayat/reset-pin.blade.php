@extends('layouts.user')

@section('title', 'Reset PIN')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">

        {{-- Peringatan --}}
        <div class="alert alert-danger d-flex gap-2 mb-4" style="border-radius:14px;">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1" style="font-size:1.2rem;"></i>
            <div>
                <strong>Peringatan!</strong><br>
                Reset PIN akan <strong>menghapus seluruh riwayat diagnosa</strong> yang terkait
                dengan nomor HP Anda secara permanen. Tindakan ini tidak dapat dibatalkan.
            </div>
        </div>

        <div class="card-user bg-white p-4">
            <div class="text-center mb-4">
                <div style="
                    width:56px;height:56px;border-radius:16px;margin:0 auto 0.75rem;
                    background:rgba(220,53,69,0.1);
                    display:flex;align-items:center;justify-content:center;
                    font-size:1.5rem;color:#dc3545;
                ">
                    <i class="bi bi-key-fill"></i>
                </div>
                <h5 class="fw-bold mb-0">Reset PIN</h5>
            </div>

            <form action="{{ route('user.riwayat.reset-pin.proses') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nomor HP</label>
                    <input type="text"
                           name="no_hp"
                           class="form-control @error('no_hp') is-invalid @enderror"
                           value="{{ old('no_hp') }}"
                           placeholder="Nomor HP yang terdaftar"
                           inputmode="numeric"
                           maxlength="15">
                    @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">PIN Baru (4–6 digit)</label>
                    <input type="password"
                           name="pin_baru"
                           class="form-control @error('pin_baru') is-invalid @enderror"
                           placeholder="Buat PIN baru"
                           maxlength="6"
                           inputmode="numeric">
                    @error('pin_baru')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Konfirmasi PIN Baru</label>
                    <input type="password"
                           name="konfirmasi"
                           class="form-control @error('konfirmasi') is-invalid @enderror"
                           placeholder="Ulangi PIN baru"
                           maxlength="6"
                           inputmode="numeric">
                    @error('konfirmasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Checkbox konfirmasi --}}
                <div class="mb-4 p-3 bg-light rounded-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               name="setuju" id="setuju" value="1">
                        <label class="form-check-label" for="setuju"
                               style="font-size:0.88rem;">
                            Saya memahami bahwa seluruh riwayat diagnosa saya akan
                            <strong>dihapus permanen</strong> dan tidak dapat dipulihkan.
                        </label>
                    </div>
                    @error('setuju')
                        <div class="text-danger mt-1" style="font-size:0.82rem;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-danger w-100 mb-2"
                        style="border-radius:10px;font-weight:600;">
                    <i class="bi bi-trash me-2"></i>Reset PIN & Hapus Riwayat
                </button>

                <a href="{{ route('user.riwayat.index') }}"
                   class="btn btn-outline-secondary w-100" style="border-radius:10px;">
                    <i class="bi bi-arrow-left me-1"></i>Batal
                </a>
            </form>
        </div>

    </div>
</div>
@endsection