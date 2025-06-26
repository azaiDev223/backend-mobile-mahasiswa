<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_matkul',
        'nama_matkul',
        'sks',
        'semester',
        'program_studi_id',
    ];

    // Relasi ke Program Studi
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }

    // Relasi Many-to-Many ke KHS melalui tabel pivot khs_matakuliah
    public function khs()
    {
        return $this->belongsToMany(Khs::class, 'khs_matakuliah', 'mata_kuliah_id', 'khs_id')
                    ->withPivot('nilai', 'grade')
                    ->withTimestamps();
    }
    public function khsDetails()
    {
        return $this->hasMany(KhsDetail::class, 'mata_kuliah_id');
    }
}
