<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
=======
use App\Http\Controllers\UnggahPenjadwalanController;
use App\Http\Controllers\PenjadwalanController;
>>>>>>> a4f730104d3d990a99d5a23aac0c813c60462830
use App\Http\Controllers\RuanganController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

<<<<<<< HEAD
// API Routes untuk manajemen ruangan
Route::prefix('v1/admin')->group(function () {
    Route::post('/ruangan', [RuanganController::class, 'store'])->name('ruangan.store');
    Route::put('/ruangan/{id}', [RuanganController::class, 'update'])->name('ruangan.update');
    Route::delete('/ruangan/{id}', [RuanganController::class, 'destroy'])->name('ruangan.destroy');
});
=======
// Route::prefix('v1')->middleware('auth')->group(function () {
//     // untuk mendapatkan nama ruangan dengan status_ruangan tersedia
//     Route::get('rooms/names', [RuanganController::class, 'namaRuanganTersedia']); //bisa di postman
//     // untuk mendapatkan ruangan yang dapat dipilih berdasarkan tanggal dan sesi
//     Route::get('schedule/rooms', [RuanganController::class, 'ruanganUntukPenjadwalan']); //bisa di postman
//     // untuk mendapatkan sesi yang dapat dipilih berdasarkan tanggal
//     Route::get('schedule/sessions/{tanggal}', [PenjadwalanController::class, 'sesiUntukPenjadwalan']); //bisa di postman
//     // untuk mendapatkan jadwal kegiatan pada waktu dan ruang tertentu
//     Route::get("schedules", [PenjadwalanController::class, "agenda"]); //bisa di postman
//     // untuk melakukan add, update, dan delete terhadap jadwal
//     Route::post('schedule/action', [UnggahPenjadwalanController::class, 'aksiKalender']); //bisa di postman
// });

Route::prefix('v1')->group(function () {
    // untuk mendapatkan nama ruangan dengan status_ruangan tersedia
    Route::get('rooms/names', [RuanganController::class, 'namaRuanganTersedia']); //bisa di postman
    // untuk mendapatkan ruangan yang dapat dipilih berdasarkan tanggal dan sesi
    Route::get('schedule/rooms', [RuanganController::class, 'ruanganUntukPenjadwalan']); //bisa di postman
    // untuk mendapatkan sesi yang dapat dipilih berdasarkan tanggal
    Route::get('schedule/sessions/{tanggal}', [PenjadwalanController::class, 'sesiUntukPenjadwalan']); //bisa di postman
    // untuk mendapatkan jadwal kegiatan pada waktu dan ruang tertentu
    Route::get("schedules", [PenjadwalanController::class, "agenda"]); //bisa di postman
    // untuk melakukan add, update, dan delete terhadap jadwal
    Route::post('schedule/action', [UnggahPenjadwalanController::class, 'aksiKalender'])
        ->middleware(\App\Http\Middleware\VerifySiptaToken::class.':admin'); // Apply middleware here
});
>>>>>>> a4f730104d3d990a99d5a23aac0c813c60462830
