<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Feature;
use App\Models\Mahasiswa;
use App\Models\Message;
use App\Models\Pengumuman;
use App\Models\ProgramStudi;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Menampilkan halaman utama (landing page) dengan data dinamis.
     */
    /**
     * Menampilkan halaman utama (landing page) dengan data dinamis.
     */
    public function index()
    {
        $stats = [
            'mahasiswa' => Mahasiswa::count(),
            'dosen' => Dosen::count(),
            'prodi' => ProgramStudi::count(),
        ];
        $features = Feature::all();
        $pengumuman = Pengumuman::count();
        $testimonials = Testimonial::where('is_approved', true)->latest()->get();

        return view('index', compact('stats', 'features', 'testimonials','pengumuman'));
    }

    /**
     * Menyimpan testimoni baru dari form web.
     */
    public function storeTestimonial(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:100',
            'content' => 'required|string|max:500',
        ]);

        Testimonial::create($validated);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('testimonial_status', 'Terima kasih! Testimoni Anda akan kami review.');
    }

    /**
     * Menyimpan pesan kontak baru dari form web.
     */
    public function storeMessage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        Message::create($validated);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('contact_status', 'Pesan Anda telah berhasil terkirim.');
    }
}
