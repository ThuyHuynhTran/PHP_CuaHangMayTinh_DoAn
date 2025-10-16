<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Lấy thông tin của người dùng đang đăng nhập
        $user = Auth::user();

        // 2. Lấy tất cả thông báo thuộc về người dùng đó
        //    (thông qua mối quan hệ 'notifications' bạn đã định nghĩa trong model User)
        $notifications = $user->notifications;

        // 3. Trả về view 'notifications.index' và truyền biến $notifications sang
        return view('notifications.index', ['notifications' => $notifications]);
    }
}
