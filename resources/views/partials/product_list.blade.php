@foreach ($products as $item)
    <div class="product-card">
        <div class="product-image">
            <img src="{{ asset('assets/img/' . $item->duong_dan) }}"
                 alt="{{ $item->ten_sp }}"
                 style="width: 100%; height: 180px; object-fit: cover; border-radius: 8px;">
        </div>
        <h3>{{ $item->ten_sp }}</h3>
        <p class="price" style="color: red; font-weight: bold;">
            {{ number_format((float) str_replace(',', '', $item->gia), 0, ',', '.') }} ₫
        </p>
       <a href="{{ route('product.show', $item->id) }}" class="btn-detail">Xem chi tiết</a>

    </div>
@endforeach
