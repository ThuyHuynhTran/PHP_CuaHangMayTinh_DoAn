@extends('layouts.main')

@section('content')
<main class="main-content">

    <!-- Giới thiệu -->
    <div class="intro">
        <h1>🖥️MaiCo Technology</h1>
        <p>Laptop, phụ kiện và thiết bị công nghệ chính hãng</p>
    </div>

    <!-- Banner -->
    <div class="intro-banner">
        <div class="banner-content">
            <div class="slides">
                <div class="slide active">
                    <img src="{{ asset('assets/img/cbanner.jpg') }}" alt="Khuyến mãi 1" class="banner-img">
                    <div class="banner-text">
                        <h2>🔥 Siêu khuyến mãi tháng 10 🔥</h2>
                        <p>Giảm giá đến <strong>50%</strong> cho Laptop & Phụ kiện chính hãng!</p>
                        <a href="#" class="btn-banner">Xem ngay</a>
                    </div>
                </div>

                <div class="slide">
                    <img src="{{ asset('assets/img/cbanner2.webp') }}" alt="Khuyến mãi 2" class="banner-img">
                    <div class="banner-text">
                        <h2>🎉 Ưu đãi lớn cuối năm 🎉</h2>
                        <p>Giảm <strong>20%</strong> cho phụ kiện & gaming gear chính hãng!</p>
                        <a href="#" class="btn-banner">Mua ngay</a>
                    </div>
                </div>

                <div class="slide">
                    <img src="{{ asset('assets/img/cbanner3.webp') }}" alt="Khuyến mãi 3" class="banner-img">
                    <div class="banner-text">
                        <h2>🎉 Ưu đãi lớn cuối năm 🎉</h2>
                        <p>Giảm <strong>20%</strong> cho phụ kiện & gaming gear chính hãng!</p>
                        <a href="#" class="btn-banner">Mua ngay</a>
                    </div>
                </div>
            </div>

            <button class="prev-btn"><i class="fas fa-chevron-left"></i></button>
            <button class="next-btn"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>

    <!-- ==============================
         HIỂN THỊ SẢN PHẨM THEO DANH MỤC
    ============================== -->
    @foreach($categories as $category)
    <section style="max-width:1200px; margin:40px auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="font-size:24px; font-weight:bold; color:#0b2c80;">🛒 {{ $category->ten_danh_muc }}</h2>
            <a href="{{ route('category.show', $category->id) }}" 
               style="color:#c21b1b; font-weight:bold; text-decoration:none;">Xem tất cả <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="product-grid"
             style="display:grid; grid-template-columns:repeat(auto-fill, minmax(220px, 1fr)); gap:20px;">
            @forelse($category->products->take(8) as $item)
                <div class="product-card" 
                     style="position:relative; border:1px solid #eee; border-radius:10px; padding:10px; text-align:center; background:white;">

                    <!-- Ảnh sản phẩm -->
                    <a href="{{ route('product.show', $item->id) }}">
                        <img src="{{ asset('assets/img/' . $item->duong_dan) }}" 
                             alt="{{ $item->ten_sp }}" 
                             style="width:70%; height:180px; object-fit:cover; border-radius:8px;">
                    </a>

                    <!-- ❤️ Nút yêu thích -->
                    <button 
                        class="wishlist-btn"
                        data-id="{{ $item->id }}"
                        style="position:absolute; top:8px; right:8px; background:white; border:none; border-radius:50%; width:38px; height:38px; 
                               display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 2px 6px rgba(0,0,0,0.15);">
                        <i class="fa-solid fa-heart {{ in_array($item->id, session('wishlist', [])) ? 'active' : '' }}"
                           style="color: {{ in_array($item->id, session('wishlist', [])) ? 'red' : '#ccc' }}; font-size:18px;"></i>
                    </button>

                    <h4 style="margin:10px 0; color:#0b2c80;">{{ $item->ten_sp }}</h4>
                    <p style="color:#c21b1b; font-weight:bold;">
                        {{ number_format((float) str_replace(',', '', $item->gia), 0, ',', '.') }} ₫
                    </p>
                    <a href="{{ route('product.show', $item->id) }}" 
                       style="background:#c21b1b; color:white; padding:6px 12px; border-radius:6px; text-decoration:none;">
                       Xem chi tiết
                    </a>
                </div>
            @empty
                <p style="color:#777;">Chưa có sản phẩm nào trong danh mục này.</p>
            @endforelse
        </div>
    </section>
    @endforeach


    <!-- ============================
         PHẦN TIN TỨC
    ============================= -->
    <section class="news-section" style="margin: 60px auto; max-width: 1200px;">
        <div class="news-header" 
             style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="font-size:28px; font-weight:bold;">TIN TỨC</h2>
            <a href="#" id="load-more-news" 
               data-page="2"
               style="color:#007bff; font-weight:bold; text-decoration:none; cursor:pointer;">
                Xem tất cả <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div id="news-container"
             class="news-grid"
             style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:20px;">
            @foreach($news as $item)
                <div class="news-item"
                     style="background:white; border-radius:10px; overflow:hidden; 
                            box-shadow:0 4px 15px rgba(0,0,0,0.1); transition:transform 0.3s;">
                    <img src="{{ asset('assets/img/news/' . $item['image']) }}" 
                         alt="{{ $item['title'] }}"
                         style="width:100%; height:160px; object-fit:cover;">
                    <div style="padding:15px;">
                        <h3 style="font-size:16px; line-height:1.4; color:#333;">
                            {{ $item['title'] }}
                        </h3>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

</main>
@endsection


@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- ❤️ SCRIPT YÊU THÍCH -->
<script>
$(document).on('click', '.wishlist-btn', function(e) {
    e.preventDefault();
    const productId = $(this).data('id');
    const icon = $(this).find('i');

    @if(!Auth::check())
        // nếu chưa đăng nhập
        window.location.href = "{{ route('login') }}";
    @else
        $.ajax({
            url: "{{ route('wishlist.toggle') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: productId
            },
            success: function(res) {
                if (res.status === 'added') {
                    icon.css('color', 'red');
                } else {
                    icon.css('color', '#ccc');
                }
            }
        });
    @endif
});
</script>

<!-- 🎞️ SCRIPT SLIDE BANNER -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const slides = document.querySelectorAll('.slide');
  const nextBtn = document.querySelector('.next-btn');
  const prevBtn = document.querySelector('.prev-btn');
  let current = 0;

  const showSlide = index => {
    slides.forEach(slide => slide.classList.remove('active'));
    slides[index].classList.add('active');
  };

  const nextSlide = () => {
    current = (current + 1) % slides.length;
    showSlide(current);
  };

  const prevSlide = () => {
    current = (current - 1 + slides.length) % slides.length;
    showSlide(current);
  };

  nextBtn?.addEventListener('click', nextSlide);
  prevBtn?.addEventListener('click', prevSlide);
  setInterval(nextSlide, 5000);
});
</script>
@endpush
