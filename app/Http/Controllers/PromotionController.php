<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PromotionSubscriber;
use App\Models\Promotion;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class PromotionController extends Controller
{
    /**
     * 🔔 Đăng ký người dùng nhận thông báo khuyến mãi
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:promotion_subscribers,email',
            'phone' => 'nullable|string|max:20',
        ]);

        $subscriber = PromotionSubscriber::create([
            'email' => $request->email,
            'phone' => $request->phone,
            'is_verified' => 1,   // ✅ Cho phép nhận thông báo
            'is_notified' => 0,   // ✅ Chưa nhận khuyến mãi nào
        ]);

        Log::info('✅ New subscriber added: ' . $subscriber->email);

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký nhận thông báo khuyến mãi thành công!',
            'data' => $subscriber,
        ]);
    }

    /**
     * 📬 Lấy danh sách thông báo của người dùng hiện tại (API)
     */
    public function getNotifications()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Chưa đăng nhập'], 401);
        }

        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }

    /**
     * 🕓 Đánh dấu thông báo là đã đọc
     */
    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['error' => 'Thông báo không tồn tại'], 404);
        }

        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Bạn không có quyền thao tác'], 403);
        }

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã đánh dấu thông báo là đã đọc!',
        ]);
    }

    /**
     * 🎉 Admin thêm khuyến mãi mới (Observer sẽ tự gửi thông báo)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $promotion = Promotion::create($validated);

        Log::info('✅ New promotion created: ' . $promotion->title);

        return response()->json([
            'success' => true,
            'message' => 'Khuyến mãi đã được tạo thành công! Thông báo sẽ tự động gửi đến subscribers.',
            'data' => $promotion,
        ]);
    }
/**
 * 🖥 Hiển thị giao diện danh sách thông báo của user
 */
public function showNotifications()
{
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem thông báo.');
    }

    $notifications = Notification::with('promotion')
        ->where('user_id', auth()->id())
        ->orderByDesc('created_at')
        ->paginate(10);

    return view('notifications.index', compact('notifications'));
}

    /**
     * 🧪 API test nhanh cho Observer (dùng khi debug)
     * POST /api/test-promotion
     */
    public function testPromotion()
    {
        $promotion = Promotion::create([
            'title' => 'Test Promo ' . now()->format('H:i:s'),
            'description' => 'Khuyến mãi thử nghiệm - kiểm tra observer.',
            'discount_percent' => rand(5, 50),
            'start_date' => now(),
            'end_date' => now()->addDays(5),
        ]);

        Log::info('🧪 Test promotion created manually.');

        return response()->json([
            'success' => true,
            'message' => 'Đã tạo khuyến mãi test — kiểm tra log hoặc bảng notifications.',
            'data' => $promotion,
        ]);
    }
}
