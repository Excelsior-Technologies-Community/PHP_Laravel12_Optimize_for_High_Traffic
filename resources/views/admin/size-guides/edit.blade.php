@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Edit Size Guide</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('size-guides.update', $sizeGuide->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Product</label>
        <select name="product_id" class="form-control">
            <option value="">Select Product</option>
            @foreach($products as $product)
            <option value="{{ $product->id }}" {{ old('product_id', $sizeGuide->product_id) == $product->id ? 'selected' : '' }}>
                {{ $product->name }}
            </option>
            @endforeach
        </select>
        @error('product_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Size</label>
        <select name="size_id" class="form-control">
            <option value="">Select Size</option>
            @foreach($sizes as $size)
            <option value="{{ $size->id }}" {{ old('size_id', $sizeGuide->size_id) == $size->id ? 'selected' : '' }}>
                {{ $size->size_name }}
            </option>
            @endforeach
        </select>
        @error('size_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Measurements (JSON)</label>
        <textarea name="measurements" class="form-control" rows="3">{{ old('measurements', is_array($sizeGuide->measurements) ? json_encode($sizeGuide->measurements) : $sizeGuide->measurements) }}</textarea>
        @error('measurements')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control">{{ old('description', $sizeGuide->description) }}</textarea>
        @error('description')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-primary">Update Size Guide</button>
    <a href="{{ route('size-guides.index') }}" class="btn btn-secondary">Back</a>
</form>

@endsection
