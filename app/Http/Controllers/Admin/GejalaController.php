<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gejala;
use Illuminate\Http\Request;

class GejalaController extends Controller
{
    public function index()
    {
        $gejala = Gejala::orderBy('kode')->paginate(15);
        return view('admin.gejala.index', compact('gejala'));
    }

    public function create()
    {
        return view('admin.gejala.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:gejala,kode',
            'nama' => 'required|string|max:200',
        ], [
            'kode.required' => 'Kode gejala wajib diisi.',
            'kode.unique'   => 'Kode gejala sudah digunakan.',
            'nama.required' => 'Nama gejala wajib diisi.',
        ]);

        Gejala::create($request->only('kode', 'nama'));

        return redirect()->route('admin.gejala.index')
            ->with('success', 'Gejala berhasil ditambahkan.');
    }

    public function edit(Gejala $gejala)
    {
        return view('admin.gejala.edit', compact('gejala'));
    }

    public function update(Request $request, Gejala $gejala)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:gejala,kode,' . $gejala->id,
            'nama' => 'required|string|max:200',
        ], [
            'kode.required' => 'Kode gejala wajib diisi.',
            'kode.unique'   => 'Kode gejala sudah digunakan.',
            'nama.required' => 'Nama gejala wajib diisi.',
        ]);

        $gejala->update($request->only('kode', 'nama'));

        return redirect()->route('admin.gejala.index')
            ->with('success', 'Gejala berhasil diperbarui.');
    }

    public function destroy(Gejala $gejala)
    {
        $gejala->delete();

        return redirect()->route('admin.gejala.index')
            ->with('success', 'Gejala berhasil dihapus.');
    }
}