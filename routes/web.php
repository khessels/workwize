<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $roles =  Auth::user()->roles->pluck('name')->toArray();
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'roles' => $roles,
    ]);
});



Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $roles =  Auth::user()->roles->pluck('name')->toArray();
        return Inertia::render('Dashboard', ['roles' => $roles]);
    })->middleware(['auth', 'verified'])->name('dashboard');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::middleware('role:customer')->group(function () {
        Route::get('/customer/products', [ProductController::class, 'CustomerList'])->name('customer.product.list');
    });
    Route::middleware('role:supplier')->group(function () {
        Route::get('/supplier/products', [ProductController::class, 'SupplierList'])->name('supplier.product.list');
    });

});



require __DIR__.'/auth.php';
