@extends('layouts.admin')

@section('content')

<h2 class="mb-3">Product Variant List</h2>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<form method="GET" action="{{ route('product-variants.index') }}" class="row g-2 mb-3">
    <div class="col-md-4">
        <select name="product_id" class="form-control">
            <option value="">All Products</option>
            @foreach($products as $product)
            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                {{ $product->name }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <button class="btn btn-primary">Filter</button>
        <a href="{{ route('product-variants.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

<a href="{{ route('product-variants.create') }}" class="btn btn-primary mb-3">Add Variant</a>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>SKU</th>
            <th>Size</th>
            <th>Color</th>
            <th>Category</th>
            <th>Image</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Status</th>
            <th width="150">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($variants as $variant)
        <tr>
            <td>{{ $variant->id }}</td>
            <td>{{ $variant->product->name ?? 'N/A' }}</td>
            <td>{{ $variant->sku }}</td>
            <td>{{ $variant->size->size_name ?? 'N/A' }}</td>
            <td>{{ $variant->color->color_name ?? 'N/A' }}</td>
            <td>{{ $variant->category->category_name ?? 'N/A' }}</td>
            <td>
                @if($variant->image)
                    @if(filter_var($variant->image, FILTER_VALIDATE_URL))
                        <img src="{{ $variant->image }}" width="50" class="rounded"
                             onerror="this.src='https://placehold.co/50x50?text=N/A'">
                    @else
                        <img src="{{ asset('images/variants/'.$variant->image) }}" width="50" class="rounded"
                             onerror="this.src='https://placehold.co/50x50?text=N/A'">
                    @endif
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>{{ $variant->price }}</td>
            <td>{{ $variant->stock }}</td>
            <td>
                @if($variant->status == 'active')
                <span class="badge bg-success">Active</span>
                @else
                <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td>
                <a href="{{ route('product-variants.edit', $variant->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('product-variants.destroy', $variant->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="10" class="text-center text-muted">No variants found</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center mt-3">
    {{ $variants->links('pagination::bootstrap-5') }}
</div>

@endsection
