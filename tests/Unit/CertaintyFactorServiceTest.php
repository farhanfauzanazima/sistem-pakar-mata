<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\CertaintyFactorService;

class CertaintyFactorServiceTest extends TestCase
{
    protected CertaintyFactorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CertaintyFactorService();
    }

    public function test_kombinasi_dua_cf_benar(): void
    {
        // CF1=0.8, CF2=0.6 → 0.8 + 0.6×(1-0.8) = 0.8 + 0.12 = 0.92
        $hasil = $this->service->kombinasiCF([0.8, 0.6]);
        $this->assertEqualsWithDelta(0.92, $hasil, 0.001);
    }

    public function test_kombinasi_tiga_cf_benar(): void
    {
        // CF12=0.92, CF3=0.7 → 0.92 + 0.7×(1-0.92) = 0.92+0.056 = 0.976
        $hasil = $this->service->kombinasiCF([0.8, 0.6, 0.7]);
        $this->assertEqualsWithDelta(0.976, $hasil, 0.001);
    }

    public function test_kombinasi_cf_tidak_melebihi_satu(): void
    {
        $hasil = $this->service->kombinasiCF([1.0, 1.0, 1.0]);
        $this->assertLessThanOrEqual(1.0, $hasil);
    }

    public function test_kombinasi_cf_array_kosong_mengembalikan_nol(): void
    {
        $hasil = $this->service->kombinasiCF([]);
        $this->assertEquals(0.0, $hasil);
    }

    public function test_cf_user_frekuensi_sangat_sering_adalah_satu(): void
    {
        $this->assertEquals(1.0, CertaintyFactorService::CF_USER['sangat_sering']);
    }

    public function test_label_keyakinan_benar(): void
    {
        $this->assertEquals('Sangat Tinggi', CertaintyFactorService::labelKeyakinan(0.85));
        $this->assertEquals('Tinggi',        CertaintyFactorService::labelKeyakinan(0.65));
        $this->assertEquals('Sedang',        CertaintyFactorService::labelKeyakinan(0.45));
        $this->assertEquals('Rendah',        CertaintyFactorService::labelKeyakinan(0.25));
        $this->assertEquals('Sangat Rendah', CertaintyFactorService::labelKeyakinan(0.10));
    }
}