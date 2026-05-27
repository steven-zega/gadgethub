<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Menampilkan Halaman Checkout (Mendukung Keranjang & Instant)
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $checkoutItems = collect();
        $type = $request->query('type', 'cart'); // 'cart' atau 'instant'
        $productId = null; // Inisialisasi ID produk untuk tombol kembali

        if ($type === 'instant') {
            // Validasi data untuk instant checkout
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $product = Product::findOrFail($request->product_id);
            $quantity = $request->quantity;
            $productId = $product->id; // Simpan ID produk untuk dilempar ke view

            // Masukkan ke koleksi object palsu mirip struktur Cart agar blade tinggal pakai
            $checkoutItems->push((object)[
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $product->price * $quantity
            ]);
        } else {
            // Ambil dari keranjang belanja database
            $cartData = Cart::where('user_id', $user->id)->with('product')->get();
            
            if ($cartData->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong, tidak bisa checkout.');
            }

            foreach ($cartData as $item) {
                $checkoutItems->push((object)[
                    'product' => $item->product,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->product->price * $item->quantity
                ]);
            }
        }

        $totalPrice = $checkoutItems->sum('subtotal');

        // Mengirimkan variabel $type dan $productId ke file blade
        return view('user.checkout', compact('checkoutItems', 'totalPrice', 'type', 'productId'));
    }

    /**
     * Proses Pembayaran / Submit Order
     */
    public function process(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'type' => 'required|in:cart,instant',
        ]);

        // SEMENTARA: Kurangi stok produk & Kosongkan keranjang jika tipe 'cart'
        // Nanti di sini tempat integrasi Midtrans / Gateway Pembayaran
        if ($request->type === 'cart') {
            Cart::where('user_id', auth()->id())->delete();
        }

        return redirect()->route('user.dashboard')->with('success', 'Pesanan berhasil dibuat! Terima kasih telah berbelanja di GadgetHub.');
    }
}