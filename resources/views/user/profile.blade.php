<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - GadgetHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#0f172a] text-white font-sans selection:bg-blue-600 selection:text-white"
      x-data="{ isEditing: false, avatarPreview: null }">

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
                    </a>
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

    <main class="max-w-4xl mx-auto px-4 py-12 relative">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="mb-8 relative z-10">
            <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-blue-400 transition group">
                <i class="bi bi-arrow-left transition-transform group-hover:-translate-x-1"></i> Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center gap-3 text-emerald-400 text-sm backdrop-blur-md animate-pulse">
                <i class="bi bi-check-circle-fill text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white/4 rounded-3xl border border-white/10 backdrop-blur-md shadow-2xl overflow-hidden relative z-10">
            
            <div class="h-32 bg-gradient-to-r from-blue-600/20 via-cyan-500/10 to-slate-900 border-b border-white/5 relative"></div>

            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-10 -mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
                @csrf
                @method('PUT')
                
                <div class="flex flex-col items-center text-center space-y-4">
                    <div class="relative group">
                        <div class="w-32 h-32 rounded-full p-1 bg-gradient-to-tr from-blue-500 via-cyan-400 to-purple-500 shadow-xl shadow-blue-500/10 overflow-hidden flex items-center justify-center">
                            
                            <template x-if="avatarPreview">
                                <img :src="avatarPreview" alt="Preview Avatar" class="w-full h-full object-cover rounded-full bg-slate-900">
                            </template>
                            
                            <template x-if="!avatarPreview">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover rounded-full bg-slate-900">
                                @else
                                    <div class="w-full h-full rounded-full bg-slate-900 flex flex-col items-center justify-center text-slate-400">
                                        <i class="bi bi-person text-5xl"></i>
                                    </div>
                                @endif
                            </template>
                        </div>
                        
                        <label x-show="isEditing" x-transition class="absolute inset-0 rounded-full bg-slate-950/80 border border-dashed border-cyan-400 flex flex-col items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 transition duration-300">
                            <i class="bi bi-camera text-xl text-cyan-400 mb-1"></i>
                            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-300">Ganti Foto</span>
                            
                            <input type="file" name="avatar" class="hidden" accept="image/*"
                                   @change="const file = $event.target.files[0]; if(file) { avatarPreview = URL.createObjectURL(file) }">
                        </label>
                    </div>

                    <div>
                        <h2 class="text-xl font-black tracking-tight text-white">{{ auth()->user()->name }}</h2>
                        <p class="text-xs font-medium text-blue-400 mt-1 uppercase tracking-widest">Verified Customer</p>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-6">
                    
                    <div class="space-y-4">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                            <i class="bi bi-shield-lock text-blue-400"></i> Informasi Akun
                        </h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-white/5 rounded-2xl p-4 border border-white/5">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama</label>
                                <div x-show="!isEditing" class="text-sm font-semibold text-white py-1">{{ auth()->user()->name }}</div>
                                <input x-show="isEditing" type="text" name="name" value="{{ auth()->user()->name }}" class="w-full bg-slate-950/50 border border-white/10 rounded-xl px-3 py-1.5 text-sm text-white focus:outline-none focus:border-blue-500 transition" required>
                            </div>

                            <div class="bg-white/5 rounded-2xl p-4 border border-white/5">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Email</label>
                                <div x-show="!isEditing" class="text-sm font-semibold text-white py-1">{{ auth()->user()->email }}</div>
                                <input x-show="isEditing" type="email" name="email" value="{{ auth()->user()->email }}" class="w-full bg-slate-950/50 border border-white/10 rounded-xl px-3 py-1.5 text-sm text-white focus:outline-none focus:border-blue-500 transition" required>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                            <i class="bi bi-geo-alt text-cyan-400"></i> Alamat
                        </h3>

                        @if(empty(auth()->user()->address))
                            <div x-show="!isEditing" class="p-6 bg-blue-500/5 border border-dashed border-blue-500/20 rounded-2xl text-center">
                                <i class="bi bi-building-add text-blue-400 text-3xl block mb-2"></i>
                                <p class="text-sm font-medium text-slate-300">Alamat default pengiriman belum diatur</p>
                                <p class="text-xs text-slate-500 mt-1 mb-4">Atur alamat sekarang untuk mempermudah proses pembuatan invoice checkout belanjaanmu.</p>
                                <button type="button" @click="isEditing = true" class="inline-flex items-center gap-1.5 bg-blue-600/20 hover:bg-blue-600 text-blue-400 hover:text-white text-xs font-bold px-4 py-2 rounded-xl transition">
                                    <i class="bi bi-plus-lg"></i> Isi Alamat Pertama Kali
                                </button>
                            </div>
                        @else
                            <div x-show="!isEditing" class="bg-white/5 rounded-2xl p-5 border border-white/5">
                                <p class="text-sm leading-relaxed text-slate-200 whitespace-pre-line">{{ auth()->user()->address }}</p>
                            </div>
                        @endif

                        <div x-show="isEditing" x-transition>
                            <textarea name="address" rows="4" placeholder="Masukkan alamat lengkap pengiriman kamu (Nama Jalan, No. Rumah, RT/RW, Kecamatan, Kota/Kabupaten, Kode Pos)..." class="w-full bg-slate-950/50 border border-white/10 rounded-2xl p-4 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-cyan-400 transition leading-relaxed">{{ auth()->user()->address }}</textarea>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-white/5 flex justify-end gap-3">
                        <button type="button" x-show="!isEditing" @click="isEditing = true" class="inline-flex items-center gap-2 bg-white/5 hover:bg-white/10 text-white text-sm font-bold px-6 py-3 rounded-xl border border-white/10 transition transform hover:-translate-y-0.5">
                            <i class="bi bi-pencil-square text-blue-400"></i> Edit
                        </button>

                        <button type="button" x-show="isEditing" @click="isEditing = false; avatarPreview = null" class="inline-flex items-center gap-2 bg-slate-800 text-slate-400 hover:text-white text-sm font-bold px-5 py-3 rounded-xl transition" x-cloak>
                            Batal
                        </button>
                        <button type="submit" x-show="isEditing" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-cyan-500 text-white text-sm font-bold px-6 py-3 rounded-xl shadow-lg shadow-blue-500/20 hover:opacity-90 transition transform hover:-translate-y-0.5" x-cloak>
                            <i class="bi bi-cloud-arrow-up-fill"></i> Simpan Perubahan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </main>

    <footer class="bg-[#0b111e] text-slate-500 py-10 border-t border-white/5 mt-32">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs tracking-wider">
            &copy; {{ date('Y') }} GADGETHUB INDONESIA. All rights reserved.
        </div>
    </footer>

</body>
</html>