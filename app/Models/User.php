<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;
use App\Models\Review;
use App\Models\Message;
// Đã xóa "use App\Http\Controllers\UserMessageController;"

class User extends Authenticatable
{
    // Thêm lại Notifiable để hệ thống thông báo của Laravel hoạt động
    use HasFactory, Notifiable;

    /**
     * Các cột được phép gán dữ liệu hàng loạt.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'gender',
        'birthday',
        'avatar',
        'is_locked',
        'login_attempts',
    ];

    /**
     * Các trường cần được ẩn khi chuyển đổi thành mảng hoặc JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Các thuộc tính cần được ép kiểu.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'date',
        'password' => 'hashed', // Đảm bảo mật khẩu được băm
    ];

    /**
     * Lấy tất cả review của người dùng.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Lấy tất cả tin nhắn của người dùng.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Mối quan hệ này có thể dùng cho một hệ thống thông báo tùy chỉnh khác.
     */
    public function customNotifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    /**
     * Kiểm tra vai trò của người dùng.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Lấy đường dẫn đầy đủ đến ảnh đại diện.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }

        // Trả về ảnh mặc định nếu chưa có avatar
        return asset('images/default-avatar.png');
    }
}