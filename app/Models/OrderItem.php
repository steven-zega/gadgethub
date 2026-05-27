<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // 🌟 SELESAI DIUBAH: Menambahkan 'status' ke dalam $fillable agar aksi verifikasi admin tersimpan ke DB
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price', 'status'];

    /**
     * Relasi ke model Order (Menghubungkan item pesanan ke data induk Order)
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}