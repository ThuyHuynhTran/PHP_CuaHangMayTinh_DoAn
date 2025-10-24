@extends('layouts.main')

@section('content')
<main class="main-content" style="background-color: #fafafa; min-height: 100vh; padding: 40px 0; font-family: 'Segoe UI', Arial, sans-serif;">
    <div class="container" style="max-width: 1000px; margin: auto; background: #fff; border-radius: 12px; box-shadow: 0 3px 8px rgba(0,0,0,0.08); overflow: hidden;">

        <!-- HEADER -->
        <div style="background-color: #c21b1b; color: #fff; padding: 15px 25px; font-size: 22px; font-weight: bold;">
            <i class="fas fa-credit-card"></i> Thanh toán
        </div>

        <!-- ĐỊA CHỈ NHẬN HÀNG -->
        <div id="addressSection" style="padding: 25px 25px 25px 40px; border-bottom: 1px solid #eee;">
           <div style="text-align: center; position: relative;">
    <h4 style="color: #c21b1b; font-weight: bold; margin: 0; display: inline-block;">Địa chỉ nhận hàng</h4>
    <a href="#" id="changeAddressBtn"
       style="color: #c21b1b; font-weight: 600; text-decoration: none; position: absolute; right: 25px; top: 0;">
        Thay đổi
    </a>
</div>


            @php
                $addresses = $addresses ?? collect();
                $default = $addresses->where('is_default', true)->first();
            @endphp

            @if($default)
                <div style="margin-top: 10px; text-align: center;">
                    <p style="font-weight: bold; margin: 0;">
                        {{ $default->fullname }} <span style="color: #666;">| {{ $default->phone }}</span>
                    </p>
                    <p style="color: #444; margin-top: 5px;">{{ $default->address }}</p>
                </div>
            @else
                <p style="text-align: center; color: #999;">Chưa có địa chỉ nào</p>
            @endif
        </div>

        <!-- POPUP THÊM ĐỊA CHỈ -->
        <div id="addressModal"
             style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
                    background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:1000;">
            <div style="background:white; padding:25px; border-radius:12px; width:480px; position:relative;
                        max-height:85vh; overflow-y:auto; box-shadow:0 4px 12px rgba(0,0,0,0.25);">

                <h3 style="color:#c21b1b; text-align:center; margin-bottom:20px; font-weight:bold;">
                    Chọn / thêm địa chỉ
                </h3>

                <div id="addressList">
                    @foreach($addresses as $address)
                        <label class="address-item"
                               data-id="{{ $address->id }}"
                               data-fullname="{{ $address->fullname }}"
                               data-phone="{{ $address->phone }}"
                               data-address="{{ $address->address }}"
                               style="display:block; border:1px solid #ddd; border-radius:10px; padding:12px 15px;
                                      margin-bottom:10px; cursor:pointer; transition:0.2s;
                                      {{ $address->is_default ? 'border-color:#c21b1b; background:#fff6f6;' : '' }}">
                            <input type="radio" name="selected_address" value="{{ $address->id }}"
                                   {{ $address->is_default ? 'checked' : '' }} style="margin-right:8px;">
                            <strong>{{ $address->fullname }}</strong> | {{ $address->phone }}
                            <p style="margin:5px 0; color:#444;">{{ $address->address }}</p>
                        </label>
                    @endforeach
                </div>

                <form id="addressForm" action="{{ route('address.store') }}" method="POST"
                      style="display:none; margin-top:20px;">
                    @csrf
                    <label style="font-weight:600;">Họ và tên:</label>
                    <input type="text" name="fullname" required
                           style="width:100%; margin-bottom:10px; padding:8px; border:1px solid #ccc; border-radius:6px;">
                    <label style="font-weight:600;">Số điện thoại:</label>
                    <input type="text" name="phone" required
                           style="width:100%; margin-bottom:10px; padding:8px; border:1px solid #ccc; border-radius:6px;">
                    <label style="font-weight:600;">Địa chỉ:</label>
                    <textarea name="address" rows="3" required
                              style="width:100%; margin-bottom:10px; padding:8px; border:1px solid #ccc; border-radius:6px;"></textarea>
                    <button type="submit"
                            style="background:#c21b1b; color:white; border:none; padding:8px 15px;
                                   border-radius:6px; font-weight:600;">Lưu</button>
                </form>

                <div style="display:flex; justify-content:space-between; margin-top:20px;">
                    <button id="addNewAddressBtn"
                            style="background:#0099cc; color:white; border:none; padding:10px 16px;
                                   border-radius:8px; font-weight:bold; flex:1; margin-right:10px;">
                        + Thêm địa chỉ mới
                    </button>
                    <button id="closeModalBtn"
                            style="background:#ccc; color:#222; border:none; padding:10px 16px;
                                   border-radius:8px; font-weight:bold; flex:1;">
                        Đóng
                    </button>
                </div>
            </div>
        </div>

        {{-- CÁC PHẦN SẢN PHẨM, KHUYẾN MÃI, THANH TOÁN... GIỮ NGUYÊN --}}
        <!-- SẢN PHẨM -->
        <div style="padding: 25px 25px 25px 40px; border-bottom: 1px solid #eee;">
            <h4 style="color: #c21b1b; font-weight: bold; margin-bottom: 20px;">Sản phẩm</h4>
            @if(isset($cartItems) && count($cartItems) > 0)
                @foreach($cartItems as $item)
                    <div style="border: 1px solid #ddd; border-radius: 10px; padding: 15px 20px; margin-bottom: 15px; background-color: #fff;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <img src="{{ asset('assets/img/' . $item->product->duong_dan) }}" alt="{{ $item->product->ten_sp }}" style="width: 80px; height: 80px; border-radius: 8px; object-fit: cover;">
                                <div>
                                    <p style="font-weight: 600; font-size: 16px; margin: 0;">{{ $item->product->ten_sp }}</p>
                                    <p style="color: #666; font-size: 14px;">Số lượng: x{{ $item->quantity }}</p>
                                </div>
                            </div>
                            <span style="color: #c21b1b; font-weight: bold; font-size: 16px;">
                                {{ number_format((float) preg_replace('/[^\d.]/', '', $item->product->gia) * $item->quantity, 0, ',', '.') }}₫
                            </span>
                        </div>
                    </div>
                @endforeach
            @elseif(isset($product))
                <div style="border: 1px solid #ddd; border-radius: 10px; padding: 15px 20px; background-color: #fff;">
                     <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <img src="{{ asset('assets/img/' . $product->duong_dan) }}" alt="{{ $product->ten_sp }}" style="width: 80px; height: 80px; border-radius: 8px; object-fit: cover;">
                            <div>
                                <p style="font-weight: 600; font-size: 16px; margin: 0;">{{ $product->ten_sp }}</p>
                                <p style="color: #666; font-size: 14px;">Số lượng: x1</p>
                            </div>
                        </div>
                        <span style="color: #c21b1b; font-weight: bold; font-size: 16px;">
                            {{ number_format((float) preg_replace('/[^\d.]/', '', $product->gia), 0, ',', '.') }}₫
                        </span>
                    </div>
                </div>
            @endif
        </div>

        <!-- KHUYẾN MÃI -->
        <div style="padding: 25px 25px 25px 40px; border-bottom: 1px solid #eee;">
            <h4 style="color: #c21b1b; font-weight: bold; margin-bottom: 15px;">Khuyến mãi</h4>
            @if(isset($activePromotions) && $activePromotions->isNotEmpty())
                <select id="promotionSelect" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 16px;">
                    <option value="" data-discount="0">Không sử dụng khuyến mãi</option>
                    @foreach($activePromotions as $promo)
                        <option value="{{ $promo->id }}" data-discount="{{ $promo->discount_percent }}">
                            {{ $promo->title }} (-{{ rtrim(rtrim(number_format($promo->discount_percent, 2), '0'), '.') }}%)
                        </option>
                    @endforeach
                </select>
            @else
                <p style="color: #666;">Hiện không có khuyến mãi nào hợp lệ.</p>
            @endif
        </div>

        <!-- PHƯƠNG THỨC THANH TOÁN -->
        <div style="padding: 25px 25px 25px 40px; border-bottom: 1px solid #eee;">
            <h4 style="color: #c21b1b; font-weight: bold; margin-bottom: 15px;">Phương thức thanh toán</h4>
            <div style="display: flex; flex-direction: column; gap: 10px; align-items: flex-start;">
                <label><input type="radio" name="payment_option" value="cod" checked> Thanh toán khi nhận hàng (COD)</label>
                <label><input type="radio" name="payment_option" value="bank"> Chuyển khoản ngân hàng</label>
                <label><input type="radio" name="payment_option" value="momo"> Ví MoMo / ZaloPay</label>
            </div>
        </div>
        
        <!-- QR HIỂN THỊ -->
        <div id="qrSection" style="display:none; text-align:center; padding: 25px;">
            <h4 style="color:#c21b1b; font-weight:bold;">Quét mã để thanh toán</h4>
            <img id="qrImage" src="" alt="QR Code" style="width:250px; height:250px; border:1px solid #ccc; border-radius:10px; margin-top:10px;">
            <p id="qrNote" style="color:#555; font-style:italic; margin-top:10px;"></p>
        </div>

        <!-- CHI TIẾT THANH TOÁN -->
        <div style="padding: 25px 25px 25px 40px; border-bottom: 1px solid #eee;">
            <h4 style="color: #c21b1b; font-weight: bold; margin-bottom: 15px;">Chi tiết thanh toán</h4>
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span>Tổng tiền hàng</span>
                <span id="subtotalValue">{{ number_format($total ?? 0, 0, ',', '.') }}₫</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span>Phí vận chuyển</span>
                <span style="color: green;">Miễn phí</span>
            </div>
            <div style="display: flex; justify-content: space-between; color: green;" id="discountRow" hidden>
                <span>Giảm giá</span>
                <span id="discountValue">0₫</span>
            </div>
            <hr style="margin: 15px 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 18px;">
                <strong>Tổng thanh toán</strong>
                <strong style="color: #c21b1b;" id="finalTotalValue">{{ number_format($total ?? 0, 0, ',', '.') }}₫</strong>
            </div>
        </div>

        <!-- NÚT ĐẶT HÀNG -->
       <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST">
            @csrf
            @if(isset($product))
                <input type="hidden" name="product_id" value="{{ $product->id }}">
            @endif
            <input type="hidden" name="payment_method" id="paymentMethod" value="cod">
            <input type="hidden" name="promotion_id" id="promotionIdInput" value="">
            <div style="padding: 25px; text-align: right;">
                <button type="submit" style="background-color: #c21b1b; color: white; border: none; padding: 14px 35px; border-radius: 8px; font-size: 18px; font-weight: bold; cursor: pointer;">
                    Đặt hàng
                </button>
            </div>
        </form>

        <!-- POPUP THÀNH CÔNG -->
        <div id="orderSuccessPopup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:2000;">
            <div style="background:white; border-radius:15px; padding:40px; width:380px; text-align:center; box-shadow:0 4px 10px rgba(0,0,0,0.2); animation: pop 0.3s ease;">
                <div style="font-size:60px; color:#22bb33;"><i class="fas fa-check-circle"></i></div>
                <h2 style="margin:10px 0; color:#22bb33;">Đặt hàng thành công!</h2>
                <p style="color:#555;">Đơn hàng của bạn đã được ghi nhận.</p>
                <div style="display:flex; justify-content:center; gap:15px; margin-top:25px;">
                    <a href="{{ route('home') }}" style="background:#c21b1b; color:white; padding:10px 18px; border-radius:8px; text-decoration:none; font-weight:bold;">Quay lại trang chủ</a>
                    <a href="{{ route('orders.history') }}" style="background:#0099cc; color:white; padding:10px 18px; border-radius:8px; text-decoration:none; font-weight:bold;">Xem chi tiết đơn hàng</a>
                </div>
            </div>
        </div>

        <style>
        @keyframes pop {
          0% { transform: scale(0.7); opacity: 0; }
          100% { transform: scale(1); opacity: 1; }
        }
        </style>
    </div>
</main>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // --- KHAI BÁO BIẾN ---
    const addressModal = document.getElementById("addressModal");
    const addressSection = document.getElementById("addressSection");
    const addressList = document.getElementById("addressList");
    const addressForm = document.getElementById("addressForm");
    const checkoutForm = document.getElementById('checkoutForm');
    const successPopup = document.getElementById('orderSuccessPopup');
    const qrSection = document.getElementById("qrSection");
    const paymentMethodInput = document.getElementById("paymentMethod");
    const paymentRadios = document.querySelectorAll('input[name="payment_option"]');
    const promotionSelect = document.getElementById('promotionSelect');
    const promotionIdInput = document.getElementById('promotionIdInput');

    // --- HÀM HỖ TRỢ ---
    function renderSelectedAddress(fullname, phone, address) {
        if (!addressSection) return;
        addressSection.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h4 style="color: #c21b1b; font-weight: bold; margin: 0;">Địa chỉ nhận hàng</h4>
                <a href="#" id="changeAddressBtn" style="color: #c21b1b; font-weight: 600; text-decoration: none;">Thay đổi</a>
            </div>
            <div style="margin-top: 10px; text-align: center;">
                <p style="font-weight: bold; margin: 0;">${fullname} <span style="color: #666;">| ${phone}</span></p>
                <p style="color: #444; margin-top: 5px;">${address}</p>
            </div>`;
        const newChangeBtn = document.getElementById("changeAddressBtn");
        if (newChangeBtn) {
            newChangeBtn.addEventListener("click", e => {
                e.preventDefault();
                if (addressModal) addressModal.style.display = "flex";
            });
        }
    }

    // --- LOGIC XỬ LÝ ĐỊA CHỈ ---
    if (addressModal) {
        const openBtn = document.getElementById("changeAddressBtn");
        const closeBtn = document.getElementById("closeModalBtn");
        const addNewBtn = document.getElementById("addNewAddressBtn");

        if(openBtn) { openBtn.addEventListener("click", e => { e.preventDefault(); addressModal.style.display = "flex"; }); }
        if(closeBtn) { closeBtn.addEventListener("click", () => { addressModal.style.display = "none"; }); }
        if(addNewBtn) { addNewBtn.addEventListener("click", () => { if(addressForm) addressForm.style.display = 'block'; }); }
        
        if (addressForm) {
            addressForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const csrfToken = this.querySelector('input[name="_token"]').value;
                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
                        body: formData
                    });
                    const data = await response.json();
                    
                    if (data.success && data.address) {
                        const addr = data.address;
                        renderSelectedAddress(addr.fullname, addr.phone, addr.address);
                        
                        // 🔹 BẮT ĐẦU SỬA LỖI 🔹
                        // Logic để cập nhật danh sách địa chỉ trong popup mà không cần tải lại trang
                        const newAddressElement = document.createElement('label');
                        newAddressElement.className = 'address-item';
                        // Gán data attributes cho item mới
                        newAddressElement.dataset.id = addr.id;
                        newAddressElement.dataset.fullname = addr.fullname;
                        newAddressElement.dataset.phone = addr.phone;
                        newAddressElement.dataset.address = addr.address;
                        // Tạo nội dung HTML cho item mới
                        newAddressElement.innerHTML = `
                            <input type="radio" name="selected_address" value="${addr.id}" style="margin-right:8px;">
                            <strong>${addr.fullname}</strong> | ${addr.phone}
                            <p style="margin:5px 0; color:#444;">${addr.address}</p>
                        `;

                        // Xóa style 'mặc định' khỏi tất cả các địa chỉ cũ trong popup
                        if(addressList) {
                            addressList.querySelectorAll('.address-item').forEach(item => {
                                item.style.borderColor = '#ddd';
                                item.style.backgroundColor = 'transparent';
                                const radio = item.querySelector('input[type="radio"]');
                                if(radio) radio.checked = false;
                            });
                        }
                        
                        // Thêm style 'mặc định' cho địa chỉ mới và check radio
                        newAddressElement.style.borderColor = '#c21b1b';
                        newAddressElement.style.backgroundColor = '#fff6f6';
                        const newRadio = newAddressElement.querySelector('input[type="radio"]');
if(newRadio) newRadio.checked = true;

                        // Thêm địa chỉ mới vào cuối danh sách trong popup
                        if(addressList) addressList.appendChild(newAddressElement);
                        // 🔹 KẾT THÚC SỬA LỖI 🔹

                        fetch("{{ route('address.setDefault') }}", {
                            method: "POST",
                            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}", "Content-Type": "application/json" },
                            body: JSON.stringify({ address_id: addr.id })
                        });
                        
                        this.reset();
                        this.style.display = 'none';
                        addressModal.style.display = 'none';
                    } else {
                        alert(data.message || 'Không thể thêm địa chỉ mới.');
                    }
                } catch (error) {
                    console.error('Lỗi khi thêm địa chỉ:', error);
                    alert('Lỗi kết nối máy chủ khi thêm địa chỉ.');
                }
            });
        }

        if (addressList) {
            addressList.addEventListener('change', (e) => {
                if (e.target.name === 'selected_address') {
                    const label = e.target.closest('.address-item');
                    if (!label) return;

                    const id = label.dataset.id;
                    const fullname = label.dataset.fullname;
                    const phone = label.dataset.phone;
                    const address = label.dataset.address;
                    
                    renderSelectedAddress(fullname, phone, address);
                    
                    fetch("{{ route('address.setDefault') }}", {
                        method: "POST",
                        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}", "Content-Type": "application/json" },
                        body: JSON.stringify({ address_id: id })
                    });
                    
                    addressModal.style.display = 'none';
                }
            });
        }
    }

    // --- LOGIC THANH TOÁN QR ---
    if (qrSection && paymentRadios.length > 0) {
        paymentRadios.forEach(radio => {
            radio.addEventListener("change", () => {
                const method = radio.value;
                if(paymentMethodInput) paymentMethodInput.value = method;
                if (method === "bank") {
                    qrSection.style.display = "block";
                    qrSection.querySelector('#qrImage').src = "{{ asset('assets/img/qr_bank.webp') }}";
                    qrSection.querySelector('#qrNote').textContent = "Quét mã QR để chuyển khoản ngân hàng.";
                } else if (method === "momo") {
                    qrSection.style.display = "block";
                    qrSection.querySelector('#qrImage').src = "{{ asset('assets/img/qr_momo.webp') }}";
                    qrSection.querySelector('#qrNote').textContent = "Quét mã QR để thanh toán qua Ví MoMo / ZaloPay.";
                } else {
                    qrSection.style.display = "none";
                }
            });
        });
    }

    // --- LOGIC XỬ LÝ KHUYẾN MÃI VÀ CẬP NHẬT GIÁ ---
    if (promotionSelect) {
        const discountRow = document.getElementById('discountRow');
        const discountValueEl = document.getElementById('discountValue');
        const finalTotalValueEl = document.getElementById('finalTotalValue');
        const subtotal = {{ $total ?? 0 }};

        promotionSelect.addEventListener('change', function() {
            const selectedOpt = this.options[this.selectedIndex];
            const discountPercent = parseFloat(selectedOpt.dataset.discount) || 0;
            if(promotionIdInput) promotionIdInput.value = selectedOpt.value;

            const discountAmount = subtotal * (discountPercent / 100);
            const finalTotal = subtotal - discountAmount;

            if (discountPercent > 0) {
                if(discountValueEl) discountValueEl.textContent = `- ${Math.round(discountAmount).toLocaleString('vi-VN')}₫`;
                if(finalTotalValueEl) finalTotalValueEl.textContent = `${Math.round(finalTotal).toLocaleString('vi-VN')}₫`;
                if(discountRow) discountRow.hidden = false;
            } else {
                if(discountValueEl) discountValueEl.textContent = '0₫';
                if(finalTotalValueEl) finalTotalValueEl.textContent = `${subtotal.toLocaleString('vi-VN')}₫`;
                if(discountRow) discountRow.hidden = true;
            }
        });
    }

    // --- LOGIC ĐẶT HÀNG AJAX ---
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': formData.get('_token')},
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    if(successPopup) successPopup.style.display = 'flex';
                } else {
                    alert('Có lỗi xảy ra: ' + (data.message || 'Vui lòng thử lại.'));
                }
            } catch (error) {
                console.error('Lỗi khi đặt hàng:', error);
                alert('Không thể kết nối đến máy chủ. Vui lòng kiểm tra lại đường truyền.');
            }
        });
    }
});
</script>

@endsection

