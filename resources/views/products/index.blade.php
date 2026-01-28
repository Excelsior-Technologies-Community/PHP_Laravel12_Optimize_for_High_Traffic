@extends('layouts.admin')

@section('content')

{{-- 🔹 HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h2 class="mb-0">Product List</h2>

    <!-- <div class="d-flex gap-2">

     <a href="{{ route('admin.orders.index') }}" class="btn btn-dark">
            All Orders
        </a>
        <a href="{{ route('discounts.index') }}" class="btn btn-secondary">Discounts</a>
        <a href="{{ route('sizes.index') }}" class="btn btn-info">
           Sizes
        </a>

        <a href="{{ route('colors.index') }}" class="btn btn-warning">
           Colors
        </a>

        <a href="{{ route('categories.index') }}" class="btn btn-success">
           Categories
        </a>

        <a href="{{ route('products.create') }}" class="btn btn-primary">
            Add Product
        </a>
    </div> -->
</div>


{{-- 🔹 SUCCESS MESSAGE --}}
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

{{-- 🔹 SEARCH FORM --}}
<form method="GET" action="{{ route('products.index') }}" class="mb-3">
    <div class="row g-2">
        <div class="col-md-4">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   class="form-control"
                   placeholder="Search by name, details, price...">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary">Search</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                Reset
            </a>
        </div>
    </div>
</form>

{{-- 🔹 PRODUCT TABLE --}}
<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Details</th>
            <th>Price</th>
            <th>Sizes</th>
            <th>Colors</th>
            <th>Categories</th>
            <th>Image</th>
            <th width="180">Action</th>
        </tr>
    </thead>

    <tbody>
        @forelse($products as $product)
        <tr>
            {{-- ✅ SERIAL NO (pagination safe) --}}
            <td>
                {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
            </td>

            <td>{{ $product->name }}</td>
            <td>{{ $product->details }}</td>
            <td>₹ {{ $product->price }}</td>

            {{-- ✅ Sizes --}}
            <td>
                @forelse($product->sizes ?? [] as $sizeId)
                    <span class="badge bg-info text-dark me-1">
                        {{ $sizes[$sizeId] ?? 'N/A' }}
                    </span>
                @empty
                    <span class="text-muted">-</span>
                @endforelse
            </td>

            {{-- ✅ Colors --}}
            <td>
                @forelse($product->colors ?? [] as $colorId)
                    <span class="badge bg-warning text-dark me-1">
                        {{ $colors[$colorId] ?? 'N/A' }}
                    </span>
                @empty
                    <span class="text-muted">-</span>
                @endforelse
            </td>

            {{-- ✅ Categories --}}
            <td>
                @forelse($product->categories ?? [] as $catId)
                    <span class="badge bg-success me-1">
                        {{ $categories[$catId] ?? 'N/A' }}
                    </span>
                @empty
                    <span class="text-muted">-</span>
                @endforelse
            </td>

            <td>
                <img src="{{ asset('images/'.$product->image) }}"
                     width="80"
                     class="rounded">
            </td>

            <td>
                <a href="{{ route('products.edit',$product->id) }}"
                   class="btn btn-warning btn-sm">
                    Edit
                </a>

                <form action="{{ route('products.destroy',$product->id) }}"
                      method="POST"
                      class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure?')">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" class="text-center text-muted">
                No products found
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- 🔹 PAGINATION --}}
<div class="d-flex justify-content-center mt-3">
    {{ $products->links('pagination::bootstrap-5') }}
</div>

@endsection
