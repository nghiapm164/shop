@extends('layouts.app')

@section('meta_title', 'Đăng nhập | MODESPORT')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <span class="text-3xl font-black tracking-tighter text-slate-900">
                    MODE<span class="text-red-500">SPORT</span>
                </span>
            </a>
            <p class="mt-2 text-sm text-slate-500">Đăng nhập để mua sắm và quản lý tài khoản</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-lg p-6 md:p-8">
            {{-- Error Messages --}}
            @if (session('status'))
                <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc ps-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Mật khẩu</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="••••••••"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                </div>

                <div class="flex items-center justify-between">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                        Ghi nhớ đăng nhập
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-red-600 hover:text-red-700 font-medium">Quên mật khẩu?</a>
                    @endif
                </div>

                <button type="submit" class="w-full rounded-xl bg-red-500 hover:bg-red-600 text-white py-3 font-bold text-sm transition shadow-sm hover:shadow-md">
                    Đăng nhập
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="bg-white px-3 text-slate-400 font-medium">hoặc</span>
                </div>
            </div>

            {{-- Register Link --}}
            <div class="text-center">
                <p class="text-sm text-slate-500">
                    Chưa có tài khoản?
                    <a href="{{ route('register') }}" class="font-semibold text-red-600 hover:text-red-700">Đăng ký ngay</a>
                </p>
            </div>
        </div>

        {{-- Back to Home --}}
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-sm text-slate-500 hover:text-slate-700 font-medium">
                ← Quay về trang chủ
            </a>
        </div>
    </div>
</div>
@endsection
