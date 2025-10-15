<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewImage extends Model
{
    use HasFactory;

    protected $table = 'review_images';

    // ✅ Cho phép mass assignment 2 cột này
    protected $fillable = [
        'review_id',
        'path',
    ];

    // ✅ Liên kết ngược về Review
    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
