<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DienThoai;

class WishlistController extends Controller
{
    /**
     * ✅ Toggle thêm / xóa sản phẩm khỏi danh sách yêu thích (AJAX)
     */
    public function toggle(Request $request)
    {
        $productId = $request->input('product_id');
        $wishlist = session('wishlist', []);

        // Nếu sản phẩm đã có trong danh sách -> xóa
        if (in_array($productId, $wishlist)) {
            $wishlist = array_values(array_diff($wishlist, [$productId]));
            session(['wishlist' => $wishlist]);
            return response()->json([
                'status' => 'removed',
                'message' => 'Đã xóa khỏi danh sách yêu thích 💔'
            ]);
        } 
        // Nếu chưa có -> thêm vào
        else {
            $wishlist[] = $productId;
            session(['wishlist' => $wishlist]);
            return response()->json([
                'status' => 'added',
                'message' => 'Đã thêm vào danh sách yêu thích ❤️'
            ]);
        }
    }

    /**
     * ✅ Trang hiển thị danh sách yêu thích
     */
    public function index()
    {
        $wishlist = session('wishlist', []);

        // Nếu trống thì không truy vấn database
        $products = count($wishlist) > 0
            ? DienThoai::whereIn('id', $wishlist)->get()
            : collect([]);

        return view('wishlist.mywishlist', compact('products'));
    }
}
