<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Store\HomeController;
use App\Http\Controllers\Store\ProductController;
use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\Store\CheckoutController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas de la tienda online
Route::prefix('tienda')->name('store.')->group(function () {
    // Página principal de la tienda
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    // Productos
    Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
    Route::get('/productos/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/categoria/{category}', [ProductController::class, 'category'])->name('products.category');
    
    // Carrito de compras
    Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
    Route::get('/carrito/count', [CartController::class, 'count'])->name('cart.count');
    Route::post('/carrito/agregar', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/carrito/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/carrito/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/carrito', [CartController::class, 'clear'])->name('cart.clear');
    
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/exito/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    
    // Órdenes del usuario (requiere autenticación)
    Route::middleware('auth')->group(function () {
        Route::get('/mis-pedidos', [CheckoutController::class, 'orders'])->name('orders.index');
        Route::get('/mis-pedidos/{order}', [CheckoutController::class, 'orderShow'])->name('orders.show');
    });
});
