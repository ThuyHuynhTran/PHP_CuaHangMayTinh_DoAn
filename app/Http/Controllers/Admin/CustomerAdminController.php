<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order; // ✅ Thêm dòng này
use Illuminate\Support\Facades\Auth;

class CustomerAdminController extends Controller
{
    /**
     * 🧩 Hiển thị danh sách khách hàng
     */
    public function index()
    {
        // ✅ Kiểm tra quyền admin trực tiếp (không cần middleware)
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('home')
                ->with('status', 'Bạn không có quyền truy cập trang quản trị!');
        }

        // 📋 Lấy danh sách khách hàng (role = user)
        $customers = User::where('role', 'user')->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * 👤 Hiển thị chi tiết khách hàng và danh sách đơn hàng
     */
    public function show($id)
    {
        // 🔍 Tìm khách hàng
        $user = User::findOrFail($id);

        // 📦 Lấy danh sách đơn hàng của khách hàng
        $orders = Order::where('user_id', $id)
                        ->latest()
                        ->paginate(10);

        return view('admin.customers.show', compact('user', 'orders'));
    }

    /**
     * 🔒 Bật / tắt khóa tài khoản khách hàng
     */
    public function toggleLock($id)
    {
        // ✅ Kiểm tra quyền admin trực tiếp
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('home')
                ->with('status', 'Bạn không có quyền thực hiện thao tác này!');
        }

        // 🔍 Tìm người dùng
        $user = User::findOrFail($id);

        // 🧩 Đảo trạng thái khóa tài khoản
        $user->is_locked = !$user->is_locked;
        $user->login_attempts = 0; // reset số lần sai khi mở khóa
        $user->save();

        // ✅ Gửi thông báo
        return redirect()->back()->with('success', 'Cập nhật trạng thái tài khoản thành công!');
    }
}
