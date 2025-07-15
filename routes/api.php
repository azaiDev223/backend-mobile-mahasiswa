<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KhsController;
use App\Http\Controllers\Api\ProfileMahasiswaController;
// use App\Http\Controllers\Api\Mahasiswa\BimbinganController;
use App\Http\Controllers\Api\KrsController;
use App\Http\Controllers\Api\TranskripController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\LandingPageController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});



// login dosen
use App\Http\Controllers\Api\AuthDosenController;
use App\Http\Controllers\Api\DosenController;
use App\Http\Controllers\Api\ProfileDosenController;
use App\Http\Controllers\Api\InputNilaiController;
use App\Http\Controllers\Api\BimbinganDosenController;
use App\Http\Controllers\Api\KrsDosenController;


Route::post('/login-dosen', [AuthDosenController::class, 'login']);



Route::middleware('auth:sanctum')->group(function () {

    Route::post('/dosen/profile/update', [ProfileDosenController::class, 'updateProfile']);
    Route::put('/dosen/profile/update', [ProfileDosenController::class, 'updateProfile']);


    // update password
    Route::post('/dosen/update-password', [ProfileDosenController::class, 'updatePassword']);


    // update nilai
    Route::get('/input-nilai/matkul', [InputNilaiController::class, 'listMatkul']);
    Route::get('/input-nilai/mahasiswa/{jadwal_kuliah_id}', [InputNilaiController::class, 'listMahasiswa']);
    Route::post('/input-nilai/simpan', [InputNilaiController::class, 'simpanNilai']);
    Route::get('/nilai/{jadwal_kuliah_id}', [InputNilaiController::class, 'listMahasiswaDenganNilai']);
    Route::get('/input-nilai/sudah-dinilai/{jadwal_kuliah_id}', [InputNilaiController::class, 'listMahasiswaSudahDinilai']);
    // Rute untuk mendapatkan daftar bimbingan
    Route::get('/bimbingan-dosen', [BimbinganDosenController::class, 'index']);

    // Rute untuk mengubah status bimbingan
    Route::post('/bimbingan-dosen/{id}/update-status', [BimbinganDosenController::class, 'updateStatus']);
    Route::put('/bimbingan/{id}', [BimbinganDosenController::class, 'updateStatus']);

    // Rute untuk mendapatkan daftar pengajuan KRS
    Route::get('/dosen/krs-pengajuan', [KrsDosenController::class, 'getPengajuanKrs']);

    // Rute untuk mengubah status KRS
    Route::post('/dosen/krs/{krsId}/update-status', [KrsDosenController::class, 'updateKrsStatus']);







    Route::get('/dosen/me', [DosenController::class, 'me']);



    Route::get('/dosen/chat/conversations', [ChatController::class, 'getDosenConversations']);
    Route::get('/dosen/chat/{mahasiswaId}', [ChatController::class, 'getMessagesWithMahasiswa']);
    Route::post('/dosen/chat', [ChatController::class, 'sendMessageToMahasiswa']);
    Route::post('/chat-dosen', [ChatController::class, 'sendMessageFromDosen']);
    Route::middleware('auth:sanctum')->delete('/chat/{id}', [ChatController::class, 'destroy']);




    // Route::delete('/chat/{id}', [ChatController::class, 'deleteMessage']);
    // Route::middleware('auth:sanctum')->delete('/chat/{id}', [ChatController::class, 'destroy']);

});

Route::get('/daftar-mahasiswa/{jadwal_kuliah_id}', [App\Http\Controllers\Api\JadwalKuliahController::class, 'daftarmahasiswa']);



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
    Route::post('/krs/simpan', [KrsController::class, 'simpanKrs']); // <-- RUTE BARU
    Route::get('/krs/riwayat', [KrsController::class, 'getSubmittedKrs']); // <-- RUTE BARU
    // --- RUTE BARU UNTUK JADWAL KULIAH ---
    Route::get('/jadwal-kuliah/mahasiswa', [KrsController::class, 'getJadwalKuliah']);
    Route::get('/khs', [KhsController::class, 'index']);
    // --- RUTE BARU UNTUK FITUR TRANSKRIP ---
    Route::get('/transkrip', [TranskripController::class, 'getTranskrip']);
    // Route spesifik untuk Bimbingan dari sisi Mahasiswa
    Route::get('/mahasiswa/bimbingan', [App\Http\Controllers\Api\Mahasiswa\BimbinganController::class, 'index']);
    Route::post('/mahasiswa/bimbingan', [App\Http\Controllers\Api\Mahasiswa\BimbinganController::class, 'store']);

    // Route untuk Chat
    Route::get('/chat/{partnerId}', [ChatController::class, 'getMessages']);
    Route::post('/chat', [ChatController::class, 'sendMessage']);

    Route::post('/logout', [AuthController::class, 'logout']);
});

// Route untuk Landing Page
Route::get('/landing-page', [LandingPageController::class, 'getData']);
Route::post('/testimonials', [LandingPageController::class, 'storeTestimonial']);
Route::post('/contact', [LandingPageController::class, 'storeMessage']);

// Api untuk pengumuman

Route::get('pengumuman', [App\Http\Controllers\Api\PengumumanController::class, 'index']);
Route::post('pengumuman', [App\Http\Controllers\Api\PengumumanController::class, 'store']);
Route::put('pengumuman/{id}', [App\Http\Controllers\Api\PengumumanController::class, 'update']);
Route::patch('pengumuman/{id}', [App\Http\Controllers\Api\PengumumanController::class, 'update']);
Route::delete('pengumuman/{id}', [App\Http\Controllers\Api\PengumumanController::class, 'destroy']);

//  



// users
Route::get('users', [App\Http\Controllers\Api\UsersController::class, 'index']);
Route::post('users', [App\Http\Controllers\Api\UsersController::class, 'store']);
Route::put('users/{id}', [App\Http\Controllers\Api\UsersController::class, 'update']);
Route::patch('users/{id}', [App\Http\Controllers\Api\UsersController::class, 'update']);
Route::delete('users/{id}', [App\Http\Controllers\Api\UsersController::class, 'destroy']);


// prodi
Route::get('prodi', [App\Http\Controllers\Api\ProgramStudiController::class, 'index']);
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


Route::middleware('auth:sanctum')->get('/jadwal-dosen', [JadwalKuliahController::class, 'jadwalByDosen']);





// bimbingan
use App\Http\Controllers\Api\BimbinganController;
// use App\Http\Controllers\Api\BimbinganDosenController;

Route::get('bimbingan', [BimbinganController::class, 'index']);
Route::post('bimbingan', [BimbinganController::class, 'store']);
Route::get('bimbingan/{id}', [BimbinganController::class, 'show']);
Route::put('bimbingan/{id}', [BimbinganController::class, 'update']);
Route::delete('bimbingan/{id}', [BimbinganController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/bimbingan-admin', [BimbinganController::class, 'bimbinganAdmin']);
// Route::middleware('auth:sanctum')->get('/bimbingan-dosen', [BimbinganController::class, 'bimbinganDosen']);
