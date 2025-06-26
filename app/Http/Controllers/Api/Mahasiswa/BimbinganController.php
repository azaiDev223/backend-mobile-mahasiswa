<?php

// [FIXED] Sesuaikan namespace dengan lokasi file yang baru
namespace App\Http\Controllers\Api\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bimbingan;
use Illuminate\Support\Facades\Validator;

class BimbinganController extends Controller
{
    /**
     * Menampilkan riwayat bimbingan milik mahasiswa yang sedang login.
     */
    public function index(Request $request)
    {
        $mahasiswa = $request->user();
        
        $bimbingan = Bimbingan::where('mahasiswa_id', $mahasiswa->id)
                            ->with('dosen:id,nama') // Hanya ambil id dan nama dosen
                            ->latest() // Urutkan dari yang terbaru
                            ->get();

        return response()->json(['data' => $bimbingan]);
    }

    /**
     * Menyimpan pengajuan bimbingan baru.
     */
    public function store(Request $request)
    {
        $mahasiswa = $request->user();

        $validator = Validator::make($request->all(), [
            'tanggal_bimbingan' => 'required|date',
            'topik' => 'required|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $bimbingan = Bimbingan::create([
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id' => $mahasiswa->dosen_id, // Ambil ID dosen wali dari data mahasiswa
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'topik' => $request->topik,
            'catatan' => $request->catatan,
            'status' => 'Diajukan', // Status awal sesuai permintaan
        ]);

        return response()->json([
            'message' => 'Pengajuan bimbingan berhasil dikirim.',
            'data' => $bimbingan
        ], 201);
    }
}