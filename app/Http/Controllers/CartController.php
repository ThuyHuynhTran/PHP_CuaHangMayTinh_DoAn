<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DienThoai;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Promotion;
use App\Models\Order;
use Carbon\Carbon;

class CartController extends Controller
{
    /** 🛒 Thêm sản phẩm vào giỏ hàng */
    public function addToCart(Request $request, $productId)
    {
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để thêm sản phẩm!']);
            }
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thêm vào giỏ hàng.');
        }

        $user = Auth::user();
        $product = DienThoai::findOrFail($productId);

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

        $cartCount = Cart::where('user_id', $user->id)->sum('quantity');
        session(['cart_count' => $cartCount]);

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

    /** ✅ Thanh toán các sản phẩm được chọn */
    public function checkoutSelected(Request $request)
    {
        $selectedProductIds = $request->input('selected_products', []);
        $quantities = $request->input('quantities', []); // ✅ Nhận số lượng thực tế từ form

        if (empty($selectedProductIds)) {
            return redirect()->route('cart.view')->with('error', 'Vui lòng chọn sản phẩm.');
        }

        $user = Auth::user();

        $selectedItems = Cart::with('product')
            ->whereIn('id', $selectedProductIds)
            ->where('user_id', $user->id)
            ->get();

        // ✅ Cập nhật số lượng tạm thời trong dữ liệu tính toán
        foreach ($selectedItems as $item) {
            if (isset($quantities[$item->id])) {
                $item->quantity = (int) $quantities[$item->id];
            }
        }

        // ✅ Tính tổng tiền dựa trên số lượng mới
        $total = $selectedItems->sum(function ($item) {
            $price = (float) preg_replace('/[^\d.]/', '', $item->product->gia);
            return $price * $item->quantity;
        });

        $addresses = Address::where('user_id', $user->id)->get();

        // ✅ Lấy danh sách khuyến mãi còn hiệu lực mà user chưa sử dụng
        $usedPromotionIds = Order::where('user_id', $user->id)
                                ->whereNotNull('promotion_id')
                                ->pluck('promotion_id')
                                ->unique();

        $now = Carbon::now();
        $activePromotions = Promotion::where('start_date', '<=', $now)
                                    ->where('end_date', '>=', $now)
                                    ->whereNotIn('id', $usedPromotionIds)
                                    ->get();

        return view('checkout', [
            'cartItems' => $selectedItems,
            'total' => $total,
            'addresses' => $addresses,
            'activePromotions' => $activePromotions,
        ]);
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
