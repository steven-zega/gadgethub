<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - GadgetHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-[#0f172a] text-white font-sans selection:bg-blue-600 selection:text-white">

    <nav class="bg-[#0f172a]/80 backdrop-blur-xl border-b border-white/10 sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('user.dashboard') }}" class="text-2xl font-black bg-gradient-to-r from-blue-500 to-cyan-400 bg-clip-text text-transparent tracking-wider">
                        GADGET<span class="text-white">HUB</span>
                    </a>
                </div>
                <div class="flex items-center space-x-6">
                    <span class="text-sm text-slate-400">
                        <i class="bi bi-person-circle text-blue-400 mr-1.5"></i> Halo, <strong class="text-white">{{ auth()->user()->name }}</strong>
                    </span>
                    <form action="{{ url('/logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-red-400 hover:text-red-300 transition flex items-center gap-1">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-12 relative">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(37,99,235,0.05),transparent_60%)] pointer-events-none"></div>

        <div class="mb-8 relative z-10">
            <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-blue-400 transition group">
                <i class="bi bi-arrow-left transition-transform group-hover:-translate-x-1"></i> Kembali
            </a>
        </div>

        <div class="bg-white/4 rounded-3xl p-6 md:p-10 border border-white/10 backdrop-blur-md shadow-2xl grid grid-cols-1 md:grid-cols-2 gap-12 relative z-10">
            
            <div class="w-full h-[450px] bg-white/5 rounded-2xl flex items-center justify-center overflow-hidden border border-white/5 p-6 group">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain group-hover:scale-102 transition duration-300">
                @else
                    <div class="text-slate-500 flex flex-col items-center gap-3">
                        <i class="bi bi-image text-4xl"></i>
                        <span class="text-xs tracking-widest uppercase">No Image Available</span>
                    </div>
                @endif
            </div>

            <div class="flex flex-col justify-between">
                <div>
                    <h1 class="text-2xl md:text-4xl font-black text-white tracking-tight mb-3 leading-tight">
                        {{ $product->name }}
                    </h1>
                    
                    <div class="text-3xl font-black text-cyan-400 mb-6 flex items-center gap-2">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>

                    <div class="bg-white/5 rounded-2xl p-4 mb-8 space-y-3 border border-white/5 text-sm">
                        <div class="flex justify-between items-center pb-2 border-b border-white/5">
                            <span class="text-slate-400 flex items-center gap-1.5"><i class="bi bi-shield-check text-blue-400"></i> Seller:</span>
                            <span class="font-bold text-white">{{ $product->user->name ?? 'Admin GadgetHub' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 flex items-center gap-1.5"><i class="bi bi-boxes text-blue-400"></i> Stock:</span>
                            @if($product->stock <= 0)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/20">Stok Habis</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-success bg-opacity-25 text-success border border-success border-opacity-50">{{ $product->stock }} Unit</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-3 flex items-center gap-1.5">
                            <i class="bi bi-file-text"></i> Deskripsi Produk
                        </h2>
                        <p class="text-slate-300 text-sm leading-relaxed whitespace-pre-line bg-white/[0.02] p-4 rounded-xl border border-white/5">
                            {{ $product->description ?? 'Tidak ada deskripsi atau spesifikasi teknis lengkap untuk produk ini.' }}
                        </p>
                    </div>
                </div>

                <div class="pt-6 border-t border-white/10 mt-auto">
                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" class="w-full inline-flex items-center justify-center gap-2 border border-white/10 text-slate-200 font-bold py-4 px-6 rounded-2xl hover:bg-white/10 hover:text-white transition text-sm">
                            <i class="bi bi-cart-plus text-base"></i> + Masuk Keranjang
                        </button>
                        
                        <button type="button" class="w-full bg-blue-600 text-white font-bold py-4 px-6 rounded-2xl hover:bg-blue-700 transition shadow-lg shadow-blue-600/20 text-center text-sm">
                            Beli Sekarang
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="bg-[#0b111e] text-slate-500 py-10 border-t border-white/5 mt-32">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs tracking-wider">
            &copy; {{ date('Y') }} GADGETHUB INDONESIA. All rights reserved.
        </div>
    </footer>

</body>
</html>