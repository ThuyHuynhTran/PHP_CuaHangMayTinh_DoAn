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
                      color:{{ request('status', 'cho_xac_nhan') === $key ? '#0b2c80' : '#555' }}; 
                      border-bottom:3px solid {{ request('status', 'cho_xac_nhan') === $key ? '#0b2c80' : 'transparent' }};
                      font-weight:{{ request('status', 'cho_xac_nhan') === $key ? 'bold' : 'normal' }};">
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
                        // Lấy review cụ thể thay vì chỉ kiểm tra tồn tại
                        $review = \App\Models\Review::where('user_id', Auth::id())
                            ->where('product_id', $item->product->id)
                            ->first();
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

                        {{-- NÚT ĐÁNH GIÁ HOẶC SỬA ĐÁNH GIÁ --}}
                        @if($order->status === 'Đã giao')
                            @if(!$review)
                                {{-- Nút đánh giá mới --}}
                                <button class="btn-standard btn-yellow btn-review"
                                        data-product="{{ $item->product->id }}"
                                        data-name="{{ $item->product->ten_sp }}">
                                    ⭐ Đánh giá
                                </button>
                            @else
                                {{-- Nút sửa đánh giá đã có --}}
                                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 5px;">
                                     <span style="color:#28a745; font-size:14px; font-weight: bold;">✅ Đã đánh giá</span>
                                     <button class="btn-standard btn-blue btn-edit-review"
                                        data-review-id="{{ $review->id }}">
                                        Sửa
                                    </button>
                                </div>
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
                <div class="btn-group">
                    <a href="{{ route('orders.show', $order->id) }}" 
                       class="btn-standard btn-blue">
                    Xem chi tiết
                    </a>

                    @if($order->status === 'Chờ xác nhận')
                        <button class="btn-standard btn-red btn-cancel"
                                data-order="{{ $order->id }}">
                            Hủy đơn hàng
                        </button>

                    @elseif($order->status === 'Đang giao')
                        {{-- Làm mờ + vô hiệu hóa --}}
                        <button class="btn-standard btn-red disabled-btn" disabled title="Không thể hủy khi đang giao">
                            Hủy đơn hàng
                        </button>

                    @elseif($order->status === 'Đã hủy')
                        <a href="{{ route('checkout.now', $order->items->first()->product->id) }}"
                           class="btn-standard btn-green">
                           Mua lại
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
        <h3 style="margin-bottom:15px; color:#c21b1b;">Hủy đơn hàng</h3>
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
                <button type="submit" class="btn-standard btn-red">
                    Xác nhận hủy
                </button>
                <button type="button" id="closeCancel" class="btn-standard btn-gray">
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

        <form id="reviewForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="reviewMethod">
            <input type="hidden" name="product_id" id="productId">

            <div style="margin-bottom:10px;">
                <label><strong>Chọn số sao:</strong></label><br>
                <select name="rating" id="reviewRating" required style="padding:5px 10px; border-radius:5px; border:1px solid #ccc;">
                    <option value="">--Chọn--</option>
                    @for($i=1;$i<=5;$i++)
                        <option value="{{ $i }}">{{ $i }} ⭐</option>
                    @endfor
                </select>
            </div>

            <textarea name="comment" id="reviewComment" rows="3" placeholder="Nhập đánh giá của bạn..."
                      style="width:100%; border-radius:8px; border:1px solid #ccc; padding:8px;"></textarea>

            <div style="margin-top:10px; text-align:left;">
                <label for="reviewImages"><strong>📷 Thêm/Thay thế hình ảnh (tối đa 3):</strong></label>
                <input type="file" name="images[]" id="reviewImages" multiple accept="image/*"
                       style="display:block; margin-top:5px;">
                <div id="imagePreview" 
                     style="display:flex; gap:8px; flex-wrap:wrap; margin-top:8px;"></div>
            </div>

            <div class="review-actions" style="margin-top:20px;">
                <button type="submit" id="submitReviewBtn" class="btn-standard btn-blue">
                    Cập nhật đánh giá
                </button>
                <button type="button" id="closeModal" class="btn-standard btn-gray">
                    Hủy
                </button>
            </div>
        </form>
    </div>
</div>

{{-- JS xử lý modal hủy & đánh giá --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
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

    const reviewModal = document.getElementById('reviewModal');
    const reviewForm = document.getElementById('reviewForm');
    const productNameEl = document.getElementById('productName');
    const productIdInput = document.getElementById('productId');
    const closeModal = document.getElementById('closeModal');
    const imageInput = document.getElementById('reviewImages');
    const imagePreview = document.getElementById('imagePreview');
    const reviewMethodInput = document.getElementById('reviewMethod');
    const reviewRatingSelect = document.getElementById('reviewRating');
    const reviewCommentTextarea = document.getElementById('reviewComment');
    const submitReviewBtn = document.getElementById('submitReviewBtn');

    document.querySelectorAll('.btn-review').forEach(btn => {
        btn.addEventListener('click', () => {
            const productId = btn.dataset.product;
            const productName = btn.dataset.name;
            reviewForm.reset();
            imagePreview.innerHTML = "";
            reviewForm.action = "{{ route('review.store') }}";
            reviewMethodInput.value = "POST";
            submitReviewBtn.textContent = "Gửi đánh giá";
            productNameEl.textContent = "Đánh giá sản phẩm: " + productName;
            productIdInput.value = productId;
            reviewModal.style.display = 'flex';
        });
    });

    document.querySelectorAll('.btn-edit-review').forEach(btn => {
        btn.addEventListener('click', () => {
            const reviewId = btn.dataset.reviewId;
            fetch(`/reviews/${reviewId}/edit`)
                .then(response => response.json())
                .then(data => {
                    productNameEl.textContent = "Chỉnh sửa đánh giá";
                    productIdInput.value = data.product_id;
                    reviewRatingSelect.value = data.rating;
                    reviewCommentTextarea.value = data.comment;
                    imagePreview.innerHTML = "";
                    if(data.images && data.images.length > 0) {
                        data.images.forEach(img => {
                             const imgEl = document.createElement('img');
                             imgEl.src = `/storage/${img.path}`;
                             imgEl.style.width = "70px";
                             imgEl.style.height = "70px";
                             imgEl.style.borderRadius = "6px";
                             imagePreview.appendChild(imgEl);
                        });
                    }
                    reviewForm.action = `/reviews/${reviewId}`;
                    reviewMethodInput.value = "PUT";
                    submitReviewBtn.textContent = "Cập nhật đánh giá";
                    reviewModal.style.display = 'flex';
                })
                .catch(error => console.error('Lỗi:', error));
        });
    });
    
    closeModal.addEventListener('click', () => reviewModal.style.display = 'none');
    window.addEventListener('click', e => { if (e.target === reviewModal) reviewModal.style.display = 'none'; });

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

<style>
/* === CSS CHUẨN CHO TẤT CẢ NÚT === */
.btn-standard,
.disabled-btn {
    width: 130px;
    height: 35px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 15px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
    background-color: #fff;
    box-sizing: border-box;
    white-space: nowrap; /* tránh xuống dòng gây lệch */
}
#imagePreview {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 8px;
}


/* Viền + màu chữ cho từng loại */
.btn-blue { border: 1.2px solid #0b2c80; color: #0b2c80; }
.btn-blue:hover { background-color: #0b2c80; color: #fff; }

.btn-red { border: 1.2px solid #c21b1b; color: #c21b1b; }
.btn-red:hover { background-color: #c21b1b; color: #fff; }

.btn-green { border: 1.2px solid green; color: green; }
.btn-green:hover { background-color: green; color: #fff; }

.btn-yellow { border: 1.2px solid #ffb700; color: #ffb700; }
.btn-yellow:hover { background-color: #ffb700; color: #fff; }

.btn-gray { border: 1.2px solid #777; color: #777; }
.btn-gray:hover { background-color: #777; color: #fff; }

/* Nút disable — cùng kích thước, viền đỏ đậm */
.disabled-btn {
    border: 1.2px solid #c21b1b;
    color: #c21b1b;
    background-color: #fff;
    cursor: not-allowed;
    pointer-events: none;
    opacity: 1;
    filter: brightness(97%);
}

.btn-standard:active { transform: scale(0.97); }

.btn-group {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 10px;
}

/* === CĂN CHỈNH NÚT TRONG POPUP ĐÁNH GIÁ === */
#reviewModal .review-actions {
    display: flex;
    justify-content: center;  /* căn giữa 2 nút */
    gap: 12px;
    flex-wrap: wrap;
}

/* Bắt buộc cùng kích thước trong popup, đủ dài để không xuống dòng */
#reviewModal .review-actions .btn-standard {
    width: 180px;   /* rộng hơn để "Cập nhật đánh giá" không bị xuống dòng */
    height: 40px;
}
</style>

@endsection
