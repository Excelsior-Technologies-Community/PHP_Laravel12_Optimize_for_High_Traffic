<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerDiscountController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        
        $discounts = Discount::where('status', 'active')
            ->where(function ($query) use ($now) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $now);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('customer.discounts.index', compact('discounts'));
    }

    public function show(Discount $discount)
    {
        $now = Carbon::now();
        
        if ($discount->status !== 'active') {
            abort(404);
        }
        
        if ($discount->start_date && $discount->start_date > now()) {
            abort(404);
        }
        
        if ($discount->end_date && $discount->end_date < now()) {
            abort(404);
        }

        $productIds = $discount->product_ids ?? [];
        $products = Product::whereIn('id', $productIds)
            ->where('status', 'active')
            ->get();

        return view('customer.discounts.show', compact('discount', 'products'));
    }
}