@extends('layouts.main')

@section('content')
<div class="auth-container" style="min-height: 80vh; display: flex; justify-content: center; align-items: center; background: linear-gradient(180deg, #e6f9ff, #ffffff);">
    <div class="auth-card" style="background: white; width: 400px; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #0099cc; margin-bottom: 20px;">Đăng nhập</h2>

        {{-- Hiển thị thông báo lỗi --}}
        @if ($errors->any())
            <div style="color: red; margin-bottom: 10px;">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Form đăng nhập --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Email</label>
                <input type="email" name="email" required autofocus
                       class="form-control"
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Mật khẩu</label>
                <input type="password" name="password" required
                       class="form-control"
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <button type="submit"
                    style="width: 100%; padding: 10px; background-color: #0099cc; color: white; border: none; border-radius: 8px;">
                Đăng nhập
            </button>
        </form>

        <p style="text-align: center; margin-top: 20px;">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" style="color: #007bff; text-decoration: none;">Đăng ký ngay</a>
        </p>
    </div>
</div>
@endsection
