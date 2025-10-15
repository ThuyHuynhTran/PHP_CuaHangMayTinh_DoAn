@extends('layouts.main')

@section('content')

@if(session('status'))
    <div style="background:#fff2e6; border-left:5px solid #ff9933; padding:10px 15px; margin:10px; border-radius:5px;">
        {{ session('status') }}
    </div>
@endif

<div class="product-detail-container"
     style="max-width: 1000px; margin: 50px auto; display: flex; gap: 40px; background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">

    {{-- Ảnh sản phẩm --}}
    <div class="product-image" style="flex: 1; text-align: center; position: relative;">
        <img src="{{ asset('assets/img/' . $product->duong_dan) }}"
             alt="{{ $product->ten_sp }}"
             style="width: 80%; max-width: 400px; border-radius: 10px;">

        {{-- ❤️ Nút yêu thích trong trang chi tiết --}}
        @if(Auth::check())
            <!-- Nếu đã đăng nhập -->
            <button 
                class="wishlist-btn"
                data-id="{{ $product->id }}"
                style="position:absolute; top:10px; right:10px; background:white; border:none; border-radius:50%; width:45px; height:45px; 
                       display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 2px 6px rgba(0,0,0,0.2);">
                <i class="fa-solid fa-heart {{ in_array($product->id, session('wishlist', [])) ? 'active' : '' }}"
                   style="color: {{ in_array($product->id, session('wishlist', [])) ? 'red' : '#ccc' }}; font-size:20px;"></i>
            </button>
        @else
            <!-- Nếu chưa đăng nhập -->
            <a href="{{ route('login') }}"
               title="Đăng nhập để thêm vào yêu thích"
               style="position:absolute; top:10px; right:10px; background:white; border:none; border-radius:50%; width:45px; height:45px; 
                      display:flex; align-items:center; justify-content:center; box-shadow:0 2px 6px rgba(0,0,0,0.2); text-decoration:none;">
                <i class="fa-solid fa-heart" style="color:#ccc; font-size:20px;"></i>
            </a>
        @endif
    </div>

    {{-- Thông tin sản phẩm --}}
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
            {{-- Nút Mua ngay --}}
            <a href="{{ route('checkout.now', $product->id) }}"
               style="background-color: #c21b1b; color: white; padding: 8px 19px; border-radius: 8px; text-decoration: none; font-weight: bold; transition: 0.3s;">
               🛒 Mua ngay
            </a>

            {{-- Nút Thêm vào giỏ --}}
            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                @csrf
                <button type="submit"
                        style="background-color:#150782; color:white; padding:10px 20px; border:none; border-radius:8px; cursor:pointer; transition:0.3s;">
                    🛒 Thêm vào giỏ hàng
                </button>
            </form>

            {{-- Nút quay lại --}}
            <a href="{{ route('home') }}" style="color: #c21b1b; text-decoration: none; align-self: center;">
                ← Quay lại trang chủ
            </a>
        </div>

        {{-- DANH SÁCH ĐÁNH GIÁ --}}
        @foreach($product->reviews as $review)
            <div style="border:1px solid #eee; padding:10px; border-radius:8px; margin-bottom:10px;">
                <strong>{{ $review->user->name }}</strong>
                <p>⭐ {{ $review->rating }} / 5</p>
                <p>{{ $review->comment }}</p>

                @if($review->images && $review->images->count())
                    <div style="display:flex; gap:8px; margin-top:5px;">
                        @foreach($review->images as $img)
                            <img src="{{ asset('storage/' . $img->path) }}" 
                                 alt="Review Image"
                                 style="width:70px; height:70px; border-radius:6px; object-fit:cover; border:1px solid #ccc;">
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

        @if($product->reviews->isEmpty())
            <p style="color:#777; font-style:italic;">Chưa có đánh giá nào cho sản phẩm này.</p>
        @endif
    </div>
</div>

@endsection
