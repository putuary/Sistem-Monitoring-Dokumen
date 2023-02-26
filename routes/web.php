<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\DataManagementController;
use App\Http\Controllers\ProgresController;

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

Route::get('/', [UserAuthController::class, 'dashboard'])->name('dashboard')->middleware('auth');

Route::get('/manajemen-pengguna', [UserAuthController::class, 'user_management'])->middleware('auth');
Route::post('/manajemen-pengguna/tambah', [UserAuthController::class, 'add_user'])->middleware('auth');
Route::post('/manajemen-pengguna/edit', [UserAuthController::class, 'edit_user'])->middleware('auth');
Route::post('/manajemen-pengguna/delete', [UserAuthController::class, 'delete_user'])->middleware('auth');

Route::get('/manajemen-data', [DataManagementController::class, 'index'])->middleware('auth');

Route::get('/progres-pengumpulan', [ProgresController::class, 'index'])->middleware('auth');

Route::get('/dokumen-sebelumnya', function () {
    return view('user.dokumen-sebelumnya');
})->middleware('auth');

Route::get('/atur-pengingat-pengumpulan', function () {
    return view('user.atur-pengingat');
})->middleware('auth');

Route::get('/atur-pengingat-pengumpulan', function () {
    return view('user.atur-pengingat');
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