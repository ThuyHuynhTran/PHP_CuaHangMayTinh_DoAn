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
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h4 style="color: #c21b1b; font-weight: bold; margin: 0;">Địa chỉ nhận hàng</h4>
                <a href="#" id="changeAddressBtn" style="color: #c21b1b; font-weight: 600; text-decoration: none;">Thay đổi</a>
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
                <label class="address-item" data-id="{{ $address->id }}"
                       style="display:block; border:1px solid #ddd; border-radius:10px; padding:12px 15px;
                              margin-bottom:10px; cursor:pointer; transition:0.2s;
                              {{ $address->is_default ? 'border-color:#c21b1b; background:#fff6f6;' : '' }}">
                    <input type="radio" name="selected_address" value="{{ $address->id }}"
                           {{ $address->is_default ? 'checked' : '' }} style="margin-right:8px;">
                    <strong>{{ $address->fullname }}</strong> | {{ $address->phone }}
                    <p style="margin:5px 0; color:#444;">{{ $address->address }}</p>
                    @if($address->is_default)
                        
                    @endif
                </label>
            @endforeach
        </div>

        <!-- FORM thêm mới -->
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

        <!-- Nút hành động dưới cùng -->
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



        <!-- SẢN PHẨM -->
        <div style="padding: 25px 25px 25px 40px; border-bottom: 1px solid #eee;">
            <h4 style="color: #c21b1b; font-weight: bold; margin-bottom: 20px;">Sản phẩm</h4>

            @if(isset($cartItems) && count($cartItems) > 0)
                @foreach($cartItems as $item)
                    <div style="border: 1px solid #ddd; border-radius: 10px; padding: 15px 20px; margin-bottom: 15px; background-color: #fff;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <img src="{{ asset('assets/img/' . $item->product->duong_dan) }}" 
                                     alt="{{ $item->product->ten_sp }}" 
                                     style="width: 80px; height: 80px; border-radius: 8px; object-fit: cover;">
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
                            <img src="{{ asset('assets/img/' . $product->duong_dan) }}" 
                                 alt="{{ $product->ten_sp }}" 
                                 style="width: 80px; height: 80px; border-radius: 8px; object-fit: cover;">
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

        <!-- PHƯƠNG THỨC THANH TOÁN -->
        <div style="padding: 25px 25px 25px 40px; border-bottom: 1px solid #eee;">
            <h4 style="color: #c21b1b; font-weight: bold; margin-bottom: 15px;">Phương thức thanh toán</h4>
            <div style="display: flex; flex-direction: column; gap: 10px; align-items: flex-start;">
                <label><input type="radio" name="payment_option" value="cod" checked> Thanh toán khi nhận hàng (COD)</label>
                <label><input type="radio" name="payment_option" value="bank"> Chuyển khoản ngân hàng (VCB, MBB, TPBank)</label>
                <label><input type="radio" name="payment_option" value="momo"> Ví MoMo / ZaloPay</label>
            </div>
        </div>
        <!-- QR HIỂN THỊ KHI CHỌN NGÂN HÀNG / MOMO -->
<div id="qrSection" style="display:none; text-align:center; margin-top:25px; margin-bottom:25px;">
    <h4 style="color:#c21b1b; font-weight:bold;">Quét mã để thanh toán</h4>
    <img id="qrImage" src="" alt="QR Code"
         style="width:250px; height:250px; border:1px solid #ccc; border-radius:10px; margin-top:10px;">
    <p id="qrNote" style="color:#555; font-style:italic; margin-top:10px;"></p>
</div>


        <!-- CHI TIẾT THANH TOÁN -->
        <div style="padding: 25px 25px 25px 40px; border-bottom: 1px solid #eee;">
            <h4 style="color: #c21b1b; font-weight: bold; margin-bottom: 15px;">Chi tiết thanh toán</h4>
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span>Tổng tiền hàng</span>
                <span>{{ number_format($total ?? 0, 0, ',', '.') }}₫</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Phí vận chuyển</span>
                <span style="color: green;">Miễn phí</span>
            </div>
            <hr style="margin: 15px 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 18px;">
                <strong>Tổng thanh toán</strong>
                <strong style="color: #c21b1b;">{{ number_format($total ?? 0, 0, ',', '.') }}₫</strong>
            </div>
        </div>

        <!-- NÚT ĐẶT HÀNG -->
       <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST">
    @csrf
    @if(isset($product))
        <input type="hidden" name="product_id" value="{{ $product->id }}">
    @endif
    <input type="hidden" name="payment_method" id="paymentMethod" value="cod">

    <div style="padding: 25px; text-align: right;">
        <button type="submit"
                style="background-color: #c21b1b; color: white; border: none; padding: 14px 35px; border-radius: 8px; font-size: 18px; font-weight: bold; cursor: pointer;">
            Đặt hàng
        </button>
    </div>
</form>
<!-- POPUP THÀNH CÔNG -->
<div id="orderSuccessPopup"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:2000;">
    <div style="background:white; border-radius:15px; padding:40px; width:380px; text-align:center;
                box-shadow:0 4px 10px rgba(0,0,0,0.2); animation: pop 0.3s ease;">
        <div style="font-size:60px; color:#22bb33;">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2 style="margin:10px 0; color:#22bb33;">Đặt hàng thành công!</h2>
        <p style="color:#555;">Đơn hàng của bạn đã được ghi nhận.</p>

        <div style="display:flex; justify-content:center; gap:15px; margin-top:25px;">
            <a href="{{ route('home') }}"
               style="background:#c21b1b; color:white; padding:10px 18px; border-radius:8px;
                      text-decoration:none; font-weight:bold;">Quay lại trang chủ</a>

            <a href="{{ route('orders.history') }}"
               style="background:#0099cc; color:white; padding:10px 18px; border-radius:8px;
                      text-decoration:none; font-weight:bold;">Xem chi tiết đơn hàng</a>
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

{{-- SCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("addressModal");
    const openBtn = document.getElementById("changeAddressBtn");
    const closeBtn = document.getElementById("closeModalBtn");
    const addNewBtn = document.getElementById("addNewAddressBtn");
    const form = document.getElementById("addressForm");
    const addressList = document.getElementById("addressList");
    const addressSection = document.getElementById("addressSection");

    // helper: render lại khu vực hiển thị địa chỉ nhận hàng (giữ nguyên layout + nút "Thay đổi")
    function renderSelectedAddress(fullname, phone, address) {
        addressSection.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h4 style="color: #c21b1b; font-weight: bold; margin: 0;">Địa chỉ nhận hàng</h4>
                <a href="#" id="changeAddressBtn" style="color: #c21b1b; font-weight: 600; text-decoration: none;">Thay đổi</a>
            </div>
            <div style="margin-top: 10px; text-align: center;">
                <p style="font-weight: bold; margin: 0;">
                    ${fullname} <span style="color: #666;">| ${phone}</span>
                </p>
                <p style="color: #444; margin-top: 5px;">${address}</p>
            </div>
        `;
        // re-bind nút "Thay đổi" vì DOM đã thay
        const _openBtn = addressSection.querySelector("#changeAddressBtn");
        _openBtn.addEventListener("click", e => { e.preventDefault(); modal.style.display = "flex"; });
    }

    // mở/đóng modal
    openBtn.addEventListener("click", e => { e.preventDefault(); modal.style.display = "flex"; });
    closeBtn.addEventListener("click", () => modal.style.display = "none");

    // ẩn/hiện form thêm mới
    addNewBtn.addEventListener("click", () => {
        form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
    });

    // === 1) LƯU ĐỊA CHỈ MỚI (AJAX) -> đóng popup + hiển thị địa chỉ vừa thêm ===
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const csrfToken = this.querySelector('input[name="_token"]').value;

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                const a = data.address;

                // Thêm item mới vào danh sách (có radio và được chọn)
                const newItem = document.createElement('label');
                newItem.className = 'address-item';
                newItem.dataset.id = a.id;
                newItem.style.cssText = 'display:block; border:1px solid #ddd; border-radius:8px; padding:10px 15px; margin-bottom:10px; cursor:pointer;';
                newItem.innerHTML = `
                    <input type="radio" name="selected_address" value="${a.id}" checked style="margin-right:8px;">
                    <strong>${a.fullname}</strong> | ${a.phone}
                    <p style="margin:5px 0; color:#444;">${a.address}</p>
                `;
                // bỏ check các radio khác
                addressList.querySelectorAll('input[name="selected_address"]').forEach(r => r.checked = false);
                addressList.appendChild(newItem);

                // cập nhật khu vực hiển thị địa chỉ bên ngoài
                renderSelectedAddress(a.fullname, a.phone, a.address);

                // đặt mặc định trên backend (không chặn UI)
                fetch("{{ route('address.setDefault') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ address_id: a.id })
                }).catch(() => {});

                // reset form + đóng modal
                this.reset();
                this.style.display = 'none';
                modal.style.display = 'none';
            } else {
                alert('Không thể thêm địa chỉ mới.');
            }
        } catch (error) {
            console.error('Lỗi:', error);
            alert('Lỗi kết nối máy chủ.');
        }
    });

    // === 2) CHỌN ĐỊA CHỈ QUA RADIO: cập nhật hiển thị + set default + đóng popup ===
    addressList.addEventListener('change', (e) => {
        if (e.target && e.target.name === 'selected_address') {
            const label = e.target.closest('.address-item');
            const id = e.target.value;
            const fullname = label.querySelector('strong').textContent.trim();

            // lấy phone từ chuỗi " | phone"
            const text = label.textContent;
            // match sau dấu |
            const phoneMatch = text.match(/\|\s*([^\n]+)\n/);
            const phone = phoneMatch ? phoneMatch[1].trim() : '';

            const address = label.querySelector('p') ? label.querySelector('p').textContent.trim() : '';

            // cập nhật hiển thị bên ngoài
            renderSelectedAddress(fullname, phone, address);

            // gọi API đặt mặc định
            fetch("{{ route('address.setDefault') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ address_id: id })
            }).catch(() => {});

            // đóng modal
            modal.style.display = 'none';
        }
    });

    // (Giữ nguyên tính năng click cả block nếu bạn muốn, nhưng giờ radio là chính)
    document.querySelectorAll(".address-item").forEach(item => {
        item.addEventListener("click", (ev) => {
            // nếu click vào label ngoài radio, sẽ check radio bên trong
            const radio = item.querySelector('input[type="radio"]');
            if (ev.target !== radio) {
                radio.checked = true;
                radio.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const qrSection = document.getElementById("qrSection");
    const qrImage = document.getElementById("qrImage");
    const qrNote = document.getElementById("qrNote");
    const paymentRadios = document.querySelectorAll('input[name="payment_option"]');
    const paymentMethodInput = document.getElementById("paymentMethod");

    paymentRadios.forEach(radio => {
        radio.addEventListener("change", () => {
            const method = radio.value;
            paymentMethodInput.value = method; // cập nhật hidden input để gửi form

            if (method === "bank") {
                qrSection.style.display = "block";
                qrImage.src = "{{ asset('assets/img/qr_bank.webp') }}"; // đặt ảnh QR ngân hàng của bạn tại public/assets/img/
                qrNote.textContent = "Quét mã QR để chuyển khoản ngân hàng (VCB, MBB, TPBank).";
            } else if (method === "momo") {
                qrSection.style.display = "block";
                qrImage.src = "{{ asset('assets/img/qr_momo.webp') }}"; // đặt ảnh QR momo của bạn tại public/assets/img/
                qrNote.textContent = "Quét mã QR để thanh toán qua Ví MoMo / ZaloPay.";
            } else {
                qrSection.style.display = "none";
            }
        });
    });
});
</script>


<script>
document.addEventListener("DOMContentLoaded", () => {
    const checkoutForm = document.getElementById('checkoutForm');
    const popup = document.getElementById('orderSuccessPopup');

    checkoutForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const csrfToken = this.querySelector('input[name="_token"]').value;

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                popup.style.display = 'flex'; // Hiển thị popup
            } else {
                alert('Có lỗi xảy ra khi đặt hàng.');
            }
        } catch (error) {
            console.error('Lỗi:', error);
            alert('Không thể kết nối máy chủ.');
        }
    });
});
</script>

@endsection
