@extends('layouts.admin')

@section('page-title', 'Quản lý Flash Sale')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Flash Sale</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý các chương trình flash sale cho trang chủ.</p>
        </div>
        <a href="{{ route('admin.flash-sales.create') }}" class="px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold">+ Thêm flash sale</a>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Tiêu đề</th>
                        <th class="px-4 py-3 text-left">Sản phẩm</th>
                        <th class="px-4 py-3 text-left">Giá gốc</th>
                        <th class="px-4 py-3 text-left">Giá flash</th>
                        <th class="px-4 py-3 text-left">Thời gian</th>
                        <th class="px-4 py-3 text-left">Trạng thái</th>
                        <th class="px-4 py-3 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($flashSales as $flashSale)
                        @php
                            $product = $flashSale->product;
                            $basePrice = $product ? ($product->sale_price ?? $product->price) : 0;
                        @endphp
                        <tr class="border-t border-gray-100 hover:bg-gray-50">
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $flashSale->title }}</td>
                            <td class="px-4 py-3 text-gray-700">
                                @if ($product)
                                    <div class="font-semibold">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $product->sku }}</div>
                                @else
                                    <span class="text-gray-400">San pham da bi xoa</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ number_format($basePrice, 0) }}đ</td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-red-600">{{ number_format($flashSale->flash_price, 0) }}đ</div>
                                @if ($flashSale->discount_percent > 0)
                                    <div class="text-xs text-red-500">-{{ $flashSale->discount_percent }}%</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                <div>{{ $flashSale->start_at->format('d/m/Y H:i') }}</div>
                                <div>{{ $flashSale->end_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if (!$flashSale->is_active)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Tắt</span>
                                @elseif ($flashSale->is_running)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Đang chạy</span>
                                @elseif ($flashSale->is_upcoming)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Sắp diễn ra</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">Đã kết thúc</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.flash-sales.edit', $flashSale) }}" class="px-3 py-1.5 border border-blue-200 bg-blue-50 text-blue-700 rounded-md">Sửa</a>
                                    <form action="{{ route('admin.flash-sales.destroy', $flashSale) }}" method="POST" onsubmit="return confirm('Xóa flash sale này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 border border-red-200 bg-red-50 text-red-700 rounded-md">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-gray-500">Chưa có flash sale nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-gray-100">
            {{ $flashSales->links() }}
        </div>
    </div>
</div>
@endsection
