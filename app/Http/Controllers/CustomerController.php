<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // CUSTOMER PRODUCT LIST + SEARCH + PAGINATION
public function index(Request $request)
{
    $search = $request->search;

    // 🔹 NAME se ID nikalna
    $sizeIds = Size::where('size_name', 'like', "%{$search}%")
                    ->pluck('id')
                    ->toArray();

    $colorIds = Color::where('color_name', 'like', "%{$search}%")
                     ->pluck('id')
                     ->toArray();

    $categoryIds = Category::where('category_name', 'like', "%{$search}%")
                            ->pluck('id')
                            ->toArray();

    // 🔹 PRODUCT SEARCH
     $products = Product::where('status', 'active')
        ->when($search, function ($query) use (
            $search,
            $sizeIds,
            $colorIds,
            $categoryIds
        ) {
            $query->where(function ($q) use (
                $search,
                $sizeIds,
                $colorIds,
                $categoryIds
            ) {

                // ✅ Product fields
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%")
                  ->orWhere('price', 'like', "%{$search}%");

                // ✅ Size NAME search
                foreach ($sizeIds as $id) {
                    $q->orWhereJsonContains('sizes', $id);
                }

                // ✅ Color NAME search
                foreach ($colorIds as $id) {
                    $q->orWhereJsonContains('colors', $id);
                }

                // ✅ Category NAME search
                foreach ($categoryIds as $id) {
                    $q->orWhereJsonContains('categories', $id);
                }
            });
        })
        ->orderBy('id', 'asc')   // ✅ OLD → NEW (new record niche)
        ->paginate(8)
        ->withQueryString();

    // 🔹 Mapping for view
    $sizes = Size::pluck('size_name', 'id');
    $colors = Color::pluck('color_name', 'id');
    $categories = Category::pluck('category_name', 'id');

    // 🔹 CUSTOMER AUTH INFO
    $isCustomerLoggedIn = auth('customer')->check();
    $customer = auth('customer')->user();

    return view('customer.index', compact(
        'products',
        'sizes',
        'colors',
        'categories',
        'isCustomerLoggedIn',
        'customer'
    ));
}

}
