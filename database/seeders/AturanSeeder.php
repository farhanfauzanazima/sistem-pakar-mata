<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AturanSeeder extends Seeder
{
    public function run(): void
    {
        // ID penyakit: P001=1, P002=2, P003=3, P004=4
        // ID gejala:   G001=1 s/d G012=12
        $aturan = [
            // Katarak (penyakit_id = 1)
            ['penyakit_id' => 1, 'gejala_id' => 1,  'cf_pakar' => 0.80], // Pandangan kabur
            ['penyakit_id' => 1, 'gejala_id' => 2,  'cf_pakar' => 0.60], // Sensitif terhadap cahaya
            ['penyakit_id' => 1, 'gejala_id' => 3,  'cf_pakar' => 0.70], // Penglihatan ganda

            // Glaukoma (penyakit_id = 2)
            ['penyakit_id' => 2, 'gejala_id' => 4,  'cf_pakar' => 0.90], // Nyeri mata
            ['penyakit_id' => 2, 'gejala_id' => 5,  'cf_pakar' => 0.85], // Penglihatan terowongan
            ['penyakit_id' => 2, 'gejala_id' => 6,  'cf_pakar' => 0.70], // Mata merah
            ['penyakit_id' => 2, 'gejala_id' => 12, 'cf_pakar' => 0.75], // Melihat lingkaran cahaya

            // Konjungtivitis (penyakit_id = 3)
            ['penyakit_id' => 3, 'gejala_id' => 7,  'cf_pakar' => 0.75], // Mata berair
            ['penyakit_id' => 3, 'gejala_id' => 8,  'cf_pakar' => 0.80], // Mata gatal
            ['penyakit_id' => 3, 'gejala_id' => 9,  'cf_pakar' => 0.70], // Keluarnya cairan mata
            ['penyakit_id' => 3, 'gejala_id' => 6,  'cf_pakar' => 0.65], // Mata merah

            // Retinopati Diabetik (penyakit_id = 4)
            ['penyakit_id' => 4, 'gejala_id' => 1,  'cf_pakar' => 0.90], // Penglihatan kabur
            ['penyakit_id' => 4, 'gejala_id' => 10, 'cf_pakar' => 0.85], // Kehilangan penglihatan tiba-tiba
            ['penyakit_id' => 4, 'gejala_id' => 11, 'cf_pakar' => 0.80], // Penglihatan berbintik
        ];

        foreach ($aturan as $item) {
            DB::table('aturan')->insert([
                'penyakit_id' => $item['penyakit_id'],
                'gejala_id'   => $item['gejala_id'],
                'cf_pakar'    => $item['cf_pakar'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}