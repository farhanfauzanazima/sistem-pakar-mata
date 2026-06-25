@extends('layouts.user')

@section('title', 'Beranda')

@section('content')

{{-- Hero Section --}}
<div class="row align-items-center py-4 py-md-5 mb-4">
    <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="badge mb-3 px-3 py-2"
             style="background:rgba(46,134,171,0.1);color:#2E86AB;border-radius:20px;font-size:0.8rem;">
            <i class="bi bi-shield-check me-1"></i>
            Sistem Pakar Berbasis AI
        </div>
        <h1 class="fw-bold mb-3" style="font-size:2.2rem;line-height:1.25;color:#1a2e3b;">
            Diagnosis Penyakit Mata
            <span style="color:#2E86AB;">Lebih Cepat</span> &
            <span style="color:#57CC99;">Lebih Mudah</span>
        </h1>
        <p class="text-muted mb-4" style="font-size:1rem;line-height:1.7;">
            Sistem pakar berbasis metode <strong>Certainty Factor</strong> dan
            <strong>Forward Chaining</strong> untuk membantu diagnosis awal penyakit mata.
            Cocok sebagai konsultasi awal sebelum bertemu dokter spesialis.
        </p>
        <div class="d-flex flex-wrap gap-3">
            <a href="{{ route('user.diagnosa.form') }}"
               class="btn btn-utama px-4 py-2">
                <i class="bi bi-search-heart me-2"></i>Mulai Diagnosa Sekarang
            </a>
            <a href="{{ route('user.tentang') }}"
               class="btn btn-outline-secondary px-4 py-2" style="border-radius:10px;">
                <i class="bi bi-info-circle me-2"></i>Pelajari Lebih Lanjut
            </a>
        </div>
    </div>
    <div class="col-lg-6 text-center">
        <div style="
            width:100%;max-width:380px;margin:0 auto;
            background:linear-gradient(135deg,rgba(46,134,171,0.08),rgba(87,204,153,0.08));
            border-radius:24px;padding:3rem 2rem;
        ">
            <i class="bi bi-eye-fill" style="font-size:6rem;color:#2E86AB;opacity:0.8;"></i>
            <div class="mt-3">
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    @foreach(['Katarak','Glaukoma','Konjungtivitis','Retinopati Diabetik'] as $p)
                    <span class="badge px-2 py-1"
                          style="background:rgba(46,134,171,0.1);color:#2E86AB;
                                 border-radius:8px;font-size:0.75rem;">
                        {{ $p }}
                    </span>
                    @endforeach
                </div>
                <p class="text-muted mt-2 mb-0" style="font-size:0.8rem;">
                    4 penyakit mata yang dapat didiagnosis
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Statistik --}}
<div class="row g-3 mb-5">
    @foreach([
        ['icon'=>'bi-clipboard2-pulse','angka'=>'12','label'=>'Gejala Tersedia','warna'=>'#2E86AB'],
        ['icon'=>'bi-virus','angka'=>'4','label'=>'Penyakit Mata','warna'=>'#57CC99'],
        ['icon'=>'bi-graph-up','angka'=>'92%','label'=>'Akurasi Sistem','warna'=>'#f0a500'],
        ['icon'=>'bi-clock-history','angka'=>'<1s','label'=>'Waktu Diagnosa','warna'=>'#e05c5c'],
    ] as $stat)
    <div class="col-6 col-md-3">
        <div class="card-user p-3 text-center bg-white">
            <div style="
                width:48px;height:48px;border-radius:12px;margin:0 auto 0.75rem;
                background:{{ $stat['warna'] }}18;
                display:flex;align-items:center;justify-content:center;
                font-size:1.4rem;color:{{ $stat['warna'] }};
            ">
                <i class="bi {{ $stat['icon'] }}"></i>
            </div>
            <h4 class="fw-bold mb-0" style="color:#1a2e3b;">{{ $stat['angka'] }}</h4>
            <p class="text-muted mb-0" style="font-size:0.8rem;">{{ $stat['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Cara Kerja --}}
<div class="mb-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold" style="color:#1a2e3b;">Cara Kerja Sistem</h2>
        <p class="text-muted">Diagnosa penyakit mata dalam 3 langkah mudah</p>
    </div>
    <div class="row g-3">
        @foreach([
            [
                'no'    => '1',
                'icon'  => 'bi-person-fill',
                'judul' => 'Isi Data Diri',
                'desc'  => 'Masukkan nama dan nomor HP beserta PIN untuk menyimpan riwayat diagnosa Anda.',
                'warna' => '#2E86AB',
            ],
            [
                'no'    => '2',
                'icon'  => 'bi-clipboard2-check-fill',
                'judul' => 'Pilih Gejala',
                'desc'  => 'Pilih gejala yang Anda alami beserta seberapa sering gejala tersebut muncul.',
                'warna' => '#57CC99',
            ],
            [
                'no'    => '3',
                'icon'  => 'bi-file-medical-fill',
                'judul' => 'Lihat Hasil',
                'desc'  => 'Sistem menghitung CF dan menampilkan hasil diagnosis beserta rekomendasi penanganan.',
                'warna' => '#f0a500',
            ],
        ] as $step)
        <div class="col-md-4">
            <div class="card-user p-4 h-100 bg-white">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="
                        width:48px;height:48px;border-radius:14px;flex-shrink:0;
                        background:{{ $step['warna'] }}18;
                        display:flex;align-items:center;justify-content:center;
                        font-size:1.4rem;color:{{ $step['warna'] }};
                    ">
                        <i class="bi {{ $step['icon'] }}"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:0.75rem;">
                            Langkah {{ $step['no'] }}
                        </div>
                        <h6 class="fw-bold mb-0">{{ $step['judul'] }}</h6>
                    </div>
                </div>
                <p class="text-muted mb-0" style="font-size:0.88rem;line-height:1.6;">
                    {{ $step['desc'] }}
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- CTA --}}
<div class="text-center py-4 px-3 mb-2"
     style="background:linear-gradient(135deg,rgba(46,134,171,0.08),rgba(87,204,153,0.08));
            border-radius:20px;">
    <h3 class="fw-bold mb-2" style="color:#1a2e3b;">Siap Memulai Diagnosa?</h3>
    <p class="text-muted mb-4">
        Gratis, cepat, dan tidak memerlukan registrasi akun.
    </p>
    <a href="{{ route('user.diagnosa.form') }}" class="btn btn-utama px-5 py-2">
        <i class="bi bi-search-heart me-2"></i>Mulai Diagnosa Sekarang
    </a>
</div>

@endsection