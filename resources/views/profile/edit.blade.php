@extends('layouts.main')

@section('title', 'Quản lý tài khoản')

@section('styles')
@endsection

@section('content')
    <div class="profile-container">
        <h2 class="profile-title">Quản lý tài khoản của bạn</h2>

       

        @if ($errors->any())
            <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 12px 20px; border-radius: 8px; margin-bottom: 25px; border: 1px solid #f5c6cb;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ✅ thêm input giả để chặn autofill -->
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="profile-form" autocomplete="off">
            @method('PATCH')
            @csrf

            <!-- input giả để ngăn trình duyệt tự động điền mật khẩu -->
            <input type="text" name="fakeusernameremembered" style="display:none">
            <input type="password" name="fakepasswordremembered" style="display:none">

            <!-- ✅ Upload avatar -->
            <div class="avatar-upload-section">
                <img id="avatarPreview"
                     src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('default-avatar.png') }}" 
                     width="120" height="120" class="rounded-circle"
                     style="object-fit: cover; border-radius: 50%;">
                <input type="file" name="avatar" id="avatarInput" class="form-control" accept="image/*">
            </div>

            <!-- Thông tin người dùng -->
            <div class="form-group">
                <label for="name">Tên</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}">
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
            </div>

            <div class="form-group">
                <label for="gender">Giới tính</label>
                <select id="gender" name="gender" class="form-select">
                    <option value="">--Chọn--</option>
                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                    <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Khác</option>
                </select>
            </div>

            <div class="form-group">
                <label for="birthday">Ngày sinh</label>
                <input type="date" id="birthday" name="birthday" class="form-control" value="{{ old('birthday', $user->birthday) }}">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
            </div>

            <!-- ✅ Mật khẩu mới -->
            <div class="form-group">
                <label for="password">Mật khẩu mới</label>
                <input type="password" id="password" name="password" class="form-control" autocomplete="new-password">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </form>
    </div>

    <!-- ✅ SCRIPT HIỂN THỊ ẢNH TRONG KHUNG -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('avatarInput');
        const preview = document.getElementById('avatarPreview');

        if (!input || !preview) return;

        input.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('avatarInput');
    const preview = document.getElementById('avatarPreview');

    if (!input || !preview) return;

    input.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;

                // ✅ Cập nhật avatar ở header nếu có
                const headerAvatar = document.getElementById('navUserAvatar');
                if (headerAvatar && headerAvatar.tagName.toLowerCase() === 'img') {
                    headerAvatar.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>

@endsection
