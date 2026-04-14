<header class="sticky top-0 z-50 border-b border-slate-200 bg-white shadow-sm">
    <div class="hidden md:block border-b border-slate-200 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 text-xs text-slate-600 flex items-center justify-between gap-4">
            <p class="tracking-wide whitespace-nowrap overflow-hidden text-ellipsis">FREESHIP đơn từ 299.000đ • Đổi trả 7 ngày • Hỗ trợ size 24/7</p>
            <div class="flex items-center gap-4">
                @auth
                    <span class="whitespace-nowrap">Xin chào {{ auth()->user()->name }}</span>
                @else
                    <a href="{{ route('login') }}" class="hover:text-red-500">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="hover:text-red-500">Đăng ký</a>
                @endauth
            </div>
        </div>
    </div>

    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4" x-data="{ mobileMenuOpen: false, openUser: false }">
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="display-font text-2xl font-extrabold tracking-tight text-slate-900">
                MODE<span class="text-red-500">SPORT</span>
            </a>

            <div class="hidden lg:flex items-center gap-8 text-sm font-semibold text-slate-700">
                <a href="{{ route('home') }}" class="hover:text-red-500 transition-colors {{ request()->routeIs('home') ? 'text-red-500' : '' }}">Trang chủ</a>
                <a href="{{ route('shop.index') }}" class="hover:text-red-500 transition-colors {{ request()->routeIs('shop.*') || request()->routeIs('products.*') ? 'text-red-500' : '' }}">Cửa hàng</a>
                <a href="{{ route('shop.index', ['collection' => 'new_arrivals', 'sort' => 'newest']) }}" class="hover:text-red-500 transition-colors">Mới về</a>
                <a href="{{ route('shop.index', ['sort' => 'price_low']) }}" class="hover:text-red-500 transition-colors">Giá tốt</a>
            </div>

            <div class="flex items-center gap-2 sm:gap-4">
                <a href="{{ route('shop.index') }}" class="hidden sm:inline-flex items-center rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-slate-400 hover:bg-slate-50 transition-all">
                    Tìm sản phẩm
                </a>

                <a href="{{ route('cart.index') }}" class="relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 text-slate-700 hover:border-red-400 hover:text-red-500 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <livewire:cart-badge />
                </a>

                <div class="relative" @click.outside="openUser = false">
                    <button @click="openUser = !openUser" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 text-slate-700 hover:border-red-400 hover:text-red-500 transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                        </svg>
                    </button>

                    <div x-show="openUser" x-transition class="absolute right-0 mt-2 w-56 rounded-2xl border border-slate-200 bg-white p-2 shadow-xl">
                        @auth
                            <a href="{{ route('profile.edit') }}" class="block rounded-xl px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Tài khoản của tôi</a>
                            <a href="{{ route('profile.edit') }}" class="block rounded-xl px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Thông tin cá nhân</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full rounded-xl px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-100">Đăng xuất</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block rounded-xl px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Đăng nhập</a>
                            <a href="{{ route('register') }}" class="block rounded-xl px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Đăng ký</a>
                        @endauth
                    </div>
                </div>

                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 text-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-transition class="lg:hidden mt-4 rounded-2xl border border-slate-200 bg-white p-3 space-y-1">
            <a href="{{ route('home') }}" class="block rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Trang chủ</a>
            <a href="{{ route('shop.index') }}" class="block rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Cửa hàng</a>
            <a href="{{ route('shop.index', ['collection' => 'new_arrivals', 'sort' => 'newest']) }}" class="block rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Hàng mới</a>
            <a href="{{ route('cart.index') }}" class="block rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Giỏ hàng</a>
        </div>
    </nav>
</header>
