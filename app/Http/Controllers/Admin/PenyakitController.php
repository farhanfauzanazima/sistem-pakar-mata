<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyakit;
use Illuminate\Http\Request;

class PenyakitController extends Controller
{
    public function index()
    {
        $penyakit = Penyakit::orderBy('kode')->paginate(10);
        return view('admin.penyakit.index', compact('penyakit'));
    }

    public function create()
    {
        return view('admin.penyakit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'      => 'required|string|max:10|unique:penyakit,kode',
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'solusi'    => 'required|string',
        ], [
            'kode.required'      => 'Kode penyakit wajib diisi.',
            'kode.unique'        => 'Kode penyakit sudah digunakan.',
            'nama.required'      => 'Nama penyakit wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'solusi.required'    => 'Solusi wajib diisi.',
        ]);

        Penyakit::create($request->only('kode', 'nama', 'deskripsi', 'solusi'));

        return redirect()->route('admin.penyakit.index')
            ->with('success', 'Penyakit berhasil ditambahkan.');
    }

    public function show(Penyakit $penyakit)
    {
        $penyakit->load('aturan.gejala');
        return view('admin.penyakit.show', compact('penyakit'));
    }

    public function edit(Penyakit $penyakit)
    {
        return view('admin.penyakit.edit', compact('penyakit'));
    }

    public function update(Request $request, Penyakit $penyakit)
    {
        $request->validate([
            'kode'      => 'required|string|max:10|unique:penyakit,kode,' . $penyakit->id,
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'solusi'    => 'required|string',
        ], [
            'kode.required'      => 'Kode penyakit wajib diisi.',
            'kode.unique'        => 'Kode penyakit sudah digunakan.',
            'nama.required'      => 'Nama penyakit wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'solusi.required'    => 'Solusi wajib diisi.',
        ]);

        $penyakit->update($request->only('kode', 'nama', 'deskripsi', 'solusi'));

        return redirect()->route('admin.penyakit.index')
            ->with('success', 'Penyakit berhasil diperbarui.');
    }

    public function destroy(Penyakit $penyakit)
    {
        $penyakit->delete();

        return redirect()->route('admin.penyakit.index')
            ->with('success', 'Penyakit berhasil dihapus.');
    }
}