<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification; // Đảm bảo đã import Model Notification

class NotificationController extends Controller
{
    /**
     * Hiển thị danh sách thông báo của người dùng.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem thông báo.');
        }

        // Tải trước cả hai mối quan hệ: 'promotion' và 'messageRel'
        $notifications = Auth::user()->notifications()
                                      ->with(['promotion', 'messageRel'])
                                      ->latest()
                                      ->paginate(15);

        // Đánh dấu tất cả các thông báo chưa đọc là đã đọc
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        return view('notifications.index', compact('notifications'));
    }
}

