<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Upload - GadgetHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#0f172a] text-white font-sans selection:bg-blue-600 selection:text-white"
      x-data="{
        paymentMethod: 'qris', 
        imagePreview: null,
        fileChosen(event) {
            const file = event.target.files[0];
            if (file) {
                this.imagePreview = URL.createObjectURL(file);
            }
        }
      }">

    <nav class="bg-[#0f172a]/80 backdrop-blur-xl border-b border-white/10 sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-2xl font-black bg-gradient-to-r from-blue-500 to-cyan-400 bg-clip-text text-transparent tracking-wider">
                        GADGET<span class="text-white">HUB</span> <span class="text-xs font-mono text-slate-500 border border-white/10 px-2 py-0.5 rounded ml-2">SECURE PAYMENT</span>
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 py-12 relative">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(6,182,212,0.03),transparent_60%)] pointer-events-none"></div>

        <div class="mb-8 relative z-10">
            @if(isset($type) && $type === 'instant' && isset($productId))
                <a href="{{ route('checkout.index', ['type' => 'instant', 'product_id' => $productId]) }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-blue-400 transition group">
                    <i class="bi bi-arrow-left transition-transform group-hover:-translate-x-1"></i> Kembali ke Checkout
                </a>
            @else
                <a href="{{ route('checkout.index', ['type' => 'cart']) }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-blue-400 transition group">
                    <i class="bi bi-arrow-left transition-transform group-hover:-translate-x-1"></i> Kembali ke Checkout
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-8 relative z-10">
            
            <div class="md:col-span-3 space-y-6">
                
                <div class="bg-white/4 border border-white/10 rounded-3xl p-6 backdrop-blur-md">
                    <h2 class="text-lg font-bold flex items-center gap-2 mb-4">
                        <i class="bi bi-wallet2 text-cyan-400"></i> Pilih Metode Pembayaran
                    </h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div @click="paymentMethod = 'qris'" 
                             :class="paymentMethod === 'qris' ? 'border-cyan-500 bg-cyan-500/10 text-white' : 'border-white/10 bg-slate-950/40 text-slate-400 hover:border-white/20'"
                             class="border rounded-2xl p-4 flex flex-col items-center justify-center gap-2 cursor-pointer transition select-none">
                            <i class="bi bi-qr-code-scan text-2xl"></i>
                            <span class="text-sm font-bold">QRIS (QR Code)</span>
                        </div>

                        <div @click="paymentMethod = 'bank'" 
                             :class="paymentMethod === 'bank' ? 'border-blue-500 bg-blue-500/10 text-white' : 'border-white/10 bg-slate-950/40 text-slate-400 hover:border-white/20'"
                             class="border rounded-2xl p-4 flex flex-col items-center justify-center gap-2 cursor-pointer transition select-none">
                            <i class="bi bi-bank text-2xl"></i>
                            <span class="text-sm font-bold">Transfer Bank</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white/4 border border-white/10 rounded-3xl p-6 backdrop-blur-md">
                    
                    <div x-show="paymentMethod === 'qris'" class="text-center space-y-4" x-transition>
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Scan QRIS GadgetHub</h3>
                        <div class="w-48 h-48 bg-white p-3 rounded-2xl mx-auto shadow-lg shadow-cyan-500/10">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=GadgetHubPaymentSimulated" alt="QRIS Code" class="w-full h-full object-contain">
                        </div>
                        <p class="text-xs text-slate-400 max-w-xs mx-auto leading-relaxed">
                            Silakan scan QR code di atas menggunakan aplikasi e-wallet (Gopay, OVO, Dana) or Mobile Banking kamu.
                        </p>
                    </div>

                    <div x-show="paymentMethod === 'bank'" class="space-y-4" x-transition x-cloak>
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Rekening Transfer Resmi</h3>
                        
                        <div class="p-4 bg-slate-950/50 border border-white/5 rounded-2xl flex justify-between items-center">
                            <div>
                                <p class="text-xs font-bold text-blue-400 uppercase">Bank BCA</p>
                                <p class="text-lg font-mono font-black tracking-wider text-white mt-1">8720 4412 99</p>
                                <p class="text-xs text-slate-500 mt-0.5">a.n PT GADGETHUB INDONESIA</p>
                            </div>
                            <button type="button" @click="navigator.clipboard.writeText('8720441299'); alert('Nomor rekening BCA berhasil disalin!')" class="text-xs font-bold bg-white/5 hover:bg-white/10 px-3 py-2 rounded-xl transition border border-white/10 active:scale-95">
                                <i class="bi bi-copy"></i> Salin
                            </button>
                        </div>

                        <div class="p-4 bg-slate-950/50 border border-white/5 rounded-2xl flex justify-between items-center">
                            <div>
                                <p class="text-xs font-bold text-yellow-500 uppercase">Bank Mandiri</p>
                                <p class="text-lg font-mono font-black tracking-wider text-white mt-1">13200 9981 7721</p>
                                <p class="text-xs text-slate-500 mt-0.5">a.n PT GADGETHUB INDONESIA</p>
                            </div>
                            <button type="button" @click="navigator.clipboard.writeText('1320099817721'); alert('Nomor rekening Mandiri berhasil disalin!')" class="text-xs font-bold bg-white/5 hover:bg-white/10 px-3 py-2 rounded-xl transition border border-white/10 active:scale-95">
                                <i class="bi bi-copy"></i> Salin
                            </button>
                        </div>
                    </div>

                </div>

            </div>

            <div class="md:col-span-2">
                <form action="{{ route('checkout.payment.upload') }}" method="POST" enctype="multipart/form-data" class="bg-gradient-to-b from-slate-900 to-[#131c31] border border-white/10 rounded-3xl p-6 sticky top-24 shadow-xl space-y-6">
                    @csrf
                    <input type="hidden" name="payment_method" :value="paymentMethod">

                    <input type="hidden" name="address" value="{{ $address }}">
                    <input type="hidden" name="type" value="{{ $type }}">
                    @if(isset($productId))
                        <input type="hidden" name="product_id" value="{{ $productId }}">
                    @endif

                    <h2 class="text-base font-bold border-b border-white/10 pb-4 flex items-center gap-2">
                        <i class="bi bi-cloud-arrow-up text-emerald-400"></i> Upload Bukti Transfer
                    </h2>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Foto/Gambar Bukti</label>
                        
                        <div class="relative group border-2 border-dashed border-white/10 hover:border-emerald-500/50 bg-slate-950/40 rounded-2xl p-4 transition text-center cursor-pointer min-h-[200px] flex flex-col items-center justify-center">
                            
                            <input type="file" name="payment_proof" @change="fileChosen" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*" required>

                            <div x-show="!imagePreview" class="space-y-2 z-10 pointer-events-none">
                                <i class="bi bi-images text-3xl text-slate-600 group-hover:text-emerald-400 transition"></i>
                                <p class="text-xs font-bold text-slate-300">Pilih atau Drag file kesini</p>
                                <p class="text-[10px] text-slate-500">Mendukung format PNG, JPG, JPEG</p>
                            </div>

                            <div x-show="imagePreview" class="w-full h-full z-10" x-cloak>
                                <img :src="imagePreview" class="max-h-44 mx-auto rounded-xl object-contain border border-white/10">
                                <p class="text-[11px] text-emerald-400 font-bold mt-2"><i class="bi bi-check-circle-fill"></i> File siap diupload (Klik untuk ganti)</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-black py-4 rounded-xl shadow-lg shadow-blue-500/25 transition transform hover:-translate-y-0.5 text-center text-sm flex items-center justify-center gap-2 group">
                        <i class="bi bi-shield-lock-fill"></i> Konfirmasi Pembayaran
                    </button>

                    <p class="text-center text-[10px] text-slate-500 flex items-center justify-center gap-1 leading-relaxed">
                        <i class="bi bi-info-circle"></i> Tim admin kami akan memverifikasi bukti pembayaran kamu dalam waktu maksimal 1x24 jam.
                    </p>
                </form>
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