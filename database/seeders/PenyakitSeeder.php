<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenyakitSeeder extends Seeder
{
    public function run(): void
    {
        $penyakit = [
            [
                'kode'       => 'P001',
                'nama'       => 'Katarak',
                'deskripsi'  => 'Katarak adalah kondisi di mana lensa mata menjadi keruh sehingga menghalangi cahaya masuk ke retina. Kondisi ini menyebabkan penglihatan menjadi kabur, buram, atau seperti melihat melalui kaca yang berkabut.',
                'solusi'     => 'Segera konsultasikan dengan dokter spesialis mata. Penanganan utama katarak adalah operasi pengangkatan lensa yang keruh dan menggantinya dengan lensa buatan (IOL). Hindari paparan sinar UV berlebihan dan gunakan kacamata hitam saat beraktivitas di luar ruangan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode'       => 'P002',
                'nama'       => 'Glaukoma',
                'deskripsi'  => 'Glaukoma adalah kelompok penyakit mata yang ditandai dengan kerusakan saraf optik, seringkali akibat tekanan cairan di dalam mata (tekanan intraokular) yang terlalu tinggi. Glaukoma dapat menyebabkan kehilangan penglihatan permanen jika tidak ditangani.',
                'solusi'     => 'Segera konsultasikan dengan dokter spesialis mata. Penanganan glaukoma meliputi obat tetes mata untuk menurunkan tekanan intraokular, terapi laser, atau operasi. Pemeriksaan mata rutin sangat penting untuk deteksi dini.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode'       => 'P003',
                'nama'       => 'Konjungtivitis',
                'deskripsi'  => 'Konjungtivitis atau mata merah adalah peradangan pada konjungtiva (lapisan bening yang melapisi bagian putih mata dan kelopak mata bagian dalam). Penyebabnya dapat berupa infeksi bakteri, virus, atau reaksi alergi.',
                'solusi'     => 'Konsultasikan dengan dokter untuk menentukan penyebab dan pengobatan yang tepat. Jaga kebersihan mata, hindari menyentuh mata dengan tangan kotor, gunakan obat tetes mata sesuai anjuran dokter, dan hindari penggunaan lensa kontak sementara waktu.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode'       => 'P004',
                'nama'       => 'Retinopati Diabetik',
                'deskripsi'  => 'Retinopati Diabetik adalah komplikasi diabetes yang mempengaruhi pembuluh darah di retina mata. Kondisi ini dapat menyebabkan gangguan penglihatan hingga kebutaan jika tidak dikelola dengan baik.',
                'solusi'     => 'Kontrol kadar gula darah secara ketat dan rutin. Segera konsultasikan dengan dokter spesialis mata dan dokter penyakit dalam. Pemeriksaan mata minimal setahun sekali bagi penderita diabetes sangat dianjurkan. Penanganan dapat berupa injeksi anti-VEGF, terapi laser, atau operasi vitrektomi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('penyakit')->insert($penyakit);
    }
}