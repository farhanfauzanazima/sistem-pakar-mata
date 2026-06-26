<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Penyakit;
use App\Models\Gejala;
use App\Models\Aturan;

class TentangController extends Controller
{
    public function index()
    {
        $penyakit    = Penyakit::withCount('aturan')->orderBy('kode')->get();
        $totalGejala = Gejala::count();
        $totalAturan = Aturan::count();

        // Cek apakah file PDF jurnal tersedia
        $pdfJurnalAda = file_exists(
            storage_path('app/public/jurnal/jurnal-sistem-pakar-mata.pdf')
        );

        $urlWebsiteJurnal = env('JURNAL_WEBSITE_URL', '#');

        return view('user.tentang.index', compact(
            'penyakit',
            'totalGejala',
            'totalAturan',
            'pdfJurnalAda',
            'urlWebsiteJurnal'
        ));
    }
}