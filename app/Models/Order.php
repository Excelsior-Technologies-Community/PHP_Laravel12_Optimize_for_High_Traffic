<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'address_id',
        'subtotal',          // ✅ NEW
        'discount_amount',   // ✅ NEW
        'total_price',
        'payment_method',
        'payment_status',    // (ONLINE / COD)
        'status',            // (pending, shipped, delivered etc.)
    ];

    // 🔗 Order → Items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // 🔗 Order → Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // 🔗 Order → Address
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
