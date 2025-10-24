<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewAdminController extends Controller
{
    /**
     * Hiển thị danh sách tất cả đánh giá.
     */
    public function index()
    {
        $reviews = Review::with(['user', 'product'])->orderBy('id', 'desc')->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Hiển thị trang để admin nhập phản hồi.
     */
    public function reply(Review $review)
    {
        return view('admin.reviews.reply', compact('review'));
    }

    /**
     * Lưu phản hồi của admin cho một đánh giá.
     */
    public function storeReply(Request $request, Review $review)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:1000',
        ]);

        $review->update([
            'admin_reply' => $request->admin_reply,
        ]);

        // Chuyển hướng về trang danh sách với thông báo thành công
        return redirect()->route('admin.reviews.index')->with('success', 'Phản hồi đánh giá thành công!');
    }

    /**
     * Xóa một đánh giá.
     */
    public function destroy(Review $review)
    {
        try {
            $review->delete();
            return redirect()->back()->with('success', 'Xóa đánh giá thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Không thể xóa đánh giá: ' . $e->getMessage());
        }
    }
}

