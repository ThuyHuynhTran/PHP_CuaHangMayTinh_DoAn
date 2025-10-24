<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * Các cột được phép gán dữ liệu hàng loạt.
     * Cần bao gồm tất cả các cột mà bạn đang cố gắng chèn.
     */
    protected $fillable = [
        'user_id',
        'promotion_id',
        'message_id',
        'message',
        'is_read',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
    ];

    /**
     * Lấy user sở hữu thông báo này.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lấy thông tin khuyến mãi mà thông báo này thuộc về (nếu có).
     */
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    /**
     * Lấy thông tin cuộc hội thoại mà thông báo này thuộc về (nếu có).
     */
    public function messageRel()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }
}

