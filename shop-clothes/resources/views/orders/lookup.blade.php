@extends('layouts.app')

@section('meta_title', 'Tra cứu đơn hàng - MODESPORT')
@section('meta_description', 'Tra cứu trạng thái đơn hàng MODESPORT nhanh chóng.')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <div class="text-center mb-10">
        <div class="w-16 h-16 mx-auto rounded-full bg-red-100 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
        </div>
        <h1 class="fashion-title text-3xl md:text-4xl">Tra cứu đơn hàng</h1>
        <p class="fashion-subtitle mt-2">Nhập mã đơn hàng và email/số điện thoại để kiểm tra trạng thái.</p>
    </div>

    <div class="fashion-section p-6 md:p-8">
        <form method="POST" action="{{ route('order.lookup.search') }}" class="space-y-5">
            @csrf

            <div>
                <label for="order_code" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Mã đơn hàng <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="order_code"
                    name="order_code"
                    value="{{ old('order_code') }}"
                    required
                    autofocus
                    placeholder="Ví dụ: ORD-XXXXXX"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition uppercase"
                >
                @error('order_code')
                    <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contact" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Email hoặc số điện thoại <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="contact"
                    name="contact"
                    value="{{ old('contact') }}"
                    required
                    placeholder="Email hoặc SĐT dùng khi đặt hàng"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                >
                @error('contact')
                    <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
                <p class="text-xs text-slate-400 mt-1.5">Nhập email hoặc số điện thoại bạn đã dùng khi đặt hàng để xác minh.</p>
            </div>

            <button type="submit" class="w-full rounded-xl bg-red-500 hover:bg-red-600 text-white py-3.5 font-bold text-sm transition shadow-sm hover:shadow-md">
                Tra cứu đơn hàng
            </button>
        </form>
    </div>

    {{-- Help text --}}
    <div class="mt-8 text-center">
        <p class="text-sm text-slate-500">
            Không tìm thấy mã đơn? Kiểm tra trong
            <a href="{{ auth()->check() ? route('client.orders.index') : route('login') }}" class="text-red-600 hover:text-red-700 font-semibold">Lịch sử đơn hàng</a>
            hoặc <a href="{{ route('pages.contact') }}" class="text-red-600 hover:text-red-700 font-semibold">liên hệ hỗ trợ</a>.
        </p>
    </div>
</div>
@endsection
