@extends('layouts.admin')

@section('page-title', 'Quản lý đánh giá')

@section('content')
<div class="space-y-6">
    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Tổng đánh giá</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Đã duyệt</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Chờ duyệt</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Đánh giá trung bình</p>
            <p class="text-2xl font-bold text-yellow-500">
                <i class="fas fa-star mr-1"></i>{{ $stats['avg_rating'] }}
            </p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nội dung, tên người đánh giá, sản phẩm..."
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                <select name="status" class="rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                    <option value="">Tất cả</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Số sao</label>
                <select name="rating" class="rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                    <option value="">Tất cả</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                    @endfor
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-semibold">
                    <i class="fas fa-search mr-1"></i> Lọc
                </button>
                <a href="{{ route('admin.reviews.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-semibold">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Reviews Table --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Người đánh giá</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Sản phẩm</th>
                        <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Số sao</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Nội dung</th>
                        <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Trạng thái</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Ngày</th>
                        <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $review)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4">
                                <p class="text-sm font-semibold text-gray-900">{{ $review->user->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $review->user->email ?? '' }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <a href="{{ route('products.show', $review->product->slug ?? '#') }}"
                                   class="text-sm font-semibold text-red-600 hover:underline"
                                   target="_blank">
                                    {{ $review->product->name ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star text-yellow-400 text-xs"></i>
                                        @else
                                            <i class="far fa-star text-gray-300 text-xs"></i>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <p class="text-sm text-gray-700 line-clamp-2 max-w-xs">{{ $review->comment }}</p>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($review->is_approved)
                                    <span class="px-2 py-1 text-xs font-semibold text-green-600 bg-green-100 rounded-full">
                                        <i class="fas fa-check mr-1"></i>Đã duyệt
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold text-amber-600 bg-amber-100 rounded-full">
                                        <i class="fas fa-clock mr-1"></i>Chờ duyệt
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600">
                                {{ $review->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center justify-end gap-1">
                                    @if(!$review->is_approved)
                                        <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="px-2 py-1 text-xs font-semibold text-green-600 bg-green-50 rounded hover:bg-green-100 transition-colors"
                                                    title="Duyệt">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.reviews.reject', $review) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="px-2 py-1 text-xs font-semibold text-amber-600 bg-amber-50 rounded hover:bg-amber-100 transition-colors"
                                                    title="Ẩn">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-2 py-1 text-xs font-semibold text-red-600 bg-red-50 rounded hover:bg-red-100 transition-colors"
                                                title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <i class="fas fa-star text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 mt-2">Không tìm thấy đánh giá nào</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($reviews->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>
@endsection