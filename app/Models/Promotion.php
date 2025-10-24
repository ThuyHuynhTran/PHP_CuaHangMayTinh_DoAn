<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    /**
     * 🧩 Tên bảng tương ứng trong cơ sở dữ liệu.
     * (Không bắt buộc nếu Laravel tự nhận đúng tên số nhiều)
     */
    protected $table = 'promotions';

    /**
     * 🛠️ Các cột được phép gán dữ liệu hàng loạt.
     */
    protected $fillable = [
        'title',
        'description',
        'discount_percent',
        'start_date',
        'end_date',
    ];

    /**
     * 📅 Ép kiểu cho các cột thời gian để dễ xử lý bằng Carbon.
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];
}
