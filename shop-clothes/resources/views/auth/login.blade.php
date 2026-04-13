<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập khách hàng | SportWear</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-white">
    <div class="min-h-screen grid lg:grid-cols-2">
        <div class="hidden lg:flex relative overflow-hidden bg-gradient-to-br from-red-600 via-orange-500 to-amber-400 p-10">
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 20%, #fff 2px, transparent 2px); background-size: 30px 30px;"></div>
            <div class="relative z-10 self-end max-w-xl">
                <p class="uppercase tracking-[0.3em] text-white/80 text-xs font-semibold">Trung tâm SportWear</p>
                <h1 class="text-5xl font-black leading-tight mt-3">Đăng nhập để bứt tốc mỗi ngày.</h1>
                <p class="mt-5 text-white/90 text-lg">Mở khóa ưu đãi cá nhân hóa, theo dõi đơn hàng nhanh và nhận các bộ sưu tập thể thao mới nhất.</p>
            </div>
        </div>

        <div class="flex items-center justify-center p-6 md:p-10">
            <div class="w-full max-w-md bg-white text-slate-900 rounded-2xl shadow-2xl p-7 md:p-8 border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-extrabold">Đăng nhập khách hàng</h2>
                        <p class="text-sm text-slate-500 mt-1">Chào mừng quay lại SportWear</p>
                    </div>
                    <a href="{{ route('home') }}" class="text-sm text-red-600 hover:text-red-700 font-semibold">Trang chủ</a>
                </div>

                @if (session('status'))
                    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                        <ul class="list-disc ps-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('client.login.submit') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold mb-1">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold mb-1">Mật khẩu</label>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                            <input id="remember" type="checkbox" name="remember" class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                            Ghi nhớ đăng nhập
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-red-600 hover:text-red-700">Quên mật khẩu?</a>
                        @endif
                    </div>

                    <button type="submit" class="w-full rounded-lg bg-red-600 hover:bg-red-700 text-white py-2.5 font-bold transition">
                        Đăng nhập ngay
                    </button>
                </form>

                <div class="mt-5 text-sm text-slate-600 flex items-center justify-between">
                    <span>Đăng nhập quản trị?</span>
                    <a href="{{ route('admin.login') }}" class="font-semibold text-slate-900 hover:text-red-600">Vào cổng quản trị</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
