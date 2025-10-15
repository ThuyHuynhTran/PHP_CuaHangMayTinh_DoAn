<?php

namespace App\Http\Controllers;

use App\Models\DienThoai;
use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * ✅ Lưu hoặc cập nhật đánh giá sản phẩm (có kèm ảnh)
     */
    public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:dien_thoais,id',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
        'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $userId = Auth::id();
    $productId = $request->product_id;

    // ✅ Kiểm tra người dùng đã mua và đơn hàng đã giao
    $hasPurchased = \App\Models\OrderItem::whereHas('order', function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->where('status', 'Đã giao');
        })
        ->where('product_id', $productId)
        ->exists();

    if (!$hasPurchased) {
        return back()->with('error', '⚠️ Bạn chỉ có thể đánh giá sản phẩm sau khi đã mua và nhận hàng.');
    }

    // ✅ Kiểm tra đã từng đánh giá chưa
    $alreadyReviewed = Review::where('user_id', $userId)
        ->where('product_id', $productId)
        ->exists();

    if ($alreadyReviewed) {
        return back()->with('error', '⚠️ Bạn đã đánh giá sản phẩm này rồi.');
    }

    // ✅ Tạo mới review
    $review = Review::create([
        'user_id' => $userId,
        'product_id' => $productId,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    // ✅ Lưu ảnh nếu có
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('reviews', 'public');
            \App\Models\ReviewImage::create([
                'review_id' => $review->id,
                'path' => $path,
            ]);
        }
    }

    return back()->with('success', '🎉 Cảm ơn bạn đã đánh giá sản phẩm!');
}


    /**
     * 🧾 Hiển thị chi tiết sản phẩm kèm đánh giá
     */
    public function show($id)
    {
        $product = DienThoai::with([
            'reviews.user',     // người viết đánh giá
            'reviews.images',   // ảnh trong đánh giá
        ])->findOrFail($id);

        return view('product_detail', compact('product'));
    }
}
