<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\DetailPengadaanController;
// use App\Http\Controllers\ReturController;



Route::get('/', function () {
    return view('welcome'); // Pastikan ada file welcome.blade.php di views
});

Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create'); // Form tambah barang
Route::post('/barang', [BarangController::class, 'store'])->name('barang.store'); // Simpan barang baru
Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit'); // Form edit barang
Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update'); // Update barang
Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy'); // Hapus barang

// Routes untuk Vendor
Route::get('/vendor', [VendorController::class, 'index'])->name('vendor.index');
Route::get('/vendor/create', [VendorController::class, 'create'])->name('vendor.create');
Route::post('/vendor', [VendorController::class, 'store'])->name('vendor.store');
Route::get('/vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit');
Route::put('/vendor/{id}', [VendorController::class, 'update'])->name('vendor.update');
Route::delete('/vendor/{id}', [VendorController::class, 'destroy'])->name('vendor.destroy');
Route::get('/vendors/list', [VendorController::class, 'list']);

// Routes untuk Satuan
Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan.index');
Route::get('/satuan/create', [SatuanController::class, 'create'])->name('satuan.create');
Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan.store');
Route::get('/satuan/{id}/edit', [SatuanController::class, 'edit'])->name('satuan.edit');
Route::put('/satuan/{id}', [SatuanController::class, 'update'])->name('satuan.update');
Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan.destroy');

// Routes untuk Role
Route::get('/role', [RoleController::class, 'index'])->name('role.index');
Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');
Route::post('/role', [RoleController::class, 'store'])->name('role.store');
Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');
Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');

// Routes untuk User
Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
Route::post('/user', [UserController::class, 'store'])->name('user.store');
Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

Route::get('/pengadaan', [PengadaanController::class, 'index'])->name('pengadaan.index');
Route::get('/pengadaan/store', [PengadaanController::class, 'create'])->name('pengadaan.create');
Route::post('/pengadaan/store', [PengadaanController::class, 'store'])->name('pengadaan.store');
Route::get('/pengadaan/{id}/edit', [PengadaanController::class, 'edit'])->name('pengadaan.edit');
Route::put('/pengadaan/{id}', [PengadaanController::class, 'update'])->name('pengadaan.update');
Route::delete('/pengadaan/{id}', [PengadaanController::class, 'destroy'])->name('pengadaan.destroy');
Route::get('/pengadaan/{id}', [PengadaanController::class, 'show'])->name('pengadaan.show');


// Route::resource('detail_pengadaan', DetailPengadaanController::class);
// Route::resource('penerimaan', PenerimaanController::class);
Route::get('/pengadaan/detail/{id}', [PengadaanController::class, 'showDetail'])->name('pengadaan.detail');
Route::post('/pengadaan/terima/{idpengadaan}', [PengadaanController::class, 'terima'])->name('pengadaan.terima');

Route::post('/pengadaan/tunda/{id}', [PengadaanController::class, 'tunda'])->name('pengadaan.tunda');
Route::put('/pengadaan/{id}', [PengadaanController::class, 'update'])->name('pengadaan.update');


Route::post('/terima-barang', [PenerimaanController::class, 'terimaBarang']);
// penerimaan
Route::get('/penerimaan', [PenerimaanController::class, 'index']);
Route::get('penerimaan/{idpenerimaan}', [PenerimaanController::class, 'show'])->name('penerimaan.show');
Route::get('penerimaan/{idpenerimaan}/edit', [PenerimaanController::class, 'edit'])->name('penerimaan.edit');
Route::delete('penerimaan/{idpenerimaan}', [PenerimaanController::class, 'destroy'])->name('penerimaan.destroy');

//detail penerimaan
Route::get('/penerimaan/{id}/detail', [PenerimaanController::class, 'detail'])->name('penerimaan.detail');







// Route::get('/retur', [ReturController::class, 'index'])->name('retur.index');




Route::get('/caribarang', [BarangController::class, 'show'])->name('barang.show');

