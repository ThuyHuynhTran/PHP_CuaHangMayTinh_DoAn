<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];

    public function product()
    {
        return $this->belongsTo(DienThoai::class);
    }

    /**
     * Lấy đơn hàng (order) mà item này thuộc về.
     * Mối quan hệ này là bắt buộc để code trong ReviewController có thể chạy.
     */
    public function order()
    {
        // Giả sử Model của bạn tên là Order và nằm ở App\Models\Order
        return $this->belongsTo(\App\Models\Order::class);
    }
}
