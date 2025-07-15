<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\LandingPageController;
use App\Http\Controllers\Web\StatistikController;

// Rute untuk menampilkan landing page
Route::get('/', [LandingPageController::class, 'index'])->name('landing.index');

Route::post('/testimonials', [LandingPageController::class, 'storeTestimonial'])->name('landing.testimonial');

// Rute untuk menyimpan pesan kontak (POST)
Route::post('/contact', [LandingPageController::class, 'storeMessage'])->name('landing.contact');


Route::get('/statistik/mahasiswa', [StatistikController::class, 'mahasiswa'])->name('statistik.mahasiswa');
Route::get('/statistik/dosen', [StatistikController::class, 'dosen'])->name('statistik.dosen');
Route::get('/statistik/prodi', [StatistikController::class, 'prodi'])->name('statistik.prodi');

require __DIR__.'/auth.php';
