@extends('layouts.user')

@section('title', 'Riwayat Diagnosa')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">

        <div class="text-center mb-4">
            <div style="
                width:64px;height:64px;border-radius:18px;margin:0 auto 1rem;
                background:linear-gradient(135deg,#2E86AB,#57CC99);
                display:flex;align-items:center;justify-content:center;
                font-size:1.8rem;color:white;
            ">
                <i class="bi bi-clock-history"></i>
            </div>
            <h4 class="fw-bold mb-1">Riwayat Diagnosa</h4>
            <p class="text-muted" style="font-size:0.9rem;">
                Masukkan nomor HP yang pernah digunakan untuk diagnosa.
            </p>
        </div>

        <div class="card-user bg-white p-4">
            <form action="{{ route('user.riwayat.cek-hp') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-phone me-1 text-primary"></i>
                        Nomor HP
                    </label>
                    <input type="text"
                           name="no_hp"
                           class="form-control @error('no_hp') is-invalid @enderror"
                           value="{{ old('no_hp') }}"
                           placeholder="Contoh: 08123456789"
                           inputmode="numeric"
                           maxlength="15">
                    @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-utama w-100">
                    <i class="bi bi-search me-2"></i>Cari Riwayat
                </button>
            </form>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('user.riwayat.reset-pin') }}"
               class="text-muted" style="font-size:0.85rem;">
                <i class="bi bi-key me-1"></i>Lupa PIN? Reset di sini
            </a>
        </div>

    </div>
</div>
@endsection