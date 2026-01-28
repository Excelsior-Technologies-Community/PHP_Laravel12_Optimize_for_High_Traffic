<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

   protected $fillable = [
    'title',
    'discount_code',
    'apply_on',
    'value',
    'apply_to',
    'product_ids',
    'start_date',
    'end_date',
];


}
