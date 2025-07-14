<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengumumanResource extends JsonResource
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
            'judul' => $this->judul,
            'kategori' => $this->kategori,
            'isi' => $this->isi,
            'foto' => $this->foto, // Menambahkan foto jika ada
            'foto_url' => $this->foto ? asset('storage/' . $this->foto) : null, // URL lengkap untuk foto
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
