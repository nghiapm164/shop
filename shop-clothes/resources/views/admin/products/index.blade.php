@extends('layouts.admin')

@section('page-title', 'Quản lý sản phẩm')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Quản lý sản phẩm</h1>
            <p class="text-sm text-gray-500">Theo dõi, lọc và cập nhật danh mục sản phẩm thể thao nam.</p>
        </div>

        <a
            href="{{ route('admin.products.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700"
        >
            <span>+</span>
            <span>Thêm sản phẩm</span>
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <livewire:admin-product-table />
</div>
@endsection
