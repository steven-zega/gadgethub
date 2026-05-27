<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // Hanya mengambil produk milik admin yang sedang login
        $products = Product::where('user_id', auth()->id())->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function show($id)
    {
        // Memastikan produk yang dilihat adalah milik admin yang sedang login
        $product = Product::where('user_id', auth()->id())->findOrFail($id);

        return view('admin.products.show', compact('product'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'category' => 'required|in:Handphone,Laptop,Tablet',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'description' => 'nullable',
            'image' => 'nullable|image',
            'specifications' => 'nullable|array', // <-- PERUBAHAN: Izinkan data spesifikasi berbentuk array masuk
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Otomatis mengikat user_id dengan id admin yang sedang login
        $data['user_id'] = auth()->id();

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created!');
    }

    public function edit(Product $product)
    {
        // Pengaman: Jika admin mencoba mengedit produk milik orang lain, lempar error 403
        if ($product->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah produk ini.');
        }

        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // Pengaman: Pastikan produk yang diupdate adalah miliknya sendiri
        if ($product->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah produk ini.');
        }

        $data = $request->validate([
            'name' => 'required',
            'category' => 'required|in:Handphone,Laptop,Tablet',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'description' => 'nullable',
            'image' => 'nullable|image',
            'specifications' => 'nullable|array', // <-- PERUBAHAN: Izinkan data spesifikasi berbentuk array masuk saat update produk
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated!');
    }

    public function destroy(Product $product)
    {
        // Pengaman: Pastikan produk yang dihapus adalah miliknya sendiri
        if ($product->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus produk ini.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted!');
    }
}