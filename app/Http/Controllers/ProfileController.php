<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    /**
     * Hiển thị form chỉnh sửa thông tin cá nhân.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Cập nhật thông tin cá nhân của người dùng.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // --- 1. Validate tất cả dữ liệu gửi lên ---
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:15'],
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other'])],
            'birthday' => ['nullable', 'date'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Giới hạn 2MB
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // --- 2. Xử lý upload ảnh đại diện ---
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu có
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            // Lưu ảnh mới và cập nhật đường dẫn
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        // --- 3. Cập nhật các thông tin khác ---
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->phone = $validatedData['phone'];
        $user->gender = $validatedData['gender'];
        $user->birthday = $validatedData['birthday'];

        // --- 4. Cập nhật mật khẩu (chỉ khi người dùng nhập mật khẩu mới) ---
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        // --- 5. Lưu tất cả thay đổi ---
        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Cập nhật tài khoản thành công!');
    }

    /**
     * Xóa tài khoản người dùng.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        
        // Xóa ảnh đại diện khỏi storage trước khi xóa user
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}