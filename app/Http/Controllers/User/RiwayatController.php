<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\HasilDiagnosa;
use App\Services\CertaintyFactorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RiwayatController extends Controller
{
    // Langkah 1: Form verifikasi nomor HP
    public function index()
    {
        return view('user.riwayat.index');
    }

    // Langkah 2: Proses cek nomor HP → tampilkan form PIN
    // Langkah 2: Proses cek nomor HP → redirect ke form PIN
    public function cekHp(Request $request)
    {
        $request->validate([
            'no_hp' => 'required|string|max:15',
        ], [
            'no_hp.required' => 'Nomor HP wajib diisi.',
        ]);

        $pasien = Pasien::where('no_hp', $request->no_hp)->first();

        if (!$pasien) {
            return redirect()->route('user.riwayat.index')
                ->with('error', 'Nomor HP tidak ditemukan. Pastikan Anda sudah pernah melakukan diagnosa.');
        }

        // Simpan no_hp ke session
        session(['riwayat_no_hp' => $request->no_hp]);

        return redirect()->route('user.riwayat.pin');
    }

    // Langkah 3: Tampilkan form PIN (GET)
    public function formPin()
    {
        $noHp = session('riwayat_no_hp');

        if (!$noHp) {
            return redirect()->route('user.riwayat.index')
                ->with('error', 'Sesi habis. Silakan ulangi.');
        }

        $pasien = Pasien::where('no_hp', $noHp)->firstOrFail();

        return view('user.riwayat.verifikasi-pin', compact('pasien'));
    }

    // Langkah 4: Proses verifikasi PIN (POST)
    public function verifikasiPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits_between:4,6',
        ], [
            'pin.required'       => 'PIN wajib diisi.',
            'pin.digits_between' => 'PIN harus 4 sampai 6 digit.',
        ]);

        $noHp = session('riwayat_no_hp');

        if (!$noHp) {
            return redirect()->route('user.riwayat.index')
                ->with('error', 'Sesi habis. Silakan ulangi.');
        }

        $pasien = Pasien::where('no_hp', $noHp)->first();

        if (!$pasien) {
            return redirect()->route('user.riwayat.index')
                ->with('error', 'Data tidak ditemukan.');
        }

        // Cek lockout
        if ($pasien->isPinLocked()) {
            $sisa = $pasien->pinLockRemainingSeconds();
            return redirect()->route('user.riwayat.pin')
                ->with('error', "Akun dikunci. Coba lagi dalam {$sisa} detik.");
        }

        // Verifikasi PIN
        if (!Hash::check($request->pin, $pasien->pin)) {
            $pasien->increment('pin_attempts');
            $pasien->refresh();

            if ($pasien->pin_attempts >= 3) {
                $pasien->update([
                    'pin_locked_until' => now()->addMinutes(5),
                    'pin_attempts'     => 0,
                ]);
                return redirect()->route('user.riwayat.pin')
                    ->with('error', 'PIN salah 3 kali. Akun dikunci selama 5 menit.');
            }

            $sisa = 3 - $pasien->pin_attempts;
            return redirect()->route('user.riwayat.pin')
                ->with('error', "PIN salah. Sisa percobaan: {$sisa} kali.");
        }

        // PIN benar — reset attempts
        $pasien->update([
            'pin_attempts'     => 0,
            'pin_locked_until' => null,
        ]);

        session(['riwayat_pasien_id' => $pasien->id]);
        session()->forget('riwayat_no_hp');

        return redirect()->route('user.riwayat.daftar');
    }

    // Daftar riwayat diagnosa
    public function daftar()
    {
        $pasienId = session('riwayat_pasien_id');

        if (!$pasienId) {
            return redirect()->route('user.riwayat.index')
                ->with('error', 'Silakan verifikasi PIN terlebih dahulu.');
        }

        $pasien  = Pasien::findOrFail($pasienId);
        $riwayat = HasilDiagnosa::with('penyakit')
            ->where('pasien_id', $pasienId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.riwayat.daftar', compact('pasien', 'riwayat'));
    }

    // Detail satu riwayat
    public function detail($id)
    {
        $pasienId = session('riwayat_pasien_id');

        if (!$pasienId) {
            return redirect()->route('user.riwayat.index')
                ->with('error', 'Silakan verifikasi PIN terlebih dahulu.');
        }

        $hasil = HasilDiagnosa::with([
            'penyakit',
            'pasien',
            'detailDiagnosa.gejala',
        ])->where('pasien_id', $pasienId)
            ->findOrFail($id);

        $label = CertaintyFactorService::labelKeyakinan($hasil->cf_hasil);
        $warna = CertaintyFactorService::warnaKeyakinan($hasil->cf_hasil);

        return view('user.riwayat.detail', compact('hasil', 'label', 'warna'));
    }

    // Download PDF dari riwayat
    public function downloadPdf($id)
    {
        $pasienId = session('riwayat_pasien_id');

        if (!$pasienId) {
            return redirect()->route('user.riwayat.index')
                ->with('error', 'Silakan verifikasi PIN terlebih dahulu.');
        }

        $hasil = HasilDiagnosa::with([
            'penyakit',
            'pasien',
            'detailDiagnosa.gejala',
        ])->where('pasien_id', $pasienId)
            ->findOrFail($id);

        $label = CertaintyFactorService::labelKeyakinan($hasil->cf_hasil);
        $warna = CertaintyFactorService::warnaKeyakinan($hasil->cf_hasil);

        $pdf = Pdf::loadView('user.hasil.pdf', compact('hasil', 'label', 'warna'))
            ->setPaper('A4', 'portrait');

        $namaFile = 'riwayat-diagnosa-' . $hasil->pasien->nama . '-' .
            $hasil->created_at->format('Ymd') . '.pdf';

        return $pdf->download($namaFile);
    }

    // Form reset PIN
    public function formResetPin()
    {
        return view('user.riwayat.reset-pin');
    }

    // Proses reset PIN — hapus semua riwayat
    public function resetPin(Request $request)
    {
        $request->validate([
            'no_hp'       => 'required|string|max:15',
            'pin_baru'    => 'required|digits_between:4,6',
            'konfirmasi'  => 'required|same:pin_baru',
            'setuju'      => 'required|accepted',
        ], [
            'no_hp.required'      => 'Nomor HP wajib diisi.',
            'pin_baru.required'   => 'PIN baru wajib diisi.',
            'pin_baru.digits_between' => 'PIN harus 4 sampai 6 digit.',
            'konfirmasi.required' => 'Konfirmasi PIN wajib diisi.',
            'konfirmasi.same'     => 'Konfirmasi PIN tidak cocok.',
            'setuju.accepted'     => 'Anda harus menyetujui penghapusan data.',
        ]);

        $pasien = Pasien::where('no_hp', $request->no_hp)->first();

        if (!$pasien) {
            return back()->withInput()
                ->with('error', 'Nomor HP tidak ditemukan.');
        }

        // Hapus semua riwayat diagnosa
        HasilDiagnosa::where('pasien_id', $pasien->id)->delete();

        // Update PIN baru
        $pasien->update([
            'pin'              => Hash::make($request->pin_baru),
            'pin_attempts'     => 0,
            'pin_locked_until' => null,
        ]);

        // Bersihkan session riwayat
        session()->forget(['riwayat_pasien_id', 'riwayat_no_hp']);

        return redirect()->route('user.riwayat.index')
            ->with('success', 'PIN berhasil direset. Seluruh riwayat diagnosa telah dihapus.');
    }
}
