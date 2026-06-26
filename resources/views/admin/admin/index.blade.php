@extends('layouts.admin')

@section('title', 'Manajemen Admin')
@section('page-title', 'Manajemen Admin')

@section('breadcrumb')
    <li class="breadcrumb-item active">Manajemen Admin</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span>
            <i class="bi bi-shield-person me-2 text-primary"></i>
            Daftar Akun Admin
        </span>
        <a href="{{ route('admin.admin.create') }}" class="btn btn-primary-custom btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Tambah Admin
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Dibuat</th>
                    <th style="width:130px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="
                                width:32px;height:32px;border-radius:8px;flex-shrink:0;
                                background:linear-gradient(135deg,#2E86AB,#57CC99);
                                display:flex;align-items:center;justify-content:center;
                                color:white;font-size:0.8rem;font-weight:700;
                            ">
                                {{ strtoupper(substr($item->nama, 0, 1)) }}
                            </div>
                            <span class="fw-semibold">{{ $item->nama }}</span>
                            @if($item->id === Auth::guard('admin')->id())
                                <span class="badge bg-success-subtle text-success"
                                      style="font-size:0.7rem;">Anda</span>
                            @endif
                        </div>
                    </td>
                    <td class="text-muted">{{ $item->email }}</td>
                    <td class="text-muted" style="font-size:0.82rem;">
                        {{ $item->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.admin.edit', $item) }}"
                               class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($item->id !== Auth::guard('admin')->id())
                            <form action="{{ route('admin.admin.destroy', $item) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus akun {{ $item->nama }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        Belum ada data admin.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection