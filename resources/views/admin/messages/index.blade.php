@extends('layouts.admin')

@section('title', 'Quản lý tin nhắn')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mb-4"><i class="fas fa-envelope-open-text"></i> Hộp thư liên hệ</h2>

    {{-- Thông báo --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-danger">
                    <tr>
                        <th>Người gửi</th>
                        <th>Email</th>
                        <th>Nội dung</th>
                        <th class="text-center">Trạng thái</th>
                        <th>Ngày gửi</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                        <tr class="{{ $message->status == 'chua_doc' ? 'fw-bold' : '' }}">
                            <td>{{ $message->name }}</td>
                            <td>{{ $message->email }}</td>
                            <td style="max-width: 400px;">{{ Str::limit($message->message, 100) }}</td>
                            <td class="text-center">
                                @if($message->status == 'chua_doc')
                                    <span class="badge bg-danger">Chưa đọc</span>
                                @elseif($message->status == 'da_doc')
                                    <span class="badge bg-secondary">Đã đọc</span>
                                @else
                                    <span class="badge bg-success">Đã trả lời</span>
                                @endif
                            </td>
                            <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.messages.show', $message->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Xem & Trả lời
                                </a>
                                <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa tin nhắn này?')">
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
                            <td colspan="6" class="text-center text-muted">Hộp thư của bạn trống.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="d-flex justify-content-center mt-3">
        {{ $messages->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

