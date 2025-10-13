@extends('layouts.main')

@section('content')
<main class="main-content" style="background-color: #eaf8ff; min-height: 100vh; padding: 40px 0;">
    <div class="container" style="max-width: 1000px; margin: auto; background: #fff; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); padding: 30px;">
        <h2 style="text-align: center; color: #d02424ff; margin-bottom: 30px; font-weight: bold;">
            <i class="fas fa-shopping-cart"></i> Giỏ hàng của bạn
        </h2>

        

        @if(empty($cartItems) || count($cartItems) === 0)
            <div style="text-align: center; color: #777; padding: 60px 0;">
                <i class="fas fa-box-open" style="font-size: 60px; color: #9de2ff;"></i>
                <p style="margin-top: 20px; font-size: 18px;">Giỏ hàng của bạn hiện đang trống.</p>
                <a href="{{ route('home') }}" 
                   style="margin-top: 20px; display: inline-block; background-color: #2d3ee0; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;">
                    Tiếp tục mua sắm
                </a>
            </div>
        @else

            <div style="
                display: grid;
                grid-template-columns: 0.5fr 3fr 1fr 1fr 1fr 0.5fr;
                background-color: #f69797ff;
                font-weight: bold;
                text-align: center;
                padding: 10px 0;
                border-radius: 8px;
                margin-bottom: 10px;
                color: black;
                
            ">
                <div></div>
                <div style="text-align: left; padding-left: 20px;">Sản phẩm</div>
                <div>Giá</div>
                <div>Số lượng</div>
                <div>Thành tiền</div>
                <div>Thao tác</div>
            </div>

            @foreach($cartItems as $cartItem)
            <div class="cart-item-row" style="
                display: grid;
                grid-template-columns: 0.5fr 3fr 1fr 1fr 1fr 0.5fr;
                align-items: center;
                padding: 15px 0;
                border-bottom: 1px solid #eee;
                text-align: center;
            ">
                <div>
                    <input type="checkbox" 
                           value="{{ $cartItem->id }}" 
                           class="cart-checkbox" 
                           style="width: 18px; height: 18px; cursor: pointer;">
                </div>

                <div style="display: flex; align-items: center; gap: 10px; justify-content: flex-start;">
                    <img src="{{ asset('assets/img/' . $cartItem->product->duong_dan) }}" 
                         alt="{{ $cartItem->product->ten_sp }}" 
                         style="width: 60px; height: 60px; border-radius: 8px; object-fit: cover;">
                    <span style="font-weight: 600;">{{ $cartItem->product->ten_sp }}</span>
                </div>

                <div>
                    {{ number_format((float) preg_replace('/[^\d.]/', '', $cartItem->product->gia)) }}₫
                </div>

                <div style="display: flex; justify-content: center; align-items: center; gap: 5px; width: 120px;">
                    <button type="button"
                            onclick="updateQuantity(this, -1)"
                            style="background-color: #e0e0e0; border: none; width: 30px; height: 30px; border-radius: 50%; font-size: 18px; cursor: pointer;">−</button>

                    <input type="text" 
                           value="{{ $cartItem->quantity }}" 
                           readonly 
                           style="width: 40px; text-align: center; border: 1px solid #ccc; border-radius: 6px; padding: 5px; font-weight: bold;">

                    <button type="button"
                            onclick="updateQuantity(this, 1)"
                            style="background-color: #c21b1b; color: white; border: none; width: 30px; height: 30px; border-radius: 50%; font-size: 18px; cursor: pointer;">+</button>
                </div>

                <div class="item-total" style="color: #c21b1b; font-weight: bold;">
                    {{ number_format((float) preg_replace('/[^\d.]/', '', $cartItem->product->gia) * $cartItem->quantity) }}₫
                </div>

                <div>
                    <form action="{{ route('cart.remove', $cartItem->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:none; border:none; color:red; cursor:pointer;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach

            <div style="text-align: right; margin-top: 20px;">
                <p style="font-size: 18px;">
                    Tổng thanh toán:
                    <strong id="selectedTotal" style="color: #0047ab;">0₫</strong>
                </p>
                
                <form action="{{ route('checkout.selected') }}" method="POST" id="checkoutForm" style="display: none;">
                    @csrf
                </form>

                <button type="button" id="checkoutButton"
                        style="background: #c21b1b; color: white; border: none; padding: 12px 25px; border-radius: 10px; cursor: pointer; font-size: 16px;">
                    Thanh toán
                </button>
            </div>
        @endif
    </div>
</main>

<script>
// Nút tăng/giảm số lượng (giữ nguyên)
function updateQuantity(button, change) {
    const input = button.parentElement.querySelector('input');
    let currentValue = parseInt(input.value);
    let newValue = currentValue + change;
    if (newValue < 1) newValue = 1;
    input.value = newValue;
}

// Cập nhật tổng tiền khi chọn checkbox
document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll('.cart-checkbox');
    const totalDisplay = document.getElementById('selectedTotal');
    const checkoutForm = document.getElementById('checkoutForm');
    const checkoutButton = document.getElementById('checkoutButton');

    // ✅ THAY ĐỔI 2: Thêm script tự động ẩn thông báo sau 5 giây
    const alertBox = document.querySelector('.auto-hide-alert');
    if (alertBox) {
        setTimeout(() => {
            // Thêm hiệu ứng mờ dần (fade out) cho đẹp
            alertBox.style.transition = 'opacity 0.5s ease';
            alertBox.style.opacity = '0';
            
            // Sau khi hiệu ứng kết thúc, ẩn hoàn toàn thẻ div để không chiếm không gian
            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 200); // Thời gian này khớp với thời gian của transition
            
        }, 2000); // 5000ms = 5 giây
    }
    // Kết thúc phần code mới

    checkboxes.forEach(chk => chk.addEventListener('change', updateTotal));

    function updateTotal() {
        let total = 0;
        checkboxes.forEach(chk => {
            if (chk.checked) {
                const row = chk.closest('.cart-item-row'); 
                const priceText = row.querySelector('.item-total').innerText.replace(/[^\d]/g, '');
                total += parseFloat(priceText);
            }
        });
        totalDisplay.textContent = new Intl.NumberFormat('vi-VN').format(total) + '₫';
    }

    checkoutButton.addEventListener('click', function (e) {
        const selectedCheckboxes = document.querySelectorAll('.cart-checkbox:checked');

        if (selectedCheckboxes.length === 0) {
            e.preventDefault();
            alert('⚠️ Vui lòng chọn sản phẩm trước khi thanh toán!');
            return; 
        }

        const csrfInput = checkoutForm.querySelector('input[name="_token"]').outerHTML;
        checkoutForm.innerHTML = csrfInput;
        
        selectedCheckboxes.forEach(chk => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'selected_products[]';
            hiddenInput.value = chk.value;
            checkoutForm.appendChild(hiddenInput);
        });

        checkoutForm.submit();
    });
});
</script>
@endsection