<header class="sticky top-0 z-50 bg-white border-b border-gray-200" style="backdrop-filter: blur(12px); background-color: rgba(255,255,255,0.97);" x-data="{ mobileMenuOpen: false, openUser: false }" x-on:scroll.window="
    if (window.scrollY > 10) {
        $el.classList.add('shadow-sm');
    } else {
        $el.classList.remove('shadow-sm');
    }
">
    {{-- Top Announcement Bar --}}
    <div class="hidden md:block border-b border-gray-100 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-1.5 text-xs text-gray-500 flex items-center justify-between gap-4">
            <p class="tracking-wide font-medium whitespace-nowrap overflow-hidden text-ellipsis flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"/></svg>
                FREESHIP đơn từ 299.000đ &bull; Đổi trả 7 ngày &bull; Hỗ trợ size 24/7
            </p>
            <div class="flex items-center gap-5">
                @auth
                    <span class="whitespace-nowrap font-medium text-slate-600">Xin chào, {{ auth()->user()->name }}</span>
                @else
                    <a href="{{ route('login') }}" class="hover:text-red-500 transition-colors font-medium">Đăng nhập</a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('register') }}" class="hover:text-red-500 transition-colors font-medium">Đăng ký</a>
                @endauth
            </div>
        </div>
    </div>

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
            <div class="hidden lg:flex items-center gap-1">
                <a href="{{ route('home') }}" 
                   class="relative px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900 transition-colors rounded-lg hover:bg-gray-50 {{ request()->routeIs('home') ? 'text-red-500' : '' }}">
                    Trang chủ
                </a>
                <a href="{{ route('shop.index') }}" 
                   class="relative px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900 transition-colors rounded-lg hover:bg-gray-50 {{ request()->routeIs('shop.*') || request()->routeIs('products.*') ? 'text-red-500' : '' }}">
                    Cửa hàng
                </a>
                <a href="{{ route('shop.index', ['collection' => 'new_arrivals', 'sort' => 'newest']) }}" 
                   class="relative px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900 transition-colors rounded-lg hover:bg-gray-50">
                    Mới về
                </a>
                <a href="{{ route('shop.index', ['sort' => 'price_low']) }}" 
                   class="relative px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900 transition-colors rounded-lg hover:bg-gray-50">
                    Giá tốt
                </a>
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-2">
                {{-- Search Button (Desktop) --}}
                <a href="{{ route('shop.index') }}" 
                   class="hidden sm:inline-flex items-center gap-2 h-10 px-4 rounded-full border border-gray-200 text-gray-500 text-sm font-medium hover:border-gray-300 hover:text-gray-700 hover:bg-gray-50 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35" stroke-linecap="round"/>
                    </svg>
                    <span class="hidden md:inline">Tìm kiếm</span>
                </a>

                {{-- Wishlist (Desktop) --}}
                @auth
                <a href="{{ route('wishlist.index') }}" 
                   class="hidden md:inline-flex h-10 w-10 items-center justify-center rounded-full text-gray-500 hover:text-red-500 hover:bg-red-50 transition-all"
                   title="Yêu thích">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
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
            <a href="{{ route('shop.index', ['collection' => 'new_arrivals', 'sort' => 'newest']) }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                Hàng mới
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
        </div>
    </nav>
</header>