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
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-slate-400 hover:text-blue-400 hover:bg-blue-500/10 rounded-xl transition flex items-center justify-center group" title="Keranjang Belanja">
                        <i class="bi bi-cart3 text-xl transition-transform group-hover:scale-110"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-cyan-400 shadow-[0_0_8px_rgba(34,211,238,0.6)]"></span>
                    </a>

                    <span class="text-sm text-slate-400 hidden sm:inline-block">
                        <i class="bi bi-person-circle text-blue-400 mr-1.5"></i> Halo, <strong class="text-white">{{ auth()->user()->name }}</strong>
                    </span>
                    <form action="{{ url('/logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-red-400 hover:text-red-300 transition flex items-center gap-1">
                            <i class="bi bi-box-arrow-right"></i> <span class="hidden sm:inline">Logout</span>
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
                        
                        <div class="flex justify-between items-center pb-2 border-b border-white/5">
                            <span class="text-slate-400 flex items-center gap-1.5"><i class="bi bi-tag text-blue-400"></i> Kategori:</span>
                            <span class="px-2 py-0.5 rounded-md text-xs font-bold bg-white/10 text-cyan-400 border border-white/5">{{ $product->category ?? 'Gadget' }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 flex items-center gap-1.5"><i class="bi bi-boxes text-blue-400"></i> Stock:</span>
                            @if($product->stock <= 0)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/20">Stok Habis</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">{{ $product->stock }} Unit</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-6">
                        <h2 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-3 flex items-center gap-1.5">
                            <i class="bi bi-file-text"></i> Deskripsi Produk
                        </h2>
                        <p class="text-slate-300 text-sm leading-relaxed whitespace-pre-line bg-white/[0.02] p-4 rounded-xl border border-white/5">
                            {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-3 flex items-center gap-1.5">
                            <i class="bi bi-cpu-fill text-cyan-400"></i> Spesifikasi {{ $product->category ?? '' }}
                        </h2>
                        
                        @if(!empty($product->specifications))
                            <div class="bg-white/[0.02] border border-white/5 rounded-2xl overflow-hidden text-sm shadow-inner">
                                @foreach($product->specifications as $key => $value)
                                    @if(!empty($value))
                                        <div class="grid grid-cols-3 border-b border-white/5 last:border-b-0 hover:bg-white/[0.03] transition duration-150">
                                            <div class="col-span-1 p-3.5 font-bold text-slate-400 bg-slate-950/20 flex items-center gap-2">
                                                @switch(strtolower($key))
                                                    @case('ram') <i class="bi bi-memory text-blue-400"></i> @break
                                                    @case('storage') <i class="bi bi-hdd-fill text-blue-400"></i> @break
                                                    @case('ram_storage') <i class="bi bi-device-ssd text-blue-400"></i> @break
                                                    @case('battery') <i class="bi bi-battery-charging text-emerald-400"></i> @break
                                                    @case('processor') <i class="bi bi-cpu text-cyan-400"></i> @break
                                                    @case('chipset') <i class="bi bi-cpu-fill text-cyan-400"></i> @break
                                                    @case('vga') <i class="bi bi-pci-card text-purple-400"></i> @break
                                                    @case('screen') <i class="bi bi-display text-amber-400"></i> @break
                                                    @case('camera') <i class="bi bi-camera-fill text-rose-400"></i> @break
                                                    @case('os') <i class="bi bi-windows text-sky-400"></i> @break
                                                    @case('stylus') <i class="bi bi-pencil-fill text-indigo-400"></i> @break
                                                    @default <i class="bi bi-arrow-right-short text-slate-500"></i>
                                                @endswitch
                                                <span class="capitalize">{{ str_replace('_', ' & ', $key) }}</span>
                                            </div>
                                            <div class="col-span-2 p-3.5 text-slate-200 font-semibold bg-white/[0.01] flex items-center">
                                                {{ $value }}
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="text-xs text-slate-500 italic bg-white/[0.01] p-4 rounded-2xl border border-dashed border-white/10 text-center">
                                <i class="bi bi-exclamation-circle d-block mb-1 text-base text-slate-600"></i>
                                Detail spesifikasi belum dikonfigurasi untuk produk ini.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="pt-6 border-t border-white/10 mt-auto">
                    <div class="flex items-center gap-4 w-full">
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" @if($product->stock <= 0) disabled @endif class="w-full h-14 inline-flex items-center justify-center gap-2 bg-slate-900 border border-white/10 text-slate-200 font-bold rounded-2xl hover:bg-slate-800 hover:text-white transition text-sm disabled:opacity-40 disabled:cursor-not-allowed">
                            + Keranjang 
                            </button>
                        </form>
                        
                        <form action="{{ route('checkout.index') }}" method="GET" class="flex-1">
                            <input type="hidden" name="type" value="instant">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1"> 
                            <button type="submit" @if($product->stock <= 0) disabled @endif class="w-full h-14 inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-black rounded-2xl text-sm shadow-lg shadow-blue-500/20 hover:opacity-90 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                Checkout
                            </button>
                        </form>
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