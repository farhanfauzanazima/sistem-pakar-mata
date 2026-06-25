<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aturan;
use App\Models\Penyakit;
use App\Models\Gejala;
use Illuminate\Http\Request;

class AturanController extends Controller
{
    public function index()
    {
        $aturan = Aturan::with(['penyakit', 'gejala'])
            ->orderBy('penyakit_id')
            ->orderBy('cf_pakar', 'desc')
            ->paginate(15);

        return view('admin.aturan.index', compact('aturan'));
    }

    public function create()
    {
        $penyakit = Penyakit::orderBy('kode')->get();
        $gejala   = Gejala::orderBy('kode')->get();

        return view('admin.aturan.create', compact('penyakit', 'gejala'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penyakit_id' => 'required|exists:penyakit,id',
            'gejala_id'   => 'required|exists:gejala,id',
            'cf_pakar'    => 'required|numeric|min:0.01|max:1',
        ], [
            'penyakit_id.required' => 'Penyakit wajib dipilih.',
            'penyakit_id.exists'   => 'Penyakit tidak ditemukan.',
            'gejala_id.required'   => 'Gejala wajib dipilih.',
            'gejala_id.exists'     => 'Gejala tidak ditemukan.',
            'cf_pakar.required'    => 'Nilai CF Pakar wajib diisi.',
            'cf_pakar.numeric'     => 'Nilai CF Pakar harus berupa angka.',
            'cf_pakar.min'         => 'Nilai CF Pakar minimal 0.01.',
            'cf_pakar.max'         => 'Nilai CF Pakar maksimal 1.',
        ]);

        // Cek duplikat relasi
        $exists = Aturan::where('penyakit_id', $request->penyakit_id)
            ->where('gejala_id', $request->gejala_id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'Relasi penyakit dan gejala ini sudah ada.');
        }

        Aturan::create($request->only('penyakit_id', 'gejala_id', 'cf_pakar'));

        return redirect()->route('admin.aturan.index')
            ->with('success', 'Aturan CF berhasil ditambahkan.');
    }

    public function edit(Aturan $aturan)
    {
        $penyakit = Penyakit::orderBy('kode')->get();
        $gejala   = Gejala::orderBy('kode')->get();

        return view('admin.aturan.edit', compact('aturan', 'penyakit', 'gejala'));
    }

    public function update(Request $request, Aturan $aturan)
    {
        $request->validate([
            'penyakit_id' => 'required|exists:penyakit,id',
            'gejala_id'   => 'required|exists:gejala,id',
            'cf_pakar'    => 'required|numeric|min:0.01|max:1',
        ], [
            'penyakit_id.required' => 'Penyakit wajib dipilih.',
            'gejala_id.required'   => 'Gejala wajib dipilih.',
            'cf_pakar.required'    => 'Nilai CF Pakar wajib diisi.',
            'cf_pakar.numeric'     => 'Nilai CF Pakar harus berupa angka.',
            'cf_pakar.min'         => 'Nilai CF Pakar minimal 0.01.',
            'cf_pakar.max'         => 'Nilai CF Pakar maksimal 1.',
        ]);

        // Cek duplikat kecuali record saat ini
        $exists = Aturan::where('penyakit_id', $request->penyakit_id)
            ->where('gejala_id', $request->gejala_id)
            ->where('id', '!=', $aturan->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'Relasi penyakit dan gejala ini sudah ada.');
        }

        $aturan->update($request->only('penyakit_id', 'gejala_id', 'cf_pakar'));

        return redirect()->route('admin.aturan.index')
            ->with('success', 'Aturan CF berhasil diperbarui.');
    }

    public function destroy(Aturan $aturan)
    {
        $aturan->delete();

        return redirect()->route('admin.aturan.index')
            ->with('success', 'Aturan CF berhasil dihapus.');
    }
}