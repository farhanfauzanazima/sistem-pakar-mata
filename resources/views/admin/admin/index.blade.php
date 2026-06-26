@extends('layouts.admin')

@section('title', 'Manajemen Admin')
@section('page-title', 'Manajemen Admin')

@section('breadcrumb')
    <li class="breadcrumb-item active">Manajemen Admin</li>
@endsection

@section('content')

{{-- Info akses --}}
<div class="alert alert-info d-flex align-items-center gap-2 mb-3"
     style="border-radius:12px;font-size:0.88rem;">
    <i class="bi bi-shield-check-fill"></i>
    <div>
        Halaman ini hanya dapat diakses oleh <strong>Super Admin</strong>.
        Admin biasa tidak dapat melihat atau mengakses halaman ini.
    </div>
</div>

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
                    <th>Role</th>
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
                                background:linear-gradient(135deg,
                                    {{ $item->isSuperAdmin() ? '#f0a500,#e05c5c' : '#2E86AB,#57CC99' }});
                                display:flex;align-items:center;justify-content:center;
                                color:white;font-size:0.8rem;font-weight:700;
                            ">
                                {{ strtoupper(substr($item->nama, 0, 1)) }}
                            </div>
                            <div>
                                <span class="fw-semibold">{{ $item->nama }}</span>
                                @if($item->id === Auth::guard('admin')->id())
                                    <span class="badge bg-success-subtle text-success ms-1"
                                          style="font-size:0.68rem;">Anda</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="text-muted">{{ $item->email }}</td>
                    <td>
                        @if($item->isSuperAdmin())
                            <span class="badge px-2 py-1"
                                  style="background:rgba(240,165,0,0.15);
                                         color:#b37d00;border-radius:8px;
                                         font-size:0.75rem;">
                                <i class="bi bi-shield-fill-check me-1"></i>Super Admin
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary px-2 py-1"
                                  style="border-radius:8px;font-size:0.75rem;">
                                <i class="bi bi-person me-1"></i>Admin
                            </span>
                        @endif
                    </td>
                    <td class="text-muted" style="font-size:0.82rem;">
                        {{ $item->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.admin.edit', $item) }}"
                               class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            {{-- Tombol hapus hanya untuk admin biasa --}}
                            @if(!$item->isSuperAdmin() && $item->id !== Auth::guard('admin')->id())
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
                            @else
                            <button class="btn btn-sm btn-outline-secondary"
                                    disabled title="Tidak dapat dihapus">
                                <i class="bi bi-lock"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        Belum ada data admin.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection