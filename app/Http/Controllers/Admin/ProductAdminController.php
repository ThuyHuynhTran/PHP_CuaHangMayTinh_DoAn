<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DienThoai;
use App\Models\DanhMuc;

class ProductAdminController extends Controller
{
    /**
     * 📋 Hiển thị danh sách sản phẩm
     */
    public function index(Request $request)
    {
        // 🔍 Khởi tạo query builder
        $query = DienThoai::with('category')->orderBy('id', 'desc');

        // 🧩 Lọc theo danh mục (nếu có)
        if ($request->filled('danh_muc_id')) {
            $query->where('danh_muc_id', $request->danh_muc_id);
        }

        // 🧩 Lọc theo thương hiệu (nếu có)
        if ($request->filled('thuong_hieu')) {
            $query->where('thuong_hieu', 'like', '%' . $request->thuong_hieu . '%');
        }

        // 📦 Lấy danh sách danh mục để hiển thị trong form lọc
        $categories = DanhMuc::all();

        // 📄 Phân trang + giữ nguyên query khi chuyển trang
        $products = $query->paginate(10)->appends($request->query());

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * ➕ Form thêm sản phẩm
     */
    public function create()
    {
        $categories = DanhMuc::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * 💾 Lưu sản phẩm mới vào database
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'ten_sp'        => 'required|string|max:255',
            'mo_ta'         => 'nullable|string',
            'gia'           => 'required|numeric|min:0',
            'so_luong_kho'  => 'required|integer|min:0', // 🆕 Thêm kiểm tra số lượng tồn
            'thuong_hieu'   => 'nullable|string|max:255', // 🆕 Cho phép nhập thương hiệu
            'danh_muc_id'   => 'required|exists:danh_mucs,id',
            'duong_dan'     => 'nullable|image|max:2048',
        ]);

        // Kiểm tra thư mục ảnh tồn tại
        if (!file_exists(public_path('assets/img'))) {
            mkdir(public_path('assets/img'), 0777, true);
        }

        // Nếu có upload ảnh
        if ($request->hasFile('duong_dan')) {
            $file = $request->file('duong_dan');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/img'), $filename);
            $data['duong_dan'] = $filename;
        }

        // Tạo sản phẩm
        DienThoai::create($data);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Thêm sản phẩm thành công!');
    }

    /**
     * ✏️ Form chỉnh sửa sản phẩm
     */
    public function edit($id)
    {
        $product = DienThoai::findOrFail($id);
        $categories = DanhMuc::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * 🔄 Cập nhật sản phẩm
     */
    public function update(Request $request, $id)
    {
        $product = DienThoai::findOrFail($id);

        $data = $request->validate([
            'ten_sp'        => 'required|string|max:255',
            'gia'           => 'required|numeric|min:0',
            'so_luong_kho'  => 'required|integer|min:0', // 🆕 Cập nhật số lượng tồn
            'thuong_hieu'   => 'nullable|string|max:255', // 🆕 Cho phép sửa thương hiệu
            'danh_muc_id'   => 'required|exists:danh_mucs,id',
            'mo_ta'         => 'nullable|string',
            'duong_dan'     => 'nullable|image|max:2048'
        ]);

        // Nếu có upload ảnh mới
        if ($request->hasFile('duong_dan')) {
            $oldImagePath = public_path('assets/img/' . $product->duong_dan);

            // Xóa ảnh cũ nếu có
            if ($product->duong_dan && file_exists($oldImagePath)) {
                @unlink($oldImagePath);
            }

            // Lưu ảnh mới
            $file = $request->file('duong_dan');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/img'), $filename);
            $data['duong_dan'] = $filename;
        }

        // Cập nhật sản phẩm
        $product->update($data);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    /**
     * ❌ Xóa sản phẩm
     */
    public function destroy($id)
    {
        $product = DienThoai::findOrFail($id);

        try {
            if ($product->duong_dan) {
                $imagePath = public_path('assets/img/' . $product->duong_dan);
                if (file_exists($imagePath)) {
                    @unlink($imagePath);
                }
            }

            $product->delete();

            return redirect()->route('admin.products.index')
                             ->with('success', 'Xóa sản phẩm thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')
                             ->with('error', 'Đã xảy ra lỗi khi xóa sản phẩm!');
        }
    }
}
