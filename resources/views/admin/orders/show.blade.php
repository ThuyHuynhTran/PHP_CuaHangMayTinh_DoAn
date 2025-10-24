@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mb-4">Chi tiết đơn hàng #{{ $order->id }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        {{-- Thông tin khách hàng & Giao hàng --}}
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-dark text-white"><i class="fas fa-user"></i> Thông tin khách hàng</div>
                <div class="card-body">
                    <p><strong>Tên:</strong> {{ $order->fullname }}</p>
                    <p><strong>Email:</strong> {{ optional($order->user)->email ?? 'Khách vãng lai' }}</p>
                    <p><strong>Điện thoại:</strong> {{ $order->phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                </div>
            </div>
        </div>

        {{-- Cập nhật trạng thái --}}
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-danger text-white"><i class="fas fa-sync-alt"></i> Cập nhật trạng thái</div>
                <div class="card-body">
                    <p><strong>Trạng thái hiện tại:</strong> 
                        <span class="badge 
                            @switch($order->status)
                                @case('cho_xac_nhan') bg-warning text-dark @break
                                @case('cho_lay_hang') bg-info text-dark @break
                                @case('cho_giao_hang') bg-primary @break
                                @case('da_giao') bg-success @break
                                @case('da_huy') bg-secondary @break
                            @endswitch">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </p>
                    <hr>
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Chọn trạng thái mới:</label>
                            <select name="status" id="status" class="form-select">
                                <option value="Chờ xác nhận" {{ $order->status == 'Chờ xác nhận' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="Chờ lấy hàng" {{ $order->status == 'Chờ lấy hàng' ? 'selected' : '' }}>Chờ lấy hàng</option>
                                <option value="Chờ giao hàng" {{ $order->status == 'Chờ giao hàng' ? 'selected' : '' }}>Đang giao hàng</option>
                                <option value="Đã giao" {{ $order->status == 'Đã giao' ? 'selected' : '' }}>Đã giao</option>
                                <option value="Đã hủy" {{ $order->status == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-danger">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
        
        {{-- Thông tin đơn hàng --}}
        <div class="col-md-4 mb-4">
             <div class="card h-100">
                <div class="card-header bg-dark text-white"><i class="fas fa-info-circle"></i> Thông tin đơn hàng</div>
                <div class="card-body">
                    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</p>
                    <hr>
                    <p><strong>Giảm giá:</strong> -{{ number_format($order->discount_amount, 0, ',', '.') }}₫</p>
                    <h5 class="text-danger"><strong>Tổng thanh toán: {{ number_format($order->total, 0, ',', '.') }}₫</strong></h5>
                </div>
            </div>
        </div>
    </div>

    {{-- Danh sách sản phẩm --}}
    <div class="card mt-4">
        <div class="card-header bg-dark text-white"><i class="fas fa-box-open"></i> Các sản phẩm trong đơn</div>
        <div class="card-body">
            <table class="table">
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td><img src="{{ asset('assets/img/' . optional($item->product)->duong_dan) }}" width="60" alt=""></td>
                        <td>{{ optional($item->product)->ten_sp ?? '[Sản phẩm đã bị xóa]' }}</td>
                        <td>Số lượng: {{ $item->quantity }}</td>
                        <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
</div>
@endsection

