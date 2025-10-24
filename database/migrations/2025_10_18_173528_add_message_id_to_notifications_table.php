<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    /**
     * Hiển thị danh sách tất cả đơn hàng, có thể lọc theo trạng thái.
     */
    public function index(Request $request)
    {
        // Lấy query builder
        $query = Order::with('user')->latest();

        // Lọc theo trạng thái nếu có
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Hiển thị chi tiết của một đơn hàng.
     */
    public function show(Order $order)
    {
        // Tải trước thông tin người dùng và các sản phẩm trong đơn hàng
        $order->load('user', 'items.product');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Cập nhật trạng thái của một đơn hàng.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:cho_xac_nhan,cho_lay_hang,cho_giao_hang,da_giao,da_huy',
        ]);

        $order->update(['status' => $request->status]);
        
        // (Tùy chọn) Tại đây bạn có thể thêm logic gửi thông báo cho người dùng về việc cập nhật trạng thái đơn hàng.

        return redirect()->route('admin.orders.show', $order)->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
}
