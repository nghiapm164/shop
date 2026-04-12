@php use App\Models\Banner; @endphp
@extends('layouts.admin')

@section('page-title', 'Quản lý banner')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Banner</h1>
            <p class="text-sm text-gray-500">Quản lý banner hiển thị ở các vị trí trên website.</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700">+ Thêm banner</a>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Ảnh</th>
                        <th class="px-4 py-3 text-left">Tiêu đề</th>
                        <th class="px-4 py-3 text-left">Vị trí</th>
                        <th class="px-4 py-3 text-left">Thứ tự</th>
                        <th class="px-4 py-3 text-left">Trạng thái</th>
                        <th class="px-4 py-3 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($banners as $banner)
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-3"><img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-24 h-14 object-cover rounded border border-gray-200"></td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-900">{{ $banner->title }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $banner->link ?: '-' }}</p>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ Banner::getPositionLabel($banner->position) }}</td>
                            <td class="px-4 py-3">{{ $banner->sort_order }}</td>
                            <td class="px-4 py-3">
                                @if ($banner->is_active)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Kích hoạt</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Ẩn</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.banners.edit', $banner) }}" class="px-3 py-1.5 border border-blue-200 bg-blue-50 text-blue-700 rounded-md">Sửa</a>
                                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('Xóa banner này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 border border-red-200 bg-red-50 text-red-700 rounded-md">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-gray-500">Chưa có banner nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100">{{ $banners->links() }}</div>
    </div>
</div>
@endsection
