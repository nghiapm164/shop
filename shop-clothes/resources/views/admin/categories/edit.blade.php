@extends('layouts.admin')

@section('title', 'Sửa danh mục - Admin')
@section('page-title', 'Chỉnh sửa danh mục')

@section('content')
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.categories.index') }}"
           class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-900">Chỉnh sửa danh mục</h2>
            <p class="text-xs text-gray-400 mt-0.5">Cập nhật "{{ $category->name }}"</p>
        </div>
    </div>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')
        @include('admin.categories._form')

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.categories.index') }}" 
               class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Hủy
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm font-semibold shadow-sm transition-all">
                <i class="fas fa-save mr-1"></i> Cập nhật
            </button>
        </div>
    </form>
</div>
@endsection