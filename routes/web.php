<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
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



Route::middleware('auth')->group(function () {
    route::group(['prefix' => 'cart'], function(){
        Route::get('/', [CartController::class, 'show'])->name('cart');
        Route::post('/item', [CartController::class, 'addItem'])->name('cart.item.add');
        Route::delete('/item/{id}', [CartController::class, 'removeItem'])->name('cart.item.remove');
        Route::get('/items/count', [CartController::class, 'cartItemsCount'])->name('cart.item.count');
        Route::get('/history', [CartController::class, 'getCartsHistory'])->name('cart.history');
        Route::get('/history/count', [CartController::class, 'getCartsHistoryCount'])->name('cart.history.count');
    });
    route::group(['prefix' => 'carts'], function() {
        Route::get('/history', [CartController::class, 'showHistory'])->name('carts.user.history');
    });

    route::group(['prefix' => 'profile'], function(){
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::middleware('role:customer')->group(function () {
        route::group(['prefix' => 'checkout'], function(){
            Route::get('/', [CheckoutController::class, 'show'])->name('page.checkout');
        });
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });


    Route::middleware('role:supplier')->group(function () {
        route::group(['prefix' => 'products'], function() {
            //Route::get('/', [ProductController::class, 'show'])->name('products');
            Route::get('/category/key/{key}', [ProductController::class, 'getByCategoryKey'])->name('products.category.key');
            Route::get('/sales', [ProductController::class, 'showSales'])->name('products.sales');
        });
        route::group(['prefix' => 'product'], function() {
            Route::put('/stock', [ProductController::class, 'setStock'])->name('product.stock.update');
            Route::put('/active', [ProductController::class, 'setActiveState'])->name('product.active.update');
            Route::put('/', [ProductController::class, 'update'])->name('product.update');
            Route::post('/', [ProductController::class, 'create'])->name('product.create');
            Route::delete('/{productId}', [ProductController::class, 'delete'])->name('product.delete');
        });
    });

    Route::middleware('role:admin')->group(function () {
        route::group(['prefix' => 'category'], function() {
            Route::get('/tree/items', [CategoryController::class, 'treeItems'])->name('categories.tree.items');
            Route::get('/test', [CategoryController::class, 'test'])->name('categories.test');
            Route::post('/{key}/sibling/{name}', [CategoryController::class, 'createSibling'])->name('category.create.sibling');
        });
        Route::get('/backend', [DashboardController::class, 'index_backend'])->name('dashboard.backend');
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories');

    });
    Route::middleware('role:admin|supplier')->group(function () {
        Route::get('/tags/topic/{topic?}', [TagController::class, 'getTags'])->name('tags');
        Route::get('/tags/manage', [TagController::class, 'show'])->name('tags.manage');
        Route::get('/tags/queued', [TagController::class, 'queuedTags'])->name('tags.queued');
        Route::get('/tags/active', [TagController::class, 'activeTags'])->name('tags.active');
    });
});
Route::get('/tag/topics', [TagController::class, 'getTopics'])->name('tag.topics');
Route::get('/tags/tree', [TagController::class, 'tree'])->name('tags.tree');
Route::get('/', [PageController::class, 'index'])->name('welcome')->middleware(InjectLocaleData::class);
Route::get('/category/tree/{rootLabel?}/{parentId?}', [CategoryController::class, 'tree'])->name('category.tree');
Route::get('/product/{id}', [ProductController::class, 'getById'])->name('product.details.by.id');

Route::get('/products/category/{categoryId}/{categoryParentId}', [ProductController::class, 'show'])->name('products.by.category.ids');
//Route::get('/products/filter', [ProductController::class, 'filter'])->name('products.by.filters');
Route::get('/products', [ProductController::class, 'filter'])->name('products');
require __DIR__.'/auth.php';
