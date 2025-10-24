@extends('layouts.main')

@section('content')

@if(session('status'))
    <div style="background:#fff2e6; border-left:5px solid #ff9933; padding:10px 15px; margin:10px; border-radius:5px;">
        {{ session('status') }}
    </div>
@endif

@php
    $isOutOfStock = $product->so_luong_kho <= 0;
@endphp

<div class="product-detail-container"
     style="max-width: 1000px; margin: 50px auto; display: flex; flex-wrap: wrap; gap: 40px; background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); {{ $isOutOfStock ? 'opacity:0.6;' : '' }}">

    {{-- Ảnh sản phẩm --}}
    <div class="product-image" style="flex: 1; min-width: 300px; text-align: center; position: relative;">
        <img src="{{ asset('assets/img/' . $product->duong_dan) }}"
             alt="{{ $product->ten_sp }}"
             style="width: 80%; max-width: 400px; border-radius: 10px;">

        {{-- ❤️ Nút yêu thích --}}
        @if(Auth::check())
            <button 
                class="wishlist-btn"
                data-id="{{ $product->id }}"
                style="position:absolute; top:10px; right:10px; background:white; border:none; border-radius:50%; width:45px; height:45px; 
                       display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 2px 6px rgba(0,0,0,0.2);">
                <i class="fa-solid fa-heart"
                   style="color: {{ in_array($product->id, session('wishlist', [])) ? 'red' : '#ccc' }}; font-size:20px;"></i>
            </button>
        @else
            <a href="{{ route('login') }}"
               title="Đăng nhập để thêm vào yêu thích"
               style="position:absolute; top:10px; right:10px; background:white; border:none; border-radius:50%; width:45px; height:45px; 
                      display:flex; align-items:center; justify-content:center; box-shadow:0 2px 6px rgba(0,0,0,0.2); text-decoration:none;">
                <i class="fa-solid fa-heart" style="color:#ccc; font-size:20px;"></i>
            </a>
        @endif
    </div>

    {{-- Thông tin sản phẩm --}}
    <div class="product-info" style="flex: 1; min-width: 300px;">
        <h1 style="color: #0099cc;">{{ $product->ten_sp }}</h1>
        <p style="font-size: 15px; color: red; font-weight: bold;">
            {{ number_format((float) str_replace(',', '', $product->gia), 0, ',', '.') }} ₫
        </p>
        <p><strong>Thương hiệu:</strong> {{ $product->thuong_hieu }}</p>
        <p><strong>CPU:</strong> {{ $product->vi_xu_ly }}</p>
        <p><strong>RAM:</strong> {{ $product->ram }}</p>
        <p><strong>Lưu trữ:</strong> {{ $product->luu_tru }}</p>
        <p><strong>Màu sắc:</strong> {{ $product->mau_sac }}</p>
            <p>
        <strong>Tồn kho:</strong>
        @if($product->so_luong_kho > 0)
            <span style="color: green; font-weight: bold;">Còn {{ $product->so_luong_kho }} sản phẩm</span>
        @else
            <span style="color: red; font-weight: bold;">Hết hàng</span>
        @endif
    </p>

        <p><strong>Mô tả:</strong></p>
        <p style="text-align: justify;">{{ $product->mo_ta }}</p>

        {{-- Nút hành động --}}
        <div style="margin-top: 25px; display: flex; gap: 15px; flex-wrap: wrap;">
            @if(!$isOutOfStock)
                <a href="{{ route('checkout.now', $product->id) }}" class="btn-standard btn-red">
                    Mua ngay
                </a>

                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-standard btn-blue">
                        Thêm vào giỏ hàng
                    </button>
                </form>
            @else
                <button class="btn-standard btn-gray" disabled style="cursor:not-allowed; opacity:0.6;">
                    Hết hàng
                </button>
            @endif

            <a href="{{ route('home') }}" class="btn-standard btn-gray">
                ← Quay lại trang chủ
            </a>
        </div>

        {{-- Đánh giá sản phẩm --}}
        @if($product->reviews->count() > 0)
            <div style="margin-top: 30px;">
                <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:2px solid #f1f1f1; padding-bottom:10px; margin-bottom:15px;">
                    <h3 style="margin:0;">Đánh giá sản phẩm</h3>
                    <span id="showAllReviews" style="color:#0b2c80; cursor:pointer; font-weight:600;">Xem tất cả đánh giá &gt;&gt;</span>
                </div>

                {{-- Hiển thị 1 đánh giá đầu tiên --}}
                @php $firstReview = $product->reviews->first(); @endphp
                @if($firstReview)
                    <div style="border:1px solid #eee; padding:15px; border-radius:8px; margin-bottom:15px;">
                        <strong>{{ $firstReview->user->name }}</strong>
                        <p style="margin: 5px 0;">⭐ {{ $firstReview->rating }} / 5</p>
                        <p style="margin: 5px 0;">{{ $firstReview->comment }}</p>

                        {{-- Ảnh --}}
                        @if($firstReview->images && $firstReview->images->count())
                            <div style="display:flex; gap:8px; margin-top:10px;">
                                @foreach($firstReview->images as $img)
                                    <img src="{{ asset('storage/' . $img->path) }}" 
                                         alt="Review Image"
                                         style="width:70px; height:70px; border-radius:6px; object-fit:cover; border:1px solid #ccc;">
                                @endforeach
                            </div>
                        @endif

                        {{-- Like button nhỏ --}}
                        <div style="margin-top:8px; display:flex; align-items:center; gap:6px;">
                            <button class="like-btn" data-id="{{ $firstReview->id }}" style="border:none; background:none; cursor:pointer; color:#0b2c80; font-size:15px;">
                                👍
                            </button>
                            <span id="like-count-{{ $firstReview->id }}">{{ $firstReview->likes_count ?? 0 }}</span> lượt thích
                        </div>

                        {{-- Phản hồi --}}
                        @if($firstReview->admin_reply)
                            <div style="background-color: #f8f9fa; border-left: 3px solid #0099cc; padding: 10px 15px; margin-top: 15px; margin-left: 20px; border-radius: 5px;">
                                <p style="margin: 0; font-weight: bold; color: #0099cc;">Phản hồi từ Cửa hàng:</p>
                                <p style="margin: 5px 0 0 0; color: #333;">{{ $firstReview->admin_reply }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @else
            <p style="color:#777; font-style:italic; margin-top:30px;">Chưa có đánh giá nào cho sản phẩm này.</p>
        @endif
    </div>
</div>

{{-- === POPUP HIỂN THỊ TẤT CẢ ĐÁNH GIÁ === --}}
@if($product->reviews->count() > 0)
<div id="allReviewsModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.4); align-items:center; justify-content:center; z-index:1000;">
    <div style="background:white; width:80%; max-width:800px; max-height:80vh; overflow-y:auto; padding:20px; border-radius:10px; position:relative;">
        <h3 style="margin-bottom:20px; color:#0b2c80;">Tất cả đánh giá sản phẩm</h3>

        <button id="closeAllReviews" style="position:absolute; top:10px; right:15px; background:none; border:none; font-size:22px; cursor:pointer; color:#777;">&times;</button>

        @foreach($product->reviews as $review)
            <div style="border:1px solid #eee; padding:15px; border-radius:8px; margin-bottom:15px;">
                <strong>{{ $review->user->name }}</strong>
                <p style="margin: 5px 0;">⭐ {{ $review->rating }} / 5</p>
                <p style="margin: 5px 0;">{{ $review->comment }}</p>

                @if($review->images && $review->images->count())
                    <div style="display:flex; gap:8px; margin-top:10px; flex-wrap:wrap;">
                        @foreach($review->images as $img)
                            <img src="{{ asset('storage/' . $img->path) }}" 
                                 alt="Review Image"
                                 style="width:70px; height:70px; border-radius:6px; object-fit:cover; border:1px solid #ccc;">
                        @endforeach
                    </div>
                @endif

                {{-- Like button --}}
                <div style="margin-top:8px; display:flex; align-items:center; gap:6px;">
                    <button class="like-btn" data-id="{{ $review->id }}" style="border:none; background:none; cursor:pointer; color:#0b2c80; font-size:15px;">
                        👍
                    </button>
                    <span id="like-count-{{ $review->id }}">{{ $review->likes_count ?? 0 }}</span> lượt thích
                </div>

                @if($review->admin_reply)
                    <div style="background-color: #f8f9fa; border-left: 3px solid #0099cc; padding: 10px 15px; margin-top: 15px; margin-left: 20px; border-radius: 5px;">
                        <p style="margin: 0; font-weight: bold; color: #0099cc;">Phản hồi từ Cửa hàng:</p>
                        <p style="margin: 5px 0 0 0; color: #333;">{{ $review->admin_reply }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Wishlist logic (giữ nguyên)
    const wishlistBtn = document.querySelector('.wishlist-btn');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function () {
            const productId = this.dataset.id;
            const heartIcon = this.querySelector('i');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('wishlist.toggle') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(r => r.json())
            .then(data => {
                const toast = document.createElement('div');
                toast.innerText = data.message;
                toast.style.position = 'fixed';
                toast.style.top = '20px';
                toast.style.left = '50%';
                toast.style.transform = 'translateX(-50%)';
                toast.style.background = data.status === 'added' ? '#d4edda' : '#f8d7da';
                toast.style.color = data.status === 'added' ? '#155724' : '#721c24';
                toast.style.padding = '15px 25px';
                toast.style.borderRadius = '8px';
                toast.style.zIndex = '10000';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
                heartIcon.style.color = (data.status === 'added') ? 'red' : '#ccc';
            });
        });
    }

    // Popup xem tất cả đánh giá
    const showAll = document.getElementById('showAllReviews');
    const modal = document.getElementById('allReviewsModal');
    const closeAll = document.getElementById('closeAllReviews');
    if(showAll && modal && closeAll){
        showAll.addEventListener('click', ()=> modal.style.display = 'flex');
        closeAll.addEventListener('click', ()=> modal.style.display = 'none');
        window.addEventListener('click', e=>{ if(e.target===modal) modal.style.display='none'; });
    }

    // Like button AJAX
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(`/reviews/${id}/like`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            })
            .then(r=>r.json())
            .then(data=>{
                document.getElementById(`like-count-${id}`).textContent = data.likes;
            })
            .catch(()=>alert('Lỗi khi like.'));
        });
    });
});
</script>
@endpush

<style>
.btn-standard {
    width: auto;
    min-width: 130px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 15px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.25s ease;
    background-color: #fff;
    box-sizing: border-box;
    padding: 0 16px;
    white-space: nowrap;
    cursor: pointer;
    border: 1.2px solid transparent;
}
.btn-blue { border-color: #0b2c80; color: #0b2c80; }
.btn-blue:hover { background-color: #0b2c80; color: #fff; }
.btn-red { border-color: #c21b1b; color: #c21b1b; }
.btn-red:hover { background-color: #c21b1b; color: #fff; }
.btn-gray { border-color: #777; color: #777; }
.btn-gray:hover { background-color: #777; color: #fff; }
.btn-standard:active { transform: scale(0.97); }
</style>
