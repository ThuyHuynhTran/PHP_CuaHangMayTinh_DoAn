<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DienThoai extends Model
{
    use HasFactory;

    // Tên bảng trong database
    protected $table = 'dien_thoais';

    // Các cột có thể ghi
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

    
}
