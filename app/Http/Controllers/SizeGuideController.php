<?php

namespace App\Http\Controllers;

use App\Models\SizeGuide;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeGuideController extends Controller
{
    public function index(Request $request)
    {
        $sizeGuides = SizeGuide::with(['product', 'size'])->paginate(15);

        return view('admin.size-guides.index', compact('sizeGuides'));
    }

    public function create()
    {
        $products = Product::orderBy('name', 'asc')->get();
        $sizes = Size::orderBy('size_name', 'asc')->get();

        return view('admin.size-guides.create', compact('products', 'sizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'measurements' => 'required|json',
            'description' => 'nullable|string',
        ]);

        SizeGuide::create($request->only('product_id', 'size_id', 'measurements', 'description'));

        return redirect()->route('size-guides.index')->with('success', 'Size guide added successfully');
    }

    public function edit(SizeGuide $sizeGuide)
    {
        $products = Product::orderBy('name', 'asc')->get();
        $sizes = Size::orderBy('size_name', 'asc')->get();

        return view('admin.size-guides.edit', compact('sizeGuide', 'products', 'sizes'));
    }

    public function update(Request $request, SizeGuide $sizeGuide)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'measurements' => 'required|json',
            'description' => 'nullable|string',
        ]);

        $sizeGuide->update($request->only('product_id', 'size_id', 'measurements', 'description'));

        return redirect()->route('size-guides.index')->with('success', 'Size guide updated successfully');
    }

    public function destroy(SizeGuide $sizeGuide)
    {
        $sizeGuide->delete();

        return redirect()->route('size-guides.index')->with('success', 'Size guide deleted successfully');
    }
}
