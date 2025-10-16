<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PromotionSubscriber;
use App\Models\Promotion;
use App\Models\Notification;
use App\Events\PromotionAdded; // Sử dụng Event để gửi thông báo

class PromotionController extends Controller
{
    // Đăng ký người dùng nhận thông báo khuyến mãi
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:promotions_subscribers,email',
            'phone' => 'nullable|string|max:20',
        ]);

        // Lưu thông tin người dùng vào bảng promotions_subscribers
        PromotionSubscriber::create([
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return response()->json(['success' => true, 'message' => 'Đăng ký thành công!']);
    }

    // Lấy danh sách thông báo của người dùng đã đăng nhập
    public function notifications()
    {
        // Kiểm tra người dùng đã đăng nhập chưa
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Lấy thông báo của người dùng hiện tại
        $notifications = Notification::where('user_id', auth()->id())
                                     ->orderBy('created_at', 'desc')
                                     ->take(10)
                                     ->get();
        
        return view('notifications.index', compact('notifications'));
    }

    // Lưu thông báo và gửi cho tất cả người dùng đăng ký
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Tạo mới promotion
        $promotion = Promotion::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        // Gửi thông báo cho tất cả người dùng đã đăng ký qua sự kiện
        $subscribers = PromotionSubscriber::all();

        foreach ($subscribers as $subscriber) {
            // Tạo thông báo cho người dùng
            Notification::create([
                'user_id' => $subscriber->id,
                'title' => $promotion->title,
                'content' => $promotion->content,
            ]);
        }

        // Gửi thông báo qua sự kiện (Nếu bạn sử dụng WebSockets hoặc Push Notifications)
        event(new PromotionAdded($promotion));

        return response()->json(['success' => true, 'message' => 'Khuyến mãi đã được tạo và thông báo đã được gửi!']);
    }

    // Lấy danh sách thông báo của người dùng hiện tại (API)
    public function getNotifications()
    {
        // Kiểm tra người dùng đã đăng nhập chưa
        if (!auth()->check()) {
            return response()->json(['error' => 'Chưa đăng nhập'], 401);
        }

        // Lấy thông báo của người dùng hiện tại
        $notifications = Notification::where('user_id', auth()->id())
                                     ->latest()
                                     ->take(5)
                                     ->get();
        
        return response()->json(['notifications' => $notifications]);
    }

    // Đánh dấu thông báo là đã đọc
    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        // Kiểm tra quyền truy cập của người dùng
        if ($notification && $notification->user_id == auth()->id()) {
            $notification->update(['is_read' => true]);

            return redirect()->back()->with('success', 'Đã đánh dấu thông báo là đã đọc!');
        }

        return redirect()->back()->with('error', 'Thông báo không hợp lệ hoặc bạn không có quyền');
    }
}
