@extends('layouts.app')

@section('meta_title', 'Tài khoản của tôi - MODESPORT')

@section('content')
@php
    $user = auth()->user();
    $orders = $user->orders()->latest()->limit(5)->get();
    $orderCounts = [
        'all' => $user->orders()->count(),
        'pending' => $user->orders()->where('status', 'pending')->count(),
        'processing' => $user->orders()->whereIn('status', ['confirmed', 'processing'])->count(),
        'shipped' => $user->orders()->where('status', 'shipped')->count(),
        'delivered' => $user->orders()->where('status', 'delivered')->count(),
    ];
    $wishlistCount = $user->wishlist()->count();
    $addressCount = $user->addresses()->count();
@endphp

<div class="min-h-screen pb-16">
    {{-- Header --}}
    <div class="max-w-7xl mx-auto px-4 pt-8 pb-4">
        <div class="fashion-section p-6 md:p-7">
            <h1 class="fashion-title text-3xl md:text-4xl">Xin chào, {{ $user->name }}! 👋</h1>
            <p class="fashion-subtitle mt-2">Quản lý tài khoản, đơn hàng và cài đặt cá nhân của bạn.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        {{-- Quick Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('client.orders.index') }}" class="fashion-section p-5 hover:shadow-lg transition-shadow group">
                <div class="text-3xl font-black text-red-600">{{ $orderCounts['all'] }}</div>
                <p class="text-sm text-slate-500 mt-1 group-hover:text-red-500 transition-colors">Đơn hàng</p>
            </a>
            <a href="{{ route('wishlist.index') }}" class="fashion-section p-5 hover:shadow-lg transition-shadow group">
                <div class="text-3xl font-black text-pink-600">{{ $wishlistCount }}</div>
                <p class="text-sm text-slate-500 mt-1 group-hover:text-pink-500 transition-colors">Yêu thích</p>
            </a>
            <a href="{{ route('addresses.index') }}" class="fashion-section p-5 hover:shadow-lg transition-shadow group">
                <div class="text-3xl font-black text-blue-600">{{ $addressCount }}</div>
                <p class="text-sm text-slate-500 mt-1 group-hover:text-blue-500 transition-colors">Địa chỉ</p>
            </a>
            <div class="fashion-section p-5">
                <div class="text-3xl font-black text-green-600">{{ $orderCounts['delivered'] }}</div>
                <p class="text-sm text-slate-500 mt-1">Đã giao</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left: Order Status & Recent Orders --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Order Status Quick Links --}}
                <div class="fashion-section p-6">
                    <h2 class="fashion-title text-xl mb-4">Đơn hàng theo trạng thái</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <a href="{{ route('client.orders.index', ['status' => 'pending']) }}" class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-blue-300 hover:bg-blue-50 transition-all">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Chờ xử lý</p>
                                <p class="text-lg font-bold text-blue-600">{{ $orderCounts['pending'] }}</p>
                            </div>
                        </a>
                        <a href="{{ route('client.orders.index', ['status' => 'processing']) }}" class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-amber-300 hover:bg-amber-50 transition-all">
                            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Đang xử lý</p>
                                <p class="text-lg font-bold text-amber-600">{{ $orderCounts['processing'] }}</p>
                            </div>
                        </a>
                        <a href="{{ route('client.orders.index', ['status' => 'shipped']) }}" class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-violet-300 hover:bg-violet-50 transition-all">
                            <div class="w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Đang giao</p>
                                <p class="text-lg font-bold text-violet-600">{{ $orderCounts['shipped'] }}</p>
                            </div>
                        </a>
                        <a href="{{ route('client.orders.index', ['status' => 'delivered']) }}" class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-green-300 hover:bg-green-50 transition-all">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Đã giao</p>
                                <p class="text-lg font-bold text-green-600">{{ $orderCounts['delivered'] }}</p>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Recent Orders --}}
                <div class="fashion-section p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="fashion-title text-xl">Đơn hàng gần đây</h2>
                        <a href="{{ route('client.orders.index') }}" class="text-sm text-red-600 hover:text-red-700 font-semibold">Xem tất cả →</a>
                    </div>

                    @if($orders->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            <p class="text-slate-500">Bạn chưa có đơn hàng nào.</p>
                            <a href="{{ route('shop.index') }}" class="mt-4 inline-flex items-center gap-2 px-6 py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition-colors">
                                Bắt đầu mua sắm
                            </a>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($orders as $order)
                                <a href="{{ route('client.orders.show', $order) }}" class="flex items-center justify-between p-4 rounded-xl border border-slate-200 hover:border-red-300 hover:bg-red-50/50 transition-all">
                                    <div class="flex items-center gap-4">
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm">{{ $order->order_code }}</p>
                                            <p class="text-xs text-slate-500 mt-0.5">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="text-sm font-bold text-slate-900">{{ number_format($order->total, 0, ',', '.') }}₫</span>
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-blue-100 text-blue-700',
                                                'confirmed' => 'bg-sky-100 text-sky-700',
                                                'processing' => 'bg-amber-100 text-amber-700',
                                                'shipped' => 'bg-violet-100 text-violet-700',
                                                'delivered' => 'bg-green-100 text-green-700',
                                                'cancelled' => 'bg-red-100 text-red-700',
                                                'refunded' => 'bg-slate-100 text-slate-700',
                                            ];
                                        @endphp
                                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $statusColors[$order->status] ?? 'bg-slate-100 text-slate-700' }}">
                                            {{ \App\Models\Order::getStatusLabel($order->status) }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right: Account Actions --}}
            <div class="space-y-6">
                <div class="fashion-section p-6">
                    <h2 class="fashion-title text-xl mb-4">Tài khoản</h2>
                    <div class="space-y-2">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="text-sm font-medium text-slate-700">Thông tin cá nhân</span>
                        </a>
                        <a href="{{ route('addresses.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-sm font-medium text-slate-700">Quản lý địa chỉ</span>
                        </a>
                        <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.17a4 4 0 115.656 5.656L10 17.657l-6.828-6.828a4 4 0 010-5.657z"/></svg>
                            <span class="text-sm font-medium text-slate-700">Sản phẩm yêu thích</span>
                        </a>
                        <a href="{{ route('client.orders.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span class="text-sm font-medium text-slate-700">Lịch sử đơn hàng</span>
                        </a>
                    </div>
                </div>

                <div class="fashion-section p-6">
                    <h2 class="fashion-title text-xl mb-4">Hỗ trợ</h2>
                    <div class="space-y-2">
                        <a href="{{ route('pages.about') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm font-medium text-slate-700">Giới thiệu</span>
                        </a>
                        <a href="{{ route('pages.contact') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="text-sm font-medium text-slate-700">Liên hệ</span>
                        </a>
                        <a href="{{ route('pages.return-policy') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span class="text-sm font-medium text-slate-700">Chính sách đổi trả</span>
                        </a>
                    </div>
                </div>

                {{-- Quick Action --}}
                <a href="{{ route('shop.index') }}" class="block fashion-section p-6 bg-gradient-to-br from-red-500 to-orange-400 text-white hover:shadow-xl transition-shadow">
                    <h3 class="font-bold text-lg">🛍️ Tiếp tục mua sắm</h3>
                    <p class="text-sm text-white/80 mt-1">Khám phá sản phẩm mới nhất</p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
