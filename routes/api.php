<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnggahPenjadwalanController;
use App\Http\Controllers\PenjadwalanController;
use App\Http\Controllers\RuanganController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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
    Route::post('schedule/action', [UnggahPenjadwalanController::class, 'aksiKalender']); //bisa di postman
});