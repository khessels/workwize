<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [PageController::class, 'index'])->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->middleware(['verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::middleware('role:customer')->group(function () {
        Route::get('/cart', [CartController::class, 'show'])->name('cart');
        Route::post('/cart/checkout', [CartController::class, 'checkOut'])->name('checkout');
        Route::get('/carts/history', [CartController::class, 'showHistory'])->name('carts.user.history');
        Route::post('/cart/item', [CartController::class, 'addItem'])->name('cart.item.add');
        Route::delete('/cart/item/{id}', [CartController::class, 'removeItem'])->name('cart.item.remove');
    });
    Route::middleware('role:supplier')->group(function () {
        Route::get('/products', [ProductController::class, 'show'])->name('products');
        Route::get('/products/sales', [ProductController::class, 'showSales'])->name('products.sales');
        Route::put('/product/stock', [ProductController::class, 'setStock'])->name('product.stock.update');
        Route::put('/product/active', [ProductController::class, 'setActiveState'])->name('product.active.update');
        Route::put('/product', [ProductController::class, 'update'])->name('product.update');
        Route::post('/product', [ProductController::class, 'create'])->name('product.create');
        Route::delete('/product/{productId}', [ProductController::class, 'delete'])->name('product.delete');
    });
});

require __DIR__.'/auth.php';
