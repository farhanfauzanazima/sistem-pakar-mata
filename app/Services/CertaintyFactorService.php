<?php

namespace App\Services;

use App\Models\Aturan;
use App\Models\Penyakit;

class CertaintyFactorService
{
    /**
     * Nilai CF User berdasarkan frekuensi yang dipilih.
     * Mengacu pada skala yang umum digunakan pada sistem pakar CF.
     */
    const CF_USER = [
        'sangat_sering' => 1.0,
        'sering'        => 0.6,
        'jarang'        => 0.3,
        'tidak_pernah'  => 0.0,
    ];

    /**
     * Proses utama: Forward Chaining + Certainty Factor.
     *
     * @param  array  $inputGejala  Format: ['gejala_id' => 'frekuensi', ...]
     *                              Contoh: [1 => 'sangat_sering', 3 => 'jarang']
     * @return array  Hasil diagnosa terurut dari CF tertinggi
     */
    public function diagnosa(array $inputGejala): array
    {
        // Hanya proses gejala yang tidak "tidak_pernah"
        $gejalaAktif = array_filter(
            $inputGejala,
            fn($frekuensi) => $frekuensi !== 'tidak_pernah' && $frekuensi !== null
        );

        if (empty($gejalaAktif)) {
            return [];
        }

        // Ambil semua aturan yang gejala_id-nya ada di input aktif
        $aturanList = Aturan::with(['penyakit', 'gejala'])
            ->whereIn('gejala_id', array_keys($gejalaAktif))
            ->get();

        if ($aturanList->isEmpty()) {
            return [];
        }

        // Kelompokkan aturan per penyakit
        $aturanPerPenyakit = $aturanList->groupBy('penyakit_id');

        $hasil = [];

        foreach ($aturanPerPenyakit as $penyakitId => $aturanGroup) {

            // Forward Chaining:
            // Hanya proses penyakit yang memiliki minimal 1 gejala cocok
            $cfValues = [];

            foreach ($aturanGroup as $aturan) {
                $gejalaId  = $aturan->gejala_id;
                $frekuensi = $gejalaAktif[$gejalaId] ?? null;

                if ($frekuensi === null) {
                    continue;
                }

                $cfUser  = self::CF_USER[$frekuensi] ?? 0;
                $cfPakar = (float) $aturan->cf_pakar;

                // CF per gejala = CF User × CF Pakar
                $cfGejala = $cfUser * $cfPakar;

                if ($cfGejala > 0) {
                    $cfValues[] = [
                        'gejala_id'  => $gejalaId,
                        'frekuensi'  => $frekuensi,
                        'cf_user'    => $cfUser,
                        'cf_pakar'   => $cfPakar,
                        'cf_gejala'  => $cfGejala,
                    ];
                }
            }

            if (empty($cfValues)) {
                continue;
            }

            // Kombinasi CF menggunakan rumus:
            // CFcombine = CF1 + CF2 × (1 - CF1)
            $cfKombinasi = $this->kombinasiCF(
                array_column($cfValues, 'cf_gejala')
            );

            $hasil[$penyakitId] = [
                'penyakit'    => $aturanGroup->first()->penyakit,
                'cf_hasil'    => round($cfKombinasi, 4),
                'cf_persen'   => (int) round($cfKombinasi * 100),
                'detail'      => $cfValues,
            ];
        }

        // Urutkan dari CF tertinggi ke terendah
        uasort($hasil, fn($a, $b) => $b['cf_hasil'] <=> $a['cf_hasil']);

        return $hasil;
    }

    /**
     * Menggabungkan array nilai CF menggunakan rumus kombinasi bertahap.
     * CFcombine = CF1 + CF2 × (1 - CF1)
     *
     * @param  array  $cfArray  Array nilai CF (float)
     * @return float  Nilai CF gabungan
     */
    public function kombinasiCF(array $cfArray): float
    {
        if (empty($cfArray)) {
            return 0.0;
        }

        // Mulai dari CF pertama
        $cfKombinasi = array_shift($cfArray);

        foreach ($cfArray as $cf) {
            $cfKombinasi = $cfKombinasi + ($cf * (1 - $cfKombinasi));
        }

        // Pastikan tidak melebihi 1
        return min(1.0, max(0.0, $cfKombinasi));
    }

    /**
     * Ambil label tingkat keyakinan berdasarkan nilai CF.
     */
    public static function labelKeyakinan(float $cf): string
    {
        $persen = $cf * 100;

        return match(true) {
            $persen >= 80 => 'Sangat Tinggi',
            $persen >= 60 => 'Tinggi',
            $persen >= 40 => 'Sedang',
            $persen >= 20 => 'Rendah',
            default       => 'Sangat Rendah',
        };
    }

    /**
     * Ambil warna Bootstrap berdasarkan nilai CF.
     */
    public static function warnaKeyakinan(float $cf): string
    {
        $persen = $cf * 100;

        return match(true) {
            $persen >= 80 => 'success',
            $persen >= 60 => 'primary',
            $persen >= 40 => 'warning',
            default       => 'danger',
        };
    }

    /**
     * Kembalikan semua gejala yang tersedia untuk ditampilkan di form diagnosa.
     */
    public function getSemuaGejala(): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\Gejala::orderBy('kode')->get();
    }

    /**
     * Daftar pilihan frekuensi beserta label dan nilai CF User-nya.
     */
    public static function pilihanFrekuensi(): array
    {
        return [
            'sangat_sering' => [
                'label'   => 'Sangat Sering',
                'cf_user' => 1.0,
                'warna'   => 'danger',
            ],
            'sering' => [
                'label'   => 'Sering',
                'cf_user' => 0.6,
                'warna'   => 'warning',
            ],
            'jarang' => [
                'label'   => 'Jarang',
                'cf_user' => 0.3,
                'warna'   => 'info',
            ],
            'tidak_pernah' => [
                'label'   => 'Tidak Pernah',
                'cf_user' => 0.0,
                'warna'   => 'secondary',
            ],
        ];
    }
}