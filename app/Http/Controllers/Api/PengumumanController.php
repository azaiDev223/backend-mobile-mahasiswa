<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengumumanResource;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        return PengumumanResource::collection(Pengumuman::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'isi' => 'required|string',
        ]);

        $pengumuman = Pengumuman::create($validated);

        return new PengumumanResource($pengumuman);
    }

    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'sometimes|required|string|max:255',
            'kategori' => 'sometimes|required|string|max:100',
            'isi' => 'sometimes|required|string',
        ]);

        $pengumuman->update($validated);

        return new PengumumanResource($pengumuman);
    }

    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return response()->json(['message' => 'Pengumuman berhasil dihapus']);
    }
}
