@extends('layouts.admin')

@section('title', 'Chỉnh sửa khuyến mãi')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mb-4">
        <i class="fas fa-tags text-danger"></i> Chỉnh sửa khuyến mãi
    </h2>

    {{-- Thông báo --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Form chỉnh sửa --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fas fa-edit"></i> Thông tin khuyến mãi</h5>
        </div>

        <div class="card-body bg-light">
            <form method="POST" action="{{ route('admin.promotions.update', $promotion->id) }}">
                @csrf
                @method('PUT')

                {{-- Hàng tiêu đề và phần trăm giảm --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tiêu đề khuyến mãi <span class="text-danger">*</span></label>
                        <input type="text" name="title"
                               value="{{ old('title', $promotion->title) }}"
                               class="form-control @error('title') is-invalid @enderror"
                               placeholder="Nhập tiêu đề khuyến mãi..." required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Phần trăm giảm giá (%) <span class="text-danger">*</span></label>
                        <input type="number" name="discount_percent"
                               value="{{ old('discount_percent', $promotion->discount_percent) }}"
                               class="form-control @error('discount_percent') is-invalid @enderror"
                               step="0.01" min="0" max="100"
                               placeholder="Ví dụ: 10 hoặc 20" required>
                        @error('discount_percent')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Mô tả --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Mô tả</label>
                    <textarea name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="3"
                              placeholder="Nhập mô tả ngắn gọn...">{{ old('description', $promotion->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ngày bắt đầu và kết thúc --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Ngày bắt đầu <span class="text-danger">*</span></label>
                        <input type="date" name="start_date"
                               value="{{ old('start_date', \Carbon\Carbon::parse($promotion->start_date)->format('Y-m-d')) }}"
                               class="form-control @error('start_date') is-invalid @enderror" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Ngày kết thúc <span class="text-danger">*</span></label>
                        <input type="date" name="end_date"
                               value="{{ old('end_date', \Carbon\Carbon::parse($promotion->end_date)->format('Y-m-d')) }}"
                               class="form-control @error('end_date') is-invalid @enderror" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Nút hành động --}}
                <div class="d-flex justify-content-end mt-4 gap-2">
                    <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection