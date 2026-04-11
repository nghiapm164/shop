<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Shop Nam</title>
    <style>
        :root { font-family: 'Inter', system-ui, sans-serif; color-scheme: dark; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: #07111f; color: #f8fafc; }
        .page-container { padding: 28px 24px; max-width: 1120px; margin: 0 auto; }
        .topbar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 18px; }
        .brand { display: inline-flex; flex-direction: column; gap: 8px; }
        .brand h1 { margin: 0; font-size: clamp(1.9rem, 3vw, 2.6rem); }
        .brand p { margin: 0; color: #cbd5e1; }
        .button-logout { display: inline-flex; align-items: center; justify-content: center; padding: 14px 20px; border: none; border-radius: 14px; background: #f59e0b; color: #111827; font-weight: 700; cursor: pointer; }
        .grid { display: grid; gap: 22px; margin-top: 28px; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); }
        .card { background: rgba(15, 23, 42, .92); border: 1px solid rgba(148,163,184,.12); border-radius: 28px; padding: 28px; box-shadow: 0 22px 60px rgba(0,0,0,.25); }
        .card h2 { margin: 0 0 14px; font-size: 1.35rem; }
        .card p { margin: 0; color: #cbd5e1; line-height: 1.8; }
        .highlight { color: #f59e0b; font-weight: 700; }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="topbar">
            <div class="brand">
                <h1>Admin Dashboard</h1>
                <p>Điều hành cửa hàng thời trang nam với hiệu suất, đơn giản và trực quan.</p>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="button-logout">Đăng xuất</button>
            </form>
        </div>

        <div class="grid">
            <div class="card">
                <h2>Chào mừng Admin</h2>
                <p>Bạn đang ở khu vực quản trị riêng. Quản lý sản phẩm, đơn hàng và khách hàng của shop quần áo nam tại đây.</p>
            </div>
            <div class="card">
                <h2>Trang nhanh</h2>
                <p>Thêm tính năng mới như quản lý sản phẩm, báo cáo đơn hàng, hoặc kiểm tra tồn kho cho cửa hàng.</p>
            </div>
        </div>
    </div>
</body>
</html>
