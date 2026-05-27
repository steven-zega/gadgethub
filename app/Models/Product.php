<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// TAMBAHAN: Impor namespace untuk relasi HasMany ke OrderItem
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'category',
        'description',
        'price',
        'stock',
        'image',
        'specifications'
    ];

    protected $casts = [
        'specifications' => 'array',
    ];

    /**
     * Relasi ke User (Penjual/Admin yang memiliki produk ini)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * TAMBAHAN RELASI: Produk ini bisa dibeli dalam banyak item pesanan
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }
}