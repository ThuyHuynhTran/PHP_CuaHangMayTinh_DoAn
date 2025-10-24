@extends('layouts.admin')

@section('title', 'Thống kê & Báo cáo')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mb-4"><i class="fas fa-chart-line"></i> Thống kê & Báo cáo</h2>

    <div class="row">
        {{-- Menu chọn loại báo cáo --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-header fw-bold">
                    Chọn loại báo cáo
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.statistics.show', 'daily') }}" class="list-group-item list-group-item-action {{ $reportType == 'daily' ? 'active' : '' }}">Doanh thu theo ngày</a>
                    <a href="{{ route('admin.statistics.show', 'monthly') }}" class="list-group-item list-group-item-action {{ $reportType == 'monthly' ? 'active' : '' }}">Doanh thu theo tháng</a>
                    <a href="{{ route('admin.statistics.show', 'top-products') }}" class="list-group-item list-group-item-action {{ $reportType == 'top-products' ? 'active' : '' }}">Sản phẩm bán chạy</a>
                    <a href="{{ route('admin.statistics.show', 'by-category') }}" class="list-group-item list-group-item-action {{ $reportType == 'by-category' ? 'active' : '' }}">Doanh thu theo danh mục</a>
                    <a href="{{ route('admin.statistics.show', 'top-customers') }}" class="list-group-item list-group-item-action {{ $reportType == 'top-customers' ? 'active' : '' }}">Khách hàng tiềm năng</a>
                </div>
            </div>
        </div>

        {{-- Khu vực hiển thị báo cáo --}}
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $title }}</h5>

                    {{-- FORM LỌC THEO NGÀY (CHO NHIỀU LOẠI BÁO CÁO) --}}
                    @if(in_array($reportType, ['daily', 'top-products', 'by-category', 'top-customers']))
                    <form method="GET" action="{{ route('admin.statistics.show', $reportType) }}" class="d-flex align-items-center gap-2">
                        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate ?? '' }}" style="width: 150px;">
                        <span class="text-white">-</span>
                        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate ?? '' }}" style="width: 150px;">
                        <button type="submit" class="btn btn-light btn-sm">Lọc</button>
                    </form>
                    @endif

                    {{-- FORM LỌC THEO NĂM --}}
                    @if($reportType == 'monthly')
                    <form method="GET" action="{{ route('admin.statistics.show', 'monthly') }}" class="d-flex align-items-center gap-2">
                        <select name="year" class="form-select form-select-sm" style="width: 120px;">
                            @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}" {{ ($year ?? date('Y')) == $y ? 'selected' : '' }}>Năm {{ $y }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="btn btn-light btn-sm">Lọc</button>
                    </form>
                    @endif
                </div>
                <div class="card-body">
                    @if($data->isEmpty() || (
    $reportType != 'top-products' &&
    $reportType != 'top-customers' &&
    $data->sum('total_revenue') == 0
))

                        <p class="text-center text-muted">Không có dữ liệu để hiển thị cho khoảng thời gian này.</p>
                    @else
                        {{-- Biểu đồ --}}
                        <div style="height: 400px;">
                            <canvas id="myChart"></canvas>
                        </div>

                        {{-- Bảng dữ liệu chi tiết --}}
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        @if($reportType == 'daily')
                                            <th>Ngày</th><th>Doanh thu</th>
                                        @elseif($reportType == 'monthly')
                                            <th>Tháng</th><th>Doanh thu</th>
                                        @elseif($reportType == 'top-products')
                                            <th>Sản phẩm</th><th>Số lượng bán</th>
                                        @elseif($reportType == 'by-category')
                                            <th>Danh mục</th><th>Doanh thu</th>
                                        @elseif($reportType == 'top-customers')
                                            <th>Khách hàng</th><th>Tổng chi tiêu</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $row)
                                    <tr>
                                        @if($reportType == 'daily')
                                            <td>{{ \Carbon\Carbon::parse($row->date)->format('d/m/Y') }}</td>
                                            <td>{{ number_format($row->total_revenue, 0, ',', '.') }}₫</td>
                                        @elseif($reportType == 'monthly')
                                            <td>Tháng {{ $row->month_numeric }}</td>
                                            <td>{{ number_format($row->total_revenue, 0, ',', '.') }}₫</td>
                                        @elseif($reportType == 'top-products')
                                            <td>{{ $row->ten_sp }}</td>
                                            <td>{{ $row->total_quantity }}</td>
                                        @elseif($reportType == 'by-category')
                                            <td>{{ $row->ten_danh_muc }}</td>
                                            <td>{{ number_format($row->total_revenue, 0, ',', '.') }}₫</td>
                                        @elseif($reportType == 'top-customers')
                                            <td>{{ $row->name }}</td>
                                            <td>{{ number_format($row->total_spent, 0, ',', '.') }}₫</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script cho biểu đồ Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('myChart');
        const reportType = @json($reportType);
        const rawData = @json($data);

        if (rawData.length === 0 || !ctx) {
            return;
        }
        
        const hasData = rawData.some(row => (row.total_revenue > 0 || row.total_quantity > 0 || row.total_spent > 0));
        if (!hasData) {
            const chartContainer = ctx.parentElement;
            if(chartContainer) {
                chartContainer.innerHTML = '<p class="text-center text-muted">Không có dữ liệu để vẽ biểu đồ.</p>';
            }
            return;
        }

        let labels = [];
        let chartData = [];
        let chartType = 'bar';
        let label = 'Giá trị';

        switch(reportType) {
            case 'daily':
                labels = rawData.map(row => new Date(row.date).toLocaleDateString('vi-VN'));
                chartData = rawData.map(row => row.total_revenue);
                chartType = 'line';
                label = 'Doanh thu';
                break;
            case 'monthly':
                labels = rawData.map(row => `Tháng ${row.month_numeric}`);
                chartData = rawData.map(row => row.total_revenue);
                chartType = 'bar';
                label = 'Doanh thu';
                break;
            case 'top-products':
                labels = rawData.map(row => row.ten_sp);
                chartData = rawData.map(row => row.total_quantity);
                label = 'Số lượng bán';
                break;
            case 'by-category':
                labels = rawData.map(row => row.ten_danh_muc);
                chartData = rawData.map(row => row.total_revenue);
                chartType = 'pie';
                label = 'Doanh thu';
                break;
            case 'top-customers':
                labels = rawData.map(row => row.name);
                chartData = rawData.map(row => row.total_spent);
                label = 'Tổng chi tiêu';
                break;
        }

        new Chart(ctx, {
            type: chartType,
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: chartData,
                    backgroundColor: chartType === 'pie' ? [
                        'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)',
                        'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)'
                    ] : 'rgba(220, 53, 69, 0.7)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1,
                    tension: 0.1
                }]
            },
            options: {
                scales: (chartType === 'line' || chartType === 'bar') ? {
                    y: { beginAtZero: true }
                } : {},
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
@endsection

