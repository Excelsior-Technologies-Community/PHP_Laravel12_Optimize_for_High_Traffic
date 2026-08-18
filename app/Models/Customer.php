<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
    ];

    protected $hidden = [
        'password',
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function comparisons()
    {
        return $this->hasMany(ProductComparison::class);
    }

    public function recentlyViewed()
    {
        return $this->hasMany(RecentlyViewedProduct::class);
    }

    public function wallet()
    {
        return $this->hasOne(CustomerWallet::class);
    }

    public function returnRequests()
    {
        return $this->hasMany(ReturnRequest::class);
    }

    public function referral()
    {
        return $this->hasOne(Referral::class);
    }

    public function giftCards()
    {
        return $this->hasMany(GiftCard::class);
    }

    public function backInStockNotifications()
    {
        return $this->hasMany(BackInStockNotification::class);
    }
}
