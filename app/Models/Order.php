<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'invoice_number', 'address', 'payment_method', 'payment_proof', 'total_price', 'status'];

    public function items()
    {
        return $table = $this->hasMany(OrderItem::class);
    }
}