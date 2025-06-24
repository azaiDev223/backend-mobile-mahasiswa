<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Ganti pencarian awal untuk menyertakan relasi
        $mahasiswa = Mahasiswa::with(['programStudi', 'dosen']) // <-- Eager load relasi
                                ->where('email', $credentials['email'])
                                ->first();

        if (!$mahasiswa || !Hash::check($credentials['password'], $mahasiswa->password)) {
            return response()->json([
                'message' => 'Email atau password salah.'
            ], 401);
        }

        $token = $mahasiswa->createToken('mahasiswa-token')->plainTextToken;

        // --- BAGIAN YANG DIPERBAIKI ---
        // Kita bangun kembali respons secara manual untuk memastikan semua data terkirim
        return response()->json([
            'message' => 'Login berhasil.',
            'token' => $token,
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
                // Pastikan foto_url dibuat di sini
                'foto_url' => $mahasiswa->foto ? asset('storage/' . $mahasiswa->foto) : null,
                // Kirim ID relasi
                'program_studi_id' => $mahasiswa->program_studi_id,
                'dosen_id' => $mahasiswa->dosen_id,
                // Kirim juga objek relasi agar nama prodi dan dosen bisa ditampilkan
                'program_studi' => $mahasiswa->programStudi,
                'dosen' => $mahasiswa->dosen,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil.']);
    }


   public function register(Request $request)
    {
        // Validasi data yang masuk dari Flutter
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|unique:mahasiswas,nim',
            'email' => 'required|string|email|max:255|unique:mahasiswas,email',
            'password' => 'required|string|min:8|confirmed',
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'no_hp' => 'nullable|string',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'angkatan' => 'nullable|integer',
            
            // --- TAMBAHKAN DUA BARIS VALIDASI DI BAWAH INI ---
            'program_studi_id' => 'required|exists:program_studis,id',
            'dosen_id' => 'required|exists:dosens,id',
        ]);

        // Buat mahasiswa baru
        // Hashing password sudah ditangani oleh booted() method di model Mahasiswa Anda
        $mahasiswa = Mahasiswa::create($validatedData);

        // Berikan response sukses
        return response()->json([
            'message' => 'Registrasi berhasil. Silakan login.',
            'mahasiswa' => $mahasiswa
        ], 201); // 201 Created
    }
}
