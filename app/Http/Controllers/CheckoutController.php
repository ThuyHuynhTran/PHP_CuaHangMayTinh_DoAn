<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DienThoai;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Promotion;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    /**
     * 🛒 Hiển thị trang thanh toán (Mua ngay hoặc từ giỏ hàng)
     */
       /**
     * 🛒 Hiển thị trang thanh toán (Mua ngay hoặc từ giỏ hàng)
     */
    public function checkoutNow($id = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để mua hàng.');
        }

        $user = Auth::user();
        $addresses = Address::where('user_id', $user->id)->get();

        // --- 🔹 BẮT ĐẦU LOGIC NÂNG CẤP 🔹 ---

        // 1. Lấy danh sách ID các khuyến mãi mà user này ĐÃ SỬ DỤNG
        $usedPromotionIds = Order::where('user_id', $user->id)
                                 ->whereNotNull('promotion_id') // Chỉ lấy các đơn hàng có áp dụng KM
                                 ->pluck('promotion_id') // Chỉ lấy cột promotion_id
                                 ->unique(); // Lấy các giá trị duy nhất

        // 2. Lấy các khuyến mãi đang hiệu lực VÀ user CHƯA TỪNG SỬ DỤNG
        $now = Carbon::now();
        $activePromotions = Promotion::where('start_date', '<=', $now)
                                     ->where('end_date', '>=', $now)
                                     ->whereNotIn('id', $usedPromotionIds) // Điều kiện mới quan trọng nhất
                                     ->get();

        // --- 🔹 KẾT THÚC LOGIC NÂNG CẤP 🔹 ---


        // Logic cũ của bạn để xử lý "mua ngay"
        if ($id) {
            $product = DienThoai::findOrFail($id);
            $unitPrice = (float) preg_replace('/[^\d.]/', '', $product->gia);
            $total = $unitPrice;
            return view('checkout', compact('product', 'total', 'addresses', 'activePromotions'));
        }

        // Logic cũ của bạn để xử lý "giỏ hàng"
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();
        $total = 0;
        foreach ($cartItems as $item) {
            $price = (float) preg_replace('/[^\d.]/', '', $item->product->gia);
            $total += $price * $item->quantity;
        }

        return view('checkout', compact('cartItems', 'total', 'addresses', 'activePromotions'));
    }


    /**
     * 💳 Xử lý thanh toán (cho cả mua ngay và giỏ hàng)
     */
    public function store(Request $request)
    {
        $request->validate(['payment_method' => 'required|string']);

        $user = Auth::user();
        $cartItems = [];

        if ($request->product_id) {
            $product = DienThoai::find($request->product_id);
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại.']);
            }
            $cartItems[] = ['product_id' => $product->id, 'product_name' => $product->ten_sp, 'price' => (float) preg_replace('/[^\d.]/', '', $product->gia), 'quantity' => 1];
        } else {
            $dbCartItems = Cart::where('user_id', $user->id)->with('product')->get();
            if ($dbCartItems->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Giỏ hàng trống.']);
            }
            foreach ($dbCartItems as $item) {
                $cartItems[] = ['product_id' => $item->product->id, 'product_name' => $item->product->ten_sp, 'price' => (float) preg_replace('/[^\d.]/', '', $item->product->gia), 'quantity' => $item->quantity];
            }
        }

        if (empty($cartItems)) {
            return response()->json(['success' => false, 'message' => 'Không có sản phẩm để thanh toán.']);
        }

        $subtotal = collect($cartItems)->sum(fn($i) => $i['price'] * $i['quantity']);
        $promotionId = null;
        $discountAmount = 0;
        $finalTotal = $subtotal;

        if ($request->filled('promotion_id')) {
            $promotion = Promotion::find($request->promotion_id);
            $now = Carbon::now();
            if ($promotion && $now->between($promotion->start_date, $promotion->end_date)) {
                $discountAmount = $subtotal * ($promotion->discount_percent / 100);
                $finalTotal = $subtotal - $discountAmount;
                $promotionId = $promotion->id;
            }
        }

        DB::beginTransaction();
        try {
            $defaultAddress = Address::where('user_id', $user->id)->where('is_default', true)->first();

            $order = Order::create([
                'user_id' => $user->id,
                'fullname' => $defaultAddress->fullname ?? $user->name,
                'phone' => $defaultAddress->phone ?? ($user->phone ?? 'Không có'),
                'address' => $defaultAddress->address ?? 'Chưa có địa chỉ',
                'payment_method' => $request->payment_method,
                'status' => 'Chờ xác nhận',
                'total' => $finalTotal,
                'promotion_id' => $promotionId,
                'discount_amount' => $discountAmount,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            if (!$request->product_id) {
                Cart::where('user_id', $user->id)->delete();
            }

            DB::commit();
            return response()->json(['success' => true, 'order_id' => $order->id, 'message' => 'Đặt hàng thành công!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Lỗi khi đặt hàng: ' . $e->getMessage()]);
        }
    }

    /**
     * 📋 Trang "Đơn hàng của tôi"
     */
    public function myorder(Request $request)
    {
        $query = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->latest();

        $statusMap = [
            'cho_xac_nhan' => 'Chờ xác nhận',
            'cho_lay_hang' => 'Chờ lấy hàng',
            'cho_giao_hang' => 'Đang giao',
            'da_giao'       => 'Đã giao',
            'tra_hang'      => 'Trả hàng',
            'da_huy'        => 'Đã hủy',
        ];

        if ($request->has('status') && $request->status) {
            $mapped = $statusMap[$request->status] ?? $request->status;
            $query->where('status', $mapped);
        }

        $orders = $query->get();
        return view('orders.myorder', compact('orders'));
    }

    /**
     * 📜 Lịch sử đơn hàng
     */
    public function orderHistory()
    {
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('orders.history', compact('orders'));
    }

    /**
     * ❌ Hủy đơn hàng
     */
    public function cancel(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->status = 'Đã hủy';
        $order->cancel_reason = $request->reason ?: $request->other_reason;
        $order->save();
        return redirect()->back()->with('status', 'Đơn hàng đã được hủy thành công.');
    }

    /**
     * 🔍 Xem chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        return view('orders.show', compact('order'));
    }
}

