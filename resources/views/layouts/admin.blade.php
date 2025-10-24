<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MaiCo Admin - @yield('title', 'Bảng điều khiển')</title>

    {{-- Bootstrap & Font Awesome --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Custom CSS --}}
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background-color: #c82333;
            color: white;
            padding: 25px 0;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.1);
        }
        .sidebar .logo {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 35px;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #f1f1f1;
            padding: 15px 30px;
            text-decoration: none;
            transition: 0.3s;
            border-right: 4px solid transparent;
        }
        .sidebar a.active,
        .sidebar a:hover {
            background-color: #a21b29;
            border-right-color: #ffd700;
            font-weight: bold;
        }
        .sidebar .logout-link {
            margin-top: auto;
        }
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 40px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        {{-- SIDEBAR --}}
        <div class="sidebar">
            <div class="logo"><i class="fas fa-store"></i> MaiCo Admin</div>

            <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Trang quản trị
            </a>
            <a href="{{ route('admin.products.index') }}" class="{{ request()->is('admin/products*') ? 'active' : '' }}">
                <i class="fas fa-box-open"></i> Quản lý sản phẩm
            </a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->is('admin/categories*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Quản lý danh mục
            </a>
            <a href="{{ route('admin.promotions.index') }}" class="{{ request()->is('admin/promotions*') ? 'active' : '' }}">
                <i class="fas fa-percent"></i> Quản lý khuyến mãi
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="{{ request()->is('admin/reviews*') ? 'active' : '' }}">
                <i class="fas fa-star"></i> Quản lý đánh giá
            </a>
            <a href="{{ route('admin.messages.index') }}" class="{{ request()->is('admin/messages*') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i> Quản lý tin nhắn
            </a>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->is('admin/orders*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar"></i> Quản lý đơn hàng
            </a>
                <a href="{{ route('admin.customers.index') }}" class="{{ request()->is('admin/customers*') ? 'active' : '' }}">
        <i class="fas fa-users"></i> Quản lý khách hàng
    </a>

            <a href="{{ route('admin.statistics.index') }}" class="{{ request()->is('admin/faqs*') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i> Thống kê doanh thu
            </a>

            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
               class="logout-link">
               <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="main-content">
            @yield('content')
        </div>
    </div>
</body>
</html>
