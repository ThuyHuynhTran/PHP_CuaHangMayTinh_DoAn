<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Mai Cồ Shop</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> {{-- Thêm thư viện jQuery --}}
</head>
<body>
    {{-- ========================================================= --}}
    {{-- ✅ 1. THÔNG BÁO FLASH --}}
    {{-- ========================================================= --}}
    @if(session('success'))
        <div class="auto-hide-alert" 
             style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); 
                    background: #d4edda; color: #155724; padding: 15px 25px; 
                    border-radius: 8px; z-index: 9999; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="auto-hide-alert"
             style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%);
                    background: #f8d7da; color: #721c24; padding: 15px 25px;
                    border-radius: 8px; z-index: 9999; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    @include('partials.header')

    {{-- Nội dung --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Stack cho script của từng trang --}}
    @stack('scripts')

    {{-- ========================================================= --}}
    {{-- ✅ 2. AUTO ẨN ALERT SAU 3S --}}
    {{-- ========================================================= --}}
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const alertBox = document.querySelector('.auto-hide-alert');
        if (alertBox) {
            setTimeout(() => {
                alertBox.style.transition = 'opacity 0.5s ease';
                alertBox.style.opacity = '0';
                setTimeout(() => { alertBox.style.display = 'none'; }, 500);
            }, 3000);
        }
    });
    </script>

    {{-- ========================================================= --}}
    {{-- ✅ 3. SCRIPT XỬ LÝ ❤️ YÊU THÍCH (hiển thị thông báo kiểu giỏ hàng) --}}
    {{-- ========================================================= --}}
    <script>
    $(document).on('click', '.wishlist-btn', function(e) {
        e.preventDefault();
        let btn = $(this);
        let productId = btn.data('id');
        let icon = btn.find('i');

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
            showToast('Đã thêm vào danh sách yêu thích ❤️');
        } else if (res.status === 'removed') {
            icon.css('color', '#ccc');
            showToast('Đã xóa khỏi danh sách yêu thích 💔');
            
            // ✅ Nếu đang ở trang wishlist, tự reload lại danh sách
            if (window.location.href.includes("wishlist")) {
                setTimeout(() => {
                    location.reload();
                }, 1200); // đợi hiệu ứng toast hiện xong
            }
        }
    },
    error: function() {
        alert('Lỗi! Vui lòng đăng nhập để sử dụng tính năng này.');
    }
});

    });

    // ✅ Hiển thị thông báo kiểu giỏ hàng (trên cùng giữa màn hình)
    function showWishlistAlert(message, bgColor = '#d4edda', textColor = '#155724') {
        // Nếu đã có alert cũ thì xóa trước
        $('.wishlist-alert').remove();

        const alertBox = $('<div></div>')
            .addClass('wishlist-alert')
            .text(message)
            .css({
                position: 'fixed',
                top: '20px',
                left: '50%',
                transform: 'translateX(-50%)',
                background: bgColor,
                color: textColor,
                padding: '14px 28px',
                borderRadius: '8px',
                zIndex: 9999,
                boxShadow: '0 4px 10px rgba(0,0,0,0.15)',
                fontWeight: '500',
                opacity: 0
            });

        $('body').append(alertBox);
        alertBox.animate({opacity: 1, top: '40px'}, 300);

        // Tự động ẩn sau 3 giây
        setTimeout(() => {
            alertBox.animate({opacity: 0, top: '20px'}, 400, () => alertBox.remove());
        }, 3000);
    }
    </script>

</body>
</html>
