<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // Tên bảng của bạn
    protected $table = 'notifications';

    // Các cột được phép gán hàng loạt
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'is_read',
    ];

    /**
     * Lấy user sở hữu thông báo này.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
