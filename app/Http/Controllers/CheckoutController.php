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
            $itemsToGroup = [];

            // 1. Ambil data item belanjaan
            if ($request->type === 'instant') {
                $product = Product::findOrFail($request->product_id);
                $quantity = $request->input('quantity', 1); 
                
                if ($product->stock < $quantity) {
                    return redirect()->back()->with('error', 'Stok produk ' . $product->name . ' tidak mencukupi.');
                }

                $itemsToGroup[] = [
                    'admin_id' => $product->user_id ?? 1, // 🌟 PERBAIKAN: Menggunakan 'user_id' sesuai tabel products kamu
                    'product_id' => $product->id, 
                    'quantity' => $quantity, 
                    'price' => $product->price
                ];
            } else {
                // Ambil data keranjang dengan relasi produk
                $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
                foreach ($cartItems as $item) {
                    if ($item->product->stock < $item->quantity) {
                        return redirect()->back()->with('error', 'Stok produk ' . $item->product->name . ' tidak mencukupi.');
                    }
                    $itemsToGroup[] = [
                        'admin_id' => $item->product->user_id ?? 1, // 🌟 PERBAIKAN: Menggunakan 'user_id' sesuai tabel products kamu
                        'product_id' => $item->product_id, 
                        'quantity' => $item->quantity, 
                        'price' => $item->product->price
                    ];
                }
            }

            // 2. Kelompokkan item berdasarkan user_id (Admin Pemilik Produk)
            $groupedItems = collect($itemsToGroup)->groupBy('admin_id');

            // 3. Looping untuk membuat satu invoice per admin
            foreach ($groupedItems as $adminId => $items) {
                $totalPricePerAdmin = $items->sum(function($item) {
                    return $item['price'] * $item['quantity'];
                });

                // Buat invoice unik per admin
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'admin_id' => $adminId, // Menyimpan ID Admin pemilik ke orders
                    'invoice_number' => 'INV-' . strtoupper(Str::random(5)) . '-' . $adminId . '-' . time(),
                    'buyer_name' => auth()->user()->name, 
                    'address' => $request->address,
                    'payment_method' => $request->payment_method,
                    'payment_proof' => $path, 
                    'total_price' => $totalPricePerAdmin,
                    'status' => 'pending'
                ]);

                // Simpan detail item ke invoice terkait
                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'status' => 'pending'
                    ]);

                    // Potong stok produk
                    Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
                }
            }

            // 4. Hapus keranjang jika checkout dari cart
            if ($request->type === 'cart') {
                Cart::where('user_id', auth()->id())->delete();
            }

            return redirect()->route('user.orders')->with('success', 'Pesanan berhasil dibuat! Invoice otomatis terpisah berdasarkan pemilik toko.');
        }

        return redirect()->back()->with('error', 'Gagal memproses pembayaran.');
    }
    
    public function orders()
    {
        // 🌟 PERBAIKAN: Hanya mengambil order yang is_visible bernilai true (1)
        $orders = Order::where('user_id', auth()->id())
                        ->where('is_visible', true)
                        ->with('items.product')
                        ->latest()
                        ->get();
        
        return view('user.orders', compact('orders'));
    }

    public function hideOrder($id)
    {
        // Cari order milik user tersebut
        $order = Order::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        
        // 🌟 PERBAIKAN: Cukup ubah is_visible menjadi false. Data di database TETAP AMAN!
        $order->update([
            'is_visible' => false
        ]);

        return redirect()->back()->with('success', 'Riwayat pesanan telah berhasil dihapus.');
    }

    public function reorder($id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->with('items.product')->firstOrFail();

        foreach ($order->items as $item) {
            // Validasi: Pastikan produk masih ada dan stoknya di atas 0
            if ($item->product && $item->product->stock > 0) {
                
                // Cari tahu apakah produk ini sudah ada di dalam keranjang belanja user
                $existingCart = Cart::where('user_id', auth()->id())
                                    ->where('product_id', $item->product_id)
                                    ->first();

                if ($existingCart) {
                    // Jika ada, jumlahkan kuantitas barunya, namun batasi agar tidak melampaui sisa stok riil produk
                    $newQty = $existingCart->quantity + $item->quantity;
                    $existingCart->update([
                        'quantity' => min($newQty, $item->product->stock)
                    ]);
                } else {
                    // Jika belum ada, buat record baru di dalam keranjang belanja
                    Cart::create([
                        'user_id' => auth()->id(),
                        'product_id' => $item->product_id,
                        'quantity' => min($item->quantity, $item->product->stock)
                    ]);
                }
            }
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dimasukkan kembali ke keranjang belanja. Silakan periksa sisa kuantitas stok Anda.');
    }
}