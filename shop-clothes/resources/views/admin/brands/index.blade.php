@extends('layouts.admin')

@section('title', 'Quản lý thương hiệu - Admin')
@section('page-title', 'Quản lý thương hiệu')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Thương hiệu</h2>
            <p class="text-sm text-gray-400 mt-0.5">Quản lý thương hiệu để gán khi thêm/sửa sản phẩm.</p>
        </div>
        <a href="{{ route('admin.brands.create') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm font-semibold shadow-sm transition-all">
            <i class="fas fa-plus text-xs"></i> Thêm thương hiệu
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="py-3 px-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Logo</th>
                        <th class="py-3 px-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tên thương hiệu</th>
                        <th class="py-3 px-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="py-3 px-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mô tả</th>
                        <th class="py-3 px-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="py-3 px-5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($brands as $brand)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3 px-5">
                                <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}"
                                     class="w-12 h-12 rounded-xl object-cover border border-gray-100 shadow-sm">
                            </td>
                            <td class="py-3 px-5 font-semibold text-gray-900">{{ $brand->name }}</td>
                            <td class="py-3 px-5 text-xs text-gray-400 font-mono">{{ $brand->slug }}</td>
                            <td class="py-3 px-5 text-sm text-gray-500 max-w-xs truncate">{{ $brand->description ?: '—' }}</td>
                            <td class="py-3 px-5">
                                @if ($brand->is_active)
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
                                    <a href="{{ route('admin.brands.edit', $brand) }}" 
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Chỉnh sửa">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" onsubmit="return confirm('Xóa thương hiệu này?')">
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
                            <td colspan="6" class="py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-3">
                                        <i class="fas fa-tags text-2xl text-gray-300"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Chưa có thương hiệu nào</p>
                                    <a href="{{ route('admin.brands.create') }}" class="text-xs text-red-600 mt-1 hover:underline">Thêm thương hiệu mới</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($brands->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $brands->links() }}
            </div>
        @endif
    </div>
</div>
@endsection