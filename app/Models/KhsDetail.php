<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhsDetail extends Model
{
    protected $table = 'khs_details';

    protected $fillable = [
        'khs_id',
        'mata_kuliah_id',
        'nilai',
        'grade',
    ];

    public function khs()
    {
        return $this->belongsTo(Khs::class, 'khs_id'); // FIXED
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }
}
