@extends('admin.dashboard')

@section('admin-content')
    <h1>Chào mừng, Admin!</h1>
    <p>Đây là trang quản trị của <strong>MaiCo Technology</strong>.</p>

    <div class="card-container">
        <!-- Các card giới thiệu -->
        <div class="card">
            <i class="fas fa-box-open icon"></i>
            <h3>Sản phẩm</h3>
            <p>Quản lý danh sách sản phẩm, thêm, sửa, xóa.</p>
        </div>
        <div class="card">
            <i class="fas fa-file-invoice icon"></i>
            <h3>Đơn hàng</h3>
            <p>Theo dõi trạng thái, xử lý thanh toán.</p>
        </div>
        <!-- ... -->
    </div>
@endsection
