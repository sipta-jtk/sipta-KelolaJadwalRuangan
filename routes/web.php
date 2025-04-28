<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GedungController;
use Illuminate\Support\Facades\File;
<<<<<<< HEAD
<<<<<<< HEAD
use App\Http\Controllers\RuanganController;
=======
use App\Http\Middleware\VerifySiptaToken; 
>>>>>>> 54b7a72ba6f065ae6070487e0eb039e17830888b
=======
use App\Http\Controllers\PenjadwalanController;
use App\Http\Middleware\VerifySiptaToken; 
use App\Http\Controllers\PdfController;
use App\Http\Controllers\AuthController;
>>>>>>> a4f730104d3d990a99d5a23aac0c813c60462830

// Redirect root to penjadwalan-ruangan
Route::get('/', function () {
    return redirect('penjadwalan-ruangan');
});

// Authentication routes
Route::middleware('guest')->prefix('penjadwalan-ruangan')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->prefix('penjadwalan-ruangan')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

<<<<<<< HEAD
=======
// ==================== Route untuk manajemen ruangan ====================
// Gunakan salah satu pendekatan saja, jangan keduanya
// Pendekatan 1: Resource Route (direkomendasikan)
// Route::resource('admin/ruangan', RuanganController::class);

// Pendekatan 2: Manual Routes
Route::middleware(\App\Http\Middleware\VerifySiptaToken::class.':admin')
    ->prefix('penjadwalan-ruangan')->group(function () {
        Route::get('/admin/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
        Route::get('/admin/ruangan/create', [RuanganController::class, 'create'])->name('ruangan.create');
        Route::post('/admin/ruangan', [RuanganController::class, 'store'])->name('ruangan.store');
        Route::get('/admin/ruangan/{id}/edit', [RuanganController::class, 'edit'])->name('ruangan.edit');
        Route::put('/admin/ruangan/{id}', [RuanganController::class, 'update'])->name('ruangan.update');
        Route::delete('/admin/ruangan/{id}', [RuanganController::class, 'destroy'])->name('ruangan.destroy');
        Route::get('admin/ruangan/file/{filename}', function ($filename) {
            $path = base_path('admin/ruangan/' . $filename);
            
            if (!File::exists($path)) {
                abort(404);
            }
            
            return response()->file($path);
        })->where('filename', '.*');
        Route::get('/download-schedule-pdf', [PdfController::class, 'downloadSchedulePdf'])->name('schedule.pdf.download');
});
<<<<<<< HEAD
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
=======

// Route untuk manajemen gedung
Route::resource('gedung', GedungController::class);

Route::group(['prefix' => ''], function () {
    Route::get('penjadwalan-ruangan', [PenjadwalanController::class, 'index'])->name('penjadwalan.index'); //bisa di postman
});

>>>>>>> a4f730104d3d990a99d5a23aac0c813c60462830
