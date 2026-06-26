<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilDiagnosa;
use App\Models\Penyakit;
use App\Exports\DiagnosaExport;
use App\Services\CertaintyFactorService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class DiagnosaController extends Controller
{
    public function index(Request $request)
    {
        $query = HasilDiagnosa::with(['pasien', 'penyakit'])
            ->orderBy('created_at', 'desc');

        // Filter tanggal mulai
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        // Filter tanggal akhir
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }

        // Filter penyakit
        if ($request->filled('penyakit_id')) {
            $query->where('penyakit_id', $request->penyakit_id);
        }

        // Filter CF minimum
        if ($request->filled('cf_min')) {
            $query->where('cf_persen', '>=', (int) $request->cf_min);
        }

        $diagnosa = $query->paginate(15)->withQueryString();
        $penyakit = Penyakit::orderBy('kode')->get();
        $total    = HasilDiagnosa::count();

        return view('admin.diagnosa.index', compact(
            'diagnosa', 'penyakit', 'total'
        ));
    }

    public function show($id)
    {
        $hasil = HasilDiagnosa::with([
            'pasien',
            'penyakit',
            'detailDiagnosa.gejala',
        ])->findOrFail($id);

        $label = CertaintyFactorService::labelKeyakinan($hasil->cf_hasil);
        $warna = CertaintyFactorService::warnaKeyakinan($hasil->cf_hasil);

        return view('admin.diagnosa.show', compact('hasil', 'label', 'warna'));
    }

    public function destroy($id)
    {
        $hasil = HasilDiagnosa::findOrFail($id);
        $hasil->delete();

        return redirect()->route('admin.diagnosa.index')
            ->with('success', 'Data diagnosa berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai ?? null;
        $tanggalAkhir = $request->tanggal_akhir ?? null;
        $penyakitId   = $request->penyakit_id   ? (int) $request->penyakit_id : null;

        $namaFile = 'data-diagnosa-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(
            new DiagnosaExport($tanggalMulai, $tanggalAkhir, $penyakitId),
            $namaFile
        );
    }
}