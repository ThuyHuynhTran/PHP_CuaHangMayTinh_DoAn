<?php

namespace App\Http\Controllers;

use App\Models\MayTinh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // ===============================
        // 🔹 PHẦN SẢN PHẨM
        // ===============================
        $limit = 12;
        $page = $request->get('page', 1);

        // Lấy sản phẩm có phân trang
        $products = MayTinh::orderBy('id', 'asc')->paginate($limit, ['*'], 'page', $page);

        // ===============================
        // 🔹 PHẦN TIN TỨC
        // ===============================
        $imageDir = public_path('assets/img/news');
        $imageNames = File::exists($imageDir)
            ? collect(File::files($imageDir))->map(fn($file) => $file->getFilename())->toArray()
            : [];

        $titles = [
            'Tạo video lời chúc 20/10 bằng AI chi tiết, sáng tạo và đầy cảm xúc',
            'Huawei Watch D2 được thêm tính năng theo dõi huyết áp 24/7 trong bản cập nhật mới',
            'Samsung phát hành One UI 8 ổn định cho Galaxy Z Fold4, Z Flip4, M36 và F36',
            'Sennheiser HDB 630 ra mắt, mang Hi-Res aptX Adaptive đến cả những điện thoại không hỗ trợ',
            'ASUS giới thiệu dòng laptop OLED mỏng nhẹ nhất thế giới 2025',
            'MacBook Air M4 ra mắt với chip M4 hiệu năng vượt trội',
            'Dell XPS 2025 nâng cấp mạnh mẽ về hiệu năng và thiết kế',
            'Lenovo Legion 9i được trang bị hệ thống làm mát bằng chất lỏng',
            'HP Envy 2025 ra mắt với màn hình OLED 16 inch cực sắc nét',
            'Acer Aspire mới hỗ trợ sạc nhanh 100W Type-C',
            'Logitech MX Master 4 Pro ra mắt với cảm biến chính xác hơn',
        ];

        // Phân trang tin tức (mỗi lần 4 tin)
        $perPageNews = 4;
        $pageNews = $request->get('page', 1);
        $offset = ($pageNews - 1) * $perPageNews;

        $news = collect($titles)
            ->shuffle()
            ->slice($offset, $perPageNews)
            ->map(function ($title) use ($imageNames) {
                return [
                    'title' => $title,
                    'image' => count($imageNames) ? collect($imageNames)->random() : 'default.jpg',
                ];
            });

        // ===============================
        // 🔹 AJAX LOAD MORE NEWS
        // ===============================
        if ($request->ajax() && $request->has('loadNews')) {
            $view = view('partials.news_list', compact('news'))->render();
            return response()->json(['html' => $view]);
        }

        // ===============================
        // 🔹 AJAX LOAD MORE PRODUCTS
        // ===============================
        if ($request->ajax() && $request->has('loadProducts')) {
            $view = view('partials.product_list', compact('products'))->render();
            return response()->json(['html' => $view]);
        }

        // ===============================
        // 🔹 TRẢ VỀ TRANG CHÍNH
        // ===============================
        return view('mainpage_screen', compact('products', 'news'));
    }
    public function cart() {
    return view('cart');
}

}
