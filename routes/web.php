<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\LandingPageController;

// Rute untuk menampilkan landing page
Route::get('/', [LandingPageController::class, 'index'])->name('landing.index');

Route::post('/testimonials', [LandingPageController::class, 'storeTestimonial'])->name('landing.testimonial');

// Rute untuk menyimpan pesan kontak (POST)
Route::post('/contact', [LandingPageController::class, 'storeMessage'])->name('landing.contact');

require __DIR__.'/auth.php';
