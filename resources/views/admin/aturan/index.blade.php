@extends('layouts.admin')

@section('title', 'Manajemen Aturan CF')
@section('page-title', 'Manajemen Aturan / Relasi CF')

@section('breadcrumb')
    <li class="breadcrumb-item active">Aturan CF</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-diagram-3 me-2 text-purple" style="color:#6f42c1"></i>Daftar Aturan Relasi CF Pakar</span>
        <a href="{{ route('admin.aturan.create') }}" class="btn btn-primary-custom btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Aturan
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:60px">No</th>
                        <th>Penyakit</th>
                        <th>Gejala</th>
                        <th style="width:120px">CF Pakar</th>
                        <th style="width:180px">Tingkat Keyakinan</th>
                        <th style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aturan as $item)
                    <tr>
                        <td>{{ $aturan->firstItem() + $loop->index }}</td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary me-1">
                                {{ $item->penyakit->kode }}
                            </span>
                            <span class="fw-semibold">{{ $item->penyakit->nama }}</span>
                        </td>
                        <td>
                            <span class="badge bg-success-subtle text-success me-1">
                                {{ $item->gejala->kode }}
                            </span>
                            {{ $item->gejala->nama }}
                        </td>
                        <td>
                            <span class="fw-bold fs-6">{{ number_format($item->cf_pakar, 2) }}</span>
                        </td>
                        <td>
                            @php $persen = $item->cf_pakar * 100; @endphp
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:8px;">
                                    <div class="progress-bar
                                        @if($persen >= 80) bg-success
                                        @elseif($persen >= 60) bg-warning
                                        @else bg-danger @endif"
                                        style="width:{{ $persen }}%">
                                    </div>
                                </div>
                                <small class="text-muted" style="width:38px;">
                                    {{ number_format($persen, 0) }}%
                                </small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.aturan.edit', $item) }}"
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.aturan.destroy', $item) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus aturan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            Belum ada aturan CF.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($aturan->hasPages())
        <div class="p-3 border-top">
            {{ $aturan->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Ringkasan per Penyakit --}}
<div class="card mt-3">
    <div class="card-header">
        <i class="bi bi-bar-chart me-2"></i>Ringkasan Aturan per Penyakit
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>Penyakit</th>
                    <th>Jumlah Gejala</th>
                    <th>Rata-rata CF</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\Penyakit::withCount('aturan')->with('aturan')->orderBy('kode')->get() as $p)
                <tr>
                    <td>
                        <span class="badge bg-primary-subtle text-primary me-1">{{ $p->kode }}</span>
                        {{ $p->nama }}
                    </td>
                    <td>
                        <span class="badge bg-secondary">{{ $p->aturan_count }} gejala</span>
                    </td>
                    <td>
                        @php
                            $rata = $p->aturan->avg('cf_pakar');
                        @endphp
                        {{ $rata ? number_format($rata, 2) : '-' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection