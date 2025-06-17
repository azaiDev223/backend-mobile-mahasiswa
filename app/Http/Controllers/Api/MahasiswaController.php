<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MahasiswaResource;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        return MahasiswaResource::collection(Mahasiswa::with(['programStudi', 'dosen'])->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string|max:50|unique:mahasiswas,nim',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'email' => 'required|email|unique:mahasiswas,email',
            'password' => 'required|string|min:6',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'angkatan' => 'required|integer',
            'program_studi_id' => 'required|exists:program_studis,id',
            'dosen_id' => 'required|exists:dosens,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto-mahasiswa', 'public');
        }

        $mahasiswa = Mahasiswa::create($validated);

        return new MahasiswaResource($mahasiswa);
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $validated = $request->validate([
            'nim' => 'sometimes|required|string|max:50|unique:mahasiswas,nim,' . $id,
            'nama' => 'sometimes|required|string|max:255',
            'jenis_kelamin' => 'sometimes|required|in:Laki-laki,Perempuan',
            'email' => 'sometimes|required|email|unique:mahasiswas,email,' . $id,
            'password' => 'sometimes|nullable|string|min:6',
            'no_hp' => 'sometimes|required|string|max:15',
            'alamat' => 'sometimes|required|string|max:255',
            'tanggal_lahir' => 'sometimes|required|date',
            'angkatan' => 'sometimes|required|integer',
            'program_studi_id' => 'sometimes|required|exists:program_studis,id',
            'dosen_id' => 'sometimes|required|exists:dosens,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto-mahasiswa', 'public');
        }

        $mahasiswa->update($validated);

        return new MahasiswaResource($mahasiswa);
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return response()->json(['message' => 'Mahasiswa berhasil dihapus']);
    }
}
