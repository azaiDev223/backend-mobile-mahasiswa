<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DosenResource;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DosenController extends Controller
{
    public function index()
    {
        return DosenResource::collection(Dosen::with('programStudi')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string|max:50|unique:dosens,nip',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'email' => 'required|email|unique:dosens,email',
            'password' => 'required|string|min:6',
            'no_hp' => 'required|string|max:15',
            'tanggal_lahir' => 'required|date',
            'program_studi_id' => 'required|exists:program_studis,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto-dosen', 'public');
            $validated['foto'] = basename($path);
        }

        $dosen = Dosen::create($validated);

        return new DosenResource($dosen);
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $validated = $request->validate([
            'nip' => 'sometimes|required|string|max:50|unique:dosens,nip,' . $id,
            'nama' => 'sometimes|required|string|max:255',
            'jenis_kelamin' => 'sometimes|required|in:Laki-laki,Perempuan',
            'email' => 'sometimes|required|email|unique:dosens,email,' . $id,
            'password' => 'sometimes|nullable|string|min:6',
            'no_hp' => 'sometimes|required|string|max:15',
            'tanggal_lahir' => 'sometimes|required|date',
            'program_studi_id' => 'sometimes|required|exists:program_studis,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($dosen->foto && Storage::disk('public')->exists('foto-dosen/' . $dosen->foto)) {
                Storage::disk('public')->delete('foto-dosen/' . $dosen->foto);
            }

            $path = $request->file('foto')->store('foto-dosen', 'public');
            $validated['foto'] = basename($path);
        }

        $dosen->update($validated);

        return new DosenResource($dosen);
    }

    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);

        // Hapus foto dari storage jika ada
        if ($dosen->foto && Storage::disk('public')->exists('foto-dosen/' . $dosen->foto)) {
            Storage::disk('public')->delete('foto-dosen/' . $dosen->foto);
        }

        $dosen->delete();

        return response()->json(['message' => 'Dosen berhasil dihapus']);
    }

    public function me(Request $request)
{
    $dosen = Dosen::with('programStudi')->findOrFail($request->user()->id);
    return new DosenResource($dosen);
}
}


