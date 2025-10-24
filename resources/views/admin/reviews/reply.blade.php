@extends('layouts.admin')

@section('title', 'Phản hồi đánh giá')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mb-4">
        <i class="fas fa-reply text-danger"></i> Phản hồi đánh giá
    </h2>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Chi tiết đánh giá</h5>
        </div>

        <div class="card-body bg-light">
            {{-- Thông tin đánh giá gốc --}}
            <div class="mb-4 p-3 border rounded bg-white">
                <p><strong>Người dùng:</strong> {{ $review->user->name ?? 'N/A' }}</p>
                <p><strong>Sản phẩm:</strong> {{ optional($review->product)->ten_sp ?? 'Sản phẩm đã xóa' }}</p>
                <p><strong>Bình luận của khách:</strong></p>
                <blockquote class="blockquote">
                    <p class="mb-0"><em>{{ $review->comment }}</em></p>
                </blockquote>
            </div>

            {{-- Form phản hồi --}}
            <form method="POST" action="{{ route('admin.reviews.storeReply', $review->id) }}">
                @csrf
                <div class="mb-3">
                    <label for="admin_reply" class="form-label fw-bold">Nội dung phản hồi của bạn <span class="text-danger">*</span></label>
                    <textarea name="admin_reply" 
                              id="admin_reply"
                              class="form-control @error('admin_reply') is-invalid @enderror" 
                              rows="5" 
                              placeholder="Nhập phản hồi của bạn tại đây..." 
                              required>{{ old('admin_reply', $review->admin_reply) }}</textarea>
                    @error('admin_reply')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nút hành động --}}
                <div class="d-flex justify-content-end mt-4 gap-2">
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save"></i> Lưu phản hồi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
