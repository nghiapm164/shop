@extends('layouts.admin')

@section('page-title', 'Quản lý mã giảm giá')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mã giảm giá</h1>
            <p class="text-sm text-gray-500">Tạo và quản lý các chương trình khuyến mãi.</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700">+ Thêm mã</a>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Mã</th>
                        <th class="px-4 py-3 text-left">Loại</th>
                        <th class="px-4 py-3 text-left">Giá trị</th>
                        <th class="px-4 py-3 text-left">Thời gian</th>
                        <th class="px-4 py-3 text-left">Lượt dùng</th>
                        <th class="px-4 py-3 text-left">Trạng thái</th>
                        <th class="px-4 py-3 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($coupons as $coupon)
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-3 font-semibold">{{ $coupon->code }}</td>
                            <td class="px-4 py-3">{{ $coupon->type_label }}</td>
                            <td class="px-4 py-3">{{ $coupon->discount_text }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $coupon->start_date->format('d/m/Y') }} - {{ $coupon->end_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $coupon->used_count }}{{ $coupon->usage_limit ? '/' . $coupon->usage_limit : '' }}</td>
                            <td class="px-4 py-3">
                                @if ($coupon->is_active)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Kích hoạt</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Ẩn</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="px-3 py-1.5 border border-blue-200 bg-blue-50 text-blue-700 rounded-md">Sửa</a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Xóa mã giảm giá này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 border border-red-200 bg-red-50 text-red-700 rounded-md">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-10 text-center text-gray-500">Chưa có mã giảm giá.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100">{{ $coupons->links() }}</div>
    </div>
</div>
@endsection
