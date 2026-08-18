<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackInStockNotification extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'product_variant_id',
        'notified',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
