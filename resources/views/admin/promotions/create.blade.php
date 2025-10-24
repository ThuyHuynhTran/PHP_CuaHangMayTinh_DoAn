@extends('layouts.admin')

@section('title', 'Thêm khuyến mãi mới')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mb-4"><i class="fas fa-plus-circle"></i> Thêm khuyến mãi mới</h2>

    <form method="POST" action="{{ route('admin.promotions.store') }}" class="bg-white p-4 rounded shadow-sm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tiêu đề khuyến mãi</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Nhập mô tả ngắn về chương trình..."></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Phần trăm giảm giá (%)</label>
            <input type="number" name="discount_percent" class="form-control" step="0.01" min="0" max="100" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Ngày bắt đầu</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Ngày kết thúc</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-save"></i> Lưu khuyến mãi
            </button>
            <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection
