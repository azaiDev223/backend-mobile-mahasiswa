<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KhsResource;
use App\Models\Khs;
use Illuminate\Http\Request;

class KhsController extends Controller
{
    public function index(Request $request)
    {
        // $mahasiswa = auth()->user();

        // $khs = Khs::with('details.mataKuliah')
        //     ->where('mahasiswa_id', $mahasiswa->id)
        //     ->orderByDesc('semester')
        //     ->get();

        // return KhsResource::collection($khs);

        $mahasiswa = $request->user();

        // Ambil semua KHS milik mahasiswa ini, diurutkan dari yang terbaru
        // dan sertakan relasi detail beserta mata kuliahnya
        $khsHistory = Khs::where('mahasiswa_id', $mahasiswa->id)
                        ->with('details.mataKuliah')
                        ->orderBy('tahun_akademik', 'desc')
                        ->orderBy('semester', 'desc')
                        ->get();

        if ($khsHistory->isEmpty()) {
            return response()->json(['message' => 'Riwayat KHS tidak ditemukan.'], 404);
        }

        return KhsResource::collection($khsHistory);
    }
}

