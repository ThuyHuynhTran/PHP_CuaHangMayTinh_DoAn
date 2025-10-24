<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', 
        'product_id', 
        'rating', 
        'comment',
        'admin_reply' // Đã thêm để cho phép admin phản hồi
    ];

    /**
     * Lấy người dùng đã viết đánh giá.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Lấy sản phẩm được đánh giá.
     * (Giữ nguyên quan hệ tới model DienThoai theo code của bạn)
     */
    public function product() {
        return $this->belongsTo(DienThoai::class, 'product_id');
    }

    /**
     * Lấy các hình ảnh của đánh giá.
     */
    public function images()
    {
        return $this->hasMany(\App\Models\ReviewImage::class);
    }
}

