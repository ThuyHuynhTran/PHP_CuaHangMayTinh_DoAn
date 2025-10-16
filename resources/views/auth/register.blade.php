@extends('layouts.main')

@section('content')
<div class="auth-container"
     style="min-height: 80vh; display: flex; justify-content: center; align-items: center; background: linear-gradient(180deg, #e6f9ff, #ffffff);">

    <div class="auth-card"
         style="background: white; width: 400px; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">

        <h2 style="text-align: center; color: #0099cc; margin-bottom: 20px;">Đăng ký tài khoản</h2>

        {{-- ✅ Thông báo lỗi --}}
        @if ($errors->any())
            <div style="background:#ffe6e6; color:#cc0000; padding:10px 15px; border-radius:8px; margin-bottom:15px;">
                <ul style="margin:0; padding-left:15px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Họ và tên --}}
            <div class="form-group" style="margin-bottom: 15px;">
                <label>Họ và tên</label>
                <input type="text" name="name" required
                       value="{{ old('name') }}"
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; height: 42px; box-sizing: border-box;">
            </div>

            {{-- Email --}}
            <div class="form-group" style="margin-bottom: 15px;">
                <label>Email</label>
                <input type="email" name="email" required
                       value="{{ old('email') }}"
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; height: 42px; box-sizing: border-box;">
            </div>

            {{-- Mật khẩu --}}
            <div class="form-group" style="margin-bottom: 15px; position: relative;">
                <label>Mật khẩu</label>
                <input type="password" id="password" name="password" required
                       style="width: 100%; padding: 10px 40px 10px 10px; border: 1px solid #ddd; border-radius: 8px; height: 42px; box-sizing: border-box;">
                <span id="togglePassword" style="position: absolute; right: 12px; top: 65%; transform: translateY(-50%);
                      cursor: pointer; color: #888; font-size: 16px; transition: color 0.3s;">
                    <i class="fa fa-eye" id="eyeIcon1"></i>
                </span>
                <small id="passwordHelp" style="color:#cc0000; display:none;">
                    Mật khẩu phải có ít nhất 8 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt.
                </small>
            </div>

            {{-- Xác nhận mật khẩu --}}
            <div class="form-group" style="margin-bottom: 15px; position: relative;">
                <label>Xác nhận mật khẩu</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       style="width: 100%; padding: 10px 40px 10px 10px; border: 1px solid #ddd; border-radius: 8px; height: 42px; box-sizing: border-box;">
                <span id="togglePasswordConfirm" style="position: absolute; right: 12px; top: 65%; transform: translateY(-50%);
                      cursor: pointer; color: #888; font-size: 16px; transition: color 0.3s;">
                    <i class="fa fa-eye" id="eyeIcon2"></i>
                </span>
            </div>

            {{-- Nút đăng ký --}}
            <button type="submit"
                    style="width: 100%; padding: 10px; background-color: #0099cc; color: white; border: none; border-radius: 8px; font-weight: bold;">
                Đăng ký
            </button>
        </form>

        {{-- Liên kết đăng nhập --}}
        <p style="text-align: center; margin-top: 20px;">
            Đã có tài khoản?
            <a href="{{ route('login') }}" style="color: #007bff; text-decoration: none;">Đăng nhập</a>
        </p>
    </div>
</div>

{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

{{-- ✅ Script kiểm tra độ mạnh mật khẩu + toggle password --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passInput = document.getElementById('password');
    const passConfirmInput = document.getElementById('password_confirmation');
    const helpText = document.getElementById('passwordHelp');

    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const eyeIcon1 = document.getElementById('eyeIcon1');
    const eyeIcon2 = document.getElementById('eyeIcon2');

    // ✅ Kiểm tra độ mạnh mật khẩu
    passInput.addEventListener('input', function() {
        const pass = this.value;
        const isValid = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(pass);
        helpText.style.display = pass.length > 0 && !isValid ? 'block' : 'none';
    });

    // ✅ Toggle cho ô "Mật khẩu"
    togglePassword.addEventListener('click', function() {
        const isHidden = passInput.getAttribute('type') === 'password';
        passInput.setAttribute('type', isHidden ? 'text' : 'password');
        eyeIcon1.classList.toggle('fa-eye');
        eyeIcon1.classList.toggle('fa-eye-slash');
    });

    // ✅ Toggle cho ô "Xác nhận mật khẩu"
    togglePasswordConfirm.addEventListener('click', function() {
        const isHidden = passConfirmInput.getAttribute('type') === 'password';
        passConfirmInput.setAttribute('type', isHidden ? 'text' : 'password');
        eyeIcon2.classList.toggle('fa-eye');
        eyeIcon2.classList.toggle('fa-eye-slash');
    });

    // ✅ Hiệu ứng hover
    [togglePassword, togglePasswordConfirm].forEach(icon => {
        icon.addEventListener('mouseenter', () => icon.style.color = '#0099cc');
        icon.addEventListener('mouseleave', () => icon.style.color = '#888');
    });
});
</script>
@endsection
