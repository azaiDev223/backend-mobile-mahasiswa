<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'tanggal_bimbingan',
        'topik',
        'catatan',
        'status',
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    // Relasi ke Dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}
