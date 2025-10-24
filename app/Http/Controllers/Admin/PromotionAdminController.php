<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\Order;

class PromotionAdminController extends Controller
{
    /** 📄 Hiển thị danh sách khuyến mãi */
    public function index()
    {
        $promotions = Promotion::orderBy('id', 'desc')->paginate(10);
        return view('admin.promotions.index', compact('promotions'));
    }

    /** ➕ Trang thêm khuyến mãi mới */
    public function create()
    {
        return view('admin.promotions.create');
    }

    /** 💾 Lưu khuyến mãi mới */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Promotion::create($data);

        return redirect()
            ->route('admin.promotions.index')
            ->with('success', 'Thêm khuyến mãi thành công!');
    }

    /**
     * ✏️ Hiển thị form chỉnh sửa khuyến mãi
     *
     * @param  \App\Models\Promotion $promotion
     * @return \Illuminate\View\View
     */
    public function edit(Promotion $promotion) // <-- THAY ĐỔI Ở ĐÂY
    {
        // Laravel tự động tìm khuyến mãi dựa trên ID trong URL
        // Không cần dùng Promotion::findOrFail($id) nữa
        return view('admin.promotions.edit', compact('promotion'));
    }

    /**
     * 🔁 Cập nhật khuyến mãi
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Promotion $promotion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Promotion $promotion) // <-- THAY ĐỔI Ở ĐÂY
    {
        // Không cần dùng Promotion::findOrFail($id) nữa
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $promotion->update($data);

        return redirect()
            ->route('admin.promotions.index')
            ->with('success', 'Cập nhật khuyến mãi thành công!');
    }

    /**
     * ❌ Xóa 1 khuyến mãi
     *
     * @param  \App\Models\Promotion $promotion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Promotion $promotion) // <-- THAY ĐỔI Ở ĐÂY
    {
        // Không cần dùng Promotion::findOrFail($id) nữa
        try {
            // Gỡ liên kết với orders nếu có
            Order::where('promotion_id', $promotion->id)->update(['promotion_id' => null]);
            $promotion->delete();

            return redirect()
                ->back()
                ->with('success', 'Xóa khuyến mãi thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Không thể xóa khuyến mãi: ' . $e->getMessage());
        }
    }

    /** 🗑️ Xóa nhiều khuyến mãi cùng lúc */
    public function bulkDelete(Request $request)
    {
        // Chuyển chuỗi ids từ '1,2,3' thành mảng [1, 2, 3]
        $ids = explode(',', $request->input('ids'));

        // Lọc bỏ các giá trị rỗng hoặc không phải số
        $ids = array_filter($ids, 'is_numeric');

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một khuyến mãi để xóa.');
        }

        try {
            // Cập nhật các đơn hàng liên quan
            Order::whereIn('promotion_id', $ids)->update(['promotion_id' => null]);
            
            // Xóa các khuyến mãi
            Promotion::whereIn('id', $ids)->delete();

            return redirect()
                ->route('admin.promotions.index')
                ->with('success', 'Đã xóa các khuyến mãi được chọn thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Không thể xóa do ràng buộc dữ liệu: ' . $e->getMessage());
        }
    }
}
