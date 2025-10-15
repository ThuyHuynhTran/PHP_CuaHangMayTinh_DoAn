<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DanhMuc;
use App\Models\DienThoai;

class CategoryController extends Controller
{
    // Hiển thị danh sách sản phẩm theo danh mục
    // Hiển thị sản phẩm theo danh mục
public function show($id)
{
    $category = DanhMuc::findOrFail($id);

    // Lấy sản phẩm và phân trang 8 sản phẩm mỗi trang
    $products = DienThoai::where('danh_muc_id', $id)->paginate(8);

    return view('category', compact('category', 'products'));
}


}
