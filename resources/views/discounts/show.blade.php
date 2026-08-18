@extends('layouts.admin')

@section('content')

<h2 class="mb-3">Discount Details</h2>

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

<div class="card shadow-sm">
    <div class="card-body">
        <h4>{{ $discount->title }}</h4>
        <p><strong>Type:</strong> {{ ucfirst($discount->apply_on) }}</p>
        <p><strong>Value:</strong> {{ $discount->value }}</p>
        <p><strong>Apply To:</strong> {{ ucfirst(str_replace('_', ' ', $discount->apply_to)) }}</p>
        <p><strong>Code:</strong> {{ $discount->discount_code ?? '-' }}</p>
        <p><strong>Start Date:</strong> {{ $discount->start_date ?? '-' }}</p>
        <p><strong>End Date:</strong> {{ $discount->end_date ?? '-' }}</p>

        @if($discount->apply_to == 'specific_product' && $products->count() > 0)
            <h5 class="mt-3">Products</h5>
            <ul>
                @foreach($products as $product)
                    <li>{{ $product->name }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<a href="{{ route('discounts.index') }}" class="btn btn-secondary mt-3">Back</a>

@endsection
