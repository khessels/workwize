<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Models\Product;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $roles = [];
    if(Auth::check()){
        $roles =  Auth::user()->roles->pluck('name')->toArray();
    }
    $products = Product::where('quantity', '>')->where('Active', 'YES')->orderBy('name', 'ASC')->get()->toArray();
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'roles' => $roles,
        'products' => $products,
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
