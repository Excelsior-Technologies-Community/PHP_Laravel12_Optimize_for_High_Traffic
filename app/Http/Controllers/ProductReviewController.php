<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    // 📝 PRODUCT REVIEWS
    public function index(Request $request, Product $product)
    {
        $reviews = ProductReview::with('customer')
            ->where('product_id', $product->id)
            ->where('status', 'approved')
            ->latest()
            ->paginate(10);

        return view('reviews.index', compact('reviews', 'product'));
    }

    // ➕ ADD REVIEW
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'review'     => 'required|string|min:10',
        ]);

        ProductReview::create([
            'customer_id' => auth('customer')->id(),
            'product_id'  => $request->product_id,
            'rating'      => $request->rating,
            'review'      => $request->review,
            'status'      => 'pending',
        ]);

        return redirect()->back()
            ->with('success', 'Review submitted for approval');
    }

    // ✅ ADMIN: UPDATE REVIEW STATUS
    public function updateStatus(Request $request, ProductReview $review)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review->update([
            'status' => $request->status,
        ]);

        return redirect()->back()
            ->with('success', 'Review status updated');
    }
}
