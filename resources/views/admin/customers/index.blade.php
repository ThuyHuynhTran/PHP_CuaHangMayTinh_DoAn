@extends('layouts.admin')
@section('title', 'Quản lý khách hàng')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mb-4"><i class="fas fa-users"></i> Danh sách khách hàng</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-hover text-center">
        <thead class="table-danger">
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->is_locked)
                            <span class="badge bg-danger">Đã khóa</span>
                        @else
                            <span class="badge bg-success">Hoạt động</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td>
                         <a href="{{ route('admin.customers.show', $user->id) }}" class="btn btn-sm btn-primary">
        <i class="fas fa-eye"></i> Xem
    </a>
                        <form action="{{ route('admin.customers.toggleLock', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm {{ $user->is_locked ? 'btn-success' : 'btn-danger' }}">
                                <i class="fas {{ $user->is_locked ? 'fa-lock-open' : 'fa-lock' }}"></i>
                                {{ $user->is_locked ? 'Mở khóa' : 'Khóa' }}
                            </button>
                        </form>
                    </td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-3">
        {{ $customers->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
