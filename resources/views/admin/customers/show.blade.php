@extends('layouts.admin')
@section('title', 'Chi tiết khách hàng')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mb-4"><i class="fas fa-user-circle"></i> Thông tin khách hàng</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5><strong>Tên:</strong> {{ $user->name }}</h5>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Ngày tạo:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Trạng thái:</strong>
                @if($user->is_locked)
                    <span class="badge bg-danger">Đã khóa</span>
                @else
                    <span class="badge bg-success">Hoạt động</span>
                @endif
            </p>

            <form action="{{ route('admin.customers.toggleLock', $user->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-sm {{ $user->is_locked ? 'btn-success' : 'btn-danger' }}">
                    <i class="fas {{ $user->is_locked ? 'fa-lock-open' : 'fa-lock' }}"></i>
                    {{ $user->is_locked ? 'Mở khóa' : 'Khóa tài khoản' }}
                </button>
            </form>
        </div>
    </div>

    <h4 class="mt-5"><i class="fas fa-file-invoice-dollar"></i> Đơn hàng của khách hàng</h4>

    @if($orders->isEmpty())
        <p class="text-muted">Khách hàng này chưa có đơn hàng nào.</p>
    @else
        <table class="table table-bordered table-striped mt-3 text-center">
            <thead class="table-danger">
                <tr>
                    <th>ID</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ number_format($order->total, 0, ',', '.') }}₫</td>
                        <td>
                            <span class="badge 
                                @if($order->status === 'Đã giao') bg-success 
                                @elseif($order->status === 'Đã hủy') bg-danger 
                                @else bg-warning text-dark 
                                @endif">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-3">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
