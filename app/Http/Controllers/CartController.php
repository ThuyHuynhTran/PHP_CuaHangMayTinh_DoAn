<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DienThoai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // 🛒 Thêm sản phẩm vào giỏ
    public function addToCart(Request $request, $productId)
    {
        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thêm vào giỏ hàng.');
        }

        $user = Auth::user();
        $product = DienThoai::findOrFail($productId);

        // Kiểm tra sản phẩm đã có trong giỏ chưa
        $cartItem = Cart::where('user_id', $user->id)
                        ->where('product_id', $product->id)
                        ->first();

        if ($cartItem) {
            // Nếu đã có → tăng số lượng và cập nhật thời gian
            $cartItem->quantity++;
            $cartItem->updated_at = now();
            $cartItem->save();
        } else {
            // Nếu chưa có → thêm mới (và sẽ hiện lên đầu vì created_at mới nhất)
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        return redirect()->back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
    }

    // 🧺 Hiển thị giỏ hàng
    public function viewCart()
    {
        // Lấy sản phẩm của user hiện tại, sắp xếp theo mới nhất
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc') // Mới cập nhật hiện lên đầu
            ->orderBy('created_at', 'desc') // Nếu cùng thời gian, lấy theo tạo mới nhất
            ->get();

        // Tính tổng tiền
        $total = $cartItems->sum(function ($item) {
            $gia = (float) preg_replace('/[^\d.]/', '', $item->product->gia);
            return $gia * $item->quantity;
        });

        return view('cart', compact('cartItems', 'total'));
    }
// Trong file: App/Http/Controllers/CartController.php

public function checkoutSelected(Request $request)
{
    $selectedProductIds = $request->input('selected_products', []);

    if (empty($selectedProductIds)) {
        return redirect()->route('cart.view')->with('error', 'Vui lòng chọn sản phẩm.');
    }

    $selectedItems = Cart::with('product')
                        ->whereIn('id', $selectedProductIds)
                        ->where('user_id', Auth::id())
                        ->get();
    
    $total = $selectedItems->sum(function($item) {
        $price = (float) preg_replace('/[^\d.]/', '', $item->product->gia);
        return $price * $item->quantity;
    });

    // ✅ BƯỚC 1: LẤY DANH SÁCH ĐỊA CHỈ CỦA USER TỪ DATABASE
    // Thay 'App\Models\Address' bằng đường dẫn đúng đến model Address của bạn nếu cần
    $addresses = \App\Models\Address::where('user_id', Auth::id())->get();

    // ✅ BƯỚC 2: TRUYỀN BIẾN $addresses SANG VIEW
    return view('checkout', [
        'cartItems' => $selectedItems, // Đổi 'selectedItems' thành 'cartItems' cho khớp với view của bạn
        'total' => $total,
        'addresses' => $addresses,      // Dòng quan trọng để sửa lỗi
    ]);
}
public function checkoutNow($id)
{
    // Lấy thông tin sản phẩm
    $product = DienThoai::findOrFail($id);

    // Tạo "đơn hàng tạm" chỉ chứa 1 sản phẩm
    $cartItems = collect([
        (object)[
            'product' => $product,
            'quantity' => 1
        ]
    ]);

    // Tính tổng tiền
    $total = (float) preg_replace('/[^\d.]/', '', $product->gia);

    // Gọi view checkout.blade.php
    return view('checkout', compact('cartItems', 'total'));
}

public function removeFromCart($id)
{
    $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->first();

    if (!$cartItem) {
        return redirect()->back()->with('error', 'Không tìm thấy sản phẩm trong giỏ hàng.');
    }

    $cartItem->delete();

    return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
}


}
