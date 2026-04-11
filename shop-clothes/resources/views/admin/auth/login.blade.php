<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Đăng nhập | Shop Nam</title>
    <style>
        :root {
            color-scheme: dark;
            font-family: 'Inter', system-ui, sans-serif;
            background: #0b1220;
            color: #f8fafc;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(circle at top left, rgba(245, 158, 11, .12), transparent 24%),
                        radial-gradient(circle at bottom right, rgba(79, 70, 229, .12), transparent 24%),
                        #07111f;
        }
        .page-shell {
            display: grid;
            place-items: center;
            min-height: 100vh;
            padding: 24px;
        }
        .auth-card {
            width: min(100%, 520px);
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 40px 120px rgba(0, 0, 0, .35);
            border: 1px solid rgba(148, 163, 184, .12);
            background: rgba(15, 23, 42, .96);
        }
        .hero {
            padding: 36px 32px 28px;
            text-align: center;
            background: linear-gradient(180deg, rgba(30, 41, 59, .95), rgba(15, 23, 42, .95));
        }
        .hero h1 {
            margin: 0;
            font-size: clamp(2rem, 4vw, 2.7rem);
            letter-spacing: .04em;
        }
        .hero p {
            margin: 14px auto 0;
            max-width: 420px;
            color: #cbd5e1;
            line-height: 1.75;
        }
        .form-card {
            padding: 32px 32px 36px;
        }
        .form-group {
            margin-top: 18px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-size: .95rem;
            color: #e2e8f0;
        }
        input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid rgba(148, 163, 184, .2);
            background: rgba(255, 255, 255, .06);
            color: #f8fafc;
            transition: .2s;
        }
        input:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, .12);
            outline: none;
        }
        button {
            width: 100%;
            margin-top: 22px;
            padding: 15px 18px;
            border: none;
            border-radius: 16px;
            background: linear-gradient(135deg, #f59e0b, #ea580c);
            color: #111827;
            font-weight: 700;
            letter-spacing: .02em;
            cursor: pointer;
            transition: transform .2s, filter .2s;
        }
        button:hover {
            transform: translateY(-1px);
            filter: brightness(1.04);
        }
        .form-group .checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #cbd5e1;
            font-size: .95rem;
        }
        .form-group input[type='checkbox'] {
            width: 18px;
            height: 18px;
            accent-color: #f59e0b;
        }
        .links {
            margin-top: 26px;
            display: grid;
            gap: 10px;
            text-align: center;
            color: #94a3b8;
            font-size: .95rem;
        }
        .links a {
            color: #f8fafc;
            text-decoration: none;
            opacity: .88;
        }
        .links a:hover {
            opacity: 1;
        }
        .alert, .error {
            border-radius: 16px;
            padding: 16px 18px;
            margin-bottom: 20px;
            font-size: .95rem;
            line-height: 1.6;
        }
        .alert {
            background: rgba(16, 185, 129, .12);
            color: #a7f3d0;
            border: 1px solid rgba(16, 185, 129, .25);
        }
        .error {
            background: rgba(239, 68, 68, .12);
            color: #fecaca;
            border: 1px solid rgba(239, 68, 68, .3);
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <div class="auth-card">
            <div class="hero">
                <h1>Admin Đăng nhập</h1>
                <p>Tiếp cận nhanh giao diện quản trị bán hàng, kho, đơn và sản phẩm thời trang nam.</p>
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

                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input id="password" type="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember">
                            Ghi nhớ đăng nhập
                        </label>
                    </div>

                    <button type="submit">Đăng nhập</button>
                </form>

                <div class="links">
                    <a href="{{ route('admin.register') }}">Tạo tài khoản Admin</a>
                    <a href="{{ route('admin.password.request') }}">Quên mật khẩu?</a>
                    <a href="{{ route('client.login') }}">Chuyển sang trang client</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
