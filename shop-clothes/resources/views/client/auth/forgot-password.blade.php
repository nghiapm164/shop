<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu Client | Shop Nam</title>
    <style>
        :root { color-scheme: dark; font-family: 'Inter', system-ui, sans-serif; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: radial-gradient(circle at bottom right, rgba(59, 130, 246, .12), transparent 18%), #07111f; color: #f8fafc; }
        .page-shell { display: grid; place-items: center; min-height: 100vh; padding: 24px; }
        .auth-card { width: min(100%, 520px); background: rgba(15, 23, 42, .96); border-radius: 30px; overflow: hidden; box-shadow: 0 40px 120px rgba(0, 0, 0, .35); border: 1px solid rgba(148,163,184,.12); }
        .hero { padding: 36px 32px 28px; text-align: center; background: linear-gradient(180deg, rgba(30,41,59,.95), rgba(15,23,42,.95)); }
        .hero h1 { margin: 0; font-size: clamp(2rem, 4vw, 2.7rem); }
        .hero p { margin: 14px auto 0; max-width: 420px; color: #cbd5e1; line-height: 1.7; }
        .form-card { padding: 32px 32px 36px; }
        .form-group { margin-top: 18px; }
        label { display: block; margin-bottom: 10px; color: #e2e8f0; font-size: .95rem; }
        input { width: 100%; padding: 14px 16px; border-radius: 16px; border: 1px solid rgba(148, 163, 184, .2); background: rgba(255,255,255,.06); color: #f8fafc; }
        input:focus { border-color: #60a5fa; box-shadow: 0 0 0 4px rgba(96,165,250,.12); outline: none; }
        button { width: 100%; margin-top: 22px; padding: 15px 18px; border: none; border-radius: 16px; background: linear-gradient(135deg, #60a5fa, #2563eb); color: #111827; font-weight: 700; cursor: pointer; }
        button:hover { filter: brightness(1.05); }
        .links { margin-top: 26px; text-align: center; color: #94a3b8; font-size: .95rem; }
        .links a { color: #f8fafc; text-decoration: none; opacity: .9; }
        .links a:hover { opacity: 1; }
        .alert, .error { padding: 16px 18px; border-radius: 16px; margin-bottom: 20px; font-size: .95rem; line-height: 1.6; }
        .alert { background: rgba(16, 185, 129, .12); color: #a7f3d0; border: 1px solid rgba(16,185,129,.25); }
        .error { background: rgba(239, 68, 68, .12); color: #fecaca; border: 1px solid rgba(239,68,68,.3); }
    </style>
</head>
<body>
    <div class="page-shell">
        <div class="auth-card">
            <div class="hero">
                <h1>Quên mật khẩu Client</h1>
                <p>Nhập email để nhận hướng dẫn khôi phục mật khẩu dễ dàng và nhanh chóng.</p>
            </div>
            <div class="form-card">
                @if(session('status'))
                    <div class="alert">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('client.password.email') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <button type="submit">Gửi liên kết đặt lại mật khẩu</button>
                </form>

                <div class="links">
                    <a href="{{ route('client.login') }}">Quay lại đăng nhập Client</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
