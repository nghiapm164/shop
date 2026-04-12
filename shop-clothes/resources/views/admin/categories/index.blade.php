@extends('layouts.admin')

@section('page-title', 'Quản lý danh mục')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Danh mục sản phẩm</h1>
            <p class="text-sm text-gray-500 mt-1">Tạo và quản lý cấu trúc danh mục phục vụ thêm mới sản phẩm.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold">
            + Thêm danh mục
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Ảnh</th>
                        <th class="px-4 py-3 text-left">Tên danh mục</th>
                        <th class="px-4 py-3 text-left">Slug</th>
                        <th class="px-4 py-3 text-left">Danh mục cha</th>
                        <th class="px-4 py-3 text-left">Thứ tự</th>
                        <th class="px-4 py-3 text-left">Trạng thái</th>
                        <th class="px-4 py-3 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr class="border-t border-gray-100 hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <img
                                    src="{{ $category->image ? asset('storage/' . $category->image) : asset('images/placeholder.jpg') }}"
                                    alt="{{ $category->name }}"
                                    class="w-12 h-12 rounded-md object-cover border border-gray-200"
                                >
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $category->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $category->slug }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $category->parent?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $category->sort_order }}</td>
                            <td class="px-4 py-3">
                                @if ($category->is_active)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Kích hoạt</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Ẩn</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="px-3 py-1.5 border border-blue-200 bg-blue-50 text-blue-700 rounded-md">Sửa</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Xóa danh mục này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 border border-red-200 bg-red-50 text-red-700 rounded-md">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-gray-500">Chưa có danh mục nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-gray-100">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
