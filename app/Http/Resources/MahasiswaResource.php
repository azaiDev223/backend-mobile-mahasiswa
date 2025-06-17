<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MahasiswaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nim' => $this->nim,
            'nama' => $this->nama,
            'jenis_kelamin' => $this->jenis_kelamin,
            'email' => $this->email,
            'foto' => $this->foto,
            'foto_url' => $this->foto ? asset('storage/' . $this->foto) : null,
            'no_hp' => $this->no_hp,
            'alamat' => $this->alamat,
            'tanggal_lahir' => $this->tanggal_lahir,
            'angkatan' => $this->angkatan,
            'program_studi_id' => $this->program_studi_id,
            'dosen_id' => $this->dosen_id,

            // 'program_studi' => [
            //     'id' => $this->program_studi_id,
            //     'nama' => $this->programStudi->nama_prodi ?? null,
            // ],
            // 'dosen_pembimbing' => [
            //     'id' => $this->dosen_id,
            //     'nama' => $this->dosen->nama ?? null,
            // ],
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
