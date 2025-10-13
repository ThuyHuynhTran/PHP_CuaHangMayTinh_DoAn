<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Hiển thị trang đăng nhập
     */
    public function showLogin()
    {
        // ⚠️ Nếu view của bạn nằm ở resources/views/login.blade.php
        // hãy để return view('login');
        // Nếu nằm trong thư mục auth/, để view('auth.login');
        return view('auth.login');
    }

    /**
     * Hiển thị trang đăng ký
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Xử lý đăng ký
     */
    public function register(Request $request): RedirectResponse
    {
        // ✅ Validate input
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // ✅ Lưu user mới
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']), // tốt hơn bcrypt()
        ]);

        // ✅ Có thể login luôn nếu bạn muốn:
        // Auth::login($user);

        return redirect()->route('login')->with('status', 'Đăng ký thành công! Hãy đăng nhập.');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request): RedirectResponse
    {
        // ✅ validate form input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // ✅ kiểm tra thông tin đăng nhập
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // ✅ phân quyền (nếu có cột role trong bảng users)
            if ($user->role === 'admin') {
                return redirect()->intended('/admin');
            }

            return redirect()->intended(route('home'));

        }

        // ❌ sai email hoặc mật khẩu
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    /**
     * Đăng xuất
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Bạn đã đăng xuất thành công!');
    }
}
