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
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#ffe5ec',
                            100: '#ffb3d0',
                            200: '#ff80b5',
                            300: '#ff4d99',
                            400: '#f21a7d',
                            500: '#e11d48',
                            600: '#be1642',
                            700: '#9b0f3c',
                            800: '#780835',
                            900: '#55012f',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
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
        .transition-all { @apply transition-all duration-300 ease-in-out; }
        .btn-primary { @apply px-4 py-2 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-all active:scale-95; }
        .btn-secondary { @apply px-4 py-2 border border-gray-300 text-gray-800 rounded-lg font-medium hover:border-gray-400 hover:bg-gray-50 transition-all; }
        .btn-ghost { @apply px-4 py-2 text-gray-700 rounded-lg font-medium hover:bg-gray-100 transition-all; }
        .badge { @apply inline-block px-3 py-1 text-xs font-semibold rounded-full; }
        .badge-primary { @apply badge bg-red-50 text-red-600; }
        .badge-success { @apply badge bg-green-50 text-green-600; }
        .badge-warning { @apply badge bg-yellow-50 text-yellow-600; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        @yield('styles')
    </style>
</head>
<body class="font-sans antialiased bg-white text-gray-900" x-cloak>
    <!-- Navigation -->
    <x-navbar />
    
    <!-- Main Content -->
    <main class="min-h-screen">
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
