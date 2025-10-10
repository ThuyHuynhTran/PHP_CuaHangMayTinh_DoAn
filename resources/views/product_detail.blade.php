@extends('layouts.main')

@section('content')
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
    <a href="#"
       id="buy-now-btn"
       style="background-color: #e63946; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; transition: 0.3s;">
       🛒 Mua ngay
    </a>

    <!-- Nút Thêm vào giỏ hàng -->
    <button style="background-color: #0099cc; color: white; padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
        + Thêm vào giỏ hàng
    </button>

    <!-- Nút quay lại -->
    <a href="{{ route('home') }}" style="color: #007bff; text-decoration: none; align-self: center;">
        ← Quay lại trang chủ
    </a>
</div>

    </div>
</div>
@endsection
