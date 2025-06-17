<?php

namespace App\Http\Controllers\Api\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Resources\KhsResource;
use App\Models\Khs;
use Illuminate\Http\Request;

class KhsController extends Controller
{
    public function index()
    {
        $mahasiswa = auth()->user();

        $khs = Khs::with('details.mataKuliah')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('semester')
            ->get();

        return KhsResource::collection($khs);
    }
}

