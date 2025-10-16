<?php
namespace App\Events;

use App\Models\Promotion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PromotionAdded
{
    use Dispatchable, SerializesModels;

    public $promotion;

    public function __construct(Promotion $promotion)
    {
        $this->promotion = $promotion;
    }

    public function broadcastOn()
    {
        return new Channel('promotion-channel'); // Kênh thông báo real-time
    }
}
