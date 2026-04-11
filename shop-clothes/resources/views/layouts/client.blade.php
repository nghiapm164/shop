<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Shop Nam')</title>
    <style>
        :root {
            color-scheme: light;
            font-family: 'Inter', system-ui, sans-serif;
            background: #f8fbff;
            color: #0f172a;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            background-color: #f8fbff;
            background-image: url('https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=1400&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(248, 251, 255, 0.84);
            pointer-events: none;
        }
        a { color: inherit; text-decoration: none; }
        .page-shell {
            position: relative;
            display: grid;
            grid-template-rows: auto 1fr auto;
            min-height: 100vh;
            padding: 24px;
        }
        .page-shell > * { position: relative; z-index: 1; }
        .site-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            max-width: 1120px;
            width: 100%;
            margin: 0 auto 24px;
        }
        .brand {
            display: inline-flex;
            flex-direction: column;
            gap: 4px;
        }
        .brand strong {
            font-size: clamp(1.5rem, 2.5vw, 2rem);
            letter-spacing: .04em;
        }
        .brand span {
            color: #475569;
            font-size: .95rem;
        }
        .site-main {
            width: min(100%, 1120px);
            margin: 0 auto;
            padding: 28px 0;
        }
        .card {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, .08);
            border-radius: 30px;
            box-shadow: 0 40px 100px rgba(15, 23, 42, .12);
            overflow: hidden;
        }
        .card-inner {
            padding: 36px;
        }
        .page-footer {
            width: min(100%, 1120px);
            margin: 30px auto 0;
            text-align: center;
            color: #64748b;
            font-size: .95rem;
        }
        .hero-title {
            margin: 0 0 8px;
            font-size: clamp(2rem, 4vw, 2.8rem);
            line-height: 1.05;
            color: #0f172a;
        }
        .hero-subtitle {
            margin: 0;
            color: #475569;
            line-height: 1.8;
            max-width: 720px;
        }
        .alert,
        .error {
            padding: 16px 18px;
            border-radius: 18px;
            margin-bottom: 22px;
            font-size: .95rem;
            line-height: 1.6;
        }
        .alert {
            background: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
        }
        .error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }
        .form-group { margin-top: 18px; }
        label {
            display: block;
            margin-bottom: 10px;
            color: #475569;
            font-size: .95rem;
        }
        input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid rgba(100, 116, 139, .2);
            background: #f8fafc;
            color: #0f172a;
        }
        input:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, .16);
            border-color: #38bdf8;
        }
        button {
            width: 100%;
            margin-top: 22px;
            padding: 15px 18px;
            border: none;
            border-radius: 16px;
            background: #0ea5e9;
            color: #ffffff;
            font-weight: 700;
            cursor: pointer;
            transition: transform .2s ease, filter .2s ease;
        }
        button:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
        }
        .helper-links {
            margin-top: 24px;
            display: grid;
            gap: 10px;
            font-size: .95rem;
            color: #475569;
            text-align: center;
        }
        .helper-links a {
            color: #0f172a;
            opacity: .9;
        }
        .helper-links a:hover { opacity: 1; }
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #475569;
            font-size: .95rem;
        }
        .checkbox-label input {
            width: 18px;
            height: 18px;
            accent-color: #0ea5e9;
        }
        @media (max-width: 640px) {
            .site-shell { padding: 18px; }
            .card-inner { padding: 28px; }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <header class="site-header">
            <div class="brand">
                <strong>Shop Nam</strong>
                <span>Thời trang thể thao nam trẻ trung</span>
            </div>
            <div class="brand">
                <a href="{{ route('client.login') }}">Đăng nhập</a>
                <a href="{{ route('client.register') }}">Đăng ký</a>
            </div>
        </header>

        <main class="site-main">
            @yield('content')
        </main>

        <footer class="page-footer">
            © {{ date('Y') }} Shop Nam - Thương hiệu thể thao nam năng động.
        </footer>
    </div>
</body>
</html>
