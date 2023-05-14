<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\DataManagementController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\ProgresController;
use App\Http\Controllers\PengingatController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KelasDiampuController;
use App\Http\Controllers\DokumenDikumpulController;
use App\Http\Controllers\DokumenPerkuliahanController;
use App\Http\Controllers\DokumenDitugaskanController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\LeaderBoardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/user-login', [UserAuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/user-login', [UserAuthController::class, 'authenticate'])->middleware('guest');

Route::middleware('auth')->group(function () {
    // Ubah Dashboard Kaprodi/GKMP ke dosen pengampu
    Route::post('/change-dashboard', [UserAuthController::class, 'changeDashboard']);

    Route::get('/', [UserAuthController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/profil', [UserAuthController::class, 'profile']);
    Route::post('/profil/update', [UserAuthController::class, 'updateProfile']);
    Route::post('/profil/update-password', [UserAuthController::class, 'updatePassword']);

    Route::post('/user-logout', [UserAuthController::class, 'logout']);
    
    Route::post('/set-session', [UserAuthController::class, 'setSession']);

    Route::get('/leaderboard', [LeaderBoardController::class, 'index']);
    Route::get('/badge', [LeaderBoardController::class, 'showResultBadge']);
});

Route::middleware(['auth', 'role:superAdmin'])->group(function () {
    // Penugasan
    Route::get('/penugasan', [PenugasanController::class, 'index']);

    Route::get('/penugasan/buat-penugasan-baru/form-pertama', [PenugasanController::class, 'stepOne']);
    Route::post('/penugasan/buat-penugasan-baru/store-form-kedua', [PenugasanController::class, 'storeStepOne']);

    Route::get('/penugasan/buat-penugasan-baru/form-kedua', [PenugasanController::class, 'stepTwo']);
    Route::post('/penugasan/buat-penugasan-baru/store-form-ketiga', [PenugasanController::class, 'storeStepTwo']);

    Route::get('/penugasan/buat-penugasan-baru/form-ketiga', [PenugasanController::class, 'stepThree']);
    Route::post('/penugasan/buat-penugasan-baru/store', [PenugasanController::class, 'storePenugasan'])->name('penugasan.store');
    
    Route::get('/penugasan/daftar-jumlah-kelas', [PenugasanController::class, 'showJumlahKelas']);
    
    Route::resource('/penugasan/daftar-kelas', KelasController::class)->except(['create', 'show', 'edit']);
    
    Route::resource('/penugasan/dokumen-ditugaskan', DokumenDitugaskanController::class)->except(['create', 'show', 'edit']);

    Route::post('/penugasan/dokumen-ditugaskan/edit-pengumpulan', [DokumenDitugaskanController::class, 'editPengumpulan']);


    Route::post('/leaderboard/badge', [LeaderBoardController::class, 'resultBadge']);
    Route::delete('/leaderboard/badge', [LeaderBoardController::class, 'deleteBadge']);

});

Route::middleware(['auth', 'role:admin'])->group(function () {
    
    Route::resource('/manajemen-pengguna', UserManagementController::class)->except(['create', 'show', 'edit']);

    // Manajemen Data
    Route::get('/manajemen-data', [DataManagementController::class, 'index']);
    
    Route::get('/manajemen-data/mata-kuliah', [DataManagementController::class, 'showMatkul']);
    Route::post('/manajemen-data/mata-kuliah', [DataManagementController::class, 'storeMatkul']);
    Route::put('/manajemen-data/mata-kuliah/{kode_matkul}', [DataManagementController::class, 'editMatkul']);
    Route::delete('/manajemen-data/mata-kuliah/{kode_matkul}', [DataManagementController::class, 'deleteMatkul']);
    
    Route::get('/manajemen-data/dokumen-perkuliahan', [DataManagementController::class, 'showDokumen']);
    Route::post('/manajemen-data/dokumen-perkuliahan', [DataManagementController::class, 'storeDokumen']);
    Route::put('/manajemen-data/dokumen-perkuliahan/{id_dokumen}', [DataManagementController::class, 'editDokumen']);
    Route::delete('/manajemen-data/dokumen-perkuliahan/{id_dokumen}', [DataManagementController::class, 'deleteDokumen']);

    Route::resource('/manajemen-data/badge', BadgeController::class)->except(['create', 'store', 'show', 'edit']);

    // Progres Pengumpulan
    Route::get('/progres-pengumpulan', [ProgresController::class, 'index']);
    Route::post('/progres-pengumpulan/unduh-semua-dokumen', [ProgresController::class, 'downloadArchiveDokumen']);
    Route::get('/progres-pengumpulan/resume-pengumpulan', [ProgresController::class, 'showReport']);
    Route::post('/progres-pengumpulan/resume-pengumpulan/unduh', [ProgresController::class, 'generateReport']);
   
    Route::get('/progres-pengumpulan/kelas', [ProgresController::class, 'showProgresKelas']);
    Route::get('/progres-pengumpulan/kelas/{id_dokumen}', [DokumenDikumpulController::class, 'showDokumenDikumpul']);
    Route::get('/progres-pengumpulan/kelas/unduh/{id_dokumen}', [DokumenDikumpulController::class, 'downloadDokumenDikumpul']);
    // Route::delete('/progres-pengumpulan/kelas/{id_dokumen}', [DokumenDikumpulController::class, 'deleteDokumenDikumpul']);
   
    Route::get('/progres-pengumpulan/dokumen', [ProgresController::class, 'showProgresDokumen']);
    Route::get('/progres-pengumpulan/dokumen/{id_dokumen}', [DokumenDikumpulController::class, 'showDokumenDikumpul']);
    Route::get('/progres-pengumpulan/dokumen/unduh/{id_dokumen}', [DokumenDikumpulController::class, 'downloadDokumenDikumpul']);
    Route::post('/progres-pengumpulan/dokumen/catatan', [ProgresController::class, 'storeCatatan']);

    Route::post('/progres-pengumpulan/dokumen', [ProgresController::class, 'downloadDokumen']);
    Route::post('/progres-pengumpulan/kelas', [ProgresController::class, 'downloadDokumenKelas']);


    // Riwayat Pengumpulan
    Route::get('/riwayat-pengumpulan-score', [ProgresController::class, 'showRiwayat']);
});

Route::middleware(['auth', 'role:dosen'])->group(function () {
    // Kelas Diampu
    Route::get('/kelas-diampu', [KelasDiampuController::class, 'showKelasDiampu']);
    Route::get('/kelas-diampu/{kode_kelas}', [KelasDiampuController::class, 'showDokumenDitugaskan']);
    Route::get('/kelas-diampu/unduh-template/{id_dokumen}', [DokumenDikumpulController::class, 'downloadTemplate']);
    Route::post('/kelas-diampu/upload', [DokumenDikumpulController::class, 'uploadDokumen']);
    Route::post('/kelas-diampu/multiple-upload', [DokumenDikumpulController::class, 'uploadDokumenMultiple']);

    Route::get('/kelas-diampu/dokumen/{id_dokumen}', [DokumenDikumpulController::class, 'showDokumenDikumpul']);
    Route::get('/kelas-diampu/dokumen/unduh/{id_dokumen}', [DokumenDikumpulController::class, 'downloadDokumenDikumpul']);
    Route::put('/kelas-diampu/dokumen/{id_dokumen}', [DokumenDikumpulController::class, 'renameDokumenDikumpulMultiple']);
    Route::delete('/kelas-diampu/dokumen/{id_dokumen}', [DokumenDikumpulController::class, 'deleteDokumenDikumpul']);

    // Riwayat Pengumpulan
    Route::get('/riwayat-pengumpulan-perolehan-score', [KelasDiampuController::class, 'showRiwayat']);

    // Score
    // Route::get('/perolehan-score', [LeaderboardController::class, 'showUserScore']);

    // Dokumen Perkuliahan
    Route::get('/dokumen-perkuliahan', [DokumenPerkuliahanController::class, 'index']);
    Route::get('/dokumen-perkuliahan/{id_dokumen}', [DokumenDikumpulController::class, 'showDokumenDikumpul']);
    Route::get('/dokumen-perkuliahan/unduh/{id_dokumen}', [DokumenDikumpulController::class, 'downloadDokumenDikumpul']);
});