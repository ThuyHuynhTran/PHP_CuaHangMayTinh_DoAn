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
            background: #c21b1b;
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

        .logo .tech {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: wheat;
            font-weight: bold;
            text-decoration: none;
        }

        .menu-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            background: white;
            color: black;
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn:hover {
            background: #1b2cc1;
            color: white;
            transform: scale(1.05);
            transition: 0.2s;
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
            width: 200px;
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

        /* Loader */
        .loader {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #c21b1b;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            animation: spin 1s linear infinite;
            margin: 8px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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

        /* ==== PRICE FILTER POPUP ==== */
        #priceFilterModal {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.4);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .price-filter-content {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            width: 350px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
            animation: fadeIn 0.3s ease;
        }

        .price-filter-content h3 {
            margin-bottom: 15px;
            color: #c21b1b;
        }

        .price-filter-options button {
            background: #f5f5f5;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            margin: 5px;
            cursor: pointer;
        }

        .price-filter-options button:hover {
            background: #c21b1b;
            color: white;
        }

        .price-inputs {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }

        .price-inputs input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .price-actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .price-actions button {
            border: none;
            border-radius: 6px;
            padding: 8px 14px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-cancel {
            background: #ddd;
        }

        .btn-apply {
            background: #c21b1b;
            color: white;
        }
/* --- CĂN CHỈNH CHIỀU CAO NÚT MENU TRÁI --- */
.menu-left .btn,
#brandBtn {
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px 14px;
    line-height: 1;
    white-space: nowrap; /* Giữ chữ không bị xuống hàng */
}

/* Giúp icon và chữ ở giữa hoàn hảo */
.menu-left .btn i {
    font-size: 16px;
    margin-right: 6px;
    display: inline-block;
}

/* Đảm bảo chữ trong nút Thương hiệu thẳng hàng với 2 nút còn lại */
#brandBtn span {
    display: inline-block;
    line-height: 1;
    vertical-align: middle;
}

/* --- CĂN CHỈNH NHÓM ICON PHẢI --- */
.menu-right {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
}

/* 4 icon phải có cùng kích thước */
.menu-right .btn,
.btn-cart,
.btn-user,
.btn-logout {
    width: 45px;
    height: 45px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    padding: 0;
}

/* Icon và avatar đều nhau */
.menu-right .btn i,
.menu-right .btn img {
    font-size: 22px;
    width: 27px;
    height: 26px;
    object-fit: cover;
    border-radius: 50%;
}

/* --- BADGE SỐ TRÒN NHỎ (chuẩn tròn, không bị che) --- */
#notify-count,
#cart-count {
    position: absolute;
    top: -2px; /* đẩy hẳn ra ngoài nút */
    right: -2px; /* đẩy ra khỏi góc trắng */
    background: #ff3333;
    color: white;
    font-size: 11px;
    border-radius: 50%; /* ✅ tròn hoàn toàn */
    width: 11px; /* chiều rộng cố định để tránh méo */
    height: 15px; /* chiều cao cố định */
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    line-height: 1;
    z-index: 20; /* đảm bảo nằm trên icon */
    box-shadow: 0 0 3px rgba(0,0,0,0.25);
}

/* --- ĐẢM BẢO TẤT CẢ NẰM CÙNG HÀNG --- */
.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: nowrap;
    gap: 12px;
    width: 90%;
    margin: auto;
    overflow: visible;
}
/* --- CĂN CHỈNH ICON GIỎ HÀNG GIỮA HOÀN HẢO --- */
.menu-right .btn-cart i {
    transform: translateY(2px); /* đẩy xuống 1px cho cân giữa */
    display: inline-block;
}


        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
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
            <a href="{{ route('home') }}" class="tech">MaiCo Technology</a>
        </div>

        <!-- MENU TRÁI -->
        <div class="menu-left">
            <!-- DANH MỤC -->
            <div class="dropdown">
                <button id="categoryBtn" class="btn dropdown-toggle">
                    <i class="fas fa-th"></i> Danh mục <span>▾</span>
                </button>
                <ul id="category-menu" class="dropdown-menu">
                    <div class="loader"></div>
                </ul>
            </div>

            <!-- NÚT GIÁ (mở popup) -->
            <div class="dropdown">
                <button id="priceFilterBtn" class="btn dropdown-toggle">
                    <i class="fas fa-tags"></i>
                    <span>Giá</span>
                    <span>▾</span>
                </button>
            </div>
        </div>
        <!-- NÚT THƯƠNG HIỆU (mở popup) -->

<!-- NÚT THƯƠNG HIỆU -->
<div class="dropdown">
    <button id="brandBtn" class="btn dropdown-toggle">
        <i class="fas fa-industry"></i> Thương hiệu <span>▾</span>
    </button>
    <ul id="brand-menu" class="dropdown-menu">
        <div class="loader"></div>
    </ul>
</div>




        <!-- THANH TÌM KIẾM -->
        <form action="{{ route('search') }}" method="GET" class="search-box">
            <input type="text" name="q" placeholder="Bạn muốn mua gì hôm nay?" value="{{ request('q') }}">
            <button type="submit" class="search-btn">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <!-- MENU PHẢI -->
         <a href="{{ route('notifications') }}" class="btn btn-cart" title="Thông báo" style="position: relative; ">
  <i class="fas fa-bell"></i>
  <span id="notify-count" style="position:absolute;top:-5px;right:-3px;background:#ff3333;color:#fff;font-size:12px;border-radius:50%;padding:2px 6px;font-weight:bold;display:none;">0</span>
</a>

        <div class="menu-right auth-icons"> <a href="{{ route('cart') }}" class="btn btn-cart" title="Giỏ hàng" style="position: relative;"> <i class="fas fa-shopping-cart"></i>
         <span id="cart-count" style="position:absolute; top:-5px; right:-2px; background:#ff3333; color:#fff; font-size:12px; border-radius:50%; padding:2px 6px; font-weight:bold; display: {{ session('cart_count', 0) > 0 ? 'inline-block' : 'none' }};"> {{ session('cart_count', 0) }} </span> </a> @guest <a href="{{ route('login') }}" class="btn btn-user" title="Đăng nhập"> <i class="fas fa-user-circle"></i> </a> @else <div style="position: relative;"> <button id="userDropdownBtn" class="btn btn-user" title="Tài khoản của tôi"> @if(Auth::user()->avatar) <img id="navUserAvatar" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar"> @else <i class="fas fa-user-circle"></i> @endif </button> <div id="userDropdown" style="display:none; position:absolute; right:0; top:50px; background:white; border:1px solid #ddd; border-radius:10px; width:200px; box-shadow:0 4px 10px rgba(0,0,0,0.15); z-index:999;"> <a href="{{ route('profile.edit') }}" style="display:flex; align-items:center; gap:8px; padding:10px 15px; text-decoration:none; color:#333;"> <i class="fas fa-user"></i> Hồ sơ của tôi </a> <a href="{{ route('orders.myorder') }}" style="display:flex; align-items:center; gap:8px; padding:10px 15px; text-decoration:none; color:#333;"> <i class="fas fa-box"></i> Đơn hàng của tôi </a> <a href="{{ route('wishlist.mywishlist') }}" style="display:flex; align-items:center; gap:8px; padding:10px 15px; text-decoration:none; color:#333;"> <i class="fas fa-heart"></i> Yêu thích </a> </div> </div> <form method="POST" action="{{ route('logout') }}"> @csrf <button type="submit" class="btn btn-logout" title="Đăng xuất"> <i class="fas fa-right-from-bracket"></i> </button> </form> @endguest </div> </div>

</header>

<!-- ✅ POPUP LỌC GIÁ -->
<div id="priceFilterModal">
    <div class="price-filter-content">
        <h3><i class="fas fa-filter"></i> Chọn mức giá</h3>

        <div class="price-filter-options">
            <button data-min="0" data-max="1000000">Dưới 1 triệu</button>
            <button data-min="1000000" data-max="5000000">1 – 5 triệu</button>
            <button data-min="5000000" data-max="10000000">5 – 10 triệu</button>
            <button data-min="10000000" data-max="">Trên 10 triệu</button>
        </div>

        <div class="price-inputs">
            <input type="number" id="priceMin" placeholder="Từ (₫)">
            <input type="number" id="priceMax" placeholder="Đến (₫)">
        </div>

        <div class="price-actions">
            <button class="btn-cancel" id="closePriceModal">Hủy</button>
            <button class="btn-apply" id="applyPriceFilter">Lọc ngay</button>
        </div>
    </div>
</div>

<!-- ========== SCRIPTS ========== -->
<script>
document.addEventListener('DOMContentLoaded', async () => {
    /* ================================
       📂 DANH MỤC
    ================================= */
    const categoryBtn = document.getElementById('categoryBtn');
    const categoryMenu = document.getElementById('category-menu');

    try {
        const res = await fetch('{{ url('/api/categories') }}');
        const data = await res.json();
        categoryMenu.innerHTML = '';

        if (data.success && data.categories.length > 0) {
            data.categories.forEach(cat => {
                const li = document.createElement('li');
                li.textContent = cat.ten_danh_muc;
                li.addEventListener('click', () => window.location.href = `/category/${cat.id}`);
                categoryMenu.appendChild(li);
            });
        } else categoryMenu.innerHTML = '<li>Không có danh mục</li>';
    } catch (error) {
        console.error('Lỗi tải danh mục:', error);
        categoryMenu.innerHTML = '<li>Lỗi tải danh mục</li>';
    }

    categoryBtn.addEventListener('click', e => {
        e.stopPropagation();
        const isVisible = categoryMenu.style.display === 'block';
        document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
        categoryMenu.style.display = isVisible ? 'none' : 'block';
    });


    /* ================================
       🏷️ THƯƠNG HIỆU
    ================================= */
   const brandBtn = document.getElementById('brandBtn');
    const brandMenu = document.getElementById('brand-menu');

    try {
        const res = await fetch('{{ url('/api/brands') }}'); // ✅ Gọi API
        const data = await res.json();
        brandMenu.innerHTML = '';

        if (data.success && data.brands.length > 0) {
            data.brands.forEach(brand => {
                const li = document.createElement('li');
                li.textContent = brand.thuong_hieu;
                li.addEventListener('click', () => {
                    const q = new URLSearchParams(window.location.search).get('q') || '';
                    window.location.href = `/search?q=${encodeURIComponent(q)}&brand=${encodeURIComponent(brand.thuong_hieu)}`;
                });
                brandMenu.appendChild(li);
            });
        } else {
            brandMenu.innerHTML = '<li>Không có thương hiệu</li>';
        }
    } catch (error) {
        console.error('Lỗi tải thương hiệu:', error);
        brandMenu.innerHTML = '<li>Lỗi tải thương hiệu</li>';
    }

    brandBtn.addEventListener('click', e => {
        e.stopPropagation();
        const isVisible = brandMenu.style.display === 'block';
        document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
        brandMenu.style.display = isVisible ? 'none' : 'block';
    });

    document.addEventListener('click', () => {
        brandMenu.style.display = 'none';
        categoryMenu.style.display = 'none';
    });


    /* ================================
       💰 LỌC GIÁ
    ================================= */
    const priceBtn = document.getElementById('priceFilterBtn');
    const priceModal = document.getElementById('priceFilterModal');
    const closeModal = document.getElementById('closePriceModal');
    const applyFilter = document.getElementById('applyPriceFilter');

    priceBtn.addEventListener('click', () => priceModal.style.display = 'flex');
    closeModal.addEventListener('click', () => priceModal.style.display = 'none');
    window.addEventListener('click', e => { if (e.target === priceModal) priceModal.style.display = 'none'; });

    document.querySelectorAll('.price-filter-options button').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('priceMin').value = btn.dataset.min;
            document.getElementById('priceMax').value = btn.dataset.max;
        });
    });

    applyFilter.addEventListener('click', () => {
        const min = document.getElementById('priceMin').value;
        const max = document.getElementById('priceMax').value;
        const q = new URLSearchParams(window.location.search).get('q') || '';
        let url = `/search?q=${encodeURIComponent(q)}&price_min=${min}&price_max=${max}`;
        window.location.href = url;
    });


    /* ================================
       👤 DROPDOWN USER
    ================================= */
    const userBtn = document.getElementById('userDropdownBtn');
    const userMenu = document.getElementById('userDropdown');

    if (userBtn && userMenu) {
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isVisible = userMenu.style.display === 'block';
            document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
            userMenu.style.display = isVisible ? 'none' : 'block';
        });
    }

    // đóng mọi menu khi click ra ngoài
    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
        if (userMenu) userMenu.style.display = 'none';
    });
});
// === Load thông báo ===
async function loadNotifications() {
  try {
    const res = await fetch('/api/notifications');
    const data = await res.json();
    const count = data.notifications.length;
    const notify = document.getElementById('notify-count');
    if (count > 0) {
      notify.textContent = count;
      notify.style.display = 'inline-block';
    }
  } catch (err) {
    console.error('Lỗi tải thông báo', err);
  }
}
loadNotifications();

</script>



</body>
</html>  