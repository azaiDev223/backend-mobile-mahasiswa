<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Pengumuman;
use App\Models\ProgramStudi;

class StatistikController extends Controller
{
    public function mahasiswa()
    {
        $mahasiswa = Mahasiswa::with('ProgramStudi')->paginate(10);
        return view('mahasiswa', compact('mahasiswa'));
    }

    public function dosen()
    {
        $dosen = Dosen::with('ProgramStudi')->paginate(10);
        return view('dosen', compact('dosen'));
    }

    public function prodi()
    {
        $prodi = ProgramStudi::with(['mataKuliahs' => function($query) {
        $query->orderBy('semester');
    }])->get();
        return view('prodi', compact('prodi'));
    }

    public function pengumuman()
    {
        $pengumuman = Pengumuman::all();
        return view('pengumuman',compact('pengumuman'));
    }
}

