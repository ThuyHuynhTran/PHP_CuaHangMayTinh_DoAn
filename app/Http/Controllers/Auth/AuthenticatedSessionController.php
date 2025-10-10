<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Hiển thị trang đăng nhập
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Xác thực người dùng
        $request->authenticate();
        $request->session()->regenerate();

        // 🔹 Kiểm tra quyền và điều hướng tương ứng
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->intended('/admin'); // Trang admin
        }

        if ($user->role === 'user') {
            return redirect()->intended('/'); // Trang chủ người dùng
        }

        // Nếu role chưa xác định
        Auth::logout();
        return redirect('/login')->withErrors([
            'email' => 'Tài khoản của bạn chưa được gán quyền truy cập.',
        ]);
    }

    /**
     * Đăng xuất người dùng
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 🔹 Sau khi đăng xuất → quay lại trang chủ
        return redirect('/');
    }
}
