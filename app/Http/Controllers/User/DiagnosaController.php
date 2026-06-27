<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\HasilDiagnosa;
use App\Models\DetailDiagnosa;
use App\Services\CertaintyFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DiagnosaController extends Controller
{
    protected CertaintyFactorService $cfService;

    public function __construct(CertaintyFactorService $cfService)
    {
        $this->cfService = $cfService;
    }

    // Langkah 1: Form data diri & PIN
    public function form()
    {
        return view('user.diagnosa.form');
    }

    // Langkah 1 proses: Verifikasi / daftarkan pasien
    public function prosesForm(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:100',
            'no_hp' => 'required|string|max:15|regex:/^[0-9]+$/',
            'pin'   => 'required|digits_between:4,6',
        ], [
            'nama.required'  => 'Nama lengkap wajib diisi.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.regex'    => 'Nomor HP hanya boleh berisi angka.',
            'pin.required'   => 'PIN wajib diisi.',
            'pin.digits_between' => 'PIN harus 4 sampai 6 digit angka.',
        ]);

        $pasien = Pasien::where('no_hp', $request->no_hp)->first();

        if ($pasien) {
            // Pasien sudah terdaftar — verifikasi PIN
            if ($pasien->isPinLocked()) {
                $sisa = $pasien->pinLockRemainingSeconds();
                return back()->withInput()->with(
                    'error',
                    "Terlalu banyak percobaan PIN. Coba lagi dalam {$sisa} detik."
                );
            }

            if (!Hash::check($request->pin, $pasien->pin)) {
                $pasien->increment('pin_attempts');

                if ($pasien->pin_attempts >= 3) {
                    $pasien->update([
                        'pin_locked_until' => now()->addMinutes(5),
                        'pin_attempts'     => 0,
                    ]);
                    return back()->withInput()->with(
                        'error',
                        'PIN salah 3 kali. Akun dikunci selama 5 menit.'
                    );
                }

                $sisa = 3 - $pasien->pin_attempts;
                return back()->withInput()->with(
                    'error',
                    "PIN salah. Sisa percobaan: {$sisa} kali."
                );
            }

            // PIN benar — reset attempts
            $pasien->update([
                'nama'         => $request->nama,
                'pin_attempts' => 0,
                'pin_locked_until' => null,
            ]);
        } else {
            // Pasien baru — daftarkan
            $request->validate([
                'pin_konfirmasi' => 'required|same:pin',
            ], [
                'pin_konfirmasi.required' => 'Konfirmasi PIN wajib diisi.',
                'pin_konfirmasi.same'     => 'Konfirmasi PIN tidak cocok.',
            ]);

            $pasien = Pasien::create([
                'nama'  => $request->nama,
                'no_hp' => $request->no_hp,
                'pin'   => Hash::make($request->pin),
            ]);
        }

        // Simpan pasien_id ke session
        session(['pasien_id' => $pasien->id]);

        return redirect()->route('user.diagnosa.gejala');
    }

    // Langkah 2: Pilih gejala
    public function gejala()
    {
        if (!session('pasien_id')) {
            return redirect()->route('user.diagnosa.form')
                ->with('error', 'Silakan isi data diri terlebih dahulu.');
        }

        $gejala    = $this->cfService->getSemuaGejala();
        $frekuensi = CertaintyFactorService::pilihanFrekuensi();
        $pasien    = Pasien::find(session('pasien_id'));

        return view('user.diagnosa.gejala', compact('gejala', 'frekuensi', 'pasien'));
    }

    // Langkah 2 proses: Hitung CF dan simpan hasil
    public function prosesGejala(Request $request)
    {
        if (!session('pasien_id')) {
            return redirect()->route('user.diagnosa.form')
                ->with('error', 'Sesi habis. Silakan mulai ulang.');
        }

        $request->validate([
            'gejala' => 'required|array|min:1',
        ], [
            'gejala.required' => 'Pilih minimal satu gejala.',
            'gejala.min'      => 'Pilih minimal satu gejala.',
        ]);

        $inputGejala = $request->input('gejala', []);

        // Pastikan ada yang bukan "tidak_pernah"
        $adaAktif = collect($inputGejala)
            ->filter(fn($f) => $f !== 'tidak_pernah')
            ->isNotEmpty();

        if (!$adaAktif) {
            return back()->with(
                'error',
                'Pilih minimal satu gejala dengan frekuensi selain "Tidak Pernah".'
            );
        }

        // Jalankan engine CF
        $hasilDiagnosa = $this->cfService->diagnosa($inputGejala);

        if (empty($hasilDiagnosa)) {
            return back()->with(
                'error',
                'Tidak ada penyakit yang cocok dengan gejala yang dipilih.'
            );
        }

        $pasienId     = session('pasien_id');
        $hasilTeratas = reset($hasilDiagnosa);

        // Simpan hasil diagnosa ke database
        $hasil = HasilDiagnosa::create([
            'pasien_id'   => $pasienId,
            'penyakit_id' => $hasilTeratas['penyakit']->id,
            'cf_hasil'    => $hasilTeratas['cf_hasil'],
            'cf_persen'   => $hasilTeratas['cf_persen'],
        ]);

        // ── PERBAIKAN: Simpan SEMUA gejala aktif yang dipilih user ──
        $cfUserMap = \App\Services\CertaintyFactorService::CF_USER;

        foreach ($inputGejala as $gejalaId => $frekuensi) {
            // Lewati yang tidak pernah
            if ($frekuensi === 'tidak_pernah' || $frekuensi === null) {
                continue;
            }

            \App\Models\DetailDiagnosa::create([
                'hasil_diagnosa_id' => $hasil->id,
                'gejala_id'         => (int) $gejalaId,
                'frekuensi'         => $frekuensi,
                'cf_user'           => $cfUserMap[$frekuensi] ?? 0,
            ]);
        }

        session(['hasil_diagnosa_id' => $hasil->id]);

        return redirect()->route('user.hasil.show');
    }

    // Tambahkan method ini di DiagnosaController
    public function cekHp(Request $request)
    {
        $terdaftar = Pasien::where('no_hp', $request->no_hp)->exists();
        return response()->json(['terdaftar' => $terdaftar]);
    }
}
