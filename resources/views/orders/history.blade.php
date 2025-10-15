@extends('layouts.main')

@section('content')
<main style="max-width: 1000px; margin: 50px auto; background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
    <h2 style="color:#c21b1b; margin-bottom:20px;">🧾 Lịch sử đơn hàng của bạn</h2>

    {{-- Thông báo sau khi đặt hàng --}}
    @if(session('status'))
        <div style="background:#e6ffed; border-left:5px solid #22bb33; padding:10px 15px; border-radius:5px; margin-bottom:20px; color: blue">
            {{ session('status') }}
        </div>
    @endif

    {{-- Nếu chưa có đơn hàng --}}
    @if($orders->isEmpty())
        <p style="color:#666;">Bạn chưa có đơn hàng nào.</p>
    @else
        {{-- Lặp qua từng đơn --}}
        @foreach($orders as $order)
            <div style="border:1px solid #ddd; border-radius:10px; margin-bottom:25px; padding:20px;">
                
                {{-- Thông tin cơ bản --}}
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <h4 style="margin:0;">🛍️ Mã đơn: 
                        <span style="color:#c21b1b;">#{{ $order->id }}</span>
                    </h4>

                    {{-- Màu sắc trạng thái --}}
                   @php
    $statusColors = [
        'da_huy' => '#990626',      // đỏ đậm
        'tra_hang' => '#990626',    // đỏ đậm
    ];

    // Tất cả các trạng thái khác dùng màu xanh đậm
    $color = $statusColors[$order->status] ?? '#0b2c80';
@endphp


                    <span style="color:{{ $color }}; font-weight:bold;">
                        {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>

                <p style="margin:5px 0; color:#666;">🕒 {{ $order->created_at->format('H:i d/m/Y') }}</p>
                <p style="margin:5px 0;"><strong>Địa chỉ giao hàng:</strong> {{ $order->address }}</p>

                {{-- Danh sách sản phẩm --}}
                <table style="width:100%; margin-top:15px; border-collapse:collapse;">
                    <thead style="background:#f5f5f5;">
                        <tr>
                            <th style="padding:8px; text-align:left;">Sản phẩm</th>
                            <th style="padding:8px; text-align:center;">Số lượng</th>
                            <th style="padding:8px; text-align:right;">Giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td style="padding:8px; border-top:1px solid #eee;">
                                    {{ $item->product->ten_sp ?? 'Sản phẩm đã bị xóa' }}
                                </td>
                                <td style="padding:8px; text-align:center; border-top:1px solid #eee;">
                                    {{ $item->quantity }}
                                </td>
                                <td style="padding:8px; text-align:right; border-top:1px solid #eee;">
                                    {{ number_format($item->price, 0, ',', '.') }}₫
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Tổng tiền --}}
                <hr>
                <div style="text-align:right;">
                    <strong>Tổng: 
                        <span style="color:#c21b1b;">
                            {{ number_format($order->total, 0, ',', '.') }}₫
                        </span>
                    </strong>
                </div>

                {{-- Nút xem chi tiết --}}
                <div style="text-align:right; margin-top:10px;">
                    <a href="{{ route('orders.show', $order->id) }}" 
                       style="background:#0099cc; color:white; padding:6px 15px; border-radius:6px; text-decoration:none; font-weight:600;">
                        Xem chi tiết
                    </a>
                </div>
            </div>
        @endforeach
    @endif

    {{-- Nút quay lại trang chủ --}}
    <div style="text-align:center; margin-top:20px;">
        <a href="{{ route('home') }}" 
           style="background:#c21b1b; color:#fff; padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:bold;">
           ← Quay lại trang chủ
        </a>
    </div>
</main>
@endsection
