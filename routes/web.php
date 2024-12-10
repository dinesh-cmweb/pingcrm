<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductController2;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('cm-admin')->group(function () {
    Route::name('admin.')->middleware('auth')->group(function () {
        Route::prefix('a_categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('categories');
            Route::get('create', [CategoryController::class, 'create'])->name('category.create');
            Route::post('save', [CategoryController::class, 'save'])->name('category.save');
            Route::get('{category}/edit/', [CategoryController::class, 'edit'])->name('category.edit');
            Route::put('{category}/update', [CategoryController::class, 'update'])->name('category.update');
            Route::delete('{category}/delete', [CategoryController::class, 'delete'])->name('category.delete');
        });

        Route::prefix('a_products')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('products');
            Route::get('create', [ProductController::class, 'create'])->name('product.create');
            Route::post('save', [ProductController::class, 'save'])->name('product.save');
            Route::get('{product}/edit/', [ProductController::class, 'edit'])->name('product.edit');
            Route::put('{product}/update', [ProductController::class, 'update'])->name('product.update');
            Route::delete('{product}/delete', [ProductController::class, 'delete'])->name('product.delete');
            Route::delete('/image/{productId}/delete/{imageId}', [ProductController::class, 'deleteImage'])->name('product.deleteImage');
        });

        Route::prefix('a_products2')->group(function () {
            Route::get('/', [ProductController2::class, 'index'])->name('products2');
            Route::get('create', [ProductController2::class, 'create'])->name('product2.create');
            Route::post('save', [ProductController2::class, 'save'])->name('product2.save');
            Route::get('{product}/edit/', [ProductController2::class, 'edit'])->name('product2.edit');
            Route::put('{product}/update', [ProductController2::class, 'update'])->name('product2.update');
            Route::delete('{product}/delete', [ProductController2::class, 'delete'])->name('product2.delete');

            Route::prefix('{product}/product_media')->group(function () {
                Route::get('create', [ProductController2::class, 'createItem'])->name('products2.item.create');
                Route::post('save', [ProductController2::class, 'saveItem'])->name('products2.item.save');
                Route::get('{productMedia}/edit', [ProductController2::class, 'editItem'])->name('products2.item.edit');
                Route::put('{productMedia}/update', [ProductController2::class, 'updateItem'])->name('products2.item.update');
                Route::delete('{productMedia}/delete', [ProductController2::class, 'deleteItem'])->name('products2.item.delete');
            });
        });

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/subscription', [SettingController::class, 'subscription'])->name('subscription');
    });
    require __DIR__ . '/auth.php';
});