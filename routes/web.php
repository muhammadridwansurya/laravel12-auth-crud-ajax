<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;

Route::get('/', function () {
    return redirect('login');
});

// Rute yang hanya bisa diakses sebelum login
Route::middleware(['guest'])->group(function () {
    // Menampilkan form login
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    // Proses login
    Route::post('/login', [AuthController::class, 'login']);
});

// Rute yang hanya bisa diakses setelah login
Route::middleware(['auth'])->group(function () {
    // Proses logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // halaman dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // route kelas
    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas');
    Route::get('/kelas/show/{idKelas}', [KelasController::class, 'getDataById'])->name('kelas.show');
    Route::get('/kelas/json_data', [KelasController::class, 'getData'])->name('kelas.json_data');
    Route::post('/kelas/tambah', [KelasController::class, 'insertData'])->name('kelas.tambah');
    Route::put('/kelas/edit/{siswa_id}', [KelasController::class, 'updateData'])->name('kelas.edit');
    Route::delete('/kelas/hapus/{siswa_id}', [KelasController::class, 'deleteData'])->name('kelas.hapus');

    // route siswa
    Route::get('/siswa', [DashboardController::class, 'index'])->name('siswa');
});

