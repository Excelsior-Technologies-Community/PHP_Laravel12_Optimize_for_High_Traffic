<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index(Request $request)
    {
        $flashSales = FlashSale::with(['product', 'variant'])->paginate(15);

        return view('admin.flash-sales.index', compact('flashSales'));
    }

    public function create()
    {
        $products = Product::orderBy('name', 'asc')->get();
        $variants = ProductVariant::with('product')->orderBy('id', 'desc')->get();

        return view('admin.flash-sales.create', compact('products', 'variants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'stock' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        FlashSale::create([
            'product_id' => $request->product_id,
            'product_variant_id' => $request->product_variant_id,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'stock' => $request->stock,
            'status' => $request->status,
        ]);

        return redirect()->route('flash-sales.index')->with('success', 'Flash sale created successfully');
    }

    public function edit(FlashSale $flashSale)
    {
        $products = Product::orderBy('name', 'asc')->get();
        $variants = ProductVariant::with('product')->orderBy('id', 'desc')->get();

        return view('admin.flash-sales.edit', compact('flashSale', 'products', 'variants'));
    }

    public function update(Request $request, FlashSale $flashSale)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'stock' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        $flashSale->update([
            'product_id' => $request->product_id,
            'product_variant_id' => $request->product_variant_id,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'stock' => $request->stock,
            'status' => $request->status,
        ]);

        return redirect()->route('flash-sales.index')->with('success', 'Flash sale updated successfully');
    }

    public function destroy(FlashSale $flashSale)
    {
        $flashSale->delete();

        return redirect()->route('flash-sales.index')->with('success', 'Flash sale deleted successfully');
    }

    public function toggleStatus(FlashSale $flashSale)
    {
        $flashSale->update([
            'status' => $flashSale->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Flash sale status updated');
    }
}
