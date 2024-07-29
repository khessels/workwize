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
        Route::get('/carts/history', [CartController::class, 'showHistory'])->name('carts.history');
    });
    Route::middleware('role:supplier')->group(function () {
        Route::get('/products/manage', [ProductController::class, 'show'])->name('products.manage');
    });

});

require __DIR__.'/auth.php';
