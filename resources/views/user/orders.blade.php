<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - GadgetHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-[#0f172a] text-white font-sans selection:bg-blue-600 selection:text-white">

    <nav class="bg-[#0f172a]/80 backdrop-blur-xl border-b border-white/10 sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-2xl font-black bg-gradient-to-r from-blue-500 to-cyan-400 bg-clip-text text-transparent tracking-wider">
                        GADGET<span class="text-white">HUB</span>
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-12 relative">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(6,182,212,0.02),transparent_60%)] pointer-events-none"></div>

        <div class="flex justify-between items-center mb-8 relative z-10">
            <div>
                <h1 class="text-2xl font-black tracking-tight">Riwayat Pemesanan</h1>
            </div>
            <a href="{{ route('user.dashboard') }}" class="text-xs font-bold bg-white/5 hover:bg-white/10 border border-white/10 px-4 py-2 rounded-xl transition">
                <i class="bi bi-house-door"></i> Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium rounded-2xl flex items-center gap-2">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6 relative z-10">
            @forelse($orders as $order)
                <div class="bg-slate-900/60 border border-white/10 rounded-3xl overflow-hidden backdrop-blur-md">
                    
                    <div class="p-4 sm:p-6 bg-white/5 border-b border-white/5 flex flex-wrap justify-between items-center gap-4">
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs">
                            <div>
                                <span class="text-slate-500 font-medium">No. Invoice:</span>
                                <span class="font-mono text-cyan-400 font-bold ml-1">{{ $order->invoice_number }}</span>
                            </div>
                            <div class="text-slate-600 hidden sm:block">|</div>
                            <div>
                                <span class="text-slate-500 font-medium">Tanggal Transaksi:</span>
                                <span class="text-slate-300 ml-1">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 sm:p-6 divide-y divide-white/5">
                        @foreach($order->items as $item)
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-4 first:pt-0 last:pb-0">
                                <div class="flex items-center gap-4 flex-1 min-w-0">
                                    <div class="w-14 h-14 bg-white/5 rounded-xl border border-white/5 p-1 flex-shrink-0 flex items-center justify-center">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-contain">
                                        @else
                                            <i class="bi bi-image text-slate-600 text-lg"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-sm text-slate-200 truncate">{{ $item->product->name ?? 'Produk Terhapus' }}</h4>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $item->quantity }} barang x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between md:justify-end gap-6 border-t md:border-t-0 border-white/5 pt-2 md:pt-0">
                                    <div class="text-xs">
                                        @if(($item->status ?? 'pending') === 'success')
                                            <span class="text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-3 py-1 rounded-full flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> Diterima
                                            </span>
                                        @elseif(($item->status ?? 'pending') === 'rejected')
                                            <span class="text-[10px] font-bold uppercase tracking-wider bg-rose-500/10 text-rose-400 border border-rose-500/20 px-3 py-1 rounded-full flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 bg-rose-400 rounded-full"></span> Ditolak
                                            </span>
                                        @else
                                            <span class="text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-400 border border-amber-500/20 px-3 py-1 rounded-full flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span> Menunggu
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="text-sm font-bold text-slate-300 text-right min-w-[100px]">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="p-4 sm:p-6 bg-slate-950/40 border-t border-white/5 flex flex-col sm:flex-row justify-between gap-4 text-xs">
                        <div class="max-w-md">
                            <span class="text-slate-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Alamat Pengiriman:</span>
                            <p class="text-slate-400 leading-relaxed">{{ $order->address }}</p>
                            <p class="text-[10px] text-slate-500 mt-1">Metode: <span class="uppercase font-mono text-slate-400 font-bold">{{ $order->payment_method }}</span></p>
                        </div>
                        <div class="sm:text-right flex flex-row sm:flex-col justify-between sm:justify-end items-center sm:items-end gap-1 border-t sm:border-t-0 border-white/5 pt-3 sm:pt-0">
                            <span class="text-slate-500 font-medium">Total Tagihan Toko:</span>
                            <span class="text-lg font-black text-cyan-400">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($order->items->where('status', 'rejected')->count() > 0)
                        <div class="p-4 sm:px-6 bg-slate-950/60 border-t border-white/5 flex justify-end items-center gap-3">
                            <form action="{{ route('user.orders.hide', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat pesanan ini secara permanen dari database?')">
                                @csrf
                                <button type="submit" class="text-xs font-bold bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-400 px-4 py-2.5 rounded-xl transition flex items-center gap-1.5">
                                    <i class="bi bi-trash3"></i> Hapus Riwayat
                                </button>
                            </form>

                            <form action="{{ route('user.orders.reorder', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl transition flex items-center gap-1.5 shadow-lg shadow-blue-600/10">
                                    <i class="bi bi-arrow-clockwise"></i> Pesan Ulang
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            @empty
                <div class="text-center py-20 bg-slate-900/40 border border-white/10 rounded-3xl backdrop-blur-md">
                    <i class="bi bi-receipt-cutoff text-5xl text-slate-700 block mb-4"></i>
                    <h3 class="text-base font-bold text-slate-300">Belum Ada Transaksi</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto leading-relaxed">Kamu belum pernah melakukan checkout produk apa pun di toko online kami.</p>
                    <a href="{{ route('user.dashboard') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl transition mt-6">
                        Mulai Belanja Sekarang
                    </a>
                </div>
            @endforelse
        </div>
    </main>

    <footer class="bg-[#0b111e] text-slate-500 py-10 border-t border-white/5 mt-32">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs tracking-wider">
            &copy; {{ date('Y') }} GADGETHUB INDONESIA. All rights reserved.
        </div>
    </footer>

</body>
</html>