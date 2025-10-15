@extends('layouts.main')

@section('content')
<main style="min-height:80vh; display:flex; flex-direction:column; justify-content:center; align-items:center; background-color:#f9f9f9; padding:40px 20px;">
    <div style="background:#fff; border-radius:12px; padding:40px 50px; text-align:center; box-shadow:0 4px 10px rgba(0,0,0,0.1); max-width:600px;">
        
        <div style="font-size:60px; color:#22bb33; margin-bottom:20px;">
            <i class="fas fa-check-circle"></i>
        </div>

        <h2 style="color:#22bb33; margin-bottom:10px;">Bạn đã đặt hàng thành công!</h2>
        <p style="color:#555;">Cảm ơn bạn đã mua sắm tại cửa hàng của chúng tôi.</p>

        <p style="margin:20px 0; font-size:16px; color:#333;">
            <strong>Mã đơn hàng:</strong> #{{ $order->id }}
        </p>

        <div style="display:flex; justify-content:center; gap:20px; margin-top:25px;">
            <a href="{{ route('home') }}" 
               style="background:#c21b1b; color:white; padding:12px 25px; border-radius:8px; text-decoration:none; font-weight:bold;">
               ← Quay lại trang chủ
            </a>

            <a href="{{ route('orders.history') }}" 
               style="background:#0099cc; color:white; padding:12px 25px; border-radius:8px; text-decoration:none; font-weight:bold;">
               Xem chi tiết đơn hàng
            </a>
        </div>
    </div>
</main>
@endsection
