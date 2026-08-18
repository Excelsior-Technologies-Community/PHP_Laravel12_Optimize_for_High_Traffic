@extends('layouts.customer')

@section('content')

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold mb-0">Offers & Discounts</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($discounts->isEmpty())
                <div class="text-center py-5">
                    <div class="text-muted mb-3">
                        <svg class="mx-auto d-block" width="80" height="80" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <h4 class="fw-bold mb-2">No Active Discounts</h4>
                    <p class="text-muted">Check back later for exciting offers!</p>
                    <a href="{{ route('customer.products') }}" class="btn btn-primary mt-3">Continue Shopping</a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($discounts as $discount)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge bg-{{ $discount->apply_on === 'percentage' ? 'success' : 'warning' }} fs-6">
                                            {{ $discount->apply_on === 'percentage' ? $discount->value . '% OFF' : '₹' . number_format($discount->value) . ' OFF' }}
                                        </span>
                                        <small class="text-muted">{{ $discount->apply_to === 'all_products' ? 'All Products' : 'Selected Products' }}</small>
                                    </div>

                                    <h5 class="card-title fw-bold mb-2">{{ $discount->title }}</h5>
                                    
                                    @if($discount->discount_code)
                                        <div class="mb-3 p-2 bg-light rounded text-center">
                                            <span class="fw-bold text-primary">{{ $discount->discount_code }}</span>
                                            <small class="text-muted d-block">Use this code at checkout</small>
                                        </div>
                                    @endif

                                    <div class="d-flex gap-2 mb-3">
                                        @if($discount->start_date)
                                            <small class="text-muted">
                                                <svg class="me-1" width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 16H5V10h14v10z"/></svg>
                                                Starts: {{ \Carbon\Carbon::parse($discount->start_date)->format('M d, Y') }}
                                            </small>
                                        @endif
                                        @if($discount->end_date)
                                            <small class="text-muted">
                                                <svg class="me-1" width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 16H5V10h14v10z"/></svg>
                                                Ends: {{ \Carbon\Carbon::parse($discount->end_date)->format('M d, Y') }}
                                            </small>
                                        @endif
                                    </div>

                                    <a href="{{ route('customer.discounts.show', $discount) }}" class="btn btn-outline-primary w-100">View Details</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $discounts->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.hover-shadow:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
    transform: translateY(-2px);
}
.transition {
    transition: all 0.3s ease;
}
</style>

@endsection