@props(['type' => 'info', 'title' => null, 'closeable' => true])

@php
    $types = [
        'success' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'text' => 'text-green-800', 'icon' => '✓', 'icon_color' => 'text-green-500'],
        'error' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'text' => 'text-red-800', 'icon' => '✕', 'icon_color' => 'text-red-500'],
        'warning' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'text' => 'text-yellow-800', 'icon' => '⚠', 'icon_color' => 'text-yellow-500'],
        'info' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-800', 'icon' => 'ℹ', 'icon_color' => 'text-blue-500'],
    ];
    
    $style = $types[$type] ?? $types['info'];
@endphp

<div {{ $attributes->merge(['class' => "alert alert-{$type} {$style['bg']} {$style['border']} {$style['text']} rounded-lg border p-4"]) }} 
    x-data="{ show: true }" x-show="show" x-transition>
    
    <div class="flex items-start gap-3">
        {{-- Icon --}}
        <span class="flex-shrink-0 text-lg {{ $style['icon_color'] }}">
            {{ $style['icon'] }}
        </span>
        
        {{-- Content --}}
        <div class="flex-1">
            @if($title)
                <h3 class="font-semibold mb-1">{{ $title }}</h3>
            @endif
            <div class="text-sm">
                {{ $slot }}
            </div>
        </div>
        
        {{-- Close Button --}}
        @if($closeable)
            <button @click="show = false" class="flex-shrink-0 text-lg opacity-60 hover:opacity-100 transition-opacity">
                ✕
            </button>
        @endif
    </div>
</div>

@php
    // Flash message from session
    $success = session('success');
    $error = session('error');
    $warning = session('warning');
    $info = session('info');
@endphp

{{-- Success Flash Message --}}
@if($success)
    <div class="mb-4 alert alert-success bg-green-50 border border-green-200 text-green-800 rounded-lg p-4" 
        x-data="{ show: true }" x-show="show" x-transition
        @load="setTimeout(() => show = false, 5000)">
        <div class="flex items-center gap-3">
            <span class="flex-shrink-0 text-lg text-green-500">✓</span>
            <div class="flex-1">{{ $success }}</div>
            <button @click="show = false" class="flex-shrink-0 text-lg opacity-60 hover:opacity-100">✕</button>
        </div>
    </div>
@endif

{{-- Error Flash Message --}}
@if($error)
    <div class="mb-4 alert alert-error bg-red-50 border border-red-200 text-red-800 rounded-lg p-4" 
        x-data="{ show: true }" x-show="show" x-transition>
        <div class="flex items-center gap-3">
            <span class="flex-shrink-0 text-lg text-red-500">✕</span>
            <div class="flex-1">{{ $error }}</div>
            <button @click="show = false" class="flex-shrink-0 text-lg opacity-60 hover:opacity-100">✕</button>
        </div>
    </div>
@endif

{{-- Warning Flash Message --}}
@if($warning)
    <div class="mb-4 alert alert-warning bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg p-4" 
        x-data="{ show: true }" x-show="show" x-transition
        @load="setTimeout(() => show = false, 5000)">
        <div class="flex items-center gap-3">
            <span class="flex-shrink-0 text-lg text-yellow-500">⚠</span>
            <div class="flex-1">{{ $warning }}</div>
            <button @click="show = false" class="flex-shrink-0 text-lg opacity-60 hover:opacity-100">✕</button>
        </div>
    </div>
@endif

{{-- Info Flash Message --}}
@if($info)
    <div class="mb-4 alert alert-info bg-blue-50 border border-blue-200 text-blue-800 rounded-lg p-4" 
        x-data="{ show: true }" x-show="show" x-transition
        @load="setTimeout(() => show = false, 5000)">
        <div class="flex items-center gap-3">
            <span class="flex-shrink-0 text-lg text-blue-500">ℹ</span>
            <div class="flex-1">{{ $info }}</div>
            <button @click="show = false" class="flex-shrink-0 text-lg opacity-60 hover:opacity-100">✕</button>
        </div>
    </div>
@endif
