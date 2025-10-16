<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
// Import Model Notification mới của bạn
use App\Models\Notification;

class User extends Authenticatable
{
    // Đã xóa Notifiable khỏi đây
    use HasFactory;

    /**
     * Các cột được phép gán dữ liệu hàng loạt
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
    ];

    /**
     * Ẩn những trường không cần hiển thị
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Các kiểu dữ liệu cần ép kiểu
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'date',
    ];

    /**
     * Lấy tất cả review của người dùng.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * (THÊM MỚI) Lấy tất cả thông báo của người dùng.
     * Mối quan hệ này sẽ kết nối đến bảng notifications tùy chỉnh của bạn.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest(); // Dùng latest() để thông báo mới nhất lên đầu
    }

    /**
     * Kiểm tra vai trò của user
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
     * Lấy đường dẫn đầy đủ đến ảnh đại diện
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
