@extends('layouts.admin')

@section('title', 'Chỉnh sửa tài khoản - Admin')
@section('page-title', 'Chỉnh sửa tài khoản')

@section('content')
<div class="space-y-5">
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.users.index') }}"
           class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-900">Chỉnh sửa tài khoản</h2>
            <p class="text-xs text-gray-400 mt-0.5">Cập nhật thông tin cho {{ $user->name }}</p>
        </div>
    </div>

    {{-- User Profile Card --}}
    <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl p-6 text-white">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-2xl font-bold">
                {{ strtoupper(mb_substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="text-lg font-bold">{{ $user->name }}</h3>
                <p class="text-gray-300 text-sm">{{ $user->email }}</p>
                <div class="flex items-center gap-2 mt-2">
                    @php
                        $roleColors = [
                            'super_admin' => 'bg-red-500/20 text-red-300',
                            'admin' => 'bg-purple-500/20 text-purple-300',
                            'staff' => 'bg-blue-500/20 text-blue-300',
                            'customer' => 'bg-white/10 text-gray-300',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $roleColors[$user->role] ?? 'bg-white/10 text-gray-300' }}">
                        {{ $roleOptions[$user->role] ?? $user->role }}
                    </span>
                    @if ($user->is_active)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-500/20 text-green-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span> Hoạt động
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-500/20 text-red-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Đã khóa
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Form --}}
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Personal Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-bold text-gray-900 mb-5">
                <i class="fas fa-user mr-2 text-red-500"></i>Thông tin cá nhân
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Họ tên</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all" required>
                    @error('name')
                        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all" required>
                    @error('email')
                        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all">
                    @error('phone')
                        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Địa chỉ</label>
                    <input type="text" name="address" value="{{ old('address', $user->address) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all">
                    @error('address')
                        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Permissions & Status --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-bold text-gray-900 mb-5">
                <i class="fas fa-shield-alt mr-2 text-red-500"></i>Phân quyền & Trạng thái
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Vai trò</label>
                    <select name="role" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all" required>
                        @foreach ($roleOptions as $key => $label)
                            <option value="{{ $key }}" @selected(old('role', $user->role) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Trạng thái</label>
                    <select name="is_active" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all" required>
                        <option value="1" @selected((string) old('is_active', (int) $user->is_active) === '1')>Đang hoạt động</option>
                        <option value="0" @selected((string) old('is_active', (int) $user->is_active) === '0')>Đã khóa</option>
                    </select>
                    @error('is_active')
                        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Xác minh email</label>
                    <select name="email_verified_at" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all">
                        <option value="" @selected(!old('email_verified_at', $user->email_verified_at))>Chưa xác minh</option>
                        <option value="{{ now()->toDateTimeString() }}" @selected(old('email_verified_at', $user->email_verified_at))>Đã xác minh</option>
                    </select>
                    @error('email_verified_at')
                        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.users.index') }}"
               class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Hủy
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm font-semibold shadow-sm transition-all">
                <i class="fas fa-save mr-1"></i> Lưu cập nhật
            </button>
        </div>
    </form>

    {{-- Password Management (OUTSIDE main form to avoid nested forms) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6" x-data="{ showPasswordForm: false }">
        <h3 class="text-base font-bold text-gray-900 mb-5">
            <i class="fas fa-key mr-2 text-red-500"></i>Quản lý mật khẩu
        </h3>

        <p class="text-sm text-gray-500 mb-4">Tạo mật khẩu tạm thời cho khách hàng. Cần nhập mật khẩu admin để xác nhận.</p>

        @if (session('temp_password') && session('temp_password_user') === $user->email)
            <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-4">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5"></i>
                    <div class="flex-1">
                        <p class="font-semibold text-amber-800 text-sm">Mật khẩu tạm thời (chỉ hiển thị 1 lần)</p>
                        <div class="mt-2 flex items-center gap-2">
                            <code class="px-3 py-2 bg-white border border-amber-300 rounded-lg text-lg font-mono font-bold text-amber-900 select-all" id="temp-password">{{ session('temp_password') }}</code>
                            <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('temp-password').textContent).then(() => this.innerHTML = '<i class=\'fas fa-check text-green-600\'></i>')" class="px-3 py-2 border border-amber-300 rounded-lg text-sm hover:bg-amber-100 transition">
                                <i class="fas fa-copy text-amber-600"></i>
                            </button>
                        </div>
                        <p class="text-xs text-amber-600 mt-2">⚠️ Hãy ghi nhớ hoặc sao chép mật khẩu này. Khách hàng sẽ dùng mật khẩu này để đăng nhập và nên đổi mật khẩu ngay sau đó.</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error') && str_contains(session('error'), 'Mật khẩu admin'))
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
            </div>
        @endif

        <button type="button" @click="showPasswordForm = !showPasswordForm"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-500 text-white rounded-xl hover:bg-amber-600 text-sm font-semibold shadow-sm transition-all">
            <i class="fas fa-key"></i>
            <span x-text="showPasswordForm ? 'Hủy' : 'Tạo mật khẩu tạm thời'"></span>
        </button>

        <div x-show="showPasswordForm" x-transition class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
            <form action="{{ route('admin.users.reveal-password', $user) }}" method="POST" class="flex items-end gap-3">
                @csrf
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Mật khẩu admin hiện tại</label>
                    <input type="password" name="admin_password" required placeholder="Nhập mật khẩu admin để xác nhận"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400 transition-all">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-amber-500 text-white rounded-xl hover:bg-amber-600 text-sm font-semibold shadow-sm transition-all whitespace-nowrap">
                    <i class="fas fa-unlock mr-1"></i> Xác nhận
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
