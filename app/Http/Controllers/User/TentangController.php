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
        $penyakit     = Penyakit::withCount('aturan')->orderBy('kode')->get();
        $totalGejala  = Gejala::count();
        $totalAturan  = Aturan::count();

        return view('user.tentang.index', compact(
            'penyakit',
            'totalGejala',
            'totalAturan'
        ));
    }
}