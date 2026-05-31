<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bảng điều khiển quản trị') - ShopGym</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
        
        /* Sidebar transitions */
        .sidebar-link {
            position: relative;
            transition: all 0.2s ease;
        }
        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: #dc2626;
            border-radius: 0 2px 2px 0;
            transition: height 0.2s ease;
        }
        .sidebar-link:hover::before,
        .sidebar-link.active::before {
            height: 60%;
        }
        .sidebar-link.active {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }
        .sidebar-link:hover:not(.active) {
            background: rgba(255,255,255,0.08);
            color: white;
        }
        
        /* Sidebar group label */
        .sidebar-group-label {
            position: relative;
            padding-left: 16px;
        }
        .sidebar-group-label::before {
            content: '';
            position: absolute;
            left: 16px;
            right: 16px;
            top: 50%;
            height: 1px;
            background: rgba(255,255,255,0.08);
        }
        .sidebar-group-label span {
            position: relative;
            z-index: 1;
            background: #111827;
            padding-right: 8px;
        }
        
        /* Card animations */
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        /* Table row hover */
        .table-row-hover:hover {
            background: #f9fafb;
        }
        
        /* Page transitions */
        .page-enter {
            animation: fadeInUp 0.3s ease-out;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Notification badge pulse */
        .badge-pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #dc2626, #f97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Sidebar logo glow */
        .logo-glow {
            text-shadow: 0 0 20px rgba(220, 38, 38, 0.3);
        }
    </style>

    @yield('styles')
</head>
<body class="bg-gray-50 antialiased">
    <div
        class="flex h-screen overflow-hidden"
        x-data="{
            sidebarOpen: window.innerWidth >= 1024,
            isDesktop: window.innerWidth >= 1024,
            sidebarCollapsed: false,
            toggleSidebar() {
                if (this.isDesktop) {
                    this.sidebarCollapsed = !this.sidebarCollapsed;
                } else {
                    this.sidebarOpen = !this.sidebarOpen;
                }
            },
            closeSidebar() {
                if (!this.isDesktop) this.sidebarOpen = false;
            },
            handleResize() {
                this.isDesktop = window.innerWidth >= 1024;
                if (this.isDesktop) {
                    this.sidebarOpen = true;
                } else {
                    this.sidebarOpen = false;
                }
            }
        }"
        x-init="window.addEventListener('resize', () => handleResize())"
    >
        {{-- ==================== SIDEBAR ==================== --}}
        <aside class="fixed inset-y-0 left-0 z-50 flex flex-col bg-gray-900 text-gray-300 overflow-hidden transition-all duration-300"
               :class="sidebarCollapsed && isDesktop ? 'w-20' : 'w-64'"
               :style="!isDesktop && !sidebarOpen ? 'transform: translateX(-100%)' : 'transform: translateX(0)'">
            
            {{-- Logo --}}
            <div class="flex items-center gap-3 px-5 py-5 border-b border-white/5 flex-shrink-0">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-red-500 to-red-700 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/20">
                    <i class="fas fa-dumbbell text-white text-lg"></i>
                </div>
                <div x-show="!sidebarCollapsed || !isDesktop" x-transition class="overflow-hidden">
                    <h1 class="text-lg font-extrabold text-white tracking-tight logo-glow">ShopGym</h1>
                    <p class="text-[10px] uppercase tracking-widest text-gray-500 font-medium">Admin Panel</p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                {{-- MAIN --}}
                <div x-show="!sidebarCollapsed || !isDesktop" class="sidebar-group-label mb-3 mt-1">
                    <span class="text-[10px] uppercase tracking-widest text-gray-500 font-semibold">Chính</span>
                </div>

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : 'bg-white/5' }}">
                        <i class="fas fa-chart-pie text-sm"></i>
                    </div>
                    <span x-show="!sidebarCollapsed || !isDesktop" x-transition>Tổng quan</span>
                </a>

                {{-- COMMERCE --}}
                <div x-show="!sidebarCollapsed || !isDesktop" class="sidebar-group-label mb-3 mt-5">
                    <span class="text-[10px] uppercase tracking-widest text-gray-500 font-semibold">Thương mại</span>
                </div>

                {{-- Products --}}
                <a href="{{ route('admin.products.index') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-white/20' : 'bg-white/5' }}">
                        <i class="fas fa-box-open text-sm"></i>
                    </div>
                    <span x-show="!sidebarCollapsed || !isDesktop" x-transition>Sản phẩm</span>
                </a>

                {{-- Categories --}}
                <a href="{{ route('admin.categories.index') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-white/20' : 'bg-white/5' }}">
                        <i class="fas fa-sitemap text-sm"></i>
                    </div>
                    <span x-show="!sidebarCollapsed || !isDesktop" x-transition>Danh mục</span>
                </a>

                {{-- Brands --}}
                <a href="{{ route('admin.brands.index') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->routeIs('admin.brands.*') ? 'bg-white/20' : 'bg-white/5' }}">
                        <i class="fas fa-tags text-sm"></i>
                    </div>
                    <span x-show="!sidebarCollapsed || !isDesktop" x-transition>Thương hiệu</span>
                </a>

                {{-- Flash Sale --}}
                <a href="{{ route('admin.flash-sales.index') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('admin.flash-sales.*') ? 'active' : '' }}">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->routeIs('admin.flash-sales.*') ? 'bg-white/20' : 'bg-white/5' }}">
                        <i class="fas fa-bolt text-sm"></i>
                    </div>
                    <span x-show="!sidebarCollapsed || !isDesktop" x-transition>Flash Sale</span>
                </a>

                {{-- Orders --}}
                <a href="{{ route('admin.orders.index') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-white/20' : 'bg-white/5' }}">
                        <i class="fas fa-shopping-bag text-sm"></i>
                    </div>
                    <span x-show="!sidebarCollapsed || !isDesktop" x-transition>Đơn hàng</span>
                </a>

                {{-- Coupons --}}
                <a href="{{ route('admin.coupons.index') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->routeIs('admin.coupons.*') ? 'bg-white/20' : 'bg-white/5' }}">
                        <i class="fas fa-ticket-alt text-sm"></i>
                    </div>
                    <span x-show="!sidebarCollapsed || !isDesktop" x-transition>Mã giảm giá</span>
                </a>

                {{-- PEOPLE --}}
                <div x-show="!sidebarCollapsed || !isDesktop" class="sidebar-group-label mb-3 mt-5">
                    <span class="text-[10px] uppercase tracking-widest text-gray-500 font-semibold">Người dùng</span>
                </div>

                {{-- Users --}}
                <a href="{{ route('admin.users.index') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-white/20' : 'bg-white/5' }}">
                        <i class="fas fa-user-shield text-sm"></i>
                    </div>
                    <span x-show="!sidebarCollapsed || !isDesktop" x-transition>Tài khoản</span>
                </a>

                {{-- Customers --}}
                <a href="{{ route('admin.customers.index') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->routeIs('admin.customers.*') ? 'bg-white/20' : 'bg-white/5' }}">
                        <i class="fas fa-users text-sm"></i>
                    </div>
                    <span x-show="!sidebarCollapsed || !isDesktop" x-transition>Khách hàng</span>
                </a>

                {{-- Reviews --}}
                <a href="{{ route('admin.reviews.index') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->routeIs('admin.reviews.*') ? 'bg-white/20' : 'bg-white/5' }}">
                        <i class="fas fa-star text-sm"></i>
                    </div>
                    <span x-show="!sidebarCollapsed || !isDesktop" x-transition>Đánh giá</span>
                </a>

                {{-- CONTENT --}}
                <div x-show="!sidebarCollapsed || !isDesktop" class="sidebar-group-label mb-3 mt-5">
                    <span class="text-[10px] uppercase tracking-widest text-gray-500 font-semibold">Nội dung</span>
                </div>

                {{-- Banners --}}
                <a href="{{ route('admin.banners.index') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->routeIs('admin.banners.*') ? 'bg-white/20' : 'bg-white/5' }}">
                        <i class="fas fa-images text-sm"></i>
                    </div>
                    <span x-show="!sidebarCollapsed || !isDesktop" x-transition>Banner</span>
                </a>

                {{-- SYSTEM --}}
                <div x-show="!sidebarCollapsed || !isDesktop" class="sidebar-group-label mb-3 mt-5">
                    <span class="text-[10px] uppercase tracking-widest text-gray-500 font-semibold">Hệ thống</span>
                </div>

                {{-- Settings --}}
                <a href="{{ route('admin.settings.index') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->routeIs('admin.settings.*') ? 'bg-white/20' : 'bg-white/5' }}">
                        <i class="fas fa-cog text-sm"></i>
                    </div>
                    <span x-show="!sidebarCollapsed || !isDesktop" x-transition>Cài đặt</span>
                </a>

                {{-- Audit Logs --}}
                <a href="{{ route('admin.audit-logs.index') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->routeIs('admin.audit-logs.*') ? 'bg-white/20' : 'bg-white/5' }}">
                        <i class="fas fa-history text-sm"></i>
                    </div>
                    <span x-show="!sidebarCollapsed || !isDesktop" x-transition>Nhật ký</span>
                </a>
            </nav>

            {{-- Sidebar Footer --}}
            <div class="border-t border-white/5 p-3 flex-shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-400 hover:text-red-400 transition-colors">
                        <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </div>
                        <span x-show="!sidebarCollapsed || !isDesktop" x-transition>Đăng xuất</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen && !isDesktop"
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="closeSidebar()"
             class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden">
        </div>

        {{-- ==================== MAIN CONTENT ==================== --}}
        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300"
             :class="isDesktop ? (sidebarCollapsed ? 'ml-20' : 'ml-64') : 'ml-0'">
            
            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-gray-200/80 shadow-sm">
                <div class="flex items-center justify-between px-4 lg:px-6 py-3">
                    {{-- Left: Toggle + Breadcrumb --}}
                    <div class="flex items-center gap-3">
                        <button @click="toggleSidebar()"
                                class="p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                            <i class="fas" :class="isDesktop ? (sidebarCollapsed ? 'fa-angles-right' : 'fa-angles-left') : 'fa-bars'" class="text-lg"></i>
                        </button>

                        <div class="hidden sm:flex items-center gap-2 text-sm">
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-home"></i>
                            </a>
                            <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
                            <span class="text-gray-700 font-medium">@yield('page-title', 'Tổng quan')</span>
                        </div>
                    </div>

                    {{-- Right: Actions --}}
                    <div class="flex items-center gap-2">
                        {{-- Search --}}
                        <div class="hidden md:block relative">
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input type="text" placeholder="Tìm kiếm..."
                                       class="w-56 pl-9 pr-4 py-2 text-sm bg-gray-100 border-0 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:bg-white transition-all">
                            </div>
                        </div>

                        {{-- Notifications --}}
                        <div class="relative" x-data="{ notifOpen: false }">
                            <button @click="notifOpen = !notifOpen" class="relative p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full badge-pulse"></span>
                            </button>
                            <div x-show="notifOpen" @click.away="notifOpen = false"
                                 x-transition
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden z-50">
                                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                                    <h4 class="font-semibold text-gray-900">Thông báo</h4>
                                    <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-medium">Mới</span>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <div class="px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer border-b border-gray-50">
                                        <p class="text-sm text-gray-700">Đơn hàng mới vừa được tạo</p>
                                        <p class="text-xs text-gray-400 mt-1">Vài phút trước</p>
                                    </div>
                                </div>
                                <div class="px-4 py-2 bg-gray-50 text-center">
                                    <a href="{{ route('admin.orders.index') }}" class="text-xs text-red-600 font-medium hover:underline">Xem tất cả</a>
                                </div>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="h-8 w-px bg-gray-200 mx-1"></div>

                        {{-- Admin Profile --}}
                        <div class="relative" x-data="{ profileOpen: false }">
                            <button @click="profileOpen = !profileOpen" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-gray-100 transition-colors">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-red-500 to-red-700 text-white flex items-center justify-center font-bold text-sm shadow-md shadow-red-500/20">
                                    {{ auth()->user()->name ? strtoupper(mb_substr(auth()->user()->name, 0, 1)) : 'A' }}
                                </div>
                                <div class="hidden lg:block text-left">
                                    <p class="text-sm font-semibold text-gray-900 leading-tight">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-gray-400 leading-tight">{{ ucfirst(auth()->user()->role ?? 'admin') }}</p>
                                </div>
                                <i class="fas fa-chevron-down text-[10px] text-gray-400 hidden lg:block"></i>
                            </button>

                            <div x-show="profileOpen"
                                 @click.away="profileOpen = false"
                                 x-transition
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden z-50">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="py-1">
                                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-user w-4 text-gray-400"></i> Hồ sơ cá nhân
                                    </a>
                                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-cog w-4 text-gray-400"></i> Cài đặt
                                    </a>
                                </div>
                                <div class="border-t border-gray-100 py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <i class="fas fa-sign-out-alt w-4"></i> Đăng xuất
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-auto">
                <div class="p-4 lg:p-6 page-enter">
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-transition
                             x-init="setTimeout(() => show = false, 4000)"
                             class="mb-4 flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>{{ session('success') }}</span>
                            <button @click="show = false" class="ml-auto text-green-400 hover:text-green-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div x-data="{ show: true }" x-show="show" x-transition
                             x-init="setTimeout(() => show = false, 5000)"
                             class="mb-4 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                            <span>{{ session('error') }}</span>
                            <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>

            {{-- Footer --}}
            <footer class="border-t border-gray-200 bg-white px-6 py-3">
                <div class="flex items-center justify-between text-xs text-gray-400">
                    <span>&copy; {{ date('Y') }} ShopGym. All rights reserved.</span>
                    <span>Admin Panel v1.0</span>
                </div>
            </footer>
        </div>
    </div>

    <script>
        function updateTime() {
            const el = document.getElementById('current-time');
            if (el) {
                const now = new Date();
                el.textContent = now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }
        }
        updateTime();
        setInterval(updateTime, 1000);
    </script>

    @livewireScripts

    @yield('scripts')
</body>
</html>