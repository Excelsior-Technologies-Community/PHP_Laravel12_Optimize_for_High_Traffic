<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    protected $fillable = [
        'code',
        'amount',
        'balance',
        'customer_id',
        'recipient_name',
        'recipient_email',
        'message',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'amount' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transactions()
    {
        return $this->hasMany(GiftCardTransaction::class);
    }
}
