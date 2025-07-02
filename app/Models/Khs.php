<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Khs extends Model
{
    protected $table = 'khs';
    // Hapus baris berikut karena tidak sesuai:
    // protected $primaryKey = 'id_khs';

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

    public function details(): HasMany
    {
        return $this->hasMany(KhsDetail::class, 'khs_id');
    }
}
