@extends('layouts.admin')

@section('title', 'Detail Diagnosa')
@section('page-title', 'Detail Diagnosa Pasien')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.diagnosa.index') }}">Diagnosa</a>
    </li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-3" style="max-width:900px">

    {{-- Info Utama --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>
                    <i class="bi bi-journal-medical me-2 text-info"></i>
                    Detail Diagnosa
                    <span class="text-muted fw-normal ms-1">
                        #{{ str_pad($hasil->id, 6, '0', STR_PAD_LEFT) }}
                    </span>
                </span>
                <a href="{{ route('admin.diagnosa.index') }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">

                    {{-- Info Pasien --}}
                    <div class="col-md-6">
                        <p class="text-muted fw-semibold mb-2"
                           style="font-size:0.78rem;text-transform:uppercase;">
                            Informasi Pasien
                        </p>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" style="width:100px;">Nama</td>
                                <td class="fw-semibold">{{ $hasil->pasien->nama }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nomor HP</td>
                                <td>{{ $hasil->pasien->no_hp }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal</td>
                                <td>{{ $hasil->created_at->format('d M Y, H:i') }} WIB</td>
                            </tr>
                        </table>
                    </div>

                    {{-- Hasil Diagnosa --}}
                    <div class="col-md-6">
                        <p class="text-muted fw-semibold mb-2"
                           style="font-size:0.78rem;text-transform:uppercase;">
                            Hasil Diagnosa
                        </p>
                        <h5 class="fw-bold mb-1">{{ $hasil->penyakit->nama }}</h5>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="progress flex-grow-1" style="height:10px;border-radius:5px;">
                                <div class="progress-bar bg-{{ $warna }} progress-bar-striped"
                                     style="width:{{ $hasil->cf_persen }}%;border-radius:5px;">
                                </div>
                            </div>
                            <span class="fw-bold text-{{ $warna }}">
                                {{ $hasil->cf_persen }}%
                            </span>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-{{ $warna }}-subtle text-{{ $warna }} px-2 py-1"
                                  style="border-radius:8px;">
                                {{ $label }}
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary px-2 py-1"
                                  style="border-radius:8px;">
                                CF = {{ number_format($hasil->cf_hasil, 4) }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Gejala --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clipboard2-check me-2 text-success"></i>
                Gejala yang Dilaporkan ({{ $hasil->detailDiagnosa->count() }})
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Gejala</th>
                            <th>Frekuensi</th>
                            <th>CF User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hasil->detailDiagnosa as $i => $detail)
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
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <span class="badge bg-success-subtle text-success">
                                    {{ $detail->gejala->kode }}
                                </span>
                            </td>
                            <td>{{ $detail->gejala->nama }}</td>
                            <td>
                                <span class="badge bg-{{ $wFrek }}-subtle text-{{ $wFrek }}"
                                      style="border-radius:20px;">
                                    {{ $lFrek }}
                                </span>
                            </td>
                            <td class="fw-semibold">{{ $detail->cf_user }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Deskripsi & Solusi --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-info-circle me-2 text-primary"></i>
                Tentang {{ $hasil->penyakit->nama }}
            </div>
            <div class="card-body">
                <p class="text-muted mb-0" style="font-size:0.88rem;line-height:1.7;">
                    {{ $hasil->penyakit->deskripsi }}
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-heart-pulse me-2 text-danger"></i>
                Rekomendasi Penanganan
            </div>
            <div class="card-body">
                <p class="text-muted mb-0" style="font-size:0.88rem;line-height:1.7;">
                    {{ $hasil->penyakit->solusi }}
                </p>
            </div>
        </div>
    </div>

</div>
@endsection