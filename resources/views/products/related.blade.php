@extends('layouts.customer')

@section('content')
<div class="mt-5">
    <h4 class="mb-3 fw-bold">Related Products</h4>
    <div class="row">
        @forelse($relatedProducts as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                @if($product->image)
                    @if(filter_var($product->image, FILTER_VALIDATE_URL))
                        <img src="{{ $product->image }}" class="card-img-top" style="height:150px; object-fit:contain; background:#f8f9fa;">
                    @else
                        <img src="{{ asset('images/'.$product->image) }}" class="card-img-top" style="height:150px; object-fit:contain; background:#f8f9fa;">
                    @endif
                @else
                    <img src="https://placehold.co/600x600?text=No+Image" class="card-img-top" style="height:150px; object-fit:contain; background:#f8f9fa;">
                @endif
                <div class="card-body">
                    <h6 class="card-title">{{ $product->name }}</h6>
                    <p class="fw-bold text-primary mb-0">₹ {{ $product->price }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted">No related products found</div>
        @endforelse
    </div>
</div>
@endsection
