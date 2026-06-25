<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gejala extends Model
{
    protected $table = 'gejala';

    protected $fillable = [
        'kode',
        'nama',
    ];

    public function aturan()
    {
        return $this->hasMany(Aturan::class);
    }
}