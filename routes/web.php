<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\DataManagementController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\ProgresController;
use App\Http\Controllers\PengingatController;
use App\Http\Controllers\KelasController;

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

 // Ubah Dashboard
 Route::post('/change-dashboard', [UserAuthController::class, 'changeDashboard'])->middleware('auth');

Route::get('/', [UserAuthController::class, 'dashboard'])->name('dashboard')->middleware('auth');

Route::post('/user-logout', [UserAuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth', 'role:superAdmin'])->group(function () {
    // Penugasan
    Route::get('/penugasan', [PenugasanController::class, 'index']);
    Route::get('/penugasan/buat-penugasan-baru/form-pertama', [PenugasanController::class, 'stepOne']);
    Route::post('/penugasan/buat-penugasan-baru/form-kedua', [PenugasanController::class, 'stepTwo']);
    Route::post('/penugasan/buat-penugasan-baru/store', [PenugasanController::class, 'storePenugasan'])->name('penugasan.store');
    Route::get('/penugasan/daftar-jumlah-kelas', [PenugasanController::class, 'showJumlahKelas']);
    Route::get('/penugasan/daftar-kelas', [PenugasanController::class, 'showKelas']);

});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/manajemen-pengguna', [UserAuthController::class, 'user_management']);
    Route::post('/manajemen-pengguna/tambah', [UserAuthController::class, 'add_user']);
    Route::post('/manajemen-pengguna/edit', [UserAuthController::class, 'edit_user']);
    Route::post('/manajemen-pengguna/delete', [UserAuthController::class, 'delete_user']);

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

    // Progres Pengumpulan
    Route::get('/progres-pengumpulan', [ProgresController::class, 'index']);

    // Pengingat
    Route::get('/atur-pengingat-pengumpulan', [PengingatController::class, 'showPengingat']);
    Route::post('/atur-pengingat-pengumpulan/edit', [PengingatController::class, 'editPengingat']);
    Route::post('/atur-pengingat-pengumpulan/edit_pengumpulan', [PengingatController::class, 'editPengumpulan']);

});

Route::middleware(['auth', 'role:dosen'])->group(function () {
    // Kelas Diampu
    Route::get('/kelas-diampu', [KelasController::class, 'showKelasDiampu']);
    Route::get('/kelas-diampu/{kode_kelas}', [KelasController::class, 'showDokumenDitugaskan']);
    Route::get('/kelas-diampu/download/{id_dokumen}', [KelasController::class, 'downloadTemplate']);
    Route::post('/kelas-diampu/upload', [KelasController::class, 'uploadDokumen']);

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

Route::get('/riwayat', function () {
    return view('admin.riwayat.index');
})->middleware('auth');

Route::get('/jumlah-kelas', function () {
    return view('user.jumlah-kelas');
})->middleware('auth');

Route::get('/dosen-pengampu', function () {
    return view('user.dosen-pengampu');
})->middleware('auth');

Route::get('/dokumen-dikumpul', function () {
    return view('user.dokumen-dikumpul');
})->middleware('auth');