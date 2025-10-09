<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MayTinh extends Model
{
    use HasFactory;

    protected $table = 'may_tinhs'; // Tên bảng trong MySQL

    protected $fillable = [
        'ten_sp',
        'mo_ta',
        'gia',
        'danh_muc_id',
        'so_luong_kho',
        'thuong_hieu',
        'vi_xu_ly',
        'ram',
        'luu_tru',
        'kich_thuoc_man_hinh',
        'mau_sac',
        'dung_luong_pin',
        'duong_dan',
    ];
}
