@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('styles')
<style>
    .stat-card {
        border-radius: 14px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        background: white;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: none;
    }
    .stat-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .stat-info .stat-angka {
        font-size: 1.6rem;
        font-weight: 800;
        line-height: 1;
        color: #1a2e3b;
    }
    .stat-info .stat-label {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 2px;
    }
    .stat-info .stat-sub {
        font-size: 0.75rem;
        margin-top: 4px;
    }
</style>
@endpush

@section('content')

{{-- Kartu Statistik --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(46,134,171,0.12);color:#2E86AB;">
                <i class="bi bi-journal-medical"></i>
            </div>
            <div class="stat-info">
                <div class="stat-angka">{{ number_format($totalDiagnosa) }}</div>
                <div class="stat-label">Total Diagnosa</div>
                <div class="stat-sub text-primary">
                    +{{ $diagnosabulanIni }} bulan ini
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(87,204,153,0.12);color:#57CC99;">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-info">
                <div class="stat-angka">{{ number_format($totalPasien) }}</div>
                <div class="stat-label">Total Pasien</div>
                <div class="stat-sub text-success">Terdaftar</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(240,165,0,0.12);color:#f0a500;">
                <i class="bi bi-graph-up"></i>
            </div>
            <div class="stat-info">
                <div class="stat-angka">
                    {{ $rataRataCf ? number_format($rataRataCf, 1) . '%' : '-' }}
                </div>
                <div class="stat-label">Rata-rata CF</div>
                <div class="stat-sub text-warning">Tingkat keyakinan</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(224,92,92,0.12);color:#e05c5c;">
                <i class="bi bi-virus"></i>
            </div>
            <div class="stat-info">
                <div class="stat-angka">
                    {{ $penyakitTerbanyak ? Str::limit($penyakitTerbanyak->penyakit->nama, 10) : '-' }}
                </div>
                <div class="stat-label">Penyakit Terbanyak</div>
                <div class="stat-sub text-danger">
                    {{ $penyakitTerbanyak ? $penyakitTerbanyak->total . ' kasus' : '-' }}
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Grafik --}}
<div class="row g-3 mb-4">

    {{-- Grafik Line: Tren Diagnosa --}}
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>
                    <i class="bi bi-graph-up-arrow me-2 text-primary"></i>
                    Tren Diagnosa 6 Bulan Terakhir
                </span>
            </div>
            <div class="card-body">
                <canvas id="chartTren" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Grafik Pie: Distribusi Penyakit --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart me-2 text-success"></i>
                Distribusi Penyakit
            </div>
            <div class="card-body d-flex flex-column align-items-center">
                <canvas id="chartPie" style="max-height:220px;"></canvas>
                <div class="mt-3 w-100" id="pie-legend"></div>
            </div>
        </div>
    </div>

</div>

{{-- Info Bawah --}}
<div class="row g-3">

    {{-- Diagnosa Terbaru --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>
                    <i class="bi bi-clock-history me-2 text-info"></i>
                    Diagnosa Terbaru
                </span>
                <a href="{{ route('admin.diagnosa.index') }}"
                   class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Pasien</th>
                            <th>Penyakit</th>
                            <th>CF</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($diagnosaTermbaru as $item)
                        @php
                            $w = \App\Services\CertaintyFactorService::warnaKeyakinan($item->cf_hasil);
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $item->pasien->nama }}</td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary">
                                    {{ $item->penyakit->nama }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-{{ $w }}">
                                    {{ $item->cf_persen }}%
                                </span>
                            </td>
                            <td class="text-muted" style="font-size:0.82rem;">
                                {{ $item->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                Belum ada diagnosa.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Info Sistem --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i>
                Ringkasan Sistem
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted ps-3" style="font-size:0.85rem;">
                            Total Penyakit
                        </td>
                        <td class="fw-semibold pe-3">{{ $totalPenyakit }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-3" style="font-size:0.85rem;">
                            Total Gejala
                        </td>
                        <td class="fw-semibold pe-3">{{ $totalGejala }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-3" style="font-size:0.85rem;">
                            Metode
                        </td>
                        <td class="fw-semibold pe-3" style="font-size:0.82rem;">
                            CF + Forward Chaining
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-3" style="font-size:0.85rem;">
                            Framework
                        </td>
                        <td class="fw-semibold pe-3">Laravel 12</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-3" style="font-size:0.85rem;">
                            Database
                        </td>
                        <td class="fw-semibold pe-3">MySQL 8</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Distribusi per penyakit --}}
        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-bar-chart me-2 text-warning"></i>
                Jumlah Diagnosa per Penyakit
            </div>
            <div class="card-body p-3">
                @forelse($distribusiPenyakit as $item)
                @php $pct = $totalDiagnosa > 0
                    ? round($item->total / $totalDiagnosa * 100) : 0;
                @endphp
                <div class="mb-2">
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:0.82rem;font-weight:600;">
                            {{ $item->penyakit->nama }}
                        </span>
                        <span style="font-size:0.82rem;" class="text-muted">
                            {{ $item->total }} ({{ $pct }}%)
                        </span>
                    </div>
                    <div class="progress" style="height:6px;border-radius:3px;">
                        <div class="progress-bar bg-primary"
                             style="width:{{ $pct }}%;border-radius:3px;"></div>
                    </div>
                </div>
                @empty
                <p class="text-muted mb-0" style="font-size:0.85rem;">
                    Belum ada data diagnosa.
                </p>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
// ── Grafik Line: Tren Diagnosa ──
const ctxTren = document.getElementById('chartTren').getContext('2d');
new Chart(ctxTren, {
    type: 'line',
    data: {
        labels: @json($labelTren),
        datasets: [{
            label: 'Jumlah Diagnosa',
            data: @json($dataTren),
            borderColor: '#2E86AB',
            backgroundColor: 'rgba(46,134,171,0.08)',
            borderWidth: 2.5,
            pointBackgroundColor: '#2E86AB',
            pointRadius: 5,
            pointHoverRadius: 7,
            tension: 0.35,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' ' + ctx.parsed.y + ' diagnosa'
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    font: { size: 11 }
                },
                grid: { color: 'rgba(0,0,0,0.04)' }
            },
            x: {
                ticks: { font: { size: 11 } },
                grid: { display: false }
            }
        }
    }
});

// ── Grafik Pie: Distribusi Penyakit ──
const distribusi = @json($distribusiPenyakit);
const warnaPie   = ['#2E86AB','#57CC99','#f0a500','#e05c5c','#6f42c1'];

const ctxPie = document.getElementById('chartPie').getContext('2d');
const chartPie = new Chart(ctxPie, {
    type: 'doughnut',
    data: {
        labels: distribusi.map(d => d.penyakit.nama),
        datasets: [{
            data: distribusi.map(d => d.total),
            backgroundColor: warnaPie,
            borderWidth: 2,
            borderColor: '#fff',
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        cutout: '60%',
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx =>
                        ' ' + ctx.label + ': ' + ctx.parsed + ' kasus'
                }
            }
        }
    }
});

// Legend manual pie
const legend = document.getElementById('pie-legend');
if (distribusi.length > 0) {
    distribusi.forEach((d, i) => {
        legend.innerHTML += `
            <div class="d-flex align-items-center gap-2 mb-1">
                <div style="width:12px;height:12px;border-radius:3px;
                            background:${warnaPie[i]};flex-shrink:0;"></div>
                <span style="font-size:0.8rem;">${d.penyakit.nama}</span>
                <span class="ms-auto text-muted" style="font-size:0.8rem;">
                    ${d.total}
                </span>
            </div>`;
    });
} else {
    legend.innerHTML =
        '<p class="text-muted text-center mb-0" style="font-size:0.82rem;">' +
        'Belum ada data.</p>';
}
</script>
@endpush