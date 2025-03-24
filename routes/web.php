<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GedungController;
use Illuminate\Support\Facades\File;
<<<<<<< HEAD
use App\Http\Controllers\RuanganController;
=======
use App\Http\Middleware\VerifySiptaToken; 
>>>>>>> 54b7a72ba6f065ae6070487e0eb039e17830888b

Route::get('/', function () {
    return view('welcome');
});

<<<<<<< HEAD
=======
// ==================== Route untuk manajemen ruangan ====================
// Gunakan salah satu pendekatan saja, jangan keduanya
// Pendekatan 1: Resource Route (direkomendasikan)
// Route::resource('admin/ruangan', RuanganController::class);

// Pendekatan 2: Manual Routes
Route::middleware(\App\Http\Middleware\VerifySiptaToken::class.':dosen')->group(function () {
    Route::get('/admin/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
    Route::get('/admin/ruangan/create', [RuanganController::class, 'create'])->name('ruangan.create');
    Route::post('/admin/ruangan', [RuanganController::class, 'store'])->name('ruangan.store');
    Route::get('/admin/ruangan/{id}/edit', [RuanganController::class, 'edit'])->name('ruangan.edit');
    Route::put('/admin/ruangan/{id}', [RuanganController::class, 'update'])->name('ruangan.update');
    Route::delete('/admin/ruangan/{id}', [RuanganController::class, 'destroy'])->name('ruangan.destroy');
});
>>>>>>> 54b7a72ba6f065ae6070487e0eb039e17830888b
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