<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnggahPenjadwalanController;
use App\Http\Controllers\PenjadwalanController;
use App\Http\Controllers\RuanganController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('ruangan/nama', [RuanganController::class, 'allruanganname']); //bisa di postman
    Route::get("schedules", [PenjadwalanController::class, "getEvent"]); //bisa di postman
    Route::post('schedule/action', [UnggahPenjadwalanController::class, 'action']); //bisa di postman
});