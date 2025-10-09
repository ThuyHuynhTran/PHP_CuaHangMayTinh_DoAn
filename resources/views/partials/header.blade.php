<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Mai Cồ Shop - Cửa hàng máy tính</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* ==== HEADER ==== */
        .header {
            background: #9de2ff;
            padding: 10px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .header-container {
            width: 90%;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        /* ==== LOGO ==== */
        .logo .tech {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #0047ab;
            font-weight: bold;
            text-decoration: none;
        }

        /* ==== MENU TRÁI ==== */
        .menu-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            background: #2d3ee0;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn:hover {
            background: #1b2cc1;
            transform: scale(1.05);
            transition: 0.2s;
        }

        /* ==== SEARCH BOX ==== */
        .search-box {
            position: relative;
            display: flex;
            align-items: center;
            background: white;
            border-radius: 25px;
            overflow: hidden;
            width: 320px;
        }

        .search-box input {
            border: none;
            outline: none;
            padding: 10px 45px 10px 15px;
            width: 100%;
            font-size: 15px;
            border-radius: 25px;
        }

        .search-box .search-btn {
            position: absolute;
            right: 10px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            color: #333;
        }

        .search-box .search-btn:hover {
            transform: scale(1.15);
            color: #007bff;
        }

        /* ==== MENU PHẢI ==== */
        .menu-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ==== DROPDOWN ==== */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 110%;
            left: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            list-style: none;
            padding: 5px 0;
            margin: 0;
            width: 180px;
            z-index: 1000;
        }

        .dropdown-menu li {
            padding: 10px 15px;
            cursor: pointer;
        }

        .dropdown-menu li:hover {
            background: #f1f1f1;
            color: #007bff;
        }

        /* ==== RESPONSIVE ==== */
        @media (max-width: 900px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .search-box {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<header class="header">
    <div class="header-container">

        <!-- LOGO -->
        <div class="logo">
            <a href="{{ route('home') }}" class="tech">Mai Cồ Shop</a>
        </div>

        <!-- MENU TRÁI -->
        <div class="menu-left">
            <!-- DANH MỤC -->
            <div class="dropdown">
                <button class="btn dropdown-toggle">
                    <i class="fas fa-th"></i> Danh mục <span>▾</span>
                </button>
                <ul class="dropdown-menu">
                    <li>Laptop</li>
                    <li>Phụ kiện</li>
                    <li>Gaming Gear</li>
                    <li>PC - Màn hình</li>
                </ul>
            </div>

            <!-- KHU VỰC -->
            <div class="dropdown">
                <button class="btn dropdown-toggle">
                    <i class="fas fa-map-marker-alt"></i>
                    <span id="current-location">Hồ Chí Minh</span>
                    <span>▾</span>
                </button>
                <ul class="dropdown-menu location-menu">
                    <li>Hồ Chí Minh</li>
                    <li>Hà Nội</li>
                    <li>Đà Nẵng</li>
                    <li>Cần Thơ</li>
                    <li>Khác...</li>
                </ul>
            </div>
        </div>

        <!-- THANH TÌM KIẾM -->
        <div class="search-box">
            <input type="text" placeholder="Bạn muốn mua gì hôm nay?">
            <button class="search-btn"><i class="fas fa-search"></i></button>
        </div>

        <!-- MENU PHẢI -->
        <div class="menu-right auth-icons">
            <a href="{{ route('cart') }}" class="btn" title="Giỏ hàng">
    <i class="fas fa-shopping-cart"></i>
</a>

            @guest
                <a href="{{ route('login') }}" class="btn" title="Đăng nhập"><i class="fas fa-user"></i></a>
            @else
                <span style="color:white;"><i class="fas fa-user-circle"></i></span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn"><i class="fas fa-right-from-bracket"></i></button>
                </form>
            @endguest
        </div>

    </div>
</header>

<!-- ✅ Script xử lý dropdown -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    const dropdownMenus = document.querySelectorAll('.dropdown-menu');
    const currentLocation = document.getElementById('current-location');

    // Ẩn tất cả dropdowns
    function closeAll() {
        dropdownMenus.forEach(menu => menu.style.display = 'none');
    }

    // Toggle mở/đóng dropdown
    dropdownToggles.forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            const menu = button.nextElementSibling;

            // Đóng tất cả trước
            closeAll();

            // Mở menu nếu chưa hiển thị
            if (menu && menu.style.display !== 'block') {
                menu.style.display = 'block';
            }
        });
    });

    // Click chọn khu vực
    document.querySelectorAll('.location-menu li').forEach(item => {
        item.addEventListener('click', () => {
            currentLocation.textContent = item.textContent;
            closeAll();
        });
    });

    // Click ra ngoài → đóng hết
    document.addEventListener('click', () => closeAll());
});
</script>

</body>
</html>
