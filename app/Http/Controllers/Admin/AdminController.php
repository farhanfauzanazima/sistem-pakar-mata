<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::orderBy('created_at', 'desc')->get();
        return view('admin.admin.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'nama.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah digunakan.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        Admin::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.admin.index')
            ->with('success', 'Akun admin berhasil ditambahkan.');
    }

    public function edit(Admin $admin)
    {
        return view('admin.admin.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'nama'  => 'required|string|max:100',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
        ], [
            'nama.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email sudah digunakan.',
        ]);

        $data = [
            'nama'  => $request->nama,
            'email' => $request->email,
        ];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6|confirmed',
            ], [
                'password.min'       => 'Password minimal 6 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.admin.index')
            ->with('success', 'Akun admin berhasil diperbarui.');
    }

    public function destroy(Admin $admin)
    {
        // Tidak boleh hapus diri sendiri
        if ($admin->id === Auth::guard('admin')->id()) {
            return redirect()->route('admin.admin.index')
                ->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
        }

        // Minimal harus ada 1 admin
        if (Admin::count() <= 1) {
            return redirect()->route('admin.admin.index')
                ->with('error', 'Tidak dapat menghapus admin terakhir.');
        }

        $admin->delete();

        return redirect()->route('admin.admin.index')
            ->with('success', 'Akun admin berhasil dihapus.');
    }
}