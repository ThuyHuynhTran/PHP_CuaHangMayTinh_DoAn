<?php

namespace App\Observers;

use App\Models\Promotion;
use App\Models\PromotionSubscriber;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class PromotionObserver
{
    public function created(Promotion $promotion): void
    {
        Log::info('📢 PromotionObserver triggered: Promotion ID = ' . $promotion->id);

        try {
            // ✅ Chỉ lấy subscribers đã xác minh (is_verified = 1)
            $subscribers = PromotionSubscriber::where('is_verified', 1)->get();

            if ($subscribers->isEmpty()) {
                Log::warning('⚠️ Không có subscriber nào đã xác minh để nhận thông báo.');
                return;
            }

            foreach ($subscribers as $sub) {
                // ✅ Tìm user tương ứng
                $user = User::where('email', $sub->email)->first();

                if (!$user) {
                    Log::warning("⚠️ Không tìm thấy user tương ứng với email: {$sub->email}");
                    continue; // bỏ qua subscriber không có user
                }

                Notification::create([
                    'user_id'        => $user->id,
                    'promotion_id'   => $promotion->id,
                    'message'        => "🎉 {$promotion->title} - Giảm {$promotion->discount_percent}%",
                    'is_read'        => false,
                    'type'           => 'promotion',
                    'notifiable_type'=> Promotion::class,
                    'notifiable_id'  => $promotion->id,
                    'data'           => json_encode([
                        'email'      => $sub->email,
                        'discount'   => $promotion->discount_percent,
                        'start_date' => $promotion->start_date,
                        'end_date'   => $promotion->end_date,
                    ]),
                ]);

                // ✅ Cập nhật trạng thái subscriber
                $sub->update(['is_notified' => 1]);

                Log::info("✅ Notification created for verified subscriber: {$sub->email}");
            }

            Log::info('🎯 PromotionObserver: Gửi thông báo thành công cho tất cả subscribers đã xác minh.');
        } catch (\Throwable $e) {
            Log::error('❌ Lỗi khi gửi thông báo khuyến mãi: ' . $e->getMessage());
        }
    }
}
