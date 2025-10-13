<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController; // ✅ Controller bạn đã tạo để xử lý đăng nhập / đăng ký
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AddressController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Các route chính của website bán điện thoại
|--------------------------------------------------------------------------
*/

// 🏠 Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// 📱 Chi tiết sản phẩm
Route::get('/san-pham/{id}', [ProductController::class, 'show'])->name('product.show');

// 🛒 Giỏ hàng (người dùng)
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/cart', [HomeController::class, 'cart'])->name('cart');
});

// ⚙️ Quản trị (admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::resource('/admin/products', \App\Http\Controllers\ProductController::class);

    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
});

// ==========================================================
// 🔐 AUTH — Đăng ký / Đăng nhập / Đăng xuất (dành cho form bạn tự thiết kế)
// ==========================================================
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
});

// Xem giỏ hàng
Route::get('/cart', [CartController::class, 'viewCart'])->name('cart');

// Xóa sản phẩm
Route::delete('/cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');

// Thanh toán sản phẩm đã chọn
Route::post('/checkout/selected', [CartController::class, 'checkoutSelected'])->name('checkout.selected');
Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');
Route::get('/checkout-now/{id}', [CartController::class, 'checkoutNow'])->name('checkout.now');
Route::post('/address/store', [AddressController::class, 'store'])->name('address.store');
Route::get('/address/manage', [AddressController::class, 'manage'])->name('address.manage');
Route::post('/address/set-default/{id}', [AddressController::class, 'setDefault'])->name('address.setDefault');
Route::delete('/address/delete/{id}', [AddressController::class, 'destroy'])->name('address.delete');
Route::get('/address/create', [AddressController::class, 'create'])->name('address.create');
Route::get('/addresses/json', [AddressController::class, 'getAddressesJson'])->name('addresses.json');
// trong file routes/web.php

Route::delete('/address/{id}', [AddressController::class, 'destroy'])->name('address.destroy');


Route::post('/address/set-default/{id}', [AddressController::class, 'setDefault'])->name('address.setDefault');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ==========================================================
// ⚙️ (Tùy chọn) Nếu bạn vẫn muốn giữ route của Breeze, có thể để lại bên dưới
// ==========================================================
// require __DIR__ . '/auth.php';
