@extends('layouts.user')

@section('title', 'Daftar Riwayat')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Riwayat Diagnosa</h4>
        <p class="text-muted mb-0" style="font-size:0.88rem;">
            <i class="bi bi-person me-1"></i>{{ $pasien->nama }}
            &nbsp;·&nbsp;
            <i class="bi bi-phone me-1"></i>{{ $pasien->no_hp }}
        </p>
    </div>
    <a href="{{ route('user.diagnosa.form') }}" class="btn btn-utama btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Diagnosa Baru
    </a>
</div>

@if($riwayat->count() > 0)
<div class="row g-3">
    @foreach($riwayat as $item)
    @php
        $warna = \App\Services\CertaintyFactorService::warnaKeyakinan($item->cf_hasil);
        $label = \App\Services\CertaintyFactorService::labelKeyakinan($item->cf_hasil);
    @endphp
    <div class="col-md-6">
        <div class="card-user bg-white p-3 h-100">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div>
                    <h6 class="fw-bold mb-0">{{ $item->penyakit->nama }}</h6>
                    <small class="text-muted">
                        {{ $item->created_at->format('d M Y, H:i') }} WIB
                    </small>
                </div>
                <span class="badge bg-{{ $warna }}-subtle text-{{ $warna }} px-2 py-1"
                      style="border-radius:8px;font-size:0.75rem;">
                    {{ $item->cf_persen }}%
                </span>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <small class="text-muted">Tingkat Keyakinan</small>
                    <small class="fw-semibold text-{{ $warna }}">{{ $label }}</small>
                </div>
                <div class="progress" style="height:6px;border-radius:3px;">
                    <div class="progress-bar bg-{{ $warna }}"
                         style="width:{{ $item->cf_persen }}%;border-radius:3px;">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('user.riwayat.detail', $item->id) }}"
                   class="btn btn-sm btn-outline-primary flex-grow-1"
                   style="border-radius:8px;">
                    <i class="bi bi-eye me-1"></i>Detail
                </a>
                <a href="{{ route('user.riwayat.pdf', $item->id) }}"
                   class="btn btn-sm btn-outline-secondary"
                   style="border-radius:8px;">
                    <i class="bi bi-file-pdf"></i>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($riwayat->hasPages())
<div class="mt-4">
    {{ $riwayat->links() }}
</div>
@endif

@else
<div class="text-center py-5">
    <i class="bi bi-inbox" style="font-size:3rem;color:#dee2e6;"></i>
    <h5 class="fw-bold mt-3 text-muted">Belum Ada Riwayat</h5>
    <p class="text-muted mb-4">Anda belum pernah melakukan diagnosa.</p>
    <a href="{{ route('user.diagnosa.form') }}" class="btn btn-utama">
        <i class="bi bi-search-heart me-2"></i>Mulai Diagnosa
    </a>
</div>
@endif

<div class="text-center mt-4">
    <a href="{{ route('user.riwayat.reset-pin') }}"
       class="text-muted" style="font-size:0.82rem;">
        <i class="bi bi-key me-1"></i>Reset PIN & Hapus Riwayat
    </a>
</div>

@endsection