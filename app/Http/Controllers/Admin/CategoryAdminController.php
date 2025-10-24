<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DanhMuc;
use App\Models\DienThoai; // Import model DienThoai

class CategoryAdminController extends Controller
{
    public function index()
    {
        $categories = DanhMuc::paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten_danh_muc' => 'required|string|max:255',
        ]);

        DanhMuc::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    public function edit($id)
    {
        $category = DanhMuc::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = DanhMuc::findOrFail($id);
        $data = $request->validate([
            'ten_danh_muc' => 'required|string|max:255',
        ]);

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Cập nhật phương thức destroy.
     */
    public function destroy($id)
    {
        $category = DanhMuc::with('products')->findOrFail($id);

        // Kiểm tra xem danh mục có sản phẩm nào không
        if ($category->products->count() > 0) {
            // Nếu có, trả về lỗi
            return redirect()->route('admin.categories.index')
                             ->with('error', 'Không thể xóa danh mục này vì vẫn còn sản phẩm thuộc về nó.');
        }

        // Nếu không có, tiến hành xóa
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công!');
    }
}
