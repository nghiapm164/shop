@extends('layouts.app')

@section('meta_title', 'Không có quyền truy cập - MODESPORT')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="text-center max-w-md">
        <div class="text-8xl font-black text-amber-500 mb-4">403</div>
        <h1 class="fashion-title text-2xl mb-3">Không có quyền truy cập</h1>
        <p class="text-slate-500 mb-8">Bạn không có quyền truy cập trang này.</p>
        <a href="{{ route('home') }}" class="rounded-xl bg-red-500 hover:bg-red-600 text-white px-6 py-3 font-bold text-sm transition">
            Về trang chủ
        </a>
    </div>
</div>
@endsection
