<?php

namespace App\Http\Controllers;

use App\Models\ProductComparison;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductComparisonController extends Controller
{
    // ⚖️ ADD TO COMPARISON
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $sessionId = Session::getId();
        $customerId = auth('customer')->id();

        $comparison = ProductComparison::where('session_id', $sessionId)
            ->when($customerId, function ($query) use ($customerId) {
                $query->orWhere('customer_id', $customerId);
            })
            ->first();

        if (!$comparison) {
            $comparison = ProductComparison::create([
                'session_id'  => $sessionId,
                'customer_id' => $customerId,
                'product_ids' => [$request->product_id],
            ]);
        } else {
            $productIds = $comparison->product_ids ?? [];

            if (count($productIds) >= 3) {
                return redirect()->back()
                    ->with('error', 'You can compare maximum 3 products');
            }

            if (!in_array($request->product_id, $productIds)) {
                $productIds[] = $request->product_id;
                $comparison->update([
                    'product_ids' => $productIds,
                ]);
            }
        }

        return redirect()->back()
            ->with('success', 'Product added to comparison');
    }

    // ❌ REMOVE FROM COMPARISON
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $sessionId = Session::getId();
        $customerId = auth('customer')->id();

        $comparison = ProductComparison::where('session_id', $sessionId)
            ->when($customerId, function ($query) use ($customerId) {
                $query->orWhere('customer_id', $customerId);
            })
            ->first();

        if ($comparison) {
            $productIds = $comparison->product_ids ?? [];
            $productIds = array_values(array_filter($productIds, fn ($id) => $id != $request->product_id));

            if (empty($productIds)) {
                $comparison->delete();
            } else {
                $comparison->update([
                    'product_ids' => $productIds,
                ]);
            }
        }

        return redirect()->back()
            ->with('success', 'Product removed from comparison');
    }

    // 🗑️ CLEAR COMPARISON
    public function clear()
    {
        $sessionId = Session::getId();
        $customerId = auth('customer')->id();

        ProductComparison::where('session_id', $sessionId)
            ->when($customerId, function ($query) use ($customerId) {
                $query->orWhere('customer_id', $customerId);
            })
            ->delete();

        return redirect()->back()
            ->with('success', 'Comparison cleared');
    }

    // 📋 SHOW COMPARISON
    public function show()
    {
        $sessionId = Session::getId();
        $customerId = auth('customer')->id();

        $comparison = ProductComparison::where('session_id', $sessionId)
            ->when($customerId, function ($query) use ($customerId) {
                $query->orWhere('customer_id', $customerId);
            })
            ->first();

        $productIds = $comparison ? ($comparison->product_ids ?? []) : [];
        $products = Product::whereIn('id', $productIds)->get();

        return view('compare.show', compact('products'));
    }
}
