<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;

        $reviews = ProductReview::with(['product', 'customer'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.reviews.index', compact('reviews', 'status'));
    }

    public function updateStatus(Request $request, ProductReview $review)
    {
        $request->validate([
            'status'     => 'required|in:approved,pending,rejected',
            'admin_note' => 'nullable|string',
        ]);

        $review->update([
            'status'     => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('success', 'Review status updated');
    }
}
