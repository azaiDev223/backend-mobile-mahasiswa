<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileDosenController extends Controller
{
    /**
     * Memperbarui data profil dosen yang sedang login.
     */
    public function updateProfile(Request $request)
    {
        $dosen = $request->user(); // ambil dosen yang login lewat Sanctum

        // Validasi input yang bisa diubah
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('dosens')->ignore($dosen->id)],
            'nip' => 'nullable|string|max:100',
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'tanggal_lahir' => 'nullable|date',
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Jika upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($dosen->foto && Storage::disk('public')->exists('foto-dosen/' . $dosen->foto)) {
                Storage::disk('public')->delete('foto-dosen/' . $dosen->foto);
            }

            $path = $request->file('foto')->store('foto-dosen', 'public');
            $validated['foto'] = basename($path);
        }

        // Update data dosen
        $dosen->update($validated);

        // Response JSON rapi
        return response()->json([
            'message' => 'Profil dosen berhasil diperbarui.',
            'dosen' => [
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



    public function updatePassword(Request $request)
{
    $user = $request->user(); // ✅ BENAR

    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json(['message' => 'Password lama salah'], 400);
    }

    $user->update([
    'password' => $request->new_password,
]);

    return response()->json(['message' => 'Password berhasil diperbarui']);
}

}
