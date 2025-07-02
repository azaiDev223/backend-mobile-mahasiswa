<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    // --- TAMBAHKAN DI SINI ---
    /**
     * The accessors to append to the model's array form.
     * Atribut ini akan selalu ditambahkan saat model diubah menjadi JSON.
     * @var array
     */
    protected $appends = ['foto_url'];

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
    return $this->hasMany(Krs::class, 'id_mahasiswa');
}


    protected function fotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->foto ? asset('storage/' . $this->foto) : null,
        );
    }

    public function khs()
    {
        return $this->hasMany(Khs::class, 'mahasiswa_id');
    }

    public function messages()
    {
        return $this->morphMany(ChatMessage::class, 'sender');
    }
}
