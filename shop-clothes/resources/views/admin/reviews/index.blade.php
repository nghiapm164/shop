@extends('layouts.admin')

@section('title', 'Quản lý đánh giá - Admin')
@section('page-title', 'Quản lý đánh giá')

@section('content')
<div class="space-y-5">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-gray-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center mb-2">
                    <i class="fas fa-star text-gray-500 text-sm"></i>
                </div>
                <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Tổng đánh giá</p>
                <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ $stats['total'] }}</p>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-green-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center mb-2">
                    <i class="fas fa-check-circle text-green-500 text-sm"></i>
                </div>
                <p class="text-xs text-green-500 uppercase tracking-wider font-semibold">Đã duyệt</p>
                <p class="text-2xl font-extrabold text-green-600 mt-1">{{ $stats['approved'] }}</p>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-amber-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center mb-2">
                    <i class="fas fa-clock text-amber-500 text-sm"></i>
                </div>
                <p class="text-xs text-amber-500 uppercase tracking-wider font-semibold">Chờ duyệt</p>
                <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $stats['pending'] }}</p>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-yellow-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="w-9 h-9 rounded-xl bg-yellow-50 flex items-center justify-center mb-2">
                    <i class="fas fa-star text-yellow-500 text-sm"></i>
                </div>
                <p class="text-xs text-yellow-600 uppercase tracking-wider font-semibold">Đánh giá TB</p>
                <p class="text-2xl font-extrabold text-yellow-500 mt-1">
                    <i class="fas fa-star text-lg mr-1"></i>{{ $stats['avg_rating'] }}
                </p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-col lg:flex-row gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Tìm kiếm</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nội dung, người đánh giá, sản phẩm..."
                           class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Trạng thái</label>
                <select name="status" class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                    <option value="">Tất cả</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Số sao</label>
                <select name="rating" class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                    <option value="">Tất cả</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                    @endfor
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm font-semibold shadow-sm transition-all">
                    <i class="fas fa-filter mr-1"></i> Lọc
                </button>
                <a href="{{ route('admin.reviews.index') }}"
                   class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 text-sm font-semibold transition-all">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Reviews Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Người đánh giá</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                        <th class="text-center py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Số sao</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nội dung</th>
                        <th class="text-center py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày</th>
                        <th class="text-right py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($reviews as $review)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3 px-5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-xs font-bold text-gray-500">
                                        {{ strtoupper(mb_substr($review->user->name ?? 'N', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $review->user->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-400">{{ $review->user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-5">
                                <a href="{{ route('products.show', $review->product->slug ?? '#') }}"
                                   class="text-sm font-semibold text-red-600 hover:text-red-700 hover:underline"
                                   target="_blank">
                                    {{ $review->product->name ?? 'N/A' }}
                                    <i class="fas fa-external-link-alt text-[10px] ml-1 opacity-50"></i>
                                </a>
                            </td>
                            <td class="py-3 px-5 text-center">
                                <div class="inline-flex items-center gap-0.5 px-2 py-1 bg-yellow-50 rounded-lg">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star text-yellow-400 text-xs"></i>
                                        @else
                                            <i class="far fa-star text-gray-300 text-xs"></i>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                            <td class="py-3 px-5">
                                <p class="text-sm text-gray-600 line-clamp-2 max-w-xs">{{ $review->comment }}</p>
                            </td>
                            <td class="py-3 px-5 text-center">
                                @if($review->is_approved)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Đã duyệt
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Chờ duyệt
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-5 text-xs text-gray-500">
                                {{ $review->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-5">
                                <div class="flex items-center justify-end gap-1">
                                    @if(!$review->is_approved)
                                        <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                                    title="Duyệt đánh giá">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.reviews.reject', $review) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition-colors"
                                                    title="Ẩn đánh giá">
                                                <i class="fas fa-eye-slash text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Xóa">
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
                                        <i class="fas fa-star text-2xl text-gray-300"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Không tìm thấy đánh giá</p>
                                    <p class="text-xs text-gray-400 mt-1">Thử thay đổi bộ lọc tìm kiếm</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($reviews->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>
@endsection