@extends('layouts.admin')

@section('title', 'Thêm thương hiệu - Admin')
@section('page-title', 'Thêm thương hiệu')

@section('content')
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.brands.index') }}"
           class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-900">Thêm thương hiệu mới</h2>
            <p class="text-xs text-gray-400 mt-0.5">Tạo thương hiệu sản phẩm mới</p>
        </div>
    </div>

    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @include('admin.brands._form')

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.brands.index') }}" 
               class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Hủy
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm font-semibold shadow-sm transition-all">
                <i class="fas fa-save mr-1"></i> Lưu thương hiệu
            </button>
        </div>
    </form>
</div>
@endsection