<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';

    protected $fillable = [
        'nama',
        'no_hp',
        'pin',
        'pin_attempts',
        'pin_locked_until',
    ];

    protected $hidden = [
        'pin',
    ];

    protected $casts = [
        'pin_locked_until' => 'datetime',
        'pin_attempts'     => 'integer',
    ];

    public function hasilDiagnosa()
    {
        return $this->hasMany(HasilDiagnosa::class);
    }

    // Cek apakah PIN sedang terkunci
    public function isPinLocked(): bool
    {
        if ($this->pin_locked_until === null) {
            return false;
        }

        return now()->lt($this->pin_locked_until);
    }

    // Sisa waktu lockout dalam detik
    public function pinLockRemainingSeconds(): int
    {
        if (!$this->isPinLocked()) {
            return 0;
        }

        return (int) now()->diffInSeconds($this->pin_locked_until);
    }
}