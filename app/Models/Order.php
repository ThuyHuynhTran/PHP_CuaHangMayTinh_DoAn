<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính được phép gán dữ liệu hàng loạt.
     * Đã được cập nhật để khớp với cấu trúc database của bạn.
     */
    protected $fillable = [
        'user_id',
        'fullname',
        'phone',
        'address',
        'payment_method',
        'status',
        'cancel_reason',
        'total',
        'promotion_id',
        'discount_amount',
    ];

    /**
     * Mối quan hệ: Một đơn hàng thuộc về một người dùng.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mối quan hệ: Một đơn hàng có nhiều sản phẩm (order items).
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
     public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id', 'id');
    }
}

