@extends('layouts.user')

@section('title', 'Detail Riwayat')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        {{-- Tombol Kembali --}}
        <a href="{{ route('user.riwayat.daftar') }}"
           class="btn btn-outline-secondary btn-sm mb-3" style="border-radius:8px;">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
        </a>

        {{-- Reuse view hasil dengan data dari riwayat --}}
        <div class="card-user bg-white overflow-hidden mb-4">

            {{-- Header --}}
            <div style="
                background:linear-gradient(135deg,#2E86AB,#57CC99);
                padding:2rem;color:white;text-align:center;
            ">
                <div style="
                    width:120px;height:120px;border-radius:50%;
                    background:rgba(255,255,255,0.15);
                    border:6px solid rgba(255,255,255,0.3);
                    display:flex;align-items:center;justify-content:center;
                    flex-direction:column;margin:0 auto 1rem;
                ">
                    <span style="font-size:2.2rem;font-weight:800;line-height:1;">
                        {{ $hasil->cf_persen }}%
                    </span>
                    <span style="font-size:0.8rem;opacity:0.85;">Keyakinan</span>
                </div>
                <h3 class="fw-bold mb-1">{{ $hasil->penyakit->nama }}</h3>
                <p class="mb-0" style="opacity:0.85;font-size:0.88rem;">
                    {{ $label }} · CF = {{ number_format($hasil->cf_hasil, 4) }}
                </p>
            </div>

            <div class="p-4">

                {{-- Info Diagnosa --}}
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                    <div style="
                        width:44px;height:44px;border-radius:12px;flex-shrink:0;
                        background:linear-gradient(135deg,#2E86AB,#57CC99);
                        display:flex;align-items:center;justify-content:center;
                        font-size:1.2rem;color:white;font-weight:700;
                    ">
                        {{ strtoupper(substr($hasil->pasien->nama, 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $hasil->pasien->nama }}</div>
                        <div class="text-muted" style="font-size:0.82rem;">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $hasil->created_at->format('d M Y, H:i') }} WIB
                            &nbsp;·&nbsp; ID #{{ str_pad($hasil->id, 6, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>
                </div>

                {{-- Progress Bar CF --}}
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-semibold" style="font-size:0.88rem;">
                            Nilai Certainty Factor
                        </span>
                        <span class="fw-bold text-{{ $warna }}">{{ $hasil->cf_persen }}%</span>
                    </div>
                    <div class="progress" style="height:12px;border-radius:6px;">
                        <div class="progress-bar bg-{{ $warna }} progress-bar-striped"
                             style="width:{{ $hasil->cf_persen }}%;border-radius:6px;">
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <h6 class="fw-bold mb-2">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        Tentang {{ $hasil->penyakit->nama }}
                    </h6>
                    <p class="text-muted mb-0" style="font-size:0.9rem;line-height:1.7;">
                        {{ $hasil->penyakit->deskripsi }}
                    </p>
                </div>

                {{-- Solusi --}}
                <div class="mb-4 p-3"
                     style="background:rgba(46,134,171,0.06);
                            border-left:4px solid #2E86AB;
                            border-radius:0 10px 10px 0;">
                    <p class="fw-semibold mb-2">
                        <i class="bi bi-heart-pulse me-2 text-primary"></i>
                        Rekomendasi Penanganan
                    </p>
                    <p class="mb-0 text-muted" style="line-height:1.7;font-size:0.9rem;">
                        {{ $hasil->penyakit->solusi }}
                    </p>
                </div>

                {{-- Detail Gejala --}}
                @if($hasil->detailDiagnosa->count() > 0)
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-clipboard2-check text-success me-2"></i>
                        Gejala yang Teridentifikasi ({{ $hasil->detailDiagnosa->count() }})
                    </h6>
                    @foreach($hasil->detailDiagnosa as $detail)
                    @php
                        $wFrek = match($detail->frekuensi) {
                            'sangat_sering' => 'danger',
                            'sering'        => 'warning',
                            'jarang'        => 'info',
                            default         => 'secondary',
                        };
                        $lFrek = match($detail->frekuensi) {
                            'sangat_sering' => 'Sangat Sering',
                            'sering'        => 'Sering',
                            'jarang'        => 'Jarang',
                            default         => 'Tidak Pernah',
                        };
                    @endphp
                    <div style="
                        display:flex;align-items:center;justify-content:space-between;
                        padding:0.6rem 0;border-bottom:1px solid #f0f0f0;
                        font-size:0.88rem;
                    ">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success-subtle text-success"
                                  style="font-size:0.7rem;">
                                {{ $detail->gejala->kode }}
                            </span>
                            {{ $detail->gejala->nama }}
                        </div>
                        <span class="badge bg-{{ $wFrek }}-subtle text-{{ $wFrek }}"
                              style="font-size:0.75rem;border-radius:20px;">
                            {{ $lFrek }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Disclaimer --}}
                <div class="alert alert-warning d-flex gap-2 mb-4" style="border-radius:12px;">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
                    <div style="font-size:0.85rem;">
                        <strong>Perhatian:</strong> Hasil ini merupakan perkiraan awal dan
                        <strong>bukan pengganti diagnosis dokter</strong>.
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('user.riwayat.pdf', $hasil->id) }}"
                       class="btn btn-utama">
                        <i class="bi bi-file-pdf me-2"></i>Download PDF
                    </a>
                    <a href="{{ route('user.diagnosa.form') }}"
                       class="btn btn-outline-primary" style="border-radius:10px;">
                        <i class="bi bi-arrow-repeat me-2"></i>Diagnosa Ulang
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection