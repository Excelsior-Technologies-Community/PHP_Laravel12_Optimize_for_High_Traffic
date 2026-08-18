<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecentlyViewedProduct extends Model
{
    protected $fillable = ['customer_id', 'product_id', 'ip_address'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
