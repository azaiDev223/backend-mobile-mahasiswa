<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class Dosen extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'nip',
        'nama',
        'jenis_kelamin',
        'email',
        'password',
        'foto',
        'no_hp',
        'tanggal_lahir',
        'program_studi_id',
    ];

    // Mutator: setiap kali password diset, akan otomatis di-hash
    protected static function booted()
    {
        static::creating(function ($dosen) {
            $dosen->password = Hash::make($dosen->password);
        });

        static::updating(function ($dosen) {
            if ($dosen->isDirty('password')) {
                $dosen->password = Hash::make($dosen->password);
            }
        });
    }
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function mahasiswas()
{
    return $this->hasMany(Mahasiswa::class, 'dosen_id');
}

public function kelas()
{
    return $this->hasMany(Kelas::class);
}

}
