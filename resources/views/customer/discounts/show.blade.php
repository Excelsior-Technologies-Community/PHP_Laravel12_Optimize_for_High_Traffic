@extends('layouts.customer')

@section('content')

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <span class="badge bg-{{ $discount->apply_on === 'percentage' ? 'success' : 'warning' }} fs-5 mb-2">
                                {{ $discount->apply_on === 'percentage' ? $discount->value . '% OFF' : '₹' . number_format($discount->value) . ' OFF' }}
                            </span>
                            <h1 class="h2 fw-bold mb-2">{{ $discount->title }}</h1>
                            <div class="d-flex gap-3 text-muted small">
                                <span>
                                    <svg class="me-1" width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                    {{ $discount->apply_to === 'all_products' ? 'Valid on All Products' : 'Valid on Selected Products' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($discount->discount_code)
                        <div class="alert alert-light border p-4 mb-4 text-center">
                            <h5 class="mb-2">Use Coupon Code</h5>
                            <div class="d-flex justify-content-center gap-2">
                                <code class="fs-4 fw-bold text-primary px-3 py-2 bg-white rounded border">{{ $discount->discount_code }}</code>
                                <button class="btn btn-sm btn-outline-primary" onclick="copyCode('{{ $discount->discount_code }}')">
                                    <svg class="me-1" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
                                    Copy
                                </button>
                            </div>
                        </div>
                    @endif

                    <div class="row mb-4">
                        @if($discount->start_date)
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-1">
                                        <svg class="me-1" width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 16H5V10h14v10z"/></svg>
                                        <strong>Valid From</strong>
                                    </small>
                                    <div class="fw-medium">{{ \Carbon\Carbon::parse($discount->start_date)->format('M d, Y h:i A') }}</div>
                                </div>
                            </div>
                        @endif
                        @if($discount->end_date)
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-1">
                                        <svg class="me-1" width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 16H5V10h14v10z"/></svg>
                                        <strong>Valid Until</strong>
                                    </small>
                                    <div class="fw-medium text-danger">{{ \Carbon\Carbon::parse($discount->end_date)->format('M d, Y h:i A') }}</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($discount->description)
                        <div class="mb-4 p-4 bg-light rounded">
                            <h6 class="fw-bold mb-2">Description</h6>
                            <p class="mb-0 text-muted">{{ $discount->description }}</p>
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        <a href="{{ route('customer.products') }}" class="btn btn-primary flex-grow-1">
                            <svg class="me-2" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                            Shop Now
                        </a>
                        <a href="{{ route('customer.discounts.index') }}" class="btn btn-outline-secondary">Back to Offers</a>
                    </div>
                </div>
            </div>

            @if($products->count() > 0)
                <div class="mt-5">
                    <h4 class="fw-bold mb-4">Applicable Products</h4>
                    <div class="row g-4">
                        @foreach($products as $product)
                            <div class="col-md-4 col-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    @if($product->image)
                                        <img src="{{ filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('images/'.$product->image) }}" 
                                             class="card-img-top" 
                                             style="height: 200px; object-fit: cover;"
                                             onerror="this.src='https://placehold.co/400x300?text=No+Image'">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <svg class="mx-auto text-muted" width="60" height="60" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-5l-3-3zM5.54 16.46l2.45-2.45L14.21 17H5.54z"/></svg>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <h6 class="card-title mb-1">{{ $product->name }}</h6>
                                        <p class="text-primary fw-bold mb-0">₹ {{ number_format($product->price, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@section('scripts')
<script>
    function copyCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            alert('Coupon code copied: ' + code);
        });
    }
</script>
@endsection