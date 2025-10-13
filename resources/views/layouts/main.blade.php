<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Mai Cồ Shop</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    {{-- ========================================================= --}}
    {{-- ✅ 1. THÊM KHỐI HIỂN THỊ THÔNG BÁO NGAY SAU THẺ BODY --}}
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

    {{-- Nội dung chính --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')
    
    @stack('scripts')

    {{-- ========================================================= --}}
    {{-- ✅ 2. THÊM SCRIPT HẸN GIỜ VÀO CUỐI FILE --}}
    {{-- ========================================================= --}}
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const alertBox = document.querySelector('.auto-hide-alert');
        if (alertBox) {
            // Đợi 3 giây
            setTimeout(() => {
                // Bắt đầu hiệu ứng mờ dần
                alertBox.style.transition = 'opacity 0.5s ease';
                alertBox.style.opacity = '0';
                
                // Sau khi hiệu ứng mờ dần kết thúc, ẩn hoàn toàn thẻ div
                setTimeout(() => {
                    alertBox.style.display = 'none';
                }, 500); // 0.5 giây khớp với thời gian transition
                
            }, 3000); // 3000ms = 3 giây
        }
    });
    </script>
</body>
</html>