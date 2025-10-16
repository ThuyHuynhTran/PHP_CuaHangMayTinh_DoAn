<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ProductController,
    AdminController,
    AuthController,
    CartController,
    AddressController,
    CheckoutController,
    ProfileController
};
use App\Http\Controllers\Auth\{
    ForgotPasswordController,
    ResetPasswordController
};
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReviewController;
use App\Models\DanhMuc;
use App\Models\DienThoai;

use App\Http\Controllers\ChatController;

Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
Route::get('/admin/messages', [ChatController::class, 'index'])->middleware(['auth', 'role:admin'])->name('admin.messages');


// 🟢 API trả danh mục
Route::get('/api/categories', function () {
    $danhMucs = DanhMuc::select('id', 'ten_danh_muc')->get();
    return response()->json([
        'success' => true,
        'categories' => $danhMucs
    ]);
});
use App\Http\Controllers\FaqController;

// Khách xem FAQ
Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

// Admin quản lý FAQ
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/faqs', [FaqController::class, 'adminIndex'])->name('admin.faq.index');
    Route::post('/admin/faqs', [FaqController::class, 'store'])->name('admin.faq.store');
    Route::patch('/admin/faqs/{faq}', [FaqController::class, 'update'])->name('admin.faq.update');
    Route::delete('/admin/faqs/{faq}', [FaqController::class, 'destroy'])->name('admin.faq.delete');
});

use App\Http\Controllers\BrandController;

Route::get('/brands', [BrandController::class, 'index']);

use App\Http\Controllers\CategoryController;

Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');

Route::post('/promotion/subscribe', [PromotionController::class, 'subscribe'])->name('promotion.subscribe');
Route::get('/notifications', [PromotionController::class, 'notifications'])->name('notifications');
Route::get('/api/notifications', [PromotionController::class, 'getNotifications']);
// routes/web.php


Route::post('/cart/apply-promotion', [CartController::class, 'applyPromotion'])->name('cart.applyPromotion');

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');
use App\Http\Controllers\SearchController;

Route::get('/search', [SearchController::class, 'search'])->name('search');






/*
|--------------------------------------------------------------------------
| 🔑 QUÊN MẬT KHẨU
|--------------------------------------------------------------------------
*/
// Form nhập email để gửi link reset
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

// Gửi email reset password
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

// Form nhập mật khẩu mới (từ link email)
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

// Xử lý đặt lại mật khẩu
Route::post('password/reset', [ResetPasswordController::class, 'reset'])
    ->name('password.update');


/*
|--------------------------------------------------------------------------
| 🏠 TRANG CHỦ & SẢN PHẨM
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/san-pham/{id}', [ProductController::class, 'show'])->name('product.show');

/*
|--------------------------------------------------------------------------
| ⭐ ĐÁNH GIÁ SẢN PHẨM
|--------------------------------------------------------------------------
*/
Route::post('/review', [ReviewController::class, 'store'])
    ->middleware('auth')
    ->name('review.store');

/*
|--------------------------------------------------------------------------
| 🔐 ĐĂNG KÝ / ĐĂNG NHẬP
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| 👤 PROFILE NGƯỜI DÙNG
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| 🛒 GIỎ HÀNG & THANH TOÁN (ROLE: user)
|--------------------------------------------------------------------------
*/
Route::post('/checkout/selected', [CartController::class, 'checkoutSelected'])->name('checkout.selected'); 
Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process'); 
 // === 🔹 PHẦN SỬA LỖI & HỢP NHẤT 🔹 ===
    // Route này sẽ hiển thị trang thanh toán cho luồng "Thanh toán từ giỏ hàng"
    Route::get('/checkout', [CheckoutController::class, 'checkoutNow'])->name('checkout');
    // Route này sẽ hiển thị trang thanh toán cho luồng "Mua ngay"
    Route::get('/checkout/{id}', [CheckoutController::class, 'checkoutNow'])->name('checkout.now');
    // =======================================
// ✅ Mua ngay 1 sản phẩm
 Route::get('/checkout-now/{id}', [CheckoutController::class, 'checkoutNow'])->name('checkout.now');
Route::middleware(['auth', 'role:user'])->group(function () {
    // 🛍 Giỏ hàng
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart');
    Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');

    // ✅ Mua ngay
    Route::get('/checkout-now/{id}', [CheckoutController::class, 'checkoutNow'])->name('checkout.now');

    // ✅ Thanh toán & lưu đơn hàng
    Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');

    // ✅ Xem lịch sử đơn hàng
    Route::get('/orders/history', [CheckoutController::class, 'orderHistory'])->name('orders.history');
    // routes/web.php
Route::post('/orders/cancel', [CheckoutController::class, 'cancel'])->name('orders.cancel');

    
});


/*
|--------------------------------------------------------------------------
| 🏠 ADMIN (ROLE: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('/admin/products', ProductController::class);
    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
});

/*
|--------------------------------------------------------------------------
| 📦 QUẢN LÝ ĐỊA CHỈ NGƯỜI DÙNG
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/address/manage', [AddressController::class, 'manage'])->name('address.manage');
    Route::get('/address/create', [AddressController::class, 'create'])->name('address.create');
    Route::post('/address/store', [AddressController::class, 'store'])->name('address.store');
Route::post('/address/set-default', [AddressController::class, 'setDefault'])->name('address.setDefault');

    Route::delete('/address/delete/{id}', [AddressController::class, 'destroy'])->name('address.delete');
    Route::get('/addresses/json', [AddressController::class, 'getAddressesJson'])->name('addresses.json');
});
Route::get('/orders/success/{orderId}', [CheckoutController::class, 'success'])
    ->name('orders.success');
Route::get('/orders/{id}', [CheckoutController::class, 'show'])->name('orders.show');
Route::get('/orders', [CheckoutController::class, 'myorder'])->name('orders.myorder');
use App\Http\Controllers\WishlistController;
Route::get('/orders/myorder', [CheckoutController::class, 'myorder'])->name('orders.myorder');


Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.mywishlist');
Route::post('/wishlist/toggle', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::get('/wishlist', [App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.mywishlist');



/*
|--------------------------------------------------------------------------
| 🚪 ĐĂNG XUẤT
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
