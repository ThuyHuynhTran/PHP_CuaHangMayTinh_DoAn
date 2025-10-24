@extends('layouts.admin')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mb-4"><i class="fas fa-edit"></i> Chỉnh sửa sản phẩm</h2>

    {{-- Hiển thị thông báo --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form cập nhật sản phẩm --}}
    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf
        @method('PUT')

        {{-- Tên sản phẩm --}}
        <div class="mb-3">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" name="ten_sp" value="{{ old('ten_sp', $product->ten_sp) }}" class="form-control" required>
        </div>

        {{-- Thương hiệu --}}
        <div class="mb-3">
            <label class="form-label">Thương hiệu</label>
            <input type="text" name="thuong_hieu" value="{{ old('thuong_hieu', $product->thuong_hieu) }}" class="form-control">
        </div>

        {{-- Giá --}}
        <div class="mb-3">
            <label class="form-label">Giá (₫)</label>
            {{-- ĐÃ SỬA: Lọc để chỉ lấy giá trị số, đảm bảo giá luôn hiển thị --}}
            <input type="number" name="gia" value="{{ old('gia', filter_var($product->gia, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) }}" class="form-control" min="0" required>
        </div>

        {{-- Danh mục --}}
        <div class="mb-3">
            <label class="form-label">Danh mục</label>
            <select name="danh_muc_id" class="form-select" required>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ $product->danh_muc_id == $c->id ? 'selected' : '' }}>
                        {{ $c->ten_danh_muc }}
                    </option>
                @endforeach
            </select>
        </div>

        <hr>

        {{-- Thông số kỹ thuật --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Bộ nhớ RAM</label>
                <input type="text" name="bo_nho_ram" value="{{ old('bo_nho_ram', $product->bo_nho_ram) }}" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Bộ nhớ trong</label>
                <input type="text" name="bo_nho_trong" value="{{ old('bo_nho_trong', $product->bo_nho_trong) }}" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Camera</label>
                <input type="text" name="camera" value="{{ old('camera', $product->camera) }}" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Pin</label>
                <input type="text" name="pin" value="{{ old('pin', $product->pin) }}" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Hệ điều hành</label>
                <input type="text" name="he_dieu_hanh" value="{{ old('he_dieu_hanh', $product->he_dieu_hanh) }}" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Màn hình</label>
                <input type="text" name="man_hinh" value="{{ old('man_hinh', $product->man_hinh) }}" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Màu sắc</label>
                <input type="text" name="mau_sac" value="{{ old('mau_sac', $product->mau_sac) }}" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Số lượng kho</label>
                <input type="number" name="so_luong_kho" value="{{ old('so_luong_kho', $product->so_luong_kho) }}" class="form-control" min="0">
            </div>
        </div>

        <hr>

        {{-- Ảnh sản phẩm --}}
        <div class="mb-3">
            <label class="form-label">Ảnh hiện tại</label><br>
            @if($product->duong_dan)
                <img src="{{ asset('assets/img/' . $product->duong_dan) }}" width="120" class="rounded shadow-sm mb-2" alt="Ảnh sản phẩm">
            @else
                <p class="text-muted">Chưa có ảnh</p>
            @endif

            <label class="form-label">Chọn ảnh mới (nếu muốn thay)</label>
            <input type="file" name="duong_dan" class="form-control" accept="image/*" onchange="previewImage(event)">
            <img id="preview" class="mt-3 rounded" style="display:none; width:120px;">
        </div>

        {{-- Mô tả --}}
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="mo_ta" class="form-control" rows="4">{{ old('mo_ta', $product->mo_ta) }}</textarea>
        </div>

        {{-- Nút hành động --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>

{{-- Preview ảnh khi upload --}}
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const output = document.getElementById('preview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>
@endsection
