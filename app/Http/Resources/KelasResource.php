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
            
            // --- BAGIAN YANG DIPERBAIKI ---
            // Gunakan Resource lain untuk menangani data relasi.
            // 'whenLoaded' memastikan data hanya disertakan jika sudah di-load
            // oleh controller untuk efisiensi.
            'dosen' => new DosenResource($this->whenLoaded('dosen')),
            'mata_kuliah' => new MataKuliahResource($this->whenLoaded('mataKuliah')),
            
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
