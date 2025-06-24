<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileMahasiswaController extends Controller
{
    /**
     * Mengubah password untuk mahasiswa yang sedang terotentikasi.
     */
    public function changePassword(Request $request)
    {
        // 1. Validasi input dari form Flutter
        $validatedData = $request->validate([
            'current_password' => 'required|string',
            // 'password' akan divalidasi harus sama dengan 'password_confirmation'
            'password' => ['required', 'string', Password::min(8), 'confirmed'],
        ]);

        // 2. Dapatkan data mahasiswa yang sedang login (terotentikasi)
        $mahasiswa = $request->user();

        // 3. Cek apakah password lama yang dimasukkan benar
        if (!Hash::check($validatedData['current_password'], $mahasiswa->password)) {
            // Jika tidak cocok, kembalikan error
            return response()->json([
                'message' => 'Password lama Anda tidak cocok.'
            ], 422); // 422: Unprocessable Entity, cocok untuk error validasi
        }

        // 4. Update password dengan yang baru
        // Model Mahasiswa Anda akan otomatis men-hash password ini berkat metode booted()
        $mahasiswa->password = $validatedData['password'];
        $mahasiswa->save();
        
        // 5. Berikan respons sukses
        return response()->json([
            'message' => 'Password berhasil diubah.'
        ]);
    }


    /**
     * Memperbarui data profil mahasiswa yang sedang login.
     */
    public function updateProfile(Request $request)
    {
        $mahasiswa = $request->user();

        // Validasi semua data yang bisa diubah
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('mahasiswas')->ignore($mahasiswa->id)],
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'tanggal_lahir' => 'nullable|date',
            'angkatan' => 'nullable|integer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        // Handle upload file foto jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($mahasiswa->foto) {
                Storage::disk('public')->delete($mahasiswa->foto);
            }
            // Simpan foto baru dan dapatkan path-nya
            $validatedData['foto'] = $request->file('foto')->store('foto-mahasiswas', 'public');
        }

        // Update data mahasiswa di database
        $mahasiswa->update($validatedData);

        // --- BAGIAN YANG DIPERBAIKI ---
        // Bangun kembali respons secara manual untuk memastikan semua data terkirim
        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'mahasiswa' => [
                'id' => $mahasiswa->id,
                'nama' => $mahasiswa->nama,
                'email' => $mahasiswa->email,
                'nim' => $mahasiswa->nim,
                'jenis_kelamin' => $mahasiswa->jenis_kelamin,
                'no_hp' => $mahasiswa->no_hp,
                'alamat' => $mahasiswa->alamat,
                'tanggal_lahir' => $mahasiswa->tanggal_lahir,
                'angkatan' => $mahasiswa->angkatan,
                'foto' => $mahasiswa->foto,
                'foto_url' => $mahasiswa->foto ? asset('storage/' . $mahasiswa->foto) : null,
                'program_studi_id' => $mahasiswa->program_studi_id,
                'dosen_id' => $mahasiswa->dosen_id,
                'program_studi' => $mahasiswa->programStudi,
                'dosen' => $mahasiswa->dosen,
            ]
        ]);
    }
}
