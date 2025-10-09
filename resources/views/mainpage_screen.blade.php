@extends('layouts.main')

@section('content')
<main class="main-content">

    <!-- Giới thiệu -->
    <div class="intro">
        <h1>🖥️ Mai Cồ Shop</h1>
        <p>Laptop, phụ kiện và thiết bị công nghệ chính hãng</p>
    </div>

    <!-- Banner -->
    <div class="intro-banner">
        <div class="banner-content">
            <div class="slides">
                <div class="slide active">
                    <img src="{{ asset('assets/img/banner.jpg') }}" alt="Khuyến mãi 1" class="banner-img">
                    <div class="banner-text">
                        <h2>🔥 Siêu khuyến mãi tháng 10 🔥</h2>
                        <p>Giảm giá đến <strong>50%</strong> cho Laptop & Phụ kiện chính hãng!</p>
                        <a href="#" class="btn-banner">Xem ngay</a>
                    </div>
                </div>

                <div class="slide">
                    <img src="{{ asset('assets/img/banner2.webp') }}" alt="Khuyến mãi 2" class="banner-img">
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

    <!-- Lưới sản phẩm -->
    <div class="product-grid" id="product-container">
        @include('partials.product_list', ['products' => $products])
    </div>

    <!-- Nút Load more & Thu gọn -->
    <div class="load-buttons" style="text-align: center; margin: 30px 0;">
        @if ($products->hasMorePages())
            <button id="load-more-btn"
                    data-next-page="{{ $products->nextPageUrl() }}"
                    style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer;">
                Xem thêm
            </button>
        @endif
        <button id="collapse-btn"
                style="display: none; background-color: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; margin-left: 10px;">
            Thu gọn
        </button>
    </div>

    <!-- ============================
         PHẦN TIN TỨC
    ============================= -->
    <section class="news-section" style="margin: 60px auto; max-width: 1200px;">
        <div class="news-header" 
             style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="font-size: 28px; font-weight: bold;">TIN TỨC</h2>
            <a href="#" id="load-more-news" 
               data-page="2"
               style="color: #007bff; font-weight: bold; text-decoration: none; cursor: pointer;">
                Xem tất cả <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <!-- Danh sách tin tức -->
        <div id="news-container"
             class="news-grid"
             style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            @foreach($news as $item)
                <div class="news-item"
                     style="background: white; border-radius: 10px; overflow: hidden; 
                            box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    <img src="{{ asset('assets/img/news/' . $item['image']) }}" 
                         alt="{{ $item['title'] }}"
                         style="width: 100%; height: 160px; object-fit: cover;">
                    <div style="padding: 15px;">
                        <h3 style="font-size: 16px; line-height: 1.4; color: #333;">
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

<!-- ===============================
     SCRIPT SLIDE BANNER
================================ -->
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

<!-- ===============================
     SCRIPT LOAD MORE + THU GỌN SẢN PHẨM
================================ -->
<script>
let loadedProducts = []; // lưu nhóm sản phẩm đã load thêm

$(document).on('click', '#load-more-btn', function() {
    const button = $(this);
    const nextPage = button.data('next-page');
    const collapseBtn = $('#collapse-btn');

    if (!nextPage) return;

    button.text('Đang tải...');

    // ✅ thêm tham số loadProducts=true để Laravel nhận diện đúng request
    const ajaxUrl = nextPage + (nextPage.includes('?') ? '&' : '?') + 'loadProducts=true';

    $.ajax({
        url: ajaxUrl,
        type: 'get',
        beforeSend: () => button.prop('disabled', true)
    })
    .done(response => {
        const newItems = $('<div class="extra-products"></div>').html(response.html).hide();
        $('#product-container').append(newItems);
        newItems.fadeIn(400);
        loadedProducts.push(newItems);

        button.text('Xem thêm').prop('disabled', false);
        collapseBtn.show();

        if (response.html.trim() === '') {
            button.remove(); // hết sản phẩm
        } else {
            const newUrl = nextPage.replace(/page=\d+/, match => {
                const current = parseInt(match.split('=')[1]);
                return 'page=' + (current + 1);
            });
            button.data('next-page', newUrl);
        }
    })
    .fail(() => button.text('Lỗi tải dữ liệu'));
});

// Thu gọn chỉ ẩn nhóm sản phẩm vừa load thêm
$(document).on('click', '#collapse-btn', function() {
    const collapseBtn = $(this);
    if (loadedProducts.length === 0) return;

    loadedProducts.forEach(group => {
        group.fadeOut(400, () => group.remove());
    });

    loadedProducts = [];

    collapseBtn.hide();
    $('#load-more-btn').show().prop('disabled', false);

    $('html, body').animate({ scrollTop: $('#product-container').offset().top - 100 }, 600);
});
</script>

<!-- ===============================
     SCRIPT LOAD THÊM TIN TỨC
================================ -->
<script>
$(document).on('click', '#load-more-news', function(e) {
    e.preventDefault();
    let btn = $(this);
    let page = btn.data('page');
    btn.html('Đang tải... <i class="fas fa-spinner fa-spin"></i>');

    $.ajax({
        url: "{{ route('home') }}?loadNews=true&page=" + page,
        type: "GET",
        success: function(res) {
            if (res.html.trim() === '') {
                btn.remove(); // Hết tin
            } else {
                $('#news-container').append(res.html);
                btn.data('page', page + 1);
                btn.html('Xem tất cả <i class="fas fa-arrow-right"></i>');
            }
        },
        error: function() {
            btn.text('Lỗi tải dữ liệu');
        }
    });
});
</script>
@endpush
