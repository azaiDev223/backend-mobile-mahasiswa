<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\LandingPageController;

// Rute untuk menampilkan landing page
Route::get('/', [LandingPageController::class, 'index'])->name('landing.index');

require __DIR__.'/auth.php';
