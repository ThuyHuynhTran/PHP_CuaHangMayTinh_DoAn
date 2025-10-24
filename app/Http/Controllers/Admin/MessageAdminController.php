<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageAdminController extends Controller
{
    /**
     * Hiển thị danh sách tất cả tin nhắn.
     */
    public function index()
    {
        // Lấy tất cả tin nhắn đã gửi và sắp xếp theo thời gian.
        $messages = Message::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Hiển thị chi tiết một tin nhắn và form để phản hồi.
     */
public function show(Message $message)
{
    // Cập nhật trạng thái thành "đã đọc" khi admin xem tin nhắn
    if ($message->status === 'chua_doc') {
        $message->update(['status' => 'da_doc']);
    }

    // Truyền dữ liệu conversation vào view
    return view('admin.messages.show', compact('message'));
}

    /**
     * Lưu phản hồi của admin và đánh dấu là "chưa đọc" cho người dùng.
     */
  public function update(Request $request, Message $message)
{
    // Kiểm tra tính hợp lệ của phản hồi từ admin
    $request->validate([
        'admin_reply' => 'required|string|max:2000',
    ]);

    // Lấy mảng hội thoại hiện tại hoặc khởi tạo mảng mới
    $conversation = $message->conversation ?? [];

    // Thêm tin nhắn mới của admin vào cuộc hội thoại
    $conversation[] = [
        'sender' => 'admin',
        'content' => $request->admin_reply,
        'timestamp' => now()->toDateTimeString(),
    ];

    // Cập nhật lại tin nhắn và trạng thái
    $message->update([
        'conversation'    => $conversation,   // Cập nhật cuộc trò chuyện
        'status'          => 'da_tra_loi',    // Đánh dấu là đã trả lời
        'is_read_by_user' => false,           // Đánh dấu là chưa đọc bởi người dùng
    ]);

    // Trả về thông báo thành công
    return redirect()->route('admin.messages.index')->with('success', 'Đã gửi phản hồi thành công!');
}


    /**
     * Xóa một tin nhắn khỏi database.
     */
    public function destroy(Message $message)
    {
        // Xóa tin nhắn
        $message->delete();

        // Trả về thông báo thành công
        return redirect()->route('admin.messages.index')->with('success', 'Đã xóa tin nhắn thành công.');
    }
}
