<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ================================
    // LIST (ONLY ACTIVE PRODUCTS)
    // ================================
    public function index(Request $request)
    {
        $search = $request->search;

        $products = Product::where('status', 'active') // ✅ IMPORTANT
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('details', 'like', "%{$search}%")
                      ->orWhere('price', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'asc')   // OLD → NEW
            ->paginate(5)
            ->withQueryString();

        $sizes = Size::pluck('size_name', 'id');
        $colors = Color::pluck('color_name', 'id');
        $categories = Category::pluck('category_name', 'id');

        return view('products.index', compact(
            'products',
            'sizes',
            'colors',
            'categories',
            'search'
        ));
    }

    // ================================
    // CREATE FORM
    // ================================
    public function create()
    {
        return view('products.create', [
            'sizes' => Size::all(),
            'colors' => Color::all(),
            'categories' => Category::all()
        ]);
    }

    // ================================
    // STORE (DEFAULT STATUS = ACTIVE)
    // ================================
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required',
            'details'    => 'required',
            'price'      => 'required|numeric',
            'image'      => 'required|image',
            'sizes'      => 'required|array',
            'colors'     => 'required|array',
            'categories' => 'required|array',
        ]);

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        Product::create([
            'name'       => $request->name,
            'details'    => $request->details,
            'price'      => $request->price,
            'image'      => $imageName,
            'sizes'      => $request->sizes,
            'colors'     => $request->colors,
            'categories' => $request->categories,
            'status'     => 'active', // ✅ DEFAULT
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product added successfully');
    }

    // ================================
    // EDIT FORM
    // ================================
    public function edit(Product $product)
    {
        return view('products.edit', [
            'product' => $product,
            'sizes' => Size::all(),
            'colors' => Color::all(),
            'categories' => Category::all()
        ]);
    }

    // ================================
    // UPDATE
    // ================================
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'       => 'required',
            'details'    => 'required',
            'price'      => 'required|numeric',
            'sizes'      => 'required|array',
            'colors'     => 'required|array',
            'categories' => 'required|array',
            'image'      => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $product->image = $imageName;
        }

        $product->update([
            'name'       => $request->name,
            'details'    => $request->details,
            'price'      => $request->price,
            'sizes'      => $request->sizes,
            'colors'     => $request->colors,
            'categories' => $request->categories,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    // ================================
    // DELETE (SOFT DELETE BY STATUS)
    // ================================
    public function destroy(Product $product)
    {
        $product->update([
            'status' => 'deleted' // 
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
}
