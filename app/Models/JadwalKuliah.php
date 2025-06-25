<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKuliah extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'tahun_akademik', // <-- DITAMBAHKAN
        'kuota',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    // public function mataKuliah()
    // {
    //     return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    //     // Asumsikan foreign key di jadwal_kuliahs adalah mata_kuliah_id
    // }
}
