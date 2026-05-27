<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Halaman Checkout Utama
     */
    public function index(Request $request)
    {
        $type = $request->query('type', 'cart');
        $checkoutItems = [];
        $totalPrice = 0;
        $productId = null;
        $quantity = $request->query('quantity', 1); // Tangkap kuantitas dari awal

        if ($type === 'instant') {
            $productId = $request->query('product_id');
            $product = Product::findOrFail($productId);
            
            $subtotal = $product->price * $quantity;
            $totalPrice = $subtotal;

            $checkoutItems[] = (object)[
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        } else {
            $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
            
            foreach ($cartItems as $item) {
                $subtotal = $item->product->price * $item->quantity;
                $totalPrice += $subtotal;
                
                $checkoutItems[] = (object)[
                    'product' => $item->product,
                    'quantity' => $item->quantity,
                    'subtotal' => $subtotal
                ];
            }
        }

        return view('user.checkout', compact('checkoutItems', 'totalPrice', 'type', 'productId', 'quantity'));
    }

    /**
     * Halaman Metode & Upload Pembayaran
     */
    public function payment(Request $request)
    {
        // Jika user me-refresh halaman (GET) atau mengakses langsung tanpa isi alamat, kembalikan ke checkout
        if ($request->isMethod('get') || !$request->has('address')) {
            return redirect()->route('checkout.index')->with('error', 'Silakan isi alamat pengiriman terlebih dahulu.');
        }

        // Menangkap seluruh input dari form checkout via POST
        $address = $request->input('address');
        $type = $request->input('type');
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1); 

        return view('user.payment', compact('address', 'type', 'productId', 'quantity'));
    }

    public function uploadPayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'required',
            'type' => 'required'
        ]);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');

            // 1. Hitung total harga ulang di backend (keamanan data)
            $totalPrice = 0;
            $itemsToSave = [];

            if ($request->type === 'instant') {
                $product = Product::findOrFail($request->product_id);
                $quantity = $request->input('quantity', 1); 
                
                // Validasi tambahan: Cek stok barang sebelum deal dibeli
                if ($product->stock < $quantity) {
                    return redirect()->back()->with('error', 'Stok produk ' . $product->name . ' tidak mencukupi.');
                }

                $totalPrice = $product->price * $quantity;
                $itemsToSave[] = ['product_id' => $product->id, 'quantity' => $quantity, 'price' => $product->price];
            } else {
                $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
                foreach ($cartItems as $item) {
                    if ($item->product->stock < $item->quantity) {
                        return redirect()->back()->with('error', 'Stok produk ' . $item->product->name . ' tidak mencukupi.');
                    }
                    $totalPrice += $item->product->price * $item->quantity;
                    $itemsToSave[] = ['product_id' => $item->product_id, 'quantity' => $item->quantity, 'price' => $item->product->price];
                }
            }

            // 2. Buat data Order induk
            $order = Order::create([
                'user_id' => auth()->id(),
                'invoice_number' => 'INV-' . strtoupper(Str::random(8)) . '-' . time(),
                'buyer_name' => auth()->user()->name, 
                'address' => $request->address,
                'payment_method' => $request->payment_method,
                'payment_proof' => $path,
                'total_price' => $totalPrice,
                'status' => 'pending'
            ]);

            // 3. Simpan item detailnya & Potong stok otomatis
            foreach ($itemsToSave as $item) {
                // 🌟 PERBAIKAN: Menyertakan 'status' => 'pending' pada level OrderItem saat data dimasukkan pertama kali
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'status' => 'pending' 
                ]);

                // Potong stok produk dari database penjual/admin
                Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
            }

            // 4. Jika checkout dari keranjang, hapus keranjang user karena sudah dibeli
            if ($request->type === 'cart') {
                Cart::where('user_id', auth()->id())->delete();
            }

            return redirect()->route('user.orders')->with('success', 'Pesanan berhasil dibuat! Menunggu verifikasi admin.');
        }

        return redirect()->back()->with('error', 'Gagal memproses pembayaran.');
    }

    /**
     * Menampilkan Halaman Riwayat Pemesanan User
     */
    public function orders()
    {
        // Mengambil riwayat order milik user yang sedang login, diurutkan dari yang terbaru
        $orders = Order::where('user_id', auth()->id())->with('items.product')->latest()->get();
        
        return view('user.orders', compact('orders'));
    }
}