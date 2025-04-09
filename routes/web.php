<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminAuthController;;

Route::get('/', [ProductController::class, 'index']);

Route::get('/products/{product_id}', [ProductController::class, 'show'])->name('products.show');

Route::get('/login', [AdminAuthController::class, 'loginPage'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
Route::get('/logout', [AdminAuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->prefix('/admin')->group(function () {
    Route::get('/products', [AdminProductController::class, 'products'])->name('admin.products');
    Route::get('/products/add', [AdminProductController::class, 'addProductForm'])->name('admin.add.product');
    Route::post('/products/add', [AdminProductController::class, 'addProduct'])->name('admin.add.product.submit');
    Route::get('/products/edit/{id}', [AdminProductController::class, 'editProduct'])->name('admin.edit.product');
    Route::post('/products/edit/{id}', [AdminProductController::class, 'updateProduct'])->name('admin.update.product');
    Route::get('/products/delete/{id}', [AdminProductController::class, 'deleteProduct'])->name('admin.delete.product');
});
