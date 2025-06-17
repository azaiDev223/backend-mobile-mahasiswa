<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KrsDetail extends Model
{
    use HasFactory;

    protected $table = 'krs_detail';

    protected $fillable = [
        'krs_id',
        'jadwal_kuliah_id',
    ];

    public function krs()
    {
        return $this->belongsTo(Krs::class, 'krs_id');
    }

    public function jadwalKuliah()
    {
        return $this->belongsTo(JadwalKuliah::class, 'jadwal_kuliah_id');
    }
}

