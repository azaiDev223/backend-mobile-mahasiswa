<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Feature;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Menampilkan halaman utama (landing page) dengan data dinamis.
     */
    public function index()
    {
        // Mengambil data statistik
        $stats = [
            'mahasiswa' => Mahasiswa::count(),
            'dosen' => Dosen::count(),
            'prodi' => ProgramStudi::count(),
        ];

        // Mengambil fitur unggulan
        $features = Feature::all();

        // Mengambil testimoni yang sudah disetujui
        $testimonials = Testimonial::where('is_approved', true)->latest()->get();

        // Mengirim semua data ke view 'index.blade.php'
        return view('index', compact('stats', 'features', 'testimonials'));
    }
}
