<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    private function clearProductCache(): void
    {
        cache()->forget('filters.sizes');
        cache()->forget('filters.colors');
        cache()->forget('filters.categories');
        cache()->forget('filters.brands');
        for ($page = 1; $page <= 20; $page++) {
            cache()->forget('admin.products.' . md5('' . $page));
            cache()->forget('customer.products.' . md5('' . $page . ''));
        }
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $cacheKey = 'admin.products.' . md5($search . $request->page);
        $products = Cache::remember($cacheKey, 300, function () use ($search) {
            return Product::where('status', 'active')
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('details', 'like', "%{$search}%")
                          ->orWhere('price', 'like', "%{$search}%");
                    });
                })
                ->orderBy('id', 'asc')
                ->paginate(5);
        });

        $sizes      = Cache::remember('filters.sizes', 3600, fn () => Size::pluck('size_name', 'id'));
        $colors     = Cache::remember('filters.colors', 3600, fn () => Color::pluck('color_name', 'id'));
        $categories = Cache::remember('filters.categories', 3600, fn () => Category::pluck('category_name', 'id'));

        return view('products.index', compact('products', 'sizes', 'colors', 'categories', 'search'));
    }

    public function create()
    {
        return view('products.create', [
            'sizes'      => Size::all(),
            'colors'     => Color::all(),
            'categories' => Category::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required',
            'details'    => 'required',
            'price'      => 'required|numeric',
            'sizes'      => 'required|array',
            'colors'     => 'required|array',
            'categories' => 'required|array',
        ]);

        $imageName = null;
        if ($request->hasFile('image_file')) {
            $imageName = time().'.'.$request->image_file->extension();
            $request->image_file->move(public_path('images'), $imageName);
        } elseif ($request->filled('image_url')) {
            $imageName = $request->image_url;
        }

        Product::create([
            'name'       => $request->name,
            'details'    => $request->details,
            'price'      => $request->price,
            'image'      => $imageName,
            'sizes'      => $request->sizes,
            'colors'     => $request->colors,
            'categories' => $request->categories,
            'status'     => 'active',
        ]);

        $this->clearProductCache();

        return redirect()->route('products.index')->with('success', 'Product added successfully');
    }

    public function edit(Product $product)
    {
        return view('products.edit', [
            'product'    => $product,
            'sizes'      => Size::all(),
            'colors'     => Color::all(),
            'categories' => Category::all(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'       => 'required',
            'details'    => 'required',
            'price'      => 'required|numeric',
            'sizes'      => 'required|array',
            'colors'     => 'required|array',
            'categories' => 'required|array',
        ]);

        if ($request->hasFile('image_file')) {
            $imageName = time().'.'.$request->image_file->extension();
            $request->image_file->move(public_path('images'), $imageName);
            $product->image = $imageName;
        } elseif ($request->filled('image_url')) {
            $product->image = $request->image_url;
        }

        $product->update([
            'name'       => $request->name,
            'details'    => $request->details,
            'price'      => $request->price,
            'sizes'      => $request->sizes,
            'colors'     => $request->colors,
            'categories' => $request->categories,
        ]);

        $this->clearProductCache();

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->update(['status' => 'deleted']);

        $this->clearProductCache();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
