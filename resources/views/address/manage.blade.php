@extends('layouts.main')

@section('content')
<main class="main-content" style="background-color:#fafafa; min-height:100vh; padding:40px 0; font-family:'Segoe UI', Arial;">
    <div class="container" style="max-width:800px; margin:auto; background:white; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
        
        <!-- HEADER -->
        <div style="background-color:#c21b1b; color:white; padding:15px 25px; border-radius:10px 10px 0 0; display:flex; align-items:center; justify-content:space-between;">
            <h2 style="font-weight:bold; font-size:20px; margin:0;">Chọn địa chỉ nhận hàng</h2>
            <a href="{{ route('checkout') }}" style="color:white; text-decoration:none;">← Quay lại</a>
        </div>

        <!-- Danh sách địa chỉ -->
        <div style="padding:25px;">
            @foreach($addresses as $address)
            <div style="border-bottom:1px solid #eee; padding:15px 0; display:flex; align-items:flex-start; justify-content:space-between;">
                <div style="display:flex; align-items:flex-start; gap:10px;">
                    <input type="radio" name="selected_address" value="{{ $address->id }}" 
                        {{ $address->is_default ? 'checked' : '' }}
                        style="margin-top:6px; width:18px; height:18px; cursor:pointer;">

                    <div>
                        <p style="margin:0; font-weight:bold;">{{ $address->fullname }}
                            <span style="color:#666;">| {{ $address->phone }}</span>
                        </p>
                        <p style="margin:5px 0; color:#555;">{{ $address->address }}</p>
                        @if($address->is_default)
                            <span style="color:#c21b1b; font-size:13px; font-weight:bold; border:1px solid #c21b1b; border-radius:5px; padding:2px 6px;">Mặc định</span>
                        @endif
                    </div>
                </div>
                <a href="#" style="color:#c21b1b; text-decoration:none;">Sửa</a>
            </div>
            @endforeach
        </div>

        <!-- Nút thêm địa chỉ mới -->
        <div style="text-align:center; padding:20px 0;">
            <a href="{{ route('address.create') }}" 
               style="color:#c21b1b; text-decoration:none; font-weight:600;">
                <i class="fas fa-plus-circle"></i> Thêm địa chỉ mới
            </a>
        </div>

        <!-- Nút xác nhận -->
        <div style="padding:20px; text-align:center; border-top:1px solid #eee;">
            <form id="chooseAddressForm" action="{{ route('checkout') }}" method="GET">
                <button type="submit" 
                        style="background-color:#c21b1b; color:white; padding:12px 40px; border:none; border-radius:8px; font-size:16px; font-weight:bold; cursor:pointer;">
                    Xác nhận địa chỉ
                </button>
            </form>
        </div>

    </div>
</main>

<script>
    // Khi chọn địa chỉ, gửi ajax để cập nhật "is_default"
    document.querySelectorAll('input[name="selected_address"]').forEach(radio => {
        radio.addEventListener('change', function () {
            fetch(`/address/set-default/${this.value}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(() => location.reload());
        });
    });
</script>
@endsection
