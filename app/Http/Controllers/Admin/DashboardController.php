<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilDiagnosa;
use App\Models\Pasien;
use App\Models\Penyakit;
use App\Models\Gejala;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Kartu Statistik
        $totalDiagnosa = HasilDiagnosa::count();
        $totalPasien   = Pasien::count();
        $totalPenyakit = Penyakit::count();
        $totalGejala   = Gejala::count();

        // Diagnosa bulan ini
        $diagnosabulanIni = HasilDiagnosa::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Penyakit terbanyak
        $penyakitTerbanyak = HasilDiagnosa::select('penyakit_id', DB::raw('count(*) as total'))
            ->with('penyakit')
            ->groupBy('penyakit_id')
            ->orderBy('total', 'desc')
            ->first();

        // Data grafik pie — distribusi penyakit
        $distribusiPenyakit = HasilDiagnosa::select(
                'penyakit_id',
                DB::raw('count(*) as total')
            )
            ->with('penyakit')
            ->groupBy('penyakit_id')
            ->orderBy('total', 'desc')
            ->get();

        // Data grafik line — tren diagnosa 6 bulan terakhir
        $trenDiagnosa = HasilDiagnosa::select(
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        // Format data tren untuk Chart.js
        $labelTren = [];
        $dataTren  = [];
        $bulanIndo = [
            1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',
            5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',
            9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des',
        ];

        // Isi semua 6 bulan terakhir (termasuk yang kosong = 0)
        for ($i = 5; $i >= 0; $i--) {
            $tgl   = now()->subMonths($i);
            $bln   = (int) $tgl->format('m');
            $thn   = (int) $tgl->format('Y');
            $label = $bulanIndo[$bln] . ' ' . $thn;

            $found = $trenDiagnosa->first(
                fn($t) => (int)$t->bulan === $bln && (int)$t->tahun === $thn
            );

            $labelTren[] = $label;
            $dataTren[]  = $found ? $found->total : 0;
        }

        // Diagnosa terbaru
        $diagnosaTermbaru = HasilDiagnosa::with(['pasien', 'penyakit'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Rata-rata CF
        $rataRataCf = HasilDiagnosa::avg('cf_persen');

        return view('admin.dashboard.index', compact(
            'totalDiagnosa',
            'totalPasien',
            'totalPenyakit',
            'totalGejala',
            'diagnosabulanIni',
            'penyakitTerbanyak',
            'distribusiPenyakit',
            'labelTren',
            'dataTren',
            'diagnosaTermbaru',
            'rataRataCf'
        ));
    }
}