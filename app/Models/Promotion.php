<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    /**
     * Các cột được phép gán dữ liệu hàng loạt.
     */
    // app/Models/Promotion.php
protected $fillable = [
    'title',
    'content',
    'discount_percent', // Thêm vào đây
    'start_date',       // Thêm vào đây
    'end_date',         // Thêm vào đây
];

protected $casts = [
    'start_date' => 'datetime', // Ép kiểu để dễ xử lý
    'end_date'   => 'datetime', // Ép kiểu để dễ xử lý
];
}
