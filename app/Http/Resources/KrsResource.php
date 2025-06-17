<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KrsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_krs' => $this->id,
            'semester' => $this->semester,
            'tahun_akademik' => $this->tahun_akademik,
            'status_krs' => $this->status_krs,
            'mata_kuliah' => $this->details->map(function ($detail) {
                return [
                    'jadwal_id' => $detail->jadwalKuliah->id,
                    'mata_kuliah' => $detail->jadwalKuliah->kelas->mataKuliah->nama_matkul,
                    'dosen' => $detail->jadwalKuliah->kelas->dosen->nama_lengkap,
                    'hari' => $detail->jadwalKuliah->hari,
                    'jam_mulai' => $detail->jadwalKuliah->jam_mulai,
                    'jam_selesai' => $detail->jadwalKuliah->jam_selesai,
                    'ruangan' => $detail->jadwalKuliah->ruangan,
                ];
            }),
        ];
    }
}

