@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mb-4"><i class="fas fa-file-invoice-dollar"></i> Quản lý đơn hàng</h2>

    {{-- Tabs Lọc Trạng Thái --}}
    <div class="d-flex justify-content-start border-bottom mb-3">
        @php
            $statuses = [
                '' => 'Tất cả',
                'Chờ xác nhận' => 'Chờ xác nhận',
                'Chờ lấy hàng' => 'Chờ lấy hàng',
                'Chờ giao hàng' => 'Đang giao',
                'Đã giao' => 'Đã giao',
                'Đã hủy' => 'Đã hủy'
            ];
            $currentStatus = request('status', '');
        @endphp

        @foreach($statuses as $key => $label)
            <a href="{{ route('admin.orders.index', ['status' => $key] + request()->except('page')) }}"
               class="px-3 py-2 text-decoration-none {{ $currentStatus === $key ? 'border-bottom border-danger border-3 text-danger fw-bold' : 'text-muted' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- 🕒 Lọc theo thời gian --}}
    <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3 align-items-end mb-4">
        <div class="col-md-3">
            <label for="start_date" class="form-label">Từ ngày</label>
            <input type="date" id="start_date" name="start_date" class="form-control"
                   value="{{ request('start_date') }}">
        </div>
        <div class="col-md-3">
            <label for="end_date" class="form-label">Đến ngày</label>
            <input type="date" id="end_date" name="end_date" class="form-control"
                   value="{{ request('end_date') }}">
        </div>
        <div class="col-md-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select id="status" name="status" class="form-select">
                <option value="">Tất cả</option>
                @foreach($statuses as $key => $label)
                    @if($key !== '')
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-danger w-100">
                <i class="fas fa-filter"></i> Lọc
            </button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100">
                <i class="fas fa-rotate-right"></i> Đặt lại
            </a>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-danger">
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Tên khách hàng</th>
                        <th>Tổng tiền</th>
                        <th class="text-center">Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="fw-bold">#{{ $order->id }}</td>
                            <td>{{ $order->user->name ?? $order->fullname }}</td>
                            <td class="text-danger fw-bold">{{ number_format($order->total, 0, ',', '.') }}₫</td>
                            <td class="text-center">
                                <span class="badge 
                                    @switch($order->status)
                                        @case('Chờ xác nhận') bg-warning text-dark @break
                                        @case('Chờ lấy hàng') bg-info text-dark @break
                                        @case('Chờ giao hàng') bg-primary @break
                                        @case('Đã giao') bg-success @break
                                        @case('Đã hủy') bg-secondary @break
                                    @endswitch">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Xem chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Không có đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="d-flex justify-content-center mt-3">
        {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
