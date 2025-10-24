<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMessageController extends Controller
{
    /**
     * Hiển thị danh sách các cuộc hội thoại của người dùng.
     */
    public function index()
    {
        $messages = Auth::user()->messages()->latest('updated_at')->paginate(10);
        return view('user.messages.index', compact('messages'));
    }

    /**
     * Hiển thị chi tiết một cuộc hội thoại và đánh dấu là đã đọc.
     */
    public function show(Message $message)
    {
        // Chính sách bảo mật: Chỉ chủ nhân của tin nhắn mới được xem
        if (Auth::id() !== $message->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Đánh dấu cuộc hội thoại là đã đọc
        if (!$message->is_read_by_user) {
            $message->update(['is_read_by_user' => true]);
        }

        return view('user.messages.show', compact('message'));
    }

    /**
     * Lưu phản hồi từ người dùng.
     */
    public function reply(Request $request, Message $message)
    {
        // Chính sách bảo mật
        if (Auth::id() !== $message->user_id) {
            abort(403);
        }

        $request->validate(['user_reply' => 'required|string|max:2000']);

        $conversation = $message->conversation ?? [];
        $conversation[] = [
            'sender' => 'user',
            'content' => $request->user_reply,
            'timestamp' => now()->toDateTimeString(),
        ];

        $message->update([
            'conversation' => $conversation,
            'status' => 'chua_doc', // Cập nhật lại trạng thái để admin biết có tin nhắn mới
            'is_read_by_user' => true // Người dùng vừa gửi tin nhắn, nên đánh dấu là đã đọc
        ]);

        return redirect()->back()->with('success', 'Đã gửi trả lời của bạn!');
    }
}

