@extends('layouts.app')

@section('meta_title', 'Giới thiệu - MODESPORT')
@section('meta_description', 'Tìm hiểu về MODESPORT - Thương hiệu thời trang thể thao nam hàng đầu Việt Nam.')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12">
    {{-- Hero --}}
    <div class="text-center mb-12">
        <h1 class="fashion-title text-4xl md:text-5xl">Về MODESPORT</h1>
        <p class="fashion-subtitle mt-4 max-w-2xl mx-auto text-lg">Nâng tầm phong cách thể thao nam với sản phẩm chất lượng cao, thiết kế hiện đại và giá cả hợp lý.</p>
    </div>

    {{-- Story --}}
    <div class="fashion-section p-8 md:p-12 mb-8">
        <h2 class="text-2xl font-bold text-slate-900 mb-4">Câu chuyện của chúng tôi</h2>
        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed space-y-4">
            <p>MODESPORT được thành lập với sứ mệnh mang đến cho phái mạnh Việt Nam những sản phẩm thời trang thể thao chất lượng cao, thiết kế tinh tế và giá cả phải chăng.</p>
            <p>Chúng tôi tin rằng mỗi người đàn ông đều xứng đáng có được sự tự tin trong từng bước đi, dù là trong phòng tập gym, trên đường chạy hay trong cuộc sống hàng ngày.</p>
            <p>Với đội ngũ thiết kế trẻ trung, sáng tạo và am hiểu xu hướng thời trang quốc tế, MODESPORT không ngừng đổi mới để mang đến những sản phẩm phù hợp nhất với phong cách sống hiện đại của nam giới Việt Nam.</p>
        </div>
    </div>

    {{-- Values --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="fashion-section p-6 text-center">
            <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="font-bold text-slate-900 text-lg mb-2">Chất lượng đảm bảo</h3>
            <p class="text-sm text-slate-600">Sản phẩm được kiểm tra nghiêm ngặt từ chất liệu đến đường may, đảm bảo độ bền và thoải mái.</p>
        </div>
        <div class="fashion-section p-6 text-center">
            <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h3 class="font-bold text-slate-900 text-lg mb-2">Giao hàng nhanh</h3>
            <p class="text-sm text-slate-600">Nội thành 2h, toàn quốc 2-5 ngày. Freeship đơn từ 299.000đ.</p>
        </div>
        <div class="fashion-section p-6 text-center">
            <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </div>
            <h3 class="font-bold text-slate-900 text-lg mb-2">Đổi trả dễ dàng</h3>
            <p class="text-sm text-slate-600">Đổi trả trong 7 ngày nếu sản phẩm không đúng mô tả hoặc bị lỗi.</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="fashion-section p-6 text-center">
            <p class="text-3xl font-black text-red-600">1000+</p>
            <p class="text-sm text-slate-600 mt-1">Sản phẩm</p>
        </div>
        <div class="fashion-section p-6 text-center">
            <p class="text-3xl font-black text-red-600">10+</p>
            <p class="text-sm text-slate-600 mt-1">Thương hiệu</p>
        </div>
        <div class="fashion-section p-6 text-center">
            <p class="text-3xl font-black text-red-600">5000+</p>
            <p class="text-sm text-slate-600 mt-1">Khách hàng</p>
        </div>
        <div class="fashion-section p-6 text-center">
            <p class="text-3xl font-black text-red-600">4.8★</p>
            <p class="text-sm text-slate-600 mt-1">Đánh giá TB</p>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('shop.index') }}" class="btn-primary text-lg px-8 py-3">Khám phá ngay</a>
    </div>
</div>
@endsection
