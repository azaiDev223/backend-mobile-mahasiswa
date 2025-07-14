<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KrsDosenController extends Controller
{
    /**
     * Mengambil daftar KRS yang diajukan oleh mahasiswa bimbingan.
     */
    public function getPengajuanKrs(Request $request)
    {
        $dosen = $request->user(); // Dapatkan dosen yang sedang login

        // Ambil semua KRS yang statusnya 'Diajukan' dari mahasiswa
        // yang dosen_id-nya adalah ID dosen yang sedang login.
        $pengajuanKrs = Krs::with('mahasiswa')
            ->whereHas('mahasiswa', function ($query) use ($dosen) {
                $query->where('dosen_id', $dosen->id);
            })
            ->where('status_krs', 'Diajukan')
            ->latest()
            ->get();

        return response()->json(['data' => $pengajuanKrs]);
    }

    /**
     * Memperbarui status KRS (menyetujui atau menolak).
     */
    public function updateKrsStatus(Request $request, $krsId)
    {
        $dosen = $request->user();

        // Validasi input
        $validated = $request->validate([
            'status' => ['required', Rule::in(['Disetujui', 'Ditolak'])],
        ]);

        // Cari KRS berdasarkan ID dan pastikan itu milik mahasiswa bimbingan dosen ini
        $krs = Krs::where('id', $krsId)
            ->whereHas('mahasiswa', function ($query) use ($dosen) {
                $query->where('dosen_id', $dosen->id);
            })
            ->firstOrFail(); // Akan error 404 jika tidak ditemukan

        // Update status
        $krs->status_krs = $validated['status'];
        $krs->save();

        return response()->json([
            'message' => 'Status KRS berhasil diperbarui.',
            'krs' => $krs->load('mahasiswa'),
        ]);
    }
}
