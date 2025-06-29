<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JadwalKuliahResource;
use App\Models\JadwalKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalKuliahController extends Controller
{
    public function index()
    {
        $jadwals = JadwalKuliah::with('kelas')->get();
        return JadwalKuliahResource::collection($jadwals);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan' => 'required|string|max:100',
            'tahun_akademik' => 'required|string|max:10', // DITAMBAHKAN
            'kuota' => 'required|integer|min:1', // DITAMBA
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $jadwal = JadwalKuliah::create($validator->validated());

        return new JadwalKuliahResource($jadwal);
    }

    public function show($id)
        {
            $jadwal = JadwalKuliah::with('kelas.mataKuliah')->findOrFail($id);

            return response()->json([
                'id' => $jadwal->id,
                'mata_kuliah_id' => $jadwal->kelas->mataKuliah->id ?? null,
            ]);
        }



    public function update(Request $request, $id)
    {
        $jadwal = JadwalKuliah::findOrFail($id);

        $validator = Validator::make($request->all(), [
    'kelas_id' => 'sometimes|required|exists:kelas,id',
    'hari' => 'sometimes|required|in:Senin,Selasa,Rabu,Kamis,Jumat',
    'jam_mulai' => 'sometimes|required|date_format:H:i',
    'jam_selesai' => 'sometimes|required|date_format:H:i|after:jam_mulai',
    'ruangan' => 'sometimes|required|string|max:100',
    'tahun_akademik' => 'sometimes|required|string|max:10', // DITAMBAHKAN
    'kuota' => 'sometimes|required|integer|min:1', // DITAMBAHKAN
]);



        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $jadwal->update($validator->validated());

        return new JadwalKuliahResource($jadwal);
    }

    public function destroy($id)
    {
        $jadwal = JadwalKuliah::findOrFail($id);
        $jadwal->delete();

        return response()->json([
            'message' => 'Jadwal berhasil dihapus',
        ]);
    }



    // jadwal untuk dosen
    public function jadwalByDosen(Request $request)
    {
        $dosen = $request->user(); // dosen yang login

        $jadwal = JadwalKuliah::whereHas('kelas', function ($query) use ($dosen) {
            $query->where('dosen_id', $dosen->id);
        })->with([
            'kelas:id,dosen_id,matakuliah_id,nama_kelas',
            'kelas.mataKuliah:id,nama_matkul,kode_matkul',
        ])->get();

        return response()->json($jadwal);
    }
}


