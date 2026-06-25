@extends('layouts.admin')

@section('title', 'Tambah Aturan CF')
@section('page-title', 'Tambah Aturan CF')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.aturan.index') }}">Aturan CF</a>
    </li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card" style="max-width:620px">
    <div class="card-header">
        <i class="bi bi-plus-circle me-2" style="color:#6f42c1"></i>Form Tambah Aturan CF
    </div>
    <div class="card-body p-4">

        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.aturan.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Penyakit <span class="text-danger">*</span>
                </label>
                <select name="penyakit_id"
                        class="form-select @error('penyakit_id') is-invalid @enderror">
                    <option value="">-- Pilih Penyakit --</option>
                    @foreach($penyakit as $p)
                        <option value="{{ $p->id }}"
                            {{ old('penyakit_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->kode }} — {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
                @error('penyakit_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Gejala <span class="text-danger">*</span>
                </label>
                <select name="gejala_id"
                        class="form-select @error('gejala_id') is-invalid @enderror">
                    <option value="">-- Pilih Gejala --</option>
                    @foreach($gejala as $g)
                        <option value="{{ $g->id }}"
                            {{ old('gejala_id') == $g->id ? 'selected' : '' }}>
                            {{ $g->kode }} — {{ $g->nama }}
                        </option>
                    @endforeach
                </select>
                @error('gejala_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Nilai CF Pakar <span class="text-danger">*</span>
                    <span class="text-muted fw-normal">(0.01 — 1.00)</span>
                </label>
                <input type="number"
                       name="cf_pakar"
                       class="form-control @error('cf_pakar') is-invalid @enderror"
                       value="{{ old('cf_pakar') }}"
                       placeholder="Contoh: 0.80"
                       step="0.01"
                       min="0.01"
                       max="1.00">
                @error('cf_pakar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    Nilai mendekati 1.00 berarti gejala sangat meyakinkan untuk penyakit tersebut.
                </div>
            </div>

            {{-- Preview CF --}}
            <div class="p-3 bg-light rounded mb-4" id="cf-preview" style="display:none;">
                <p class="mb-1 fw-semibold" style="font-size:0.85rem;">Preview Tingkat Keyakinan:</p>
                <div class="progress" style="height:10px;">
                    <div class="progress-bar bg-success" id="cf-bar" style="width:0%"></div>
                </div>
                <small class="text-muted" id="cf-label">0%</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-check-lg me-1"></i> Simpan
                </button>
                <a href="{{ route('admin.aturan.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const cfInput   = document.querySelector('input[name="cf_pakar"]');
    const cfPreview = document.getElementById('cf-preview');
    const cfBar     = document.getElementById('cf-bar');
    const cfLabel   = document.getElementById('cf-label');

    cfInput.addEventListener('input', function () {
        const val = parseFloat(this.value);
        if (!isNaN(val) && val >= 0 && val <= 1) {
            cfPreview.style.display = 'block';
            const pct = (val * 100).toFixed(0);
            cfBar.style.width = pct + '%';
            cfLabel.textContent = pct + '%';
            cfBar.className = 'progress-bar ' +
                (val >= 0.8 ? 'bg-success' : val >= 0.6 ? 'bg-warning' : 'bg-danger');
        } else {
            cfPreview.style.display = 'none';
        }
    });
</script>
@endpush