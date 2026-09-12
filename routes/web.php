<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\OrderController;

Route::get('/', [ProductController::class, 'index'])->name('product');

//category filter route
Route::get('/category/{category}', [ProductController::class, 'filterByCategory'])->name('category.filter');

Route::middleware(['auth'])->group(function () {
    // Admin routes
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/products', [AdminController::class, 'showProducts'])->name('admin.products');
    Route::get('/admin/products/create', [AdminController::class, 'showCreateProductForm'])->name('admin.products.create');
    Route::get('/admin/products/{id}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::put('/admin/products/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::post('/admin/products/create', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::delete('/admin/products/{id}', [AdminController::class, 'destroyProduct'])->name('admin.products.destroy');
    
    // ImportController routes
    Route::get('/admin/import', [ImportController::class, 'showImportForm'])->name('admin.import');
    Route::post('/import-products', [ImportController::class, 'import']);
    // OrderController routes
    Route::get('/admin/orders', [OrderController::class, 'showOrders'])->name('admin.orders');
    Route::delete('/admin/orders/{item}', [OrderController::class, 'deleteOrderItem'])->name('admin.orders.delete');
});


Route::post('/order', [ProductController::class, 'order'])->name('order');

//auth routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');