<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KelasResource;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with(['dosen', 'matakuliah'])->get();
        return KelasResource::collection($kelas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'matakuliah_id' => 'required|exists:mata_kuliahs,id',
            'nama_kelas' => 'required|string|max:100',
        ]);

        $kelas = Kelas::create($validated);

        return new KelasResource($kelas);
    }

    public function show($id)
    {
        $kelas = Kelas::with(['dosen', 'matakuliah'])->findOrFail($id);
        return new KelasResource($kelas);
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $validated = $request->validate([
            'dosen_id' => 'sometimes|required|exists:dosens,id',
            'matakuliah_id' => 'sometimes|required|exists:mata_kuliahs,id',
            'nama_kelas' => 'sometimes|required|string|max:100',
        ]);

        $kelas->update($validated);

        return new KelasResource($kelas);
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return response()->json(['message' => 'Kelas berhasil dihapus.']);
    }
}
