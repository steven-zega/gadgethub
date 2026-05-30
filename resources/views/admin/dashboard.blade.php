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
        body { background: #0f172a; font-family: 'Segoe UI', sans-serif; color: #ffffff; overflow-x: hidden; }
        
        .sidebar { position: fixed; left: 0; top: 0; width: 270px; height: 100vh; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(18px); border-right: 1px solid rgba(255,255,255,0.08); padding: 30px 22px; z-index: 100; }
        .brand { display: flex; align-items: center; gap: 12px; margin-bottom: 45px; font-size: 1.3rem; font-weight: 700; background: linear-gradient(135deg, #3b82f6, #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        .nav-link { display: flex; align-items: center; gap: 14px; color: #cbd5e1; padding: 14px 18px; border-radius: 18px; margin-bottom: 12px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; }
        .nav-link:hover, .nav-link.active { background: linear-gradient(135deg, #2563eb, #06b6d4); color: white; transform: translateX(5px); box-shadow: 0 10px 30px rgba(37,99,235,0.25); }
        
        .main-content { margin-left: 270px; padding: 30px; position: relative; }
        .bg-glow { position: absolute; top: -10%; left: 30%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(37,99,235,0.05), transparent 70%); pointer-events: none; }
                
        .card-modern { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(16px); border-radius: 28px; padding: 28px; box-shadow: 0 15px 40px rgba(0,0,0,0.25); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); margin-bottom: 28px; }
        .card-modern:hover { transform: translateY(-5px); border-color: rgba(6, 182, 212, 0.4); background: rgba(255, 255, 255, 0.05); }
        
        .btn-modern { background: linear-gradient(135deg, #2563eb, #06b6d4); border: none; color: white; padding: 12px 24px; border-radius: 16px; font-weight: 600; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .btn-modern:hover { opacity: 0.95; transform: scale(1.02); color: white; box-shadow: 0 8px 24px rgba(6, 182, 212, 0.3); }
        
        .badge-admin { background: rgba(6, 182, 212, 0.15); border: 1px solid rgba(6, 182, 212, 0.3); color: #22d3ee; font-weight: 600; }
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
        <div class="card-modern">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
                <div>
                    <h4 class="fw-bold text-white mb-2">Selamat datang kembali, {{ auth()->user()->name }}! 👋</h4>
                </div>
                <a href="{{ route('products.index') }}" class="btn-modern">
                    <i class="bi bi-plus-circle"></i> Kelola Produk
                </a>
            </div>
        </div>
             
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <a href="{{ route('products.index') }}" class="text-decoration-none">
                    <div class="card-modern">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-slate-400 text-uppercase small mb-0 tracking-wider font-semibold">Total Produk</h6>
                            <i class="bi bi-box text-primary fs-4"></i>
                        </div>
                        <h2 class="fw-bold display-6 m-0 text-white">{{ $totalProducts }}</h2>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <div class="card-modern">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-slate-400 text-uppercase small mb-0 tracking-wider font-semibold">Pendapatan Total</h6>
                        <i class="bi bi-currency-dollar text-emerald-400 fs-4"></i>
                    </div>
                    <h2 class="fw-bold display-6 m-0 text-emerald-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                </div>
            </div>

            <div class="col-md-4">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
                    <div class="card-modern">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-slate-400 text-uppercase small mb-0 tracking-wider font-semibold">Pesanan Baru</h6>
                            <i class="bi bi-cart-check text-cyan-400 fs-4"></i>
                        </div>
                        <h2 class="fw-bold display-6 m-0 text-cyan-400">
                            {{ $incomingOrders }}
                            @if($incomingOrders > 0)
                                <span class="fs-6 fw-normal text-amber-400 ms-1 animate-pulse">(Perlu Tindakan)</span>
                            @endif
                        </h2>
                    </div>
                </a>
            </div>
        </div>

    </div>

</body>
</html>