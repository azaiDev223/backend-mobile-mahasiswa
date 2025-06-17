<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DosenResource extends JsonResource
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
    'nip' => $this->nip,
    'nama' => $this->nama,
    'jenis_kelamin' => $this->jenis_kelamin,
    'email' => $this->email,
    'foto' => $this->foto,
    'foto_url' => $this->foto ? asset('storage/foto-dosen/' . $this->foto) : null,
    'no_hp' => $this->no_hp,
    'tanggal_lahir' => $this->tanggal_lahir,
    'program_studi_id' => $this->program_studi_id,
    'created_at' => $this->created_at->toDateTimeString(),
    'updated_at' => $this->updated_at->toDateTimeString(),
];

    }
}
