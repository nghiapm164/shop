@extends('layouts.admin')

@section('title', 'Quản lý mã giảm giá - Admin')
@section('page-title', 'Mã giảm giá')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Mã giảm giá</h2>
            <p class="text-sm text-gray-400 mt-0.5">Tạo và quản lý các chương trình khuyến mãi.</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm font-semibold shadow-sm transition-all">
            <i class="fas fa-plus text-xs"></i> Thêm mã
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="py-3 px-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mã</th>
                        <th class="py-3 px-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Loại</th>
                        <th class="py-3 px-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Giá trị</th>
                        <th class="py-3 px-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Thời gian</th>
                        <th class="py-3 px-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lượt dùng</th>
                        <th class="py-3 px-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="py-3 px-5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($coupons as $coupon)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3 px-5">
                                <span class="inline-flex items-center px-3 py-1 bg-gray-900 text-white rounded-lg text-xs font-bold tracking-wider">
                                    {{ $coupon->code }}
                                </span>
                            </td>
                            <td class="py-3 px-5 text-sm text-gray-600">{{ $coupon->type_label }}</td>
                            <td class="py-3 px-5 text-sm font-bold text-red-600">{{ $coupon->discount_text }}</td>
                            <td class="py-3 px-5 text-xs text-gray-500">
                                {{ $coupon->start_date->format('d/m/Y') }} - {{ $coupon->end_date->format('d/m/Y') }}
                            </td>
                            <td class="py-3 px-5">
                                <span class="text-sm font-semibold text-gray-700">{{ $coupon->used_count }}</span>
                                @if($coupon->usage_limit)
                                    <span class="text-xs text-gray-400">/ {{ $coupon->usage_limit }}</span>
                                @else
                                    <span class="text-xs text-gray-400">/ ∞</span>
                                @endif
                            </td>
                            <td class="py-3 px-5">
                                @if ($coupon->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Kích hoạt
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Ẩn
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Chỉnh sửa">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Xóa mã giảm giá này?')">
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
                                        <i class="fas fa-ticket-alt text-2xl text-gray-300"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Chưa có mã giảm giá</p>
                                    <a href="{{ route('admin.coupons.create') }}" class="text-xs text-red-600 mt-1 hover:underline">Tạo mã mới</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($coupons->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
</div>
@endsection