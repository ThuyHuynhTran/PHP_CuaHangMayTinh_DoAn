<?php

namespace App\Http\Controllers;

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
        $product = MayTinh::findOrFail($id);

        // Gửi sang view chi tiết
        return view('product_detail', compact('product'));
    }
}
