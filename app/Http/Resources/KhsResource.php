<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KhsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            // 'id_khs' => $this->id,
            // 'semester' => $this->semester,
            // 'tahun_akademik' => $this->tahun_akademik,
            // 'ip' => $this->ip,
            // 'ips' => $this->ips,
            // 'ipk' => $this->ipk,
            // 'mata_kuliah' => $this->details->map(function ($detail) {
            //     return [
            //         'mata_kuliah' => $detail->mataKuliah->nama_matkul,
            //         'sks' => $detail->mataKuliah->sks,
            //         'nilai' => $detail->nilai,
            //         'grade' => $detail->grade,
            //     ];
            // }),

            'id' => $this->id,
            'semester' => $this->semester,
            'tahun_akademik' => $this->tahun_akademik,
            'ips' => $this->ips, // Indeks Prestasi Semester
            'ipk' => $this->ipk, // Indeks Prestasi Kumulatif
            'sks_semester' => $this->details->sum('mataKuliah.sks'), // Menghitung SKS di semester ini
            // Sertakan detail KHS
            'details' => KhsDetailResource::collection($this->whenLoaded('details')),
        ];
    }
}
