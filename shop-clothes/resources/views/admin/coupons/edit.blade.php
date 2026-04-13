@extends('layouts.admin')

@section('page-title', 'Sửa mã giảm giá')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Sửa mã giảm giá</h1>
        <a href="{{ route('admin.coupons.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Quay lại</a>
    </div>

    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        @include('admin.coupons._form')

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.coupons.index') }}" class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Hủy</a>
            <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700">Cập nhật</button>
        </div>
    </form>
</div>
@endsection
