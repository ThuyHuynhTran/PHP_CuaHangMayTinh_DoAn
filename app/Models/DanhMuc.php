<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhMuc extends Model
{
    use HasFactory;

    /**
     * 🗂️ Tên bảng trong cơ sở dữ liệu mà model này quản lý.
     */
    protected $table = 'danh_mucs';

    /**
     * ⚙️ Các cột được phép gán dữ liệu hàng loạt (Mass Assignment).
     */
    protected $fillable = ['ten_danh_muc'];

    /**
     * ⏱️ Tắt tính năng tự động quản lý timestamps (created_at, updated_at).
     * Sử dụng nếu bảng của bạn không có 2 cột này.
     */
    public $timestamps = false;

    /**
     * 🔹 Quan hệ 1-nhiều: Một danh mục có thể có nhiều sản phẩm.
     */
    public function products()
    {
        // Giả sử model sản phẩm của bạn là 'DienThoai' và có khóa ngoại 'danh_muc_id'
        return $this->hasMany(DienThoai::class, 'danh_muc_id');
    }
}

