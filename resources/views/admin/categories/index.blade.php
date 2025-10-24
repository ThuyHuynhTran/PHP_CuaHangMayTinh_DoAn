@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>🏷️ Danh sách danh mục</h2>

    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">+ Thêm danh mục</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-danger">
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $c)
                <tr>
                    <td>{{ $c->id }}</td>
                    <td>{{ $c->ten_danh_muc }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $c->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('admin.categories.destroy', $c->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Xóa danh mục này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $categories->links('pagination::bootstrap-5') }}
</div>
@endsection
