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
    $dosen = $request->user();

    if (!$dosen) {
        return response()->json([
            'message' => 'Dosen tidak ditemukan.',
        ], 404);
    }

    return response()->json([
        'data' => [
            'id' => $dosen->id,
            'nama' => $dosen->nama,
            'email' => $dosen->email,
            'nip' => $dosen->nip,
            'foto' => $dosen->foto,
            'foto_url' => $dosen->foto ? asset('storage/foto-dosen/' . $dosen->foto) : null,
            'jenis_kelamin' => $dosen->jenis_kelamin,
            'tanggal_lahir' => $dosen->tanggal_lahir,
            'no_hp' => $dosen->no_hp,
            'program_studi_id' => $dosen->program_studi_id,
            'program_studi' => $dosen->programStudi->nama_prodi ?? null,
        ]
    ]);
}


public function updateMe(Request $request)
{
    $dosen = $request->user();

    if (!$dosen) {
        return response()->json([
            'message' => 'User tidak ditemukan dari token.',
        ], 401);
    }
     // dosen yang sedang login

    $validated = $request->validate([
        'nama' => 'nullable|string|max:255',
        'email' => 'nullable|email|unique:dosens,email,' . $dosen->id,
        'nip' => 'nullable|string|max:100',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
        'tanggal_lahir' => 'nullable|date',
        'no_hp' => 'nullable|string|max:20',
        // ❌ Tidak bisa edit program_studi_id
    ]);

    if ($request->hasFile('foto')) {
    if ($dosen->foto && Storage::disk('public')->exists('foto-dosen/' . $dosen->foto)) {
        Storage::disk('public')->delete('foto-dosen/' . $dosen->foto);
    }

    $path = $request->file('foto')->store('foto-dosen', 'public');
    $validated['foto'] = basename($path); // GABUNGKAN KE VALIDATED
}

$dosen->update($validated);

    

    return response()->json([
        'message' => 'Profil dosen berhasil diperbarui',
        'dosen' => [
            'id' => $dosen->id,
            'nama' => $dosen->nama,
            'email' => $dosen->email,
            'nip' => $dosen->nip,
            'foto' => $dosen->foto,
            'foto_url' => $dosen->foto ? asset('storage/foto-dosen/' . $dosen->foto) : null,
            'program_studi' => $dosen->programStudi->nama_prodi ?? null, // TIDAK DIEDIT
            'jenis_kelamin' => $dosen->jenis_kelamin,
            'tanggal_lahir' => $dosen->tanggal_lahir,
            'no_hp' => $dosen->no_hp,
        ]
    ]);
}

}




