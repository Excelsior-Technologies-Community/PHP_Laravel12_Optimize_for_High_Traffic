<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = [
        'customer_id',
        'referral_code',
        'referred_by',
        'used_count',
        'max_uses',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function referredBy()
    {
        return $this->belongsTo(Customer::class, 'referred_by');
    }
}
