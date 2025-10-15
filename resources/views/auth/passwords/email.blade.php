@extends('layouts.main')

@section('content')
<div class="auth-container"
     style="min-height: 80vh; display: flex; justify-content: center; align-items: center; background: linear-gradient(180deg, #e6f9ff, #ffffff);">

    <div class="auth-card"
         style="background: white; width: 400px; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">

        <h2 style="text-align: center; color: #0099cc; margin-bottom: 20px;">Quên mật khẩu</h2>

        @if (session('status'))
            <div style="background: #e6ffe6; padding: 10px; border-left: 5px solid #28a745; margin-bottom: 15px;">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="background: #ffe6e6; padding: 10px; border-left: 5px solid #ff4d4d; margin-bottom: 15px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="email">Nhập email của bạn:</label>
                <input id="email" type="email" name="email" required
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;">
            </div>

            <button type="submit"
                    style="width: 100%; padding: 12px; background-color: #0099cc; color: white; border: none; border-radius: 8px; font-weight: bold;">
                Gửi liên kết đặt lại mật khẩu
            </button>

            <p style="text-align: center; margin-top: 15px;">
                <a href="{{ route('login') }}" style="color: #007bff;">← Quay lại đăng nhập</a>
            </p>
        </form>
    </div>
</div>
@endsection
