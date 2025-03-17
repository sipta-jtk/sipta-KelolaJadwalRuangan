<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RuanganController;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\PenjadwalanController;

Route::get('/', function () {
    return view('welcome');
});

// ==================== Route untuk manajemen ruangan ====================
// 
// 
// =====================================================================
Route::resource('admin/ruangan', RuanganController::class);
Route::get('/admin/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
Route::get('/admin/ruangan/create', [RuanganController::class, 'create'])->name('ruangan.create');
Route::post('/admin/ruangan', [RuanganController::class, 'store'])->name('ruangan.store');

// Route untuk manajemen gedung (jika belum ada)
Route::resource('gedung', GedungController::class);

Route::get('admin/ruangan/{filename}', function ($filename) {
    $path = base_path('admin/ruangan/' . $filename);
    
    if (!File::exists($path)) {
        abort(404);
    }
    
    return response()->file($path);
})->where('filename', '.*');

Route::group(['prefix' => ''], function () {
    Route::get('penjadwalan-ruangan', [PenjadwalanController::class, 'index']); //bisa di postman
});
