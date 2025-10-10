<?php

namespace App\Http\Controllers;

use App\Models\DienThoai;
use App\Models\MayTinh;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function show($id)
    {
        // Tìm sản phẩm theo ID
        $product = DienThoai::findOrFail($id);

        // Gửi sang view chi tiết
        return view('product_detail', compact('product'));
    }
}
