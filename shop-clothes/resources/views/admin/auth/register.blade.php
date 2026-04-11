<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Đăng ký | Shop Nam</title>
    <style>
        :root { color-scheme: dark; font-family: 'Inter', system-ui, sans-serif; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: radial-gradient(circle at top right, rgba(248, 113, 113, .12), transparent 20%), #07111f; color: #f8fafc; }
        .page-shell { display: grid; place-items: center; min-height: 100vh; padding: 24px; }
        .auth-card { width: min(100%, 520px); background: rgba(15, 23, 42, .96); border-radius: 30px; overflow: hidden; box-shadow: 0 40px 120px rgba(0, 0, 0, .35); border: 1px solid rgba(148, 163, 184, .12); }
        .hero { padding: 36px 32px 28px; text-align: center; background: linear-gradient(180deg, rgba(30, 41, 59, .95), rgba(15, 23, 42, .95)); }
        .hero h1 { margin: 0; font-size: clamp(2rem, 4vw, 2.7rem); letter-spacing: .04em; }
        .hero p { margin: 14px auto 0; max-width: 420px; color: #cbd5e1; line-height: 1.7; }
        .form-card { padding: 32px 32px 36px; }
        .form-group { margin-top: 18px; }
        label { display: block; margin-bottom: 10px; color: #e2e8f0; font-size: .95rem; }
        input { width: 100%; padding: 14px 16px; border-radius: 16px; border: 1px solid rgba(148, 163, 184, .2); background: rgba(255, 255, 255, .06); color: #f8fafc; transition: .2s; }
        input:focus { border-color: #f59e0b; box-shadow: 0 0 0 4px rgba(245, 158, 11, .12); outline: none; }
        button { width: 100%; margin-top: 22px; padding: 15px 18px; border: none; border-radius: 16px; background: linear-gradient(135deg, #f59e0b, #ea580c); color: #111827; font-weight: 700; cursor: pointer; transition: transform .2s, filter .2s; }
        button:hover { transform: translateY(-1px); filter: brightness(1.04); }
        .links { margin-top: 26px; display: grid; gap: 10px; text-align: center; color: #94a3b8; font-size: .95rem; }
        .links a { color: #f8fafc; text-decoration: none; opacity: .88; }
        .links a:hover { opacity: 1; }
        .error { padding: 16px 18px; margin-bottom: 20px; border-radius: 16px; background: rgba(239, 68, 68, .12); color: #fecaca; border: 1px solid rgba(239, 68, 68, .3); }
    </style>
</head>
<body>
    <div class="page-shell">
        <div class="auth-card">
            <div class="hero">
                <h1>Đăng ký Admin</h1>
                <p>Đăng ký để quản lý cửa hàng thời trang nam, đơn hàng và kho hàng chuyên nghiệp.</p>
            </div>
            <div class="form-card">
                @if ($errors->any())
                    <div class="error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.register') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input id="password" type="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Xác nhận mật khẩu</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required>
                    </div>

                    <button type="submit">Tạo tài khoản Admin</button>
                </form>

                <div class="links">
                    <a href="{{ route('admin.login') }}">Đã có tài khoản? Đăng nhập</a>
                    <a href="{{ route('client.register') }}">Đăng ký client</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
