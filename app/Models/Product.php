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
        'stock',
        'brand_id',
        'sku',
        'weight',
        'dimensions',
        'is_track_stock',
        'is_featured',
    ];

    protected $casts = [
        'sizes' => 'array',
        'colors' => 'array',
        'categories' => 'array',
        'is_track_stock' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(ProductReview::class)->where('status', 'approved');
    }

    public function tags()
    {
        return $this->belongsToMany(ProductTag::class, 'product_tag_product', 'product_id', 'product_tag_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function comparisons()
    {
        return $this->hasMany(ProductComparison::class);
    }

    public function recentlyViewed()
    {
        return $this->hasMany(RecentlyViewedProduct::class);
    }

    public function flashSale()
    {
        return $this->hasOne(FlashSale::class);
    }

    public function flashSales()
    {
        return $this->hasMany(FlashSale::class);
    }

    public function sizeGuides()
    {
        return $this->hasMany(SizeGuide::class);
    }

    public function backInStockNotifications()
    {
        return $this->hasMany(BackInStockNotification::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('is_track_stock', false)->orWhere('stock', '>', 0);
        });
    }
}
