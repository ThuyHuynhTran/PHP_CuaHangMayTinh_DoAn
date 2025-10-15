<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    /**
     * 📦 Lấy danh sách thương hiệu từ bảng `brands`
     * Trả về JSON để dùng cho dropdown trong header
     */
    public function index()
    {
        try {
            $brands = Brand::select('id', 'ten_thuong_hieu')
                ->orderBy('ten_thuong_hieu', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'brands' => $brands
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
