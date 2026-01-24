<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\MarketplaceController::class)->name('home');

Route::get('dashboard', \App\Http\Controllers\DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Public Marketplace / Ordering Routes
Route::get('/restaurantes/{restaurante}/menu', [\App\Http\Controllers\RestauranteController::class, 'menu'])->name('restaurantes.menu');
Route::post('/pedidos/store', [\App\Http\Controllers\PedidoController::class, 'publicStore'])->name('public.pedidos.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('restaurantes/{restaurante}/duplicate', [\App\Http\Controllers\RestauranteController::class, 'duplicate'])->name('restaurantes.duplicate');
    Route::resource('restaurantes', \App\Http\Controllers\RestauranteController::class);

    Route::post('categorias/{categoria}/duplicate', [\App\Http\Controllers\CategoriaProductoController::class, 'duplicate'])->name('categorias.duplicate');
    Route::resource('categorias', \App\Http\Controllers\CategoriaProductoController::class);

    Route::post('productos/{producto}/duplicate', [\App\Http\Controllers\ProductoController::class, 'duplicate'])->name('productos.duplicate');
    Route::resource('productos', \App\Http\Controllers\ProductoController::class);

    Route::resource('pedidos', \App\Http\Controllers\PedidoController::class);
});

require __DIR__ . '/settings.php';
