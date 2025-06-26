<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TranskripResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // 1. Langsung akses relasi KHS yang SUDAH di-load oleh controller.
        // Tidak perlu query baru.
        $allDetails = $this->khs->pluck('details')->flatten();
        
        $totalSks = 0;
        $totalBobotKaliSks = 0;

        // 2. Loop melalui setiap nilai untuk menghitung SKS dan IPK.
        foreach ($allDetails as $detail) {
            if ($detail && $detail->mataKuliah) { 
                $sks = $detail->mataKuliah->sks;
                $bobot = $this->getBobotNilai($detail->grade);
                
                // Hanya hitung SKS jika nilai lulus (bukan E atau F).
                if ($bobot > 0) { 
                    $totalSks += $sks;
                }
                $totalBobotKaliSks += $bobot * $sks;
            }
        }
        
        $ipk = ($totalSks > 0) ? round($totalBobotKaliSks / $totalSks, 2) : 0;
        
        // 3. Kelompokkan nilai berdasarkan semester.
        $nilaiPerSemester = $allDetails->filter(function ($detail) {
            return $detail && $detail->mataKuliah;
        })->groupBy('mataKuliah.semester');

        // 4. Kembalikan data dalam format JSON yang terstruktur.
        return [
            'mahasiswa' => [
                'nama' => $this->nama,
                'nim' => $this->nim,
                'program_studi' => $this->programStudi->nama_prodi ?? 'N/A'
            ],
            'transkrip' => [
                'total_sks_lulus' => $totalSks,
                'ipk' => number_format($ipk, 2),
                'nilai_per_semester' => $nilaiPerSemester->map(function ($details, $semester) {
                    return [
                        'semester' => $semester,
                        'matakuliah' => $details->map(function ($detail) {
                            return [
                                'kode_matkul' => $detail->mataKuliah->kode_matkul,
                                'nama_matkul' => $detail->mataKuliah->nama_matkul,
                                'sks' => $detail->mataKuliah->sks,
                                'grade' => $detail->grade,
                            ];
                        })
                    ];
                })->values()
            ]
        ];
    }
    
    private function getBobotNilai($grade)
    {
        $bobot = ['A' => 4.0, 'B' => 3.0, 'C' => 2.0, 'D' => 1.0];
        return $bobot[$grade] ?? 0.0;
    }
}
