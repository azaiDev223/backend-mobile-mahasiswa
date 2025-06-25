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
        // 'whenLoaded' memastikan data relasi hanya disertakan jika sudah di-load
        // oleh controller (menggunakan ->with([...])) untuk efisiensi.
        return [
            'id' => $this->id,
            'hari' => $this->hari,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'ruangan' => $this->ruangan,
            'tahun_akademik' => $this->tahun_akademik, // <-- Ditambahkan
            'kuota' => $this->kuota,                // <-- Ditambahkan
            
            // --- BAGIAN YANG DIPERBAIKI ---
            // Membangun data kelas dan relasinya secara manual
            'kelas' => [
                'id' => $this->kelas->id,
                'nama_kelas' => $this->kelas->nama_kelas,

                // Menyertakan data mata kuliah dari relasi kelas
                'mata_kuliah' => [
                    'id' => $this->kelas->mataKuliah->id,
                    'kode_matkul' => $this->kelas->mataKuliah->kode_matkul, // Kirim sebagai 'kode' agar cocok dengan Flutter
                    'nama_matkul' => $this->kelas->mataKuliah->nama_matkul,
                    'sks' => $this->kelas->mataKuliah->sks,
                    'semester' => $this->kelas->mataKuliah->semester,
                    'program_studi_id' => $this->kelas->mataKuliah->program_studi_id,
                ],

                // Menyertakan data dosen dari relasi kelas
                'dosen' => [
                    'id' => $this->kelas->dosen->id,
                    'nama' => $this->kelas->dosen->nama,
                    // Anda bisa menambahkan field dosen lain jika perlu
                    'nip' => $this->kelas->dosen->nip,
                    'email' => $this->kelas->dosen->email,
                ],
            ],
        ];
    }
}
