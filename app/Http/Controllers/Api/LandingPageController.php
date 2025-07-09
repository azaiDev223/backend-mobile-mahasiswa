<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Feature;
use App\Models\Mahasiswa;
use App\Models\Message;
use App\Models\ProgramStudi;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    // Mengambil semua data untuk landing page
    public function getData()
    {
        return response()->json([
            'stats' => [
                'mahasiswa' => Mahasiswa::count(),
                'dosen' => Dosen::count(),
                'prodi' => ProgramStudi::count(),
            ],
            'features' => Feature::all(),
            'testimonials' => Testimonial::where('is_approved', true)->latest()->get(),
        ]);
    }

    // Menyimpan testimoni baru
    public function storeTestimonial(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:100',
            'content' => 'required|string|max:500',
        ]);

        Testimonial::create($validated);

        return response()->json(['message' => 'Terima kasih! Testimoni Anda akan kami review.'], 201);
    }

    // Menyimpan pesan kontak baru
    public function storeMessage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        Message::create($validated);

        return response()->json(['message' => 'Pesan Anda telah berhasil terkirim.'], 201);
    }
}