<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
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
        $prodi = ProgramStudi::withCount(['mahasiswa', 'dosen'])->get();
        return view('statistik.prodi', compact('prodi'));
    }
}

