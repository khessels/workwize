<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\InjectLocaleData;


    Route::get('/', [PageController::class, 'index'])->name('welcome')->middleware(InjectLocaleData::class);
    Route::get('/category/tree/{rootLabel?}/{parentId?}', [CategoryController::class, 'tree'])->name('category.tree');

    Route::middleware('auth')->group(function () {

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::middleware('role:customer')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/cart', [CartController::class, 'show'])->name('cart');
            Route::post('/cart/checkout', [CartController::class, 'checkOut'])->name('checkout');
            Route::get('/carts/history', [CartController::class, 'showHistory'])->name('carts.user.history');
            Route::post('/cart/item', [CartController::class, 'addItem'])->name('cart.item.add');
            Route::delete('/cart/item/{id}', [CartController::class, 'removeItem'])->name('cart.item.remove');
        });

        Route::middleware('role:supplier')->group(function () {
            Route::get('/products', [ProductController::class, 'show'])->name('products');
            Route::get('/products/category/key/{key}', [ProductController::class, 'getByCategoryKey'])->name('products.category.key');
            Route::get('/products/sales', [ProductController::class, 'showSales'])->name('products.sales');
            Route::put('/product/stock', [ProductController::class, 'setStock'])->name('product.stock.update');
            Route::put('/product/active', [ProductController::class, 'setActiveState'])->name('product.active.update');
            Route::put('/product', [ProductController::class, 'update'])->name('product.update');
            Route::post('/product', [ProductController::class, 'create'])->name('product.create');
            Route::delete('/product/{productId}', [ProductController::class, 'delete'])->name('product.delete');
        });

        Route::middleware('role:admin')->group(function () {
            Route::get('/backend', [DashboardController::class, 'index_backend'])->name('dashboard.backend');
            Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
            Route::get('/category/tree/items', [CategoryController::class, 'treeItems'])->name('categories.tree.items');
            Route::get('/category/test', [CategoryController::class, 'test'])->name('categories.test');
        });
        Route::middleware('role:admin|supplier')->group(function () {
            Route::get('/tags/{topic?}', [TagController::class, 'getTags'])->name('tags');
            Route::get('/tag/topics', [TagController::class, 'getTopics'])->name('tag.topics');

        });
    });


require __DIR__.'/auth.php';
