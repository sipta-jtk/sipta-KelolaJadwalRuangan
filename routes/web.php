<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\GedungController;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\PenjadwalanController;
use App\Http\Middleware\VerifySiptaToken; 

// Redirect root to penjadwalan-ruangan
Route::get('/', function () {
    return redirect()->route('penjadwalan.index');
});


// ==================== Route untuk manajemen ruangan ====================
// Gunakan salah satu pendekatan saja, jangan keduanya
// Pendekatan 1: Resource Route (direkomendasikan)
// Route::resource('admin/ruangan', RuanganController::class);

// Pendekatan 2: Manual Routes
Route::middleware(\App\Http\Middleware\VerifySiptaToken::class.':admin')->group(function () {
    Route::get('/admin/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
    Route::get('/admin/ruangan/create', [RuanganController::class, 'create'])->name('ruangan.create');
    Route::post('/admin/ruangan', [RuanganController::class, 'store'])->name('ruangan.store');
    Route::get('/admin/ruangan/{id}/edit', [RuanganController::class, 'edit'])->name('ruangan.edit');
    Route::put('/admin/ruangan/{id}', [RuanganController::class, 'update'])->name('ruangan.update');
    Route::delete('/admin/ruangan/{id}', [RuanganController::class, 'destroy'])->name('ruangan.destroy');
});

// Route untuk manajemen gedung
Route::resource('gedung', GedungController::class);

// Pindahkan route file ke bawah dan buat lebih spesifik
Route::get('admin/ruangan/file/{filename}', function ($filename) {
    $path = base_path('admin/ruangan/' . $filename);
    
    if (!File::exists($path)) {
        abort(404);
    }
    
    return response()->file($path);
})->where('filename', '.*');

Route::group(['prefix' => ''], function () {
    Route::get('penjadwalan-ruangan', [PenjadwalanController::class, 'index']); //bisa di postman
});
