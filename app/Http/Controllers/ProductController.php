<?php

namespace App\Http\Controllers;

use App\Models\DienThoai;
use App\Models\MayTinh;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * 🧾 Hiển thị chi tiết sản phẩm (Điện thoại hoặc Máy tính)
     */
    public function show($id)
    {
        // 🔹 Tìm trong bảng điện thoại trước
        $product = DienThoai::find($id);

        // 🔹 Nếu không có trong điện thoại, tìm trong máy tính
        if (!$product) {
            $product = MayTinh::findOrFail($id);
        }

        // 🔹 Trả về view chi tiết sản phẩm
        return view('product_detail', compact('product'));
    }

    /**
     * 📦 Hiển thị danh sách tất cả sản phẩm (nếu muốn có trang tổng hợp)
     */
    public function index()
    {
        // Lấy tất cả sản phẩm từ cả hai bảng (nếu có)
        $phones = DienThoai::select('id', 'ten_sp', 'gia', 'duong_dan')
            ->get()
            ->map(function ($item) {
                $item->type = 'dien_thoai';
                return $item;
            });

        $computers = MayTinh::select('id', 'ten_sp', 'gia', 'duong_dan')
            ->get()
            ->map(function ($item) {
                $item->type = 'may_tinh';
                return $item;
            });

        // Gộp 2 loại sản phẩm
        $products = $phones->merge($computers);

        return view('products.index', compact('products'));
    }

    /**
     * 🔍 Tìm kiếm sản phẩm theo tên
     */
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $phones = DienThoai::where('ten_sp', 'LIKE', "%{$keyword}%")->get();
        $computers = MayTinh::where('ten_sp', 'LIKE', "%{$keyword}%")->get();

        $results = $phones->merge($computers);

        return view('products.search', [
            'keyword' => $keyword,
            'results' => $results
        ]);
    }
}
