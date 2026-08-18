<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(Request $request)
    {
        $productId = $request->product_id;

        $products = Product::orderBy('name', 'asc')->get();

        $variants = ProductVariant::with(['product', 'size', 'color', 'category'])
            ->when($productId, function ($query, $productId) {
                $query->where('product_id', $productId);
            })
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.product-variants.index', compact('variants', 'productId', 'products'));
    }

    public function create()
    {
        $products = Product::orderBy('name', 'asc')->get();
        $sizes = Size::orderBy('size_name', 'asc')->get();
        $colors = Color::orderBy('color_name', 'asc')->get();
        $categories = Category::orderBy('category_name', 'asc')->get();

        return view('admin.product-variants.create', compact('products', 'sizes', 'colors', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|string|max:255|unique:product_variants,sku',
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->only(['sku', 'product_id', 'size_id', 'color_id', 'category_id', 'price', 'stock', 'status']);

        if ($request->hasFile('image')) {
            $data['image'] = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/variants'), $data['image']);
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        ProductVariant::create($data);

        return redirect()->route('product-variants.index')->with('success', 'Variant added successfully');
    }

    public function edit(ProductVariant $productVariant)
    {
        $products = Product::orderBy('name', 'asc')->get();
        $sizes = Size::orderBy('size_name', 'asc')->get();
        $colors = Color::orderBy('color_name', 'asc')->get();
        $categories = Category::orderBy('category_name', 'asc')->get();

        return view('admin.product-variants.edit', compact('productVariant', 'products', 'sizes', 'colors', 'categories'));
    }

    public function update(Request $request, ProductVariant $productVariant)
    {
        $request->validate([
            'sku' => 'required|string|max:255|unique:product_variants,sku,' . $productVariant->id,
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->only(['sku', 'product_id', 'size_id', 'color_id', 'category_id', 'price', 'stock', 'status']);

        if ($request->hasFile('image')) {
            $data['image'] = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/variants'), $data['image']);
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        $productVariant->update($data);

        return redirect()->route('product-variants.index')->with('success', 'Variant updated successfully');
    }

    public function destroy(ProductVariant $productVariant)
    {
        $productVariant->delete();

        return redirect()->route('product-variants.index')->with('success', 'Variant deleted successfully');
    }
}
