@extends('layouts.admin')

@section('title', $title . ' - Admin')
@section('page-title', $title)

@section('content')
<div class="space-y-5">
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $title }}</h2>
            <p class="text-sm text-gray-400 mt-0.5">Quản lý tài khoản, phân quyền và trạng thái hoạt động.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.audit-logs.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-50 text-indigo-700 rounded-xl text-sm font-semibold hover:bg-indigo-100 transition-colors">
                <i class="fas fa-history text-xs"></i> Nhật ký
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">
                <i class="fas fa-redo text-xs"></i> Đặt lại
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-gray-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center mb-2">
                    <i class="fas fa-users text-gray-500 text-sm"></i>
                </div>
                <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Tổng tài khoản</p>
                <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ number_format($stats['total']) }}</p>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-green-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center mb-2">
                    <i class="fas fa-check-circle text-green-500 text-sm"></i>
                </div>
                <p class="text-xs text-green-500 uppercase tracking-wider font-semibold">Đang hoạt động</p>
                <p class="text-2xl font-extrabold text-green-600 mt-1">{{ number_format($stats['active']) }}</p>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-red-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center mb-2">
                    <i class="fas fa-ban text-red-500 text-sm"></i>
                </div>
                <p class="text-xs text-red-500 uppercase tracking-wider font-semibold">Đang khóa</p>
                <p class="text-2xl font-extrabold text-red-600 mt-1">{{ number_format($stats['inactive']) }}</p>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center mb-2">
                    <i class="fas fa-envelope-open text-blue-500 text-sm"></i>
                </div>
                <p class="text-xs text-blue-500 uppercase tracking-wider font-semibold">Đã xác minh</p>
                <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ number_format($stats['verified']) }}</p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form method="GET" class="grid grid-cols-1 lg:grid-cols-5 gap-3">
            <div class="lg:col-span-2 relative">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Tìm theo tên, email, SĐT..." 
                       class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all">
            </div>

            <select name="role" class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                <option value="">Mọi vai trò</option>
                @foreach ($roleOptions as $roleKey => $label)
                    <option value="{{ $roleKey }}" @selected($filters['role'] === $roleKey)>{{ $label }}</option>
                @endforeach
            </select>

            <select name="status" class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                <option value="">Mọi trạng thái</option>
                <option value="active" @selected($filters['status'] === 'active')>Đang hoạt động</option>
                <option value="inactive" @selected($filters['status'] === 'inactive')>Đang khóa</option>
            </select>

            <select name="verified" class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                <option value="">Mọi xác minh</option>
                <option value="yes" @selected($filters['verified'] === 'yes')>Đã xác minh</option>
                <option value="no" @selected($filters['verified'] === 'no')>Chưa xác minh</option>
            </select>

            <div class="lg:col-span-5 flex items-center justify-end gap-2">
                <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm font-semibold shadow-sm transition-all">
                    <i class="fas fa-filter mr-1"></i> Lọc dữ liệu
                </button>
            </div>
        </form>
    </div>

    {{-- Bulk Actions --}}
    <form id="bulk-users-form" method="POST" action="{{ route('admin.users.bulk-action') }}" 
          class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-wrap items-center gap-3">
        @csrf
        <select name="bulk_action" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400" required>
            <option value="">Thao tác hàng loạt</option>
            <option value="activate">Mở khóa tài khoản</option>
            <option value="deactivate">Khóa tài khoản</option>
            <option value="delete">Xóa tài khoản</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-xl hover:bg-black text-sm font-medium transition-all" 
                onclick="return confirm('Xác nhận thực hiện thao tác hàng loạt?')">
            Áp dụng
        </button>
        <span class="text-xs text-gray-400 ml-auto">
            <i class="fas fa-info-circle mr-1"></i> Chọn checkbox để áp dụng hàng loạt
        </span>
    </form>

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="py-3 px-4 text-left">
                            <input id="select-all-users" type="checkbox" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        </th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Người dùng</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Vai trò</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Xác minh</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lần cuối</th>
                        <th class="py-3 px-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3 px-4">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" 
                                       class="user-select-checkbox rounded border-gray-300 text-red-600 focus:ring-red-500" form="bulk-users-form">
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-sm font-bold text-gray-500">
                                        {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                        @if($user->phone)
                                            <p class="text-xs text-gray-400"><i class="fas fa-phone mr-1"></i>{{ $user->phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    $roleColors = [
                                        'super_admin' => 'bg-red-50 text-red-700',
                                        'admin' => 'bg-purple-50 text-purple-700',
                                        'staff' => 'bg-blue-50 text-blue-700',
                                        'customer' => 'bg-gray-100 text-gray-600',
                                    ];
                                    $roleColor = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $roleColor }}">
                                    {{ $roleOptions[$user->role] ?? $user->role }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @if ($user->email_verified_at)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-700">
                                        <i class="fas fa-check-circle text-[10px]"></i> Đã xác minh
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700">
                                        <i class="fas fa-exclamation-circle text-[10px]"></i> Chưa
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if ($user->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Hoạt động
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Đã khóa
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-xs text-gray-500">
                                {{ $user->last_login_at?->format('d/m/Y H:i') ?: 'Chưa có' }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Chỉnh sửa">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>

                                    <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="p-2 {{ $user->is_active ? 'text-amber-500 hover:bg-amber-50' : 'text-green-500 hover:bg-green-50' }} rounded-lg transition-colors"
                                                title="{{ $user->is_active ? 'Khóa' : 'Mở khóa' }}">
                                            <i class="fas {{ $user->is_active ? 'fa-lock' : 'fa-unlock' }} text-xs"></i>
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.users.send-reset-link', $user) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-indigo-500 hover:bg-indigo-50 rounded-lg transition-colors" title="Gửi reset mật khẩu">
                                            <i class="fas fa-key text-xs"></i>
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Xóa tài khoản này?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-3">
                                        <i class="fas fa-users text-2xl text-gray-300"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Không tìm thấy người dùng</p>
                                    <p class="text-xs text-gray-400 mt-1">Thử thay đổi bộ lọc tìm kiếm</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
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