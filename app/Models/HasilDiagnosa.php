<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilDiagnosa extends Model
{
    protected $table = 'hasil_diagnosa';

    protected $fillable = [
        'pasien_id',
        'penyakit_id',
        'cf_hasil',
        'cf_persen',
    ];

    protected $casts = [
        'cf_hasil' => 'float',
        'cf_persen' => 'integer',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class);
    }

    public function detailDiagnosa()
    {
        return $this->hasMany(DetailDiagnosa::class);
    }
}