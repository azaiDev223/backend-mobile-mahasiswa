<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BimbinganResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mahasiswa' => [
                'id' => $this->mahasiswa_id,
                'nama' => $this->mahasiswa->nama ?? null,
            ],
            'dosen' => [
                'id' => $this->dosen_id,
                'nama' => $this->dosen->nama ?? null,
            ],
            'tanggal_bimbingan' => $this->tanggal_bimbingan,
            'topik' => $this->topik,
            'catatan' => $this->catatan,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
