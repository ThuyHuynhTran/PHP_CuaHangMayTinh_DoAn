<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DienThoai;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Cart; // 🔹 Thêm model giỏ hàng (bạn phải có model này)

class CheckoutController extends Controller
{
    /** 
     * 🛒 Hiển thị trang thanh toán (Mua ngay hoặc từ giỏ hàng)
     */
    public function checkoutNow($id = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để mua hàng.');
        }

        $addresses = Address::where('user_id', Auth::id())->get();

        // Nếu có id sản phẩm => mua ngay
        if ($id) {
            $product = DienThoai::findOrFail($id);
            $unitPrice = (float) preg_replace('/[^\d.]/', '', $product->gia);
            $total = $unitPrice;

            return view('checkout', compact('product', 'total', 'addresses'));
        }

        // Nếu không có id => lấy giỏ hàng
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $total = 0;
        foreach ($cartItems as $item) {
            $price = (float) preg_replace('/[^\d.]/', '', $item->product->gia);
            $total += $price * $item->quantity;
        }

        return view('checkout', compact('cartItems', 'total', 'addresses'));
    }

    /** 
     * 💳 Xử lý thanh toán (cho cả mua ngay và giỏ hàng)
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $user = Auth::user();
        $cartItems = [];

        // ✅ Nếu là mua ngay (có product_id)
        if ($request->product_id) {
            $product = DienThoai::find($request->product_id);
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại.']);
            }

            $cartItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->ten_sp,
                'price' => (float) preg_replace('/[^\d.]/', '', $product->gia),
                'quantity' => 1,
            ];
        } 
        // ✅ Nếu không có product_id (thanh toán từ giỏ hàng)
        else {
            $dbCartItems = Cart::where('user_id', $user->id)->with('product')->get();

            if ($dbCartItems->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Giỏ hàng trống.']);
            }

            foreach ($dbCartItems as $item) {
                $cartItems[] = [
                    'product_id' => $item->product->id,
                    'product_name' => $item->product->ten_sp,
                    'price' => (float) preg_replace('/[^\d.]/', '', $item->product->gia),
                    'quantity' => $item->quantity,
                ];
            }
        }

        if (empty($cartItems)) {
            return response()->json(['success' => false, 'message' => 'Không có sản phẩm để thanh toán.']);
        }

        DB::beginTransaction();

        try {
            // ✅ Lấy địa chỉ mặc định
            $defaultAddress = Address::where('user_id', $user->id)
                ->where('is_default', true)
                ->first();

            // ✅ Tạo đơn hàng
            $order = Order::create([
                'user_id' => $user->id,
                'fullname' => $defaultAddress->fullname ?? $user->name,
                'phone' => $defaultAddress->phone ?? ($user->phone ?? 'Không có'),
                'address' => $defaultAddress->address ?? 'Chưa có địa chỉ',
                'payment_method' => $request->payment_method,
                'status' => 'Chờ xác nhận',
                'total' => collect($cartItems)->sum(fn($i) => $i['price'] * $i['quantity']),
            ]);

            // ✅ Lưu từng sản phẩm vào OrderItem
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            // ✅ Nếu là giỏ hàng thì xóa sau khi đặt hàng
            if (!$request->product_id) {
                Cart::where('user_id', $user->id)->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Đặt hàng thành công!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi đặt hàng: ' . $e->getMessage(),
            ]);
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
