<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DienThoai;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query     = $request->input('q');
        $priceMin  = $request->input('price_min');
        $priceMax  = $request->input('price_max');
        $brand     = $request->input('brand'); // ✅ thêm lọc thương hiệu

        // Nếu không nhập gì cả -> quay lại với thông báo
        if (empty($query) && empty($priceMin) && empty($priceMax) && empty($brand)) {
            return redirect()->back()->with('status', 'Vui lòng nhập từ khóa hoặc chọn điều kiện lọc.');
        }

        $products = DienThoai::query();

        // ✅ Tìm theo từ khóa
        if (!empty($query)) {
            $products->where(function ($q) use ($query) {
                $q->where('ten_sp', 'like', "%{$query}%")
                  ->orWhere('mo_ta', 'like', "%{$query}%")
                  ->orWhere('thuong_hieu', 'like', "%{$query}%");
            });
        }

        // ✅ Lọc theo thương hiệu
        if (!empty($brand)) {
            $products->where('thuong_hieu', $brand);
        }

        // ✅ Biểu thức SQL: ép giá từ varchar sang số (loại bỏ ký tự . , ₫ đ và khoảng trắng)
        $priceExpr = "CAST(
            REPLACE(
              REPLACE(
                REPLACE(
                  REPLACE(REPLACE(gia, '.', ''), ',', ''), 'đ', ''), '₫', ''
              ), ' ', ''
            ) AS UNSIGNED
        )";

        // ✅ Lọc theo giá nếu có
        if (!empty($priceMin)) {
            $products->whereRaw("$priceExpr >= ?", [(int)$priceMin]);
        }
        if (!empty($priceMax)) {
            $products->whereRaw("$priceExpr <= ?", [(int)$priceMax]);
        }

        // ✅ Phân trang + giữ tham số query trên URL
        $products = $products->paginate(12)->appends($request->query());

        return view('search_results', compact('products', 'query', 'brand'));
    }
}
