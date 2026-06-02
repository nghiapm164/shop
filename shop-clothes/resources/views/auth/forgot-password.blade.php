@extends('layouts.app')

@section('meta_title', 'Quên mật khẩu - MODESPORT')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <span class="text-3xl font-black tracking-tighter text-slate-900">
                    MODE<span class="text-red-500">SPORT</span>
                </span>
            </a>
            <p class="mt-2 text-sm text-slate-500">Nhập email để nhận link đặt lại mật khẩu</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-lg p-6 md:p-8">
            @if (session('status'))
                <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-700">
                    <p class="font-semibold">{{ session('status') }}</p>
                    @if (session('reset_url'))
                        <p class="mt-2 text-xs text-gray-600">Link đặt lại mật khẩu cho <strong>{{ session('reset_email') }}</strong>:</p>
                        <div class="mt-2 flex items-center gap-2">
                            <a href="{{ session('reset_url') }}" class="flex-1 text-blue-600 underline break-all text-xs">{{ session('reset_url') }}</a>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ session('reset_url') }}').then(() => this.textContent = 'Đã sao chép!')" class="px-3 py-1 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 whitespace-nowrap">Sao chép</button>
                        </div>
                        <p class="mt-3 text-xs text-amber-600">⚠️ Chế độ local: email không gửi được thật. Bấm vào link trên để đặt lại mật khẩu.</p>
                    @endif
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

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        placeholder="name@example.com"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                </div>

                <button type="submit" class="w-full rounded-xl bg-red-500 hover:bg-red-600 text-white py-3 font-bold text-sm transition shadow-sm hover:shadow-md">
                    Gửi link đặt lại mật khẩu
                </button>
            </form>

            <div class="text-center mt-6">
                <a href="{{ route('login') }}" class="text-sm text-red-600 hover:text-red-700 font-medium">
                    ← Quay lại đăng nhập
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
