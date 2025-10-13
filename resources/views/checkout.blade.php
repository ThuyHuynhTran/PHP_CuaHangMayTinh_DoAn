@extends('layouts.main')

@section('content')
<main class="main-content" style="background-color: #fafafa; min-height: 100vh; padding: 40px 0; font-family: 'Segoe UI', Arial, sans-serif;">
    <div class="container" style="max-width: 1000px; margin: auto; background: #fff; border-radius: 12px; box-shadow: 0 3px 8px rgba(0,0,0,0.08); overflow: hidden;">

        <!-- HEADER -->
        <div style="background-color: #c21b1b; color: #fff; padding: 15px 25px; font-size: 22px; font-weight: bold;">
            <i class="fas fa-credit-card"></i> Thanh toán
        </div>

        <!-- ĐỊA CHỈ NHẬN HÀNG -->
        <div style="padding: 25px; border-bottom: 1px solid #eee;">
         

        <!-- Hiển thị địa chỉ -->
<div style="padding: 25px; border-bottom: 1px solid #eee;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h4 style="color: #c21b1b; font-weight: bold; margin: 0;">Địa chỉ nhận hàng</h4>
        <a href="#" id="changeAddressBtn" style="color: #c21b1b; font-weight: 600; text-decoration: none;">Thay đổi</a>
    </div>

    @php
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

<!-- POPUP thêm địa chỉ -->
<div id="addressModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
    <div style="background:white; padding:30px; border-radius:10px; width:400px; position:relative;">
        <h3 style="color:#c21b1b;">Thêm địa chỉ mới</h3>
        <form action="{{ route('address.store') }}" method="POST">
            @csrf
            <label>Họ và tên:</label>
            <input type="text" name="fullname" required style="width:100%; margin-bottom:10px; padding:8px; border:1px solid #ccc; border-radius:6px;">

            <label>Số điện thoại:</label>
            <input type="text" name="phone" required style="width:100%; margin-bottom:10px; padding:8px; border:1px solid #ccc; border-radius:6px;">

            <label>Địa chỉ:</label>
            <textarea name="address" rows="3" required style="width:100%; margin-bottom:10px; padding:8px; border:1px solid #ccc; border-radius:6px;"></textarea>

            <label>
                <input type="checkbox" name="is_default"> Đặt làm địa chỉ mặc định
            </label>

            <div style="text-align:right; margin-top:15px;">
                <button type="button" id="cancelBtn" style="background:#ccc; border:none; padding:8px 15px; border-radius:6px; margin-right:8px;">Hủy</button>
                <button type="submit" style="background:#c21b1b; color:white; border:none; padding:8px 15px; border-radius:6px;">Lưu</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('changeAddressBtn').addEventListener('click', function(e){
    e.preventDefault();
    document.getElementById('addressModal').style.display = 'flex';
});
document.getElementById('cancelBtn').addEventListener('click', function(){
    document.getElementById('addressModal').style.display = 'none';
});
</script>

        <!-- SẢN PHẨM -->
        <div style="padding: 25px; border-bottom: 1px solid #eee;">
            <h4 style="color: #c21b1b; font-weight: bold; margin-bottom: 20px;">Sản phẩm</h4>

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
                <hr style="margin: 12px 0; border-top: 1px dashed #ccc;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 600;">Tổng tiền:</span>
                    <span style="color: #c21b1b; font-weight: bold; font-size: 16px;">
                        {{ number_format((float) preg_replace('/[^\d.]/', '', $item->product->gia) * $item->quantity, 0, ',', '.') }}₫
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- PHƯƠNG THỨC THANH TOÁN -->
        <div style="padding: 25px; border-bottom: 1px solid #eee;">
            <h4 style="color: #c21b1b; font-weight: bold; margin-bottom: 15px;">Phương thức thanh toán</h4>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="radio" name="payment_method" value="cod" checked> Thanh toán khi nhận hàng (COD)
                </label>
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="radio" name="payment_method" value="bank"> Chuyển khoản ngân hàng (VCB, MBB, TPBank)
                </label>
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="radio" name="payment_method" value="momo"> Ví MoMo / ZaloPay
                </label>
            </div>
        </div>

        <!-- CHI TIẾT THANH TOÁN -->
        <div style="padding: 25px; border-bottom: 1px solid #eee;">
            <h4 style="color: #c21b1b; font-weight: bold; margin-bottom: 15px;">Chi tiết thanh toán</h4>
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span>Tổng tiền hàng</span>
                <span>{{ number_format($total, 0, ',', '.') }}₫</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Phí vận chuyển</span>
                <span style="color: green;">Miễn phí</span>
            </div>
            <hr style="margin: 15px 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 18px;">
                <strong>Tổng thanh toán</strong>
                <strong style="color: #c21b1b;">{{ number_format($total, 0, ',', '.') }}₫</strong>
            </div>
        </div>

        <!-- NÚT ĐẶT HÀNG -->
        <div style="padding: 25px; text-align: right;">
            <button style="background-color: #c21b1b; color: white; border: none; padding: 14px 35px; border-radius: 8px; font-size: 18px; font-weight: bold; cursor: pointer; transition: 0.3s;">
                Đặt hàng
            </button>
        </div>

    </div>
</main>
@endsection
