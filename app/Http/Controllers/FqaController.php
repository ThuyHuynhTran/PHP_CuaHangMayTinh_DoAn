<?php
namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    // Hiển thị danh sách FAQ cho khách
    public function index()
    {
        $faqs = Faq::where('is_active', true)->get();
        return view('faq.index', compact('faqs'));
    }

    // Admin xem & quản lý
    public function adminIndex()
    {
        $faqs = Faq::all();
        return view('admin.faq.index', compact('faqs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
        ]);

        Faq::create($request->all());
        return back()->with('success', 'Đã thêm câu hỏi thành công!');
    }

    public function update(Request $request, Faq $faq)
    {
        $faq->update($request->all());
        return back()->with('success', 'Cập nhật thành công!');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return back()->with('success', 'Đã xóa câu hỏi!');
    }
}
