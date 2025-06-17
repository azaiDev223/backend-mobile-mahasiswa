<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KelasResource extends JsonResource
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
            'nama_kelas' => $this->nama_kelas,
            'dosen' => [
                'id' => $this->dosen_id,
                'nama' => $this->dosen->nama ?? null,
            ],
            'mata_kuliah' => [
                'id' => $this->matakuliah_id,
                'nama' => $this->matakuliah->nama_matkul ?? null,
                'sks' => $this->matakuliah->sks ?? null,
            ],
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
