@extends('layouts.main')

@section('content')
<div class="auth-container" style="min-height: 80vh; display: flex; justify-content: center; align-items: center; background: linear-gradient(180deg, #e6f9ff, #ffffff);">
    <div class="auth-card" style="background: white; width: 400px; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #0099cc; margin-bottom: 20px;">Đăng ký tài khoản</h2>

        <!-- ✅ Hiển thị thông báo lỗi -->
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

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Họ và tên</label>
                <input type="text" name="name" class="form-control" required
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;"
                       value="{{ old('name') }}">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;"
                       value="{{ old('email') }}">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Mật khẩu</label>
                <input type="password" id="password" name="password" class="form-control" required
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                <small id="passwordHelp" style="color:#cc0000; display:none;">Mật khẩu phải có ít nhất 8 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt.</small>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="form-control" required
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <button type="submit"
                    style="width: 100%; padding: 10px; background-color: #0099cc; color: white; border: none; border-radius: 8px;">
                Đăng ký
            </button>
        </form>

        <p style="text-align: center; margin-top: 20px;">
            Đã có tài khoản?
            <a href="{{ route('login') }}" style="color: #007bff; text-decoration: none;">Đăng nhập</a>
        </p>
    </div>
</div>

<!-- ✅ Script kiểm tra độ mạnh mật khẩu -->
<script>
document.getElementById('password').addEventListener('input', function() {
    const pass = this.value;
    const helpText = document.getElementById('passwordHelp');

    // Regex kiểm tra: ít nhất 8 ký tự, có chữ hoa, thường, số, ký tự đặc biệt
    const isValid = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(pass);

    if (pass.length > 0 && !isValid) {
        helpText.style.display = 'block';
    } else {
        helpText.style.display = 'none';
    }
});
</script>
@endsection
