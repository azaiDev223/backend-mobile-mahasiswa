<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'judul',
        'kategori',
        'isi',
        'foto', // Menambahkan kolom foto ke dalam fillable
    ];
}
