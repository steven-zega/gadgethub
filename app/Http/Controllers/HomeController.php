<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role == 'admin') {
            return redirect('/admin/dashboard');
        }

        // Ambil data filter kategori dari URL (contoh: ?category=Laptop)
        $categoryFilter = $request->query('category');

        // Mulai base query untuk mengambil produk yang stoknya masih ada
        $query = Product::where('stock', '>', 0);

        // Jika customer memilih salah satu kategori, tambahkan filter di query
        if ($categoryFilter) {
            $query->where('category', $categoryFilter);
        }

        // Urutkan dari yang terbaru dan ambil datanya
        $products = $query->latest()->get();
        
        // Return ke view customer lama kamu 'user.index'
        return view('user.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $seller = User::where('role', 'admin')->first();

        return view('user.show', compact('product', 'seller'));
    }
}