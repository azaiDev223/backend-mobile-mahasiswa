<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KrsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'semester' => $this->semester,
            'tahun_akademik' => $this->tahun_akademik,
            'status_krs' => $this->status_krs,
            'created_at' => $this->created_at->toDateTimeString(),
            // Kunci diubah menjadi 'detail' agar cocok dengan model Flutter
            // Relasi dipanggil dengan nama 'detail' (tanpa 's')
            'detail' => $this->detail->map(function ($detailItem) {
                return [
                    'id' => $detailItem->id,
                    'jadwal_kuliah' => [
                        'id' => $detailItem->jadwalKuliah->id,
                        'hari' => $detailItem->jadwalKuliah->hari,
                        'jam_mulai' => $detailItem->jadwalKuliah->jam_mulai,
                        'jam_selesai' => $detailItem->jadwalKuliah->jam_selesai,
                        'ruangan' => $detailItem->jadwalKuliah->ruangan,
                        'tahun_akademik' => $detailItem->jadwalKuliah->tahun_akademik,
                        'kuota' => $detailItem->jadwalKuliah->kuota,
                        'kelas' => [
                            'id' => $detailItem->jadwalKuliah->kelas->id,
                            'nama_kelas' => $detailItem->jadwalKuliah->kelas->nama_kelas,
                            'mata_kuliah' => [
                                'id' => $detailItem->jadwalKuliah->kelas->mataKuliah->id,
                                // --- BAGIAN YANG DIPERBAIKI ---
                                // Ubah kunci 'kode' menjadi 'kode_matkul' agar cocok dengan model Flutter
                                'kode_matkul' => $detailItem->jadwalKuliah->kelas->mataKuliah->kode_matkul,
                                'nama_matkul' => $detailItem->jadwalKuliah->kelas->mataKuliah->nama_matkul,
                                'sks' => $detailItem->jadwalKuliah->kelas->mataKuliah->sks,
                                'semester' => $detailItem->jadwalKuliah->kelas->mataKuliah->semester,
                                'program_studi_id' => $detailItem->jadwalKuliah->kelas->mataKuliah->program_studi_id,
                            ],
                            'dosen' => [
                                'id' => $detailItem->jadwalKuliah->kelas->dosen->id,
                                // Nama kolom diperbaiki menjadi 'nama'
                                'nama' => $detailItem->jadwalKuliah->kelas->dosen->nama,
                                'nip' => $detailItem->jadwalKuliah->kelas->dosen->nip,
                            ]
                        ]
                    ]
                ];
            }),
            // Menggunakan whenLoaded untuk efisiensi dan keamanan
            // Ini akan menyertakan 'detail' hanya jika relasinya sudah di-load di controller.
            // 'detail' => KrsDetailResource::collection($this->whenLoaded('detail')),
        ];
    }
}
