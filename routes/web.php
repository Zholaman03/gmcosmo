<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ImportController;

Route::get('/', [ProductController::class, 'index'])->name('product');

//category filter route
Route::get('/category/{category}', [ProductController::class, 'filterByCategory'])->name('category.filter');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/import', [ImportController::class, 'showImportForm'])->name('admin.import');
    Route::get('/admin/orders', [AdminController::class, 'showOrders'])->name('admin.orders');
    Route::get('/admin/products', [AdminController::class, 'showProducts'])->name('admin.products');
    Route::delete('/admin/orders/{item}', [AdminController::class, 'deleteOrderItem'])->name('admin.orders.delete');
    Route::post('/import-products', [ImportController::class, 'import']);
});


Route::post('/order', [ProductController::class, 'order'])->name('order');

//auth routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');