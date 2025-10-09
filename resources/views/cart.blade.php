@extends('layouts.main')

@section('content')
<main class="main-content" style="background-color: #eaf8ff; min-height: 100vh; padding: 40px 0;">

    <div class="container" style="max-width: 1000px; margin: auto; background: #fff; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); padding: 30px;">
        <h2 style="text-align: center; color: #0047ab; margin-bottom: 30px; font-weight: bold;">
            <i class="fas fa-shopping-cart"></i> Giỏ hàng của bạn
        </h2>

        <!-- Nếu giỏ hàng rỗng -->
        @if(empty($cart) || count($cart) === 0)
            <div style="text-align: center; color: #777; padding: 60px 0;">
                <i class="fas fa-box-open" style="font-size: 60px; color: #9de2ff;"></i>
                <p style="margin-top: 20px; font-size: 18px;">Giỏ hàng của bạn hiện đang trống.</p>
                <a href="{{ route('home') }}" style="margin-top: 20px; display: inline-block; background-color: #2d3ee0; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;">
                    Tiếp tục mua sắm
                </a>
            </div>
        @else
        <!-- Nếu có sản phẩm -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead style="background-color: #9de2ff;">
                <tr>
                    <th style="padding: 12px; text-align: left;">Sản phẩm</th>
                    <th style="padding: 12px;">Giá</th>
                    <th style="padding: 12px;">Số lượng</th>
                    <th style="padding: 12px;">Tổng</th>
                    <th style="padding: 12px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $item)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px; display: flex; align-items: center; gap: 10px;">
                        <img src="{{ asset('assets/img/products/' . $item['image']) }}" alt="{{ $item['name'] }}" style="width: 60px; height: 60px; border-radius: 8px; object-fit: cover;">
                        <span>{{ $item['name'] }}</span>
                    </td>
                    <td style="text-align: center;">{{ number_format($item['price']) }}₫</td>
                    <td style="text-align: center;">
                        <input type="number" min="1" value="{{ $item['quantity'] }}" style="width: 60px; text-align: center; border-radius: 6px; border: 1px solid #ccc; padding: 5px;">
                    </td>
                    <td style="text-align: center; color: #0047ab; font-weight: bold;">
                        {{ number_format($item['price'] * $item['quantity']) }}₫
                    </td>
                    <td style="text-align: center;">
                        <button style="background: none; border: none; color: red; cursor: pointer;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Tổng cộng -->
        <div style="text-align: right; margin-top: 20px;">
            <p style="font-size: 18px;">Tổng cộng:
                <strong style="color: #0047ab;">{{ number_format($total) }}₫</strong>
            </p>
            <button style="background: #2d3ee0; color: white; border: none; padding: 12px 25px; border-radius: 10px; cursor: pointer; font-size: 16px;">
                Thanh toán ngay
            </button>
        </div>
        @endif
    </div>

</main>
@endsection
