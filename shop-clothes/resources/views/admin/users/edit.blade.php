@extends('layouts.admin')

@section('page-title', 'Chỉnh sửa tài khoản')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Chỉnh sửa tài khoản</h1>
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Quay lại danh sách</a>
    </div>

    @if (session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Họ tên</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5" required>
                @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5" required>
                @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Số điện thoại</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
                @error('phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Vai trò</label>
                <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2.5" required>
                    @foreach ($roleOptions as $key => $label)
                        <option value="{{ $key }}" @selected(old('role', $user->role) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('role')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Trạng thái tài khoản</label>
                <select name="is_active" class="w-full border border-gray-300 rounded-lg px-3 py-2.5" required>
                    <option value="1" @selected((string) old('is_active', (int) $user->is_active) === '1')>Đang hoạt động</option>
                    <option value="0" @selected((string) old('is_active', (int) $user->is_active) === '0')>Đã khóa</option>
                </select>
                @error('is_active')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Xác minh email</label>
                <select name="email_verified_at" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
                    <option value="" @selected(!old('email_verified_at', $user->email_verified_at))>Chưa xác minh</option>
                    <option value="{{ now()->toDateTimeString() }}" @selected(old('email_verified_at', $user->email_verified_at))>Đã xác minh</option>
                </select>
                @error('email_verified_at')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Địa chỉ</label>
                <textarea name="address" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">{{ old('address', $user->address) }}</textarea>
                @error('address')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Hủy</a>
            <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700">Lưu cập nhật</button>
        </div>
    </form>
</div>
@endsection
