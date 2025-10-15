@extends('layouts.main')

@section('content')
<main style="max-width: 1000px; margin: 30px auto; background:#fff; border-radius:10px; padding:20px; box-shadow:0 3px 8px rgba(0,0,0,0.1);">

    <h2 style="color:#0b2c80; margin-bottom:20px;">📦 Đơn hàng của tôi</h2>

    {{-- Tabs trạng thái --}}
    <div style="display:flex; justify-content:space-around; border-bottom:2px solid #eee; margin-bottom:15px;">
        @php
            $tabs = [
                'cho_xac_nhan' => 'Chờ xác nhận',
                'cho_lay_hang' => 'Chờ lấy hàng',
                'cho_giao_hang' => 'Đang giao',
                'da_giao' => 'Đã giao',
                'tra_hang' => 'Trả hàng',
                'da_huy' => 'Đã hủy'
            ];
        @endphp

        @foreach($tabs as $key => $label)
            <a href="{{ route('orders.myorder', ['status' => $key]) }}" 
               style="flex:1; text-align:center; padding:10px; text-decoration:none; 
                      color:{{ request('status') === $key ? '#0b2c80' : '#555' }}; 
                      border-bottom:3px solid {{ request('status') === $key ? '#0b2c80' : 'transparent' }};
                      font-weight:{{ request('status') === $key ? 'bold' : 'normal' }};">
               {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Danh sách đơn hàng --}}
    @if($orders->isEmpty())
        <p style="color:#777; text-align:center;">Không có đơn hàng nào trong trạng thái này.</p>
    @else
        @foreach($orders as $order)
            <div style="border:1px solid #ddd; border-radius:10px; padding:15px; margin-bottom:15px;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <strong style="color:#0b2c80;">#{{ $order->id }}</strong>
                    <span style="color:#0b2c80; font-weight:bold;">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
                <hr style="margin:8px 0;">

                @foreach($order->items as $item)
                    @php
                        $hasReviewed = \App\Models\Review::where('user_id', Auth::id())
                            ->where('product_id', $item->product->id)
                            ->exists();
                    @endphp

                    <div style="display:flex; align-items:center; gap:15px; margin-bottom:10px;">
                        <img src="{{ asset('assets/img/' . $item->product->duong_dan) }}" 
                             alt="{{ $item->product->ten_sp }}" 
                             style="width:80px; height:80px; border-radius:6px; object-fit:cover;">
                        <div style="flex:1;">
                            <p style="margin:0; font-weight:600;">{{ $item->product->ten_sp }}</p>
                            <p style="margin:0; color:#777;">x{{ $item->quantity }}</p>
                        </div>
                        <p style="margin:0; font-weight:bold; color:#0b2c80;">
                            {{ number_format($item->price, 0, ',', '.') }}₫
                        </p>

                        {{-- Chỉ hiển thị nút nếu đã giao và chưa đánh giá --}}
                        @if($order->status === 'Đã giao')
                            @if(!$hasReviewed)
                                <button class="btn-review"
                                    data-product="{{ $item->product->id }}"
                                    data-name="{{ $item->product->ten_sp }}"
                                    style="background:#ffb700; border:none; color:#fff; padding:6px 12px; border-radius:6px; cursor:pointer;">
                                    ⭐ Đánh giá
                                </button>
                            @else
                                <span style="color:#999; font-size:14px;">✅ Đã đánh giá</span>
                            @endif
                        @endif
                    </div>
                @endforeach

                <div style="text-align:right; margin-top:10px;">
                    <strong>Tổng: 
                        <span style="color:#c21b1b;">{{ number_format($order->total, 0, ',', '.') }}₫</span>
                    </strong>
                </div>

                {{-- Nút thao tác trạng thái --}}
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:10px;">
                    @if($order->status === 'Chờ xác nhận')
                        <button class="btn-cancel"
                            data-order="{{ $order->id }}"
                            style="background:#c21b1b; color:#fff; border:none; padding:6px 12px; border-radius:6px; cursor:pointer;">
                            ❌ Hủy đơn hàng
                        </button>
                    @elseif($order->status === 'Đã hủy')
                        <a href="{{ route('checkout.now', $order->items->first()->product->id) }}"
                           style="background:#0b2c80; color:white; padding:6px 12px; border-radius:6px; text-decoration:none;">
                           🔁 Mua lại
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</main>

{{-- Modal Hủy đơn --}}
<div id="cancelModal"
     style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.4);
            align-items:center; justify-content:center; z-index:1000;">
    <div style="background:white; padding:20px; border-radius:10px; width:400px;">
        <h3 style="margin-bottom:15px; color:#c21b1b;">❌ Hủy đơn hàng</h3>
        <form id="cancelForm" method="POST" action="{{ route('orders.cancel') }}">
            @csrf
            <input type="hidden" name="order_id" id="cancelOrderId">

            <p>Vui lòng chọn lý do hủy:</p>
            <select name="reason" required style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">
                <option value="">-- Chọn lý do --</option>
                <option value="Đặt nhầm sản phẩm">Đặt nhầm sản phẩm</option>
                <option value="Không còn nhu cầu">Không còn nhu cầu</option>
                <option value="Tìm thấy giá tốt hơn">Tìm thấy giá tốt hơn</option>
            </select>

            <textarea name="other_reason" placeholder="Lý do khác (nếu có)" rows="2"
                      style="width:100%; margin-top:10px; border-radius:6px; border:1px solid #ccc; padding:8px;"></textarea>

            <div style="text-align:right; margin-top:15px;">
                <button type="submit"
                        style="background:#c21b1b; color:white; border:none; padding:8px 14px; border-radius:6px; cursor:pointer;">
                    Xác nhận hủy
                </button>
                <button type="button" id="closeCancel"
                        style="background:#ccc; border:none; padding:8px 14px; border-radius:6px; margin-left:8px; cursor:pointer;">
                    Đóng
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal đánh giá --}}
<div id="reviewModal" 
     style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5);
            align-items:center; justify-content:center; z-index:1000;">
    <div style="background:#fff; padding:20px; border-radius:10px; width:420px; text-align:center;">
        <h3 id="productName" style="margin-bottom:10px; color:#0b2c80;"></h3>

        <form id="reviewForm" method="POST" action="{{ route('review.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" id="productId">

            <!-- Rating -->
            <div style="margin-bottom:10px;">
                <label><strong>Chọn số sao:</strong></label><br>
                <select name="rating" required style="padding:5px 10px; border-radius:5px; border:1px solid #ccc;">
                    <option value="">--Chọn--</option>
                    @for($i=1;$i<=5;$i++)
                        <option value="{{ $i }}">{{ $i }} ⭐</option>
                    @endfor
                </select>
            </div>

            <!-- Comment -->
            <textarea name="comment" rows="3" placeholder="Nhập đánh giá của bạn..."
                      style="width:100%; border-radius:8px; border:1px solid #ccc; padding:8px;"></textarea>

            <!-- Upload hình ảnh -->
            <div style="margin-top:10px; text-align:left;">
                <label for="reviewImages"><strong>📷 Thêm hình ảnh (tối đa 3):</strong></label>
                <input type="file" name="images[]" id="reviewImages" multiple accept="image/*"
                       style="display:block; margin-top:5px;">
                <div id="imagePreview" 
                     style="display:flex; gap:8px; flex-wrap:wrap; margin-top:8px;"></div>
            </div>

            <div style="margin-top:20px;">
                <button type="submit" 
                        style="background:#0b2c80; color:white; border:none; padding:8px 16px; border-radius:6px; cursor:pointer;">
                    Gửi đánh giá
                </button>
                <button type="button" id="closeModal" 
                        style="background:#ccc; border:none; padding:8px 16px; border-radius:6px; margin-left:10px; cursor:pointer;">
                    Hủy
                </button>
            </div>
        </form>
    </div>
</div>

{{-- JS xử lý modal hủy & đánh giá --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    // === PHẦN HỦY ĐƠN ===
    const cancelModal = document.getElementById('cancelModal');
    const cancelOrderId = document.getElementById('cancelOrderId');
    const closeCancel = document.getElementById('closeCancel');
    document.querySelectorAll('.btn-cancel').forEach(btn => {
        btn.addEventListener('click', () => {
            cancelOrderId.value = btn.dataset.order;
            cancelModal.style.display = 'flex';
        });
    });
    closeCancel.addEventListener('click', () => cancelModal.style.display = 'none');
    window.addEventListener('click', e => { if (e.target === cancelModal) cancelModal.style.display = 'none'; });

    // === PHẦN ĐÁNH GIÁ ===
    const reviewModal = document.getElementById('reviewModal');
    const reviewForm = document.getElementById('reviewForm');
    const productNameEl = document.getElementById('productName');
    const productIdInput = document.getElementById('productId');
    const closeModal = document.getElementById('closeModal');
    const imageInput = document.getElementById('reviewImages');
    const imagePreview = document.getElementById('imagePreview');

    document.querySelectorAll('.btn-review').forEach(btn => {
        btn.addEventListener('click', () => {
            const productId = btn.dataset.product;
            const productName = btn.dataset.name;
            productNameEl.textContent = "Đánh giá sản phẩm: " + productName;
            productIdInput.value = productId;
            reviewForm.reset();
            imagePreview.innerHTML = "";
            reviewModal.style.display = 'flex';
        });
    });
    closeModal.addEventListener('click', () => reviewModal.style.display = 'none');
    window.addEventListener('click', e => { if (e.target === reviewModal) reviewModal.style.display = 'none'; });

    // Preview ảnh
    imageInput.addEventListener('change', function () {
        imagePreview.innerHTML = "";
        const files = Array.from(this.files).slice(0, 3);
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = "70px";
                img.style.height = "70px";
                img.style.borderRadius = "6px";
                img.style.objectFit = "cover";
                img.style.border = "1px solid #ccc";
                imagePreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
});
</script>
@endsection
