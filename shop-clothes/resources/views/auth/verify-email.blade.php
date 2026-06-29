@extends('layouts.app')

@section('meta_title', 'Xác thực email - MODESPORT')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <span class="text-3xl font-black tracking-tighter text-slate-900">
                    MODE<span class="text-red-500">SPORT</span>
                </span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-lg p-6 md:p-8">
            {{-- Local verify URL (shown when mail driver is 'log') --}}
            @if (session('local_verify_url'))
                <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-700">
                    <p class="font-semibold mb-2">🔗 Chế độ Local: Link xác thực email</p>
                    <p class="text-xs text-gray-600 mb-2">Email <strong>{{ session('local_verify_email') }}</strong> - Bấm vào link bên dưới để xác thực:</p>
                    <div class="mt-2 flex items-center gap-2">
                        <a href="{{ session('local_verify_url') }}" class="flex-1 text-blue-600 underline break-all text-xs">{{ session('local_verify_url') }}</a>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ session('local_verify_url') }}').then(() => this.textContent = 'Đã sao chép!')" class="px-3 py-1 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 whitespace-nowrap">Sao chép</button>
                    </div>
                    <p class="mt-3 text-xs text-amber-600">⚠️ Chế độ local: email không gửi được thật. Bấm vào link trên để xác thực.</p>
                </div>
            @endif

            <div class="text-center mb-6">
                <div class="w-16 h-16 mx-auto rounded-full bg-blue-100 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 mb-2">Xác thực email của bạn</h2>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Cảm ơn bạn đã đăng ký! Vui lòng xác thực địa chỉ email bằng cách nhấn vào đường link chúng tôi đã gửi cho bạn.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    <p class="font-semibold">✅ Link xác thực mới đã được gửi đến địa chỉ email bạn đã đăng ký.</p>
                </div>
            @endif

            <div class="space-y-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-xl bg-red-500 hover:bg-red-600 text-white py-3 font-bold text-sm transition shadow-sm hover:shadow-md">
                        Gửi lại email xác thực
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-xl border border-slate-300 text-slate-700 py-3 font-semibold text-sm hover:bg-slate-50 transition">
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
