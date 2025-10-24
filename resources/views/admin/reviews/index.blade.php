@extends('layouts.admin')

@section('title', 'Quản lý đánh giá')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mb-4"><i class="fas fa-star"></i> Quản lý đánh giá</h2>

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

    {{-- Bảng danh sách đánh giá --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-danger">
                    <tr>
                        <th>Người dùng</th>
                        <th>Sản phẩm</th>
                        <th>Đánh giá</th>
                        <th>Bình luận</th>
                        <th>Phản hồi Admin</th>
                        <th>Ngày tạo</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>{{ $review->user->name ?? 'N/A' }}</td>
                            <td>{{ optional($review->product)->ten_sp ?? 'Sản phẩm đã xóa' }}</td>
                            <td>
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </td>
                            <td style="max-width: 300px;">{{ $review->comment }}</td>
                            <td style="max-width: 300px;">
                                @if($review->admin_reply)
                                    <span class="text-success">{{ $review->admin_reply }}</span>
                                @else
                                    <span class="text-muted">Chưa có</span>
                                @endif
                            </td>
                            <td>{{ $review->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.reviews.reply', $review->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-reply"></i> Phản hồi
                                </a>
                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Chưa có đánh giá nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="d-flex justify-content-center mt-3">
        {{ $reviews->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

