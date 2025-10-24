@extends('layouts.admin')

@section('title', 'Quản lý khuyến mãi')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mb-4"><i class="fas fa-percent"></i> Danh sách khuyến mãi</h2>

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

    {{-- Hàng nút chức năng --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.promotions.create') }}" class="btn btn-danger">
            <i class="fas fa-plus-circle"></i> Thêm khuyến mãi
        </a>

        <form id="bulkDeleteForm" 
              action="{{ route('admin.promotions.bulkDelete') }}" 
              method="POST"
              onsubmit="return confirm('Bạn có chắc muốn xóa các khuyến mãi đã chọn không?')">
            @csrf
            @method('DELETE')
            <input type="hidden" name="ids" id="selectedIds">
            <button type="submit" class="btn btn-outline-danger">
                <i class="fas fa-trash-alt"></i> Xóa mục đã chọn
            </button>
        </form>
    </div>

    {{-- Bảng danh sách --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-danger text-center">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Giảm giá (%)</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $promo)
                        <tr>
                            <td class="text-center"><input type="checkbox" class="promo-checkbox" value="{{ $promo->id }}"></td>
                            <td class="text-center fw-bold">{{ $promo->id }}</td>
                            <td>{{ $promo->title }}</td>
                            <td class="text-center">{{ number_format($promo->discount_percent, 2) }}%</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($promo->start_date)->format('d/m/Y') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') }}</td>
                            <td class="text-center">
                                
                                {{-- NÚT SỬA ĐÃ ĐƯỢC THAY ĐỔI --}}
                                <a href="{{ route('admin.promotions.edit', $promo->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>

                                <form action="{{ route('admin.promotions.destroy', $promo->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Xóa khuyến mãi này?')" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">Chưa có khuyến mãi nào</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $promotions->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- Script cho chức năng Xóa hàng loạt --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.promo-checkbox');
    const form = document.getElementById('bulkDeleteForm');
    const hiddenInput = document.getElementById('selectedIds');

    // Chọn tất cả
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Xóa hàng loạt
    form.addEventListener('submit', function(e) {
        const ids = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
        if (ids.length === 0) {
            alert('Vui lòng chọn ít nhất một khuyến mãi để xóa!');
            e.preventDefault();
            return;
        }
        hiddenInput.value = ids.join(',');
    });
});
</script>
@endsection