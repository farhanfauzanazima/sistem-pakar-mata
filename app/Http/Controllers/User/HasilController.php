<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\HasilDiagnosa;
use App\Services\CertaintyFactorService;
use Barryvdh\DomPDF\Facade\Pdf;

class HasilController extends Controller
{
    // Tampilkan halaman hasil diagnosa
    public function show()
    {
        $hasilId = session('hasil_diagnosa_id');

        if (!$hasilId) {
            return redirect()->route('user.diagnosa.form')
                ->with('error', 'Sesi habis atau belum melakukan diagnosa.');
        }

        $hasil = HasilDiagnosa::with([
            'penyakit',
            'pasien',
            'detailDiagnosa.gejala',
        ])->findOrFail($hasilId);

        $label = CertaintyFactorService::labelKeyakinan($hasil->cf_hasil);
        $warna = CertaintyFactorService::warnaKeyakinan($hasil->cf_hasil);

        return view('user.hasil.show', compact('hasil', 'label', 'warna'));
    }

    // Download PDF hasil diagnosa
    public function downloadPdf($id)
    {
        $hasil = HasilDiagnosa::with([
            'penyakit',
            'pasien',
            'detailDiagnosa.gejala',
        ])->findOrFail($id);

        $label = CertaintyFactorService::labelKeyakinan($hasil->cf_hasil);
        $warna = CertaintyFactorService::warnaKeyakinan($hasil->cf_hasil);

        $pdf = Pdf::loadView('user.hasil.pdf', compact('hasil', 'label', 'warna'))
            ->setPaper('A4', 'portrait');

        $namaFile = 'hasil-diagnosa-' . $hasil->pasien->nama . '-' .
            $hasil->created_at->format('Ymd') . '.pdf';

        return $pdf->download($namaFile);
    }
}