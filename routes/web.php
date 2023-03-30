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
Route::post('/user-login', [UserAuthController::class, 'authenticate']);
Route::post('/user-logout', [UserAuthController::class, 'logout'])->middleware('auth');
Route::post('/change-dashboard', [UserAuthController::class, 'changeDashboard'])->middleware('auth');

Route::get('/', [UserAuthController::class, 'dashboard'])->name('dashboard')->middleware('auth');

Route::get('/manajemen-pengguna', [UserAuthController::class, 'user_management'])->middleware('auth');
Route::post('/manajemen-pengguna/tambah', [UserAuthController::class, 'add_user'])->middleware('auth');
Route::post('/manajemen-pengguna/edit', [UserAuthController::class, 'edit_user'])->middleware('auth');
Route::post('/manajemen-pengguna/delete', [UserAuthController::class, 'delete_user'])->middleware('auth');

// Manajemen Data
Route::get('/manajemen-data', [DataManagementController::class, 'index'])->middleware('auth');
Route::get('/manajemen-data/mata-kuliah', [DataManagementController::class, 'showMatkul'])->middleware('auth');
Route::post('/manajemen-data/mata-kuliah/tambah', [DataManagementController::class, 'storeMatkul'])->middleware('auth');
Route::post('/manajemen-data/mata-kuliah/edit/{kode_matkul}', [DataManagementController::class, 'editMatkul'])->middleware('auth');
Route::post('/manajemen-data/mata-kuliah/delete', [DataManagementController::class, 'deleteMatkul'])->middleware('auth');
Route::get('/manajemen-data/dokumen-perkuliahan', [DataManagementController::class, 'showDokumen'])->middleware('auth');
Route::post('/manajemen-data/dokumen-perkuliahan/tambah', [DataManagementController::class, 'storeDokumen'])->middleware('auth');
Route::post('/manajemen-data/dokumen-perkuliahan/edit', [DataManagementController::class, 'editDokumen'])->middleware('auth');
Route::post('/manajemen-data/dokumen-perkuliahan/delete', [DataManagementController::class, 'deleteDokumen'])->middleware('auth');

// Penugasan
Route::get('/penugasan', [PenugasanController::class, 'index'])->middleware('auth');
Route::get('/penugasan/buat-penugasan-baru/form-pertama', [PenugasanController::class, 'stepOne'])->middleware('auth');
Route::post('/penugasan/buat-penugasan-baru/form-kedua', [PenugasanController::class, 'stepTwo'])->middleware('auth');
Route::post('/penugasan/buat-penugasan-baru/store', [PenugasanController::class, 'storePenugasan'])->middleware('auth')->name('penugasan.store');
Route::get('/penugasan/daftar-jumlah-kelas', [PenugasanController::class, 'showJumlahKelas'])->middleware('auth');
Route::get('/penugasan/daftar-kelas', [PenugasanController::class, 'showKelas'])->middleware('auth');

// Progres Pengumpulan

// Pengingat
Route::get('/atur-pengingat-pengumpulan', [PengingatController::class, 'showPengingat'])->middleware('auth');
Route::post('/atur-pengingat-pengumpulan/edit', [PengingatController::class, 'editPengingat'])->middleware('auth');
Route::post('/atur-pengingat-pengumpulan/edit_pengumpulan', [PengingatController::class, 'editPengumpulan'])->middleware('auth');



// Route Dosen

// Kelas Diampu
Route::get('/kelas-diampu', [KelasController::class, 'showKelasDiampu'])->middleware('auth');
Route::get('/kelas-diampu/{kode_kelas}', [KelasController::class, 'showDokumenDitugaskan'])->middleware('auth');
Route::get('/kelas-diampu/download/{id_dokumen}', [KelasController::class, 'downloadTemplate'])->middleware('auth');
Route::post('/kelas-diampu/upload', [KelasController::class, 'uploadDokumen'])->middleware('auth');

Route::get('/progres-pengumpulan', [ProgresController::class, 'index'])->middleware('auth');

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