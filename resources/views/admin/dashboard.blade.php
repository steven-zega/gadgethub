<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - GadgetHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #0f172a; font-family: 'Segoe UI', sans-serif; color: white; overflow-x: hidden; }
        .sidebar { position: fixed; left: 0; top: 0; width: 270px; height: 100vh; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(18px); border-right: 1px solid rgba(255,255,255,0.08); padding: 30px 22px; z-index: 100; }
        .brand { display: flex; align-items: center; gap: 12px; margin-bottom: 45px; font-size: 1.3rem; font-weight: 700; background: linear-gradient(135deg, #3b82f6, #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .nav-link { display: flex; align-items: center; gap: 14px; color: #cbd5e1; padding: 14px 18px; border-radius: 18px; margin-bottom: 12px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; }
        .nav-link:hover, .nav-link.active { background: linear-gradient(135deg, #2563eb, #06b6d4); color: white; transform: translateX(5px); box-shadow: 0 10px 30px rgba(37,99,235,0.25); }
        .main-content { margin-left: 270px; padding: 30px; }
        .topbar { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(16px); border-radius: 28px; padding: 24px 28px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
        .card-modern { background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(16px); border-radius: 28px; padding: 28px; box-shadow: 0 15px 40px rgba(0,0,0,0.25); transition: transform 0.3s ease; }
        .card-modern:hover { transform: translateY(-5px); border-color: rgba(59, 130, 246, 0.4); }
        .btn-modern { background: linear-gradient(135deg, #2563eb, #06b6d4); border: none; color: white; padding: 12px 24px; border-radius: 16px; font-weight: 600; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .btn-modern:hover { opacity: 0.9; transform: scale(1.02); color: white; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">
            <i class="bi bi-phone-vibrate text-primary"></i> GadgetHub Admin
        </div>
        <a href="{{ url('/admin/dashboard') }}" class="nav-link active">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="{{ route('products.index') }}" class="nav-link">
            <i class="bi bi-box-seam-fill"></i> Produk
        </a>
        <a href="{{ route('admin.orders.index') }}" class="nav-link">
            <i class="bi bi-wallet2"></i> Pesanan Masuk
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-bar-chart-line-fill"></i> Statistik
        </a>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div>
                <h4 class="m-0 fw-bold">Dashboard Penjualan</h4>
                <small class="text-muted">Kelola produk dan penjualan dengan mudah</small>
            </div>
            <div>
                <span class="badge bg-secondary px-3 py-2 rounded-pill">Status: Admin Aktif</span>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card-modern">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted text-uppercase small mb-0 tracking-wider">Total Produk</h6>
                        <i class="bi bi-box text-primary fs-4"></i>
                    </div>
                    <h2 class="fw-bold display-6 m-0">120</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-modern">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted text-uppercase small mb-0 tracking-wider">Penjualan Hari Ini</h6>
                        <i class="bi bi-currency-dollar text-success fs-4"></i>
                    </div>
                    <h2 class="fw-bold display-6 m-0 text-success">Rp 2.500.000</h2>
                </div>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-white">
                    <div class="card-modern">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-muted text-uppercase small mb-0 tracking-wider">Pesanan Masuk</h6>
                            <i class="bi bi-cart-check text-info fs-4"></i>
                        </div>
                        <h2 class="fw-bold display-6 m-0 text-info">18</h2>
                    </div>
                </a>
            </div>
        </div>

        <div class="card-modern">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
                <div>
                    <h4 class="fw-bold mb-2">Selamat Datang 👋</h4>
                    <p class="text-muted mb-0">Kelola semua data produk dan pantau performa toko dengan tampilan modern.</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn-modern">
                    Kelola Produk
                </a>
            </div>
        </div>
    </div>

</body>
</html>