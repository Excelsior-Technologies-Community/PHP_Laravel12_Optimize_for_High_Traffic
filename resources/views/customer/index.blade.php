@extends('layouts.customer')

@section('content')

{{-- 🔹 TOP ACTIONS --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <form method="GET" action="{{ url('/customer/products') }}" class="w-75">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Search by name, price, size, color, category...">
            @if(request('brand_id'))
                <input type="hidden" name="brand_id" value="{{ request('brand_id') }}">
            @endif
            <button class="btn btn-primary">Search</button>
            <a href="{{ url('/customer/products') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>

{{-- 🔹 FLASH MESSAGES --}}
@if(session('success'))
<div class="alert alert-success text-center">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger text-center">{{ session('error') }}</div>
@endif

{{-- 📸 BANNERS --}}
@if(isset($banners) && $banners->isNotEmpty())
<div id="bannerCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-inner" style="border-radius:12px; overflow:hidden;">
        @foreach($banners as $i => $banner)
        <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
            @php $src = filter_var($banner->image, FILTER_VALIDATE_URL) ? $banner->image : asset('images/banners/'.$banner->image); @endphp
            @if($banner->link)
                <a href="{{ $banner->link }}">
                    <img src="{{ $src }}" class="d-block w-100" style="max-height:320px; object-fit:cover;"
                         onerror="this.src='https://placehold.co/1200x320?text={{ urlencode($banner->title) }}'">
                </a>
            @else
                <img src="{{ $src }}" class="d-block w-100" style="max-height:320px; object-fit:cover;"
                     onerror="this.src='https://placehold.co/1200x320?text={{ urlencode($banner->title) }}'">
            @endif
            @if($banner->title || $banner->description)
            <div class="carousel-caption d-none d-md-block" style="background:rgba(0,0,0,0.4); border-radius:8px; padding:8px 16px;">
                <h5 class="mb-0">{{ $banner->title }}</h5>
                @if($banner->description)<p class="mb-0 small">{{ $banner->description }}</p>@endif
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @if($banners->count() > 1)
    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
    @endif
</div>
@endif

{{-- 🏪 BRANDS FILTER --}}
@if(isset($brands) && $brands->isNotEmpty())
<div class="mb-4">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ url('/customer/products') }}{{ request('search') ? '?search='.request('search') : '' }}"
           class="btn btn-sm {{ !request('brand_id') ? 'btn-dark' : 'btn-outline-dark' }} rounded-pill">
            All Brands
        </a>
        @foreach($brands as $brand)
        <a href="{{ url('/customer/products') }}?brand_id={{ $brand->id }}{{ request('search') ? '&search='.request('search') : '' }}"
           class="btn btn-sm {{ request('brand_id') == $brand->id ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill d-flex align-items-center gap-1">
            @if($brand->image)
                @php $bImg = filter_var($brand->image, FILTER_VALIDATE_URL) ? $brand->image : asset('images/brands/'.$brand->image); @endphp
                <img src="{{ $bImg }}" style="width:18px;height:18px;object-fit:contain;border-radius:3px;">
            @endif
            {{ $brand->name }}
        </a>
        @endforeach
    </div>
</div>
@endif

{{-- 🏷️ ACTIVE DISCOUNTS SECTION --}}
@if(isset($activeDiscounts) && $activeDiscounts->isNotEmpty())
<div class="mb-4">
    <h4 class="fw-bold mb-3">🏷️ Active Offers</h4>
    <div class="row">
        @foreach($activeDiscounts as $disc)
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:#fff; border-radius:12px;">
                <div class="card-body">
                    <h6 class="fw-bold mb-1">{{ $disc->title }}</h6>
                    <p class="mb-1 fs-5 fw-bold">
                        @if($disc->apply_on === 'percentage')
                            {{ $disc->value }}% OFF
                        @else
                            ₹{{ $disc->value }} OFF
                        @endif
                    </p>
                    <small class="opacity-75">
                        {{ $disc->apply_to === 'all_products' ? 'On All Products' : 'On Selected Products' }}
                        @if($disc->discount_code)
                            | Code: <strong>{{ $disc->discount_code }}</strong>
                        @endif
                    </small>
                    @if($disc->end_date)
                    <p class="mb-0 mt-1"><small class="opacity-75">Valid till: {{ \Carbon\Carbon::parse($disc->end_date)->format('d M Y') }}</small></p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- 🔥 FLASH SALES SECTION --}}
@if($flashSales->isNotEmpty())
<div class="mb-4">
    <h4 class="fw-bold mb-3">🔥 Flash Sales</h4>
    <div class="row">
        @foreach($flashSales as $flash)
        @if($flash->product)
        <div class="col-md-3 mb-3">
            <div class="card h-100 border-danger shadow-sm">
                <div class="position-relative">
                    @if($flash->product->image)
                        @if(filter_var($flash->product->image, FILTER_VALIDATE_URL))
                            <img src="{{ $flash->product->image }}" class="card-img-top" style="height:180px; object-fit:contain; background:#fff3f3;">
                        @else
                            <img src="{{ asset('images/'.$flash->product->image) }}" class="card-img-top" style="height:180px; object-fit:contain; background:#fff3f3;">
                        @endif
                    @else
                        <img src="https://placehold.co/400x400?text=Flash+Sale" class="card-img-top" style="height:180px; object-fit:contain;">
                    @endif
                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">FLASH SALE</span>
                </div>
                <div class="card-body">
                    <h6 class="card-title">{{ $flash->product->name }}</h6>
                    @php
                        $originalPrice = $flash->product->price;
                        $discountedPrice = $flash->discount_type === 'percentage'
                            ? $originalPrice - ($originalPrice * $flash->discount_value / 100)
                            : $originalPrice - $flash->discount_value;
                        $discountedPrice = max($discountedPrice, 0);
                    @endphp
                    <p class="mb-1">
                        <span class="fw-bold text-danger">₹{{ number_format($discountedPrice, 2) }}</span>
                        <span class="text-muted text-decoration-line-through ms-2">₹{{ $originalPrice }}</span>
                    </p>
                    <p class="small text-muted mb-1">
                        {{ $flash->discount_type === 'percentage' ? $flash->discount_value.'% OFF' : '₹'.$flash->discount_value.' OFF' }}
                    </p>
                    <p class="small text-danger mb-0">Ends: {{ $flash->end_date->format('d M Y') }}</p>
                    <p class="small text-muted">{{ $flash->stock - $flash->sold }} left</p>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>
@endif

{{-- 🔹 PRODUCT GRID --}}
<div class="row">
@forelse($products as $product)

    {{-- Variants JSON for JS --}}
    @php
        $variantsData = $product->variants->where('status', 'active')->map(fn($v) => [
            'size_id'   => $v->size_id,
            'color_id'  => $v->color_id,
            'price'     => $v->price,
            'stock'     => $v->stock,
            'sku'       => $v->sku,
        ])->values();
        $hasVariants = $variantsData->isNotEmpty();
    @endphp

    <div class="col-md-3 mb-4">
        <div class="card h-100 shadow-sm">

            {{-- Product Image --}}
            <div class="position-relative">
                @if(filter_var($product->image, FILTER_VALIDATE_URL))
                    <img src="{{ $product->image }}" class="card-img-top"
                         style="height:200px; object-fit:contain; background:#f8f9fa;"
                         onerror="this.src='https://placehold.co/600x600?text=No+Image'">
                @else
                    <img src="{{ asset('images/'.$product->image) }}" class="card-img-top"
                         style="height:200px; object-fit:contain; background:#f8f9fa;"
                         onerror="this.src='https://placehold.co/600x600?text=No+Image'">
                @endif

                {{-- Brand Badge --}}
                @if($product->brand)
                <span class="badge bg-dark position-absolute bottom-0 start-0 m-2" style="font-size:10px;">
                    {{ $product->brand->name }}
                </span>
                @endif

                @auth('customer')
                {{-- Wishlist Button --}}
                @if(in_array($product->id, $wishlistProductIds))
                <form action="{{ route('wishlist.remove.by.product', $product->id) }}" method="POST"
                      class="position-absolute top-0 end-0 m-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light rounded-circle shadow-sm"
                            style="width:32px;height:32px;padding:0;color:#dc3545;" title="Remove from Wishlist">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                        </svg>
                    </button>
                </form>
                @else
                <form action="{{ route('wishlist.add') }}" method="POST"
                      class="position-absolute top-0 end-0 m-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn btn-light rounded-circle shadow-sm"
                            style="width:32px;height:32px;padding:0;" title="Add to Wishlist">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                        </svg>
                    </button>
                </form>
                @endif

                {{-- Compare Button --}}
                <form action="{{ route('compare.add') }}" method="POST"
                      class="position-absolute top-0 start-0 m-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn btn-light rounded-circle shadow-sm"
                            style="width:32px;height:32px;padding:0;" title="Add to Compare">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L7.293 11H1.5a.5.5 0 0 0-.5.5m14-7a.5.5 0 0 1-.5.5H8.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L8.707 4H14.5a.5.5 0 0 1 .5.5"/>
                        </svg>
                    </button>
                </form>
                @endauth
            </div>

            <div class="card-body d-flex flex-column">

                <h6 class="card-title mb-1">
                    {{ $product->name }}
                    @if($product->sizeGuides->isNotEmpty())
                    <a href="{{ route('products.size-guide', $product->id) }}"
                       class="btn btn-outline-secondary btn-sm ms-1 py-0 px-1" title="Size Guide">📏</a>
                    @endif
                </h6>

                <p class="text-muted small mb-1">{{ \Illuminate\Support\Str::limit($product->details, 50) }}</p>

                {{-- Price display — updates via JS if variants exist --}}
                <p class="fw-bold text-primary mb-2" id="price-{{ $product->id }}">
                    ₹ {{ $product->price }}
                </p>

                {{-- Variant stock info --}}
                @if($hasVariants)
                <p class="small mb-2" id="stock-{{ $product->id }}">
                    <span class="text-muted">Select size & color to see stock</span>
                </p>
                @endif

                @auth('customer')

                <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                {{-- Variants JSON embedded --}}
                @if($hasVariants)
                <script>
                    window.variants_{{ $product->id }} = @json($variantsData);
                </script>
                @endif

                {{-- Size --}}
                <div class="mb-2">
                    <label class="form-label small">Size</label>
                    <select name="size_id" class="form-select form-select-sm"
                            id="size-{{ $product->id }}" required
                            @if($hasVariants) onchange="updateVariant({{ $product->id }})" @endif>
                        <option value="" disabled selected>Select Size</option>
                        @foreach($product->sizes ?? [] as $sizeId)
                            <option value="{{ $sizeId }}">{{ $sizes[$sizeId] ?? '' }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Color --}}
                <div class="mb-2">
                    <label class="form-label small">Color</label>
                    <select name="color_id" class="form-select form-select-sm"
                            id="color-{{ $product->id }}" required
                            @if($hasVariants) onchange="updateVariant({{ $product->id }})" @endif>
                        <option value="" disabled selected>Select Color</option>
                        @foreach($product->colors ?? [] as $colorId)
                            <option value="{{ $colorId }}">{{ $colors[$colorId] ?? '' }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Category --}}
                <div class="mb-2">
                    <label class="form-label small">Category</label>
                    <select name="category_id" class="form-select form-select-sm" required>
                        <option value="" disabled selected>Select Category</option>
                        @foreach($product->categories ?? [] as $catId)
                            <option value="{{ $catId }}">{{ $categories[$catId] ?? '' }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Quantity --}}
                <div class="mb-2">
                    <label class="form-label small">Quantity</label>
                    <select name="quantity" class="form-select form-select-sm">
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-sm w-100 mt-auto">Add to Cart</button>
                </form>

                {{-- Review Form --}}
                <hr class="my-2">
                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="mb-1">
                        <select name="rating" class="form-select form-select-sm" required>
                            <option value="">Rate</option>
                            @for($r=5;$r>=1;$r--)
                            <option value="{{ $r }}">{{ $r }} ★</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-1">
                        <textarea name="review" class="form-control form-control-sm" rows="2"
                                  placeholder="Write review (min 10 chars)" required minlength="10"></textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-secondary btn-sm w-100">Submit Review</button>
                </form>

                @else
                <div class="mt-auto">
                    <a href="{{ route('customer.login') }}?redirect={{ url()->current() }}"
                       class="btn btn-outline-primary w-100">Login to Buy</a>
                </div>
                @endauth

            </div>
        </div>
    </div>
@empty
    <div class="col-12 text-center text-muted">No products found</div>
@endforelse
</div>

{{-- 🔹 PAGINATION --}}
<div class="d-flex justify-content-center mt-4">
    {{ $products->links('pagination::bootstrap-5') }}
</div>

{{-- 🔹 RECENTLY VIEWED --}}
@auth('customer')
@if(isset($recentlyViewed) && $recentlyViewed->count() > 0)
<div class="mt-5">
    <h4 class="mb-3 fw-bold">Recently Viewed</h4>
    <div class="row">
        @foreach($recentlyViewed as $rvProduct)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                @if($rvProduct->image)
                    @if(filter_var($rvProduct->image, FILTER_VALIDATE_URL))
                        <img src="{{ $rvProduct->image }}" class="card-img-top" style="height:150px; object-fit:contain; background:#f8f9fa;">
                    @else
                        <img src="{{ asset('images/'.$rvProduct->image) }}" class="card-img-top" style="height:150px; object-fit:contain; background:#f8f9fa;">
                    @endif
                @else
                    <img src="https://placehold.co/600x600?text=No+Image" class="card-img-top" style="height:150px; object-fit:contain; background:#f8f9fa;">
                @endif
                <div class="card-body">
                    <h6 class="card-title">{{ $rvProduct->name }}</h6>
                    <p class="fw-bold text-primary mb-0">₹ {{ $rvProduct->price }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endauth

{{-- 🔹 YOU MAY ALSO LIKE --}}
@if(isset($recommendations) && $recommendations->count() > 0)
<div class="mt-5">
    <h4 class="mb-3 fw-bold">You May Also Like</h4>
    <div class="row">
        @foreach($recommendations as $recProduct)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                @if($recProduct->image)
                    @if(filter_var($recProduct->image, FILTER_VALIDATE_URL))
                        <img src="{{ $recProduct->image }}" class="card-img-top" style="height:150px; object-fit:contain; background:#f8f9fa;">
                    @else
                        <img src="{{ asset('images/'.$recProduct->image) }}" class="card-img-top" style="height:150px; object-fit:contain; background:#f8f9fa;">
                    @endif
                @else
                    <img src="https://placehold.co/600x600?text=No+Image" class="card-img-top" style="height:150px; object-fit:contain; background:#f8f9fa;">
                @endif
                <div class="card-body">
                    <h6 class="card-title">{{ $recProduct->name }}</h6>
                    <p class="fw-bold text-primary mb-0">₹ {{ $recProduct->price }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Variant JS --}}
<script>
function updateVariant(productId) {
    const variants = window['variants_' + productId];
    if (!variants || variants.length === 0) return;

    const sizeId  = parseInt(document.getElementById('size-' + productId).value);
    const colorId = parseInt(document.getElementById('color-' + productId).value);

    const match = variants.find(v => v.size_id === sizeId && v.color_id === colorId);

    const priceEl = document.getElementById('price-' + productId);
    const stockEl = document.getElementById('stock-' + productId);

    if (match) {
        priceEl.textContent = '₹ ' + parseFloat(match.price).toFixed(2);
        if (stockEl) {
            if (match.stock > 0) {
                stockEl.innerHTML = '<span class="text-success fw-semibold">In Stock (' + match.stock + ' left)</span>';
            } else {
                stockEl.innerHTML = '<span class="text-danger fw-semibold">Out of Stock</span>';
            }
        }
    } else {
        if (stockEl) {
            stockEl.innerHTML = '<span class="text-muted">No variant for this combination</span>';
        }
    }
}
</script>

@endsection
