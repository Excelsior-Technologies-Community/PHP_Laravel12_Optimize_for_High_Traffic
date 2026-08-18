<?php

namespace App\Http\Controllers;

use App\Models\SizeGuide;
use App\Models\Product;
use Illuminate\Http\Request;

class CustomerSizeGuideController extends Controller
{
    public function show(Product $product)
    {
        $sizeGuides = SizeGuide::with('size')
            ->where('product_id', $product->id)
            ->get();

        $sizes = \App\Models\Size::pluck('size_name', 'id');

        return view('customer.size-guide.show', compact('product', 'sizeGuides', 'sizes'));
    }
}
