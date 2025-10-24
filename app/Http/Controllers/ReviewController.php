<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Lưu một đánh giá mới.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:dien_thoais,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'images' => 'nullable|array|max:3', // ✅ Đảm bảo Laravel hiểu đây là mảng ảnh
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        // ✅ KIỂM TRA QUYỀN ĐÁNH GIÁ: User phải mua hàng và đơn hàng phải ở trạng thái "Đã giao"
        $hasPurchasedAndDelivered = \App\Models\OrderItem::where('product_id', $productId)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId)->where('status', 'Đã giao');
            })
            ->exists();

        if (!$hasPurchasedAndDelivered) {
            return redirect()->back()->with('status', 'Bạn chỉ có thể đánh giá sản phẩm sau khi đã nhận hàng thành công.');
        }

        // ✅ KIỂM TRA XEM ĐÃ TỒN TẠI ĐÁNH GIÁ CHƯA
        $existingReview = Review::where('user_id', $userId)->where('product_id', $productId)->first();
        if ($existingReview) {
             return redirect()->back()->with('status', 'Bạn đã đánh giá sản phẩm này rồi.');
        }

        // ✅ TẠO ĐÁNH GIÁ MỚI
        $review = Review::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // ✅ LƯU NHIỀU ẢNH (tối đa 3)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('reviews', 'public');
                    $review->images()->create(['path' => $path]);
                }
            }
        }

        return redirect()->back()->with('status', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    /**
     * Lấy dữ liệu của một đánh giá để chỉnh sửa.
     */
    public function edit(Review $review)
    {
        // Chính sách bảo mật: Chỉ chủ nhân của đánh giá mới có quyền xem để sửa
        if (Auth::id() !== $review->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($review->load('images'));
    }

    /**
     * Cập nhật một đánh giá đã có.
     */
    public function update(Request $request, Review $review)
    {
        // Chính sách bảo mật: Chỉ chủ nhân của đánh giá mới có quyền sửa
        if (Auth::id() !== $review->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'images' => 'nullable|array|max:3', // ✅ validate mảng ảnh
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // ✅ Xử lý cập nhật ảnh (xóa ảnh cũ, thêm ảnh mới)
        if ($request->hasFile('images')) {
            // Xóa ảnh cũ
            foreach ($review->images as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }

            // Thêm ảnh mới
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('reviews', 'public');
                    $review->images()->create(['path' => $path]);
                }
            }
        }

        return redirect()->back()->with('status', 'Cập nhật đánh giá thành công!');
    }
 public function toggleLike(Review $review)
{
    $userId = Auth::id();

    $liked = \DB::table('review_likes')
        ->where('review_id', $review->id)
        ->where('user_id', $userId)
        ->exists();

    if ($liked) {
        \DB::table('review_likes')
            ->where('review_id', $review->id)
            ->where('user_id', $userId)
            ->delete();
        $isLiked = false;
    } else {
        \DB::table('review_likes')->insert([
            'review_id' => $review->id,
            'user_id' => $userId,
            'created_at' => now(),
        ]);
        $isLiked = true;
    }

    $likesCount = \DB::table('review_likes')->where('review_id', $review->id)->count();

    return response()->json([
        'likes' => $likesCount,
        'liked' => $isLiked
    ]);
}


}
