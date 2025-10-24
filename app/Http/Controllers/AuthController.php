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
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // ✅ Tạo tài khoản mới
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'is_locked' => false,
            'login_attempts' => 0,
        ]);

        return redirect()->route('login')
            ->with('status', 'Đăng ký thành công! Hãy đăng nhập.');
    }

    /**
     * Xử lý đăng nhập (có kiểm tra & khóa tài khoản)
     */
    public function login(Request $request): RedirectResponse
    {
        // ✅ validate form input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // ⚠️ Nếu user tồn tại nhưng bị khóa
        if ($user && $user->is_locked) {
            return back()->withErrors([
                'email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
            ])->onlyInput('email');
        }

        // ✅ kiểm tra thông tin đăng nhập
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // ✅ Reset số lần sai
            $user = Auth::user();
            $user->update(['login_attempts' => 0]);

            // ✅ phân quyền
            if ($user->role === 'admin') {
                return redirect()->intended('/admin');
            }

            return redirect()->intended(route('home'));
        }

        // ❌ Nếu sai mật khẩu
        if ($user) {
            $user->increment('login_attempts');

            // 🔒 Nếu sai quá 3 lần thì khóa tài khoản
            if ($user->login_attempts >= 3) {
                $user->update(['is_locked' => true]);
                return back()->withErrors([
                    'email' => 'Tài khoản của bạn đã bị khóa do nhập sai mật khẩu quá 3 lần.',
                ])->onlyInput('email');
            }
        }

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
