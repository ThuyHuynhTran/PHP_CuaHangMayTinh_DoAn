<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DienThoai extends Model
{
    use HasFactory;

    // 🔹 Tên bảng trong database
    protected $table = 'dien_thoais';
public $timestamps = false;
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
        'brand_id',
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

    /**
     * 🔹 Quan hệ ngược với bảng Brand (mỗi sản phẩm thuộc 1 thương hiệu)
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * ✅ Accessor: Tự động hiển thị giá đúng đơn vị VNĐ
     * Nếu giá nhỏ hơn 1.000.000 => hiểu là đơn vị “triệu” => nhân 1.000.000
     */
 public function getGiaHienThiAttribute()
{
    // Bỏ dấu phẩy nếu có và ép về float
    $gia = (float) str_replace(',', '', $this->gia);

    // Nếu giá nhỏ hơn 10 triệu (có thể đang lưu đơn vị "triệu") thì nhân thêm 1 triệu
    return $gia < 10000000 ? $gia * 1000000 : $gia;
}

public function getGiaFormattedAttribute()
{
    return number_format($this->gia_hien_thi, 0, ',', '.') . '₫';
}


}
