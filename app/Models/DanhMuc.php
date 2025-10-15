<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhMuc extends Model
{
    use HasFactory;

    protected $table = 'danh_mucs'; // Tên bảng trong DB
    protected $fillable = ['ten_danh_muc'];
    public $timestamps = false; // nếu bảng không có cột created_at / updated_at

    /**
     * 🔹 Mỗi danh mục có nhiều sản phẩm (điện thoại)
     */
    public function products()
    {
        // Giả sử bảng 'dien_thoais' có cột khóa ngoại 'danh_muc_id'
        return $this->hasMany(DienThoai::class, 'danh_muc_id');
    }
}
