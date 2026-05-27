<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout - GadgetHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#0f172a] text-white font-sans selection:bg-blue-600 selection:text-white"
      x-data="{
        address: '',
        defaultAddress: '{{ auth()->user()->address ?? '' }}',
        couponCode: '',
        discount: 0,
        basePrice: {{ $totalPrice }},
        applyCoupon() {
            if(this.couponCode.toUpperCase() === 'GADGETNEON') {
                this.discount = this.basePrice * 0.1;
                alert('Kode promo berhasil dipasang! Diskon 10% diterapkan.');
            } else if(this.couponCode === '') {
                alert('Masukkan kode voucher terlebih dahulu.');
            } else {
                alert('Yah, kode voucher tidak valid atau sudah kedaluwarsa.');
                this.discount = 0;
            }
        },
        formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }
      }">

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

    <main class="max-w-6xl mx-auto px-4 py-12 relative">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(6,182,212,0.03),transparent_60%)] pointer-events-none"></div>

        <div class="mb-8 relative z-10">
            @if(isset($type) && $type === 'cart')
                <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-blue-400 transition group">
                    <i class="bi bi-arrow-left transition-transform group-hover:-translate-x-1"></i> Kembali
                </a>
            @elseif(isset($type) && $type === 'instant')
                <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-blue-400 transition group">
                    <i class="bi bi-arrow-left transition-transform group-hover:-translate-x-1"></i> Kembali
                </a>
            @else
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-blue-400 transition group">
                    <i class="bi bi-arrow-left transition-transform group-hover:-translate-x-1"></i> Kembali
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative z-10">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white/4 border border-white/10 rounded-3xl p-6 backdrop-blur-md">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold flex items-center gap-2">
                            <i class="bi bi-geo-alt text-cyan-400"></i> Alamat Pengiriman
                        </h2>
                        @if(!empty(auth()->user()->address))
                            <button type="button" @click="address = defaultAddress" class="text-xs font-bold text-blue-400 hover:text-blue-300 transition flex items-center gap-1 bg-blue-500/10 px-3 py-1.5 rounded-xl border border-blue-500/20">
                                <i class="bi bi-house-fill"></i> Gunakan Alamat Default
                            </button>
                        @endif
                    </div>

                    <textarea name="address" x-model="address" rows="4" placeholder="Ketik alamat pengiriman disini..." class="w-full bg-slate-950/50 border border-white/10 rounded-2xl p-4 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-cyan-400 transition leading-relaxed" required></textarea>
                    
                    @if(empty(auth()->user()->address))
                        <p class="text-[11px] text-slate-500 mt-2">
                            <i class="bi bi-info-circle"></i> Kamu belum mengatur alamat default di <a href="{{ route('user.profile') }}" class="text-blue-400 underline">Profil Akun</a>.
                        </p>
                    @endif
                </div>

                <div class="bg-white/4 border border-white/10 rounded-3xl p-6 backdrop-blur-md space-y-4">
                    <h2 class="text-lg font-bold flex items-center gap-2 mb-2">
                        <i class="bi bi-box-seam text-blue-400"></i> Item produk
                    </h2>

                    @foreach($checkoutItems as $item)
                        <div class="flex items-center gap-4 py-3 border-b border-white/5 last:border-0">
                            <div class="w-16 h-16 bg-white/5 rounded-xl flex items-center justify-center p-2 border border-white/5 flex-shrink-0">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-contain">
                                @else
                                    <i class="bi bi-image text-slate-600 text-lg"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-sm text-slate-200 truncate">{{ $item->product->name }}</h4>
                                <p class="text-xs text-slate-400 mt-1">{{ $item->quantity }} x {{ 'Rp ' . number_format($item->product->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-sm font-black text-white flex-shrink-0">
                                {{ 'Rp ' . number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-gradient-to-b from-slate-900 to-[#131c31] border border-white/10 rounded-3xl p-6 sticky top-24 shadow-xl space-y-6">
                    <h2 class="text-base font-bold border-b border-white/10 pb-4 flex items-center gap-2">
                        <i class="bi bi-shield-check text-emerald-400"></i> Ringkasan Pembayaran
                    </h2>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Punya Kode Diskon?</label>
                        <div class="flex gap-2">
                            <input type="text" x-model="couponCode" placeholder="Contoh: GADGETNEON" class="flex-1 bg-slate-950/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white uppercase placeholder-slate-600 focus:outline-none focus:border-blue-500 transition">
                            <button type="button" @click="applyCoupon()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-4 rounded-xl transition">
                                Pakai
                            </button>
                        </div>
                        <p class="text-[10px] text-slate-500 font-mono">*Gunakan kode <span class="text-cyan-400">GADGETNEON</span> untuk mendapatkan diskon 10%</p>
                    </div>

                    <hr class="border-white/5">

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between text-slate-400">
                            <span>Subtotal Produk</span>
                            <span class="text-white font-medium" x-text="formatRupiah(basePrice)"></span>
                        </div>
                        <div class="flex justify-between text-slate-400" x-show="discount > 0" x-cloak>
                            <span>Potongan Diskon</span>
                            <span class="text-emerald-400 font-medium" x-text="'- ' + formatRupiah(discount)"></span>
                        </div>
                        <div class="flex justify-between text-slate-400">
                            <span>Biaya Pengiriman</span>
                            <span class="text-emerald-400 font-medium uppercase tracking-widest text-[10px] bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/10">Gratis Ongkir</span>
                        </div>
                        
                        <hr class="border-white/5 my-2">
                        
                        <div class="flex flex-col gap-1">
                            <span class="text-xs text-slate-400">Total:</span>
                            <span class="text-2xl font-black text-cyan-400 tracking-tight" x-text="formatRupiah(basePrice - discount)"></span>
                        </div>
                    </div>

                    <form action="{{ route('checkout.process') }}" method="POST" @submit="if(address === '') { alert('Alamat pengiriman wajib diisi!'); $event.preventDefault(); }">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        <input type="hidden" name="address" :value="address">

                        <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-black py-4 rounded-xl shadow-lg shadow-emerald-500/25 transition transform hover:-translate-y-0.5 text-center text-sm flex items-center justify-center gap-2 group">
                        Bayar Sekarang
                        </button>
                    </form>
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