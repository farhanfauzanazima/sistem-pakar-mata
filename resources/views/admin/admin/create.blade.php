@extends('layouts.admin')

@section('title', 'Tambah Admin')
@section('page-title', 'Tambah Admin')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.admin.index') }}">Manajemen Admin</a>
    </li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card" style="max-width:560px">
    <div class="card-header">
        <i class="bi bi-person-plus me-2 text-primary"></i>Form Tambah Admin
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.admin.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Nama <span class="text-danger">*</span>
                </label>
                <input type="text"
                       name="nama"
                       class="form-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama') }}"
                       placeholder="Nama lengkap admin">
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Email <span class="text-danger">*</span>
                </label>
                <input type="email"
                       name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       placeholder="email@contoh.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Password <span class="text-danger">*</span>
                </label>
                <input type="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Minimal 6 karakter">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Konfirmasi Password <span class="text-danger">*</span>
                </label>
                <input type="password"
                       name="password_confirmation"
                       class="form-control"
                       placeholder="Ulangi password">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-check-lg me-1"></i>Simpan
                </button>
                <a href="{{ route('admin.admin.index') }}"
                   class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection