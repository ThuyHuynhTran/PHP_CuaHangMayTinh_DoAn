<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Cập nhật thông tin người dùng
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // ✅ Bước 1: Validate các thông tin cơ bản (không liên quan mật khẩu)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:15'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'birthday' => ['nullable', 'date'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'email.unique' => 'Email này đã được sử dụng.',
            'avatar.image' => 'Ảnh đại diện phải là tệp hình ảnh (jpg, jpeg, png).',
            'avatar.max' => 'Kích thước ảnh tối đa là 2MB.',
        ]);

        // ✅ Bước 2: Nếu có nhập mật khẩu thì mới kiểm tra thêm độ mạnh + xác nhận
        if ($request->filled('password')) {
            $request->validate([
                'password' => [
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
                ],
            ], [
                'password.regex' => 'Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            ]);
        }

        // ✅ Upload ảnh đại diện (nếu có chọn ảnh mới)
        if ($request->hasFile('avatar')) {
            try {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $avatarPath;
            } catch (\Exception $e) {
                return back()->withErrors(['avatar' => 'Không thể tải ảnh lên: ' . $e->getMessage()]);
            }
        }

        // ✅ Cập nhật thông tin cơ bản
        $user->fill([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
        ]);

        // ✅ Nếu có mật khẩu mới → mã hóa và lưu
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // ✅ Lưu thay đổi
        $user->save();

        // ✅ Trả về thông báo thành công
        return redirect()
            ->route('profile.edit')
            ->with('success', 'Cập nhật thông tin thành công!');
    }
}
