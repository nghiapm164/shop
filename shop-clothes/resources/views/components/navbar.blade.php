<!-- Header Sticky -->
<header class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    <!-- Top Bar -->
    <div class="hidden md:block bg-gray-50 border-b border-gray-100 py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center text-xs text-gray-600">
            <div class="flex gap-6">
                <span>📞 Hotline: (84) 123 456 789</span>
                <span>📧 Email: support@sportwear.shop</span>
            </div>
            <div class="flex gap-4">
                @auth
                    <span>Xin chào, {{ auth()->user()->name }}</span>
                @else
                    <a href="{{ route('login') }}" class="hover:text-red-500 transition-all">Đăng nhập</a>
                    <span>/</span>
                    <a href="{{ route('register') }}" class="hover:text-red-500 transition-all">Đăng ký</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4" x-data="{ mobileMenuOpen: false, productDropdown: false }">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-900 hover:text-red-500 transition-all">
                    <span class="text-red-500">Sport</span>Wear
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center space-x-8">
                <!-- Home -->
                <a href="{{ route('home') }}" class="text-gray-700 font-medium hover:text-red-500 transition-all">
                    Trang chủ
                </a>

                <!-- Products with Mega Menu -->
                <div class="relative group" @mouseenter="productDropdown = true" @mouseleave="productDropdown = false">
                    <button class="text-gray-700 font-medium hover:text-red-500 transition-all flex items-center gap-1">
                        Sản phẩm
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </button>

                    <!-- Mega Menu Dropdown -->
                    <div x-show="productDropdown" class="absolute left-0 top-full pt-4 invisible group-hover:visible">
                        <div class="bg-white rounded-lg shadow-xl border border-gray-100 w-max">
                            <div class="grid grid-cols-2 gap-8 p-6">
                                @php
                                    $categories = [
                                        ['name' => 'Áo thun', 'icon' => '👕'],
                                        ['name' => 'Quần shorts', 'icon' => '🩱'],
                                        ['name' => 'Quần dài', 'icon' => '👖'],
                                        ['name' => 'Áo khoác', 'icon' => '🧥'],
                                        ['name' => 'Giày thể thao', 'icon' => '👟'],
                                        ['name' => 'Phụ kiện', 'icon' => '🧢'],
                                    ];
                                @endphp
                                
                                @foreach($categories as $category)
                                    <a href="#" class="flex items-center gap-3 hover:text-red-500 transition-all p-2 rounded-lg hover:bg-gray-50">
                                        <span class="text-xl">{{ $category['icon'] }}</span>
                                        <span class="font-medium text-gray-700">{{ $category['name'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Brands -->
                <a href="#" class="text-gray-700 font-medium hover:text-red-500 transition-all">
                    Thương hiệu
                </a>

                <!-- Sale -->
                <a href="#" class="text-red-500 font-bold hover:text-red-600 transition-all flex items-center gap-1">
                    🔥 Sale
                </a>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center space-x-4 lg:space-x-6">
                <!-- Search -->
                <button class="text-gray-700 hover:text-red-500 transition-all lg:hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                <!-- Search Bar Desktop -->
                <div class="hidden lg:block w-64">
                    <div class="relative">
                        <input type="text" placeholder="Tìm kiếm..." 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500">
                        <button class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Wishlist -->
                <a href="#" class="relative text-gray-700 hover:text-red-500 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">12</span>
                </a>

                <!-- Cart (Livewire) -->
                <a href="#" class="relative text-gray-700 hover:text-red-500 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <livewire:cart-badge />
                </a>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="text-gray-700 hover:text-red-500 transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                        </svg>
                    </button>

                    <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-2">
                        @auth
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-red-500 transition-all">
                                👤 Tài khoản của tôi
                            </a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-red-500 transition-all">
                                📦 Đơn hàng của tôi
                            </a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-red-500 transition-all">
                                ❤️ Yêu thích
                            </a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-red-500 transition-all">
                                    🚪 Đăng xuất
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-red-500 transition-all">
                                Đăng nhập
                            </a>
                            <a href="{{ route('register') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-red-500 transition-all">
                                Đăng ký
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Mobile Menu Toggle -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-gray-700 hover:text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Drawer -->
        <div x-show="mobileMenuOpen" x-transition class="lg:hidden mt-4 pb-4 border-t border-gray-200 pt-4">
            <a href="{{ route('home') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Trang chủ</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Sản phẩm</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Thương hiệu</a>
            <a href="#" class="block px-4 py-2 text-red-500 font-bold hover:bg-gray-50 rounded-lg">🔥 Sale</a>
        </div>
    </nav>
</header>
