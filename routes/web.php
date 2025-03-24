<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GedungController;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\RuanganController;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk manajemen gedung
Route::resource('gedung', GedungController::class);

// Pindahkan route file ke bawah dan buat lebih spesifik
Route::get('/admin/ruangan/file/{filename}', function ($filename) {
    $path = base_path('image/ruangan/' . $filename);
    
    if (!File::exists($path)) {
        abort(404);
    }
    
    return response()->file($path);
})->where('filename', '.*');

Route::prefix('admin')->group(function () {
    // Routes untuk menampilkan halaman/view
    Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
    Route::get('/ruangan/create', [RuanganController::class, 'create'])->name('ruangan.create');
    Route::get('/ruangan/{id}/edit', [RuanganController::class, 'edit'])->name('ruangan.edit');
});