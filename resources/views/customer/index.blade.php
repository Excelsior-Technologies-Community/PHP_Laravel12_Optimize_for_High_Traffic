@extends('layouts.customer')

@section('content')


{{-- 🔹 TOP ACTIONS --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <form method="GET" action="{{ url('/customer/products') }}" class="w-75">
        <div class="input-group">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   class="form-control"
                   placeholder="Search by name, price, size, color, category...">
            <button class="btn btn-primary">Search</button>
            <a href="{{ url('/customer/products') }}" class="btn btn-secondary">
                Reset
            </a>
        </div>
    </form>

    <!-- <a href="{{ route('cart.index') }}" class="btn btn-outline-primary ms-3">
        🛒 View Cart
    </a>
    <a href="{{ route('customer.orders') }}"
   class="btn btn-outline-secondary">
    My Orders
</a> -->

</div>

{{-- 🔹 FLASH MESSAGES --}}
@if(session('success'))
<div class="alert alert-success text-center">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger text-center">
    {{ session('error') }}
</div>
@endif

{{-- 🔹 PRODUCT GRID --}}
<div class="row">
@forelse($products as $product)
    <div class="col-md-3 mb-4">
        <div class="card h-100 shadow-sm">

            {{-- Product Image --}}
            <img src="{{ asset('images/'.$product->image) }}"
                 class="card-img-top"
                 style="height:200px; object-fit:contain; background:#f8f9fa;">

            <div class="card-body d-flex flex-column">

                <h5 class="card-title">{{ $product->name }}</h5>

                <p class="text-muted small mb-2">
                    {{ \Illuminate\Support\Str::limit($product->details, 50) }}
                </p>

                <p class="fw-bold text-primary mb-3">
                    ₹ {{ $product->price }}
                </p>

                {{--  AUTH CHECK --}}
                @auth('customer')

                {{--  ADD TO CART FORM (LOGGED IN) --}}
                <form action="{{ route('cart.add') }}" method="POST">
                @csrf

                <input type="hidden" name="product_id" value="{{ $product->id }}">

                {{-- Size --}}
                <div class="mb-2">
                    <label class="form-label small">Size</label>
                    <select name="size_id" class="form-select form-select-sm" required>
                        <option value="" disabled selected>Select Size</option>
                        @foreach($product->sizes ?? [] as $sizeId)
                            <option value="{{ $sizeId }}">
                                {{ $sizes[$sizeId] ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Color --}}
                <div class="mb-2">
                    <label class="form-label small">Color</label>
                    <select name="color_id" class="form-select form-select-sm" required>
                        <option value="" disabled selected>Select Color</option>
                        @foreach($product->colors ?? [] as $colorId)
                            <option value="{{ $colorId }}">
                                {{ $colors[$colorId] ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Category --}}
                <div class="mb-2">
                    <label class="form-label small">Category</label>
                    <select name="category_id" class="form-select form-select-sm" required>
                        <option value="" disabled selected>Select Category</option>
                        @foreach($product->categories ?? [] as $catId)
                            <option value="{{ $catId }}">
                                {{ $categories[$catId] ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Quantity --}}
                <div class="mb-3">
                    <label class="form-label small">Quantity</label>
                    <select name="quantity" class="form-select form-select-sm">
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="mt-auto">
                    <button type="submit" class="btn btn-primary w-100">
                        Add to Cart
                    </button>
                </div>

                </form>

                @else

                {{--  NOT LOGGED IN --}}
                <div class="mt-auto">
                    <a href="{{ route('customer.login') }}?redirect={{ url()->current() }}"
                       class="btn btn-outline-primary w-100">
                        Login to Buy
                    </a>
                </div>

                @endauth

            </div>
        </div>
    </div>
@empty
    <div class="col-12 text-center text-muted">
        No products found
    </div>
@endforelse
</div>

{{-- 🔹 PAGINATION --}}
<div class="d-flex justify-content-center mt-4">
    {{ $products->links('pagination::bootstrap-5') }}
</div>

@endsection
