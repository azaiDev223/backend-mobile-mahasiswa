<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramStudiResource;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    //
    public function index(){
        return ProgramStudiResource::collection(ProgramStudi::all());
    }
        public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_prodi' => 'required|integer|max:10',
            'nama_prodi' => 'required|string|max:100',
        ]);

        $prodi = ProgramStudi::create($validated);

        return new ProgramStudiResource($prodi);
    }

    public function update(Request $request, $id)
    {
        $prodi = ProgramStudi::findOrFail($id);

        $validated = $request->validate([
            'kode_prodi' => 'sometimes|required|integer|max:10',
            'nama_prodi' => 'sometimes|required|string|max:100',
        ]);

        $prodi->update($validated);

        return new ProgramStudiResource($prodi);
    }

    public function destroy($id)
    {
        $prodi = ProgramStudi::findOrFail($id);
        $prodi->delete();

        return response()->json(['message' => 'Pengumuman berhasil dihapus']);
    }
}
