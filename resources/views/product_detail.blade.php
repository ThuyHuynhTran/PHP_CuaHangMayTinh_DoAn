@extends('layouts.main')

@section('content')


@if(session('status'))
    <div style="background:#fff2e6; border-left:5px solid #ff9933; padding:10px 15px; margin:10px; border-radius:5px;">
        {{ session('status') }}
    </div>
@endif

<div class="product-detail-container" style="max-width: 1000px; margin: 50px auto; display: flex; gap: 40px; background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    
    <!-- Ảnh sản phẩm -->
    <div class="product-image" style="flex: 1; text-align: center;">
        <img src="{{ asset('assets/img/' . $product->duong_dan) }}" 
             alt="{{ $product->ten_sp }}" 
             style="width: 80%; max-width: 400px; border-radius: 10px;">
    </div>

    <!-- Thông tin chi tiết -->
    <div class="product-info" style="flex: 1;">
        <h1 style="color: #0099cc;">{{ $product->ten_sp }}</h1>
        <p style="font-size: 15px; color: red; font-weight: bold;">
            {{ number_format((float) str_replace(',', '', $product->gia), 0, ',', '.') }} ₫
        </p>
        <p><strong>Thương hiệu:</strong> {{ $product->thuong_hieu }}</p>
        <p><strong>CPU:</strong> {{ $product->vi_xu_ly }}</p>
        <p><strong>RAM:</strong> {{ $product->ram }}</p>
        <p><strong>Lưu trữ:</strong> {{ $product->luu_tru }}</p>
        <p><strong>Màu sắc:</strong> {{ $product->mau_sac }}</p>
        <p><strong>Mô tả:</strong></p>
        <p style="text-align: justify;">{{ $product->mo_ta }}</p>

        <div style="margin-top: 25px; display: flex; gap: 15px; flex-wrap: wrap;">
    <!-- Nút Mua ngay -->
    <a href="{{ route('checkout.now', $product->id) }}"
   style="background-color: #c21b1b; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; transition: 0.3s;">
   🛒 Mua ngay
</a>


    <form action="{{ route('cart.add', $product->id) }}" method="POST">
    @csrf
    <button type="submit"
    style="background-color:#007bbd; color:white; padding:10px 20px; border:none; border-radius:8px; cursor:pointer; transition:0.3s;"
    onmouseover="this.style.backgroundColor='#007bbd';"
    onmouseout="this.style.backgroundColor='#0099cc';">
    🛒 Thêm vào giỏ hàng
</button>

</form>


    <!-- Nút quay lại -->
    <a href="{{ route('home') }}" style="color: #c21b1b; text-decoration: none; align-self: center;">
        ← Quay lại trang chủ
    </a>
</div>

    </div>
</div>
@endsection
