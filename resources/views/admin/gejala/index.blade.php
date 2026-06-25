@extends('layouts.admin')

@section('title', 'Manajemen Gejala')
@section('page-title', 'Manajemen Gejala')

@section('breadcrumb')
    <li class="breadcrumb-item active">Gejala</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-clipboard2-pulse me-2 text-success"></i>Daftar Gejala</span>
        <a href="{{ route('admin.gejala.create') }}" class="btn btn-primary-custom btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Gejala
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:60px">No</th>
                        <th style="width:80px">Kode</th>
                        <th>Nama Gejala</th>
                        <th style="width:130px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gejala as $item)
                    <tr>
                        <td>{{ $gejala->firstItem() + $loop->index }}</td>
                        <td>
                            <span class="badge bg-success-subtle text-success fw-semibold">
                                {{ $item->kode }}
                            </span>
                        </td>
                        <td>{{ $item->nama }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.gejala.edit', $item) }}"
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.gejala.destroy', $item) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus gejala {{ $item->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            Belum ada data gejala.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($gejala->hasPages())
        <div class="p-3 border-top">
            {{ $gejala->links() }}
        </div>
        @endif
    </div>
</div>
@endsection