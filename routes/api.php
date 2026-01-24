<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserApiController;
use App\Http\Controllers\PedidoController;

Route::controller(UserApiController::class)->group(function () {
    //post
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::post('/logoutAll', 'logoutAll')->middleware('auth:sanctum');
    //get
    Route::get('/user', 'user')->middleware('auth:sanctum');
});

Route::controller(PedidoController::class)->group(function () {
    //post
    Route::post('/store', 'store')->middleware('auth:sanctum');
});
