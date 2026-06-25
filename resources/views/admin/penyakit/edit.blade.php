@extends('layouts.admin')

@section('title', 'Edit Penyakit')
@section('page-title', 'Edit Penyakit')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.penyakit.index') }}">Penyakit</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card" style="max-width:760px">
    <div class="card-header">
        <i class="bi bi-pencil-square me-2 text-warning"></i>Edit Penyakit — {{ $penyakit->nama }}
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.penyakit.update', $penyakit) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kode Penyakit <span class="text-danger">*</span></label>
                    <input type="text"
                           name="kode"
                           class="form-control @error('kode') is-invalid @enderror"
                           value="{{ old('kode', $penyakit->kode) }}"
                           maxlength="10">
                    @error('kode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Penyakit <span class="text-danger">*</span></label>
                    <input type="text"
                           name="nama"
                           class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama', $penyakit->nama) }}"
                           maxlength="100">
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="deskripsi"
                              rows="4"
                              class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $penyakit->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Solusi / Penanganan <span class="text-danger">*</span></label>
                    <textarea name="solusi"
                              rows="4"
                              class="form-control @error('solusi') is-invalid @enderror">{{ old('solusi', $penyakit->solusi) }}</textarea>
                    @error('solusi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-check-lg me-1"></i> Perbarui
                </button>
                <a href="{{ route('admin.penyakit.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection