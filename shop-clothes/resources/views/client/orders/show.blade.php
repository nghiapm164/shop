@extends('layouts.app')

@section('title', 'Đơn hàng ' . $order->order_code)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Back Button --}}
    <a href="{{ route('client.orders.index') }}"
       class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-semibold mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        Quay lại danh sách đơn hàng
    </a>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Order Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">
                    Đơn hàng <span class="text-red-600">{{ $order->order_code }}</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Đặt ngày {{ $order->created_at->format('d/m/Y lúc H:i') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                @if($order->status === 'pending')
                    <span class="px-4 py-2 text-sm font-semibold text-blue-600 bg-blue-100 rounded-full">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Chờ xử lý
                    </span>
                @elseif($order->status === 'confirmed')
                    <span class="px-4 py-2 text-sm font-semibold text-sky-600 bg-sky-100 rounded-full">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Đã xác nhận
                    </span>
                @elseif($order->status === 'processing')
                    <span class="px-4 py-2 text-sm font-semibold text-amber-600 bg-amber-100 rounded-full">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Đang xử lý
                    </span>
                @elseif($order->status === 'shipped')
                    <span class="px-4 py-2 text-sm font-semibold text-purple-600 bg-purple-100 rounded-full">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                        Đang giao
                    </span>
                @elseif($order->status === 'delivered')
                    <span class="px-4 py-2 text-sm font-semibold text-green-600 bg-green-100 rounded-full">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Đã giao
                    </span>
                @elseif($order->status === 'cancelled')
                    <span class="px-4 py-2 text-sm font-semibold text-red-600 bg-red-100 rounded-full">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Đã hủy
                    </span>
                @endif

                @if(in_array($order->status, ['pending', 'confirmed']))
                    <form method="POST" action="{{ route('client.orders.cancel', $order) }}"
                          onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 text-sm font-semibold text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Hủy đơn
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Order Progress Bar --}}
        @if(!in_array($order->status, ['cancelled', 'refunded']))
            @php
                $steps = [
                    'pending' => ['label' => 'Đặt hàng', 'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121 0 2.09-.773 2.34-1.872l1.836-8.04A1.125 1.125 0 0019.5 3.75H5.136M7.5 14.25L5.136 3.75M7.5 14.25L3.75 18"/></svg>'],
                    'confirmed' => ['label' => 'Xác nhận', 'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'],
                    'processing' => ['label' => 'Đóng gói', 'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>'],
                    'shipped' => ['label' => 'Vận chuyển', 'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25"/></svg>'],
                    'delivered' => ['label' => 'Hoàn thành', 'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'],
                ];
                $statusOrder = array_keys($steps);
                $currentIndex = array_search($order->status, $statusOrder);
            @endphp
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    @foreach($steps as $key => $step)
                        @php
                            $stepIndex = array_search($key, $statusOrder);
                            $isCompleted = $stepIndex <= $currentIndex;
                            $isCurrent = $key === $order->status;
                        @endphp
                        <div class="flex flex-col items-center relative {{ $loop->first ? '' : 'flex-1' }}">
                            @if(!$loop->first)
                                <div class="absolute top-4 right-1/2 w-full h-0.5 {{ $isCompleted ? 'bg-green-500' : 'bg-gray-300' }} -z-10"></div>
                            @endif
                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $isCurrent ? 'bg-red-600 ring-4 ring-red-200' : ($isCompleted ? 'bg-green-500' : 'bg-gray-300') }}">
                                <span class="text-white">{!! $step['icon'] !!}</span>
                            </div>
                            <p class="text-xs mt-2 font-semibold {{ $isCurrent ? 'text-red-600' : ($isCompleted ? 'text-green-600' : 'text-gray-400') }}">
                                {{ $step['label'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Order Items --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900">
                        <svg class="w-5 h-5 inline mr-2 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                        Sản phẩm
                    </h2>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        @php
                            $variant = $item->productVariant;
                            $product = $variant?->product;
                            $imageUrl = $product?->image_url ?? asset('images/product-placeholder.svg');
                        @endphp
                        <div class="px-6 py-4 flex items-center gap-4">
                            <img src="{{ $imageUrl }}" alt="{{ $item->product_name }}"
                                 class="w-20 h-20 object-cover rounded-lg">
                            <div class="flex-1 min-w-0">
                                @if($product)
                                    <a href="{{ route('products.show', $product->slug) }}"
                                       class="text-sm font-semibold text-gray-900 hover:text-red-600">
                                        {{ $item->product_name }}
                                    </a>
                                @else
                                    <p class="text-sm font-semibold text-gray-900">{{ $item->product_name }}</p>
                                @endif
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @if($item->size)
                                        <span class="text-xs bg-gray-100 px-2 py-0.5 rounded">Size: {{ $item->size }}</span>
                                    @endif
                                    @if($item->color)
                                        <span class="text-xs bg-gray-100 px-2 py-0.5 rounded">Màu: {{ $item->color }}</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-1">x{{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900">{{ number_format($item->subtotal, 0, ',', '.') }}₫</p>
                                <p class="text-xs text-gray-500">{{ number_format($item->price, 0, ',', '.') }}₫ / cái</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">
                    <svg class="w-5 h-5 inline mr-2 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25"/></svg>
                    Địa chỉ giao hàng
                </h2>
                @if($order->shipping_address)
                    <div class="space-y-2">
                        @if(isset($order->shipping_address['recipient_name']))
                            <p class="text-sm">
                                <svg class="w-4 h-4 inline mr-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                <span class="font-semibold">{{ $order->shipping_address['recipient_name'] }}</span>
                            </p>
                        @endif
                        @if(isset($order->shipping_address['phone']))
                            <p class="text-sm">
                                <svg class="w-4 h-4 inline mr-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                                <span>{{ $order->shipping_address['phone'] }}</span>
                            </p>
                        @endif
                        @if(isset($order->shipping_address['address']))
                            <p class="text-sm">
                                <svg class="w-4 h-4 inline mr-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                <span>{{ $order->shipping_address['address'] }}</span>
                            </p>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-gray-500">Không có thông tin địa chỉ</p>
                @endif
            </div>

            {{-- Notes --}}
            @if($order->notes)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">
                        <svg class="w-5 h-5 inline mr-2 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9"/></svg>
                        Ghi chú
                    </h2>
                    <p class="text-sm text-gray-700">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        {{-- Right: Summary --}}
        <div class="space-y-6">
            {{-- Payment Info --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">
                    <svg class="w-5 h-5 inline mr-2 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                    Thanh toán
                </h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">Phương thức</p>
                        <p class="text-sm font-semibold text-gray-900">
                            @if($order->payment_method === 'cod')
                                <svg class="w-4 h-4 inline mr-1 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                                Thanh toán khi nhận hàng
                            @elseif($order->payment_method === 'bank_transfer')
                                <svg class="w-4 h-4 inline mr-1 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"/></svg>
                                Chuyển khoản ngân hàng
                            @elseif($order->payment_method === 'e_wallet')
                                <svg class="w-4 h-4 inline mr-1 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3"/></svg>
                                Ví điện tử
                            @else
                                {{ $order->payment_method }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Trạng thái</p>
                        @if($order->payment_status === 'unpaid')
                            <span class="px-3 py-1 text-xs font-semibold text-orange-600 bg-orange-100 rounded-full">Chưa thanh toán</span>
                        @elseif($order->payment_status === 'paid')
                            <span class="px-3 py-1 text-xs font-semibold text-green-600 bg-green-100 rounded-full">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Đã thanh toán
                            </span>
                        @elseif($order->payment_status === 'refunded')
                            <span class="px-3 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded-full">Đã hoàn tiền</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">
                    <svg class="w-5 h-5 inline mr-2 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008H18v-.008zm0 2.25h.008v.008H18V13.5zM9.75 9h7.5v7.5h-7.5V9z"/></svg>
                    Tổng kết đơn hàng
                </h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tạm tính:</span>
                        <span class="font-semibold">{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Giảm giá:</span>
                            <span class="font-semibold text-green-600">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Phí vận chuyển:</span>
                        <span class="font-semibold">
                            @if($order->shipping_fee > 0)
                                {{ number_format($order->shipping_fee, 0, ',', '.') }}₫
                            @else
                                <span class="text-green-600">Miễn phí</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-3 border-t border-gray-200">
                        <span>Tổng cộng:</span>
                        <span class="text-red-600">{{ number_format($order->total, 0, ',', '.') }}₫</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
