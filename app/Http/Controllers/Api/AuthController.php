<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $mahasiswa = Mahasiswa::where('email', $credentials['email'])->first();

        if (!$mahasiswa || !Hash::check($credentials['password'], $mahasiswa->password)) {
            return response()->json([
                'message' => 'Email atau password salah.'
            ], 401);
        }

        $token = $mahasiswa->createToken('mahasiswa-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'token' => $token,
            'mahasiswa' => [
                'id' => $mahasiswa->id,
                'nama' => $mahasiswa->nama,
                'email' => $mahasiswa->email,
                'nim' => $mahasiswa->nim,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil.']);
    }
}
