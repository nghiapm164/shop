@extends('layouts.app')

@section('meta_title', 'Liên hệ - MODESPORT')
@section('meta_description', 'Liên hệ với MODESPORT để được hỗ trợ nhanh nhất.')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="fashion-title text-4xl">Liên hệ với chúng tôi</h1>
        <p class="fashion-subtitle mt-3">Chúng tôi luôn sẵn sàng hỗ trợ bạn. Hãy liên hệ qua các kênh dưới đây.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        {{-- Contact Info --}}
        <div class="space-y-6">
            <div class="fashion-section p-6 flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-lg">Hotline</h3>
                    <p class="text-slate-600 mt-1">0123 456 789</p>
                    <p class="text-sm text-slate-500">Hỗ trợ 8:00 - 22:00 hàng ngày</p>
                </div>
            </div>

            <div class="fashion-section p-6 flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-lg">Email</h3>
                    <p class="text-slate-600 mt-1">support@modesport.vn</p>
                    <p class="text-sm text-slate-500">Phản hồi trong 24h</p>
                </div>
            </div>

            <div class="fashion-section p-6 flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-lg">Địa chỉ</h3>
                    <p class="text-slate-600 mt-1">Hà Nội, Việt Nam</p>
                </div>
            </div>
        </div>

        {{-- Contact Form --}}
        <div class="fashion-section p-6">
            <h3 class="font-bold text-slate-900 text-lg mb-4">Gửi tin nhắn</h3>
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Họ và tên</label>
                    <input type="text" class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400" placeholder="Nhập họ tên">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400" placeholder="email@example.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nội dung</label>
                    <textarea rows="4" class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400" placeholder="Nhập nội dung..."></textarea>
                </div>
                <button type="button" class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition" onclick="alert('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất.')">
                    Gửi tin nhắn
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
