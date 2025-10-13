<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    // Hiển thị danh sách địa chỉ trên trang checkout
    public function index()
    {
        $addresses = Address::where('user_id', Auth::id())->get();
        return view('checkout', compact('addresses'));
    }

    // Lưu địa chỉ mới (từ popup hoặc trang riêng)
    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:100',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
        ]);

        // Nếu chọn "Đặt làm mặc định", hủy mặc định cũ
        if ($request->has('is_default')) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $newAddress = Address::create([
            'user_id'    => Auth::id(),
            'fullname'   => $request->fullname,
            'phone'      => $request->phone,
            'address'    => $request->address,
            'is_default' => $request->has('is_default'),
        ]);

        // Trả JSON nếu request là AJAX, ngược lại redirect
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm địa chỉ mới!',
                'address' => $newAddress
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm địa chỉ mới!');
    }

    // Trang quản lý địa chỉ (cho phép chọn hoặc xóa)
    public function manage()
    {
        $addresses = Address::where('user_id', Auth::id())->get();
        return view('address.manage', compact('addresses'));
    }

    // Đặt địa chỉ mặc định
    public function setDefault($id)
    {
        Address::where('user_id', Auth::id())->update(['is_default' => false]);
        Address::where('id', $id)
               ->where('user_id', Auth::id())
               ->update(['is_default' => true]);

        return response()->json(['success' => true, 'message' => 'Đã thay đổi địa chỉ mặc định!']);
    }

    // Xóa địa chỉ
    public function destroy($id)
    {
        Address::where('id', $id)
               ->where('user_id', Auth::id())
               ->delete();

        return back()->with('success', 'Đã xóa địa chỉ!');
    }

    // Hiển thị form thêm địa chỉ
    public function create()
    {
        return view('address.create');
    }

    // API trả về danh sách địa chỉ dạng JSON
    public function getAddressesJson()
    {
        $addresses = Address::where('user_id', Auth::id())->get();
        return response()->json($addresses);
    }
}
