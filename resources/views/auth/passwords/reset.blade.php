@extends('layouts.main')

@section('content')
<div class="auth-container"
     style="min-height: 80vh; display: flex; justify-content: center; align-items: center; background: linear-gradient(180deg, #e6f9ff, #ffffff);">

    <div class="auth-card"
         style="background: white; width: 400px; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">

        <h2 style="text-align: center; color: #0099cc; margin-bottom: 20px;">Đặt lại mật khẩu</h2>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" required value="{{ old('email') }}"
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;">
            </div>

          <div class="form-group" style="margin-bottom: 15px;">
    <label for="password">Mật khẩu mới</label>
    <input id="password" type="password" name="password" required
           class="form-control"
           placeholder="Nhập mật khẩu mới"
           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;">

    {{-- ✅ Hiển thị lỗi mật khẩu nếu có --}}
    @error('password')
        <div style="color: red; font-size: 14px; margin-top: 5px;">
            {{ $message }}
        </div>
    @enderror
</div>


            <div class="form-group" style="margin-bottom: 15px;">
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;">
            </div>

            <button type="submit"
                    style="width: 100%; padding: 12px; background-color: #0099cc; color: white; border: none; border-radius: 8px; font-weight: bold;">
                Đặt lại mật khẩu
            </button>
        </form>
    </div>
</div>
@endsection
