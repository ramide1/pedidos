<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoriaProductoController;
use App\Http\Controllers\ProductoController;
use App\Http\Middleware\EnsureUserIsAdmin;

Route::get('/', MarketplaceController::class)->name('home');

Route::get('restaurantes/menu/{restaurante}', [RestauranteController::class, 'menu'])->name('restaurantes.menu');
Route::post('pedidos/store', [PedidoController::class, 'store'])->name('pedidos.store');

Route::middleware(['auth', 'verified', EnsureUserIsAdmin::class])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::resource('restaurantes', RestauranteController::class);
    Route::post('restaurantes/duplicate/{restaurante}', [RestauranteController::class, 'duplicate'])->name('restaurantes.duplicate');
    Route::resource('categorias', CategoriaProductoController::class);
    Route::post('categorias/duplicate/{categoria}', [CategoriaProductoController::class, 'duplicate'])->name('categorias.duplicate');
    Route::resource('productos', ProductoController::class);
    Route::post('productos/duplicate/{producto}', [ProductoController::class, 'duplicate'])->name('productos.duplicate');
    Route::resource('pedidos', PedidoController::class);
    Route::put('pedidos/marcar-como-pagado/{pedido}', [PedidoController::class, 'markAsPaid'])->name('pedidos.markAsPaid');
});

require __DIR__ . '/settings.php';
