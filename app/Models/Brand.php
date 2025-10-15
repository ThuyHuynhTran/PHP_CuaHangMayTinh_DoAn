<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';
    protected $fillable = ['ten_thuong_hieu'];

    // 1 thương hiệu có nhiều điện thoại
    public function dienThoais()
    {
        return $this->hasMany(DienThoai::class, 'brand_id');
    }
}
