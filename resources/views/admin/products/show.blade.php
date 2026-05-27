@extends('admin.dashboard')

@section('content')
<div class="container">
    <div class="mb-3">
        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">< Back to List</a>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden bg-white" style="border-radius: 15px;">
        <div class="row g-0 d-flex align-items-stretch">
            
            <div class="col-md-5 d-flex">
                <img src="{{ asset('storage/' . $product->image) }}" 
                     class="img-fluid w-100 h-100" 
                     alt="{{ $product->name }}"
                     style="object-fit: cover; min-height: 400px;">
            </div>
            
            <div class="col-md-7">
                <div class="card-body p-4">
                    <h1 class="fw-bold mb-3 text-dark">{{ $product->name }}</h1>

                    <div class="mb-4">
                        <h6 class="fw-bold text-muted small">Deskripsi Produk</h6>
                        <p class="text-secondary">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>
                    </div>

                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div>
                            <h6 class="text-muted small mb-1">Stok</h6>
                            <p class="fw-bold mb-0 text-dark">{{ $product->stock ?? 0 }} pcs</p>
                        </div>
                        
                        <div style="border-left: 1px solid #dee2e6; height: 30px;"></div>

                        <div>
                        {{-- Logika Stok Konsisten: Habis, Hampir Habis, atau Tersedia --}}
                        @if($product->stock <= 0)
                            <span class="badge bg-danger px-3 py-2">Habis</span>
                        @elseif($product->stock <= 5)
                            <span class="badge bg-warning text-dark px-3 py-2">Hampir Habis: {{ $product->stock }}</span>
                        @else
                            <span class="badge bg-success px-3 py-2">Tersedia</span>
                        @endif
                        </div>
                    </div>

                    <hr class="my-4 text-muted opacity-25">

                    <div class="mb-4">
                        <h6 class="text-muted small mb-1">Harga Satuan</h6>
                        <h2 class="text-primary fw-bold">
                            Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}
                        </h2>
                    </div>

                    <div class="d-flex gap-2 pt-2">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning px-4 text-white fw-bold shadow-sm">Edit</a>
                        
                        {{-- Button Delete dengan SweetAlert2 --}}
                        <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger px-4 fw-bold shadow-sm" onclick="confirmDelete({{ $product->id }})">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Script SweetAlert2 untuk konsistensi notifikasi --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Produk?',
            text: "Data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            background: '#ffffff',
            customClass: {
                popup: 'rounded-4 shadow',
                title: 'fw-bold text-dark'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection