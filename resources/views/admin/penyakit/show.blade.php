@extends('layouts.admin')

@section('title', 'Detail Penyakit')
@section('page-title', 'Detail Penyakit')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.penyakit.index') }}">Penyakit</a>
    </li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-3" style="max-width:900px">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>
                    <i class="bi bi-virus me-2 text-primary"></i>
                    {{ $penyakit->nama }}
                    <span class="badge bg-primary-subtle text-primary ms-2">{{ $penyakit->kode }}</span>
                </span>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.penyakit.edit', $penyakit) }}"
                       class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.penyakit.index') }}"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-12">
                        <p class="text-muted fw-semibold mb-1" style="font-size:0.8rem;text-transform:uppercase;">Deskripsi</p>
                        <p class="mb-0">{{ $penyakit->deskripsi }}</p>
                    </div>
                    <div class="col-12">
                        <p class="text-muted fw-semibold mb-1" style="font-size:0.8rem;text-transform:uppercase;">Solusi / Penanganan</p>
                        <p class="mb-0">{{ $penyakit->solusi }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Gejala Terkait --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-diagram-3 me-2 text-success"></i>
                Gejala Terkait ({{ $penyakit->aturan->count() }} aturan)
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Gejala</th>
                            <th>Nama Gejala</th>
                            <th>CF Pakar</th>
                            <th>Tingkat Keyakinan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penyakit->aturan as $aturan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge bg-success-subtle text-success fw-semibold">
                                    {{ $aturan->gejala->kode }}
                                </span>
                            </td>
                            <td>{{ $aturan->gejala->nama }}</td>
                            <td><strong>{{ $aturan->cf_pakar }}</strong></td>
                            <td>
                                <div class="progress" style="height:8px; width:120px;">
                                    <div class="progress-bar bg-success"
                                         style="width:{{ $aturan->cf_pakar * 100 }}%"></div>
                                </div>
                                <small class="text-muted">{{ ($aturan->cf_pakar * 100) }}%</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                Belum ada aturan gejala untuk penyakit ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection