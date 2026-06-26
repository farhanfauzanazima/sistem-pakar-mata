@extends('layouts.user')

@section('title', 'Tentang Sistem')

@section('content')

{{-- Header --}}
<div class="text-center py-4 mb-4">
    <div style="
        width:72px;height:72px;border-radius:20px;margin:0 auto 1rem;
        background:linear-gradient(135deg,#2E86AB,#57CC99);
        display:flex;align-items:center;justify-content:center;
        font-size:2rem;color:white;
    ">
        <i class="bi bi-info-circle-fill"></i>
    </div>
    <h2 class="fw-bold mb-2" style="color:#1a2e3b;">Tentang Sistem</h2>
    <p class="text-muted" style="max-width:560px;margin:0 auto;line-height:1.7;">
        Sistem Pakar Diagnosis Penyakit Mata adalah aplikasi berbasis web yang
        menggunakan kecerdasan buatan untuk membantu diagnosis awal penyakit mata.
    </p>
</div>

{{-- Statistik Sistem --}}
<div class="row g-3 mb-5">
    @foreach([
        ['icon'=>'bi-virus',           'angka'=>$penyakit->count(), 'label'=>'Penyakit Mata',    'warna'=>'#2E86AB'],
        ['icon'=>'bi-clipboard2-pulse','angka'=>$totalGejala,        'label'=>'Gejala Tersedia',  'warna'=>'#57CC99'],
        ['icon'=>'bi-diagram-3',       'angka'=>$totalAturan,        'label'=>'Aturan CF',        'warna'=>'#f0a500'],
        ['icon'=>'bi-graph-up',        'angka'=>'92%',               'label'=>'Akurasi Sistem',   'warna'=>'#e05c5c'],
    ] as $stat)
    <div class="col-6 col-md-3">
        <div class="card-user bg-white p-3 text-center">
            <div style="
                width:48px;height:48px;border-radius:14px;margin:0 auto 0.75rem;
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

{{-- Metode --}}
<div class="row g-3 mb-5">
    <div class="col-12">
        <h4 class="fw-bold mb-3" style="color:#1a2e3b;">
            <i class="bi bi-cpu me-2" style="color:#2E86AB;"></i>
            Metode yang Digunakan
        </h4>
    </div>

    <div class="col-md-6">
        <div class="card-user bg-white p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="
                    width:48px;height:48px;border-radius:14px;flex-shrink:0;
                    background:rgba(46,134,171,0.1);
                    display:flex;align-items:center;justify-content:center;
                    font-size:1.4rem;color:#2E86AB;
                ">
                    <i class="bi bi-percent"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Certainty Factor (CF)</h6>
                    <small class="text-muted">Metode Ketidakpastian</small>
                </div>
            </div>
            <p class="text-muted mb-3" style="font-size:0.88rem;line-height:1.7;">
                Certainty Factor adalah metode untuk merepresentasikan tingkat kepastian
                dalam sistem pakar. Nilai CF berkisar antara -1 (tidak yakin sama sekali)
                hingga 1 (sangat yakin).
            </p>
            <div class="p-3 rounded-3" style="background:#f8f9fa;font-size:0.82rem;">
                <p class="fw-semibold mb-2">Rumus Kombinasi CF:</p>
                <code style="color:#2E86AB;">
                    CF<sub>combine</sub> = CF<sub>1</sub> + CF<sub>2</sub> × (1 - CF<sub>1</sub>)
                </code>
                <hr class="my-2">
                <p class="mb-1 fw-semibold">Skala Frekuensi Gejala:</p>
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex justify-content-between">
                        <span><i class="bi bi-circle-fill text-danger me-1" style="font-size:0.5rem;"></i>Sangat Sering</span>
                        <span class="fw-semibold">CF User = 1.0</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><i class="bi bi-circle-fill text-warning me-1" style="font-size:0.5rem;"></i>Sering</span>
                        <span class="fw-semibold">CF User = 0.6</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><i class="bi bi-circle-fill text-info me-1" style="font-size:0.5rem;"></i>Jarang</span>
                        <span class="fw-semibold">CF User = 0.3</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><i class="bi bi-circle-fill text-secondary me-1" style="font-size:0.5rem;"></i>Tidak Pernah</span>
                        <span class="fw-semibold">CF User = 0.0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card-user bg-white p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="
                    width:48px;height:48px;border-radius:14px;flex-shrink:0;
                    background:rgba(87,204,153,0.1);
                    display:flex;align-items:center;justify-content:center;
                    font-size:1.4rem;color:#57CC99;
                ">
                    <i class="bi bi-diagram-2"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Forward Chaining</h6>
                    <small class="text-muted">Metode Inferensi</small>
                </div>
            </div>
            <p class="text-muted mb-3" style="font-size:0.88rem;line-height:1.7;">
                Forward Chaining adalah metode inferensi yang dimulai dari fakta
                (gejala yang dialami pasien) menuju ke kesimpulan (diagnosis penyakit).
                Sistem menelusuri aturan IF-THEN dari gejala ke penyakit.
            </p>
            <div class="p-3 rounded-3" style="background:#f8f9fa;font-size:0.82rem;">
                <p class="fw-semibold mb-2">Alur Kerja:</p>
                @foreach([
                    ['no'=>'1','teks'=>'User memilih gejala yang dialami'],
                    ['no'=>'2','teks'=>'Sistem mencocokkan gejala dengan aturan IF-THEN'],
                    ['no'=>'3','teks'=>'CF per gejala = CF User × CF Pakar'],
                    ['no'=>'4','teks'=>'CF digabungkan dengan rumus kombinasi'],
                    ['no'=>'5','teks'=>'Penyakit dengan CF tertinggi sebagai hasil'],
                ] as $step)
                <div class="d-flex align-items-start gap-2 mb-1">
                    <div style="
                        width:20px;height:20px;border-radius:50%;flex-shrink:0;
                        background:#57CC99;color:white;
                        display:flex;align-items:center;justify-content:center;
                        font-size:0.65rem;font-weight:700;
                    ">{{ $step['no'] }}</div>
                    <span>{{ $step['teks'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Daftar Penyakit --}}
<div class="mb-5">
    <h4 class="fw-bold mb-3" style="color:#1a2e3b;">
        <i class="bi bi-virus me-2" style="color:#e05c5c;"></i>
        Penyakit yang Dapat Didiagnosis
    </h4>
    <div class="row g-3">
        @foreach($penyakit as $p)
        <div class="col-md-6">
            <div class="card-user bg-white p-4">
                <div class="d-flex align-items-start gap-3">

                    {{-- Ikon Kode --}}
                    <div style="
                        width:44px;height:44px;border-radius:12px;flex-shrink:0;
                        background:rgba(46,134,171,0.1);
                        display:flex;align-items:center;justify-content:center;
                        font-weight:700;color:#2E86AB;font-size:0.85rem;
                    ">
                        {{ $p->kode }}
                    </div>

                    <div class="flex-grow-1" x-data="{ buka: false }">

                        {{-- Header: Nama + Badge + Tombol Panah --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0">{{ $p->nama }}</h6>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary-subtle text-primary"
                                      style="font-size:0.72rem;">
                                    {{ $p->aturan_count }} gejala
                                </span>
                                {{-- Tombol Panah --}}
                                <button
                                    type="button"
                                    @click="buka = !buka"
                                    class="btn btn-sm btn-outline-secondary p-1"
                                    style="width:28px;height:28px;border-radius:8px;
                                           display:flex;align-items:center;
                                           justify-content:center;flex-shrink:0;"
                                    :title="buka ? 'Tutup' : 'Baca selengkapnya'">
                                    <i class="bi"
                                       :class="buka ? 'bi-chevron-up' : 'bi-chevron-down'"
                                       style="font-size:0.75rem;"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Deskripsi Singkat (selalu tampil) --}}
                        <p class="text-muted mb-0"
                           style="font-size:0.85rem;line-height:1.6;"
                           x-show="!buka">
                            {{ Str::limit($p->deskripsi, 100) }}
                        </p>

                        {{-- Deskripsi Lengkap (tampil saat panah diklik) --}}
                        <div x-show="buka"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0">

                            <p class="text-muted mb-2"
                               style="font-size:0.85rem;line-height:1.6;">
                                {{ $p->deskripsi }}
                            </p>

                            {{-- Solusi --}}
                            <div class="p-2 rounded-2 mt-2"
                                 style="background:rgba(87,204,153,0.08);
                                        border-left:3px solid #57CC99;">
                                <p class="fw-semibold mb-1"
                                   style="font-size:0.78rem;color:#57CC99;">
                                    <i class="bi bi-heart-pulse me-1"></i>
                                    Rekomendasi Penanganan
                                </p>
                                <p class="text-muted mb-0"
                                   style="font-size:0.82rem;line-height:1.6;">
                                    {{ $p->solusi }}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Referensi Jurnal --}}
<div class="mb-5">
    <h4 class="fw-bold mb-3" style="color:#1a2e3b;">
        <i class="bi bi-journal-bookmark me-2" style="color:#6f42c1;"></i>
        Referensi Penelitian
    </h4>
    <div class="card-user bg-white p-4">
        <div class="d-flex align-items-center gap-3 flex-wrap">

            {{-- Ikon --}}
            <div style="
                width:44px;height:44px;border-radius:12px;flex-shrink:0;
                background:rgba(111,66,193,0.1);
                display:flex;align-items:center;justify-content:center;
                font-size:1.2rem;color:#6f42c1;
            ">
                <i class="bi bi-file-earmark-text"></i>
            </div>

            {{-- Teks Jurnal --}}
            <div class="flex-grow-1" style="min-width:200px;">
                <h6 class="fw-bold mb-1" style="font-size:0.9rem;">
                    Sistem Pakar untuk Diagnosis Penyakit Mata dengan
                    Certainty Factor dan Forward Chaining
                </h6>
                <p class="text-muted mb-0" style="font-size:0.82rem;line-height:1.5;">
                    <i class="bi bi-person me-1"></i>
                    Joni Warta, Hendarman Lubis &nbsp;·&nbsp;
                    <em>JISICOM</em> Vol.9 No.2 (2025) &nbsp;·&nbsp;
                    DOI: 10.52362/jisicom.v9i2.2218
                </p>
            </div>

            {{-- Tombol sejajar di kanan --}}
            <div class="d-flex gap-2 flex-shrink-0 flex-wrap">

                @if($pdfJurnalAda)
                <a href="{{ asset('storage/jurnal/jurnal-sistem-pakar-mata.pdf') }}"
                   target="_blank"
                   download="Jurnal-Sistem-Pakar-Mata.pdf"
                   class="btn btn-sm btn-utama">
                    <i class="bi bi-file-pdf me-1"></i>Download PDF
                </a>
                @else
                <button class="btn btn-sm btn-outline-secondary" disabled>
                    <i class="bi bi-file-pdf me-1"></i>PDF
                </button>
                @endif

                @if($urlWebsiteJurnal !== '#')
                <a href="{{ $urlWebsiteJurnal }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="btn btn-sm btn-outline-primary"
                   style="border-radius:8px;">
                    <i class="bi bi-box-arrow-up-right me-1"></i>Website Jurnal
                </a>
                @endif

            </div>
        </div>
    </div>
</div>

{{-- Info Pengembang --}}
<div class="mb-2">
    <h4 class="fw-bold mb-3" style="color:#1a2e3b;">
        <i class="bi bi-code-slash me-2" style="color:#57CC99;"></i>
        Informasi Pengembang
    </h4>
    <div class="card-user bg-white p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width:130px;font-size:0.85rem;">
                            Framework
                        </td>
                        <td class="fw-semibold" style="font-size:0.85rem;">
                            Laravel 12
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted" style="font-size:0.85rem;">Database</td>
                        <td class="fw-semibold" style="font-size:0.85rem;">MySQL 8.0</td>
                    </tr>
                    <tr>
                        <td class="text-muted" style="font-size:0.85rem;">Bahasa</td>
                        <td class="fw-semibold" style="font-size:0.85rem;">PHP 8.3</td>
                    </tr>
                    <tr>
                        <td class="text-muted" style="font-size:0.85rem;">Frontend</td>
                        <td class="fw-semibold" style="font-size:0.85rem;">
                            Bootstrap 5 + Alpine.js
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width:130px;font-size:0.85rem;">
                            Generate PDF
                        </td>
                        <td class="fw-semibold" style="font-size:0.85rem;">DomPDF</td>
                    </tr>
                    <tr>
                        <td class="text-muted" style="font-size:0.85rem;">Ekspor Excel</td>
                        <td class="fw-semibold" style="font-size:0.85rem;">
                            Maatwebsite Excel
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted" style="font-size:0.85rem;">Metode</td>
                        <td class="fw-semibold" style="font-size:0.85rem;">
                            CF + Forward Chaining
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted" style="font-size:0.85rem;">Versi</td>
                        <td class="fw-semibold" style="font-size:0.85rem;">1.0.0</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection