<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'is_read',
    ];

    // Quan hệ với model PromotionSubscriber (người nhận thông báo)
    public function user()
    {
        return $this->belongsTo(PromotionSubscriber::class);
    }
}
