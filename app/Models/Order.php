<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// PERBAIKAN: Impor namespace resmi untuk relasi Eloquent
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    // PERBAIKAN: Tambahkan 'buyer_name' agar diizinkan masuk dalam Mass Assignment (create/update)
    protected $fillable = [
        'user_id', 
        'invoice_number', 
        'buyer_name', 
        'address', 
        'payment_method', 
        'payment_proof', 
        'total_price', 
        'status'
    ];

    /**
     * Relasi ke User (Konsumen/Pembeli yang membuat pesanan ini)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * PERBAIKAN SINTAKS: Relasi ke rincian item produk yang dibeli
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}