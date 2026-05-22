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
    
    <!-- Fonts: Inter + Be Vietnam Pro (Premium Vietnamese font) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/css/ndstyle.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <!-- Additional Styles -->
    <style>
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
        @yield('styles')
    </style>
</head>
<body class="antialiased text-slate-900 store-shell" x-data="{
    toasts: [],
    pushToast(detail) {
        if (!detail || !detail.message) return;
        const id = Date.now() + Math.floor(Math.random() * 1000);
        const type = detail.type || 'info';
        this.toasts.push({ id, type, message: detail.message });
        setTimeout(() => {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }, 3000);
    },
    removeToast(id) {
        this.toasts = this.toasts.filter(t => t.id !== id);
    }
}" x-on:notify.window="pushToast($event.detail)">
    <!-- Navigation (hidden on homepage which has its own header) -->
    @if(!request()->routeIs('home'))
        <x-navbar />
    @endif
    
    <!-- Main Content -->
    <main class="min-h-screen relative z-10">
        @yield('content')
        {{ $slot ?? '' }}
    </main>
    
    <!-- Footer -->
    <x-footer />

    <!-- Toast Notifications -->
    <div class="fixed z-[9999] right-4 top-4 space-y-2 w-[min(92vw,360px)] pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-3 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                class="pointer-events-auto rounded-2xl border px-5 py-4 shadow-xl backdrop-blur-sm text-sm"
                :class="{
                    'bg-emerald-50 border-emerald-200 text-emerald-800': toast.type === 'success',
                    'bg-red-50 border-red-200 text-red-800': toast.type === 'error',
                    'bg-amber-50 border-amber-200 text-amber-800': toast.type === 'warning',
                    'bg-slate-50 border-slate-200 text-slate-800': !['success', 'error', 'warning'].includes(toast.type),
                }"
            >
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex-shrink-0" x-text="toast.type === 'success' ? '✓' : (toast.type === 'error' ? '✕' : (toast.type === 'warning' ? '⚠' : 'ℹ'))"></span>
                    <p class="flex-1 font-medium leading-relaxed" x-text="toast.message"></p>
                    <button type="button" class="flex-shrink-0 opacity-60 hover:opacity-100 transition-opacity" @click="removeToast(toast.id)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </template>
    </div>
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Scroll to Top -->
    <div x-data="{ show: false }" x-on:scroll.window="show = (window.scrollY > 400)">
        <button
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 scale-90"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-90"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="fixed bottom-6 right-6 z-50 inline-flex h-12 w-12 items-center justify-center rounded-full bg-slate-900 text-white shadow-xl hover:bg-red-500 transition-colors duration-300"
            aria-label="Cuộn lên đầu"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
        </button>
    </div>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>