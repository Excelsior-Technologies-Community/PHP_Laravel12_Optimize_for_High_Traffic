<?php

namespace App\Http\Controllers;

use App\Models\SizeGuide;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SizeGuideController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST SIZE GUIDES
    |--------------------------------------------------------------------------
    |
    | Features:
    | - Search
    | - Product filter
    | - Size filter
    | - Sorting
    | - Pagination
    |
    */

    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $productId = $request->input('product_id');
        $sizeId = $request->input('size_id');
        $sort = $request->input('sort', 'newest');

        $query = SizeGuide::with([
            'product',
            'size',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {
            $query->where(function ($q) use ($search) {

                $q->where('description', 'like', "%{$search}%")

                    ->orWhereHas('product', function ($productQuery) use ($search) {
                        $productQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    })

                    ->orWhereHas('size', function ($sizeQuery) use ($search) {
                        $sizeQuery->where(
                            'size_name',
                            'like',
                            "%{$search}%"
                        );
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUCT FILTER
        |--------------------------------------------------------------------------
        */

        if ($productId) {
            $query->where('product_id', $productId);
        }

        /*
        |--------------------------------------------------------------------------
        | SIZE FILTER
        |--------------------------------------------------------------------------
        */

        if ($sizeId) {
            $query->where('size_id', $sizeId);
        }

        /*
        |--------------------------------------------------------------------------
        | SORTING
        |--------------------------------------------------------------------------
        */

        switch ($sort) {

            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;

            case 'product_asc':
                $query->join(
                    'products',
                    'size_guides.product_id',
                    '=',
                    'products.id'
                )
                    ->select('size_guides.*')
                    ->orderBy('products.name', 'asc');
                break;

            case 'product_desc':
                $query->join(
                    'products',
                    'size_guides.product_id',
                    '=',
                    'products.id'
                )
                    ->select('size_guides.*')
                    ->orderBy('products.name', 'desc');
                break;

            case 'size_asc':
                $query->join(
                    'sizes',
                    'size_guides.size_id',
                    '=',
                    'sizes.id'
                )
                    ->select('size_guides.*')
                    ->orderBy('sizes.size_name', 'asc');
                break;

            case 'size_desc':
                $query->join(
                    'sizes',
                    'size_guides.size_id',
                    '=',
                    'sizes.id'
                )
                    ->select('size_guides.*')
                    ->orderBy('sizes.size_name', 'desc');
                break;

            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $sizeGuides = $query
            ->paginate(5)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | FILTER DROPDOWNS
        |--------------------------------------------------------------------------
        */

        $products = Product::orderBy('name', 'asc')->get();

        $sizes = Size::orderBy('size_name', 'asc')->get();

        return view(
            'admin.size-guides.index',
            compact(
                'sizeGuides',
                'products',
                'sizes',
                'search',
                'productId',
                'sizeId',
                'sort'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $products = Product::orderBy('name')->get();

        $sizes = Size::orderBy('size_name')->get();

        return view(
            'admin.size-guides.create',
            compact('products', 'sizes')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'measurements' => 'nullable|array',
            'description' => 'nullable|string|max:5000',
        ]);

        SizeGuide::create($validated);

        return redirect()
            ->route('size-guides.index')
            ->with(
                'success',
                'Size guide added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(SizeGuide $sizeGuide)
    {
        $products = Product::orderBy('name')->get();

        $sizes = Size::orderBy('size_name')->get();

        return view(
            'admin.size-guides.edit',
            compact(
                'sizeGuide',
                'products',
                'sizes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        SizeGuide $sizeGuide
    ) {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'measurements' => 'nullable|array',
            'description' => 'nullable|string|max:5000',
        ]);

        $sizeGuide->update($validated);

        return redirect()
            ->route('size-guides.index')
            ->with(
                'success',
                'Size guide updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE SINGLE SIZE GUIDE
    |--------------------------------------------------------------------------
    */

    public function destroy(SizeGuide $sizeGuide)
    {
        $sizeGuide->delete();

        return redirect()
            ->route('size-guides.index')
            ->with(
                'success',
                'Size guide deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | BULK DELETE
    |--------------------------------------------------------------------------
    |
    | Feature #4
    |
    */

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:size_guides,id',
        ]);

        $count = SizeGuide::whereIn(
            'id',
            $request->ids
        )->delete();

        return redirect()
            ->route('size-guides.index')
            ->with(
                'success',
                "{$count} size guide(s) deleted successfully."
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DUPLICATE
    |--------------------------------------------------------------------------
    |
    | Feature #5
    |
    */

    public function duplicate(SizeGuide $sizeGuide)
    {
        $copy = $sizeGuide->replicate();

        $copy->save();

        return redirect()
            ->route('size-guides.index')
            ->with(
                'success',
                'Size guide duplicated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CSV EXPORT
    |--------------------------------------------------------------------------
    |
    | Feature #6
    |
    | Export respects:
    | - Search
    | - Product filter
    | - Size filter
    | - Sorting
    |
    */

    public function export(Request $request): StreamedResponse
    {
        $search = trim($request->input('search', ''));
        $productId = $request->input('product_id');
        $sizeId = $request->input('size_id');
        $sort = $request->input('sort', 'newest');

        $query = SizeGuide::with([
            'product',
            'size',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                $q->where(
                    'description',
                    'like',
                    "%{$search}%"
                )

                    ->orWhereHas('product', function ($productQuery) use ($search) {

                        $productQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    })

                    ->orWhereHas('size', function ($sizeQuery) use ($search) {

                        $sizeQuery->where(
                            'size_name',
                            'like',
                            "%{$search}%"
                        );
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTERS
        |--------------------------------------------------------------------------
        */

        if ($productId) {
            $query->where(
                'product_id',
                $productId
            );
        }

        if ($sizeId) {
            $query->where(
                'size_id',
                $sizeId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SORT
        |--------------------------------------------------------------------------
        */

        switch ($sort) {

            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;

            case 'product_asc':
                $query->join(
                    'products',
                    'size_guides.product_id',
                    '=',
                    'products.id'
                )
                    ->select('size_guides.*')
                    ->orderBy('products.name', 'asc');
                break;

            case 'product_desc':
                $query->join(
                    'products',
                    'size_guides.product_id',
                    '=',
                    'products.id'
                )
                    ->select('size_guides.*')
                    ->orderBy('products.name', 'desc');
                break;

            case 'size_asc':
                $query->join(
                    'sizes',
                    'size_guides.size_id',
                    '=',
                    'sizes.id'
                )
                    ->select('size_guides.*')
                    ->orderBy('sizes.size_name', 'asc');
                break;

            case 'size_desc':
                $query->join(
                    'sizes',
                    'size_guides.size_id',
                    '=',
                    'sizes.id'
                )
                    ->select('size_guides.*')
                    ->orderBy('sizes.size_name', 'desc');
                break;

            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $sizeGuides = $query->get();

        /*
        |--------------------------------------------------------------------------
        | CSV RESPONSE
        |--------------------------------------------------------------------------
        */

        $filename = 'size-guides-' . now()->format('Y-m-d-H-i-s') . '.csv';

        return response()->streamDownload(
            function () use ($sizeGuides) {

                $handle = fopen('php://output', 'w');

                /*
                |--------------------------------------------------------------------------
                | CSV HEADER
                |--------------------------------------------------------------------------
                */

                fputcsv($handle, [
                    'ID',
                    'Product',
                    'Size',
                    'Measurements',
                    'Description',
                    'Created At',
                ]);

                /*
                |--------------------------------------------------------------------------
                | CSV DATA
                |--------------------------------------------------------------------------
                */

                foreach ($sizeGuides as $guide) {

                    $measurements = '';

                    if (is_array($guide->measurements)) {

                        $parts = [];

                        foreach (
                            $guide->measurements
                            as $key => $value
                        ) {
                            $parts[] =
                                $key . ': ' . $value;
                        }

                        $measurements =
                            implode(' | ', $parts);
                    } else {

                        $measurements =
                            $guide->measurements;
                    }

                    fputcsv($handle, [
                        $guide->id,
                        $guide->product->name ?? 'N/A',
                        $guide->size->size_name ?? 'N/A',
                        $measurements,
                        $guide->description ?? '',
                        optional($guide->created_at)
                            ->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            },
            $filename,
            [
                'Content-Type' =>
                'text/csv; charset=UTF-8',
            ]
        );
    }
}
