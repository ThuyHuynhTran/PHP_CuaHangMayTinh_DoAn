<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    /**
     * Hiển thị danh sách tất cả đơn hàng,
     * có thể lọc theo trạng thái và thời gian.
     */
    public function index(Request $request)
    {
        // Khởi tạo query builder
        $query = Order::with('user')->latest();

        // 🧩 Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🕒 Lọc theo thời gian (từ ngày - đến ngày)
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Phân trang (giữ các query params khi chuyển trang)
        $orders = $query->paginate(15)->appends($request->query());

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
            'status' => 'required|in:Chờ xác nhận,Chờ lấy hàng,Chờ giao hàng,Đã giao,Đã hủy',
        ]);

        // Lưu lại trạng thái cũ để so sánh
        $oldStatus = $order->status;

        // Cập nhật trạng thái mới
        $order->update(['status' => $request->status]);
        $order->load('items.product');


        /**
         * ==============================================
         * 🧩 QUẢN LÝ TỒN KHO TỰ ĐỘNG
         * ==============================================
         */

        // Nếu đơn được chuyển sang "Đã giao" → trừ tồn kho (nếu chưa trừ)
        if (in_array($request->status, ['Chờ lấy hàng', 'Chờ giao hàng', 'Đã giao']) && $oldStatus === 'Chờ xác nhận') {
            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product && $product->so_luong_kho >= $item->quantity) {
                    $product->decrement('so_luong_kho', $item->quantity);
                }
            }
        }

        // Nếu đơn bị hủy → cộng tồn kho lại
        if ($oldStatus !== 'Đã hủy' && $request->status === 'Đã hủy') {
            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->increment('so_luong_kho', $item->quantity);
                }
            }
        }

        // ✅ (Tuỳ chọn): Gửi thông báo cho khách hàng
        // Notification::send($order->user, new OrderStatusUpdated($order));

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
}
