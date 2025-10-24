@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mb-4"><i class="fas fa-box-open"></i> Danh sách sản phẩm</h2>

    {{-- Thông báo thành công --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Nút thêm sản phẩm --}}
    <div class="mb-3">
        <a href="{{ route('admin.products.create') }}" class="btn btn-danger">
            <i class="fas fa-plus"></i> Thêm sản phẩm
        </a>
    </div>
{{-- Bộ lọc sản phẩm --}}
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3 align-items-end">
            {{-- Lọc theo danh mục --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">Danh mục</label>
                <select name="danh_muc_id" class="form-select">
                    <option value="">-- Tất cả danh mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('danh_muc_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->ten_danh_muc }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Lọc theo thương hiệu --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">Thương hiệu</label>
                <input type="text" name="thuong_hieu" value="{{ request('thuong_hieu') }}" 
                       placeholder="Nhập tên thương hiệu..." class="form-control">
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary mt-4">
                    <i class="fas fa-filter"></i> Lọc
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-4">
                    <i class="fas fa-sync-alt"></i> Làm mới
                </a>
            </div>
        </form>
    </div>
</div>

    {{-- Bảng danh sách --}}
    <table class="table table-striped table-hover align-middle text-center">
        <thead class="table-danger">
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th>Ảnh</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->ten_sp }}</td>
                    <td>{{ $p->category->ten_danh_muc ?? 'Không có' }}</td>

                    {{-- ✅ Ép kiểu giá trị để tránh lỗi number_format --}}
                    <td>{{ number_format((float) filter_var($p->gia, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION), 0, ',', '.') }}₫</td>

                    <td>
                        @if($p->duong_dan)
                            <img src="{{ asset('assets/img/' . $p->duong_dan) }}" width="70" class="rounded shadow-sm" alt="Ảnh sản phẩm">
                        @else
                            <span class="text-muted">Chưa có</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Sửa
                        </a>

                        <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này không?')">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-muted">Chưa có sản phẩm nào trong hệ thống.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Phân trang --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
