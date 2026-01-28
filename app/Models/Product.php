<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  protected $fillable = [
    'name',
    'details',
    'price',
    'image',
    'sizes',
    'colors',
    'categories',
     'status',
];

protected $casts = [
    'sizes' => 'array',
    'colors' => 'array',
    'categories' => 'array',
];

public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}

}


