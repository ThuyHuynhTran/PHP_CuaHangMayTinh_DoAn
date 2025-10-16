<?php

namespace App\Observers;

use App\Models\Notification;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PromotionObserver
{
    /**
     * Handle the Promotion "created" event.
     */
    public function created(Promotion $promotion): void
    {
        Log::info('PromotionObserver "created" event triggered for promotion ID: ' . $promotion->id);

        $users = User::all();
        Log::info('Found ' . $users->count() . ' users to notify.');

        if ($users->isEmpty()) {
            Log::warning('No users found. No notifications will be created.');
            return;
        }

        foreach ($users as $user) {
            try {
                // --- CẬP NHẬT NỘI DUNG TẠI ĐÂY ---
                Notification::create([
                    'user_id' => $user->id,
                    'title'   => "🎉 Giảm giá SỐC {$promotion->discount_percent}%!", // Hiển thị % giảm giá
                    'content' => "Chương trình '{$promotion->title}' giảm giá {$promotion->discount_percent}% đã bắt đầu. Áp dụng ngay!", // Hiển thị % giảm giá
                ]);
                Log::info('Successfully created notification for user ID: ' . $user->id);
            } catch (\Exception $e) {
                Log::error('Failed to create notification for user ID: ' . $user->id . ' | Error: ' . $e->getMessage());
            }
        }
    }
}

