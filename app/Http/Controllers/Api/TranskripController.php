<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TranskripResource;
use App\Models\Mahasiswa; // Pastikan Anda mengimpor model Mahasiswa

class TranskripController extends Controller
{
    /**
     * Mengambil data transkrip lengkap dari mahasiswa yang sedang login.
     */
    public function getTranskrip(Request $request)
    {
        // 1. Ambil user (mahasiswa) yang sedang login.
        $mahasiswa = $request->user();

        // 2. [FIX] Tambahkan validasi untuk memastikan user ada (terautentikasi).
        // Jika tidak ada, kembalikan respons error 401 (Unauthorized).
        if (!$mahasiswa) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Pastikan variabel $mahasiswa adalah instance dari model yang benar
        // jika $request->user() mengembalikan model User generic.
        // Jika $request->user() sudah pasti mengembalikan model Mahasiswa, baris ini bisa dilewati.
        // $mahasiswaModel = Mahasiswa::find($mahasiswa->id);
        // if (!$mahasiswaModel) {
        //     return response()->json(['message' => 'Data mahasiswa tidak ditemukan.'], 404);
        // }


        // 3. [OPTIMASI] Lakukan Eager Loading untuk semua relasi yang dibutuhkan
        // oleh TranskripResource. Ini akan secara signifikan mengurangi jumlah
        // query ke database dan mempercepat waktu respons API.
        $mahasiswa->load([
            'programStudi', // Memuat relasi programStudi
            'khs.details.mataKuliah' // Memuat relasi KHS, lalu details, lalu mataKuliah
        ]);
        
        // 4. Kembalikan data melalui resource.
        return new TranskripResource($mahasiswa);
    }
}
