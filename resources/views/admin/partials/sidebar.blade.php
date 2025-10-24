<div class="sidebar">
    <div class="logo"><i class="fas fa-store"></i> MaiCo Admin</div>

    <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Trang quản trị
    </a>

    <a href="{{ route('admin.products') }}" class="{{ request()->is('admin/products*') ? 'active' : '' }}">
        <i class="fas fa-box-open"></i> Quản lý sản phẩm
    </a>

    <a href="{{ route('admin.categories') }}" class="{{ request()->is('admin/categories*') ? 'active' : '' }}">
        <i class="fas fa-tags"></i> Quản lý danh mục
    </a>

    <a href="{{ route('admin.promotions') }}" class="{{ request()->is('admin/promotions*') ? 'active' : '' }}">
        <i class="fas fa-percent"></i> Quản lý khuyến mãi
    </a>

    <a href="{{ route('admin.reviews') }}" class="{{ request()->is('admin/reviews*') ? 'active' : '' }}">
        <i class="fas fa-star"></i> Quản lý đánh giá
    </a>

    <a href="{{ route('admin.messages') }}" class="{{ request()->is('admin/messages*') ? 'active' : '' }}">
        <i class="fas fa-envelope"></i> Quản lý liên hệ
    </a>

    <a href="{{ route('admin.statistics.index') }}" class="{{ request()->is('admin/faqs*') ? 'active' : '' }}">
        <i class="fas fa-question-circle"></i> Thống kê doanh thu
    </a>
</div>
