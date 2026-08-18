@extends('layouts.customer')

@section('content')

<h3 class="mb-4 fw-bold">Product Reviews</h3>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title">{{ $product->name }}</h5>
        <p class="text-muted">{{ $product->details }}</p>
    </div>
</div>

@forelse($reviews as $review)
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="mb-1">{{ $review->customer->name ?? 'Customer' }}</h6>
                <div class="text-warning mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $review->rating)
                            ★
                        @else
                            ☆
                        @endif
                    @endfor
                </div>
                <p class="mb-0">{{ $review->review }}</p>
            </div>
            <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
        </div>
    </div>
</div>
@empty
<div class="alert alert-info text-center">
    No reviews yet for this product.
</div>
@endforelse

@endsection
