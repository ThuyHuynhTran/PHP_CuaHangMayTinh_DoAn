@extends('layouts.admin')

@section('title', 'Bảng điều khiển')

@section('content')
    <h1>Chào mừng, {{ Auth::user()->name ?? 'Admin' }}!</h1>
    <p class="text-muted mb-4">
        Đây là trang quản trị của <strong>MaiCo Technology</strong>. 
        Hãy chọn chức năng từ thanh bên trái để bắt đầu.
    </p>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col">
            <a href="{{ route('admin.products.index') }}" class="text-decoration-none">

                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-box-open fa-3x text-danger mb-3"></i>
                        <h5 class="card-title">Sản phẩm</h5>
                        <p class="card-text text-muted">Quản lý danh sách sản phẩm, chỉnh sửa, thêm mới và xoá.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col">
            <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-file-invoice-dollar fa-3x text-danger mb-3"></i>
                        <h5 class="card-title">Đơn hàng</h5>
                        <p class="card-text text-muted">Theo dõi tình trạng đơn hàng và giao dịch.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col">
            <a href="{{ route('admin.categories.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-tags fa-3x text-danger mb-3"></i>
                        <h5 class="card-title">Danh mục</h5>
                        <p class="card-text text-muted">Thêm hoặc chỉnh sửa nhóm sản phẩm.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection
