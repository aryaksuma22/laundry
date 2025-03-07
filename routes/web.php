<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SatuanObatController;
use App\Http\Controllers\KategoriObatController;
use App\Http\Controllers\PembelianObatController;
use App\Http\Controllers\PenjualanObatController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/navigasiobat', function () {
    return view('navigasiobat');
})->middleware(['auth', 'verified'])->name('navigasiobat');


Route::get('/account-management', [UserController::class, 'index'])->middleware(['auth', 'verified'])->name('account.management');
Route::resource('users', UserController::class)->middleware(['auth', 'verified']);
Route::delete('/users', [UserController::class, 'destroy'])->name('users.destroy');
Route::delete('/users/single/{id}', [UserController::class, 'destroySingle'])->name('obats.destroySingle');

Route::resource('obats', ObatController::class)->middleware(['auth', 'verified']);
Route::delete('/obats/mass-delete', [ObatController::class, 'destroy'])->name('obats.massDestroy');
Route::delete('/obats/single/{id}', [ObatController::class, 'destroySingle'])->name('obats.destroySingle');

Route::resource('kategori_obats', KategoriObatController::class)->middleware(['auth', 'verified']);
Route::delete('/kategori_obats/mass-delete', [KategoriObatController::class, 'destroy'])->name('kategori_obats.massDestroy');

Route::resource('satuan_obats', SatuanObatController::class)->middleware(['auth', 'verified']);
Route::delete('/satuan_obats/mass-delete', [SatuanObatController::class, 'destroy'])->name('satuan_obats.massDestroy');

Route::resource('suppliers', SupplierController::class)->middleware(['auth', 'verified']);
Route::delete('/suppliers/mass-delete', [SupplierController::class, 'destroy'])->name('suppliers.massDestroy');

Route::resource('pembelian_obats', PembelianObatController::class)->middleware(['auth', 'verified']);
Route::delete('/pembelian_obats/mass-delete', [PembelianObatController::class, 'destroy'])->name('pembelian_obats.massDestroy');
 
Route::resource('penjualan_obats', PenjualanObatController::class)->middleware(['auth', 'verified']);
Route::delete('/penjualan_obats/mass-delete', [PenjualanObatController::class, 'destroy'])->name('penjualan_obats.massDestroy');

// Route untuk Settings
Route::get('/settings', function () {
    return view('settings');
})->middleware(['auth', 'verified'])->name('settings');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include Routes dari Auth
require __DIR__ . '/auth.php';
