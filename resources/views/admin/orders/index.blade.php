<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Masuk - GadgetHub Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #0f172a; font-family: 'Segoe UI', sans-serif; color: white; overflow-x: hidden; }
        
        /* Sidebar Styling */
        .sidebar { position: fixed; left: 0; top: 0; width: 270px; height: 100vh; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(18px); border-right: 1px solid rgba(255,255,255,0.08); padding: 30px 22px; z-index: 100; }
        .brand { display: flex; align-items: center; gap: 12px; margin-bottom: 45px; font-size: 1.3rem; font-weight: 700; background: linear-gradient(135deg, #3b82f6, #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .nav-link { display: flex; align-items: center; gap: 14px; color: #cbd5e1; padding: 14px 18px; border-radius: 18px; margin-bottom: 12px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; }
        .nav-link:hover, .nav-link.active { background: linear-gradient(135deg, #2563eb, #06b6d4); color: white; transform: translateX(5px); box-shadow: 0 10px 30px rgba(37,99,235,0.25); }
        
        /* Main Area */
        .main-content { margin-left: 270px; padding: 30px; }
        .topbar { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(16px); border-radius: 28px; padding: 24px 28px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
        
        /* Glassmorphism Card Table */
        .card-modern { background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(16px); border-radius: 28px; padding: 28px; box-shadow: 0 15px 40px rgba(0,0,0,0.25); }
        .table-dark-custom { --bs-table-bg: transparent; color: white; }
        .table-dark-custom th { color: #94a3b8; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid rgba(255,255,255,0.1); padding: 16px; }
        .table-dark-custom td { padding: 18px 16px; border-bottom: 1px solid rgba(255,255,255,0.05); vertical-align: middle; font-size: 0.9rem; }
        
        /* Image Proof Thumb */
        .proof-img { width: 55px; height: 55px; object-fit: cover; border-radius: 12px; cursor: pointer; transition: all 0.2s ease; border: 2px solid rgba(255,255,255,0.1); }
        .proof-img:hover { transform: scale(1.08); border-color: #06b6d4; box-shadow: 0 0 15px rgba(6,182,212,0.4); }
        
        .status-badge { padding: 6px 14px; border-radius: 12px; font-weight: 600; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 6px; }
        .status-pending { background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.25); }
        .status-success { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.25); }
        .status-danger { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.25); }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">
            <i class="bi bi-phone-vibrate text-primary"></i> GadgetHub Admin
        </div>
        <a href="{{ url('/admin/dashboard') }}" class="nav-link">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="{{ route('products.index') }}" class="nav-link">
            <i class="bi bi-box-seam-fill"></i> Produk
        </a>
        <a href="{{ route('admin.orders.index') }}" class="nav-link active">
            <i class="bi bi-wallet2"></i> Pesanan Masuk
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-bar-chart-line-fill"></i> Statistik
        </a>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="topbar">
            <div>
                <h4 class="m-0 fw-bold">Daftar Pesanan Masuk</h4>
            </div>
            <div class="text-end">
                <span class="badge bg-secondary px-3 py-2 rounded-pill"><i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}</span>
            </div>
        </div>

        <div class="card-modern">
            <div class="table-responsive">
                <table class="table table-dark-custom m-0">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Produk</th>
                            <th class="text-center">Kuantitas</th>
                            <th>Nama Pembeli</th>
                            <th>Alamat</th>
                            <th class="text-center">Bukti Bayar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $item)
                            <tr>
                                <td>
                                    <span class="d-block fw-bold text-info font-monospace">{{ $item->order->invoice_number ?? 'INV-NOT-FOUND' }}</span>
                                    <small class="text-muted d-block mb-1" style="font-size: 0.75rem;">
                                        {{ $item->created_at->translatedFormat('d M Y, H:i') }}
                                    </small>
                                    
                                    @if(($item->status ?? 'pending') === 'success')
                                        <span class="status-badge status-success"><i class="bi bi-check-circle-fill"></i> Diterima</span>
                                    @elseif(($item->status ?? 'pending') === 'rejected')
                                        <span class="status-badge status-danger"><i class="bi bi-x-circle-fill"></i> Ditolak</span>
                                    @else
                                        <span class="status-badge status-pending"><i class="bi bi-clock-history"></i> Menunggu</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" class="rounded-3" width="50" height="50" style="object-fit: contain; background: rgba(255,255,255,0.05)">
                                        @else
                                            <div class="bg-secondary bg-opacity-20 rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="d-block fw-bold text-white-50 mb-0">{{ $item->product->name ?? 'Produk Terhapus' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25 px-2.5 py-1.5 rounded-3">
                                        {{ $item->quantity }} Pcs
                                    </span>
                                </td>

                                <td class="fw-semibold text-white">
                                    {{ $item->order?->buyer_name ?? 'User GadgetHub' }}
                                </td>

                                <td>
                                    <span class="d-inline-block text-truncate text-white" style="max-width: 200px;" title="{{ $item->order?->address ?? '-' }}">
                                        {{ $item->order?->address ?? 'Alamat tidak terisi' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @if($item->order && $item->order->payment_proof)
                                        <img src="{{ asset('storage/' . $item->order->payment_proof) }}" 
                                             class="proof-img" 
                                             alt="Struk Transfer"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#imageModal" 
                                             onclick="showImageModal(this.src)">
                                    @else
                                        <span class="text-muted fst-italic small"><i class="bi bi-exclamation-circle"></i> Tanpa Bukti</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if(($item->status ?? 'pending') === 'pending')
                                        <div class="d-flex justify-content-center gap-2">
                                            <form action="{{ route('admin.orders.updateStatus', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="success">
                                                <button type="submit" class="btn btn-sm btn-success px-2.5 py-1.5 rounded-3 fw-semibold" title="Verifikasi Pembayaran">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('admin.orders.updateStatus', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm btn-danger px-2.5 py-1.5 rounded-3 fw-semibold" title="Tolak Pembayaran">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted small fst-italic">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted fst-italic">
                                    <i class="bi bi-inbox text-secondary display-5 d-block mb-2"></i>
                                    Belum ada pesanan masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-secondary border-opacity-25" style="background: #1e293b; border-radius: 24px;">
                <div class="modal-header border-bottom border-secondary border-opacity-25">
                    <h6 class="modal-title fw-bold text-white"><i class="bi bi-images text-cyan-400 me-2"></i>Detail Bukti Pembayaran</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <img id="modal-img-target" src="" class="img-fluid rounded-3 shadow-lg" alt="Bukti Transfer Zoom" style="max-height: 500px; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showImageModal(srcImage) {
            document.getElementById('modal-img-target').src = srcImage;
        }
    </script>
</body>
</html>