<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GadgetHub - Toko Gadget Terlengkap</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#0f172a] text-white font-sans selection:bg-blue-600 selection:text-white">

    <nav class="bg-[#0f172a]/80 backdrop-blur-xl border-b border-white/10 sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ url('/user/dashboard') }}" class="text-2xl font-black bg-gradient-to-r from-blue-500 to-cyan-400 bg-clip-text text-transparent tracking-wider">
                        GADGET<span class="text-white">HUB</span>
                    </a>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-slate-400 hover:text-blue-400 hover:bg-blue-500/10 rounded-xl transition flex items-center justify-center group" title="Keranjang Belanja">
                        <i class="bi bi-cart3 text-xl transition-transform group-hover:scale-110"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-cyan-400 shadow-[0_0_8px_rgba(34,211,238,0.6)]"></span>
                    </a>

                    <a href="{{ route('user.profile') }}" class="text-sm text-slate-400 hover:text-blue-400 transition flex items-center gap-1.5 group">
                        <i class="bi bi-person-circle text-blue-400 transition-transform group-hover:scale-110"></i> 
                        Halo, <strong class="text-white group-hover:underline">{{ auth()->user()->name }}</strong>
                    </a>
                    
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

    @if(!$products->isEmpty())
        <header x-data="{ 
                    activeSlide: 0, 
                    slidesCount: {{ min($products->count(), 4) }},
                    next() { this.activeSlide = (this.activeSlide + 1) % this.slidesCount },
                    prev() { this.activeSlide = (this.activeSlide - 1 + this.slidesCount) % this.slidesCount }
                }" 
                x-init="setInterval(() => next(), 5000)"
                class="relative overflow-hidden bg-[#0f172a] border-b border-white/5 h-[500px] md:h-[600px] max-w-[1600px] mx-auto sm:px-6 lg:px-8 pt-6">
            
            <div class="w-full h-full relative rounded-3xl overflow-hidden bg-gradient-to-br from-slate-900 via-[#131c31] to-slate-900 border border-white/10">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_50%,rgba(37,99,235,0.15),transparent_50%)] pointer-events-none"></div>

                <div class="w-full h-full relative">
                    @foreach($products->take(4) as $index => $sliderProduct)
                    <div x-show="activeSlide === {{ $index }}" 
                        x-transition:enter="transition ease-out duration-700"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-400"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="w-full h-full absolute inset-0 flex flex-col md:flex-row items-center justify-between p-8 md:p-16 gap-8">
                        
                        <div class="flex-1 max-w-xl space-y-4 md:space-y-6 text-left relative z-10 order-2 md:order-1 pl-6 md:pl-12">
                            <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 uppercase tracking-widest">
                                <i class="bi bi-fire text-amber-500"></i> Hot Deal: {{ $sliderProduct->category }}
                            </span>
                            <h1 class="text-3xl md:text-5xl font-black tracking-tight text-white leading-tight line-clamp-2">
                                {{ $sliderProduct->name }}
                            </h1>
                            <p class="text-sm md:text-base text-slate-400 line-clamp-2 md:line-clamp-3 font-normal leading-relaxed">
                                {{ $sliderProduct->description ?? 'Upgrade produktivitas dan gaya hidup digitalmu dengan penawaran eksklusif gadget spesifikasi premium terbaru di GadgetHub.' }}
                            </p>
                            <div class="text-2xl md:text-3xl font-black text-cyan-400">
                                Rp {{ number_format($sliderProduct->price, 0, ',', '.') }}
                            </div>
                            <div class="pt-2 flex flex-wrap gap-4">
                                <a href="{{ route('user.products.show', $sliderProduct->id) }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-cyan-500 text-white text-sm font-bold px-6 py-3.5 rounded-xl shadow-lg shadow-blue-500/20 hover:opacity-90 transition transform hover:-translate-y-0.5">
                                    Lihat Detail <i class="bi bi-chevron-right text-xs"></i>
                                </a>
                            </div>
                        </div>

                        <div class="flex-1 w-full h-48 md:h-full flex items-center justify-center relative order-1 md:order-2">
                            <div class="absolute w-64 h-64 md:w-96 md:h-96 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
                            @if($sliderProduct->image)
                                <img src="{{ asset('storage/' . $sliderProduct->image) }}" alt="{{ $sliderProduct->name }}" class="max-h-[220px] md:max-h-[380px] w-auto object-contain drop-shadow-[0_20px_50px_rgba(37,99,235,0.3)] transform hover:scale-105 duration-500">
                            @else
                                <div class="text-slate-600 flex flex-col items-center gap-2">
                                    <i class="bi bi-image text-6xl"></i>
                                    <span class="text-xs uppercase tracking-widest">No Image</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-xl bg-slate-900/50 hover:bg-blue-600 border border-white/10 text-white flex items-center justify-center backdrop-blur-md transition group z-20">
                    <i class="bi bi-chevron-left text-lg group-hover:scale-110 transition"></i>
                </button>
                <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-xl bg-slate-900/50 hover:bg-blue-600 border border-white/10 text-white flex items-center justify-center backdrop-blur-md transition group z-20">
                    <i class="bi bi-chevron-right text-lg group-hover:scale-110 transition"></i>
                </button>

                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20">
                    <template x-for="slideIndex in slidesCount" :key="slideIndex">
                        <button @click="activeSlide = slideIndex - 1" 
                                class="h-1.5 rounded-full transition-all duration-300"
                                :class="activeSlide === slideIndex - 1 ? 'w-8 bg-blue-500' : 'w-2 bg-white/20 hover:bg-white/40'"></button>
                    </template>
                </div>
            </div>
        </header>
    @endif

    <main id="katalog" class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 border-b border-white/10 pb-6">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-xl bg-blue-600/10 border border-blue-500/20 text-blue-400">
                    <i class="bi bi-cpu-fill text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold tracking-tight text-white m-0">Katalog Gadget Terbaru</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Menampilkan deretan device spesifikasi terbaik</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2.5">
                <a href="{{ url('/user/dashboard') }}" 
                   class="px-5 py-2.5 rounded-xl text-xs font-bold tracking-wide uppercase transition duration-200 border {{ !request('category') ? 'bg-blue-600 text-white border-blue-500 shadow-lg shadow-blue-600/20' : 'bg-white/5 text-slate-400 border-white/5 hover:bg-white/10 hover:text-white' }}">
                    Semua Gadget
                </a>
                <a href="{{ url('/user/dashboard?category=Handphone') }}" 
                   class="px-5 py-2.5 rounded-xl text-xs font-bold tracking-wide uppercase transition duration-200 border {{ request('category') == 'Handphone' ? 'bg-blue-600 text-white border-blue-500 shadow-lg shadow-blue-600/20' : 'bg-white/5 text-slate-400 border-white/5 hover:bg-white/10 hover:text-white' }}">
                    <i class="bi bi-phone mr-1.5 text-sm"></i> Handphone
                </a>
                <a href="{{ url('/user/dashboard?category=Laptop') }}" 
                   class="px-5 py-2.5 rounded-xl text-xs font-bold tracking-wide uppercase transition duration-200 border {{ request('category') == 'Laptop' ? 'bg-blue-600 text-white border-blue-500 shadow-lg shadow-blue-600/20' : 'bg-white/5 text-slate-400 border-white/5 hover:bg-white/10 hover:text-white' }}">
                    <i class="bi bi-laptop mr-1.5 text-sm"></i> Laptop
                </a>
                <a href="{{ url('/user/dashboard?category=Tablet') }}" 
                   class="px-5 py-2.5 rounded-xl text-xs font-bold tracking-wide uppercase transition duration-200 border {{ request('category') == 'Tablet' ? 'bg-blue-600 text-white border-blue-500 shadow-lg shadow-blue-600/20' : 'bg-white/5 text-slate-400 border-white/5 hover:bg-white/10 hover:text-white' }}">
                    <i class="bi bi-tablet mr-1.5 text-sm"></i> Tablet
                </a>
            </div>
        </div>

        @if($products->isEmpty())
            <div class="text-center py-20 bg-white/5 rounded-3xl border border-white/10 backdrop-blur-sm">
                <i class="bi bi-patch-exclamation text-slate-500 text-5xl block mb-4"></i>
                <p class="text-slate-300 text-xl font-medium">Belum ada gadget di kategori ini saat ini.</p>
                <p class="text-slate-500 text-sm mt-2">Silakan ganti filter atau tambahkan produk baru melalui manajemen admin.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                @foreach($products as $product)
                    <div class="bg-white/4 rounded-2xl overflow-hidden border border-white/10 backdrop-blur-md hover:border-blue-500/40 hover:shadow-2xl hover:shadow-blue-500/5 transition duration-300 flex flex-col justify-between group relative">
                        
                        <a href="{{ route('user.products.show', $product->id) }}" class="block flex-1">
                            <div class="w-full h-44 bg-white/5 flex items-center justify-center overflow-hidden border-b border-white/5 relative">
                                <span class="absolute top-3 left-3 px-2 py-0.5 rounded-md text-[10px] font-bold tracking-wide uppercase bg-slate-900/80 border border-white/10 text-slate-300 backdrop-blur-sm">
                                    {{ $product->category }}
                                </span>

                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-4 group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="text-slate-500 flex flex-col items-center gap-2">
                                        <i class="bi bi-image text-2xl"></i>
                                        <span class="text-[10px] tracking-wider uppercase">No Image</span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4 pb-0">
                                <h3 class="font-bold text-sm text-slate-200 line-clamp-2 mb-2 group-hover:text-blue-400 transition" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </h3>
                                <div class="text-base font-black text-cyan-400">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </a>

                        <div class="p-4 pt-4 mt-3">
                            <div class="grid grid-cols-1 gap-2">
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 border border-white/10 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs hover:bg-white/10 hover:text-white transition">
                                        <i class="bi bi-cart-plus"></i> + Keranjang
                                    </button>
                                </form>
                                <button type="button" class="w-full bg-blue-600 text-white font-bold py-2 px-3 rounded-xl text-xs hover:bg-blue-700 transition shadow-md shadow-blue-600/10">
                                    Checkout
                                </button>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <footer class="bg-[#0b111e] text-slate-500 py-10 border-t border-white/5 mt-32">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs tracking-wider">
            &copy; {{ date('Y') }} GADGETHUB INDONESIA. All rights reserved.
        </div>
    </footer>

</body>
</html>