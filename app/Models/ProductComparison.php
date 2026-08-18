<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductComparison extends Model
{
    protected $fillable = ['customer_id', 'session_id', 'product_ids'];

    protected $casts = [
        'product_ids' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
