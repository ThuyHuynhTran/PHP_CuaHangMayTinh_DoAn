@extends('layouts.main')

@section('content')
<main style="max-width: 1100px; margin: 30px auto; background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 3px 8px rgba(0,0,0,0.1);">

    <h2 style="color:#c21b1b; margin-bottom:20px;">
        🛍️ Danh mục: {{ $category->ten_danh_muc }}
    </h2>

    @if($products->isEmpty())
        <p style="color:#777;">Không có sản phẩm nào trong danh mục này.</p>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
            @foreach($products as $product)
                <div style="border:1px solid #ddd; border-radius:10px; padding:10px; text-align:center; transition: all 0.2s ease;">
                    <!-- Ảnh sản phẩm -->
                    <img src="{{ asset('assets/img/' . ($product->duong_dan ?? 'default.jpg')) }}" 
                         alt="{{ $product->ten_sp ?? 'Sản phẩm' }}" 
                         style="width:100%; height:180px; object-fit:cover; border-radius:8px;">

                    <!-- Tên sản phẩm -->
                    <h4 style="margin:10px 0; color:#0b2c80;">{{ $product->ten_sp }}</h4>

                    <!-- Giá sản phẩm -->
                    <p style="color:#c21b1b; font-weight:bold;">
                        {{ number_format((float)$product->gia, 0, ',', '.') }}₫
                    </p>

                    <!-- Nút xem chi tiết -->
                    <a href="{{ route('product.detail', $product->id) }}" 
                       style="display:inline-block; margin-top:6px; background:#0b2c80; color:#fff; padding:6px 12px; border-radius:6px; text-decoration:none;">
                       Xem chi tiết
                    </a>
                </div>
            @endforeach
        </div>

        <!-- 🔹 PHÂN TRANG -->
        <div style="margin-top:25px; text-align:center;">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @endif
</main>
@endsection
