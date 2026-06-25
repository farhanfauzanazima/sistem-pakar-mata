@extends('layouts.admin')

@section('title', 'Tambah Gejala')
@section('page-title', 'Tambah Gejala')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.gejala.index') }}">Gejala</a>
    </li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card" style="max-width:560px">
    <div class="card-header">
        <i class="bi bi-plus-circle me-2 text-success"></i>Form Tambah Gejala
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.gejala.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kode Gejala <span class="text-danger">*</span></label>
                    <input type="text"
                           name="kode"
                           class="form-control @error('kode') is-invalid @enderror"
                           value="{{ old('kode') }}"
                           placeholder="G013"
                           maxlength="10">
                    @error('kode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Gejala <span class="text-danger">*</span></label>
                    <input type="text"
                           name="nama"
                           class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama') }}"
                           placeholder="Contoh: Mata kering"
                           maxlength="200">
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-check-lg me-1"></i> Simpan
                </button>
                <a href="{{ route('admin.gejala.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection