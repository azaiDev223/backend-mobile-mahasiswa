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
     * Menampilkan mahasiswa yang BELUM punya nilai di khs_details.
     */
    public function listMahasiswa($jadwal_kuliah_id)
    {
        $jadwal = JadwalKuliah::with('kelas.mataKuliah')->findOrFail($jadwal_kuliah_id);
        $mataKuliahId = $jadwal->kelas->matakuliah_id;

        $mahasiswas = Mahasiswa::whereHas('krs.detail', function ($q) use ($jadwal_kuliah_id) {
                $q->where('jadwal_kuliah_id', $jadwal_kuliah_id);
            })
            ->whereDoesntHave('khs.details', function ($q) use ($mataKuliahId) {
                $q->where('mata_kuliah_id', $mataKuliahId);
            })
            ->with(['krs' => function ($q) use ($jadwal_kuliah_id) {
                $q->whereHas('detail', fn($d) => $d->where('jadwal_kuliah_id', $jadwal_kuliah_id));
            }])
            ->get();

        $result = $mahasiswas->map(function ($mhs) {
            $krs = $mhs->krs->first();
            return [
                'id' => $mhs->id,
                'nama' => $mhs->nama,
                'semester' => $krs->semester ?? null,
                'tahun_akademik' => $krs->tahun_akademik ?? null,
            ];
        });

        return response()->json($result);
    }

    /**
     * Menampilkan mahasiswa yang SUDAH punya nilai untuk halaman edit nilai.
     */
    public function listMahasiswaSudahDinilai($jadwal_kuliah_id)
{
    $jadwal = JadwalKuliah::with('kelas')->findOrFail($jadwal_kuliah_id);

    if (!$jadwal->kelas || !$jadwal->kelas->matakuliah_id) {
        return response()->json([], 200);
    }

    $mataKuliahId = $jadwal->kelas->matakuliah_id;

    $mahasiswaIds = Mahasiswa::whereHas('krs.detail', function ($q) use ($jadwal_kuliah_id) {
        $q->where('jadwal_kuliah_id', $jadwal_kuliah_id);
    })->pluck('id');

    $sudahDinilai = KhsDetail::whereIn('khs_id', function ($q) use ($mahasiswaIds) {
        $q->select('id')
          ->from('khs')
          ->whereIn('mahasiswa_id', $mahasiswaIds);
    })
    ->where('mata_kuliah_id', $mataKuliahId)
    ->with(['khs.mahasiswa'])
    ->get()
    ->map(function ($detail) {
        return [
            'id' => $detail->khs->mahasiswa->id,
            'nama' => $detail->khs->mahasiswa->nama,
            'nilai' => $detail->nilai,
            'grade' => $detail->grade,
            'semester' => $detail->khs->semester,
            'tahun_akademik' => $detail->khs->tahun_akademik,
        ];
    });

    return response()->json($sudahDinilai);
}


    /**
     * Simpan atau update nilai mahasiswa ke tabel khs dan khs_details.
     */
    public function simpanNilai(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'semester' => 'required|integer',
            'tahun_akademik' => 'required|string',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'nilai' => 'required|numeric',
            'grade' => 'required|string',
        ]);

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
