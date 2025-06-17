<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Khs extends Model
{
    protected $table = 'khs';
    protected $primaryKey = 'id_khs';

    protected $fillable = [
        'mahasiswa_id',
        'semester',
        'tahun_akademik',
        'ip',
        'ips',
        'ipk',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function details()
    {
        return $this->hasMany(KhsDetail::class, 'khs_id');
    }
}

