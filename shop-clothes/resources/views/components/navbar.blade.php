<header class="sticky top-0 z-50 bg-white border-b border-gray-200" style="backdrop-filter: blur(12px); background-color: rgba(255,255,255,0.97);" x-data="{ mobileMenuOpen: false, openUser: false }" x-on:scroll.window="
    if (window.scrollY > 10) {
        $el.classList.add('shadow-sm');
    } else {
        $el.classList.remove('shadow-sm');
    }
">

    {{-- Main Navigation --}}
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
        <div class="flex items-center justify-between h-16 md:h-[72px] gap-4">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-1.5">
                <span class="text-2xl md:text-[26px] font-black tracking-tighter text-slate-900">
                    MODE<span class="text-red-500">SPORT</span>
                </span>
            </a>

            {{-- Desktop Nav Links --}}
            <div class="hidden lg:flex items-center gap-8">
                <a href="{{ route('home') }}"
                   class="relative py-2 text-sm font-bold uppercase tracking-wide text-brand-ink hover:text-brand-primary transition-colors {{ request()->routeIs('home') ? 'text-brand-primary' : '' }}">
                    Trang chủ
                </a>
                <a href="{{ route('shop.index') }}"
                   class="relative py-2 text-sm font-bold uppercase tracking-wide text-brand-ink hover:text-brand-primary transition-colors {{ request()->routeIs('shop.*') || request()->routeIs('products.*') ? 'text-brand-primary' : '' }}">
                    Cửa hàng
                </a>
                <a href="{{ route('shop.new-arrivals') }}"
                   class="relative py-2 text-sm font-bold uppercase tracking-wide text-brand-ink hover:text-brand-primary transition-colors {{ request()->routeIs('shop.new-arrivals') ? 'text-brand-primary' : '' }}">
                    Mới về
                </a>
                <a href="{{ route('shop.best-prices') }}"
                   class="relative py-2 text-sm font-bold uppercase tracking-wide text-brand-ink hover:text-brand-primary transition-colors {{ request()->routeIs('shop.best-prices') ? 'text-brand-primary' : '' }}">
                    Giá tốt
                </a>
                <div class="relative" x-data="{ aboutOpen: false }" @click.outside="aboutOpen = false">
                    <button @click="aboutOpen = !aboutOpen"
                       class="py-2 text-sm font-bold uppercase tracking-wide text-brand-ink hover:text-brand-primary transition-colors flex items-center gap-1 {{ request()->routeIs('pages.*') ? 'text-brand-primary' : '' }}">
                        Về MODESPORT
                        <svg class="w-3 h-3 transition-transform" :class="aboutOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="aboutOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute left-0 mt-1 w-52 bg-white rounded-xl border border-gray-200 shadow-lg py-1.5 z-50">
                        <a href="{{ route('pages.about') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Giới thiệu
                        </a>
                        <a href="{{ route('pages.contact') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Liên hệ
                        </a>
                        <div class="my-1 border-t border-gray-100"></div>
                        <a href="{{ route('pages.return-policy') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Chính sách đổi trả
                        </a>
                        <a href="{{ route('pages.privacy-policy') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Chính sách bảo mật
                        </a>
                        <a href="{{ route('pages.terms') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Điều khoản sử dụng
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-2">
                {{-- Search Button (Desktop) --}}
                <form action="{{ route('shop.index') }}" method="GET" class="hidden sm:inline-flex items-center relative">
                    <input type="text" name="keyword" placeholder="Tìm kiếm sản phẩm..."
                           value="{{ request('keyword') }}"
                           class="h-10 pl-10 pr-4 w-40 md:w-56 rounded-full border border-gray-200 text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-red-500/20 focus:border-red-400 focus:w-64 md:focus:w-72 transition-all outline-none">
                    <svg class="w-4 h-4 absolute left-3.5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35" stroke-linecap="round"/>
                    </svg>
                </form>

                {{-- Wishlist (Desktop) --}}
                @auth
                <a href="{{ route('wishlist.index') }}"
                   class="relative hidden md:inline-flex h-10 w-10 items-center justify-center rounded-full text-gray-500 hover:text-red-500 hover:bg-red-50 transition-all"
                   title="Yêu thích">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    <livewire:wishlist-badge />
                </a>
                @endauth

                {{-- Cart --}}
                <a href="{{ route('cart.index') }}"
                   class="relative inline-flex h-10 w-10 items-center justify-center rounded-full text-gray-500 hover:text-red-500 hover:bg-red-50 transition-all"
                   title="Giỏ hàng">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <livewire:cart-badge />
                </a>

                {{-- User Menu --}}
                <div class="relative" @click.outside="openUser = false">
                    <button @click="openUser = !openUser"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-full text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-all">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                        </svg>
                    </button>

                    <div x-show="openUser"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                         class="absolute right-0 mt-2 w-60 rounded-2xl border border-slate-200 bg-white p-1.5 shadow-xl z-50">
                        @auth
                            <div class="px-4 py-3 mb-1 border-b border-slate-100">
                                <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate mt-0.5">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z"/></svg>
                                Tài khoản
                            </a>
                            <a href="{{ route('client.orders.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                Đơn hàng
                            </a>
                            <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.17a4 4 0 115.656 5.656L10 17.657l-6.828-6.828a4 4 0 010-5.657z"/></svg>
                                Yêu thích
                            </a>
                            <a href="{{ route('addresses.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Địa chỉ
                            </a>
                            <a href="{{ route('order.lookup') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Tra cứu đơn
                            </a>
                            <div class="my-1 border-t border-slate-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 w-full rounded-xl px-4 py-2.5 text-sm text-slate-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1"/></svg>
                                    Đăng xuất
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h7a3 3 0 0 1 3 3v1"/></svg>
                                Đăng nhập
                            </a>
                            <a href="{{ route('register') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM3 20a6 6 0 0 1 12 0v1H3v-1z"/></svg>
                                Đăng ký
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Mobile Menu Toggle --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden inline-flex h-10 w-10 items-center justify-center rounded-full text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-all">
                    <svg x-show="!mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden pb-4 border-t border-slate-100 mt-1 pt-3">
            {{-- Mobile Search --}}
            <a href="{{ route('shop.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 mb-2 bg-slate-50 text-slate-500 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35" stroke-linecap="round"/>
                </svg>
                Tìm kiếm sản phẩm...
            </a>

            <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Trang chủ
            </a>
            <a href="{{ route('shop.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Cửa hàng
            </a>
            <a href="{{ route('shop.new-arrivals') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                Hàng mới
            </a>
            <a href="{{ route('shop.best-prices') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Giá tốt
            </a>
            <a href="{{ route('cart.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Giỏ hàng
            </a>
            @auth
            <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.17a4 4 0 115.656 5.656L10 17.657l-6.828-6.828a4 4 0 010-5.657z"/></svg>
                Yêu thích
            </a>
            @endauth
            <a href="{{ route('order.lookup') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Tra cứu đơn
            </a>
        </div>
    </nav>
</header>
