<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'size_id',
        'color_id',
        'category_id',
        'quantity',
        'price',
        'discount_amount', // ✅ NEW
        'total',
    ];

    // 🔗 Item → Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // 🔗 Item → Size
    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    // 🔗 Item → Color
    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    // 🔗 Item → Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
