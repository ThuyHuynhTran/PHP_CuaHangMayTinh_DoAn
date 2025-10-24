<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * Các cột được phép gán dữ liệu hàng loạt.
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'message',
        'conversation', // Cần thiết để lưu cuộc hội thoại
        'status',       // Cần thiết để cập nhật trạng thái
        'sender',       // Cần thiết để xác định người gửi (user/admin)
    ];

    /**
     * Các thuộc tính cần được ép kiểu.
     * Giúp Laravel tự động chuyển đổi cột 'conversation' từ JSON sang mảng và ngược lại.
     */
    protected $casts = [
        'conversation' => 'array',
        // 'status' => 'string', // Nếu cần thiết, nhưng không bắt buộc vì 'status' là một chuỗi đơn giản.
    ];

    /**
     * Lấy thông tin người dùng đã gửi (nếu có).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
