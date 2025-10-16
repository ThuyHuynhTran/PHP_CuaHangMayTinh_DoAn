<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'fullname', 'phone', 'address',
        'payment_method', 'status', 'total',
    'promotion_id',     // Thêm vào đây
    'discount_amount',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
