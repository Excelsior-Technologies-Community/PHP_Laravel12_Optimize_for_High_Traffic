<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'size_id',
        'color_id',
        'category_id',
        'price',
        'stock',
        'image',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function flashSale()
    {
        return $this->hasOne(FlashSale::class);
    }
}
