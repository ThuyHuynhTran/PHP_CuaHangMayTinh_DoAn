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
   // Trong file: app/Http/Controllers/AddressController.php

public function store(Request $request)
{
    $request->validate([
        'fullname' => 'required|string|max:100',
        'phone'    => 'required|regex:/^[0-9]{9,15}$/',
        'address'  => 'required|string|max:255',
    ]);

    // Nếu chọn "Đặt làm mặc định", hủy mặc định cũ
    if ($request->has('is_default')) {
        Address::where('user_id', Auth::id())->update(['is_default' => false]);
    }

    // Tạo địa chỉ mới
    $newAddress = Address::create([
        'user_id'    => Auth::id(),
        'fullname'   => $request->fullname,
        'phone'      => $request->phone,
        'address'    => $request->address,
        'is_default' => $request->has('is_default'),
    ]);

    // ✅ Luôn trả về phản hồi JSON cho AJAX
    return response()->json([
        'success' => true,
        'message' => 'Đã thêm địa chỉ mới thành công!',
        'address' => $newAddress
    ]);
}

    // Trang quản lý địa chỉ (cho phép chọn hoặc xóa)
    public function manage()
    {
        $addresses = Address::where('user_id', Auth::id())->get();
        return view('address.manage', compact('addresses'));
    }

    // Đặt địa chỉ mặc định
   public function setDefault(Request $request)
{
    $addressId = $request->input('address_id');
    $userId = auth()->id();

    // Bỏ mặc định cũ
    Address::where('user_id', $userId)->update(['is_default' => false]);

    // Cập nhật mặc định mới
    $address = Address::where('user_id', $userId)->where('id', $addressId)->first();
    if ($address) {
        $address->is_default = true;
        $address->save();
        return response()->json(['success' => true, 'message' => 'Đã đặt địa chỉ mặc định.']);
    }
    return response()->json(['success' => false, 'message' => 'Không tìm thấy địa chỉ.']);
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
