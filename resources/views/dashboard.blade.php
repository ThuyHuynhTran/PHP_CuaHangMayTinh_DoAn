@extends('layouts.main')

@section('content')
<style>
    /* ===== ADMIN DASHBOARD STYLES ===== */
    body {
        background: linear-gradient(180deg, #e6f9ff, #ffffff);
        font-family: 'Segoe UI', sans-serif;
    }

    .admin-container {
        display: flex;
        min-height: 90vh;
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
        width: 250px;
        background: linear-gradient(180deg, #0099cc, #66ccff);
        color: white;
        padding: 20px 0;
        border-radius: 0 15px 15px 0;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar h2 {
        text-align: center;
        font-size: 22px;
        font-weight: bold;
        color: #fff;
        margin-bottom: 30px;
    }

    .sidebar a {
        display: block;
        color: white;
        padding: 12px 25px;
        margin: 8px 15px;
        border-radius: 10px;
        font-size: 15px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .sidebar a:hover,
    .sidebar a.active {
        background-color: #007bbd;
    }

    /* ===== MAIN CONTENT ===== */
    .main-content {
        flex: 1;
        padding: 30px 50px;
    }

    .main-content h1 {
        font-size: 28px;
        color: #0099cc;
        margin-bottom: 25px;
        font-weight: bold;
    }

    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card h3 {
        color: #0099cc;
        font-size: 18px;
        margin-bottom: 8px;
    }

    .card p {
        color: #555;
        font-size: 14px;
    }
</style>

<div class="admin-container">
    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>Mai Cò Shop</h2>
        <a href="#" class="active">🏠 Trang quản trị</a>
        <a href="#">📦 Quản lý sản phẩm</a>
        <a href="#">👥 Quản lý khách hàng</a>
        <a href="#">🧾 Quản lý đơn hàng</a>
        <a href="#">🗂️ Quản lý danh mục</a>
        <a href="{{ route('logout') }}" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">🚪 Đăng xuất</a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <h1>🎯 Chào mừng, {{ Auth::user()->name }}!</h1>
        <p>Đây là trang quản trị của <strong>Mai Cò Shop</strong>. Hãy chọn chức năng từ thanh bên trái để quản lý hệ thống.</p>

        <div class="card-container">
            <div class="card">
                <h3>📦 Sản phẩm</h3>
                <p>Quản lý danh sách sản phẩm, chỉnh sửa, thêm mới, xoá.</p>
            </div>

            <div class="card">
                <h3>👥 Khách hàng</h3>
                <p>Xem danh sách người dùng, lịch sử mua hàng và trạng thái.</p>
            </div>

            <div class="card">
                <h3>🧾 Đơn hàng</h3>
                <p>Theo dõi tình trạng đơn hàng và quản lý thanh toán.</p>
            </div>

            <div class="card">
                <h3>🗂️ Danh mục</h3>
                <p>Thêm hoặc chỉnh sửa các nhóm sản phẩm trong cửa hàng.</p>
            </div>
        </div>
    </div>
</div>
@endsection
