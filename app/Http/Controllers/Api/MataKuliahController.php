<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MataKuliahResource;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index()
    {
        return MataKuliahResource::collection(MataKuliah::with('programStudi')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_matkul' => 'required|string|max:50',
            'nama_matkul' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'program_studi_id' => 'required|exists:program_studis,id',
        ]);

        $matkul = MataKuliah::create($validated);

        return new MataKuliahResource($matkul);
    }

    public function update(Request $request, $id)
    {
        $matkul = MataKuliah::findOrFail($id);

        $validated = $request->validate([
            'kode_matkul' => 'sometimes|required|string|max:50',
            'nama_matkul' => 'sometimes|required|string|max:255',
            'sks' => 'sometimes|required|integer|min:1|max:6',
            'program_studi_id' => 'sometimes|required|exists:program_studis,id',
        ]);

        $matkul->update($validated);

        return new MataKuliahResource($matkul);
    }

    public function destroy($id)
    {
        $matkul = MataKuliah::findOrFail($id);
        $matkul->delete();

        return response()->json(['message' => 'Mata kuliah berhasil dihapus']);
    }
}
