<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftCardTransaction extends Model
{
    protected $fillable = [
        'gift_card_id',
        'type',
        'amount',
        'reference_type',
        'reference_id',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function giftCard()
    {
        return $this->belongsTo(GiftCard::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
