@extends('layouts.main')
<style>
 /* Grid layout đều nhau */
.wishlist-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
  gap: 25px;
  align-items: stretch;
}

/* Card sản phẩm */
.wishlist-card {
  position: relative;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: all 0.3s ease;
}

.wishlist-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

/* Ảnh sản phẩm */
.wishlist-image {
  width: 100%;
  height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fafafa;
}

.wishlist-image img {
  max-width: 100%;
  max-height: 180px;
  object-fit: contain;
}

/* Nút ❤️ */
.wishlist-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  background: white;
  border: none;
  border-radius: 50%;
  width: 38px;
  height: 38px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
  transition: transform 0.2s;
}
.wishlist-btn:hover {
  transform: scale(1.1);
}

/* Phần nội dung sản phẩm */
.wishlist-info {
  text-align: center;
  padding: 15px;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  min-height: 150px; /* Giữ độ cao đồng đều */
}

/* Tên sản phẩm */
.wishlist-info h3 {
  font-size: 16px;
  font-weight: 600;
  color: #222;
  line-height: 1.3;
  min-height: 40px; /* Giữ cùng chiều cao dù tên dài hay ngắn */
  margin-bottom: 10px;
}

/* Giá */
.wishlist-info .price {
  color: #c21b1b;
  font-weight: bold;
  margin-bottom: 15px;
}

/* Nút Xem chi tiết */
.wishlist-detail-btn {
  display: inline-block;
  background: #c21b1b;
  color: white;
  padding: 10px 18px;
  border-radius: 8px;
  text-decoration: none;
  transition: background 0.3s ease;
  align-self: center;
  margin-top: auto;
}

.wishlist-detail-btn:hover {
  background: #a31717;
}


    </style>

@section('content')
<div style="max-width: 1200px; margin: 40px auto; padding: 20px;">
    <h2 style="font-size: 28px; font-weight: bold; color: #c21b1b; margin-bottom: 30px; display:flex; align-items:center; gap:10px;">
        Danh sách yêu thích <span style="font-size:24px;">❤️</span>
    </h2>

    @if($products->isEmpty())
        <p style="text-align:center; color:#777; font-style:italic;">Chưa có sản phẩm nào trong danh sách yêu thích.</p>
    @else
        <div class="wishlist-grid">
            @foreach($products as $item)
                <div class="wishlist-card">
                    <!-- Ảnh sản phẩm -->
                    <a href="{{ route('product.show', $item->id) }}" class="wishlist-image">
                        <img src="{{ asset('assets/img/' . $item->duong_dan) }}" 
                             alt="{{ $item->ten_sp }}">
                    </a>

                    <!-- Nút ❤️ -->
                    <button class="wishlist-btn" data-id="{{ $item->id }}">
                        <i class="fa-solid fa-heart" style="color:red;"></i>
                    </button>

                    <!-- Tên & Giá -->
                    <div class="wishlist-info">
                        <h3>{{ $item->ten_sp }}</h3>
                        <p class="price">{{ number_format((float) str_replace(',', '', $item->gia), 0, ',', '.') }} ₫</p>

                        <a href="{{ route('product.show', $item->id) }}" class="wishlist-detail-btn">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Lắng nghe sự kiện click trên tất cả các nút có class .wishlist-btn
    document.querySelectorAll('.wishlist-btn').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.dataset.id;
            const cardElement = this.closest('.wishlist-card'); // Tìm đến card cha

            // Lấy token CSRF từ thẻ meta
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Gửi yêu cầu AJAX đến controller
            fetch("{{ route('wishlist.toggle') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // === BẮT ĐẦU PHẦN CẬP NHẬT ===
                // Hiển thị thông báo nhỏ (thay vì alert)
                const toast = document.createElement('div');
                toast.innerText = data.message;
                toast.style.position = 'fixed';
                toast.style.top = '20px';
                toast.style.left = '50%';
                toast.style.transform = 'translateX(-50%)';
                toast.style.background = data.status === 'added' ? '#d4edda' : '#f8d7da';
                toast.style.color = data.status === 'added' ? '#155724' : '#721c24';
                toast.style.padding = '15px 25px';
                toast.style.borderRadius = '8px';
                toast.style.zIndex = '10000';
                toast.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
                // === KẾT THÚC PHẦN CẬP NHẬT ===

                // Nếu sản phẩm đã bị xóa, hãy làm mờ và ẩn nó khỏi trang
                if (data.status === 'removed') {
                    if(cardElement) {
                        cardElement.style.transition = 'opacity 0.5s ease';
                        cardElement.style.opacity = '0';
                        setTimeout(() => cardElement.remove(), 500);
                    }
                }
            })
            .catch(error => {
                console.error('Lỗi khi gửi yêu cầu:', error);
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            });
        });
    });
});
</script>
@endpush

