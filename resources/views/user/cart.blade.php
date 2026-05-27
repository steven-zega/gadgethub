<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - GadgetHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#0f172a] text-white font-sans selection:bg-blue-600 selection:text-white"
      x-data="{
        // Inisialisasi total harga awal dari server
        totalPrice: {{ $cartItems->sum(fn($item) => $item->quantity * $item->product->price) }},
        formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }
      }">

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
                        <i class="bi bi-person-circle text-blue-400 mr-1.5"></i> <strong class="text-white">{{ auth()->user()->name }}</strong>
                    </span>
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
            <h1 class="text-3xl font-black tracking-tight mt-4 bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent">Keranjang Anda</h1>
        </div>

        @if($cartItems->isEmpty())
            <div class="text-center py-20 bg-white/5 rounded-3xl border border-white/10 backdrop-blur-sm relative z-10">
                <i class="bi bi-cart-x text-slate-600 text-6xl block mb-4"></i>
                <p class="text-slate-300 text-xl font-medium">Keranjang belanja Anda masih kosong.</p>
                <p class="text-slate-500 text-sm mt-2 mb-6">Yuk, cari gadget impianmu sekarang juga!</p>
                <a href="{{ route('user.dashboard') }}" class="bg-blue-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-600/20 text-sm">
                    Jelajahi Katalog
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative z-10">
                
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                        <div x-data="{ 
                                    qty: {{ $item->quantity }}, 
                                    price: {{ $item->product->price }},
                                    subtotal: {{ $item->quantity * $item->product->price }},
                                    isDeleted: false,
                                    async updateQty(action) {
                                        let response = await fetch('{{ route('cart.update', $item->id) }}', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({ action: action })
                                        });
                                        let data = await response.json();
                                        
                                        if (response.ok) {
                                            if (data.deleted) {
                                                $data.totalPrice -= this.subtotal;
                                                this.isDeleted = true;
                                            } else {
                                                let oldSubtotal = this.subtotal;
                                                this.qty = data.quantity;
                                                this.subtotal = data.subtotal;
                                                $data.totalPrice += (this.subtotal - oldSubtotal);
                                            }
                                        } else {
                                            alert(data.error || 'Terjadi kesalahan');
                                        }
                                    }
                                 }" 
                             x-show="!isDeleted"
                             x-transition:leave="transition ease-in duration-300 transform scale-90 opacity-0"
                             class="bg-white/4 border border-white/10 rounded-2xl p-5 flex items-center gap-5 backdrop-blur-md">
                            
                            <div class="w-20 h-20 bg-white/5 rounded-xl flex items-center justify-center p-2 border border-white/5 flex-shrink-0">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-contain">
                                @else
                                    <i class="bi bi-image text-slate-600 text-xl"></i>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <span class="text-[10px] font-bold tracking-wider text-blue-400 uppercase bg-blue-500/10 px-2 py-0.5 rounded-md border border-blue-500/10">
                                    {{ $item->product->category }}
                                </span>
                                <h3 class="font-bold text-sm text-slate-200 truncate mt-1.5" title="{{ $item->product->name }}">
                                    {{ $item->product->name }}
                                </h3>
                                <div class="text-sm font-black text-cyan-400 mt-1" x-text="formatRupiah(subtotal)"></div>
                            </div>

                            <div class="flex items-center bg-slate-950/60 rounded-xl border border-white/10 p-1 flex-shrink-0 w-28 justify-between">
                                <button @click="updateQty('decrease')" type="button" class="w-8 h-8 rounded-lg text-white hover:bg-white/10 hover:text-red-400 transition flex items-center justify-center text-lg font-black select-none">
                                    &minus;
                                </button>
                                
                                <span class="flex-1 text-center text-xs font-black text-white select-none" x-text="qty"></span>
                                
                                <button @click="updateQty('increase')" type="button" class="w-8 h-8 rounded-lg text-white hover:bg-blue-600 transition flex items-center justify-center text-lg font-black select-none">
                                    +
                                </button>
                            </div>

                        </div>
                    @endforeach
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-b from-slate-900 to-[#131c31] border border-white/10 rounded-2xl p-6 sticky top-24 shadow-xl">
                        <h2 class="text-lg font-bold border-b border-white/10 pb-4 flex items-center gap-2">
                            <i class="bi bi-receipt text-blue-400"></i> Ringkasan Belanja
                        </h2>
                        
                        <div class="space-y-3 my-6 text-sm">
                            <div class="flex justify-between text-slate-400">
                                <span>Total Item</span>
                                <span class="text-white font-medium">{{ $cartItems->count() }} Jenis Produk</span>
                            </div>
                            <hr class="border-white/5 my-2">
                            <div class="flex flex-col gap-1">
                                <span class="text-slate-400">Total Harga:</span>
                                <span class="text-2xl font-black text-cyan-400 tracking-tight" x-text="formatRupiah(totalPrice)"></span>
                            </div>
                        </div>

                        <a href="{{ route('checkout.index', ['type' => 'cart']) }}" class="w-full bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/20 transition transform hover:-translate-y-0.5 text-center text-sm flex items-center justify-center gap-2 group">
                            Checkout <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>

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