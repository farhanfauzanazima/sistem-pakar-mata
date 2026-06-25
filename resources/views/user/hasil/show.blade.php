@extends('layouts.user')

@section('title', 'Hasil Diagnosa')

@push('styles')
<style>
    .hasil-card {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }

    .hasil-header {
        background: linear-gradient(135deg, #2E86AB, #57CC99);
        padding: 2rem;
        color: white;
        text-align: center;
    }

    .cf-meter {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        margin: 0 auto 1rem;
        position: relative;
        background: rgba(255,255,255,0.15);
        border: 6px solid rgba(255,255,255,0.3);
    }

    .cf-meter .cf-angka {
        font-size: 2.4rem;
        font-weight: 800;
        line-height: 1;
        color: white;
    }

    .cf-meter .cf-satuan {
        font-size: 0.85rem;
        opacity: 0.85;
        color: white;
    }

    .detail-gejala-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.65rem 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.88rem;
    }

    .detail-gejala-item:last-child {
        border-bottom: none;
    }

    .badge-frekuensi {
        font-size: 0.75rem;
        padding: 0.3rem 0.65rem;
        border-radius: 20px;
    }

    .info-box {
        background: rgba(46,134,171,0.06);
        border-left: 4px solid #2E86AB;
        border-radius: 0 10px 10px 0;
        padding: 1rem 1.25rem;
        font-size: 0.88rem;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        {{-- Kartu Hasil Utama --}}
        <div class="hasil-card mb-4">

            {{-- Header --}}
            <div class="hasil-header">
                <div class="cf-meter">
                    <span class="cf-angka">{{ $hasil->cf_persen }}%</span>
                    <span class="cf-satuan">Keyakinan</span>
                </div>
                <h3 class="fw-bold mb-1">{{ $hasil->penyakit->nama }}</h3>
                <p class="mb-0" style="opacity:0.85;font-size:0.9rem;">
                    Tingkat Keyakinan:
                    <strong>{{ $label }}</strong>
                    (CF = {{ number_format($hasil->cf_hasil, 4) }})
                </p>
            </div>

            {{-- Body --}}
            <div class="bg-white p-4">

                {{-- Info Pasien --}}
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                    <div style="
                        width:48px;height:48px;border-radius:14px;flex-shrink:0;
                        background:linear-gradient(135deg,#2E86AB,#57CC99);
                        display:flex;align-items:center;justify-content:center;
                        font-size:1.3rem;color:white;font-weight:700;
                    ">
                        {{ strtoupper(substr($hasil->pasien->nama, 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $hasil->pasien->nama }}</div>
                        <div class="text-muted" style="font-size:0.82rem;">
                            <i class="bi bi-phone me-1"></i>{{ $hasil->pasien->no_hp }}
                            &nbsp;·&nbsp;
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $hasil->created_at->format('d M Y, H:i') }} WIB
                        </div>
                    </div>
                </div>

                {{-- Progress Bar CF --}}
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-semibold" style="font-size:0.88rem;">
                            Nilai Certainty Factor
                        </span>
                        <span class="fw-bold text-{{ $warna }}">
                            {{ $hasil->cf_persen }}%
                        </span>
                    </div>
                    <div class="progress" style="height:12px;border-radius:6px;">
                        <div class="progress-bar bg-{{ $warna }} progress-bar-striped progress-bar-animated"
                             style="width:{{ $hasil->cf_persen }}%;border-radius:6px;">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">0% (Tidak Yakin)</small>
                        <small class="text-muted">100% (Sangat Yakin)</small>
                    </div>
                </div>

                {{-- Deskripsi Penyakit --}}
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
                <div class="info-box mb-4">
                    <p class="fw-semibold mb-2">
                        <i class="bi bi-heart-pulse me-2 text-primary"></i>
                        Rekomendasi Penanganan
                    </p>
                    <p class="mb-0 text-muted" style="line-height:1.7;">
                        {{ $hasil->penyakit->solusi }}
                    </p>
                </div>

                {{-- Gejala yang Dipilih --}}
                @if($hasil->detailDiagnosa->count() > 0)
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-clipboard2-check text-success me-2"></i>
                        Gejala yang Teridentifikasi ({{ $hasil->detailDiagnosa->count() }})
                    </h6>
                    <div>
                        @foreach($hasil->detailDiagnosa as $detail)
                        @php
                            $warnaFrek = match($detail->frekuensi) {
                                'sangat_sering' => 'danger',
                                'sering'        => 'warning',
                                'jarang'        => 'info',
                                default         => 'secondary',
                            };
                            $labelFrek = match($detail->frekuensi) {
                                'sangat_sering' => 'Sangat Sering',
                                'sering'        => 'Sering',
                                'jarang'        => 'Jarang',
                                default         => 'Tidak Pernah',
                            };
                        @endphp
                        <div class="detail-gejala-item">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-subtle text-success"
                                      style="font-size:0.7rem;">
                                    {{ $detail->gejala->kode }}
                                </span>
                                <span>{{ $detail->gejala->nama }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge badge-frekuensi bg-{{ $warnaFrek }}-subtle text-{{ $warnaFrek }}">
                                    {{ $labelFrek }}
                                </span>
                                <span class="text-muted" style="font-size:0.78rem;width:60px;text-align:right;">
                                    CF={{ $detail->cf_user }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Disclaimer --}}
                <div class="alert alert-warning d-flex gap-2 mb-4" style="border-radius:12px;">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
                    <div style="font-size:0.85rem;">
                        <strong>Perhatian:</strong> Hasil diagnosa ini merupakan perkiraan awal
                        berbasis sistem pakar dan <strong>bukan pengganti diagnosis dokter</strong>.
                        Segera konsultasikan dengan dokter spesialis mata untuk penanganan lebih lanjut.
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('user.hasil.pdf', $hasil->id) }}"
                       class="btn btn-utama">
                        <i class="bi bi-file-pdf me-2"></i>Download PDF
                    </a>
                    <a href="{{ route('user.diagnosa.form') }}"
                       class="btn btn-outline-primary" style="border-radius:10px;">
                        <i class="bi bi-arrow-repeat me-2"></i>Diagnosa Ulang
                    </a>
                    <a href="{{ route('beranda') }}"
                       class="btn btn-outline-secondary" style="border-radius:10px;">
                        <i class="bi bi-house me-2"></i>Beranda
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection