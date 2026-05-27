<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Tampilkan halaman keranjang
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
        return view('user.cart', compact('cartItems'));
    }

    // Tambah produk ke keranjang (dari tombol di katalog)
    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        // Cek apakah produk sudah ada di keranjang user
        $cart = Cart::where('user_id', auth()->id())
                    ->where('product_id', $productId)
                    ->first();

        if ($cart) {
            if ($cart->quantity < $product->stock) {
                $cart->increment('quantity');
            }
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Update quantity via AJAX/Fetch API (dipakai Alpine.js)
    public function updateQuantity(Request $request, $id)
    {
        $cart = Cart::where('user_id', auth()->id())->findOrFail($id);
        $action = $request->input('action'); // 'increase' atau 'decrease'
        $product = $cart->product;

        if ($action === 'increase') {
            if ($cart->quantity >= $product->stock) {
                return response()->json(['error' => 'Stok tidak mencukupi'], 400);
            }
            $cart->increment('quantity');
        } elseif ($action === 'decrease') {
            $cart->decrement('quantity');
            
            // Jika quantity jadi 0, hapus dari database
            if ($cart->quantity <= 0) {
                $cart->delete();
                return response()->json(['deleted' => true]);
            }
        }

        return response()->json([
            'quantity' => $cart->quantity,
            'subtotal' => $cart->quantity * $product->price
        ]);
    }
}