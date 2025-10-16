<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionSubscriber extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'phone', 'is_verified'];

    // nếu Laravel không tự nhận đúng tên bảng:
    protected $table = 'promotion_subscribers';
}
