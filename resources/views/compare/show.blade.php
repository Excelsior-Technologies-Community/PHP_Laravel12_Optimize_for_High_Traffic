@extends('layouts.customer')

@section('content')

<h3 class="mb-4 fw-bold">Compare Products</h3>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($products->count() > 0)
<div class="d-flex justify-content-end mb-3">
    <form action="{{ route('compare.clear') }}" method="POST">
        @csrf
        @method('DELETE')
        <button class="btn btn-outline-danger btn-sm">
            Clear Comparison
        </button>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>Feature</th>
                @foreach($products as $product)
                <th class="text-center">
                    @if($product->image)
                        @if(filter_var($product->image, FILTER_VALIDATE_URL))
                            <img src="{{ $product->image }}" class="mb-2" style="height:120px; object-fit:contain; background:#f8f9fa;">
                        @else
                            <img src="{{ asset('images/'.$product->image) }}" class="mb-2" style="height:120px; object-fit:contain; background:#f8f9fa;">
                        @endif
                    @else
                        <img src="https://placehold.co/600x600?text=No+Image" class="mb-2" style="height:120px; object-fit:contain; background:#f8f9fa;">
                    @endif
                    <br>
                    {{ $product->name }}
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Price</strong></td>
                @foreach($products as $product)
                <td class="text-center fw-bold text-primary">₹ {{ $product->price }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Details</strong></td>
                @foreach($products as $product)
                <td>{{ $product->details }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Sizes</strong></td>
                @foreach($products as $product)
                <td>
                    @foreach($product->sizes ?? [] as $sizeId)
                        <span class="badge bg-secondary me-1">{{ $sizes[$sizeId] ?? '' }}</span>
                    @endforeach
                </td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Colors</strong></td>
                @foreach($products as $product)
                <td>
                    @foreach($product->colors ?? [] as $colorId)
                        <span class="badge bg-info text-dark me-1">{{ $colors[$colorId] ?? '' }}</span>
                    @endforeach
                </td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Categories</strong></td>
                @foreach($products as $product)
                <td>
                    @foreach($product->categories ?? [] as $catId)
                        <span class="badge bg-light text-dark border me-1">{{ $categories[$catId] ?? '' }}</span>
                    @endforeach
                </td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Actions</strong></td>
                @foreach($products as $product)
                <td class="text-center">
                    <form action="{{ route('compare.remove', $product->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm me-1">Remove</button>
                    </form>
                    <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="size_id" value="{{ $product->sizes[0] ?? '' }}">
                        <input type="hidden" name="color_id" value="{{ $product->colors[0] ?? '' }}">
                        <input type="hidden" name="category_id" value="{{ $product->categories[0] ?? '' }}">
                        <input type="hidden" name="quantity" value="1">
                        <button class="btn btn-primary btn-sm">Add to Cart</button>
                    </form>
                </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

@else
<div class="alert alert-info text-center">
    <h5>No products to compare</h5>
    <p class="mb-0">Browse products and add up to 3 to compare them side by side.</p>
    <a href="{{ route('customer.products') }}" class="btn btn-primary mt-3">Browse Products</a>
</div>
@endif

@endsection
