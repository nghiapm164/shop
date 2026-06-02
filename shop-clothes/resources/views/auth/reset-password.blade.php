@extends('layouts.app')

@section('meta_title', 'Đặt lại mật khẩu - MODESPORT')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <span class="text-3xl font-black tracking-tighter text-slate-900">
                    MODE<span class="text-red-500">SPORT</span>
                </span>
            </a>
            <p class="mt-2 text-sm text-slate-500">Nhập mật khẩu mới cho tài khoản của bạn</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-lg p-6 md:p-8">
            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc ps-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Mật khẩu mới</label>
                    <input id="password" name="password" type="password" required
                        placeholder="••••••••"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Xác nhận mật khẩu</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        placeholder="••••••••"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                </div>

                <button type="submit" class="w-full rounded-xl bg-red-500 hover:bg-red-600 text-white py-3 font-bold text-sm transition shadow-sm hover:shadow-md">
                    Đặt lại mật khẩu
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
