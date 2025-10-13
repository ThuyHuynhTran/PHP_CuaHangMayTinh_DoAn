<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = ['user_id', 'product_id', 'quantity'];

    // Liên kết với người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Liên kết với sản phẩm (DienThoai)
    public function product()
    {
        return $this->belongsTo(DienThoai::class, 'product_id', 'id');
    }
}
