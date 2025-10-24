@extends('layouts.main')

@section('content')
<main style="background:#f5f5f5; min-height:100vh; padding:20px 0; font-family:'Segoe UI', Arial;">
    <div class="container" style="max-width:750px; margin:auto; background:white; border-radius:12px; box-shadow:0 2px 6px rgba(0,0,0,0.1); padding:20px 25px;">

        <h3 style="font-weight:bold; color:#c21b1b; border-bottom:2px solid #f0f0f0; padding-bottom:10px;">📦 Thông tin đơn hàng</h3>

        <!-- Địa chỉ giao hàng -->
        <div style="margin-top:20px;">
            <h4 style="color:#333; font-size:17px;">Địa chỉ nhận hàng</h4>
            <p style="margin:5px 0 0 0; font-weight:bold;">{{ $order->fullname }}</p>
            <p style="margin:0;">📞 {{ $order->phone }}</p>
            <p style="margin:0;">🏠 {{ $order->address }}</p>
        </div>

        <!-- Danh sách sản phẩm -->
        <div style="margin-top:20px;">
            <h4 style="color:#333; font-size:17px;">Sản phẩm</h4>

            @foreach($order->items as $item)
                <div style="display:flex; align-items:center; justify-content:space-between; border:1px solid #eee; border-radius:8px; padding:10px; margin-bottom:10px;">
                    <div style="display:flex; gap:10px; align-items:center;">
                        <img src="{{ asset('assets/img/' . $item->product->duong_dan) }}" alt="{{ $item->product->ten_sp }}" 
                             style="width:70px; height:70px; border-radius:8px; object-fit:cover;">
                        <div>
                            <p style="margin:0; font-weight:600;">{{ $item->product->ten_sp }}</p>
                            <p style="color:#666; margin:5px 0;">x{{ $item->quantity }}</p>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <p style="margin:0; font-weight:bold; color:#c21b1b;">{{ number_format($item->price, 0, ',', '.') }}₫</p>
                    </div>
                </div>
            @endforeach

            <div style="text-align:right; margin-top:10px;">
                <strong>Thành tiền:</strong>
                <span style="color:#c21b1b; font-size:18px; font-weight:bold;">
                    {{ number_format($order->total, 0, ',', '.') }}₫
                </span>
            </div>
        </div>

        <!-- Khuyến mãi áp dụng -->
        <div style="margin-top:20px; border-top:1px solid #eee; padding-top:10px;">
            <h4 style="color:#333; font-size:17px;">Khuyến mãi áp dụng</h4>
            @if($order->promotion)
                <p style="margin:0;">
                    🎁 <strong>{{ $order->promotion->title }}</strong>  
                    (Giảm {{ rtrim(rtrim(number_format($order->promotion->discount_percent, 2), '0'), '.') }}%)
                </p>
                <p style="color:green; margin-top:3px;">
                    Tiết kiệm: 
                    <strong>{{ number_format(($order->total_before_discount - $order->total), 0, ',', '.') }}₫</strong>
                </p>
            @else
                <p style="margin:0; color:#777;">Không áp dụng khuyến mãi</p>
            @endif
        </div>

        <!-- Thông tin thanh toán -->
        <div style="margin-top:20px; border-top:1px solid #eee; padding-top:10px;">
            <h4 style="color:#333; font-size:17px;">Phương thức thanh toán</h4>
            <p style="margin:0;">{{ strtoupper($order->payment_method) }}</p>

            <h4 style="color:#333; font-size:17px; margin-top:10px;">Trạng thái đơn hàng</h4>
            <p style="margin:0; color:green; font-weight:bold;">{{ ucfirst($order->status) }}</p>

            <p style="margin-top:10px; color:#666;">⏰ Thời gian đặt hàng: {{ $order->created_at->format('H:i d/m/Y') }}</p>
        </div>

        <!-- Các nút hành động -->
        <div style="margin-top:30px; display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px;">
            <a href="{{ route('orders.history') }}" 
               style="flex:1; text-align:center; background:#0099cc; color:white; padding:10px 0; border-radius:8px; text-decoration:none; font-weight:bold;">
               ← Xem lịch sử đơn hàng
            </a>

            <a href="{{ route('home') }}" 
               style="flex:1; text-align:center; background:#c21b1b; color:white; padding:10px 0; border-radius:8px; text-decoration:none; font-weight:bold;">
               🏠 Về trang chủ
            </a>
        </div>

    </div>
</main>
@endsection
