<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'dosen_id',
        'matakuliah_id',
        'nama_kelas',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'matakuliah_id');
    }

    public function jadwalKuliahs()
{
    return $this->hasMany(JadwalKuliah::class);
}

}
