@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Edit Flash Sale</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('flash-sales.update', $flashSale->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Product</label>
        <select name="product_id" class="form-control">
            <option value="">Select Product</option>
            @foreach($products as $product)
            <option value="{{ $product->id }}" {{ old('product_id', $flashSale->product_id) == $product->id ? 'selected' : '' }}>
                {{ $product->name }}
            </option>
            @endforeach
        </select>
        @error('product_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Product Variant</label>
        <select name="product_variant_id" class="form-control">
            <option value="">Select Variant</option>
            @foreach($variants as $variant)
            <option value="{{ $variant->id }}" {{ old('product_variant_id', $flashSale->product_variant_id) == $variant->id ? 'selected' : '' }}>
                {{ $variant->sku }}
            </option>
            @endforeach
        </select>
        @error('product_variant_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Discount Type</label>
        <select name="discount_type" class="form-control">
            <option value="percentage" {{ old('discount_type', $flashSale->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage</option>
            <option value="fixed" {{ old('discount_type', $flashSale->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed</option>
        </select>
        @error('discount_type')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Discount Value</label>
        <input type="number" name="discount_value" value="{{ old('discount_value', $flashSale->discount_value) }}" class="form-control">
        @error('discount_value')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" value="{{ old('start_date', $flashSale->start_date ? $flashSale->start_date->format('Y-m-d') : '') }}" class="form-control">
        @error('start_date')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" value="{{ old('end_date', $flashSale->end_date ? $flashSale->end_date->format('Y-m-d') : '') }}" class="form-control">
        @error('end_date')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" value="{{ old('stock', $flashSale->stock) }}" class="form-control">
        @error('stock')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="active" {{ old('status', $flashSale->status) == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $flashSale->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-primary">Update Flash Sale</button>
    <a href="{{ route('flash-sales.index') }}" class="btn btn-secondary">Back</a>
</form>

@endsection
