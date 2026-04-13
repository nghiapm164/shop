@extends('layouts.admin')

@section('page-title', 'Chỉnh sửa sản phẩm')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Chỉnh sửa sản phẩm</h1>
        <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Quay lại danh sách</a>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        @include('admin.products._form')

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Hủy</a>
            <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700">Cập nhật sản phẩm</button>
        </div>
    </form>
</div>
@endsection
