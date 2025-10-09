<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Trang chủ hiển thị danh sách sản phẩm
Route::get('/', [HomeController::class, 'index'])->name('home');

// Laravel Breeze routes (đăng nhập, đăng ký, v.v.)
require __DIR__.'/auth.php';
use App\Http\Controllers\ProductController;

Route::get('/san-pham/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/cart', [HomeController::class, 'cart'])->name('cart');
