<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DienThoai;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /** 🛒 Thêm sản phẩm vào giỏ hàng */
    public function addToCart(Request $request, $productId)
    {
        // Yêu cầu đăng nhập
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để thêm sản phẩm!']);
            }
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thêm vào giỏ hàng.');
        }

        $user = Auth::user();
        $product = DienThoai::findOrFail($productId);

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $cartItem = Cart::where('user_id', $user->id)
                        ->where('product_id', $product->id)
                        ->first();

        if ($cartItem) {
            $cartItem->quantity++;
            $cartItem->updated_at = now();
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        // 🔹 Cập nhật số lượng hiển thị trên icon
        $cartCount = Cart::where('user_id', $user->id)->sum('quantity');
        session(['cart_count' => $cartCount]);

        // Trả về JSON nếu request là AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'count' => $cartCount,
                'message' => 'Sản phẩm đã được thêm vào giỏ hàng!',
            ]);
        }

        return redirect()->back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
    }

    /** 🧾 Hiển thị giỏ hàng */
    public function viewCart()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $total = $cartItems->sum(function ($item) {
            $gia = (float) preg_replace('/[^\d.]/', '', $item->product->gia);
            return $gia * $item->quantity;
        });

        return view('cart', compact('cartItems', 'total'));
    }

    /** 🧾 (Không dùng nữa — session cũ) */
    public function index()
    {
        $cartItems = session()->get('cart', []);
        $total = collect($cartItems)->sum(function ($item) {
            return (float) preg_replace('/[^\d.]/', '', $item['product']->gia) * $item['quantity'];
        });

        return view('cart', compact('cartItems', 'total'));
    }

    /** ✅ Thanh toán các sản phẩm được chọn */
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

        $total = $selectedItems->sum(function ($item) {
            $price = (float) preg_replace('/[^\d.]/', '', $item->product->gia);
            return $price * $item->quantity;
        });

        $addresses = Address::where('user_id', Auth::id())->get();

        return view('checkout', [
            'cartItems' => $selectedItems,
            'total' => $total,
            'addresses' => $addresses,
        ]);
    }

    /** 💳 Mua ngay 1 sản phẩm */
    public function checkoutNow($id)
    {
        $product = DienThoai::findOrFail($id);

        $cartItems = collect([
            (object)[
                'product' => $product,
                'quantity' => 1
            ]
        ]);

        $total = (float) preg_replace('/[^\d.]/', '', $product->gia);
        $addresses = Address::where('user_id', Auth::id())->get();

        return view('checkout', compact('cartItems', 'total', 'addresses'));
    }

    /** ❌ Xóa sản phẩm khỏi giỏ hàng */
    public function removeFromCart(Request $request, $id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$cartItem) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm trong giỏ hàng.']);
            }
            return redirect()->back()->with('error', 'Không tìm thấy sản phẩm trong giỏ hàng.');
        }

        $cartItem->delete();

        // Cập nhật lại tổng số lượng giỏ hàng
        $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
        session(['cart_count' => $cartCount]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'count' => $cartCount,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.'
            ]);
        }

        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }
}
