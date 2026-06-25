@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row g-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-1">
                    <i class="bi bi-hand-wave text-warning me-2"></i>
                    Selamat datang, {{ Auth::guard('admin')->user()?->nama ?? 'Admin' }}!
                </h5>
                <p class="text-muted mb-0" style="font-size:0.9rem;">
                    Ini adalah panel administrasi Sistem Pakar Diagnosis Penyakit Mata.
                    Dashboard lengkap akan tersedia setelah semua modul selesai.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection