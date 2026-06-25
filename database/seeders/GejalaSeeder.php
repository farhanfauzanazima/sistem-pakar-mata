<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GejalaSeeder extends Seeder
{
    public function run(): void
    {
        $gejala = [
            ['kode' => 'G001', 'nama' => 'Pandangan kabur',                    'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'G002', 'nama' => 'Sensitif terhadap cahaya',           'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'G003', 'nama' => 'Penglihatan ganda',                  'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'G004', 'nama' => 'Nyeri mata',                         'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'G005', 'nama' => 'Penglihatan terowongan',             'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'G006', 'nama' => 'Mata merah',                         'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'G007', 'nama' => 'Mata berair',                        'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'G008', 'nama' => 'Mata gatal',                         'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'G009', 'nama' => 'Keluarnya cairan mata',              'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'G010', 'nama' => 'Kehilangan penglihatan tiba-tiba',   'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'G011', 'nama' => 'Penglihatan berbintik',              'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'G012', 'nama' => 'Melihat lingkaran cahaya',           'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('gejala')->insert($gejala);
    }
}