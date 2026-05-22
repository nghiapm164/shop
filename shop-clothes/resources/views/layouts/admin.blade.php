<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bảng điều khiển quản trị') - Shop Quần áo Thể Thao</title>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    
    @yield('styles')
</head>
<body class="bg-gray-50">
    <div
        class="flex h-screen bg-gray-100"
        x-data="{
            sidebarOpen: window.innerWidth >= 768,
            isDesktop: window.innerWidth >= 768,
            toggleSidebar() {
                if (this.isDesktop) return;
                this.sidebarOpen = !this.sidebarOpen;
            },
            closeSidebar() {
                if (!this.isDesktop) this.sidebarOpen = false;
            },
            handleResize() {
                this.isDesktop = window.innerWidth >= 768;
                if (this.isDesktop) this.sidebarOpen = true;
            }
        }"
        x-init="window.addEventListener('resize', () => handleResize())"
    >
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white overflow-y-auto transition-transform duration-300 transform"
             :class="(sidebarOpen || isDesktop) ? 'translate-x-0' : '-translate-x-full'"
             x-transition>
            
            <!-- Logo -->
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="text-2xl font-bold text-red-600">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold">ShopGym</h1>
                        <p class="text-xs text-gray-400">Quản trị</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="mt-6">
                <ul class="space-y-2 px-4">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                            <i class="fas fa-chart-line w-5"></i>
                            <span>Tổng quan</span>
                        </a>
                    </li>

                    <!-- Products -->
                    <li>
                                <a href="{{ Route::has('admin.products.index') ? route('admin.products.index') : 'javascript:void(0)' }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                            <i class="fas fa-box w-5"></i>
                            <span>Sản phẩm</span>
                        </a>
                    </li>

                    <!-- Categories -->
                    <li>
                        <a href="{{ Route::has('admin.categories.index') ? route('admin.categories.index') : 'javascript:void(0)' }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                            <i class="fas fa-sitemap w-5"></i>
                            <span>Danh mục</span>
                        </a>
                    </li>

                    <!-- Brands -->
                    <li>
                        <a href="{{ Route::has('admin.brands.index') ? route('admin.brands.index') : 'javascript:void(0)' }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.brands.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                            <i class="fas fa-tags w-5"></i>
                            <span>Thương hiệu</span>
                        </a>
                    </li>

                    <!-- Flash Sale -->
                    <li>
                        <a href="{{ Route::has('admin.flash-sales.index') ? route('admin.flash-sales.index') : 'javascript:void(0)' }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.flash-sales.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                            <i class="fas fa-bolt w-5"></i>
                            <span>Flash Sale</span>
                        </a>
                    </li>

                    <!-- Orders -->
                    <li>
                                <a href="{{ Route::has('admin.orders.index') ? route('admin.orders.index') : 'javascript:void(0)' }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                            <i class="fas fa-shopping-bag w-5"></i>
                            <span>Đơn hàng</span>
                        </a>
                    </li>

                    <!-- Customers -->
                    <li>
                                <a href="{{ Route::has('admin.customers.index') ? route('admin.customers.index') : 'javascript:void(0)' }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.customers.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                            <i class="fas fa-users w-5"></i>
                            <span>Khách hàng</span>
                        </a>
                    </li>

                    <!-- Users / Accounts -->
                    <li>
                        <a href="{{ Route::has('admin.users.index') ? route('admin.users.index') : 'javascript:void(0)' }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                            <i class="fas fa-user-shield w-5"></i>
                            <span>Tài khoản</span>
                        </a>
                    </li>

                    <!-- Staff -->
                    <li>
                        <a href="{{ Route::has('admin.staff.index') ? route('admin.staff.index') : 'javascript:void(0)' }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.staff.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                            <i class="fas fa-user-tie w-5"></i>
                            <span>Nhân sự</span>
                        </a>
                    </li>

                    <!-- Reviews -->
                    <li>
                        <a href="{{ Route::has('admin.reviews.index') ? route('admin.reviews.index') : 'javascript:void(0)' }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.reviews.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                            <i class="fas fa-star w-5"></i>
                            <span>Đánh giá</span>
                        </a>
                    </li>

                    <!-- Coupons -->
                    <li>
                                <a href="{{ Route::has('admin.coupons.index') ? route('admin.coupons.index') : 'javascript:void(0)' }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.coupons.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                            <i class="fas fa-ticket-alt w-5"></i>
                            <span>Mã giảm giá</span>
                        </a>
                    </li>

                    <!-- Banners -->
                    <li>
                                <a href="{{ Route::has('admin.banners.index') ? route('admin.banners.index') : 'javascript:void(0)' }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.banners.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                            <i class="fas fa-images w-5"></i>
                            <span>Banner</span>
                        </a>
                    </li>

                    <!-- Settings -->
                    <li>
                                <a href="{{ Route::has('admin.settings.index') ? route('admin.settings.index') : 'javascript:void(0)' }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.settings.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                            <i class="fas fa-cog w-5"></i>
                            <span>Cài đặt</span>
                        </a>
                    </li>

                    <!-- Audit Logs -->
                    <li>
                        <a href="{{ Route::has('admin.audit-logs.index') ? route('admin.audit-logs.index') : 'javascript:void(0)' }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.audit-logs.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                            <i class="fas fa-history w-5"></i>
                            <span>Nhật ký</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Divider -->
            <div class="mt-6 px-4">
                <hr class="border-gray-700">
            </div>

            <!-- Logout -->
            <div class="p-4 mt-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-gray-800 rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Mobile Overlay -->
        <div
            x-show="sidebarOpen && !isDesktop"
            x-transition.opacity
            @click="closeSidebar()"
            class="fixed inset-0 z-40 bg-black/40 md:hidden"
        ></div>

        <!-- Mobile Sidebar Toggle Button (shown on mobile) -->
        <button @click="toggleSidebar()"
                class="fixed top-4 left-4 z-40 md:hidden p-2 rounded-lg bg-red-600 text-white">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col md:ml-64">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4 flex items-center justify-between">
                    <!-- Page Title -->
                    <div class="hidden md:block">
                        <h2 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Tổng quan')</h2>
                    </div>

                    <!-- Right Side - Admin Info -->
                    <div class="flex items-center gap-4 ml-auto">
                        <!-- Time -->
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-clock mr-2"></i>
                            <span id="current-time"></span>
                        </div>

                        <!-- Divider -->
                        <div class="h-8 w-px bg-gray-200"></div>

                        <!-- Admin Profile -->
                        <div class="flex items-center gap-3" x-data="{ profileOpen: false }">
                            <div class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center font-bold">
                                {{ auth()->user()->name ? strtoupper(auth()->user()->name[0]) : 'A' }}
                            </div>
                            <div class="hidden sm:block">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->role ?? 'Quản trị' }}</p>
                            </div>

                            <!-- Profile Dropdown -->
                            <button @click="profileOpen = !profileOpen"
                                    class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <div x-show="profileOpen"
                                 @click.away="profileOpen = false"
                                 class="absolute top-full right-4 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
                                 x-transition>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-user mr-2"></i> Hồ sơ
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="flex-1 overflow-auto">
                <div class="p-6">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update time in top bar
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('vi-VN', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('current-time').textContent = timeString;
        }
        updateTime();
        setInterval(updateTime, 1000);

    </script>

    @livewireScripts

    @yield('scripts')
</body>
</html>
