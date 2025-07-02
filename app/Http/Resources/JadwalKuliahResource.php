<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JadwalKuliahResource extends JsonResource
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
        'hari' => $this->hari,
        'jam_mulai' => $this->jam_mulai,
        'jam_selesai' => $this->jam_selesai,
        'ruangan' => $this->ruangan,
        'tahun_akademik' => $this->tahun_akademik,
        'kuota' => $this->kuota,

        // Tambahkan field ini agar bisa langsung diakses dari Flutter
        'mata_kuliah_id' => $this->kelas->mataKuliah->id ?? null,

        'kelas' => [
            'id' => $this->kelas->id,
            'nama_kelas' => $this->kelas->nama_kelas,

            'mata_kuliah' => [
                'id' => $this->kelas->mataKuliah->id,
                'kode_matkul' => $this->kelas->mataKuliah->kode_matkul,
                'nama_matkul' => $this->kelas->mataKuliah->nama_matkul,
                'sks' => $this->kelas->mataKuliah->sks,
                'semester' => $this->kelas->mataKuliah->semester,
                'program_studi_id' => $this->kelas->mataKuliah->program_studi_id,
            ],

            'dosen' => [
                'id' => $this->kelas->dosen->id,
                'nama' => $this->kelas->dosen->nama,
                'nip' => $this->kelas->dosen->nip,
                'email' => $this->kelas->dosen->email,
            ],
        ],
    ];
}
}
