@extends('layouts.app')

@section('meta_title', 'Đăng ký | MODESPORT')

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
            <p class="mt-2 text-sm text-slate-500">Tạo tài khoản để mua sắm dễ dàng hơn</p>
        </div>

        {{-- Register Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-lg p-6 md:p-8">
            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc ps-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Register Form --}}
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Họ và tên</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus placeholder="Nguyễn Văn A"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="name@example.com"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Số điện thoại</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required placeholder="0912345678"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Mật khẩu</label>
                    <input id="password" name="password" type="password" required placeholder="Tối thiểu 8 ký tự"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Xác nhận mật khẩu</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="Nhập lại mật khẩu"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                </div>

                <button type="submit" class="w-full rounded-xl bg-red-500 hover:bg-red-600 text-white py-3 font-bold text-sm transition shadow-sm hover:shadow-md">
                    Tạo tài khoản
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

            {{-- Login Link --}}
            <div class="text-center">
                <p class="text-sm text-slate-500">
                    Đã có tài khoản?
                    <a href="{{ route('login') }}" class="font-semibold text-red-600 hover:text-red-700">Đăng nhập</a>
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
