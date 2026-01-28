<?php

namespace App\Http\Controllers;


use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Discount;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();
        return view('discounts.index', compact('discounts'));
    }

    public function create()
    {
        $products = Product::where('status', '!=', 'deleted')->get();
        return view('discounts.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'apply_on'      => 'required|in:percentage,fixed',
            'apply_to'      => 'required|in:specific_product,all_products',
            'discount_code' => 'nullable|string|max:50|unique:discounts,discount_code',
            'value'         => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->apply_on === 'percentage' && $value > 100) {
                        $fail('Percentage cannot be more than 100.');
                    }
                }
            ],
            'product_ids'   => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
        ]);

        Discount::create([
            'title'         => $request->title,
            'apply_on'      => $request->apply_on,
            'apply_to'      => $request->apply_to,
            'discount_code' => $request->discount_code,
            'value'         => $request->value,
            'product_ids'   => $request->apply_to === 'specific_product'
                                ? json_encode($request->product_ids)
                                : null,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
        ]);

        return redirect()->route('discounts.index')->with('success', 'Discount added successfully');
    }

    public function edit(Discount $discount)
    {
        $products = Product::where('status', '!=', 'deleted')->get();
        $selectedProducts = $discount->product_ids ? json_decode($discount->product_ids, true) : [];
        return view('discounts.edit', compact('discount', 'products', 'selectedProducts'));
    }

    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'apply_on'      => 'required|in:percentage,fixed',
            'apply_to'      => 'required|in:specific_product,all_products',
            'discount_code' => 'nullable|string|max:50|unique:discounts,discount_code,' . $discount->id,
            'value'         => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->apply_on === 'percentage' && $value > 100) {
                        $fail('Percentage cannot be more than 100.');
                    }
                }
            ],
            'product_ids'   => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
        ]);

        $discount->update([
            'title'         => $request->title,
            'apply_on'      => $request->apply_on,
            'apply_to'      => $request->apply_to,
            'discount_code' => $request->discount_code,
            'value'         => $request->value,
            'product_ids'   => $request->apply_to === 'specific_product'
                                ? json_encode($request->product_ids)
                                : null,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
        ]);

        return redirect()->route('discounts.index')->with('success', 'Discount updated successfully.');
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect()->route('discounts.index')->with('success', 'Discount deleted successfully.');
    }

    public function show(Discount $discount)
    {
        $productIds = $discount->product_ids ? json_decode($discount->product_ids, true) : [];
        $products = Product::whereIn('id', $productIds)->get();
        return view('discounts.show', compact('discount', 'products'));
    }



 /* ==============================
     |   API METHODS (Postman)
     ============================== */
    public function apiIndex()
    {
        $discounts = Discount::all();
        return response()->json([
            'status' => true,
            'data'   => $discounts
        ]);
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'apply_on'      => 'required|in:percentage,fixed',
            'apply_to'      => 'required|in:specific_product,all_products',
            'discount_code' => 'nullable|string|max:50|unique:discounts,discount_code',
            'value'         => 'required|numeric|min:0',
            'product_ids'   => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
        ]);

        $discount = Discount::create([
            'title'         => $request->title,
            'apply_on'      => $request->apply_on,
            'apply_to'      => $request->apply_to,
            'discount_code' => $request->discount_code,
            'value'         => $request->value,
            'product_ids'   => $request->apply_to === 'specific_product'
                                ? json_encode($request->product_ids)
                                : null,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Discount created successfully',
            'data'    => $discount
        ], 201);
    }

    public function apiShow(Discount $discount)
    {
        $productIds = $discount->product_ids ? json_decode($discount->product_ids, true) : [];
        $products   = Product::whereIn('id', $productIds)->get();

        return response()->json([
            'status'   => true,
            'discount' => $discount,
            'products' => $products
        ]);
    }

    public function apiUpdate(Request $request, Discount $discount)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'apply_on'      => 'required|in:percentage,fixed',
            'apply_to'      => 'required|in:specific_product,all_products',
            'discount_code' => 'nullable|string|max:50|unique:discounts,discount_code,' . $discount->id,
            'value'         => 'required|numeric|min:0',
            'product_ids'   => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
        ]);

        $discount->update([
            'title'         => $request->title,
            'apply_on'      => $request->apply_on,
            'apply_to'      => $request->apply_to,
            'discount_code' => $request->discount_code,
            'value'         => $request->value,
            'product_ids'   => $request->apply_to === 'specific_product'
                                ? json_encode($request->product_ids)
                                : null,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Discount updated successfully',
            'data'    => $discount
        ]);
    }

    public function apiDestroy(Discount $discount)
    {
        $discount->delete();
        return response()->json([
            'status'  => true,
            'message' => 'Discount deleted successfully'
        ]);
    }
}


