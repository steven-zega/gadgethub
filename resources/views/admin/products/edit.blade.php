<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - GadgetHub Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #0f172a; font-family: 'Segoe UI', sans-serif; color: white; overflow-x: hidden; }
        .sidebar { position: fixed; left: 0; top: 0; width: 270px; height: 100vh; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(18px); border-right: 1px solid rgba(255,255,255,0.08); padding: 30px 22px; z-index: 100; }
        .brand { display: flex; align-items: center; gap: 12px; margin-bottom: 45px; font-size: 1.3rem; font-weight: 700; background: linear-gradient(135deg, #3b82f6, #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .nav-link { display: flex; align-items: center; gap: 14px; color: #cbd5e1; padding: 14px 18px; border-radius: 18px; margin-bottom: 12px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; }
        .nav-link:hover, .nav-link.active { background: linear-gradient(135deg, #2563eb, #06b6d4); color: white; transform: translateX(5px); box-shadow: 0 10px 30px rgba(37,99,235,0.25); }
        .main-content { margin-left: 270px; padding: 30px; }
        .topbar { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(16px); border-radius: 28px; padding: 24px 28px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
        .card-modern { background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(16px); border-radius: 28px; padding: 28px; box-shadow: 0 15px 40px rgba(0,0,0,0.25); }
        .btn-modern { background: linear-gradient(135deg, #2563eb, #06b6d4); border: none; color: white; padding: 12px 24px; border-radius: 16px; font-weight: 600; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .btn-modern:hover { opacity: 0.9; transform: scale(1.02); color: white; }
        .form-control-dark { background: rgba(255,255,255,0.05) !important; border: 1px solid rgba(255,255,255,0.1) !important; color: white !important; border-radius: 14px; padding: 12px 16px; }
        .form-control-dark:focus { background: rgba(255,255,255,0.08) !important; border-color: #3b82f6 !important; box-shadow: 0 0 0 0.25rem rgba(59,130,246,0.25) !important; }
        .image-zone { border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; background: rgba(255,255,255,0.02); min-height: 300px; display: flex; align-items: center; justify-content: center; overflow: hidden; p: 15px; }
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
        <a href="{{ route('products.index') }}" class="nav-link active">
            <i class="bi bi-box-seam-fill"></i> Produk
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-bar-chart-line-fill"></i> Statistik
        </a>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div>
                <h4 class="m-0 fw-bold">Edit Produk</h4>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 text-white border-secondary border-opacity-50">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card-modern">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row g-5">
                    <div class="col-md-5 border-end border-secondary border-opacity-25">
                        <label class="fw-bold mb-3 d-block text-slate-300">Gambar Produk</label>
                        
                        <div class="image-zone p-3 mb-4">
                            @if($product->image)
                                <img id="image-preview" src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded-4 shadow" alt="{{ $product->name }}" style="max-height: 280px; object-fit: contain;">
                            @else
                                <img id="image-preview" src="" class="img-fluid rounded-4 shadow" alt="" style="max-height: 280px; object-fit: contain; display: none;">
                                <div id="upload-placeholder" class="text-muted text-center">
                                    <i class="bi bi-image text-secondary display-5 d-block mb-2"></i>
                                    <span class="small d-block">Belum ada gambar terunggah</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label small fw-semibold text-slate-400">Ganti Gambar</label>
                            <input type="file" name="image" class="form-control form-control-dark @error('image') is-invalid @enderror" id="image" accept="image/*" onchange="previewImg()">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted d-block mt-2" style="font-size: 0.75rem;">Kosongkan pilihan file ini jika tidak ingin memperbarui asset gambar.</small>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="mb-4">
                            <label for="name" class="form-label small fw-semibold text-slate-400">Nama Produk</label>
                            <input type="text" name="name" class="form-control form-control-dark @error('name') is-invalid @enderror" id="name" value="{{ old('name', $product->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="category" class="form-label small fw-semibold text-slate-400">Kategori Gadget</label>
                            <select name="category" class="form-select form-control-dark @error('category') is-invalid @enderror" id="category" required>
                                <option value="Handphone" {{ old('category', $product->category) == 'Handphone' ? 'selected' : '' }}>Handphone</option>
                                <option value="Laptop" {{ old('category', $product->category) == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                                <option value="Tablet" {{ old('category', $product->category) == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                            </select>
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label small fw-semibold text-slate-400">Deskripsi</label>
                            <textarea name="description" class="form-control form-control-dark @error('description') is-invalid @enderror" id="description" rows="5" required>{{ old('description', $product->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="price" class="form-label small fw-semibold text-slate-400">Harga (Rp)</label>
                                <input type="number" name="price" class="form-control form-control-dark @error('price') is-invalid @enderror" id="price" value="{{ old('price', $product->price) }}" required>
                                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="stock" class="form-label small fw-semibold text-slate-400">Stok</label>
                                <input type="number" name="stock" class="form-control form-control-dark @error('stock') is-invalid @enderror" id="stock" value="{{ old('stock', $product->stock) }}" required>
                                @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="d-grid pt-2">
                            <button type="submit" class="btn-modern py-3 justify-content-center">
                                <i class="bi bi-check-circle-fill"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImg() {
            const image = document.querySelector('#image');
            const imgPreview = document.querySelector('#image-preview');
            const placeholder = document.querySelector('#upload-placeholder');

            if (image.files && image.files[0]) {
                const oFReader = new FileReader();
                oFReader.readAsDataURL(image.files[0]);

                oFReader.onload = function(oFREvent) {
                    if(placeholder) placeholder.style.display = 'none';
                    imgPreview.style.display = 'block';
                    imgPreview.src = oFREvent.target.result;
                }
            }
        }
    </script>
</body>
</html>