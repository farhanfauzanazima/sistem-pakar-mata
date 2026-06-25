<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailDiagnosa extends Model
{
    protected $table = 'detail_diagnosa';

    protected $fillable = [
        'hasil_diagnosa_id',
        'gejala_id',
        'frekuensi',
        'cf_user',
    ];

    protected $casts = [
        'cf_user' => 'float',
    ];

    public function hasilDiagnosa()
    {
        return $this->belongsTo(HasilDiagnosa::class);
    }

    public function gejala()
    {
        return $this->belongsTo(Gejala::class);
    }
}