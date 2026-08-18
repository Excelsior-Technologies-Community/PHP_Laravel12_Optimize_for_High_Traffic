<?php

namespace App\Http\Controllers;

use App\Models\ProductTag;
use Illuminate\Http\Request;

class ProductTagController extends Controller
{
    public function index(Request $request)
    {
        $tags = ProductTag::with('products')->paginate(15);

        return view('admin.product-tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.product-tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_tags,slug',
        ]);

        ProductTag::create($request->only('name', 'slug'));

        return redirect()->route('product-tags.index')->with('success', 'Tag added successfully');
    }

    public function edit(ProductTag $productTag)
    {
        return view('admin.product-tags.edit', compact('productTag'));
    }

    public function update(Request $request, ProductTag $productTag)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_tags,slug,' . $productTag->id,
        ]);

        $productTag->update($request->only('name', 'slug'));

        return redirect()->route('product-tags.index')->with('success', 'Tag updated successfully');
    }

    public function destroy(ProductTag $productTag)
    {
        $productTag->delete();

        return redirect()->route('product-tags.index')->with('success', 'Tag deleted successfully');
    }
}
