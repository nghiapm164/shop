@extends('layouts.app')

@section('meta_title', 'Địa chỉ giao hàng - SportWear Shop')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-red-500">Trang chủ</a>
            <span>/</span>
            <a href="{{ route('profile.edit') }}" class="hover:text-red-500">Tài khoản</a>
            <span>/</span>
            <span class="font-semibold text-slate-900">Địa chỉ giao hàng</span>
        </nav>
    </div>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Địa chỉ giao hàng</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý địa chỉ giao hàng của bạn</p>
        </div>
    </div>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="mb-4 flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Add New Address --}}
    <div class="mb-6" x-data="{ open: false }">
        <button @click="open = !open"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition">
            <i class="fas fa-plus text-xs"></i> Thêm địa chỉ mới
        </button>

        <div x-show="open" x-transition class="mt-4 bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Thêm địa chỉ mới</h3>
            <form action="{{ route('addresses.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Họ và tên *</label>
                        <input type="text" name="recipient_name" required value="{{ old('recipient_name') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition">
                        @error('recipient_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Số điện thoại *</label>
                        <input type="tel" name="phone" required value="{{ old('phone') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition"
                            placeholder="0xxxxxxxxx">
                        @error('phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Địa chỉ *</label>
                        <textarea name="address_detail" required rows="2"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition"
                            placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố">{{ old('address_detail') }}</textarea>
                        @error('address_detail')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_default" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                            <span class="text-sm text-gray-700">Đặt làm địa chỉ mặc định</span>
                        </label>
                    </div>
                </div>
                <div class="flex gap-3 mt-4">
                    <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                        Lưu địa chỉ
                    </button>
                    <button type="button" @click="open = false" class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">
                        Hủy
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Address List --}}
    @if($addresses->count() > 0)
        <div class="space-y-4">
            @foreach ($addresses as $address)
                <div x-data="{ editing: false }" class="bg-white border border-gray-200 rounded-xl p-5 {{ $address->is_default ? 'border-red-400' : '' }}">
                    <div x-show="!editing">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="font-semibold text-gray-900">{{ $address->recipient_name }}</p>
                                    @if ($address->is_default)
                                        <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded font-medium">Mặc định</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $address->phone }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $address->full_address }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 mt-3 pt-3 border-t border-gray-100">
                            @if (!$address->is_default)
                                <form action="{{ route('addresses.setDefault', $address) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Đặt mặc định</button>
                                </form>
                            @endif
                            <button type="button" @click="editing = true" class="text-xs text-gray-600 hover:text-gray-700 font-medium">✏️ Sửa</button>
                            <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Xóa địa chỉ này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:text-red-700 font-medium">🗑️ Xóa</button>
                            </form>
                        </div>
                    </div>

                    {{-- Edit Form --}}
                    <div x-show="editing" x-transition>
                        <form action="{{ route('addresses.update', $address) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên *</label>
                                    <input type="text" name="recipient_name" required value="{{ $address->recipient_name }}"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại *</label>
                                    <input type="tel" name="phone" required value="{{ $address->phone }}"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ *</label>
                                    <textarea name="address_detail" required rows="2"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400">{{ $address->address_detail }}</textarea>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_default" value="1" @checked($address->is_default) class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="text-sm text-gray-700">Đặt làm địa chỉ mặc định</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex gap-3 mt-4">
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition">Cập nhật</button>
                                <button type="button" @click="editing = false" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">Hủy</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if($addresses->hasPages())
            <div class="mt-6">{{ $addresses->links() }}</div>
        @endif
    @else
        <div class="text-center py-16 bg-white rounded-xl border border-gray-200">
            <i class="fas fa-map-marker-alt text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 font-medium">Chưa có địa chỉ nào</p>
            <p class="text-sm text-gray-400 mt-1">Thêm địa chỉ để thuận tiện cho việc đặt hàng</p>
        </div>
    @endif
</div>
@endsection
