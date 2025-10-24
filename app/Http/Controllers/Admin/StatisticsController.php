<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\DienThoai;
use App\Models\DanhMuc;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    /**
     * Hiển thị trang thống kê chính, mặc định là doanh thu theo ngày.
     */
    public function index(Request $request)
    {
        return $this->showReport($request, 'daily');
    }

    /**
     * Lấy dữ liệu doanh thu theo ngày, có lọc theo khoảng thời gian.
     */
    public function getDailyRevenue(Request $request)
    {
        $query = Order::where('status', 'Đã giao');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->subDays(29)->startOfDay());
        }

        return $query->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total_revenue')
            ]);
    }

    /**
     * Lấy dữ liệu doanh thu theo tháng cho một năm cụ thể.
     */
    public function getMonthlyRevenue(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $revenue = Order::where('status', 'Đã giao')
            ->whereYear('created_at', $year)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as total_revenue')
            )
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->pluck('total_revenue', 'month')
            ->all();

        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = (object)[
                'month_numeric' => $i,
                'total_revenue' => $revenue[$i] ?? 0,
            ];
        }
        
        return collect($monthlyData);
    }

    /**
     * Thống kê sản phẩm bán chạy nhất, có lọc theo ngày.
     */
    public function getTopProducts(Request $request)
    {
        $query = DB::table('order_items')
            ->join('dien_thoais', 'order_items.product_id', '=', 'dien_thoais.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'Đã giao');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        } else {
            $query->where('orders.created_at', '>=', Carbon::now()->subDays(29)->startOfDay());
        }

        return $query->select('dien_thoais.ten_sp', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('dien_thoais.ten_sp')
            ->orderBy('total_quantity', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Thống kê doanh thu theo danh mục, có lọc theo ngày.
     */
    public function getRevenueByCategory(Request $request)
    {
        $query = DB::table('order_items')
            ->join('dien_thoais', 'order_items.product_id', '=', 'dien_thoais.id')
            ->join('danh_mucs', 'dien_thoais.danh_muc_id', '=', 'danh_mucs.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'Đã giao');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }

        return $query->select('danh_mucs.ten_danh_muc', DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'))
            ->groupBy('danh_mucs.ten_danh_muc')
            ->orderBy('total_revenue', 'desc')
            ->get();
    }

    /**
     * Thống kê khách hàng mua nhiều nhất, có lọc theo ngày.
     */
     public function getTopCustomers(Request $request)
    {
        // Bắt đầu truy vấn từ bảng 'orders' bằng Query Builder để dễ dàng join và lọc
        $query = DB::table('orders')
            ->where('orders.status', 'Đã giao')
            ->whereNotNull('orders.user_id');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        } else {
            // Thêm bộ lọc mặc định 30 ngày để nhất quán
            $query->where('orders.created_at', '>=', Carbon::now()->subDays(29)->startOfDay());
        }

        return $query->join('users', 'orders.user_id', '=', 'users.id')
            ->select('users.name', DB::raw('SUM(orders.total) as total_spent'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_spent', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Hàm trung tâm để gọi các loại báo cáo khác nhau.
     */
    public function showReport(Request $request, $reportType)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(29)->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());
        $year = $request->input('year', Carbon::now()->year);

        switch ($reportType) {
            case 'daily':
                $data = $this->getDailyRevenue($request);
                $title = 'Thống kê doanh thu theo ngày';
                break;
            case 'monthly':
                $data = $this->getMonthlyRevenue($request);
                $title = 'Thống kê doanh thu theo tháng năm ' . $year;
                break;
            case 'top-products':
                $data = $this->getTopProducts($request);
                $title = 'Top 10 sản phẩm bán chạy';
                break;
            case 'by-category':
                $data = $this->getRevenueByCategory($request);
                $title = 'Thống kê doanh thu theo danh mục';
                break;
            case 'top-customers':
                $data = $this->getTopCustomers($request);
                $title = 'Top 10 khách hàng chi tiêu nhiều nhất';
                break;
            default:
                abort(404);
        }

        return view('admin.statistics.index', compact('data', 'reportType', 'title', 'startDate', 'endDate', 'year'));
    }
}

