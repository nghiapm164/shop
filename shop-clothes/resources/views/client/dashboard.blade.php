<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard | Shop Nam</title>
    <style>
        :root { font-family: 'Inter', system-ui, sans-serif; color-scheme: dark; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: #07111f; color: #f8fafc; }
        .page-container { padding: 28px 24px; max-width: 1120px; margin: 0 auto; }
        .topbar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 18px; }
        .brand { display: inline-flex; flex-direction: column; gap: 8px; }
        .brand h1 { margin: 0; font-size: clamp(1.9rem, 3vw, 2.6rem); }
        .brand p { margin: 0; color: #cbd5e1; }
        .button-logout { display: inline-flex; align-items: center; justify-content: center; padding: 14px 20px; border: none; border-radius: 14px; background: #38bdf8; color: #111827; font-weight: 700; cursor: pointer; }
        .grid { display: grid; gap: 22px; margin-top: 28px; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); }
        .card { background: rgba(15, 23, 42, .92); border: 1px solid rgba(148,163,184,.12); border-radius: 28px; padding: 28px; box-shadow: 0 22px 60px rgba(0,0,0,.25); }
        .card h2 { margin: 0 0 14px; font-size: 1.35rem; }
        .card p { margin: 0; color: #cbd5e1; line-height: 1.8; }
        .highlight { color: #34d399; font-weight: 700; }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="topbar">
            <div class="brand">
                <h1>Client Dashboard</h1>
                <p>Khám phá phong cách nam tính và theo dõi đơn hàng ngay trong tài khoản của bạn.</p>
            </div>
            <form method="POST" action="{{ route('client.logout') }}">
                @csrf
                <button type="submit" class="button-logout">Đăng xuất</button>
            </form>
        </div>

        <div class="grid">
            <div class="card">
                <h2>Chào mừng</h2>
                <p>Bạn đã đến đúng nơi để quản lý đơn hàng và lịch sử mua sắm sản phẩm thời trang nam.</p>
            </div>
            <div class="card">
                <h2>Tiếp tục mua sắm</h2>
                <p>Khám phá bộ sưu tập mới nhất, cập nhật xu hướng và hoàn tất thanh toán an toàn.</p>
            </div>
        </div>
    </div>
</body>
</html>
