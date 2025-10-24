@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mb-4"><i class="fas fa-plus-circle"></i> Thêm sản phẩm mới</h2>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf

        {{-- Tên sản phẩm --}}
        <div class="mb-3">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" name="ten_sp" class="form-control" required>
        </div>

        {{-- Thương hiệu --}}
        <div class="mb-3">
            <label class="form-label">Thương hiệu</label>
            <input type="text" name="thuong_hieu" class="form-control" placeholder="VD: Samsung, Apple...">
        </div>

        {{-- Mô tả --}}
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="mo_ta" class="form-control" rows="3" placeholder="Mô tả ngắn gọn về sản phẩm..."></textarea>
        </div>

        {{-- Giá --}}
        <div class="mb-3">
            <label class="form-label">Giá (VNĐ)</label>
            <input type="number" name="gia" class="form-control" min="0" required>
        </div>

        {{-- Danh mục --}}
        <div class="mb-3">
            <label class="form-label">Danh mục</label>
            <select name="danh_muc_id" class="form-select" required>
                @foreach($categories as $cate)
                    <option value="{{ $cate->id }}">{{ $cate->ten_danh_muc }}</option>
                @endforeach
            </select>
        </div>

        <hr>

        {{-- Thông số kỹ thuật --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Bộ nhớ RAM</label>
                <input type="text" name="bo_nho_ram" class="form-control" placeholder="VD: 8GB, 12GB">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Bộ nhớ trong</label>
                <input type="text" name="bo_nho_trong" class="form-control" placeholder="VD: 128GB, 256GB">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Camera</label>
                <input type="text" name="camera" class="form-control" placeholder="VD: 50MP, 12MP + 48MP...">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Pin</label>
                <input type="text" name="pin" class="form-control" placeholder="VD: 5000mAh">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Hệ điều hành</label>
                <input type="text" name="he_dieu_hanh" class="form-control" placeholder="VD: Android 14, iOS 18...">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Màn hình</label>
                <input type="text" name="man_hinh" class="form-control" placeholder="VD: 6.7 inch OLED 120Hz">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Màu sắc</label>
                <input type="text" name="mau_sac" class="form-control" placeholder="VD: Đen, Trắng, Xanh...">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Số lượng kho</label>
                <input type="number" name="so_luong_kho" class="form-control" min="0" placeholder="Nhập số lượng tồn kho">
            </div>
        </div>

        <hr>

        {{-- Ảnh sản phẩm --}}
        <div class="mb-3">
            <label class="form-label">Ảnh sản phẩm</label>
            <input type="file" name="duong_dan" class="form-control">
        </div>

        {{-- Nút hành động --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-save"></i> Lưu sản phẩm
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection
