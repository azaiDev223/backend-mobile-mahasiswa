<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class Mahasiswa extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'mahasiswas';

    protected $fillable = [
        'nim',
        'nama',
        'jenis_kelamin',
        'email',
        'password',
        'foto',
        'no_hp',
        'alamat',
        'tanggal_lahir',
        'angkatan',
        'program_studi_id',
        'dosen_id',
    ];

    protected $hidden = ['password'];

    // ✅ Hash otomatis hanya di model
    protected static function booted()
    {
        static::creating(function ($mahasiswa) {
            $mahasiswa->password = Hash::make($mahasiswa->password);
        });

        static::updating(function ($mahasiswa) {
            if ($mahasiswa->isDirty('password')) {
                $mahasiswa->password = Hash::make($mahasiswa->password);
            }
        });
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function krs()
    {
        return $this->hasMany(Krs::class, 'mahasiswa_id');
    }
}


