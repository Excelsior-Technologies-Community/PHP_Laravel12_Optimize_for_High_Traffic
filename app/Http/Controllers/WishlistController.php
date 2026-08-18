<?php

namespace App\Http\Controllers;

use App\Models\WishlistItem;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // ❤️ WISHLIST INDEX
    public function index(Request $request)
    {
        $customerId = auth('customer')->id();

        $wishlistItems = WishlistItem::with('product.brand')
            ->where('customer_id', $customerId)
            ->latest()
            ->get();

        $sizes = Size::pluck('size_name', 'id');
        $colors = Color::pluck('color_name', 'id');
        $categories = Category::pluck('category_name', 'id');

        return view('wishlist.index', compact(
            'wishlistItems',
            'sizes',
            'colors',
            'categories'
        ));
    }

    // ➕ ADD TO WISHLIST
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $customerId = auth('customer')->id();

        $exists = WishlistItem::where('customer_id', $customerId)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Product is already in your wishlist');
        }

        WishlistItem::create([
            'customer_id' => $customerId,
            'product_id' => $request->product_id,
        ]);

        return redirect()->route('wishlist.index')
            ->with('success', 'Product added to wishlist');
    }

    // ❌ REMOVE FROM WISHLIST
    public function destroy(WishlistItem $wishlist)
    {
        if ($wishlist->customer_id !== auth('customer')->id()) {
            abort(403);
        }

        $wishlist->delete();

        return redirect()->route('wishlist.index')
            ->with('success', 'Product removed from wishlist');
    }

    // ❌ REMOVE FROM WISHLIST BY PRODUCT ID
    public function destroyByProduct(Product $product)
    {
        $customerId = auth('customer')->id();

        $wishlistItem = WishlistItem::where('customer_id', $customerId)
            ->where('product_id', $product->id)
            ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
        }

        return redirect()->back()
            ->with('success', 'Product removed from wishlist');
    }
}
