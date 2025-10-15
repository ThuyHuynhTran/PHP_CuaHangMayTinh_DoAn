<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DienThoai extends Model
{
    use HasFactory;

    // 🔹 Tên bảng trong database
    protected $table = 'dien_thoais';

    // 🔹 Các cột có thể ghi
    protected $fillable = [
        'ten_sp',
        'mo_ta',
        'gia',
        'danh_muc_id',
        'so_luong_kho',
        'thuong_hieu',
        'he_dieu_hanh',
        'man_hinh',
        'camera',
        'bo_nho_ram',
        'bo_nho_trong',
        'pin',
        'mau_sac',
        'duong_dan',
    ];

    /**
     * 🔹 Quan hệ với bảng Review (1 sản phẩm có nhiều đánh giá)
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    /**
     * 🔹 Quan hệ ngược với bảng DanhMuc (mỗi sản phẩm thuộc 1 danh mục)
     */
    public function category()
    {
        return $this->belongsTo(DanhMuc::class, 'danh_muc_id');
    }
    public function brand()
{
    return $this->belongsTo(Brand::class, 'brand_id');
}

}
