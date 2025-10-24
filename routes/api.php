<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BrandController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route mặc định cho user đã xác thực
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ✅ API trả danh sách thương hiệu
Route::get('/brands', [BrandController::class, 'index']);

// ✅ API đếm số tin nhắn chưa đọc
Route::middleware('auth:sanctum')->get('/unread-messages-count', function () {
    if (!auth()->check()) {
        return response()->json(['count' => 0]);
    }
    // Đếm số tin nhắn của người dùng đang đăng nhập mà có trạng thái is_read_by_user = false
    $count = \App\Models\Message::where('user_id', auth()->id())
                                ->where('is_read_by_user', false)
                                ->count();
    return response()->json(['count' => $count]);
})->name('api.unread_messages.count');

