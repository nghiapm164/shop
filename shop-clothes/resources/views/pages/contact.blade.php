@extends('layouts.app')

@section('meta_title', 'Liên hệ - MODESPORT')
@section('meta_description', 'Liên hệ với MODESPORT để được hỗ trợ nhanh nhất.')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="fashion-title text-4xl">Liên hệ với chúng tôi</h1>
        <p class="fashion-subtitle mt-3">Chúng tôi luôn sẵn sàng hỗ trợ bạn. Hãy liên hệ qua các kênh dưới đây.</p>
    </div>

    @if (session('contact_success'))
        <div class="mb-8 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 text-center">
            <p class="font-semibold">{{ session('contact_success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        {{-- Contact Info --}}
        <div class="space-y-6">
            <div class="fashion-section p-6 flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-lg">Hotline</h3>
                    <p class="text-slate-600 mt-1">1900 6750</p>
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
                    <p class="text-slate-600 mt-1">Hồ Tùng Mậu, Hà Nội</p>
                </div>
            </div>
        </div>

        {{-- Contact Form --}}
        <div class="fashion-section p-6">
            <h3 class="font-bold text-slate-900 text-lg mb-4">Gửi tin nhắn</h3>
            <form method="POST" action="{{ route('contact.submit') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-1.5">Họ và tên <span class="text-red-500">*</span></label>
                    <input type="text" id="contact_name" name="name" value="{{ old('name') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400" placeholder="Nhập họ tên">
                    @error('name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="contact_email" name="email" value="{{ old('email') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400" placeholder="email@example.com">
                    @error('email')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1.5">Số điện thoại</label>
                    <input type="tel" id="contact_phone" name="phone" value="{{ old('phone') }}"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400" placeholder="0xxxxxxxxx">
                </div>

                <div>
                    <label for="contact_subject" class="block text-sm font-medium text-gray-700 mb-1.5">Chủ đề</label>
                    <select id="contact_subject" name="subject"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                        <option value="">-- Chọn chủ đề --</option>
                        <option value="order" {{ old('subject') === 'order' ? 'selected' : '' }}>Hỗ trợ đơn hàng</option>
                        <option value="product" {{ old('subject') === 'product' ? 'selected' : '' }}>Thông tin sản phẩm</option>
                        <option value="return" {{ old('subject') === 'return' ? 'selected' : '' }}>Đổi trả / Hoàn tiền</option>
                        <option value="complaint" {{ old('subject') === 'complaint' ? 'selected' : '' }}>Khiếu nại</option>
                        <option value="other" {{ old('subject') === 'other' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>

                <div>
                    <label for="contact_message" class="block text-sm font-medium text-gray-700 mb-1.5">Nội dung <span class="text-red-500">*</span></label>
                    <textarea id="contact_message" name="message" rows="4" required
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400" placeholder="Nhập nội dung...">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition">
                    Gửi tin nhắn
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
