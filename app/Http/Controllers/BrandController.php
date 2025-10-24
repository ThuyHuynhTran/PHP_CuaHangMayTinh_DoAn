<?php

namespace App\Http\Controllers;

use App\Models\DienThoai; // 🔹 Import model DienThoai
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * (Hàm cũ của bạn, có thể dùng để hiển thị một trang riêng cho thương hiệu)
     */
    public function index()
    {
        // Giữ nguyên logic cũ của bạn ở đây, ví dụ:
        // return view('brands.index');
    }

    /**
     * 🔹 HÀM MỚI: Cung cấp danh sách thương hiệu cho API
     * Hàm này sẽ được gọi bởi JavaScript ở header.
     */
    public function getBrandsApi()
    {
        try {
            // Lấy danh sách các thương hiệu duy nhất, không rỗng và sắp xếp theo alphabet
            $brands = DienThoai::select('thuong_hieu')
                               ->whereNotNull('thuong_hieu')
                               ->where('thuong_hieu', '!=', '')
                               ->distinct()
                               ->orderBy('thuong_hieu', 'asc')
                               ->get();

            return response()->json([
                'success' => true,
                'brands'  => $brands
            ]);
        } catch (\Exception $e) {
            // Trả về lỗi nếu có sự cố
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải danh sách thương hiệu.'
            ], 500);
        }
    }
}
