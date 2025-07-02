<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;

class AuthDosenController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $dosen = Dosen::with('programStudi')->where('email', $credentials['email'])->first();

    if (!$dosen || !Hash::check($credentials['password'], $dosen->password)) {
        return response()->json(['message' => 'Email atau password salah'], 401);
    }

    $token = $dosen->createToken('dosen-token')->plainTextToken;

    return response()->json([
        'message' => 'Login berhasil',
        'token' => $token,
        'dosen' => [
            'id' => $dosen->id,
            'nama' => $dosen->nama,
            'email' => $dosen->email,
            'nip' => $dosen->nip,
            'foto' => $dosen->foto,
            'foto_url' => $dosen->foto ? asset('storage/foto-dosen/' . $dosen->foto) : null,
            'program_studi' => $dosen->programStudi->nama_prodi ?? null, // ⬅️ ini penting
            'jenis_kelamin' => $dosen->jenis_kelamin,
            'tanggal_lahir' => $dosen->tanggal_lahir,
            'no_hp' => $dosen->no_hp,
        ]
    ]);
}



    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }
}

