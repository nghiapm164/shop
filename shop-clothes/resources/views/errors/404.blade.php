@extends('layouts.app')

@section('meta_title', 'Không tìm thấy trang - MODESPORT')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="text-center max-w-md">
        <div class="text-8xl font-black text-red-500 mb-4">404</div>
        <h1 class="fashion-title text-2xl mb-3">Không tìm thấy trang</h1>
        <p class="text-slate-500 mb-8">Trang bạn tìm kiếm không tồn tại hoặc đã bị di chuyển.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="rounded-xl bg-red-500 hover:bg-red-600 text-white px-6 py-3 font-bold text-sm transition">
                Về trang chủ
            </a>
            <a href="{{ route('shop.index') }}" class="rounded-xl border border-slate-300 text-slate-700 px-6 py-3 font-semibold text-sm hover:bg-slate-50 transition">
                Mua sắm ngay
            </a>
        </div>
    </div>
</div>
@endsection
