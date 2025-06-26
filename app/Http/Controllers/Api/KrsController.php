<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalKuliah;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import Str facade
use App\Http\Resources\JadwalKuliahResource;
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal   
use App\Models\Krs;
use Illuminate\Support\Facades\DB; // Import DB facade untuk transaksi 
use App\Http\Resources\KrsResource;

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

    public function simpanKrs(Request $request)
    {
        // 1. Validasi data yang masuk dari Flutter
        $validated = $request->validate([
            'jadwal_ids' => 'required|array',
            'jadwal_ids.*' => 'required|integer|exists:jadwal_kuliahs,id'
        ]);

        $mahasiswa = $request->user();
        $setting = Setting::where('key', 'tahun_akademik_aktif')->firstOrFail();

        // --- BAGIAN YANG DIPERBAIKI ---
        // 2. Hitung semester mahasiswa saat ini (logika yang sama dari fungsi sebelumnya)
        $tahunMasuk = $mahasiswa->angkatan;
        $tahunSekarang = Carbon::now()->year;
        $selisihTahun = $tahunSekarang - $tahunMasuk;
        $bulanSekarang = Carbon::now()->month;
        $isGanjil = ($bulanSekarang >= 8 || $bulanSekarang <= 1);
        $semesterMahasiswa = ($selisihTahun * 2) + ($isGanjil ? 1 : 2);

        DB::beginTransaction();
        try {
            // 3. Buat "dokumen" KRS utama dengan semester yang sudah dihitung
            $krs = Krs::create([
                'id_mahasiswa' => $mahasiswa->id,
                'semester' => $semesterMahasiswa, // <-- GUNAKAN SEMESTER YANG SUDAH DIHITUNG
                'tahun_akademik' => $setting->value,
                'status_krs' => 'Diajukan',
            ]);

            // 4. Loop dan simpan setiap mata kuliah yang dipilih ke krs_detail
            foreach ($validated['jadwal_ids'] as $jadwalId) {
                $krs->detail()->create([
                    'jadwal_kuliah_id' => $jadwalId,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'KRS Anda telah berhasil diajukan.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan KRS.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mengambil KRS terakhir yang diajukan oleh mahasiswa.
     */
    public function getSubmittedKrs(Request $request)
    {
        $mahasiswa = $request->user();

        // Cari KRS terakhir yang diajukan oleh mahasiswa ini
        $krs = Krs::where('id_mahasiswa', $mahasiswa->id)
            ->with([
                // Eager load semua relasi yang dibutuhkan untuk ditampilkan di Flutter
                'detail.jadwalKuliah.kelas.mataKuliah',
                'detail.jadwalKuliah.kelas.dosen'
            ])
            ->latest() // Ambil yang paling baru
            ->first();

        // Jika tidak ditemukan, kembalikan respons kosong
        if (!$krs) {
            return response()->json(['message' => 'Anda belum mengajukan KRS.'], 404);
        }

        return new KrsResource($krs);
    }

    public function getJadwalKuliah(Request $request)
    {
        $mahasiswa = $request->user();

        // 1. Cari KRS terakhir yang statusnya "Disetujui"
        $krsDisetujui = Krs::where('id_mahasiswa', $mahasiswa->id)
            ->where('status_krs', 'Disetujui')
            ->latest('created_at') // Ambil yang paling baru
            ->first();

        // 2. Jika tidak ada KRS yang disetujui, kembalikan respons kosong
        if (!$krsDisetujui) {
            return response()->json(['data' => []]);
        }

        // 3. Ambil semua detail jadwal dari KRS tersebut
        $jadwalKuliah = $krsDisetujui->detail()->with([
            'jadwalKuliah.kelas.mataKuliah',
            'jadwalKuliah.kelas.dosen'
        ])->get()->pluck('jadwalKuliah');

        // 4. Kirim data menggunakan Resource untuk format yang konsisten
        return JadwalKuliahResource::collection($jadwalKuliah);
    }
}
