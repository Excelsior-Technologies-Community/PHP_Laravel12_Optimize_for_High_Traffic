<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RecentlyViewedProduct;
use App\Models\Category;
use App\Models\ProductTag;
use Illuminate\Http\Request;

class ProductRecommendationController extends Controller
{
    public function trackView(Request $request, Product $product)
    {
        $customerId = auth('customer')->id();

        if ($customerId) {
            RecentlyViewedProduct::updateOrCreate(
                [
                    'customer_id' => $customerId,
                    'product_id'  => $product->id,
                ],
                [
                    'ip_address' => $request->ip(),
                ]
            );
        }

        return response()->json(['status' => true]);
    }

    public function related(Product $product)
    {
        $categoryIds = $product->categories ?? [];
        $tagIds = $product->tags ?? [];

        $relatedProducts = Product::where('status', 'active')
            ->where('id', '!=', $product->id)
            ->when(!empty($categoryIds), function ($query) use ($categoryIds) {
                $query->whereJsonContains('categories', $categoryIds[0]);
            })
            ->when(!empty($tagIds), function ($query) use ($tagIds) {
                foreach ($tagIds as $tagId) {
                    $query->orWhereJsonContains('tags', $tagId);
                }
            })
            ->limit(8)
            ->get();

        if (request()->wantsJson()) {
            return response()->json([
                'status' => true,
                'data'   => $relatedProducts,
            ]);
        }

        return view('products.related', compact('relatedProducts', 'product'));
    }
}
