@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Edit Product Variant</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('product-variants.update', $productVariant->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Product</label>
        <select name="product_id" class="form-control">
            <option value="">Select Product</option>
            @foreach($products as $product)
            <option value="{{ $product->id }}" {{ old('product_id', $productVariant->product_id) == $product->id ? 'selected' : '' }}>
                {{ $product->name }}
            </option>
            @endforeach
        </select>
        @error('product_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">SKU</label>
        <input type="text" name="sku" value="{{ old('sku', $productVariant->sku) }}" class="form-control">
        @error('sku')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Size</label>
        <select name="size_id" class="form-control">
            <option value="">Select Size</option>
            @foreach($sizes as $size)
            <option value="{{ $size->id }}" {{ old('size_id', $productVariant->size_id) == $size->id ? 'selected' : '' }}>
                {{ $size->size_name }}
            </option>
            @endforeach
        </select>
        @error('size_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Color</label>
        <select name="color_id" class="form-control">
            <option value="">Select Color</option>
            @foreach($colors as $color)
            <option value="{{ $color->id }}" {{ old('color_id', $productVariant->color_id) == $color->id ? 'selected' : '' }}>
                {{ $color->color_name }}
            </option>
            @endforeach
        </select>
        @error('color_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-control">
            <option value="">Select Category</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id', $productVariant->category_id) == $category->id ? 'selected' : '' }}>
                {{ $category->category_name }}
            </option>
            @endforeach
        </select>
        @error('category_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Price</label>
        <input type="number" name="price" value="{{ old('price', $productVariant->price) }}" class="form-control">
        @error('price')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" value="{{ old('stock', $productVariant->stock) }}" class="form-control">
        @error('stock')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Current Image</label><br>
        @if($productVariant->image)
            @if(filter_var($productVariant->image, FILTER_VALIDATE_URL))
                <img src="{{ $productVariant->image }}" width="120" class="rounded mb-2"
                     onerror="this.src='https://placehold.co/120x120?text=No+Image'">
            @else
                <img src="{{ asset('images/variants/'.$productVariant->image) }}" width="120" class="rounded mb-2"
                     onerror="this.src='https://placehold.co/120x120?text=No+Image'">
            @endif
        @else
        <span class="text-muted d-block mb-2">No image</span>
        @endif
        <input type="file" name="image" class="form-control mt-2" accept="image/*">
        <small class="text-muted">OR paste an online image URL below (leave blank to keep current)</small>
        <input type="text" name="image_url"
               value="{{ old('image_url', filter_var($productVariant->image, FILTER_VALIDATE_URL) ? $productVariant->image : '') }}"
               class="form-control mt-2"
               placeholder="https://placehold.co/400x400?text=Variant">
        @error('image')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="active" {{ old('status', $productVariant->status) == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $productVariant->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-primary">Update Variant</button>
    <a href="{{ route('product-variants.index') }}" class="btn btn-secondary">Back</a>
</form>

@endsection
