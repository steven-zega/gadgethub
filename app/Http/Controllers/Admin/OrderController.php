<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItem; // Fokus utama pada item pesanan
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = OrderItem::with(['order', 'product'])
            ->whereHas('product', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Memproses verifikasi setuju atau tolak pembayaran per ITEM produk
     */
    public function updateStatus(Request $request, $id)
    {
        // 1. Validasi input status, hanya boleh 'success' atau 'rejected'
        $request->validate([
            'status' => 'required|in:success,rejected'
        ]);

        // 2. PERBAIKAN: Cari data berdasarkan ID OrderItem (bukan Order induk)
        $orderItem = OrderItem::findOrFail($id);

        // 3. Update status spesifik untuk produk ini saja
        $orderItem->update([
            'status' => $request->status
        ]);

        // 4. Set teks notifikasi kilat berdasarkan pilihan aksi admin
        $message = $request->status === 'success' 
            ? 'Pembayaran produk berhasil diverifikasi!' 
            : 'Pembayaran produk telah ditolak.';

        // 5. Kembalikan ke halaman pesanan dengan pesan sukses
        return redirect()->back()->with('success', $message);
    }
}