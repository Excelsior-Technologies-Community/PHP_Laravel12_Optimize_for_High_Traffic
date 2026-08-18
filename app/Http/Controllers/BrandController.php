<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $brands = Brand::with('products')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->paginate(15)->withQueryString();

        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:brands,slug',
            'image_file'  => 'nullable|image|max:2048',
            'image_url'   => 'nullable|url',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,inactive',
        ]);

        $image = null;
        if ($request->hasFile('image_file')) {
            $image = time() . '.' . $request->image_file->extension();
            $request->image_file->move(public_path('images/brands'), $image);
        } elseif ($request->filled('image_url')) {
            $image = $request->image_url;
        }

        Brand::create([
            'name'        => $request->name,
            'slug'        => $request->slug,
            'image'       => $image,
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        return redirect()->route('brands.index')->with('success', 'Brand added successfully');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:brands,slug,' . $brand->id,
            'image_file'  => 'nullable|image|max:2048',
            'image_url'   => 'nullable|url',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,inactive',
        ]);

        $image = $brand->image;
        if ($request->hasFile('image_file')) {
            $image = time() . '.' . $request->image_file->extension();
            $request->image_file->move(public_path('images/brands'), $image);
        } elseif ($request->filled('image_url')) {
            $image = $request->image_url;
        }

        $brand->update([
            'name'        => $request->name,
            'slug'        => $request->slug,
            'image'       => $image,
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully');
    }
}
