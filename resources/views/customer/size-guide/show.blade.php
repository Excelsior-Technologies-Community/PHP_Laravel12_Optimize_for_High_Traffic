@extends('layouts.customer')

@section('content')

<h3 class="mb-4 fw-bold">Size Guide - {{ $product->name }}</h3>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($sizeGuides->isNotEmpty())
@foreach($sizeGuides as $sizeGuide)
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="text-center mb-4">
            @if($product->image)
                @if(filter_var($product->image, FILTER_VALIDATE_URL))
                    <img src="{{ $product->image }}" class="img-fluid rounded" style="height:150px; object-fit:contain; background:#f8f9fa;">
                @else
                    <img src="{{ asset('images/'.$product->image) }}" class="img-fluid rounded" style="height:150px; object-fit:contain; background:#f8f9fa;">
                @endif
            @else
                <img src="https://placehold.co/600x600?text=No+Image" class="img-fluid rounded" style="height:150px; object-fit:contain; background:#f8f9fa;">
            @endif
            <h5 class="mt-3">{{ $product->name }}</h5>
            <p class="text-muted">{{ $product->details }}</p>
        </div>

        @if($sizeGuide->description)
        <div class="alert alert-info">
            {{ $sizeGuide->description }}
        </div>
        @endif

        <h5 class="mt-4 mb-3">Measurements</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Size</th>
                        <th>Chest</th>
                        <th>Waist</th>
                        <th>Length</th>
                        <th>Shoulder</th>
                        <th>Sleeve</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>{{ $sizeGuide->size->size_name ?? 'N/A' }}</strong></td>
                        <td>{{ $sizeGuide->measurements['chest'] ?? '-' }}</td>
                        <td>{{ $sizeGuide->measurements['waist'] ?? '-' }}</td>
                        <td>{{ $sizeGuide->measurements['length'] ?? '-' }}</td>
                        <td>{{ $sizeGuide->measurements['shoulder'] ?? '-' }}</td>
                        <td>{{ $sizeGuide->measurements['sleeve'] ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endforeach
@else
<div class="alert alert-info text-center">
    Size guide not available for this product.
</div>
@endif

@endsection
