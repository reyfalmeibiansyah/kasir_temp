<?php

use App\Http\Controllers\AkunController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

// 🔓 Route Login (tanpa middleware login)
// TANPA middleware login
Route::get('/login', [UserController::class, 'index'])->name('auth.login');
Route::post('/login-proses', [UserController::class, 'login_proses'])->name('login-proses');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');


// 👑 Route Admin (akses hanya untuk admin)
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // CRUD Produk
    Route::get('/produks', [ProdukController::class, 'index'])->name('produks.index');
    Route::get('/produks/create', [ProdukController::class, 'create'])->name('produks.create');
    Route::post('/produks/store', [ProdukController::class, 'store'])->name('produks.store');
    Route::get('/produks/show/{id}', [ProdukController::class, 'show'])->name('produks.show');
    Route::get('/produks/edit/{id}', [ProdukController::class, 'edit'])->name('produks.edit');
    Route::put('/produks/{id}', [ProdukController::class, 'update'])->name('produks.update');
    Route::post('/produk/updateStock/{id}', [ProdukController::class, 'updateStock'])->name('produk.updateStock');
    Route::delete('/produks/{id}', [ProdukController::class, 'destroy'])->name('produks.destroy');

    // CRUD User
    Route::prefix('user')->name('user.')->group(function () {
    Route::get('/', [AkunController::class, 'index'])->name('index');
    Route::get('/create', [AkunController::class, 'create'])->name('create');
    Route::post('/store', [AkunController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [AkunController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [AkunController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [AkunController::class, 'destroy'])->name('destroy');
    });

    // Pembelian
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
});


// 🔧 Route Petugas (akses hanya untuk petugas)
Route::middleware(['petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    // Dashboard Petugas
    Route::get('/dashboard', [DashboardController::class, 'dashboardpetugas'])->name('dashboard');

    // Produk hanya bisa dilihat
    Route::get('/produks', [ProdukController::class, 'index'])->name('produks.index');
    Route::get('/produks/show/{id}', [ProdukController::class, 'show'])->name('produks.show');

    // CRUD Pembelian
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('/pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');

    // Route baru untuk form → halaman konfirmasi detail (pakai POST!)
    Route::post('/pembelian/detail', [PembelianController::class, 'detail'])->name('pembelian.detail');
    Route::get('/pembelian/struk/{id}', [PembelianController::class, 'struk'])->name('pembelian.struk');

    // Simpan pembelian ke database
    Route::post('/pembelian/store', [PembelianController::class, 'store'])->name('pembelian.store');

    Route::get('/pembelian/export', [PembelianController::class, 'export'])->name('pembelian.export');
    Route::get('/pembelian/{id}', [PembelianController::class, 'show'])->name('pembelian.show');
    Route::get('/pembelian/{id}/download-pdf', [PembelianController::class, 'downloadPdf'])->name('pembelian.downloadPdf');
});


// 🌐 Route default (home)
Route::get('/', function () {
    return redirect('/login');
});

