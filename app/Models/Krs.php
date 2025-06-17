<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    use HasFactory;

    protected $table = 'krs';
    protected $primaryKey = 'id_krs';

    protected $fillable = [
        'id_mahasiswa',
        'semester',
        'tahun_akademik',
        'status_krs',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    public function details()
    {
        return $this->hasMany(KrsDetail::class, 'krs_id');
    }
}

