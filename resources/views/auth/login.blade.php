@extends('layouts.main')

@section('content')
<div class="auth-container"
     style="min-height: 80vh; display: flex; justify-content: center; align-items: center; background: linear-gradient(180deg, #e6f9ff, #ffffff);">

    <div class="auth-card"
         style="background: white; width: 400px; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">

        <h2 style="text-align: center; color: #0099cc; margin-bottom: 20px;">Đăng nhập</h2>

        {{-- Thông báo lỗi --}}
        @if ($errors->any())
            <div style="background-color: #ffe6e6; border-left: 5px solid #ff4d4d; padding: 10px 15px; margin-bottom: 15px; border-radius: 5px; color: #cc0000;">
                <strong>Lỗi:</strong> {{ $errors->first() }}
            </div>
        @endif

        {{-- Thông báo trạng thái --}}
        @if (session('status'))
            <div style="background-color: #e6ffe6; border-left: 5px solid #00cc44; padding: 10px 15px; margin-bottom: 15px; border-radius: 5px; color: #006622;">
                {{ session('status') }}
            </div>
        @endif

        {{-- Form đăng nhập --}}
        <form id="login-form" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="email" style="font-weight: 600;">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="form-control"
                       placeholder="Nhập email của bạn"
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; font-size: 14px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="password" style="font-weight: 600;">Mật khẩu</label>
                <input id="password" type="password" name="password" required
                       class="form-control"
                       placeholder="Nhập mật khẩu"
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; font-size: 14px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px; display: flex; align-items: center; justify-content: space-between;">
                <label style="display: flex; align-items: center; font-size: 14px;">
                    <input type="checkbox" name="remember" style="margin-right: 5px;"> Ghi nhớ đăng nhập
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size: 14px; color: #007bff; text-decoration: none;">
                        Quên mật khẩu?
                    </a>
                @endif
            </div>

            <button id="login-btn" type="submit"
                    style="width: 100%; padding: 12px; background-color: #0099cc; color: white; font-weight: bold; border: none; border-radius: 8px; cursor: pointer; transition: background-color 0.3s;">
                Đăng nhập
            </button>

            <button type="reset"
                    style="width: 100%; padding: 12px; background-color: #f0f0f0; color: #333; border: none; border-radius: 8px; cursor: pointer; margin-top: 10px;">
                Nhập lại
            </button>
        </form>

        <p style="text-align: center; margin-top: 20px; font-size: 14px;">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" style="color: #007bff; text-decoration: none; font-weight: 600;">Đăng ký ngay</a>
        </p>
    </div>
</div>

{{-- Script tránh lỗi JS addEventListener --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('login-btn');
        if (btn) {
            btn.addEventListener('click', function() {
                console.log("Đang xử lý đăng nhập...");
            });
        }
    });
</script>
@endsection
