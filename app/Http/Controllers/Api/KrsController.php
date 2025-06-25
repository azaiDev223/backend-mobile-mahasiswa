<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalKuliah;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import Str facade
use App\Http\Resources\JadwalKuliahResource;
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal    

class KrsController extends Controller
{
    public function getPenawaranMatakuliah(Request $request)
    {
        $mahasiswa = $request->user();

        // 1. Dapatkan tahun akademik yang sedang aktif dari tabel settings
        $setting = Setting::where('key', 'tahun_akademik_aktif')->first();
        if (!$setting) {
            return response()->json(['message' => 'Tahun akademik aktif belum diatur oleh admin.'], 404);
        }
        $tahunAkademikAktif = $setting->value;

        // 2. Tentukan jenis semester (Ganjil/Genap) dari string tahun akademik
        $isGanjil = Str::contains($tahunAkademikAktif, 'Ganjil');
        
        $allowedSemesters = [];
        if ($isGanjil) {
            $allowedSemesters = [1, 3, 5, 7]; // Jika Ganjil, tawarkan semua semester ganjil
        } else {
            $allowedSemesters = [2, 4, 6, 8]; // Jika Genap, tawarkan semua semester genap
        }
        
        // 3. Ambil semua jadwal yang sesuai dengan tahun akademik dan semester yang diizinkan
        $jadwalDitawarkan = JadwalKuliah::with(['kelas.mataKuliah', 'kelas.dosen'])
            ->where('tahun_akademik', $tahunAkademikAktif)
            ->whereHas('kelas.mataKuliah', function ($query) use ($allowedSemesters) {
                $query->whereIn('semester', $allowedSemesters);
            })
            ->get();

        // 4. Hitung semester mahasiswa saat ini (lebih akurat)
        $tahunMasuk = $mahasiswa->angkatan;
        $tahunSekarang = Carbon::now()->year;
        $selisihTahun = $tahunSekarang - $tahunMasuk;
        $bulanSekarang = Carbon::now()->month;
        $currentIsGanjil = ($bulanSekarang >= 8 || $bulanSekarang <= 1);
        $semesterMahasiswa = ($selisihTahun * 2) + ($currentIsGanjil ? 1 : 2);
            
        return response()->json([
            'tahun_akademik_aktif' => $tahunAkademikAktif,
            // Kirim semester mahasiswa untuk informasi saja, bisa dihitung di Flutter jika perlu
            'semester_mahasiswa' => $semesterMahasiswa, // Semester mahasiswa saat ini
                 // Placeholder, bisa dihitung lebih detail
            'jadwal_ditawarkan' => JadwalKuliahResource::collection($jadwalDitawarkan), // Gunakan resource untuk konsistensi
        ]);
    }

    // Anda bisa menambahkan fungsi untuk menyimpan KRS di sini
    public function simpanKrs(Request $request)
    {
        // Logika untuk menyimpan KRS akan ditambahkan di sini
    }
}
