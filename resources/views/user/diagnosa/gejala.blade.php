@extends('layouts.user')

@section('title', 'Pilih Gejala')

@push('styles')
<style>
    .gejala-card {
        background: white;
        border-radius: 14px;
        border: 2px solid #e9ecef;
        padding: 1rem 1.25rem;
        transition: border-color 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }
    .gejala-card:hover {
        border-color: #A8DADC;
        box-shadow: 0 4px 12px rgba(46,134,171,0.1);
    }
    .gejala-card.dipilih {
        border-color: #2E86AB;
        background: rgba(46,134,171,0.04);
    }
    .frekuensi-btn {
        flex: 1;
        padding: 0.35rem 0.5rem;
        border: 1.5px solid #dee2e6;
        border-radius: 8px;
        background: white;
        font-size: 0.78rem;
        font-weight: 500;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.15s;
        text-align: center;
    }
    .frekuensi-btn:hover { border-color: #adb5bd; color: #495057; }
    .frekuensi-btn.aktif-sangat_sering {
        background: #dc3545; border-color: #dc3545; color: white;
    }
    .frekuensi-btn.aktif-sering {
        background: #fd7e14; border-color: #fd7e14; color: white;
    }
    .frekuensi-btn.aktif-jarang {
        background: #0dcaf0; border-color: #0dcaf0; color: white;
    }
    .frekuensi-btn.aktif-tidak_pernah {
        background: #6c757d; border-color: #6c757d; color: white;
    }
    .progress-diagnosa {
        height: 8px;
        border-radius: 4px;
        background: #e9ecef;
        overflow: hidden;
    }
    .progress-diagnosa-bar {
        height: 100%;
        background: linear-gradient(90deg, #2E86AB, #57CC99);
        border-radius: 4px;
        transition: width 0.3s ease;
    }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Pilih Gejala</h4>
        <p class="text-muted mb-0" style="font-size:0.88rem;">
            Halo, <strong>{{ $pasien->nama }}</strong>!
            Pilih gejala yang Anda alami dan seberapa sering.
        </p>
    </div>
    <div class="text-end">
        <div class="text-muted" style="font-size:0.78rem;">Gejala dipilih</div>
        <div class="fw-bold" style="color:#2E86AB;">
            <span id="jumlah-dipilih">0</span> / {{ count($gejala) }}
        </div>
    </div>
</div>

{{-- Progress Bar --}}
<div class="progress-diagnosa mb-4">
    <div class="progress-diagnosa-bar" id="progress-bar" style="width:0%"></div>
</div>

{{-- Keterangan Frekuensi --}}
<div class="d-flex flex-wrap gap-2 mb-4">
    @foreach($frekuensi as $key => $info)
    <span class="badge px-2 py-1"
          style="background:rgba(0,0,0,0.06);color:#495057;
                 border-radius:8px;font-size:0.75rem;">
        <span style="
            display:inline-block;width:8px;height:8px;border-radius:50%;margin-right:4px;
            background:{{ match($key){
                'sangat_sering'=>'#dc3545',
                'sering'=>'#fd7e14',
                'jarang'=>'#0dcaf0',
                default=>'#6c757d'
            } }};
        "></span>
        {{ $info['label'] }} (CF={{ $info['cf_user'] }})
    </span>
    @endforeach
</div>

<form action="{{ route('user.diagnosa.proses-gejala') }}" method="POST" id="form-gejala">
    @csrf

    <div class="row g-3 mb-4" id="daftar-gejala">
        @foreach($gejala as $g)
        <div class="col-md-6">
            <div class="gejala-card" id="card-{{ $g->id }}"
                 onclick="pilihGejala({{ $g->id }})">
                <div class="d-flex align-items-start gap-2 mb-2">
                    <div style="
                        width:32px;height:32px;border-radius:8px;flex-shrink:0;
                        background:rgba(46,134,171,0.1);
                        display:flex;align-items:center;justify-content:center;
                        font-size:0.85rem;color:#2E86AB;font-weight:700;
                    ">
                        {{ $g->kode }}
                    </div>
                    <div>
                        <p class="fw-semibold mb-0" style="font-size:0.9rem;line-height:1.3;">
                            {{ $g->nama }}
                        </p>
                    </div>
                </div>

                {{-- Tombol frekuensi --}}
                <div class="d-flex gap-1 mt-2">
                    @foreach($frekuensi as $key => $info)
                    <button type="button"
                            class="frekuensi-btn"
                            id="btn-{{ $g->id }}-{{ $key }}"
                            onclick="event.stopPropagation(); setFrekuensi({{ $g->id }}, '{{ $key }}')">
                        {{ $info['label'] }}
                    </button>
                    @endforeach
                </div>

                {{-- Hidden input --}}
                <input type="hidden"
                       name="gejala[{{ $g->id }}]"
                       id="input-{{ $g->id }}"
                       value="tidak_pernah">
            </div>
        </div>
        @endforeach
    </div>

    {{-- Tombol Submit --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <a href="{{ route('user.diagnosa.form') }}" class="btn btn-outline-secondary"
           style="border-radius:10px;">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <button type="submit" class="btn btn-utama px-4" id="btn-submit">
            <i class="bi bi-cpu me-2"></i>Proses Diagnosa
        </button>
    </div>
</form>

@endsection

@push('scripts')
<script>
    const totalGejala = {{ count($gejala) }};
    let   dipilihCount = 0;

    function setFrekuensi(gejalaId, frekuensi) {
        const input    = document.getElementById('input-' + gejalaId);
        const card     = document.getElementById('card-' + gejalaId);
        const semuaBtn = ['sangat_sering','sering','jarang','tidak_pernah'];

        const nilaiLama = input.value;
        input.value     = frekuensi;

        // Update tampilan tombol
        semuaBtn.forEach(f => {
            const btn = document.getElementById('btn-' + gejalaId + '-' + f);
            btn.className = 'frekuensi-btn' + (f === frekuensi ? ' aktif-' + f : '');
        });

        // Update card state
        card.classList.toggle('dipilih', frekuensi !== 'tidak_pernah');

        // Update counter
        const wasDipilih = nilaiLama !== 'tidak_pernah';
        const isDipilih  = frekuensi !== 'tidak_pernah';

        if (!wasDipilih && isDipilih)  dipilihCount++;
        if (wasDipilih  && !isDipilih) dipilihCount--;

        // Update UI counter & progress
        document.getElementById('jumlah-dipilih').textContent = dipilihCount;
        const pct = (dipilihCount / totalGejala * 100).toFixed(1);
        document.getElementById('progress-bar').style.width = pct + '%';
    }

    function pilihGejala(gejalaId) {
        // Klik card → toggle ke "sering" jika belum dipilih, ke "tidak_pernah" jika sudah
        const input = document.getElementById('input-' + gejalaId);
        if (input.value === 'tidak_pernah') {
            setFrekuensi(gejalaId, 'sering');
        } else {
            setFrekuensi(gejalaId, 'tidak_pernah');
        }
    }
</script>
@endpush