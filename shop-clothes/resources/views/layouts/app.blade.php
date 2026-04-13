<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    <title>@yield('meta_title', config('app.name', 'SportWear Shop'))</title>
    <meta name="description" content="@yield('meta_description', 'Cửa hàng quần áo thể thao nam chất lượng cao')">
    <meta name="keywords" content="@yield('meta_keywords', 'quần áo thể thao, quần lót nam, áo thun')">
    <meta name="author" content="SportWear Shop">
    
    <!-- Open Graph -->
    <meta property="og:title" content="@yield('meta_title', config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', 'Cửa hàng quần áo thể thao nam')">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/logo.png'))">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|space-grotesk:500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <!-- Additional Styles -->
    <style>
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .transition-all { transition: all 0.3s ease-in-out; }
        .btn-ghost { padding: 0.5rem 1rem; color: #374151; border-radius: 0.75rem; font-weight: 600; transition: all 0.3s ease-in-out; }
        .btn-ghost:hover { background-color: #f3f4f6; }
        .badge { display: inline-block; padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 600; border-radius: 9999px; }
        .badge-primary { background-color: #fef2f2; color: #dc2626; }
        .badge-success { background-color: #f0fdf4; color: #16a34a; }
        .badge-warning { background-color: #fefce8; color: #ca8a04; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        @yield('styles')
    </style>
</head>
<body class="antialiased text-gray-900 store-shell">
    <!-- Navigation -->
    <x-navbar />
    
    <!-- Main Content -->
    <main class="min-h-screen relative z-10">
        @yield('content')
        {{ $slot ?? '' }}
    </main>
    
    <!-- Footer -->
    <x-footer />
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Additional Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.scrollTop = {
                show: false,
                check() {
                    this.show = window.scrollY > 300;
                },
                top() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            };
            window.addEventListener('scroll', () => scrollTop.check());
        });
    </script>
    
    @stack('scripts')
</body>
</html>
