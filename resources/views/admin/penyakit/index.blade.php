@extends('layouts.admin')

@section('title', 'Manajemen Penyakit')
@section('page-title', 'Manajemen Penyakit')

@section('breadcrumb')
    <li class="breadcrumb-item active">Penyakit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-virus me-2 text-primary"></i>Daftar Penyakit</span>
        <a href="{{ route('admin.penyakit.create') }}" class="btn btn-primary-custom btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Penyakit
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:60px">No</th>
                        <th style="width:80px">Kode</th>
                        <th>Nama Penyakit</th>
                        <th>Deskripsi</th>
                        <th style="width:160px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penyakit as $item)
                    <tr>
                        <td>{{ $penyakit->firstItem() + $loop->index }}</td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary fw-semibold">
                                {{ $item->kode }}
                            </span>
                        </td>
                        <td class="fw-semibold">{{ $item->nama }}</td>
                        <td>
                            <span class="text-muted" style="font-size:0.85rem;">
                                {{ Str::limit($item->deskripsi, 80) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.penyakit.show', $item) }}"
                                   class="btn btn-sm btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.penyakit.edit', $item) }}"
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.penyakit.destroy', $item) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus penyakit {{ $item->nama }}? Data aturan terkait juga akan terhapus.')">
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
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            Belum ada data penyakit.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($penyakit->hasPages())
        <div class="p-3 border-top">
            {{ $penyakit->links() }}
        </div>
        @endif
    </div>
</div>
@endsection