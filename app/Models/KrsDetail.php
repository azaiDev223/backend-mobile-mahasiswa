<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class KrsDetail extends Model
{
    use HasFactory;

    protected $table = 'krs_detail';
    // public $timestamps = false;

    protected $fillable = [
        'krs_id',
        'jadwal_kuliah_id',
    ];

    public function krs(): BelongsTo
    {
        return $this->belongsTo(Krs::class, 'krs_id');
    }

    /**
     * Mendefinisikan relasi ke JadwalKuliah.
     */
    public function jadwalKuliah(): BelongsTo
    {
        return $this->belongsTo(JadwalKuliah::class, 'jadwal_kuliah_id');
    }
}

