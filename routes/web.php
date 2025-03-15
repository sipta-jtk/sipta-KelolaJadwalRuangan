<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenjadwalanController;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => ''], function () {
    Route::get('penjadwalan-ruangan', [PenjadwalanController::class, 'index']); //bisa di postman
});
