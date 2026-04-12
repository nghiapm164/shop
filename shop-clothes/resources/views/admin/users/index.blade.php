@extends('layouts.admin')

@section('page-title', $title)

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
            <p class="text-sm text-gray-500 mt-1">Theo dõi trạng thái tài khoản, xác minh email và phân quyền truy cập.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2 border border-indigo-200 bg-indigo-50 rounded-lg text-sm text-indigo-700 hover:bg-indigo-100">Xem nhật ký hoạt động</a>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Đặt lại bộ lọc</a>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <p class="text-xs text-gray-500 uppercase">Tổng tài khoản</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="bg-white border border-green-200 rounded-xl p-4">
            <p class="text-xs text-green-700 uppercase">Đang hoạt động</p>
            <p class="text-2xl font-bold text-green-700 mt-1">{{ number_format($stats['active']) }}</p>
        </div>
        <div class="bg-white border border-red-200 rounded-xl p-4">
            <p class="text-xs text-red-700 uppercase">Đang khóa</p>
            <p class="text-2xl font-bold text-red-700 mt-1">{{ number_format($stats['inactive']) }}</p>
        </div>
        <div class="bg-white border border-blue-200 rounded-xl p-4">
            <p class="text-xs text-blue-700 uppercase">Đã xác minh email</p>
            <p class="text-2xl font-bold text-blue-700 mt-1">{{ number_format($stats['verified']) }}</p>
        </div>
    </div>

    <form method="GET" class="bg-white border border-gray-200 rounded-xl p-4 grid grid-cols-1 lg:grid-cols-5 gap-3">
        <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Tìm theo tên, email, SĐT..." class="lg:col-span-2 border border-gray-300 rounded-lg px-3 py-2.5">

        <select name="role" class="border border-gray-300 rounded-lg px-3 py-2.5">
            <option value="">Mọi vai trò</option>
            @foreach ($roleOptions as $roleKey => $label)
                <option value="{{ $roleKey }}" @selected($filters['role'] === $roleKey)>{{ $label }}</option>
            @endforeach
        </select>

        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2.5">
            <option value="">Mọi trạng thái</option>
            <option value="active" @selected($filters['status'] === 'active')>Đang hoạt động</option>
            <option value="inactive" @selected($filters['status'] === 'inactive')>Đang khóa</option>
        </select>

        <select name="verified" class="border border-gray-300 rounded-lg px-3 py-2.5">
            <option value="">Mọi xác minh</option>
            <option value="yes" @selected($filters['verified'] === 'yes')>Đã xác minh</option>
            <option value="no" @selected($filters['verified'] === 'no')>Chưa xác minh</option>
        </select>

        <div class="lg:col-span-5 flex items-center justify-end gap-2">
            <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-black">Lọc dữ liệu</button>
        </div>
    </form>

    <form id="bulk-users-form" method="POST" action="{{ route('admin.users.bulk-action') }}" class="bg-white border border-gray-200 rounded-xl p-4 flex flex-wrap items-center gap-3">
        @csrf
        <select name="bulk_action" class="border border-gray-300 rounded-lg px-3 py-2.5" required>
            <option value="">Chọn thao tác hàng loạt</option>
            <option value="activate">Mở khóa tài khoản</option>
            <option value="deactivate">Khóa tài khoản</option>
            <option value="delete">Xóa tài khoản</option>
        </select>
        <button type="submit" class="px-4 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-black" onclick="return confirm('Xác nhận thực hiện thao tác hàng loạt?')">Áp dụng</button>
        <span class="text-sm text-gray-500">Mẹo: dùng checkbox để chọn nhiều tài khoản trước khi áp dụng.</span>
    </form>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input id="select-all-users" type="checkbox" class="rounded border-gray-300">
                        </th>
                        <th class="px-4 py-3 text-left">Người dùng</th>
                        <th class="px-4 py-3 text-left">Vai trò</th>
                        <th class="px-4 py-3 text-left">SĐT</th>
                        <th class="px-4 py-3 text-left">Xác minh</th>
                        <th class="px-4 py-3 text-left">Trạng thái</th>
                        <th class="px-4 py-3 text-left">Lần đăng nhập cuối</th>
                        <th class="px-4 py-3 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-t border-gray-100 hover:bg-gray-50">
                            <td class="px-4 py-3 align-top">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-select-checkbox rounded border-gray-300" form="bulk-users-form">
                            </td>
                            <td class="px-4 py-3 align-top">
                                <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $user->email }}</p>
                            </td>
                            <td class="px-4 py-3 align-top">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">{{ $roleOptions[$user->role] ?? $user->role }}</span>
                            </td>
                            <td class="px-4 py-3 align-top text-gray-700">{{ $user->phone ?: '-' }}</td>
                            <td class="px-4 py-3 align-top">
                                @if ($user->email_verified_at)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Đã xác minh</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Chưa xác minh</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-top">
                                @if ($user->is_active)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Hoạt động</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Đã khóa</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-top text-gray-600">{{ $user->last_login_at?->format('d/m/Y H:i') ?: 'Chưa có' }}</td>
                            <td class="px-4 py-3 align-top">
                                <div class="flex justify-end flex-wrap gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="px-2.5 py-1.5 text-xs bg-blue-50 text-blue-700 rounded border border-blue-200 hover:bg-blue-100">Sửa</a>

                                    <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-2.5 py-1.5 text-xs bg-yellow-50 text-yellow-700 rounded border border-yellow-200 hover:bg-yellow-100">
                                            {{ $user->is_active ? 'Khóa' : 'Mở khóa' }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.users.send-reset-link', $user) }}">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1.5 text-xs bg-indigo-50 text-indigo-700 rounded border border-indigo-200 hover:bg-indigo-100">Gửi reset mật khẩu</button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Xóa tài khoản này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 text-xs bg-red-50 text-red-700 rounded border border-red-200 hover:bg-red-100">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-gray-500">Không có dữ liệu người dùng phù hợp.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select-all-users');
        const itemCheckboxes = document.querySelectorAll('.user-select-checkbox');

        if (!selectAll) return;

        selectAll.addEventListener('change', function () {
            itemCheckboxes.forEach(function (checkbox) {
                checkbox.checked = selectAll.checked;
            });
        });
    });
</script>
@endsection
