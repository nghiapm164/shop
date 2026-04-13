<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cổng quản trị | SportWear</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-white">
    <div class="min-h-screen grid lg:grid-cols-2">
        <div class="hidden lg:flex items-center justify-center relative bg-gradient-to-br from-slate-900 via-slate-800 to-red-900 p-10 overflow-hidden">
            <div class="absolute inset-0 opacity-20" style="background-image: linear-gradient(45deg, rgba(255,255,255,.2) 1px, transparent 1px); background-size: 24px 24px;"></div>
            <div class="relative z-10 max-w-xl">
                <p class="text-xs uppercase tracking-[0.35em] text-red-200 font-semibold">Trung tâm điều hành quản trị</p>
                <h1 class="text-5xl font-black mt-4 leading-tight">Quản trị thông minh. Vận hành bứt phá.</h1>
                <p class="mt-5 text-slate-300 text-lg">Theo dõi đơn hàng, quản lý sản phẩm và tăng tốc doanh thu với bảng điều khiển tập trung.</p>
            </div>
        </div>

        <div class="flex items-center justify-center p-6 md:p-10">
            <div class="w-full max-w-md rounded-2xl bg-white text-slate-900 border border-slate-100 shadow-2xl p-8">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-extrabold">Đăng nhập Admin</h2>
                        <p class="text-sm text-slate-500 mt-1">Khu vực dành cho quản trị viên</p>
                    </div>
                    <a href="{{ route('home') }}" class="text-sm font-semibold text-red-600 hover:text-red-700">Trang chủ</a>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                        <ul class="list-disc ps-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-semibold mb-1">Email quản trị</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold mb-1">Mật khẩu</label>
                        <input id="password" type="password" name="password" required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>

                    <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                        Ghi nhớ đăng nhập
                    </label>

                    <button type="submit" class="w-full rounded-lg bg-slate-900 hover:bg-black text-white py-2.5 font-bold transition">
                        Vào trang tổng quan
                    </button>
                </form>

                <div class="mt-6 text-sm text-slate-600 flex items-center justify-between">
                    <span>Tài khoản khách hàng?</span>
                    <a href="{{ route('client.login') }}" class="font-semibold text-slate-900 hover:text-red-600">Chuyển sang cổng khách hàng</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
