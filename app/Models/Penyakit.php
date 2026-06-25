<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyakit extends Model
{
    protected $table = 'penyakit';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'solusi',
    ];

    public function aturan()
    {
        return $this->hasMany(Aturan::class);
    }

    // hasilDiagnosa() akan ditambahkan kembali di Sesi 9
}