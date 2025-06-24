<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MataKuliahResource extends JsonResource
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
        'kode_matkul' => $this->kode_matkul,
        'nama_matkul' => $this->nama_matkul,
        'sks' => $this->sks,
        'semester' => $this->semester, // <-- TAMBAHKAN BARIS INI
        'program_studi_id' => $this->program_studi_id,
    ];
}

}
