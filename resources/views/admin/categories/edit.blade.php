@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>✏️ Chỉnh sửa danh mục</h2>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="ten_danh_muc" class="form-label">Tên danh mục</label>
            <input type="text" name="ten_danh_muc" class="form-control" value="{{ $category->ten_danh_muc }}" required>
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
