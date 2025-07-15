<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bimbingan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BimbinganDosenController extends Controller
{
    /**
     * Mengambil daftar semua bimbingan yang ditujukan untuk dosen yang sedang login.
     */
    public function index(Request $request)
    {
        $dosen = $request->user(); // Dapatkan dosen yang sedang login

        $bimbinganList = Bimbingan::with('mahasiswa') // Muat relasi ke mahasiswa
            ->where('dosen_id', $dosen->id)
            ->orderBy('tanggal_bimbingan', 'desc')
            ->get();

        return response()->json(['data' => $bimbinganList]);
    }

    /**
     * Memperbarui status sebuah bimbingan.
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi input status baru
        $validated = $request->validate([
            'status' => ['required', Rule::in(['Diajukan', 'Terjadwal', 'Selesai', 'Dibatalkan'])],
        ]);

        $dosen = $request->user();
        
        // Cari bimbingan berdasarkan ID dan pastikan bimbingan itu milik dosen yang sedang login
        $bimbingan = Bimbingan::where('id', $id)
                            ->where('dosen_id', $dosen->id)
                            ->firstOrFail(); // Akan error 404 jika tidak ditemukan

        // Update statusnya
        $bimbingan->status = $validated['status'];
        $bimbingan->save();

        return response()->json([
            'message' => 'Status bimbingan berhasil diperbarui.',
            'bimbingan' => $bimbingan->load('mahasiswa'), // Kirim kembali data yang sudah diupdate
        ]);
    }
}
