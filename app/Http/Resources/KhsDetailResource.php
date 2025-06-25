<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KhsDetailResource extends JsonResource
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
            'nilai' => $this->nilai,
            'grade' => $this->grade,
            // Sertakan informasi mata kuliah yang terkait
            'mata_kuliah' => new MataKuliahResource($this->whenLoaded('mataKuliah')),
        ];
    }
}
