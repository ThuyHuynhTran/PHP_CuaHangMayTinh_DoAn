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
    ProfileController,
    PromotionController,
    ReviewController,
    FaqController,
    BrandController,
    CategoryController,
    SearchController,
    WishlistController,
    ChatController,
    UserMessageController
};
use App\Http\Controllers\Auth\{
    ForgotPasswordController,
    ResetPasswordController
};
use App\Http\Controllers\Admin\{
    ProductAdminController,
    CategoryAdminController,
    PromotionAdminController,
    ReviewAdminController,
    MessageAdminController,
    OrderAdminController,
    StatisticsController,
    CustomerAdminController
};
use App\Models\DanhMuc;

/*
|--------------------------------------------------------------------------|
| ⚙️ QUẢN LÝ KHÁCH HÀNG (Không dùng middleware is_admin)                  |
|--------------------------------------------------------------------------|
*/
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/customers', [CustomerAdminController::class, 'index'])->name('admin.customers.index');
    Route::get('/customers/{id}', [CustomerAdminController::class, 'show'])->name('admin.customers.show');
    Route::put('/customers/{id}/toggle-lock', [CustomerAdminController::class, 'toggleLock'])->name('admin.customers.toggleLock');
});

/*
|--------------------------------------------------------------------------|
| 🌐 CÁC ROUTE CÔNG KHAI & API                                           |
|--------------------------------------------------------------------------|
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/san-pham/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/brands', [BrandController::class, 'index']);
Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

// 🔔 Đăng ký nhận khuyến mãi
Route::post('/promotion/subscribe', [PromotionController::class, 'subscribe'])->name('promotion.subscribe');

// 💬 Chat public
Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

// 📦 API hỗ trợ frontend
Route::get('/api/categories', fn() =>
    response()->json([
        'success' => true,
        'categories' => DanhMuc::select('id', 'ten_danh_muc')->get()
    ])
);
Route::get('/api/brands', [BrandController::class, 'getBrandsApi'])->name('api.brands');
Route::get('/api/notifications', [PromotionController::class, 'getNotifications'])->name('api.notifications');

/*
|--------------------------------------------------------------------------|
| 🔐 XÁC THỰC & QUÊN MẬT KHẨU                                             |
|--------------------------------------------------------------------------|
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

/*
|--------------------------------------------------------------------------|
| 👤 NGƯỜI DÙNG ĐÃ ĐĂNG NHẬP                                            |
|--------------------------------------------------------------------------|
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 👤 Hồ sơ & địa chỉ
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/address/manage', [AddressController::class, 'manage'])->name('address.manage');
    Route::post('/address/store', [AddressController::class, 'store'])->name('address.store');
    Route::post('/address/set-default', [AddressController::class, 'setDefault'])->name('address.setDefault');
    Route::delete('/address/delete/{id}', [AddressController::class, 'destroy'])->name('address.delete');
    Route::get('/addresses/json', [AddressController::class, 'getAddressesJson'])->name('addresses.json');

    // ⭐ Đánh giá
    Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('review.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('review.update');
    Route::post('/reviews/{review}/like', [ReviewController::class, 'toggleLike'])->name('review.like');

    // ❤️ Yêu thích
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.mywishlist');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // 🔔 Thông báo khuyến mãi
    Route::get('/notifications', [PromotionController::class, 'showNotifications'])->name('notifications');

    // 💬 Tin nhắn người dùng
    Route::prefix('my-account')->name('user.')->group(function () {
        Route::get('/messages', [UserMessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{message}', [UserMessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{message}/reply', [UserMessageController::class, 'reply'])->name('messages.reply');
    });
    
    // Đường dẫn cho admin trả lời tin nhắn của người dùng
    Route::post('/chat/reply/{id}', [ChatController::class, 'reply'])->name('chat.reply');
});

/*
|--------------------------------------------------------------------------|
| 🛒 GIỎ HÀNG & THANH TOÁN                                               |
|--------------------------------------------------------------------------|
*/
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart');
    Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/apply-promotion', [CartController::class, 'applyPromotion'])->name('cart.applyPromotion');

    Route::post('/checkout/selected', [CartController::class, 'checkoutSelected'])->name('checkout.selected');
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/checkout', [CheckoutController::class, 'checkoutNow'])->name('checkout');
    Route::get('/checkout/{id}', [CheckoutController::class, 'checkoutNow'])->name('checkout.now');
    Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders', [CheckoutController::class, 'myorder'])->name('orders.myorder');
    Route::get('/orders/history', [CheckoutController::class, 'orderHistory'])->name('orders.history');
    Route::get('/orders/success/{orderId}', [CheckoutController::class, 'success'])->name('orders.success');
    Route::get('/orders/{id}', [CheckoutController::class, 'show'])->name('orders.show');
    Route::post('/orders/cancel', [CheckoutController::class, 'cancel'])->name('orders.cancel');
});

/*
|--------------------------------------------------------------------------|
| 🧾 QUẢN TRỊ ADMIN                                                      |
|--------------------------------------------------------------------------|
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductAdminController::class);
    Route::resource('categories', CategoryAdminController::class);
    Route::resource('promotions', PromotionAdminController::class);
    Route::delete('promotions/bulk-delete', [PromotionAdminController::class, 'bulkDelete'])->name('promotions.bulkDelete');
    Route::get('reviews', [ReviewAdminController::class, 'index'])->name('reviews.index');
    Route::get('reviews/{review}/reply', [ReviewAdminController::class, 'reply'])->name('reviews.reply');
    Route::post('reviews/{review}/reply', [ReviewAdminController::class, 'storeReply'])->name('reviews.storeReply');
    Route::delete('reviews/{review}', [ReviewAdminController::class, 'destroy'])->name('reviews.destroy');
    Route::resource('messages', MessageAdminController::class);
    Route::get('orders', [OrderAdminController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderAdminController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}/status', [OrderAdminController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('statistics/{reportType}', [StatisticsController::class, 'showReport'])->name('statistics.show');
    Route::get('faqs', [FaqController::class, 'adminIndex'])->name('faq.index');
    Route::post('faqs', [FaqController::class, 'store'])->name('faq.store');
    Route::patch('faqs/{faq}', [FaqController::class, 'update'])->name('faq.update');
    Route::delete('faqs/{faq}', [FaqController::class, 'destroy'])->name('faq.delete');
    Route::get('chats', [ChatController::class, 'index'])->name('chats');
});
