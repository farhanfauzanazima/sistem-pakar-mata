@extends('layouts.admin')

@section('title', 'Data Diagnosa Pasien')
@section('page-title', 'Data Diagnosa Pasien')

@section('breadcrumb')
    <li class="breadcrumb-item active">Diagnosa</li>
@endsection

@section('content')

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-header">
        <i class="bi bi-funnel me-2"></i>Filter Data
    </div>
    <div class="card-body p-3">
        <form action="{{ route('admin.diagnosa.index') }}" method="GET">
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label fw-semibold" style="font-size:0.82rem;">
                        Tanggal Mulai
                    </label>
                    <input type="date" name="tanggal_mulai"
                           class="form-control form-control-sm"
                           value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold" style="font-size:0.82rem;">
                        Tanggal Akhir
                    </label>
                    <input type="date" name="tanggal_akhir"
                           class="form-control form-control-sm"
                           value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold" style="font-size:0.82rem;">
                        Penyakit
                    </label>
                    <select name="penyakit_id" class="form-select form-select-sm">
                        <option value="">Semua Penyakit</option>
                        @foreach($penyakit as $p)
                            <option value="{{ $p->id }}"
                                {{ request('penyakit_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold" style="font-size:0.82rem;">
                        CF Minimum (%)
                    </label>
                    <input type="number" name="cf_min"
                           class="form-control form-control-sm"
                           value="{{ request('cf_min') }}"
                           placeholder="Contoh: 60"
                           min="0" max="100">
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary-custom btn-sm">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.diagnosa.index') }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg me-1"></i>Reset
                    </a>
                    {{-- Tombol Ekspor --}}
                    <a href="{{ route('admin.diagnosa.export', request()->query()) }}"
                       class="btn btn-success btn-sm ms-auto">
                        <i class="bi bi-file-earmark-excel me-1"></i>Ekspor Excel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span>
            <i class="bi bi-journal-medical me-2 text-info"></i>
            Daftar Diagnosa
        </span>
        <span class="badge bg-secondary">
            Total: {{ $total }} data
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px">No</th>
                        <th>Pasien</th>
                        <th>Nomor HP</th>
                        <th>Penyakit</th>
                        <th style="width:120px">CF</th>
                        <th style="width:130px">Tanggal</th>
                        <th style="width:110px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($diagnosa as $item)
                    @php
                        $warna = \App\Services\CertaintyFactorService::warnaKeyakinan($item->cf_hasil);
                    @endphp
                    <tr>
                        <td>{{ $diagnosa->firstItem() + $loop->index }}</td>
                        <td class="fw-semibold">{{ $item->pasien->nama }}</td>
                        <td class="text-muted">{{ $item->pasien->no_hp }}</td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary">
                                {{ $item->penyakit->nama }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:6px;">
                                    <div class="progress-bar bg-{{ $warna }}"
                                         style="width:{{ $item->cf_persen }}%"></div>
                                </div>
                                <span class="fw-semibold text-{{ $warna }}"
                                      style="font-size:0.82rem;width:36px;">
                                    {{ $item->cf_persen }}%
                                </span>
                            </div>
                        </td>
                        <td>
                            <span style="font-size:0.82rem;">
                                {{ $item->created_at->format('d M Y') }}<br>
                                <span class="text-muted">
                                    {{ $item->created_at->format('H:i') }} WIB
                                </span>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.diagnosa.show', $item->id) }}"
                                   class="btn btn-sm btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('admin.diagnosa.destroy', $item->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus data diagnosa ini?')">
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
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            Belum ada data diagnosa.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($diagnosa->hasPages())
        <div class="p-3 border-top">
            {{ $diagnosa->links() }}
        </div>
        @endif
    </div>
</div>

@endsection