<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\GedungController;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\PenjadwalanController;
use App\Http\Middleware\VerifySiptaToken; 
use App\Http\Controllers\PdfController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FasilitasController;

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
        Route::get('/admin/fasilitas', [FasilitasController::class, 'index'])->name('fasilitas.index');
        Route::post('/admin/fasilitas', [FasilitasController::class, 'store'])->name('fasilitas.store');
        Route::put('/admin/fasilitas/{id_fasilitas}', [FasilitasController::class, 'update'])->name('fasilitas.update');
        Route::delete('/admin/fasilitas/{id_fasilitas}', [FasilitasController::class, 'destroy'])->name('fasilitas.destroy');
});

        // Route untuk gedung
        Route::get('/admin/gedung', [GedungController::class, 'index'])->name('gedung.index');
        Route::post('/admin/gedung', [GedungController::class, 'store'])->name('gedung.store');
        Route::put('/admin/gedung/{kode_gedung}', [GedungController::class, 'update'])->name('gedung.update');
        Route::delete('/admin/gedung/{kode_gedung}', [GedungController::class, 'destroy'])->name('gedung.destroy');
});

Route::group(['prefix' => ''], function () {
    Route::get('penjadwalan-ruangan', [PenjadwalanController::class, 'index'])->name('penjadwalan.index'); //bisa di postman
});
