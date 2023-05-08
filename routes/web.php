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
    // Route::get('/penugasan/dokumen-ditugaskan', [PenugasanController::class, 'showDokumenDitugaskan']);

});

Route::middleware(['auth', 'role:admin'])->group(function () {
    
    Route::resource('/manajemen-pengguna', UserManagementController::class)->except(['create', 'show', 'edit']);

    // Manajemen Data
    Route::get('/manajemen-data', [DataManagementController::class, 'index']);
    
    Route::get('/manajemen-data/mata-kuliah', [DataManagementController::class, 'showMatkul']);
    Route::post('/manajemen-data/mata-kuliah/tambah', [DataManagementController::class, 'storeMatkul']);
    Route::post('/manajemen-data/mata-kuliah/edit/{kode_matkul}', [DataManagementController::class, 'editMatkul']);
    Route::post('/manajemen-data/mata-kuliah/delete', [DataManagementController::class, 'deleteMatkul']);
    
    Route::get('/manajemen-data/dokumen-perkuliahan', [DataManagementController::class, 'showDokumen']);
    Route::post('/manajemen-data/dokumen-perkuliahan/tambah', [DataManagementController::class, 'storeDokumen']);
    Route::post('/manajemen-data/dokumen-perkuliahan/edit', [DataManagementController::class, 'editDokumen']);
    Route::post('/manajemen-data/dokumen-perkuliahan/delete', [DataManagementController::class, 'deleteDokumen']);

    Route::resource('/manajemen-data/badge', BadgeController::class)->except(['create', 'store', 'show', 'edit']);

    // Progres Pengumpulan
    Route::get('/progres-pengumpulan', [ProgresController::class, 'index']);
    Route::post('/progres-pengumpulan/unduh-semua-dokumen', [ProgresController::class, 'downloadArchiveDokumen']);
    Route::get('/progres-pengumpulan/resume-pengumpulan', [ProgresController::class, 'showReport']);
    Route::post('/progres-pengumpulan/resume-pengumpulan/unduh', [ProgresController::class, 'generateReport']);
    Route::get('/progres-pengumpulan/kelas', [ProgresController::class, 'showProgresKelas']);
    Route::get('/progres-pengumpulan/dokumen', [ProgresController::class, 'showProgresDokumen']);
    Route::post('/progres-pengumpulan/dokumen', [ProgresController::class, 'downloadDokumen']);
    Route::post('/progres-pengumpulan/kelas', [ProgresController::class, 'downloadDokumenKelas']);


    // Riwayat Pengumpulan
    Route::get('/riwayat-pengumpulan', [ProgresController::class, 'showRiwayat']);

    // Pengingat
    Route::get('/atur-pengingat-pengumpulan', [PengingatController::class, 'showPengingat']);
    Route::post('/atur-pengingat-pengumpulan/edit', [PengingatController::class, 'editPengingat']);
    Route::post('/atur-pengingat-pengumpulan/edit_pengumpulan', [PengingatController::class, 'editPengumpulan']);

    
    Route::post('/leaderboard/badge', [LeaderBoardController::class, 'resultBadge']);

});

Route::middleware(['auth', 'role:dosen'])->group(function () {
    // Kelas Diampu
    Route::get('/kelas-diampu', [KelasDiampuController::class, 'showKelasDiampu']);
    Route::get('/kelas-diampu/{kode_kelas}', [KelasDiampuController::class, 'showDokumenDitugaskan']);
    Route::get('/kelas-diampu/download-template/{id_dokumen}', [DokumenDikumpulController::class, 'downloadTemplate']);
    Route::post('/kelas-diampu/upload', [DokumenDikumpulController::class, 'uploadDokumen']);
    Route::post('/kelas-diampu/multiple-upload', [DokumenDikumpulController::class, 'uploadDokumenMultiple'])->name('store.dokumen');

    Route::get('/kelas-diampu/dokumen/{id_dokumen}', [DokumenDikumpulController::class, 'readDokumenSingle'])->name('dokumen-single.show');
    Route::get('/kelas-diampu/dokumen/download/{id_dokumen}', [DokumenDikumpulController::class, 'downloadDokumenSingle'])->name('dokumen-single.download');
    Route::delete('/kelas-diampu/dokumen/{id_dokumen}', [DokumenDikumpulController::class, 'deleteDokumen'])->name('dokumen.delete');
    
    Route::get('/kelas-diampu/dokumen-multiple/{id_dokumen}', [DokumenDikumpulController::class, 'showDokumenMultiple']);
    Route::get('/kelas-diampu/dokumen-multiple/show/{id_dokumen}', [DokumenDikumpulController::class, 'readDokumenDikumpulMultiple']);
    Route::put('/kelas-diampu/dokumen-multiple/{id_dokumen}', [DokumenDikumpulController::class, 'renameDokumenDikumpulMultiple']);
    Route::delete('/kelas-diampu/dokumen-multiple/{id_dokumen}', [DokumenDikumpulController::class, 'deleteDokumenDikumpulMultiple']);

    // Dokumen Perkuliahan
    Route::get('/dokumen-perkuliahan', [DokumenPerkuliahanController::class, 'index']);
    Route::get('/dokumen-perkuliahan/show/{id_dokumen}', [DokumenDikumpulController::class, 'readDokumenSingle']);
    Route::get('/dokumen-perkuliahan/show-multiple/{id_dokumen}', [DokumenPerkuliahanController::class, 'showDokumenMultiple']);
    Route::get('/dokumen-perkuliahan/show-multiple/show/{id_dokumen}', [DokumenDikumpulController::class, 'readDokumenDikumpulMultiple']);
    Route::get('/dokumen-perkuliahan/download/{id_dokumen}', [DokumenDikumpulController::class, 'downloadDokumenSingle']);

});



// Route Dosen





Route::get('/dokumen-sebelumnya', function () {
    return view('user.dokumen-sebelumnya');
})->middleware('auth');

// Route::get('/atur-pengingat-pengumpulan', function () {
//     return view('user.atur-pengingat');
// })->middleware('auth');

// Route::get('/atur-pengingat-pengumpulan', function () {
//     return view('user.atur-pengingat');
// })->middleware('auth');

Route::get('/jumlah-kelas', function () {
    return view('user.jumlah-kelas');
})->middleware('auth');

Route::get('/dosen-pengampu', function () {
    return view('user.dosen-pengampu');
})->middleware('auth');

// Route::get('/dokumen-dikumpul', function () {
//     return view('user.dokumen-dikumpul');
// })->middleware('auth');