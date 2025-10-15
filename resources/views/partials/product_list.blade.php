@foreach ($products as $item)
    <div class="product-card" 
         style="position: relative; border: 1px solid #eee; border-radius: 10px; padding: 10px; text-align: center;">
        
        <!-- 🖼 Ảnh sản phẩm (click được) -->
        <div class="product-image" style="position: relative;">
            <a href="{{ route('product.show', $item->id) }}" style="display: inline-block; position: relative;">
                <img src="{{ asset('assets/img/' . $item->duong_dan) }}"
                     alt="{{ $item->ten_sp }}"
                     style="width: 80%; height: 200px; object-fit: cover; border-radius: 8px;">
            </a>

            <!-- ❤️ Nút yêu thích -->
            @if(Auth::check())
                <!-- Nếu đã đăng nhập -->
                <button 
                    class="wishlist-btn"
                    data-id="{{ $item->id }}"
                    style="position:absolute; top:8px; right:8px; background:white; border:none; border-radius:50%; width:38px; height:38px; 
                           display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 2px 6px rgba(0,0,0,0.15);">
                    <i class="fa-solid fa-heart {{ in_array($item->id, session('wishlist', [])) ? 'active' : '' }}"
                       style="color: {{ in_array($item->id, session('wishlist', [])) ? 'red' : '#ccc' }}; font-size: 18px;"></i>
                </button>
            @else
                <!-- Nếu chưa đăng nhập -->
                <a href="{{ route('login') }}" 
                   title="Đăng nhập để thêm vào yêu thích"
                   style="position:absolute; top:8px; right:8px; background:white; border:none; border-radius:50%; width:38px; height:38px;
                          display:flex; align-items:center; justify-content:center; box-shadow:0 2px 6px rgba(0,0,0,0.15); text-decoration:none;">
                    <i class="fa-solid fa-heart" style="color:#ccc; font-size:18px;"></i>
                </a>
            @endif
        </div>

        <!-- 📝 Tên + giá + chi tiết -->
        <h3>{{ $item->ten_sp }}</h3>
        <p class="price" style="color: red; font-weight: bold;">
            {{ number_format((float) str_replace(',', '', $item->gia), 0, ',', '.') }} ₫
        </p>
        <a href="{{ route('product.show', $item->id) }}" 
           class="btn-detail"
           style="background: #c21b1b; color: white; padding: 8px 15px; border-radius: 6px; text-decoration: none;">
           Xem chi tiết
        </a>
    </div>
@endforeach
