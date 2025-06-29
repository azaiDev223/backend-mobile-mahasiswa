<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalKuliah;
use App\Models\Mahasiswa;
use App\Models\Khs;
use App\Models\KhsDetail;

class InputNilaiController extends Controller
{
    /**
     * Menampilkan daftar mata kuliah (jadwal kuliah) yang diajar oleh dosen login.
     */
    public function listMatkul(Request $request)
    {
        $dosen = $request->user(); // Ambil dosen login dari Sanctum

        $jadwal = JadwalKuliah::with(['kelas.mataKuliah'])
            ->whereHas('kelas', function ($q) use ($dosen) {
                $q->where('dosen_id', $dosen->id);
            })
            ->get();

        return response()->json($jadwal);
    }

    /**
     * Menampilkan daftar mahasiswa yang mengambil suatu mata kuliah berdasarkan jadwal_kuliah_id.
     */
    public function listMahasiswa($jadwal_kuliah_id)
    {
        $mahasiswas = Mahasiswa::whereHas('krs.detail', function ($q) use ($jadwal_kuliah_id) {
            $q->where('jadwal_kuliah_id', $jadwal_kuliah_id);
        })->with('programStudi')->get();

        return response()->json($mahasiswas);
    }

    /**
     * Simpan nilai mahasiswa ke tabel khs dan khs_details.
     */
    public function simpanNilai(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'semester' => 'required|string',
            'tahun_akademik' => 'required|string',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'nilai' => 'required|numeric',
            'grade' => 'required|string',
        ]);

        // Cek apakah data KHS sudah ada
        $khs = Khs::firstOrCreate(
            [
                'mahasiswa_id' => $request->mahasiswa_id,
                'semester' => $request->semester,
                'tahun_akademik' => $request->tahun_akademik,
            ],
            [
                'ip' => 0,
                'ips' => 0,
                'ipk' => 0,
            ]
        );

        // Simpan atau update nilai mata kuliah pada khs_details
        $khs->details()->updateOrCreate(
            ['mata_kuliah_id' => $request->mata_kuliah_id],
            ['nilai' => $request->nilai, 'grade' => $request->grade]
        );

        return response()->json([
            'message' => 'Nilai berhasil disimpan',
            'khs_id' => $khs->id,
        ]);
    }
}
