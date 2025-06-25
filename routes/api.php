<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Mahasiswa\KhsController;
use App\Http\Controllers\Api\ProfileMahasiswaController;
use App\Http\Controllers\Api\KrsController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});



// login dosen
use App\Http\Controllers\Api\AuthDosenController;
use App\Http\Controllers\Api\DosenController;

Route::post('/login-dosen', [AuthDosenController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

Route::get('/dosen/me', [DosenController::class, 'me']);

    Route::post('/logout-dosen', [AuthDosenController::class, 'logout']);
});



// Login Mahasiswa (Sanctum)



Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', function (Request $request) {
        return $request->user()->load(['programStudi', 'dosen']);
    });
    Route::post('/password/change', [ProfileMahasiswaController::class, 'changePassword']);
    Route::post('/profile/update', [ProfileMahasiswaController::class, 'updateProfile']);
    // --- RUTE BARU UNTUK FITUR KRS ---
    Route::get('/krs/penawaran', [KrsController::class, 'getPenawaranMatakuliah']);
    Route::post('/logout', [AuthController::class, 'logout']);
});




// Api untuk pengumuman

Route::get('pengumuman', [App\Http\Controllers\Api\PengumumanController::class, 'index']);
Route::post('pengumuman', [App\Http\Controllers\Api\PengumumanController::class, 'store']);
Route::put('pengumuman/{id}', [App\Http\Controllers\Api\PengumumanController::class, 'update']);
Route::patch('pengumuman/{id}', [App\Http\Controllers\Api\PengumumanController::class, 'update']);
Route::delete('pengumuman/{id}', [App\Http\Controllers\Api\PengumumanController::class, 'destroy']);

//  



// users
Route::get('users',[App\Http\Controllers\Api\UsersController::class,'index']);
Route::post('users', [App\Http\Controllers\Api\UsersController::class, 'store']);
Route::put('users/{id}', [App\Http\Controllers\Api\UsersController::class, 'update']);
Route::patch('users/{id}', [App\Http\Controllers\Api\UsersController::class, 'update']);
Route::delete('users/{id}', [App\Http\Controllers\Api\UsersController::class, 'destroy']);


// prodi
Route::get('prodi',[App\Http\Controllers\Api\ProgramStudiController::class,'index']);
Route::post('prodi', [App\Http\Controllers\Api\ProgramStudiController::class, 'store']);
Route::put('prodi/{id}', [App\Http\Controllers\Api\ProgramStudiController::class, 'update']);
Route::patch('prodi/{id}', [App\Http\Controllers\Api\ProgramStudiController::class, 'update']);
Route::delete('prodi/{id}', [App\Http\Controllers\Api\ProgramStudiController::class, 'destroy']);


// matkul
Route::get('matkul', [App\Http\Controllers\Api\MataKuliahController::class, 'index']);
Route::post('matkul', [App\Http\Controllers\Api\MataKuliahController::class, 'store']);
Route::put('matkul/{id}', [App\Http\Controllers\Api\MataKuliahController::class, 'update']);
Route::patch('matkul/{id}', [App\Http\Controllers\Api\MataKuliahController::class, 'update']);
Route::delete('matkul/{id}', [App\Http\Controllers\Api\MataKuliahController::class, 'destroy']);


// Dosen
Route::get('dosen', [App\Http\Controllers\Api\DosenController::class, 'index']);
Route::post('dosen', [App\Http\Controllers\Api\DosenController::class, 'store']);
Route::put('dosen/{id}', [App\Http\Controllers\Api\DosenController::class, 'update']);
Route::patch('dosen/{id}', [App\Http\Controllers\Api\DosenController::class, 'update']);
Route::delete('dosen/{id}', [App\Http\Controllers\Api\DosenController::class, 'destroy']);

// routes/api.php

Route::get('mahasiswa', [App\Http\Controllers\Api\MahasiswaController::class, 'index']);
Route::post('mahasiswa', [App\Http\Controllers\Api\MahasiswaController::class, 'store']);
Route::put('mahasiswa/{id}', [App\Http\Controllers\Api\MahasiswaController::class, 'update']);
Route::patch('mahasiswa/{id}', [App\Http\Controllers\Api\MahasiswaController::class, 'update']);
Route::delete('mahasiswa/{id}', [App\Http\Controllers\Api\MahasiswaController::class, 'destroy']);

// kelas

use App\Http\Controllers\Api\KelasController;

Route::get('kelas', [KelasController::class, 'index']);
Route::post('kelas', [KelasController::class, 'store']);
Route::get('kelas/{id}', [KelasController::class, 'show']);
Route::put('kelas/{id}', [KelasController::class, 'update']);
Route::patch('kelas/{id}', [KelasController::class, 'update']);
Route::delete('kelas/{id}', [KelasController::class, 'destroy']);

// jadwal kuliah
use App\Http\Controllers\Api\JadwalKuliahController;

Route::get('jadwal-kuliah', [JadwalKuliahController::class, 'index']);
Route::post('jadwal-kuliah', [JadwalKuliahController::class, 'store']);
Route::get('jadwal-kuliah/{id}', [JadwalKuliahController::class, 'show']);
Route::put('jadwal-kuliah/{id}', [JadwalKuliahController::class, 'update']);
Route::delete('jadwal-kuliah/{id}', [JadwalKuliahController::class, 'destroy']);





// bimbingan
use App\Http\Controllers\Api\BimbinganController;

Route::get('bimbingan', [BimbinganController::class, 'index']);
Route::post('bimbingan', [BimbinganController::class, 'store']);
Route::get('bimbingan/{id}', [BimbinganController::class, 'show']);
Route::put('bimbingan/{id}', [BimbinganController::class, 'update']);
Route::delete('bimbingan/{id}', [BimbinganController::class, 'destroy']);






