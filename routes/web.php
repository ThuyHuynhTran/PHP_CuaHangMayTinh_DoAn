<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Các route chính của website bán điện thoại
|--------------------------------------------------------------------------
*/

// 🏠 Trang chủ (hiển thị danh sách điện thoại)
Route::get('/', [HomeController::class, 'index'])->name('home');

// 📱 Trang chi tiết sản phẩm
Route::get('/san-pham/{id}', [ProductController::class, 'show'])->name('product.show');

// 🛒 Trang giỏ hàng (chỉ khi user đã đăng nhập)
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/cart', [HomeController::class, 'cart'])->name('cart');
});

// ⚙️ Khu vực quản trị (chỉ cho admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    // Ví dụ: route quản lý sản phẩm, đơn hàng, người dùng
    Route::resource('/admin/products', \App\Http\Controllers\Admin\ProductController::class);
    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
});

// 🔐 Auth routes (Laravel Breeze tự động cung cấp)
require __DIR__ . '/auth.php';

// 🚪 Logout route thủ công (nếu cần)
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
