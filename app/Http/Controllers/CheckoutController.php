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

        if ($type === 'instant') {
            $productId = $request->query('product_id');
            $quantity = $request->query('quantity', 1);
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

        // DIUBAH: Mengarah ke 'user.checkout' karena filemu ada di folder resources/views/user/
        return view('user.checkout', compact('checkoutItems', 'totalPrice', 'type', 'productId'));
    }

    /**
     * Halaman Metode & Upload Pembayaran
     */
    public function payment(Request $request)
    {
        // UBAH dari query() menjadi input() karena dikirim via POST
        $address = $request->input('address');
        $type = $request->input('type');
        $productId = $request->input('product_id');

        return view('user.payment', compact('address', 'type', 'productId'));
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
                $quantity = $request->query('quantity', 1); // atau tangkap dari input jika ada
                $totalPrice = $product->price * $quantity;
                $itemsToSave[] = ['product_id' => $product->id, 'quantity' => $quantity, 'price' => $product->price];
            } else {
                $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
                foreach ($cartItems as $item) {
                    $totalPrice += $item->product->price * $item->quantity;
                    $itemsToSave[] = ['product_id' => $item->product_id, 'quantity' => $item->quantity, 'price' => $item->product->price];
                }
            }

            // 2. Buat data Order induk
            $order = Order::create([
                'user_id' => auth()->id(),
                'invoice_number' => 'INV-' . strtoupper(Str::random(8)) . '-' . time(),
                'address' => $request->address,
                'payment_method' => $request->payment_method,
                'payment_proof' => $path,
                'total_price' => $totalPrice,
                'status' => 'pending'
            ]);

            // 3. Simpan item detailnya
            foreach ($itemsToSave as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            // 4. Jika checkout dari keranjang, hapus keranjang user karena sudah dibeli
            if ($request->type === 'cart') {
                Cart::where('user_id', auth()->id())->delete();
            }

            // Redirect langsung ke rute riwayat pesanan dengan pesan sukses
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