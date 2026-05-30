<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
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

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:success,rejected'
        ]);

        $orderItem = OrderItem::findOrFail($id);

        $orderItem->update([
            'status' => $request->status
        ]);

        $message = $request->status === 'success' 
            ? 'Pembayaran produk berhasil diverifikasi!' 
            : 'Pembayaran produk telah ditolak.';

        // 5. Kembalikan ke halaman pesanan dengan pesan sukses
        return redirect()->back()->with('success', $message);
    }
}