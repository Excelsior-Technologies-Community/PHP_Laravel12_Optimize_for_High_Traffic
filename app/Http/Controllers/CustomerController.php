<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use App\Models\Brand;
use App\Models\RecentlyViewedProduct;
use App\Models\WishlistItem;
use App\Models\FlashSale;
use App\Models\Banner;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search      = $request->search;
        $brandFilter = $request->brand_id;

        $sizeIds     = Size::where('size_name', 'like', "%{$search}%")->pluck('id')->toArray();
        $colorIds    = Color::where('color_name', 'like', "%{$search}%")->pluck('id')->toArray();
        $categoryIds = Category::where('category_name', 'like', "%{$search}%")->pluck('id')->toArray();

        $cacheKey = 'customer.products.' . md5($search . $request->page . $brandFilter);
        $products = Cache::remember($cacheKey, 300, function () use ($search, $sizeIds, $colorIds, $categoryIds, $brandFilter) {
            return Product::with(['brand', 'variants', 'sizeGuides'])
                ->where('status', 'active')
                ->when($brandFilter, fn ($q) => $q->where('brand_id', $brandFilter))
                ->when($search, function ($query) use ($search, $sizeIds, $colorIds, $categoryIds) {
                    $query->where(function ($q) use ($search, $sizeIds, $colorIds, $categoryIds) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('details', 'like', "%{$search}%")
                          ->orWhere('price', 'like', "%{$search}%");
                        foreach ($sizeIds as $id)     { $q->orWhereJsonContains('sizes', $id); }
                        foreach ($colorIds as $id)    { $q->orWhereJsonContains('colors', $id); }
                        foreach ($categoryIds as $id) { $q->orWhereJsonContains('categories', $id); }
                    });
                })
                ->orderBy('id', 'asc')
                ->paginate(8);
        });

        $sizes      = Cache::remember('filters.sizes', 3600, fn () => Size::pluck('size_name', 'id'));
        $colors     = Cache::remember('filters.colors', 3600, fn () => Color::pluck('color_name', 'id'));
        $categories = Cache::remember('filters.categories', 3600, fn () => Category::pluck('category_name', 'id'));
        $brands     = Cache::remember('filters.brands', 3600, fn () => Brand::where('status', 'active')->get(['id', 'name', 'image']));

        $isCustomerLoggedIn = auth('customer')->check();
        $customer           = auth('customer')->user();
        $wishlistProductIds = [];
        $recentlyViewed     = collect();
        $recommendations    = collect();

        if ($isCustomerLoggedIn) {
            $recentlyViewed = RecentlyViewedProduct::with('product')
                ->where('customer_id', $customer->id)
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get()
                ->pluck('product')
                ->filter();

            $wishlistProductIds = WishlistItem::where('customer_id', $customer->id)
                ->pluck('product_id')
                ->toArray();

            $viewedProductIds   = $recentlyViewed->pluck('id')->toArray();
            $currentProductIds  = $products->pluck('id')->toArray();
            $excludeIds         = array_merge($viewedProductIds, $currentProductIds);

            // Fixed: use proper category IDs from viewed products, not $categories keys
            $viewedCategoryIds = $recentlyViewed
                ->flatMap(fn ($p) => $p->categories ?? [])
                ->unique()
                ->take(3)
                ->toArray();

            $recommendations = Product::where('status', 'active')
                ->when($viewedCategoryIds, function ($q) use ($viewedCategoryIds) {
                    $q->where(function ($inner) use ($viewedCategoryIds) {
                        foreach ($viewedCategoryIds as $catId) {
                            $inner->orWhereJsonContains('categories', $catId);
                        }
                    });
                })
                ->whereNotIn('id', $excludeIds)
                ->inRandomOrder()
                ->limit(4)
                ->get(['id', 'name', 'price', 'image', 'status']);
        }

        $flashSales = Cache::remember('flash.sales.active', 60, fn () =>
            FlashSale::with('product')
                ->where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->whereColumn('sold', '<', 'stock')
                ->get()
        );

        $banners = Cache::remember('banners.active', 300, fn () =>
            Banner::where('status', 'active')->orderBy('sort_order')->get()
        );

        $today = Carbon::today();
        $activeDiscounts = Cache::remember('discounts.active', 300, function () use ($today) {
            return Discount::where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->get();
        });

        return view('customer.index', compact(
            'products', 'sizes', 'colors', 'categories', 'brands', 'brandFilter',
            'isCustomerLoggedIn', 'customer', 'wishlistProductIds',
            'recentlyViewed', 'recommendations', 'flashSales', 'banners', 'activeDiscounts'
        ));
    }
}
