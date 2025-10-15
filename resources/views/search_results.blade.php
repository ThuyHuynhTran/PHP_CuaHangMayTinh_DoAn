@extends('layouts.main')

@section('content')
<main style="max-width: 1100px; margin: 30px auto; background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 3px 8px rgba(0,0,0,0.1);">

    <h2 style="color:#c21b1b;">🔍 Kết quả tìm kiếm cho: <em>{{ $query }}</em></h2>

    @if($products->isEmpty())
        <p style="color:#777;">Không tìm thấy sản phẩm nào phù hợp.</p>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; margin-top:20px;">
            @foreach($products as $product)
                <div style="border:1px solid #eee; border-radius:10px; padding:10px; text-align:center;">
                    <img src="{{ asset('assets/img/' . $product->duong_dan) }}"
                         alt="{{ $product->ten_sp }}"
                         style="width:60%; height:180px; object-fit:cover; border-radius:8px;">
                    <h4 style="margin:10px 0; color:#0b2c80;">{{ $product->ten_sp }}</h4>
                    <p style="color:#c21b1b; font-weight:bold;">
                       {{ number_format((float) preg_replace('/[^0-9.]/', '', $product->gia), 0, ',', '.') }} ₫

                    </p>
                    <a href="{{ route('product.show', $product->id) }}" 
                       style="background:#0b2c80; color:white; padding:6px 12px; border-radius:6px; text-decoration:none;">
                       Xem chi tiết
                    </a>
                </div>
            @endforeach
        </div>

        <div style="margin-top:20px; text-align:center;">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @endif
</main>
@endsection
