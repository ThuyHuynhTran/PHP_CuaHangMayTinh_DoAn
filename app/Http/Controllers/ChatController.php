<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    /**
     * Gửi tin nhắn từ người dùng.
     */
    public function send(Request $request)
    {
        try {
            // 🧩 Đảm bảo JSON body được decode đúng
            $data = $request->isJson()
                ? $request->json()->all()
                : $request->all();

            // ✅ Validate dữ liệu
            $validator = Validator::make($data, [
                'name'    => 'required|string|max:255',
                'email'   => 'required|email',
                'phone'   => 'nullable|string|max:20',
                'message' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                Log::error('❌ Lỗi validate dữ liệu chat: ' . json_encode($validator->errors()));
                return response()->json([
                    'success' => false,
                    'error'   => 'Dữ liệu không hợp lệ',
                    'details' => $validator->errors(),
                ], 422);
            }

            // ✅ Lưu tin nhắn của người dùng
            $msg = Message::create([
                'user_id' => Auth::id(),
                'name'    => $data['name'],
                'email'   => $data['email'],
                'phone'   => $data['phone'] ?? null,
                'message' => $data['message'],
                'status'  => 'chua_doc', // Trạng thái mặc định
                'sender'  => 'user',     // Tin nhắn từ người dùng
                'conversation' => [],    // Khởi tạo cuộc trò chuyện mới
            ]);

            Log::info("💬 Tin nhắn mới được lưu: {$msg->email} - {$msg->message}");

            return response()->json([
                'success' => true,
                'message' => 'Tin nhắn đã được lưu thành công!',
                'data' => $msg,
            ]);

        } catch (\Exception $e) {
            Log::error('🔥 Lỗi khi lưu tin nhắn: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error'   => 'Không thể gửi tin nhắn. Vui lòng thử lại sau.',
            ], 500);
        }
    }

    /**
     * Trả lời tin nhắn từ admin.
     */
    public function reply(Request $request, $id)
    {
        try {
            $message = Message::findOrFail($id);

            // 🧩 Validate tin nhắn admin
            $validator = Validator::make($request->all(), [
                'message' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Dữ liệu không hợp lệ',
                    'details' => $validator->errors(),
                ], 422);
            }

            // Lấy cuộc trò chuyện cũ và thêm tin nhắn của admin vào
            $conversation = $message->conversation ?? [];
            $conversation[] = [
                'sender' => 'admin',
                'content' => $request->message,
                'timestamp' => now(),
            ];

            // Cập nhật tin nhắn với cuộc trò chuyện mới
            $message->conversation = $conversation;
            $message->status = 'da_tra_loi'; // Đánh dấu tin nhắn là đã trả lời
            $message->save();

            // Trả về dữ liệu cập nhật cuộc trò chuyện, bao gồm cả tin nhắn mới của admin
            return response()->json([
                'success' => true,
                'message' => 'Trả lời tin nhắn thành công',
                'data' => $message, // Trả lại dữ liệu tin nhắn đã được cập nhật
                'conversation_html' => view('admin.messages.conversation', ['message' => $message])->render(), // Trả về HTML của cuộc trò chuyện mới
            ]);
        } catch (\Exception $e) {
            Log::error('🔥 Lỗi khi trả lời tin nhắn: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error'   => 'Không thể trả lời tin nhắn. Vui lòng thử lại sau.',
            ], 500);
        }
    }
}
